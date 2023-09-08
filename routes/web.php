<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return redirect('/admin');
});
Route::get('home', function () {
    return redirect('/admin');
});
Route::post('/broadcasting/auth', function () {
    return Auth::user();
 });

Route::get('/ecabinet','App\Http\Controllers\Admin\DashboardController@pdf_view');

Route::post('/admin/verify-otp', 'App\Http\Controllers\Auth\LoginController@otpVerify')->name('verify-otp');

Route::get('/get-meeting-request-detail','App\Http\Controllers\Admin\MeetingMinuteDetailCrudController@meetingRequestDetail')->name('meetingrequestdetail');
Route::get('/get-meeting-request-detail-direct-agenda','App\Http\Controllers\Admin\MeetingMinuteDetailCrudController@meetingRequestDetailDirectAgenda')->name('meetingrequestdetaildirectagenda');


Route::get('/admin/agenda/{agenda_id}/decisionDialog', 'App\Http\Controllers\Admin\AgendaCrudController@decisionDialog');

