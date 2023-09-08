<div id="officeDiv" class="brand-card">
    <div class="brand-card-header bg-stack-overflow p-0 pt-1">
        <h6 style="color: white; font-weight:bold" id="section_title">कार्यालय व्यवस्थापन विवरण
        </h6>
        <button class="p-2 printbtn" onclick="printDiv('officeDiv')" style="position:absolute;right:0;"><i style="font-size: 20px;" class="fa fa-print" aria-hidden="true"></i></button>
    </div>
    <div class="brand-card-body" style="overflow: auto">
        <table class="table">
            <thead>
                <tr>
                    <td rowspan="3" class="text-header">क्र.स.</td>
                    @if($ministry_field)
                    <td rowspan="3" class="text-header text-left">मन्त्रालय</td>
                    @endif
                    <td rowspan="3" class="text-header text-left small-width-80">आन्तरिक
                        नियन्त्रण प्रणाली</td>
                    <td rowspan="3" class="text-header text-left small-width-80">महिला मैत्री
                        शौचालय</td>
                    <td rowspan="3" class="text-header text-left small-width-80">अपाङ्ग मैत्री
                        शौचालय </td>
                    <td rowspan="3" class="text-header text-left small-width-120">सुचना
                        प्रविधिको प्रयोग, <br /> नियमित सुचना प्रवाह,<br />Website, अपडेट
                        भए/नभएको </td>
                    <td colspan="4" class="text-header text-center ">सवारी साधन व्यवस्थापन </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-header text-center">चालु अवस्थामा</td>
                    <td colspan="2" class="text-header text-center">अपुग अवस्थामा</td>
                </tr>
                <tr>
                    <td class="text-header small-width-70">दुई पांग्रे <br>🚲</td>
                    <td class="text-header small-width-70">चार पांग्रे <br>🚍</td>
                    <td class="text-header small-width-70">दुई पांग्रे <br>🚲</td>
                    <td class="text-header small-width-70">चार पांग्रे <br>🚍</td>
                </tr>
            </thead>

            <tbody id="office_details_table_body">
                @foreach($final_result as $key => $res)
                    <tr class="data-tr">
                        <td >{{$loop->iteration}}</td>
                        @if($ministry_field)
                        <td class="text-left">{{$res['ministry_name']}}</td>
                        @endif
                        <td>{{$res['internal_control_system']}}</td>
                        <td><i class="fas {{$res['ladies_friendly_toilet']==1?'fa-check-circle text-success':($res['ladies_friendly_toilet']==2?'fa-spinner': 'fa-times-circle text-danger')}}"></i></td>
                        <td><i class="fas {{$res['disable_friendly_toilet']==1?'fa-check-circle text-success':($res['disable_friendly_toilet']==2?'fa-spinner': 'fa-times-circle text-danger')}}"></i></td>
                        <td><i class="fas {{$res['is_information_updated']==1?'fa-check-circle text-success':'fa-times-circle text-danger'}}"></i></td>
                        <td class="text-darkgreen font-weight-bold">{{$res['current_two_wheeler']}}</td>
                        <td class="text-darkgreen font-weight-bold">{{$res['current_four_wheeler']}}</td>
                        <td class="text-brown font-weight-bold">{{$res['required_two_wheeler']}}</td>
                        <td class="text-brown font-weight-bold">{{$res['required_four_wheeler']}}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div> 

{{--chart section--}}
<div class="card-footer">

    <div class="card text-center font-weight-bold mb-3" style="border-top:5px solid green; border-bottom:5px solid lightgray; border-radius:20px">
        {{-- <div class="row"> --}}
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
            <button onclick="exportChart('officeChart','{{$chart_title}}')" class="export-button">Export Chart</button>

            </div>
        {{-- </div> --}}
        <canvas id="officeChart" style="background-color:white; border-radius:20px; height:400px;"></canvas>
    </div>
</div>