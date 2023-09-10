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
    Route::crud('menu', 'MenuCrudController');
    Route::crud('mst-department-type', 'MstDepartmentTypeCrudController');
    Route::crud('about-us', 'AboutUsCrudController');
    Route::crud('gallery', 'GalleryCrudController');
    Route::crud('contact-us', 'ContactUsCrudController');
    Route::crud('faq', 'FaqCrudController');
    Route::crud('slider', 'SliderCrudController');
    Route::crud('games', 'GamesCrudController');
    Route::crud('mst-class', 'MstClassCrudController');
    Route::crud('header', 'HeaderCrudController');
    Route::crud('footer-address', 'FooterAddressCrudController');
    Route::crud('blog', 'BlogCrudController');
    Route::crud('page', 'PageCrudController');
    Route::crud('category', 'CategoryCrudController');
    Route::crud('app-setting', 'AppSettingsCrudController');

}); // this should be the absolute last line of this file
Route::group([
    'prefix'     => '',
    'middleware' => ['web'],
    'namespace'  => 'App\Http\Controllers\Frontend',
], function () { 
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index');
    Route::get('/login', 'HomeController@showLoginForm')->name('user_login');
    Route::post('/login', 'HomeController@login')->name('check_login');
    Route::post('/logout', 'HomeController@logout')->name('logout');
    
    Route::get('/payment', 'HomeController@payment')->name('payment');
});