<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Menu;
use App\Models\Page;
use App\Models\User;
use App\Models\Games;
use App\Models\Review;
use App\Models\Slider;
use App\Models\Payment;
use App\Models\Services;
use App\Models\SlideShow;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    public function index()
    {
        // dd('as');
        $sliders = Slider::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $games = Games::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $services = Services::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $payments = Payment::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $slideshows = SlideShow::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();
        $reviews = Review::where('deleted_uq_code',1)->orderBy('display_order','asc')->get();

        $this->data = [
            'sliders' => $sliders,
            'games' => $games,
            'services' => $services,
            'payments' => $payments,
            'slideshows' => $slideshows,
            'reviews' => $reviews,
        ];
        return view('frontend.index', $this->data);
    }
    public function review()
    {
        if(Auth::user()){
            dd(Auth::user());
            $review = Slider::where('deleted_uq_code',1)->orderBy('display_order','asc')->where('created_by',Auth::user()->id)->get();
        }else{
            $review = null;
        }
        // dd($review);

        $this->data = [
            'review' => $review,
          
        ];
        return view('frontend.review', $this->data);
    }

    public function showLoginForm(){
        return view('frontend.auth.login');
    }

    public function login(Request $request){
        $user = User::where('email', $request->email)->first();

        if($user && Hash::check($request->password, $user->password)){
            auth::login($user);
            return redirect('/');
        }

        return response()->json([
            'status' => 'error',
            'message' => 'failed',
        ], 200);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/'); // Redirect to the homepage or any other page after logout
    }
    public function payment()
    {
        return view('frontend.payment');
    }
}
