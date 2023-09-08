<div id="darbandiDiv" class="brand-card">
    <div class="brand-card-header bg-stack-overflow p-0 pt-1">
        <h6 style="color: white; font-weight:bold" id="section_title">जनसक्ति दरबन्दी विवरण</h6>
        <button class="p-2 printbtn" onclick="printDiv('darbandiDiv')" style="position:absolute;right:0;"><i style="font-size: 20px;" class="fa fa-print" aria-hidden="true"></i></button>
    </div>
    <div class="brand-card-body" style="overflow: auto">
        <table class="table">
            <thead>
                <tr>
                    <td rowspan="2" class="text-header">क्र.स.</td>
                    @if($ministry_field)
                    <td rowspan="2" class="text-header text-left">मन्त्रालय</td>
                    @endif
                    <td rowspan="2" class="text-header text-left">श्रेणी तह</td>
                    <td rowspan="2" class="text-header">कुल स्वीकृत दरबन्दी</td>
                    <td colspan="2" class="text-header">हाल कार्यरत जनसक्ति </td>
                    <td rowspan="2"  class="text-header">कूल रिक्त दरबन्दी </td>
                </tr>
                <tr>
                    <td class="text-header">स्थाई</td>
                    <td class="text-header">करार</td>
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
                        <td rowspan="{{$row_count}}" class="text-left">{{$res['name']}}</td>
                        @endif
                        @if(!is_null($res['data']))
                            <td class="text-left text-blue font-weight-bold">{{$res['data'][0]->level}}</td>
                            <td class="text-brown font-weight-bold">{{$res['data'][0]->total_darbandi}}</td>
                            <td class="text-darkgreen font-weight-bold">{{$res['data'][0]->perm_darbandi}}</td>
                            <td class="text-darkgreen font-weight-bold">{{$res['data'][0]->temp_darbandi}}</td>
                            <td class="text-yellow font-weight-bold">{{$res['data'][0]->vacant_darbandi}}</td>
                        @else
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
                                    <td class="text-left text-blue font-weight-bold">{{$item->level}}</td>
                                    <td class="text-brown font-weight-bold">{{$item->total_darbandi}}</td>
                                    <td class="text-darkgreen font-weight-bold">{{$item->perm_darbandi}}</td>
                                    <td class="text-darkgreen font-weight-bold">{{$item->temp_darbandi}}</td>
                                    <td class="text-yellow font-weight-bold">{{$item->vacant_darbandi}}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    {{--chart section--}}
    <div class="card-footer">
          <div class="row">
              <canvas id="darbandiChart"></canvas>
          </div>
    </div>

</div>
