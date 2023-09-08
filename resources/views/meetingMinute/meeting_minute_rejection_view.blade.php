<div class="card" style="width:90%">
    <div class="card-header">
        <h3>{{ trans('common.rejectionReason') }} <span class="text-danger">*</span><small class="text-danger"> (एक पटक बैठक माइनुट अस्वीकृत गर्नुभएको खण्डमा त्यसलाई पुन: सम्पादन गर्न सकिने छैन!!)</small></h3>
    </div>
    <div class="card-body">
        <form action="{{ route('custom.meetingMinuteRejection',$id) }}" method="post">
            @csrf
            <textarea name="remarks" id="second_level_rejection_remarks" rows="10" style="width:100%" required></textarea>
            <button type="submit" class="btn btn-md btn-success"><i class="la la-save"></i> Submit</button>
        </form>
    </div>
</div>