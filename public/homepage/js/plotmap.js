var base_url = window.location.origin + "/admin";
// changeFiscalYear();

//load all fiscal_year form wppms_tmpp webapp

// function changeFiscalYear() {
//     let fiscal_year_id = $('#fiscal_year_id').val();
//     var url;
//     // if(fiscal_year_id != null){
//     //     url = base_url+'/set-fiscal-year?fiscal_year_id='+fiscal_year_id;
//     // }else{
//     //     url = base_url+'/set-fiscal-year';
//     // }

//     $.get(url, function (data) {
//         $('#fiscal_year_id').empty();
//         let parsed_data = JSON.parse(data);
//         let fiscal_year = parsed_data.fiscal_year;
//         selected_fiscal_year_id = parsed_data.selected_fiscal_year_id;

//         $.each(fiscal_year, function (key, value) {
//             var selected = "";
//             if (selected_fiscal_year_id == value) {
//                 selected = "SELECTED";
//             }
//             $('select[name="fiscal_year_id"]').append('<option class="font-weight-bold small" value="' + value + '" ' + selected + '>' + key + '</option>');
//         });
//         resetToCountry();
//     });
// }

//reset  fiscal year

// function resetFiscalYear() {
//     $('#fiscal_year_id').empty();
//     changeFiscalYear();
// }
function resetFilter() {
    window.location.href = "/admin/dashboard";
}
function getChartData(fiscal_year_id = "all") {
    // debugger;
    $.ajax({
        url: base_url + "/get-chart-data",
        type: "post",
        data: {
            fiscal_year_id: fiscal_year_id,
        },
        success: function (response) {
            // $("#total_in_progress_label").text(
            //     response.lang == 'lc'?
            //     response.steps[0].name_lc: response.steps[0].name_en
            // );
            $("#total_bill_count").text(
                response.step_wise_data.chart.data[0] > 0
                    ? response.step_wise_data.chart.data[0]
                    : 0
            );

            // $("#total_under_construction_label").text(
            //     response.lang == 'lc'?
            //     response.steps[1].name_lc: response.steps[1].name_en
            // );
            $("#total_yen_count").text(
                response.step_wise_data.chart.data[1] > 0
                    ? response.step_wise_data.chart.data[1]
                    : 0
            );
            // $("#total_finalized_label").text(
            //     response.lang == 'lc'?
            //     response.steps[2].name_lc: response.steps[2].name_en
            // );
            $("#total_directory_count").text(
                response.step_wise_data.chart.data[2] > 0
                    ? response.step_wise_data.chart.data[2]
                    : 0
            );
            // $("#total_rejected_label").text(
            //     response.lang == 'lc'?
            //     response.steps[3].name_lc: response.steps[3].name_en
            // );

            $("#total_procedure_count").text(
                response.step_wise_data.chart.data[3] > 0
                    ? response.step_wise_data.chart.data[3]
                    : 0
            );
            $("#total_policy_count").text(
                response.step_wise_data.chart.data[4] > 0
                    ? response.step_wise_data.chart.data[4]
                    : 0
            );
            $("#total_formation_order_count").text(
                response.step_wise_data.chart.data[5] > 0
                    ? response.step_wise_data.chart.data[5]
                    : 0
            );
            $("#total_appointment_count").text(
                response.step_wise_data.chart.data[6] > 0
                    ? response.step_wise_data.chart.data[6]
                    : 0
            );
            $("#total_sifharis_count").text(
                response.step_wise_data.chart.data[7] > 0
                    ? response.step_wise_data.chart.data[7]
                    : 0
            );
            $("#total_policie_program_count").text(
                response.step_wise_data.chart.data[8] > 0
                    ? response.step_wise_data.chart.data[8]
                    : 0
            );
             $("#total_budget_count").text(
                 response.step_wise_data.chart.data[9] > 0
                     ? response.step_wise_data.chart.data[9]
                     : 0
             );
            ;



            // $("#total_rejected_count").text(
            //     response.step_wise_data.chart.data[3] > 0
            //         ? response.step_wise_data.chart.data[3]
            //         : 0
            // );
            // $("#total_agenda_count").text(response.total_agenda_count);

            chart_title_1 =
                response.lang == "lc"
                    ? "चरण अनुसार बार चार्ट"
                    : "Step Wise Bar Chart";
            createChart(
                "bar_chart",
                chart_title_1,
                response.step_wise_data.chart,
                "bar",
                response.lang
            );
            chart_title_2 =
                response.lang == "lc"
                    ? "चरण अनुसार पाई चार्ट"
                    : "Step Wise Pie Chart";
            createChart(
                "pie_chart",
                chart_title_2,
                response.step_wise_data.chart,
                "pie",
                response.lang
            );
        },
    });
}

// element id , title of chart , data, type
function createChart(element_id, title, data, type,lang) {
    var parent_div = $("#" + element_id).parent();
    // debugger;
    if (parent_div.length == 0) {
        return;
    }
    $("#" + element_id).remove();
    parent_div.append('<canvas id="' + element_id + '" height="300"></canvas>');
    var ctx = document.getElementById(element_id);
    var customBackgroundColor;
    var tooltip_label1 = lang == 'lc'? "* बैठक संख्या : ":' Meeting count ';
    // var tooltip_label2 = '* कुल लागत : ';

    if (element_id === "bar_chart") {
        customBackgroundColor = [
            "brown",
            "green",
            "orange",
            "gray",
            "purple",
            "blue",
            "skyblue",
        ];
    }
    if (element_id === "pie_chart") {
        customBackgroundColor = [
            "yellow",
            "red",
            "orange",
            "black",
            "purple",
            "blue",
            "skyblue",
        ];
    }
    var myChart = new Chart(ctx, {
        type: type,
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: title,
                    data: data.data,
                    maxBarThickness: 25,
                    backgroundColor: customBackgroundColor,
                },
            ],
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: title,
                fontSize: 18,
                fontColor: "black",
                fontFamily: lang == 'lc' ? "Kalimati":'',
            },
            animation: {
                animateScale: true,
                animateRotate: true,
            },
            tooltips: {
                enabled: true,
                mode: "single",
                displayColors: false,
                titleFontSize: 14,
                titleFontFamily: lang == 'lc' ? "Kalimati":'',
                bodyFontSize: 13,
                bodyFontFamily: lang == 'lc' ? "Kalimati":'',
                callbacks: {
                    label: function (tooltipItem, data) {
                        var label1 = tooltip_label1;
                        var number_count = OSREC.CurrencyFormatter.format(
                            data.datasets[0].data[tooltipItem.index],
                            { currency: "INR", pattern: ",##,##,##,###" }
                        );

                        label1 += number_count;
                        return label1;
                    },
                },
            },
            legend: {
                display: false,
                position: "bottom",
                labels: {
                    fontColor: "black",
                    fontFamily: lang == 'lc' ? "Kalimati":'',
                    fontSize: 15,
                },
            },
            scales: {
                yAxes: [
                    {
                        display: true,
                        ticks: {
                            beginAtZero: true,
                            fontFamily: lang == 'lc' ? "Kalimati":'',
                            fontColor: "black",
                        },
                    },
                ],
            },
        },
    });
}

// show loading spinner
$(document)
    .ajaxStart(function () {
        $("#custom_spinner").show();
    })
    .ajaxStop(function () {
        $("#custom_spinner").hide();
    });
