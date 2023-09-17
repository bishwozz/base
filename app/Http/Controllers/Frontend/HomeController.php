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
use App\Models\AppSettings;
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
        $app_setting = AppSettings::where('deleted_uq_code',1)->first();

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
            // dd(Auth::user()->id);
            $review = Slider::where('deleted_uq_code',1)->orderBy('display_order','asc')->where('created_by',Auth::user()->id)->first();
            // dd($review);
        }else{
            $review = null;
        }

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

    public function changeBackground($color){
        $setting = AppSettings::where('id',1)->first();
        if($setting){
            $setting->background_color = $color;
            $setting->save();
            return back();
        }
    }

    public function getReviewFancyBox(){
        return view('frontend.review_fancy_box');
    }

    public function saveReview(Request $request){

        $review = Review::create([
            'rating' => $request->star,
            'comment' => $request->comment,
            'created_by' => Auth::user()->id,
        ]);
        return true;


    }
}
