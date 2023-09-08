<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class PasswordResetController extends Controller
{
    function showResetForm(Request $request){
        $email = $request->query('email');
        $token = $request->query('token');

        $tokenData = DB::table('password_resets')->where(['email' => $email, 'token' => $token])->latest()->first();
        if($tokenData) {
            $data['email'] = $email;
            $data['token'] = $token;

            return view('user.reset_password', $data);
        } else {
            // Email and token do not match, handle accordingly
            return response()->json(['message' => 'Invalid email or token'], 403);
        }
    }

    function reset(Request $request){

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password'
        ]);

        $tokenData = DB::table('password_resets')->where(['email' => $request->email, 'token' => $request->token])->latest()->first();
        if($tokenData){
            $user = User::where('email', $tokenData->email)->first();
            $password = bcrypt($request->confirm_password);
            $user->update(['password' => $password]);

            DB::table('password_resets')->where(['email'=> $request->email])->delete();
            session()->flash('success', 'Password reset successfully.');

            return redirect('/admin/login');


        } else {
            return $this->sendError("error", "Invalid Token provided");
        }
    }
}
