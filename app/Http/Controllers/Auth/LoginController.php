<?php

namespace app\Http\Controllers\Auth;

use App\Models\User;
use App\Models\BackpackUser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Base\Helpers\SessionActivityLog;
use Backpack\CRUD\app\Library\Auth\AuthenticatesUsers;

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
            : backpack_url();
    }

    public function login(Request $request)
    {
        // dd('s');
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $user_email = $request->email;

        $user = User::where('name', $user_email)
        ->orWhere('email', $user_email)            
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
        // dd($user->is_verified);

        if ($user->hasRole('superadmin')) {
            //if email is give
            if ($this->guard()->attempt(['name' => $user_email, 'password' => $request->password])) {
                return $this->sendLoginResponse($request);
            }
            
            //if mobile no is given
            if ($this->guard()->attempt(['email' => $user_email, 'password' => $request->password])) {
                return $this->sendLoginResponse($request);
            } 

            // $session = new SessionActivityLog();
            // $currentSession  = $session->addSessionLog($session_id, $session_name , $is_currently_logged_in = True);

        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }



    public function username()
    {
        return backpack_authentication_column();
    }

    /**
     * The user has logged out of the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
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

    public function showLoginForm()
    {
        // dd('login'); 
        // dd("showLoginForm");
        $this->data['title'] = trans('backpack::base.login'); // set the page title
        $this->data['username'] = $this->username();

        return view('auth.login', $this->data);
    }

    /**
     * Overriding default credentials
     * and adding is_verifed in where clause
     */
    protected function verifiyUser(Request $request)
    {
        // $data =  $request->only($this->username(), 'password');
        $user = BackpackUser::find($request->user_id);
        $code = $request->code;
        if ($user->verification_code == $code) {
            $user->verification_code = null;
            $user->is_verified = true;
            $user->save();
            
            // return view('auth.verification_success');
            return redirect('admin/login')->with(['user_username'=>$user->username]);
        }
    }

    public function logout(Request $request)
    {
        // Do the default logout procedure
        $this->guard()->logout();
        
        //update the login-status in session log and update logout time
        $time = date("h:i:sa");
        // $time = SessionActivityLog::englishToNepali($time);

        $session_id = $request->session()->get('sessionId');

        // DB::connection('pgsql2')
        //     ->table('session_log')
        //     ->where('id',$session_id)
        //     ->update(['is_currently_logged_in' => false, 'logout_time' => $time]);

        // And redirect to custom location
        return redirect($this->redirectAfterLogout);
    }
    protected function sendLoginResponse(Request $request)
    {
        $user = backpack_user();
        $request->session()->regenerate();

        $sessionInfo = $request->getSession();

        $session_id = $sessionInfo->getId();
        $session_name = $sessionInfo->getName();
        
        // $session = new SessionActivityLog();
        // $currentSession  = $session->addSessionLog($session_id, $session_name , $is_currently_logged_in = True);

        // dd($currentSession);

        // $request->session()->put('sessionId', $currentSession->id);      // Store sessionId to current session  
        
        $this->clearLoginAttempts($request);
        
        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    
}
