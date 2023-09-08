
<div id="milestoneDiv" class="brand-card">
    <div class="brand-card-header bg-stack-overflow p-0 pt-1">
        <h6 style="color: white; font-weight:bold" id="section_title">योजना क्रियाकलाप अनुसार प्रगति विवरण</h6>
            <button class="p-2 printbtn" onclick="printDiv('milestoneDiv')" style="position:absolute;right:0;"><i style="font-size: 20px;" class="fa fa-print" aria-hidden="true"></i></button>
    </div>
    <div class="brand-card-body" style="overflow: auto">
        <table class="table">
            <thead>
                <tr>
                    <td rowspan="3" class="text-header">क्र.स.</td>
                    @if($ministry_field)
                    <td rowspan="2" class="text-header text-left">मन्त्रालय</td>
                    @endif
                    <td rowspan="3" class="text-header text-left">क्रियाकलाप संख्या</td>
                    <td rowspan="3" class="text-header text-left">कार्य सुरु नभएको</td>
                    <td rowspan="3" class="text-header text-left">काम भइरहेको</td>
                    <td rowspan="3" class="text-header text-left">काम सम्पन्न भएको </td>
                
                </tr>
            </thead>

            <tbody id="milestone_details_table_body">
                @foreach($final_result as $key => $res)
                    @php
                        $not_started = $res['data']['not_started'];
                        $wip =$res['data']['wip'];
                        $completed =$res['data']['completed'];
                        $total = $not_started+$wip+$completed;
                    @endphp

                    <tr class="data-tr">
                        <td >{{$loop->iteration}}</td>
                        @if($ministry_field)
                            <td class="text-left">{{$res['ministry_name']}}</td>
                        @endif
                        <td class="text-right text-blue font-weight-bold">{{$total}}</td>
                        <td class="text-right text-red font-weight-bold">{{$not_started}}</td>
                        <td class="text-right text-violet font-weight-bold">{{$wip}}</td>
                        <td class="text-right text-darkgreen font-weight-bold">{{$completed}}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

{{--chart section--}}
<div class="card-footer">

    <div class="card text-center font-weight-bold mb-3" style="border-top:5px solid green; border-bottom:5px solid lightgray; border-radius:20px">
        <div class="text-right p-2">
            <select class="form-control-sm" name="selected_ministry_id" id="selected_ministry_id"
            onchange="loadDashboardData()">
                @if($ministry_field)
                    @foreach ($ministries as $option)
                        <option value="{{ $option->getKey() }}" {{ intval($selected_ministry_id) === $option->getKey() ? 'selected' : ''}}>{{ $option->name_lc }}</option>
                    @endforeach
                @else
                    <option value="{{ backpack_user()->ministry_id }}" selected>{{backpack_user()->ministry->name_lc }}</option>
                @endif
                </select>
            <button onclick="exportChart('milestoneChart','{{$chart_title}}')" class="export-button">Export Chart</button>
        </div>
        <canvas id="milestoneChart" style="background-color:white; border-radius:20px; height:400px; margin-bottom:30px;"></canvas>
    </div>
</div>