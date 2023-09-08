@extends(backpack_view('blank'))
@section('content')
<style>
    .page-title{
        font-family: 'Poppins';
    }
</style>
    <div class="row" style="padding-right: 1em;padding-left: 1em; padding-bottom: 1em;">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right" style="float: right;">
                    <div class="d-flex">
                        <table style="border:none;">
                            <tr style="border:none;">
                                <td style="border:none;">
                                    <div>
                                        @if(!backpack_user()->ministry_id)
                                            <label for="ministry_id" class="font-weight-bold">मन्त्रालय&nbsp;</label>
                                            <select class="form-control-sm" name="ministry_id" id="ministry_id" onchange="loadDashboardData()">
                                                <option value="all" selected>सबै</option>
                                                @foreach ($ministries as $option)
                                                <option value="{{ $option->getKey() }}">{{ $option->name_lc }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="hidden" name="ministry_id" id="ministry_id" value={{backpack_user()->ministry_id}}>
                                        @endif
                                    
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <h4 class="page-title">{{$lang == 'lc'? 'ड्यासबोर्ड' : 'Dashboard' }}</h4>
            </div>
        </div>
    </div>
    <span id="dashboard_table"></span>

    <script src="{{asset('homepage/js/chart.min.js')}}"></script>
    <script>
        $(document).ready(function() {
        // getDash(fiscal_year_id = 'all');
        loadDashboardData();

        // $('#fiscal_year_id').change(function() {
        //     getDash($('#fiscal_year_id').val());
        // });
        $('#ministry_id').change(function() {
            loadDashboardData();
        });
    });

        // function getDash(fiscal_year_id = "all"){

        //     var $base_url ='/admin/dashboard'
        //     $.ajax({
        //         url: $base_url + '/dashboard-data',
        //         type: "get",
        //         data: {
        //             fiscal_year_id:fiscal_year_id
        //         },
        //         headers: {
        //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        //         },
        //         success: function (response) {
        //             if (response) {

        //                 $("#dashboardDataT1").html("");
        //                 // $("#dashboardDataT2").html("");
        //                 $("#dashboardDataT1").append(response);
        //                 // $("#dashboardDataT2").append(response);
                        
        //             }
        //         },
        //     });
        // };
        function loadDashboardData(){
            var $base_url ='/admin/dashboard';
            var selected_type = $('#selected_type').val();
            var selected_fiscal_year  = $('#select_fiscal_year').val();

            $.ajax({
                url: $base_url + '/load-dashboard-table',
                type: "get",
                data: {
                    ministry_id:$('#ministry_id').val(),
                    selected_type:selected_type,
                    selected_fiscal_year:selected_fiscal_year,
                },
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    if (response) {
                        $("#dashboard_table").empty().append(response.view);
                        createChart(response.data.selected_type,response.data,'bar');
                        createChart('fiscalYearDecisionTypeChart',response.data,'bar');
                    }
                },
            });
        }

function generateArray(color, length) {
    return Array.from({ length }, () => color);
}

  
function createChart(element_id, res_data, type) 
{
    let data_new = '';
    let legend_display = false;
    let label_string = '';
    let scale_label=false;
    let axis_scale= false;
    let title;
    let labels=[];
    var ctx = document.getElementById(element_id).getContext('2d');

        if(element_id === 'yearWiseChart'){
            let customBackgroundColor1 = generateArray('brown', 12);
            let customBackgroundColor2 = generateArray('green', 12);
            let chart_data= [];
            //prepare data for chart
            let meeting_count =[];
            let decision_count =[];

            $.each(res_data.year_wise_data,function(key,stats){

                labels.push(key);
                meeting_count.push(stats.meeting_count)
                decision_count.push(stats.decision_count)
            });

            legend_display = true;
            axis_scale=true;
            scale_label=true;
            label_string = 'वर्ष';
            title='साल अनुसार बैठक / निर्णय संख्या'

            data_new= {
                labels: labels,
                datasets: [{    
                    label: 'बैठक संख्या',
                    data: meeting_count,
                    maxBarThickness: 15,
                    categoryPercentage: 0.035,
                    barPercentage:1,
                    backgroundColor: customBackgroundColor1,
                },
                {
                    label: 'निर्णय संख्या',
                    data: decision_count,
                    maxBarThickness: 15,
                    categoryPercentage: 0.035,
                    barPercentage:1,
                    backgroundColor: customBackgroundColor2,
                }]
            }
        } 
        if(element_id === 'fiscalYearWiseChart'){
            let customBackgroundColor1 = generateArray('brown', 12);
            let customBackgroundColor2 = generateArray('green', 12);
            let chart_data= [];
            //prepare data for chart
            let meeting_count =[];
            let decision_count =[];

            $.each(res_data.fiscal_year_wise_data,function(key,stats){

                labels.push(key);
                meeting_count.push(stats.meeting_count)
                decision_count.push(stats.decision_count)
            });

            legend_display = true;
            axis_scale=true;
            scale_label=true;
            label_string = 'आर्थिक वर्ष';
            title='आर्थिक वर्ष अनुसार बैठक / निर्णय संख्या'

            data_new= {
                labels: labels,
                datasets: [{    
                    label: 'बैठक संख्या',
                    data: meeting_count,
                    maxBarThickness: 15,
                    categoryPercentage: 0.035,
                    barPercentage:1,
                    backgroundColor: customBackgroundColor1,
                },
                {
                    label: 'निर्णय संख्या',
                    data: decision_count,
                    maxBarThickness: 15,
                    categoryPercentage: 0.035,
                    barPercentage:1,
                    backgroundColor: customBackgroundColor2,
                }]
            }
        }

        if(element_id === 'fiscalYearDecisionTypeChart'){
            let customBackgroundColor1 = ['brown', 'green', 'blue', 'violet', 'orange', 'purple', 'cyan', 'magenta', 'pink', 'teal', 'lime', 'darkgreen'];
            let chart_data= [];
            //prepare data for chart
            let count =[];


            $.each(res_data.decision_type_wise_data[res_data.fy_code],function(key,stats){
                labels.push(key);
                count.push(stats)
            });
            legend_display = true;
            axis_scale=true;
            scale_label=true;
            label_string = 'निर्णय प्रकार';
            title='आर्थिक बर्ष अनुसार विभिन्न प्रकारका निर्णय संख्या'

            data_new= {
                labels: labels,
                datasets: [{    
                    label: 'संख्या',
                    data: count,
                    maxBarThickness: 20,
                    categoryPercentage: 1,
                    barPercentage:2,
                    backgroundColor: customBackgroundColor1,
                }]
            }
        }

        var myChart = new Chart(ctx, {
            type: type,
            data:data_new,
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: title,
                    fontSize: 15,
                    fontColor:'black',
                    fontFamily:'Poppins'
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                tooltips: {
                    enabled :true,
                    mode: 'index',
                    displayColors:true,
                    titleFont:'Poppins',
                    titleFontSize:13,
                    bodyFontSize:12,
                    bodyFont:'Poppins',
                    callbacks: {
                        label: function(tooltipItem, data) {
                            if(data.type == 'pie' || data.type == 'doughnut'){
                                var label = data.labels[tooltipItem.index];
                                label += ' : ' + data.datasets[0].data[tooltipItem.index];
                            }else{
                                var label = data.datasets[tooltipItem.datasetIndex].label;
                                label += ' : '+data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                            }
                    
                            return label;
                        },
                    }
                },
                legend: {
                    display: legend_display,
                    position:'top',
                    labels: {
                        fontColor: 'black',
                        fontSize:12,
                        fontFamily:'Poppins',
                    }
                },
                scales: {
                    yAxes: [{
                        display:axis_scale,
                        ticks: {
                            beginAtZero: true,
                            fontColor:'black',
                            fontFamily:'Poppins'
                        },
                        scaleLabel: {
                            display: scale_label,
                            labelString: 'संख्या '
                        }
                    }],
                    xAxes: [{
                        display:axis_scale,
                        scaleLabel: {
                            display: scale_label,
                            labelString: label_string
                        }
                    }],
                }
            },
        });
}


        
    </script>
@endsection