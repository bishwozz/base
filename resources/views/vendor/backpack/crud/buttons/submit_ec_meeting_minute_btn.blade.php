@if(($entry->is_submitted == true))
@else
    @if($entry->getStatusRejection())
    <a onclick="ECABINET.confirmationMeetingMinute('{{$entry->id}}',this)" id="submit-meeting-minute"  class="btn btn-sm btn-info"  title="बैठक माइनुट पुन पेश गर्नुहोस">{{ trans('common.resubmitAgenda')}}</a>
    @else
    <a onclick="ECABINET.confirmationMeetingMinute('{{$entry->id}}',this)" id="submit-meeting-minute"  class="btn btn-sm btn-info"  title="बैठक माइनुट पेश गर्नुहोस">{{ trans('common.submitAgenda')}}</a>
    @endif
@endif