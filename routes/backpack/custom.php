<?php

use App\Base\BasePivotController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PtProjectMilestoneCrudController;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group(
    [
        'namespace'  => 'App\Http\Controllers',
        'middleware' => config('backpack.base.web_middleware', 'web'),
        'prefix'     => config('backpack.base.route_prefix'),
    ],
    function () {
        Route::get('/', 'AdminController@redirect')->name('backpack');
        Route::get('dashboard', 'AdminController@dashboard')->name('backpack.dashboard');

    });



Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin\CoreMaster',
], function () { // custom admin routes
    Route::crud('mst-fed-province', 'MstFedProvinceCrudController');
    Route::crud('mst-fed-district', 'MstFedDistrictCrudController');
    Route::crud('mst-fed-local-level-type', 'MstFedLocalLevelTypeCrudController');
    Route::crud('mst-fed-local-level', 'MstFedLocalLevelCrudController');

    Route::crud('mst-nepali-month', 'MstNepaliMonthCrudController');
    Route::crud('mst-fiscal-year', 'MstFiscalYearCrudController');
    Route::crud('mst-gender', 'MstGenderCrudController');
    Route::crud('app-setting', 'AppSettingCrudController');
    Route::crud('appsetting', 'AppSettingCrudController');
    Route::crud('mst-level', 'MstLevelCrudController');
    Route::crud('mst-ministry', 'MstMinistryCrudController');
    Route::crud('mst-ministry/{ministry_id}/members', 'MinistryMembersCrudController');
    Route::crud('mst-ministry/{ministry_id}/darbandi', 'MinistryDarbandiCrudController');
    Route::post('import-darbandi-excel/{ministry_id}', 'MinistryDarbandiCrudController@importExcel')->name('import_darbandi_excel');

    Route::crud('mst-posts', 'MstPostsCrudController');
    Route::crud('mst-groups', 'MstGroupsCrudController');

});

