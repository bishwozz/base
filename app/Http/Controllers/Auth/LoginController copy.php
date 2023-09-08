<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Utils\DateHelper;
use App\Helpers\SmsHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Base\Helpers\SessionActivityLog;
use App\Http\Controllers\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    protected $data = []; // the information we send to the view

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers {
        logout as defaultLogout;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $guard = backpack_guard_name();

        $this->middleware("guest:$guard", ['except' => 'logout']);

        // ----------------------------------
        // Use the admin prefix in all routes
        // ----------------------------------

        // If not logged in redirect here.
        $this->loginPath = property_exists($this, 'loginPath') ? $this->loginPath
            : backpack_url('login');

        // Redirect here after successful login.
        $this->redirectTo = property_exists($this, 'redirectTo') ? $this->redirectTo
            : backpack_url('dashboard');

        // Redirect here after logout.
        $this->redirectAfterLogout = property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout
            : backpack_url('login');
    }

    /**
     * Return custom username for authentication.
     *
     * @return string
     */
    public function username()
    {
        return backpack_authentication_column();
    }

    public function login(Request $request)
    {
         //fetch client_id from users using email
         $email_or_phone_no = $request['email_or_phone_no'];

         $user = User::where('email', $email_or_phone_no)
            ->orWhere('phone_no', $email_or_phone_no)
            ->first();

        if (!$user) {
            return back()->with(['message' => 'User doest not exists']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->with(['message' => 'Incorrect Password']);
        }

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($user->is_verified) {
            if ($this->guard()->attempt(['email' => $email_or_phone_no, 'password' => $request->password])
            || $this->guard()->attempt(['mobile_no' => $email_or_phone_no, 'password' => $request->password])) {
                return $this->sendLoginResponse($request);
            }

        } else {
            // otp generate
            $otp = $this->generateOTP($user);

            return view('auth.otp_verification', ['user' => $user]);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        $user = backpack_user();
        $request->session()->regenerate();

        $sessionInfo = $request->getSession();

        $session_id = $sessionInfo->getId();
        $session_name = $sessionInfo->getName();

        $session = new SessionActivityLog();
        $currentSession  = $session->addSessionLog($session_id, $session_name , $is_currently_logged_in = True);

        $request->session()->put('sessionId', $currentSession->id);      // Store sessionId to current session

        //Get Fiscal year
        $current_fiscal_year = null;
        $fiscal_year_code = null;

        $this->clearLoginAttempts($request);
            dd($this->guard()->user());
        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }


    public function logout(Request $request)
    {
        // Do the default logout procedure
        $this->guard()->logout();

        //update the login-status in session log and update logout time
        $time = date("h:i:sa");
        $time = SessionActivityLog::englishToNepali($time);

        $session_id = $request->session()->get('sessionId');

        DB::connection('pgsql2')
            ->table('session_log')
            ->where('id',$session_id)
            ->update(['is_currently_logged_in' => false, 'logout_time' => $time]);

        // And redirect to custom location
        return redirect($this->redirectAfterLogout);
    }

    protected function loggedOut(Request $request)
    {
        return redirect($this->redirectAfterLogout);
    }

    /**
     * Get the guard to be used during logout.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return backpack_auth();
    }

    // to generate otp for login validation
    function generateOTP($user){
        // generating random 4 digit number for otp
        $otp = random_int(1000, 9999);

        // updating db with generated otp
        $user->verification_code = $otp;
        $user->save();

        $data = [
            'email' => $user->email,
            'otp' => $otp,
        ];
        //for sending sms
        $message = "E-cabinet: Your Verification code Number is : ".$otp;
        $sms = new SmsHelper();
        $sms->send($user->phone_no,$message);

        $email = $user->email;
        // for sending email
        Mail::send('sendEmail.otp_view',$data, function($message) use ($email){
            $message->to($email)
            ->subject('E-CABINET OTP');
        });

        return $otp;
    }

    // validating user input otp
    public function otpVerify(Request $request){
        // setting data
        $user = User::findOrFail($request->user_id);
        $otp = $request->otp_number;

        // validating otp with db
        if($user && $user->verification_code === $otp){
            return $this->authenticated($request, $user)
                ?: redirect()->intended($this->redirectPath());
        }else{
            return view('auth.otp_verification',['user' => $user])->with('message', 'OTP does not match');
        }
    }

    public function otpView(Request $request){
        dd('hf');
        return view('auth.otp_verification', ['user_id' => $user->id,'otp'=>$otp]);
    }

}
