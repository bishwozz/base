
// import Echo from 'laravel-echo';


$(document).ready(function(){
    meeting_request_id = $('#minute_meeting_request option:selected').val();
    if(meeting_request_id) getMeetingMinuteDetails(meeting_request_id);
    if(meeting_request_id) getMeetingMinuteDetailsDirectAgenda(meeting_request_id);
    request_id = $('#agenda-body-table-direct-agenda-from-request').data('id');
    if(request_id) getMeetingMinuteDetailsDirectAgendaFromRequest(request_id);

    // Echo.private('agenda-updates')
    // .listen('.AgendaApprovedRejected', (event) => {
    //     // Handle the event data here
    //     console.log('Received event:', event);
    // });

    //   Echo.private(`data.${roles_id}`)
    // .listen('AgendaApprovedRejected', (e) => {
    //     console.log(e);
    // });

    jQuery.ajax({
        url: '/admin/notifications',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          var count = data.count;
          const notificationCountElement = document.getElementById('notification-count');
          notificationCountElement.textContent = count
          if(count === 0){
  
            notificationCountElement.style.display = 'none';
          }else{
  
            notificationCountElement.style.display = 'block';
          }
        },
        error: function(error) {
          console.error('Failed to fetch notifications:');
        }
      });
});
$('#minute_meeting_request').change(function (e) { 
    meeting_request_id = $('#minute_meeting_request option:selected').val();
    getMeetingMinuteDetails(meeting_request_id);
    console.log(meeting_request_id)
    getMeetingMinuteDetailsDirectAgenda(meeting_request_id);
});



function getMeetingMinuteDetails(meeting_request_id){
    $('#agenda-body-table').empty();
    if(meeting_request_id){
        $.ajax({
            url: '/get-meeting-request-detail',
            type: 'GET',
            data: {
                'meeting_request_id': meeting_request_id
            },
            success: function(response) {
                $("#minute_fiscal_year").val(response['meeting_request_detail']['fiscal_year_id']).trigger('change');
                $("#meeting_date").val(response.meeting_request_detail.start_date_bs).trigger('change');

                $('#agenda-body-table').html(response.agenda);
                

                // $("#meeting_content").summernote('code', response.minuteFormat);
            }
        });
    }else{
        $("#minute_fiscal_year").val(null).trigger('change');
        $('#agenda-body-table').val('');
        $("#meeting_date").val('').trigger('change');

    }

};
function getMeetingMinuteDetailsDirectAgendaFromRequest(meeting_request_id){
    $('#agenda-body-table-direct-agenda-from-request').empty();
    if(meeting_request_id){
        $.ajax({
            url: '/admin/get-meeting-request-detail-direct-agenda-from-request',
            type: 'GET',
            data: {
                'meeting_request_id': meeting_request_id
            },
            success: function(response) {
                // $("#minute_fiscal_year").val(response['meeting_request_detail']['fiscal_year_id']).trigger('change');
                // $("#meeting_date").val(response.meeting_request_detail.start_date_bs).trigger('change');

                $('#agenda-body-table-direct-agenda-from-request').html(response.agenda);
                

                // $("#meeting_content").summernote('code', response.minuteFormat);
            }
        });
    }else{
        // $("#minute_fiscal_year").val(null).trigger('change');
        // $('#agenda-body-table-direct-agenda-from-request').val('');
        // $("#meeting_date").val('').trigger('change');

    }

};
function getMeetingMinuteDetailsDirectAgenda(meeting_request_id){
    $('#agenda-body-table-direct-agenda').empty();
    if(meeting_request_id){
        $.ajax({
            url: '/get-meeting-request-detail-direct-agenda',
            type: 'GET',
            data: {
                'meeting_request_id': meeting_request_id
            },
            success: function(response) {
                $("#minute_fiscal_year").val(response['meeting_request_detail']['fiscal_year_id']).trigger('change');
                $("#meeting_date").val(response.meeting_request_detail.start_date_bs).trigger('change');

                $('#agenda-body-table-direct-agenda').html(response.agenda);
                

                // $("#meeting_content").summernote('code', response.minuteFormat);
            }
        });
    }else{
        $("#minute_fiscal_year").val(null).trigger('change');
        $('#agenda-body-table-direct-agenda').val('');
        $("#meeting_date").val('').trigger('change');

    }

};

$('form').submit(function(e) {
    $(':disabled').each(function(e) {
        $(this).removeAttr('disabled');
    });
    buildMinuteAttendanceData();
});

//collection attendance data for minute
function buildMinuteAttendanceData(){ 
    var items=[];
    var ministry_decisions=[];
    $('#meeting_attendance_table tbody tr').each(function() {
        item = {
            'member_id' : $(this).find('td:nth-child(2)').data('member_id'),
            'att_status' : $(this).find('td:nth-child(4) option:selected').val(),
            'ministry_id' : $(this).find('td:nth-child(5)').data('ministry_id'),

        };

        items.push(item);
    });
    items = JSON.stringify(items);

    $('input[name=ministry_attendance_status]').val(items);

    // // set ministry descision for each agenda;
    // $('#meeting_agenda_table tbody tr').each(function() {
    //     decision = {
    //         'agenda_id' : $(this).find('td:nth-child(5)').data('agenda_id'),
    //         'ministry_decision' : $(this).find('td:nth-child(5)').html(),
    //     };

    //     ministry_decisions.push(decision);
    // });
    // ministry_decisions = JSON.stringify(ministry_decisions);

    // $('input[name=ministry_agenda_decisions]').val(ministry_decisions);
}

$(document).ready(function(){
    // isVerified();
    // $('#meeting_minute_is_verified').change(function () { 
    //     isVerified();
    // });

    appSetting_letter_head();
    $('form input[name=letter_head_title_1]').keyup(appSetting_letter_head);
    $('form input[name=letter_head_title_2]').keyup(appSetting_letter_head);
    $('form input[name=letter_head_title_3]').keyup(appSetting_letter_head);
    $('form input[name=letter_head_title_4]').keyup(appSetting_letter_head);
});

function isVerified(){
    is_verified = $('input[name="is_verified"]').val();
    if(is_verified == 0){
        $('#verified_date_ad,#verified_date_bs').attr('disabled',true);
    }else{
        $('#verified_date_ad,#verified_date_bs').attr('disabled',false);
    } 
}

function appSetting_letter_head(){
    var title_1 = $('form input[name=letter_head_title_1]').val(),
    title_2 = $('form input[name=letter_head_title_2]').val(),
    title_3 = $('form input[name=letter_head_title_3]').val(),
    title_4 = $('form input[name=letter_head_title_4]').val();
    $('#letter_head_title_1_label').html(title_1);
    $('#letter_head_title_2_label').html(title_2);
    $('#letter_head_title_3_label').html(title_3);
    $('#letter_head_title_4_label').html(title_4);
}