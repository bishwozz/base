<style>
    .checkbox-wrapper {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.checkbox {
  display: none;
}

.checkbox-label {
  display: flex;
  align-items: center;
  cursor: pointer;
}

.checkbox-icon {
  display: inline-block;
  width: 20px;
  height: 20px;
  border: 2px solid #ccc;
  border-radius: 3px;
  margin-right: 10px;
  position: relative;
}

.checkbox-icon::after {
  content: "";
  display: none;
  width: 10px;
  height: 5px;
  border: solid #fff;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
  position: absolute;
  top: 4px;
  left: 5px;
}

.checkbox:checked + .checkbox-label .checkbox-icon::after {
  display: block;
}

.checkbox-text {
  font-size: 14px;
}

button {
  /* float: right; */
  margin-top: 10px;
}

</style>
<div class="card">
    <div class="card-header">
        <h3 style="text-align: center;">{{ trans('common.sendEmail') }}</h3>
    </div>
    <div class="card-body" id="email-sending">
        @if(Request::segment(2) == 'ec-meeting-request')
            <form action="{{ route('sendemail',$id) }}" method="POST">
        @else
            <form action="{{ route('sendMeetingMinuteMail',$id) }}" method="POST">
        @endif
            @csrf
            <div class="row">
                @php
                    $i = 1;
                @endphp

                @foreach($ministrys as $ministry)
                    <div class="form-group col-md-12 font-weight-bold">
                        <input type="checkbox" name="ministry[]" id="ministry_{{$ministry->id}}" value="{{$ministry->id}}" onclick="checkAll({{$ministry->id}})" {{ $ministry->is_selected == true? 'checked' : '' }}>
                        <label class="text-primary" for="ministry_{{$ministry->id}}"><b>{{$ministry->name_lc}}</b></label>
                        @foreach($members as $member)
                            @if($ministry->id == $member->ministry_id)
                                <div class="form-group col-md-12">
                                    <input type="checkbox" class="ministry_{{$ministry->id}}" name="ministry_member[]" id="ministry_member_{{$member->id}}_{{$i}}" value="{{$member->email}}" {{$member->is_selected == true? 'checked' : '' }}>
                                    {{$member->is_minute_mailed?'Send Again':''}}
                                    <input type="hidden"  name="ministry_member_phone[]" id="ministry_member_{{$member->id}}_{{$i}}" value="{{$member->mobile_number}}" >
                                    <input type="hidden"  name="meeting_request_date_bs" id="meeting_request_date_bs" value="{{ isset($meeting_request) ? $meeting_request->start_date_bs : '' }}" >
                                    <input type="hidden"  name="meeting_request_time" id="meeting_request_time" value="{{ isset($meeting_request) ? $meeting_request->start_time : '' }}" >
                                    {{$member->is_minute_mailed?'Send Again':''}}
                                    <label for="ministry_member_{{$member->id}}_{{$i}}"><b>{{$member->name_lc}}</b> <small>({{$member->email}})- {{ $member->mobile_number}}</small></label>
                                </div>
                            @endif
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </div>
                @endforeach
            </div>


            <div class="card">
                <div class="card-header">
                    पठाउनुहोस्
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <input type="checkbox" id="is_email" name="is_email" checked>
                          </div>
                        </div>
                        <label for="is_email"><b> इमेल पठाउने हो ? <i class="fa fa-envelope"></i></b></label>
                      </div>
                      
                      @if(isset($meeting_request))
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <input type="checkbox" id="is_sms" name="is_sms">
                          </div>
                        </div>
                        <label for="is_sms"><b> एसएमएस पठाउने हो ? <i class="fa fa-commenting"></i></b></label>
                    </div>
                    @endif

                    <button type="submit" class="btn btn-md btn-success" onclick="ECABINET.loading(true, 'इमेल पठाउँदै')">
                        <i class="la la-save"></i> Send
                      </button>

                </div>
              </div>



        </form>
    </div>
</div>

<script>

    function checkAll(ministry_id){
        if($('#ministry_'+ministry_id).is(':checked')){
            $('.ministry_'+ministry_id).attr('checked', true);
        }else{
            $('.ministry_'+ministry_id).attr('checked', false);
        }
    }
</script>
