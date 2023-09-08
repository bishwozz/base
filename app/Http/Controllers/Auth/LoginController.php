<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Helpers\SmsHelper;
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
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        //fetch client_id from users using email

    //    dd($this->guard()->attempt(['email' => $request->email_or_phone_no, 'password' => $request->password]), $request->all());

        $email_or_phone_no = $request->email_or_phone_no;
        $user = User::where('email', $email_or_phone_no)
            ->orWhere('phone_no', $email_or_phone_no)
            ->first();

        if (!$user) {
            $this->incrementLoginAttempts($request);
            return $this->sendFailedLoginResponse($request);
            // return back()->with('message' , 'User doest not exists');
        }


        $isOtpEnabled = env('OTP_ENABLE', FALSE);

        if ($isOtpEnabled) {
            // otp generate
            $otp = $this->generateOTP($user);
            $data = [
                'user' => $user,
                'password'=>$request->password,
            ];
            return view('auth.otp_verification', $data);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
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

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
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
    // check if given input is phon_no or email
    public function email_or_phone_no($value=null)
    {
        $hasString = preg_match('/[a-zA-Z]/', $value);
        $hasInt = preg_match('/\d/', $value);

        if ($hasString && $hasInt) {
            return "email";
        } elseif ($hasString) {
            return "email";
        } elseif ($hasInt) {
            return "phone_no";
        } else {
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return 'email';
            } else {
                return 'email_or_phone_no';
            }
        }

    }
    // validating entry credentials
    public function attemptLogin(Request $request){
       return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    // getting only email and password for credentials
    protected function credentials(Request $request)
    {
        if(!$request->email){
            $field = $this->email_or_phone_no($request->email_or_phone_no);
            return [
                $field => $request->email_or_phone_no,
                'password'=> $request->password,
            ];

        }else{
            $field = $this->email_or_phone_no($request->email);
            return $request->only($field, 'password');
        }

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
        if($user->phone_no){
            $message = "E-cabinet: Your Verification code Number is : ".$otp;
            $sms = new SmsHelper();
            $sms->send($user->phone_no,$message);
        }

        $email = $user->email;
        // for sending email
        if($email){
            Mail::send('sendEmail.otp_view',$data, function($message) use ($email){
                $message->to($email)
                ->subject('E-CABINET OTP');
            });
        }

        return $otp;
    }

    // validating user input otp
    public function otpVerify(Request $request){
        // setting data
        $user = User::findOrFail($request->user_id);
        $otp = $request->otp_number;

        // validating otp with db
        if($user && $user->verification_code === $otp){
            if ($this->attemptLogin($request)) {
                $user->is_verified = True;
                $user->save();
                return $this->sendLoginResponse($request);
            }
        }else{
            return view('auth.otp_verification',['user' => $user,'password'=>$request->password])->with('message', 'OTP does not match');
        }
    }

}
