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
        Route::get('user-manual', 'AdminController@userManual')->name('backpack');
        Route::post('get-dashboard-data', 'HomeController@index')->name('home.index');
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

Route::group([
'prefix'     => config('backpack.base.route_prefix', 'admin'),
'middleware' => array_merge(
    (array) config('backpack.base.web_middleware', 'web'),
    (array) config('backpack.base.middleware_key', 'admin')
),
'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    // Route::crud('progressReportTracking', 'ProgressReportTrackingCrudController');
    // Route::crud('ppmo-management-method', 'PpmoManagementMethodCrudController');
    // Route::crud('progress-monitoring-method', 'ProgressMonitoringMethodCrudController');
    // Route::crud('ministry-report', 'MinistryReportCrudController');
    Route::post('office-report', 'OfficeDetailReportCrudController@report');
    Route::get('office-report/filter', 'OfficeDetailReportCrudController@index');
    Route::get('office-report/excelexport','OfficeDetailReportCrudController@report');
    Route::crud('pt-project', 'PtProjectCrudController');
    Route::crud('pt-project/{project_id}/milestone', 'PtProjectMilestoneCrudController');


    Route::crud('ministry-budget-info', 'MinistryBudgetInfoCrudController');
    Route::crud('ministry-progress-info', 'MinistryProgressInfoCrudController');
    Route::crud('ministry-program-progress', 'MinistryProgramProgressCrudController');
    Route::post('minbudgetinfo','MinistryBudgetInfoCrudController@getMinBudget');

    Route::crud('ministry-act-law', 'MinistryActLawCrudController');
    Route::crud('office-detail', 'OfficeDetailCrudController');

    //pivot report
    Route::get('report/pivot_report', 'PivotReportController@index');
    Route::get('report/pivotdata', 'PivotReportController@getPivotData');
    Route::crud('office-initiative', 'OfficeInitiativeCrudController');

    Route::get('project/{ministry_id}/{fiscal_year_id}', 'MinistryDataController@getMinistryProject');
    Route::get('getproject/{ministry_id}/{fiscal_year_id}', 'MinistryDataController@getProject');
    Route::post('import-project-excel', 'PtProjectCrudController@importExcel')->name('import_project_excel');

    Route::get('report/masterdata', [BasePivotController::class,'getMasterData']);

    Route::get('pt-project/{id}/show','PtProjectCrudController@show')->name('viewProject');
    Route::post('getMilestoneData','PtProjectCrudController@getMilestoneData');

    Route::crud('ptproject-milestone', 'PtProjectMilestoneCrudController');

    Route::post('add-milestone', [PtProjectMilestoneCrudController::class, 'addMilestone']);
    Route::post('add-progress-record', 'MinistryProgramProgressCrudController@addprogressRecord');
    Route::post('edit-progress-record', 'MinistryProgramProgressCrudController@editprogressRecord');
    Route::post('delete-milestone', [PtProjectMilestoneCrudController::class, 'delete'])->name('milestone.delete');
    Route::crud('mst-milestones-status', 'MstMilestonesStatusCrudController');
    Route::get('get-timeline-chart/{project_id}', 'PtProjectMilestoneCrudController@timelineChart');
    Route::get('print-timeline-bar/{project_id}/{project_name?}', 'PtProjectMilestoneCrudController@printTimelineBar')->name('printTimelineBar');
});
