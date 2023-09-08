<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserCrudController;
use App\Http\Controllers\Admin\MeetingMinuteDetailCrudController;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.



Route::get('admin/password-reset', 'App\Http\Controllers\Auth\PasswordResetController@showResetForm');
Route::post('admin/password/reset', 'App\Http\Controllers\Auth\PasswordResetController@reset')->name('password.update');


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

    // Route::get('api/ecMc', 'EcMpCrudController@getEcMp');



});


// this should be the absolute last line of this file
Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin\Log',
], function () { // custom admin routes

    Route::crud('session-log', 'SessionLogCrudController');
    Route::crud('activity-log', 'ActivityLogCrudController');
}); // this should be the absolute last line of this file
Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
    ], function () { // custom admin routes
    Route::crud('ec-mp', 'EcMpCrudController');

    Route::crud('ec-mp/{mp_id}/tenure', 'EcMpTenureCrudController');
    // Route::crud('mst-meeting', 'MstMeetingCrudController');
    Route::crud('mst-post', 'MstPostCrudController');
    Route::crud('mst-step', 'MstStepCrudController');
    Route::crud('ministry-member-type', 'MinistryMemberTypeCrudController');
    Route::crud('ministry', 'MinistryCrudController');
    Route::crud('committee', 'CommitteeCrudController');
    Route::crud('committee/{committee_id}/members', 'CommitteeMembersCrudController');
    Route::crud('agenda-decision-type', 'AgendaDecisionTypeCrudController');
    Route::crud('agenda-file-type', 'AgendaFileTypeCrudController');




    Route::crud('mst-agenda-types', 'MstAgendaTypesCrudController');
    // Route::crud('ministry-member', 'MinistryMemberCrudController');
    Route::crud('ministry/{ministry_id}/ministrymember', 'MinistryMemberCrudController');
    Route::crud('ministry/{ministry_id}/ministryemployee', 'MinistryEmployeeCrudController');

    Route::crud('ec-meeting-request/{meeting_request_id}/meeting-attendance-detail', 'MeetingAttendanceDetailCrudController');
    Route::post('mp-attendance/{meeting_attendance_id}', 'MeetingAttendanceDetailCrudController@mpAttendance')->name('mpattendance');
    Route::post('mp-attendance/apply-for-meeting-attendance-attend', 'MeetingAttendanceDetailCrudController@applyForMeetingAttendanceAttend');

    // Notification
    Route::get('/notifications', 'AgendaCrudController@getNotifications')->name('notifications');
    Route::post('/notifications/mark-as-read', 'AgendaCrudController@notificationMarkAsRead')->name('notificationMarkAsRead');



    Route::crud('ec-meeting-request', 'EcMeetingRequestCrudController');
    // Route::crud('ec-meeting-request?ids={ids}', 'EcMeetingRequestCrudController');
    Route::get('ec-meeting-request/{meeting_request_id}/get-ministry-members','EcMeetingRequestCrudController@getMinistryMembers');
    Route::post('ec-meeting-request/{meeting_request_id}/send-email','EcMeetingRequestCrudController@sendEmail')->name('sendemail');
    Route::get('ec-meeting-request/{meeting_request_id}/ministry-meeting-agenda-pdf','EcMeetingRequestCrudController@ministryMeetingAgendaPdf')->name('ministrymeetingagendapdf');
    Route::get('ec-meeting-request-pdf/{meeting_request_id}/ministry-meeting-agenda-pdf','EcMeetingRequestCrudController@meetingRequestPdf');
    Route::get('/agenda-table','EcMeetingRequestCrudController@getAgendTable')->name('agendTable');
    Route::get('/toggle','EcMeetingRequestCrudController@getToggleData')->name('getToggleData');
    Route::get('ec-meeting-request/{meeting_request_id}/apply-for-meeting-attend','EcMeetingRequestCrudController@applyForMeetingAttend');
    Route::post('ec-meeting-request/{meeting_request_id}/apply-for-meeting-attend-confirmation','EcMeetingRequestCrudController@applyForMeetingAttendConfirmation')->name('applyformeetingattendconfirmation');
    // Route::post('ec-meeting-request/{meeting_request_id}/submit-to-chief-secretary','EcMeetingRequestCrudController@submitToChiefSecretary')->name('submittochiefsecretary');

    Route::crud('meeting-minute-detail', 'MeetingMinuteDetailCrudController');
    Route::get('meeting-minute-detail/{meeting_minute_id}/meeting-minute-details', 'MeetingMinuteDetailCrudController@meetingMinuteDetail');
    Route::get('meeting-minute-detail/{meeting_minute_id}/meeting-minute-details-pdf', 'MeetingMinuteDetailCrudController@meetingMinuteDetailPdf');
    Route::get('meeting-minute-detail/{meeting_minute_id}/meeting-minute-for-minister-pdf', 'MeetingMinuteDetailCrudController@meetingMinuteForMinisterPdf');
    Route::get('meeting-agenda-detail/{meeting_agenda_id}/meeting-agenda-details-pdf', 'MeetingMinuteDetailCrudController@meetingAgendaDetailPdf');
    Route::get('meeting-minute-detail/{meeting_minute_id}/committee-meeting-minute-details-pdf', 'MeetingMinuteDetailCrudController@committeeMeetingMinuteDetailPdf');
    Route::post('meeting-minute-detail/{meeting_request_id}/meeting-minute-send-email','MeetingMinuteDetailCrudController@meetingMinuteSendEmail')->name('sendMeetingMinuteMail');
    Route::get('/get-meeting-request-detail','MeetingMinuteDetailCrudController@meetingRequestDetail');
    Route::get('/get-meeting-request-detail-direct-agenda','MeetingMinuteDetailCrudController@meetingRequestDetailDirectAgenda');
    Route::get('/get-meeting-request-detail-direct-agenda-from-request','EcMeetingRequestCrudController@meetingRequestDetailDirectAgenda');
    Route::get('meeting-minute-detail/{id}/uploaddialog', 'MeetingMinuteDetailCrudController@uploadDialog');
    Route::get('meeting-minute-detail/{id}/uploaAgendadDialog', 'MeetingMinuteDetailCrudController@uploaAgendadDialog');
    Route::post('meeting-minute-detail/{id}/savefile', 'MeetingMinuteDetailCrudController@saveFile');
    Route::post('uploaAgendadDialog', 'MeetingMinuteDetailCrudController@uploaAgendadDialog');

    Route::post('/upload-pdf', [MeetingMinuteDetailCrudController::class, 'uploadPdf'])->name('uploadPdf');
    // Agenda Print
    Route::get('/print-agenda/{id}/report', 'MeetingMinuteDetailCrudController@printAgenda');
    Route::get('/print-direct-agenda/{id}/report', 'MeetingMinuteDetailCrudController@printDirectAgenda');
    Route::get('getDecisionContent/{id}', 'MeetingMinuteDetailCrudController@getAgendaDecisionTypeContent')->name('getDecisionContent');
    Route::post('save-decision-content', 'MeetingMinuteDetailCrudController@saveAgendaDecisionTypeContent');
    Route::get('getdirectAgendaView/{id}/agenda-view', 'MeetingMinuteDetailCrudController@directAgendaView');
    Route::get('getdirectAgendaViewFromMeetingRequest/{id}/agenda-view', 'EcMeetingRequestCrudController@directAgendaView');
    Route::get('get-view-multiple-file-upload/{id}/agenda-view', 'AgendaCrudController@agendaFileUploadView');
    Route::post('save-direct-agenda', 'MeetingMinuteDetailCrudController@saveDirectAgenda');
    Route::post('upload-multiple-files-agends', 'AgendaCrudController@multipleFileSave');
    // Agenda Show
    Route::get('/agenda/{id}/view', 'AgendaCrudController@showAgenda');


    Route::get('/change_localization','UiController@changeLocale');
    Route::get('/change-theme','UiController@changeTheme');

    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/get-chart-data', 'DashboardController@getChartData');
    Route::get('/getDashboardBoxData', 'DashboardController@getDashboardBoxData');
    Route::get('dashboard/dashboard-data','DashboardController@dashboardData');
    Route::get('dashboard/load-dashboard-table','DashboardController@loadDashboardTable');
    Route::get('/manual','DashboardController@manual');

    Route::get('fetch-mp-detail',[UserCrudController::class,'fetchMpDetail']);
    Route::get('fetch-ministry-employee-detail',[UserCrudController::class,'fetchMinistryEmployeeDetail']);

    Route::crud('agenda', 'AgendaCrudController');
    Route::get('agenda/ministry-wise/{ministry_id}', 'AgendaCrudController@getCommmitteeWiseAgenda');
    Route::post('agenda/{agenda_id}/approve-agenda', 'AgendaCrudController@approveAgenda');
    Route::post('agenda/{agenda_id}/hold-agenda', 'AgendaCrudController@holdAgenda');
    Route::post('agenda/{agenda_id}/unhold-agenda', 'AgendaCrudController@unholdAgenda');
    Route::get('agenda/{agenda_id}/reject-agenda', 'AgendaCrudController@rejectAgenda');
    Route::post('agenda/{agenda_id}/reject-agenda', 'AgendaCrudController@updateRejectAgenda')->name('updateRejectAgenda');
    Route::post('agenda/{agenda_id}/reject-agenda-first', 'AgendaCrudController@updateRejectAgendaFirst')->name('updateRejectAgendaFirst');
    Route::post('agenda/{agenda_id}/reject-agenda-second', 'AgendaCrudController@updateRejectAgendaSecond')->name('updateRejectAgendaSecond');

    // Submit agenda from agenda creator
    Route::post('agenda-submit/{agenda_id}','AgendaCrudController@submitAgenda')->name('custom.submitAgenda');
    Route::post('submit-meeting-request/{meeting_request_id}','EcMeetingRequestCrudController@submitMeetingRequest')->name('custom.submitMeetingRequest');
    Route::post('submit-meeting-minute/{meeting_minute_id}','MeetingMinuteDetailCrudController@submitMeetingMinute')->name('custom.submitMeetingMinute');

    // agenda approval and rejection
    Route::post('agenda-approve/{agenda_id}','AgendaCrudController@agendaApproval')->name('custom.agendaApproval');
    Route::get('agenda-reject-view/{agenda_id}','AgendaCrudController@agendaRejectView')->name('custom.agendaRejectView');
    Route::post('agenda-reject/{agenda_id}','AgendaCrudController@agendaRejection')->name('custom.agendaRejection');
    // Meeting Request approval and rejection
    Route::post('meeting-request-approve/{meeting_request_id}','EcMeetingRequestCrudController@meetingRequestApproval')->name('custom.meetingRequestApproval');
    Route::get('meeting-request-reject-view/{meeting_request_id}','EcMeetingRequestCrudController@meetingRequestRejectView')->name('custom.meetingRequestRejectView');
    Route::post('meeting-request-reject/{meeting_request_id}','EcMeetingRequestCrudController@meetingRequestRejection')->name('custom.meetingRequestRejection');
    // Meeting Minute approval and rejection
    Route::post('meeting-minute-approve/{meeting_minute_id}','MeetingMinuteDetailCrudController@meetingMinuteApproval')->name('custom.meetingMinuteApproval');
    Route::get('meeting-minute-reject-view/{meeting_minute_id}','MeetingMinuteDetailCrudController@meetingMinuteRejectView')->name('custom.meetingMinuteRejectView');
    Route::post('meeting-minute-reject/{meeting_minute_id}','MeetingMinuteDetailCrudController@meetingMinuteRejection')->name('custom.meetingMinuteRejection');

    Route::post('transfer-agenda','AgendaCrudController@transferAgenda')->name('transfer.agenda');
    Route::post('transfer-agenda-from-committee','AgendaCrudController@transferAgendaFromCommittee')->name('transfer.agenda.from.committee');
    Route::post('store-cabinet-decision','AgendaCrudController@storeCabinetDecision')->name('store.cabinet.decision');
    Route::post('store-committee-decision','AgendaCrudController@storeCommitteeDecision')->name('store.committee.decision');
    Route::get('get-agenda-number','AgendaCrudController@getAgendanumber')->name('getAgendanumber');
    Route::post('store-agenda-number','AgendaCrudController@storeAgendaNumber')->name('store.agendaNumber');
    Route::get('get-agenda-table','AgendaCrudController@getAgendaTable')->name('get.agenda.table');
    Route::get('get-committee-agenda-table','AgendaCrudController@getCommitteeAgendaTable')->name('get.committee.agenda.table');
    Route::get('agenda/{agenda_id}/show-agenda-detail','AgendaCrudController@showAgendaDetail');
    Route::post('agenda/{agenda_id}/hold-transfered-agenda', 'AgendaCrudController@holdTransferedAgenda');
    Route::post('agenda/{agenda_id}/unhold-transfered-agenda', 'AgendaCrudController@unholdTransferedAgenda');

    //report
    Route::get('report', 'ReportController@index');
    Route::post('report/excelexport','ReportController@export')->name('excel_export');
    Route::post('/getreportdata', 'ReportController@export');





}); // this should be the absolute last line of this file


Route::group(['middleware' => array_merge(
    (array) config('backpack.base.web_middleware', 'web'),
    (array) config('backpack.base.middleware_key', 'admin')
),
    'namespace'  => 'App\Http\Controllers\api',
], function () {

    Route::post('api/getMP/{ministry_id}', 'EcMpController@getEcMp');


});





