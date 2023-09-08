<div id="progressDiv" class="brand-card">
    <div class="brand-card-header bg-stack-overflow p-0 pt-1">
        <h6 style="color: white; font-weight:bold" id="section_title"> वित्तीय तथा भौतिक प्रगति
            विवरण</h6>
            <button class="p-2 printbtn" onclick="printDiv('progressDiv')" style="position:absolute;right:0;"><i style="font-size: 20px;" class="fa fa-print" aria-hidden="true"></i></button>
    </div>
    <div class="brand-card-body" style="overflow: auto">
        <table class="table">
            <thead>
                <tr>
                    <td rowspan="2" class="text-header">क्र.स.</td>
                    @if($ministry_field)
                    <td rowspan="2" class="text-header text-left">मन्त्रालय</td>
                    @endif
                    <td rowspan="2" class="text-header text-left">महिना</td>
                    <td colspan="3" class="text-header">वित्तीय प्रगति (%)</td>
                    <td colspan="3" class="text-header">भौतिक प्रगति (%) </td>
                    <td rowspan="2" class="text-header">बेरुजु फछर्यौट </td>
                </tr>
                <tr>
                    <td class="text-header">चालु</td>
                    <td class="text-header">पूँजीगत</td>
                    <td class="text-header">जम्मा</td>
                    <td class="text-header">चालु</td>
                    <td class="text-header">पूँजीगत</td>
                    <td class="text-header">जम्मा</td>
                </tr>
            </thead>

            <tbody id="financial_physical_table_body">
                @foreach($final_result as $key => $res)
                    @php
                        $row_count = !is_null($res['data']) ? count($res['data']) : '';
                    @endphp
                    <tr class="data-tr">
                        <td rowspan="{{$row_count}}">{{$loop->iteration}}</td>
                        @if($ministry_field)
                        <td rowspan="{{$row_count}}" class="text-left">{{$res['ministry_name']}}</td>
                        @endif
                        @if(!is_null($res['data']))
                            <td class="text-left text-blue font-weight-bold">{{$res['data'][0]->month}}</td>
                            <td>{{$res['data'][0]->current_progress_financial}}</td>
                            <td>{{$res['data'][0]->capital_progress_financial}}</td>
                            <td class="text-brown font-weight-bold">{{$res['data'][0]->total_progress_financial}}</td>
                            <td>{{$res['data'][0]->current_progress_physical}}</td>
                            <td>{{$res['data'][0]->capital_progress_physical}}</td>
                            <td class="text-darkgreen font-weight-bold">{{$res['data'][0]->total_progress_physical}}</td>
                            <td>{{$res['data'][0]->beruju_farchyat_percent}}</td>
                        @else
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        @endif
                    </tr>
                    @if(!is_null($res['data']))
                        @foreach($res['data'] as $index => $item)
                            @if($index > 0)
                                <tr class="data-tr">
                                    <td class="text-left text-blue font-weight-bold">{{$item->month}}</td>
                                    <td>{{$item->current_progress_financial}}</td>
                                    <td>{{$item->capital_progress_financial}}</td>
                                    <td class="text-brown font-weight-bold">{{$item->total_progress_financial}}</td>
                                    <td>{{$item->current_progress_physical}}</td>
                                    <td>{{$item->capital_progress_physical}}</td>
                                    <td class="text-darkgreen font-weight-bold">{{$item->total_progress_physical}}</td>
                                    <td>{{$item->beruju_farchyat_percent}}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
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
            <button onclick="exportChart('progressChart','{{$chart_title}}')" class="export-button">Export Chart</button>

            </div>
        {{-- </div> --}}
        <canvas id="progressChart" style="background-color:white; border-radius:20px; height:400px;"></canvas>
    </div>
</div>
