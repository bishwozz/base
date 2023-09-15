<?php

namespace app\Http\Controllers\Auth;

use Validator;
use App\Models\BackpackUser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendVerificationEmail;
use App\Models\BackpackUser as User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Auth\Events\Registered;
use Symfony\Component\Console\Input\Input;
use Backpack\CRUD\app\Library\Auth\RegistersUsers;


class RegisterController extends Controller
{
    protected $data = []; // the information we send to the view

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $guard = backpack_guard_name();

        $this->middleware("guest:$guard");

        // Where to redirect users after login / registration.
        $this->redirectTo = property_exists($this, 'redirectTo') ? $this->redirectTo
            : config('backpack.base.route_prefix', 'dashboard');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $user_model_fqn = config('backpack.base.user_model_fqn');
        $user = new $user_model_fqn();
        $users_table = $user->getTable();
        // dd($user);
        // $email_validation = backpack_authentication_column() == 'email' ? 'email|' : '';

        return Validator::make($data, [
            'name'                             => 'required|max:255',
            'mobile_no'                           => 'required|max:10|unique:' . $users_table,
            'username'                            => 'required|max:100|unique:' . $users_table,
            'password'                         => 'required|min:6|confirmed',

        ]);

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        $user_model_fqn = config('backpack.base.user_model_fqn');
        $user = new $user_model_fqn();

        return $user->create([
            'name'                             => $data['name'],
            'username'                             => $data['username'],
            backpack_authentication_column()   => $data[backpack_authentication_column()],
            'password'                         => bcrypt($data['password']),
        ]);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        // if registration is closed, deny access
        if (! config('backpack.base.registration_open')) {
            abort(403, trans('backpack::base.registration_closed'));
        }

        $this->data['title'] = trans('backpack::base.register'); // set the page title

        return view('auth.register', $this->data);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // if registration is closed, deny access
        if (! config('backpack.base.registration_open')) {
            abort(403, trans('backpack::base.registration_closed'));
        }

        $this->validator($request->all())->validate();

        DB::beginTransaction();
        try {
            $code = rand(1000, 9999);
            $user = new User();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->mobile_no = $request->mobile_no;
            $user->email = $request->email;
            $user->verification_code = $code;
            $user->save();

            $user->assignRoleCustom('applicant');
            // $smsHelper = new SmsHelper();
            // $message = 'Easy Industry login verification code is '. $user->verification_code;
            // $smsHelper->send($user->mobile_no, $message);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return back();
        }     
        return view('auth.verification_code', ['user_id' => $user->id]);

        // return redirect('admin/login')->with(['user_username'=>$request->username]);
    }


    public function verify(Request $request)
    {
        //veryy
        //is_verified true
        //verification null
        //save
        //return to loggin
                                                     
        $user = User::where('username_token', $token)->first();
        if ($user) {
            User::where('username_token', $token)->update(['verified' => 1, 'username_token' => null]);
            return view('username.usernameconfirm', [
                'user' => $user,
                'title' => "Confirm username",
            ]);
        } else {
            return view('username.usernameconfirmExpired', [
                'title' => "Expired Link",
            ]);
        }

    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return backpack_auth();
    }

    public function transaction()
    {

        ini_set('memory_limit', '1024M');
        DB::disableQueryLog();

        DB::transaction(function () {
            // Start transaction

        DB::beginTransaction();
        try{
            $hashedusername = Hash::make(Input::get('username'));
            $newHashedusername = str_replace([".", "/"], ["a", "b"], $hashedusername);
                // create user
                $user = User::create([
                    'name' => Input::get('name'),
                    'username' => Input::get('username'),
                    'username_token' => $newHashedusername,
                    'password' => bcrypt(Input::get('password')),
                ]);
                event(new Registered($user));
                dispatch(new SendVerificationusername($user, Input::get('password')));
            } catch (\Exception $e) {
                DB::rollback();
                // if uid not unique then repeat process
                $this->transaction();
            }
            // if all safe, Commit the queries
            DB::commit();
        });

    }
}
