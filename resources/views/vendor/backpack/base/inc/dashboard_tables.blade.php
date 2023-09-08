<style>
    table,tr,th,td {
       border:1px solid black;
       text-align: center !important;
       vertical-align: middle !important;
    }

    table,thead,tr,th,td,h5,.card-title{
        font-family: "Poppins", sans-serif !important;    
    }
    .card-header{
        background: #32579F;
        color:white;
    }
    .table th{
        background-color: #eef2f5 !important;
    }
    .table thead th{
        background-color: #80eeee !important;
    }
    .last-row {
        background-color: #d7d4d4 !important;
        color:darkblue;
    }
    .last-row th{
        background-color: #dbdbdb !important;
        font-size:15px;
    }
    .title{
        height: 70px !important;
    }
    .title2{
        position: absolute;
        top:25px;
    }
    .title1{
        position: absolute;
        top:50px;
        left:25px;
    }
    .text-right{
        font-family: 'Poppins';
    }
 </style>


<div class="card">
    <div class="card-header">
        <h6 class="card-title pb-0 mb-0">लुम्बिनी प्रदेश(मन्त्रिपरिषद) बैठक निर्णय सम्बन्धि संक्षिप्त विवरण</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 pt-3">
                <table class="table">
                    <thead>
                      <tr>
                        <th>वर्ष/साल</th>
                        <th>बैठक संख्या</th>
                        <th>निर्णय संख्या</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($year_wise_data as $year => $year_data)
                            <tr>
                                <th scope="row">{{$year}}</th>
                                <td>{{$year_data['meeting_count']}}</td>
                                <td>{{$year_data['decision_count']}}</td>
                            </tr>
                        @endforeach
                        <tr class="last-row">
                            <th scope="row">जम्मा</th>
                            <th>{{$totals['total_meeting_count']}}</th>
                            <th>{{$totals['total_decision_count']}}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6 pt-3">
                <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>आ.व.</th>
                        <th>बैठक संख्या </th>
                        <th>निर्णय संख्या</th>
                        <th>कैफियत</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($fiscal_year_wise_data as $fiscal_year => $fiscal_year_data)
                            <tr>
                                <th scope="row">{{$fiscal_year}}</th>
                                <td>{{$fiscal_year_data['meeting_count']}}</td>
                                <td>{{$fiscal_year_data['decision_count']}}</td>
                                <td></td>
                            </tr>
                        @endforeach
                        <tr class="last-row">
                            <th scope="row">जम्मा</th>
                            <th>{{$totals['total_meeting_count']}}</th>
                            <th>{{$totals['total_decision_count']}}</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card-footer">

        <div class="card text-center font-weight-bold mb-3" style="border-top:5px solid green; border-bottom:5px solid lightgray; border-radius:20px">
                <div class="text-right p-2 mr-4">
                    <label for="selected_type">प्रकार : </label>
                    <select class="form-control-sm" name="selected_type" id="selected_type" onchange="loadDashboardData()">
                    <option value="yearWiseChart" {{$selected_type == 'yearWiseChart' ? 'selected' : ''}}>साल/वर्ष अनुसार बैठक/निर्णय संख्या</option>
                    <option value="fiscalYearWiseChart" {{$selected_type == 'fiscalYearWiseChart' ? 'selected' : ''}}>आर्थिक वर्ष अनुसार बैठक/निर्णय संख्या</option>
                </select>
                </div>
                <canvas id="{{$selected_type}}" style="background-color:white; border-radius:20px; height:400px;"></canvas>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header" >
        <h6 class="card-title pb-0 mb-0">आर्थिक बर्ष अनुसार विभिन्न प्रकारका निर्णय संख्या</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 pt-2">
                <table class="table">
                    <thead>
                        @php $counts = count($decision_type_wise_data);
                        if($counts){
                            $variable = round(76/$counts, 0);
                        }else{
                            $variable = 0;
                        }
                        @endphp
                        @foreach($decision_type_wise_data as $fiscal_year => $decision_type)
                        @endforeach
                        <tr class="title">
                            <th><span class="title1">निर्णय प्रकार </span><span class="title2">आर्थिक बर्ष</span></th>
                       
                            @foreach($decision_type_wise_data as $fiscal_year => $decision_type)
                                <th> {{$fiscal_year}}</th>
                            @endforeach
                            <th class="last-row">जम्मा </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total_decision_all = 0; @endphp
                        @foreach($decision_types as $decision_type)
                        @php
                        $total_decision = 0;
                        @endphp
                            <tr>
                                <th style="width: 155px;" scope="row" class="text-left">{{$decision_type->name_lc}}</th>
                                @foreach($decision_type_wise_data as $fiscal_year => $decision_type_data)
                                    @php 
                                    $total_decision += $decision_type_data[$decision_type->name_lc];
                                    $total_decision_all += $decision_type_data[$decision_type->name_lc];
                                    @endphp
                                    <td>{{$decision_type_data[$decision_type->name_lc]}}</td>
                                @endforeach
                                <th class="last-row">{{$total_decision}}</th>
                            </tr>
                        @endforeach
                        <tr class="last-row">
                            <th scope="row" style="margin-left: 500px;">जम्मा</th>
                            @foreach($decision_type_wise_data as $fiscal_year => $decision_type)
                                <th>{{$fiscal_year_wise_data[$fiscal_year]['decision_count']}}</th>
                            @endforeach
                            <th>{{$total_decision_all}} </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card text-center font-weight-bold mb-3" style="border-top:5px solid green; border-bottom:5px solid lightgray; border-radius:20px">
        <div class="text-right p-2 mr-4">
            <label for="select_fiscal_year">आ.व. : </label>
            <select class="form-control-sm text-black" name="select_fiscal_year" id="select_fiscal_year" onchange="loadDashboardData()">
                @foreach ($fiscal_years as $option)
                    <option value="{{ $option->id}}" {{ intval($selected_fiscal_year) === $option->id ? 'selected' : ''}}>{{ $option->code }}</option>
                @endforeach
            </select>
        </div>
        <canvas id="fiscalYearDecisionTypeChart" style="background-color:white; border-radius:20px; height:400px;"></canvas>
    </div>
</div>
