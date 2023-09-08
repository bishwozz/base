
<div id="biddingDiv" class="brand-card">
    <div class="brand-card-header bg-stack-overflow p-0 pt-1">
        <h6 style="color: white; font-weight:bold" id="section_title">सार्वजनिक खरीद तथा ठेक्का
            व्यवस्थापन</h6>
            <button class="p-2 printbtn" onclick="printDiv('biddingDiv')" style="position:absolute;right:0;"><i style="font-size: 20px;" class="fa fa-print" aria-hidden="true"></i></button>
    </div>
    <div class="brand-card-body" style="overflow: auto">
        <table class="table">
            <thead>
                <tr>
                    <td rowspan="3" class="text-header">क्र.स.</td>
                    @if($ministry_field)
                    <td rowspan="2" class="text-header text-left">मन्त्रालय</td>
                    @endif
                    <td rowspan="3" class="text-header text-left">सार्वजनिक खरीद तथा ठेक्का
                        व्यवस्थापन</td>
                    <td rowspan="3" class="text-header text-left">विद्युतीय ठेक्का प्रणाली
                        आव्हान ठेक्का संख्या</td>
                    <td rowspan="3" class="text-header text-left">कुल संचलानमा रहेको ठेक्का
                        संख्या </td>
                    <td rowspan="3" class="text-header text-left">अनुगमन तथा निरिक्षणको अवस्था
                    </td>
                    <td colspan="4" class="text-header text-center ">अनुगमन तथा निरिक्षण सम्पन्न
                        भएको जम्मा संख्या </td>
                </tr>
            </thead>

            <tbody id="bidding_contract_details_table_body">
                @foreach($final_result as $key => $res)
                    <tr class="data-tr">
                        <td >{{$loop->iteration}}</td>
                        @if($ministry_field)
                        <td class="text-left">{{$res['ministry_name']}}</td>
                        @endif
                        <td class="text-left">{{$res['public_procurement']}}</td>
                        <td class="text-darkgreen font-weight-bold">{{$res['online_procurement_contract']}}</td>
                        <td class="text-red font-weight-bold">{{$res['total_operating_contract']}}</td>
                        <td class="text-left">{{$res['inspection_monitoring_period']}}</td>
                        <td class="text-blue font-weight-bold">{{$res['inspection_count']}}</td>
                        
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
            <button onclick="exportChart('biddingChart','{{$chart_title}}')" class="export-button">Export Chart</button>

            </div>
        {{-- </div> --}}
        <canvas id="biddingChart" style="background-color:white; border-radius:20px; height:400px;"></canvas>
    </div>
</div>