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

    Route::get('/services', 'ServicesController@index');
    Route::get('/about-us', 'HomeController@about');
    Route::get('/blog', 'BlogController@index');
    Route::get('/contact-us', 'HomeController@contactUs');
    Route::get('/team', 'HomeController@team');
    Route::get('/choose-us', 'HomeController@chooseUs');
    Route::get('/history', 'HomeController@history');
    Route::get('/faq', 'HomeController@faq');
    Route::get('/careers', 'HomeController@careers');
    Route::get('/gallery', 'HomeController@gallery');

    Route::Post('/contact-form', 'HomeController@contactFormSend')->name('contact-form');




    // Route::get('/courses', 'CourseController@index');
    // Route::get('/courses/{id}', 'CourseController@getCourseDetail');
    // Route::get('/about_us', 'AboutUsController@index');
    // Route::get('/about_us/introduction', 'AboutUsController@introduction');
    // Route::get('/about_us/our_team', 'AboutUsController@ourTeam');
    // Route::get('/about_us/department_type', 'AboutUsController@departmentType');
    // Route::get('/about_us/class', 'AboutUsController@class');
    // Route::get('/about_us/fee_type', 'AboutUsController@feeType');
    // Route::get('/about_us/scholarships', 'AboutUsController@scholarship');
    // Route::get('/about_u  s/agents', 'AboutUsController@agents');
    // Route::get('/agent-detail/{id}', 'AboutUsController@agentDetail');
    // Route::get('/gallery', 'GalleryController@index');
    // Route::get('/news_notices', 'NewsController@index');
    // Route::get('/news_notices/events', 'NewsController@events');
    // Route::get('/news_notices/blogs', 'NewsController@blog');

    // Route::get('/pages/{slug}', 'HomeController@getData');
    // Route::get('/event-detail/{id}', 'NewsController@eventDetail');
    // Route::get('/team-detail/{id}', 'AboutUsController@teamDetail');
    // Route::get('/blog-detail/{id}', 'NewsController@blogDetail');
    // Route::get('/news-detail/{id}', 'NewsController@newsDetail');
    // Route::get('/events/{category_id}', 'NewsController@filteredEvents');
    // Route::get('/blogs/{category_id}', 'NewsController@filteredBlogs');
    // Route::get('/news/{category_id}', 'NewsController@filteredNews');

    // Route::get('/contact_us', 'ContactUsController@index');
    // Route::post('/contact_us', 'ContactUsController@store')->name('contact_us_form');
});