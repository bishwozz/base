$(document).ready(function () {
    $('select[name="reporting_interval_fourth_quarter"]').prop('disabled', true);
    $('select[name="reporting_interval_yearly_mid"]').prop('disabled', true);
});

$('select[name="fiscal_year_id"]').on('change', function() {
    var fiscal_year_id =  $('select[name="fiscal_year_id"]').val();
    var month_id =  $('select[name="month_id"]').val();
    if(fiscal_year_id != ''){
        if(month_id == 0){
            $('select[name="reporting_interval_fourth_quarter"]').prop('disabled', false);
            $('select[name="reporting_interval_yearly_mid"]').prop('disabled', false);
        }else{
            $('select[name="reporting_interval_fourth_quarter"]').prop('disabled', true);
            $('select[name="reporting_interval_yearly_mid"]').prop('disabled', true);
        }

    }else{
        $('select[name="reporting_interval_fourth_quarter"]').prop('disabled', true);
        $('select[name="reporting_interval_yearly_mid"]').prop('disabled', true);
    }
});

$('select[name="reporting_interval_fourth_quarter"]').on('change', function() {
    var reporting_interval_fourth_quarter =  $('select[name="reporting_interval_fourth_quarter"]').val();
    if(reporting_interval_fourth_quarter != "" && reporting_interval_fourth_quarter != "0"){
        $('select[name="reporting_interval_yearly_mid"]').prop('disabled', true);
        $('select[name="month_id"]').prop('disabled', true);
    }else{
        $('select[name="reporting_interval_yearly_mid"]').prop('disabled', false);
        $('select[name="month_id"]').prop('disabled', false);
    }
});



$('select[name="reporting_interval_yearly_mid"]').on('change', function() {
    var reporting_interval_yearly_mid =  $('select[name="reporting_interval_yearly_mid"]').val();
    if(reporting_interval_yearly_mid != "" && reporting_interval_yearly_mid != "0"){
        $('select[name="reporting_interval_fourth_quarter"]').prop('disabled', true);
        $('select[name="month_id"]').prop('disabled', true);
    }else{
        $('select[name="reporting_interval_fourth_quarter"]').prop('disabled', false);
        $('select[name="month_id"]').prop('disabled', false);
    }
});


$('select[name="reporting_interval_yearly_mid"]').on('change', function() {
    var reporting_interval_yearly_mid =  $('select[name="reporting_interval_yearly_mid"]').val();
    if(reporting_interval_yearly_mid != "" && reporting_interval_yearly_mid != "0"){
        $('select[name="reporting_interval_fourth_quarter"]').prop('disabled', true);
    }else{
        $('select[name="reporting_interval_fourth_quarter"]').prop('disabled', false);
    }
});


$('select[name="month_id"]').on('change', function() {
    var month_id =  $('select[name="month_id"]').val();
    if(month_id != ''){
        if(month_id == 0){
            $('select[name="reporting_interval_fourth_quarter"]').prop('disabled', false);
            $('select[name="reporting_interval_yearly_mid"]').prop('disabled', false);
        }else{
            $('select[name="reporting_interval_fourth_quarter"]').prop('disabled', true);
            $('select[name="reporting_interval_yearly_mid"]').prop('disabled', true);
        }
    }else{
        $('select[name="reporting_interval_fourth_quarter"]').prop('disabled', true);
        $('select[name="reporting_interval_yearly_mid"]').prop('disabled', true);
    }
});
