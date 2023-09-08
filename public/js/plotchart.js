var base_url = window.location.origin + "/admin";
function resetFilter() {
    window.location.href = "/admin/dashboard";
}
function loadDashboardData() {
        loadingScreen(true);
        //get ministry and fiscal year value
        let section_type = $('#section_type').val();
        let ministryId = $('#ministry_id').val();
        let fiscalYearId = $('#fiscal_year_id').val();
        let monthId = $('#month_id').val();


        if(ministryId && ministryId != 'all'){
            selectedMinistryId = ministryId;
        }else{
          selectedMinistryId = $('#selected_ministry_id').val();
       }

       if(typeof(selectedMinistryId) == 'undefined'){
        selectedMinistryId=1;
       }


    $.ajax({
        url: base_url + "/get-dashboard-data",
        type: "post",
        data: {
            section_type: section_type,
            ministry_id: ministryId,
            fiscal_year_id: fiscalYearId,
            selectedMinistryId: selectedMinistryId,
            monthId: monthId,
        },
        success: function (response) {
            loadingScreen(false);
            $('#container_content').html(response.final_view)

            let section = response.section_type
            let result = response.final_result

            if(section == 'progress')
            {
                createChart('progressChart',result,'bar');
            }
            if(section == 'law')
            {
                createChart('new_lawChart',result,'pie');
                createChart('ammendment_lawChart',result,'pie');
            }
            if(section == 'office')
            {
                createChart('officeChart',result,'bar');
            }
            if(section == 'bidding')
            {
                createChart('biddingChart',result,'bar');
            }
            if(section == 'milestone')
            {
                createChart('milestoneChart',result,'doughnut');
            }
        },
    });
}

// chart data section
// generate array
function generateArray(color, length) {
    return Array.from({ length }, () => color);
  }
// element id , title of chart , data, type
function createChart(element_id, res_data, type) 
{
    let data_new = '';
    let legend_display = false;
    let label_string = '';
    let scale_label=false;
    let axis_scale= false;
    let title
    var ctx = document.getElementById(element_id).getContext('2d');

    if(element_id === 'milestoneChart'){
        customBackgroundColor = ['red','darkgreen','purple','orange','orange','brown','real','lightgreen','skyblue'];
        let chart_data= [];
        //load data only for selected ministry
        let selected_ministry_id = $('#selected_ministry_id').val();
        let selected_data = res_data[selected_ministry_id];
        if(selected_data){
            title = selected_data.ministry_name ;

            $.each(selected_data.data,function(key,stats){
                chart_data.push(stats)
            })

            legend_display=true;
            data_new= {
                labels: ['कार्य सुरु नभएको','काम सम्पन्न भएको','काम भइरहेको'],
                type: 'doughnut',
                datasets: [{    
                    label: 'संख्या',
                    data: chart_data,
                    maxBarThickness: 20,
                    backgroundColor: customBackgroundColor,
                }],
            }
        }else{
            title='विवरण उपलब्ध छैन !!'
        }    
    }

    if(element_id === 'progressChart'){
        let customBackgroundColor1 = generateArray('brown', 12);
        let customBackgroundColor2 = generateArray('green', 12);
        let customBackgroundColor3 = generateArray('blue', 12);

        legend_display = true;
        axis_scale=true;
        scale_label=true;
        label_string = 'महिना';
        var labels=[];
        var financial_total=[];
        var physical_total=[];

        //load data only for selected ministry
        let selected_ministry_id = $('#selected_ministry_id').val();
        let selected_data = res_data[selected_ministry_id];

        if(selected_data){
            title = selected_data.ministry_name ;

        $.each(selected_data.data,function(key,stats){
            labels.push(stats.month);
            financial_total.push(stats.total_progress_financial)
            physical_total.push(stats.total_progress_physical)
        })
            data_new= {
                labels: labels,
                datasets: [{    
                    label: 'वित्तीय प्रगति (%)',
                    data: financial_total,
                    maxBarThickness: 10,
                    categoryPercentage: 0.4,
                    barPercentage:1,
                    backgroundColor: customBackgroundColor1,
                },
                {
                    label: 'भौतिक प्रगति (%)',
                    data: physical_total,
                    maxBarThickness: 10,
                    categoryPercentage: 0.4,
                    barPercentage:1,
                    backgroundColor: customBackgroundColor2,
                },
            ]
            }
        }
        else{
            title='विवरण उपलब्ध छैन !!'
        }
    }
    if(element_id === 'officeChart'){
        customBackgroundColor = ['darkgreen','darkgreen','brown','brown'];
        legend_display = false;
        axis_scale=true;
        scale_label=true;
        label_string = 'सवारी साधन प्रकार';
        var labels=[];

        //load data only for selected ministry
        let selected_ministry_id = $('#selected_ministry_id').val();
        let selected_data = res_data[selected_ministry_id];

        if(selected_data){
            title = selected_data.ministry_name ;
            data_new= {
                labels: ['चालु दुई पांग्रे 🚲','चालु चार पांग्रे 🚍','अपुग दुई पांग्रे 🚲','अपुग चार पांग्रे 🚍'],
                datasets: [{    
                    label: labels,
                    data: [selected_data.current_two_wheeler,selected_data.current_four_wheeler,selected_data.required_two_wheeler,selected_data.required_four_wheeler],
                    maxBarThickness: 20,
                    categoryPercentage: 1,
                    barPercentage:1,
                    backgroundColor: customBackgroundColor,
                },
            ]
            }
        }
        else{
            title='विवरण उपलब्ध छैन !!'
        }
    }
    if(element_id === 'biddingChart'){
        customBackgroundColor = ['green','red','blue','purple','orange','brown','real','lightgreen','skyblue'];
        legend_display = false;
        axis_scale=true;
        scale_label=true;
        label_string = 'ठेक्का संख्या';
        var labels=[];

        //load data only for selected ministry
        let selected_ministry_id = $('#selected_ministry_id').val();
        let selected_data = res_data[selected_ministry_id];

        if(selected_data){
            title = selected_data.ministry_name ;
            data_new= {
                labels: ['विद्युतीय ठेक्का प्रणाली आव्हान ठेक्का संख्या','कुल संचलानमा रहेको ठेक्का संख्या','अनुगमन तथा निरिक्षण सम्पन्न भएको जम्मा संख्या'],
                datasets: [{    
                    label: 'संख्या',
                    data: [selected_data.online_procurement_contract,selected_data.total_operating_contract,selected_data.inspection_count],
                    maxBarThickness: 20,
                    categoryPercentage: 1,
                    barPercentage:1,
                    backgroundColor: customBackgroundColor,
                },
            ]
            }
        }
        else{
            title='विवरण उपलब्ध छैन !!'
        }
    }

    if(element_id === 'new_lawChart'){
        customBackgroundColor = ['green','red','blue','purple','orange','brown','real','lightgreen','skyblue'];
        let chart_data= [];
        //load data only for selected ministry
        let selected_ministry_id = $('#selected_ministry_id').val();
        let selected_data = res_data[selected_ministry_id];

        if(selected_data){
            title = 'नयाँ' ;

            $.each(selected_data.type.new,function(key,stats){
                chart_data.push(stats)
            })

            legend_display=true;
            data_new= {
                labels: ['बनेका ऐन/कानुन','निर्माणाधीन ऐन/कानुन','बनाउनुपर्ने ऐन/कानुन'],
                type: 'pie',
                datasets: [{    
                    label: 'संख्या',
                    data: chart_data,
                    maxBarThickness: 20,
                    backgroundColor: customBackgroundColor,
                }],
            }
        }else{
            title='विवरण उपलब्ध छैन !!'
        }    
    }
    if(element_id === 'ammendment_lawChart'){
        customBackgroundColor = ['green','red','blue','purple','orange','brown','real','lightgreen','skyblue'];
        let chart_data= [];
        //load data only for selected ministry
        let selected_ministry_id = $('#selected_ministry_id').val();
        let selected_data = res_data[selected_ministry_id];

        if(selected_data){
            title = 'संसोधन' ;

            $.each(selected_data.type.ammendment,function(key,stats){
                chart_data.push(stats)
            })

            legend_display=true;
            data_new= {
                labels: ['संसोधन भएका ऐन/कानुन','संसोधन प्रक्रियमा भएका ऐन/कानुन','संसोधन गर्नुपर्ने ऐन/कानुन'],
                type: 'pie',
                datasets: [{    
                    label: 'संख्या',
                    data: chart_data,
                    maxBarThickness: 20,
                    backgroundColor: customBackgroundColor,
                }],
            }
        }else{
            title='विवरण उपलब्ध छैन !!'
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
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
            tooltips: {
                enabled :true,
                mode: 'single',
                displayColors:true,
                titleFontSize:13,
                bodyFontSize:12,
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
                    fontSize:12
                }
            },
            scales: {
                yAxes: [{
                    display:axis_scale,
                    ticks: {
                        beginAtZero: true,
                        fontColor:'black'
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


function loadingScreen(bool){
    let status = bool === true ? 'show' : 'hide';
    $.LoadingOverlay(status, { text: "Loading ..." });
}


// show loading spinner
$(document)
    .ajaxStart(function () {
        $("#custom_spinner").show();
    })
    .ajaxStop(function () {
        $("#custom_spinner").hide();
    });
