<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::get('api/parent_menu/{value}', 'App\Http\Controllers\Api\ParentMenuApiController@index');

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('slider', 'SliderCrudController');
    Route::crud('games', 'GamesCrudController');
    Route::crud('services', 'ServicesCrudController');
    Route::crud('payments', 'PaymentCrudController');
    Route::crud('slideshow', 'SlideShowCrudController');
    Route::crud('review', 'ReviewCrudController');
    Route::crud('app-settings', 'AppSettingsCrudController');

}); // this should be the absolute last line of this file
Route::group([
    'prefix'     => '',
    'middleware' => ['web'],
    'namespace'  => 'App\Http\Controllers\Frontend',
], function () { 
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/login', 'HomeController@showLoginForm')->name('user_login');
    Route::post('/login', 'HomeController@login')->name('check_login');
    Route::post('/logout', 'HomeController@logout')->name('logout');
    
    Route::get('/payment', 'HomeController@payment')->name('payment');
    Route::get('/review', 'HomeController@review')->name('review');
});