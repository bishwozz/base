@if(($entry->is_submitted == true))
@else
    @if($entry->getStatusRejection())
    <a onclick="ECABINET.confirmationMeetingRequest('{{$entry->id}}',this)" id="submit-meeting-request"  class="btn btn-sm btn-info"  title="बैठक आहवान पुन पेश गर्नुहोस">{{ trans('common.resubmitAgenda')}}</a>
    @else
    <a onclick="ECABINET.confirmationMeetingRequest('{{$entry->id}}',this)" id="submit-meeting-request"  class="btn btn-sm btn-info"  title="बैठक आहवान पेश गर्नुहोस">{{ trans('common.submitAgenda')}}</a>
    @endif
@endif