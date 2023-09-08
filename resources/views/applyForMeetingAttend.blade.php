<div class="card">
    <div class="card-header">
        <h3 style="text-align: center;">Attend the meeting</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('applyformeetingattendconfirmation',$id) }}" method="POST">
            @csrf
            <input type="hidden" name="mp_id" value="{{ $mp_id }}">
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="font-weight-bold">बैठकमा उपस्थित हुने हो? <span class="text-danger">*</span></label>
                    <br>
                    <input type="radio" name="meeting_attend" id="meeting_attend_yes" class="meeting_attend" value="true" {{ isset($apply_for_meeting_attendance) ? ($apply_for_meeting_attendance == true ? 'checked' : '') : 'checked' }}>
                    <label class="font-weight-bold" for="meeting_attend_yes">{{ trans('common.yes') }}</label>
                        &nbsp;
                    <input type="radio" name="meeting_attend" id="meeting_attend_no" class="meeting_attend" value="false" {{ isset($apply_for_meeting_attendance) ? ($apply_for_meeting_attendance == false ? 'checked' : '') : ''}}>
                    <label class="font-weight-bold" for="meeting_attend_no">{{ trans('common.no') }}</label>
                </div>
                <div class="form-group col-md-6">
                    <label class="font-weight-bold" for="meeting_requested_date_bs">{{ trans('common.requested_date_bs') }} <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="requested_date_bs" id="meeting_requested_date_bs" value="{{ $requested_date_bs }}" readonly>
                </div>
                <div class="form-group col-md-6">
                    <label class="font-weight-bold" for="meeting_requested_date_ad">{{ trans('common.requested_date_ad') }} <span class="text-danger">*</span></label>
                    <input class="form-control" type="date" name="requested_date_ad" id="meeting_requested_date_ad" value="{{ $requested_date_ad }}" readonly>
                </div>
                <div class="form-group col-md-12">
                    <label class="font-weight-bold" for="remarks">{{ trans('common.remarks') }}</label>
                    <textarea class="form-control" name="remarks" id="remarks">{{ isset($remarks) ? $remarks : null }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-md btn-success"><i class="la la-save"></i> Submit</button>
        </form>
    </div>
</div>