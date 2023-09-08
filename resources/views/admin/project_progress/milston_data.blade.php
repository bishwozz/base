<div class="card">
    <div class="row mt-2">
        <div class="col">
            <center>
                <h5 class="font-weight-bold">कार्यक्रम माइलस्टोन प्रगति</h5>
            </center>
        </div>
    </div>
    <div class="row">
    </div>
    <div class="row mt-0">
        <div class="col-md-12">
            <div class="col-md-12 p-2" style="overflow-x:auto;">
                <table id="milestone_data_table" class="styled-table com-md-12">
                    <thead>
                        <tr>
                            <td rowspan="2">क्र.स.</td>
                            <td rowspan="2">माइलस्टोन</td>
                            <td rowspan="2">पछिल्लो अवस्था</td>
                            <td rowspan="2">हाल को अवस्था</td>
                            <td rowspan="2" class="col-md-3">माइलस्टोन प्रगति</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $key => $data)
                            <tr>
                                <td class="report-data">{{ $key+1 }}</td>
                                <td class="report-data">{{ $data['name'] }}</td>
                                @if($data['is_earlier_completed'])
                                <td class="report-data"><span class="bg-success px-3 py-1 rounded">Completed</span></td>
                                
                                @else
                                <td class="report-data">{{ $data['earlierStatusName'] }}</td>
                                @endif
                                <input type="hidden" name="milestone_id[]" value="{{$data['id']}}">
                                <td class="report-data">
                                    @if($data['is_completed'])
                                    <input type="hidden" name="milestone_status[]" value="{{$data['is_completed_status_id']}}">
                                    <span class="bg-success px-3 py-1 rounded">Completed</span>
                                    @else
                                    <select class="form-control" name="milestone_status[]"  onchange="setMilestoneProgress(this)" required="required">
                                        <option value="">स्थिति चयन गर्नुहोस्</option>
                                        @foreach ($data['status'] as $status)
                                            <option value="{{ $status->id }}" data-score="{{ $status->progress_percent }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </td>
                                <td class="report-data col-md-3">
                                    <div class="input-group">
                                        @if($data['is_completed'])
                                        <input class="form-control milestone-progress" type="number" name="milestone_progress[]" value="100" max="100" step="any"  readonly="">
                                        @else
                                        <input class="form-control milestone-progress" type="number" name="milestone_progress[]" max="100" step="any"  required="">
                                        @endif
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="percentage-addon">%</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                {{-- <button id="add_progress_data" class="btn btn-primary">Add progress</button> --}}
            </div>
        </div>
    </div>
    
</div>
</div>
<script>

function setMilestoneProgress(selectElement) {
    var score = $(selectElement).find('option:selected').data('score');

    $(selectElement).closest('tr').find('.milestone-progress').val(score);
}

</script>



