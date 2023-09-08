@extends(backpack_view('blank'))
@push('after_styles')
<link rel="stylesheet" href="{{ asset('homepage/css/chart.min.css') }}" />
<style>
    .dashboard-card-body {
        color: black;
        padding: 10px 0px;
    }

    .hr-line1 {
        opacity: .20 !important;
        color: azure;
    }

    hr.hr-line1 {
        border: 1px solid azure;
        box-shadow: 2px 2px 2px black;
    }
    .dashboard-card-body select {
        color: black;
        border: 1px solid darkgray;
    }
    .btn-toggle{
        background-color: #2774e8;
        color:white;
    }
    .btn-toggle:hover{
        background-color: #0d47a1;
        color:white;
    }

    .printbtn {
        background: none;
        border: none;
    }
    .printbtn:focus {
        outline: none;
        /* or use border: none; if you want to remove the border */
    }
</style>
@endpush
@section('content')
<div class="card p-0">
    <div class="card-body dashboard-card-body">
        <div class="row">
            <div class="col-12 px-4">
                <div class="page-title-box">
                    <h5 class="page-title float-left"><img src="{{asset('/assets/dashboard-icon.png')}}" width="40px;"
                            height="40px;"> ड्यासबोर्ड</h5>
                    <div class="page-title-right" style="float: right;">
                        <div class="d-flex">
                            <table>
                                <tr>
                                    <td>
                                        <label for="section_type" class="font-weight-bold">विवरण प्रकार</label>
                                        <select class="form-control-sm" name="section_type" id="section_type" onchange="loadDashboardData()">
                                            <option value="milestone" selected>योजना क्रियाकलाप अनुसार प्रगति विवरण</option>
                                            <option value="progress">वित्तीय तथा भौतिक प्रगति विवरण</option>
                                            <option value="law">ऐन कानुन निर्माणको अवस्था</option>
                                            <option value="bidding">सार्वजनिक खरीद तथा ठेक्का व्यवस्थापन</option>
                                            <option value="office">कार्यालय व्यवस्थापन विवरण</option>
                                            <option value="darbandi">जनसक्ति दरबन्दी विवरण</option>
                                        </select>
                                    </td>
                                    
                                    @unless($ministry_dashboard)
                                    <td>
                                        <label for="ministry_id" class="font-weight-bold">मन्त्रालय</label>
                                        <select class="form-control-sm" name="ministry_id" id="ministry_id" onchange="loadDashboardData()">
                                            <option value="all" selected>सबै</option>
                                            @foreach ($ministries as $option)
                                            <option value="{{ $option->getKey() }}">{{ $option->name_lc }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    @endunless
                                    
                                    <td>
                                        <label for="fiscal_year_id" class="font-weight-bold">आ.व.</label>
                                        <select class="form-control-sm" name="fiscal_year_id" id="fiscal_year_id" onchange="loadDashboardData()">
                                            @foreach ($fiscal_years as $option)
                                            @if (intval($fiscal_year_id) === $option->getKey())
                                            <option value="{{ $fiscal_year_id }}" selected>{{ $option->code }}</option>
                                            @else
                                            <option value="{{ $option->getKey() }}">{{ $option->code }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <label for="month_id" class="font-weight-bold">महिना</label>
                                        <select class="form-control-sm" name="month_id" id="month_id" onchange="loadDashboardData()">
                                            <option value="">{{'-- महिना छान्नुहोस् --' }}</option>
                                            @foreach ($months as $option)
                                            <option value="{{ $option->getKey() }}">{{ $option->name_lc }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="hr-line1 mt-0">

        <section id="tabular_section" class="parallax-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div id="container_content"></div>
                    </div>
                </div>
            </div>
        </section>
@endsection


@section('after_scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    
    <script src="{{asset('homepage/js/chart.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/html2pdf.js"></script> --}}
    <script src="{{asset('js/plotchart.js')}}"></script>
    <script>
        $(document).ready(function() {

        //get ministry and fiscal year value
        let ministryId = $('#ministry_id').val();
        let fiscalYearId = $('#fiscal_year_id').val();

        loadDashboardData();
        
    });

        function printDiv(divId) {

            addPrintStyles();
            var printContents = document.getElementById(divId).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            
            document.body.innerHTML = originalContents;
            
        }

        function addPrintStyles() {
            var style = document.createElement('style');
            style.innerHTML = '@media print {\
                .printbtn {\
                    display: none !important; /* Hide the print button while printing */\
                }\
            }';

            document.head.appendChild(style);
        }

        function exportChart(elementId,filename) {
            // Get the canvas element
            var canvas = document.getElementById(elementId);

            // Create a new HTML2PDF instance
                var pdf = new html2pdf();
            // Add the canvas element to the PDF document
            var options = {
                filename: filename+'.pdf',
                image: { type: 'pdf' },
                html2canvas: { scale: 2 },
                jsPDF: { format: 'a3', orientation: 'landscape' }
            };

            // Add the canvas element to the PDF document
            pdf.set(options).from(canvas).save();

            }

        function exportCombinedCharts(elementIds, filename) {
            // Create a new HTML2PDF instance
            var pdf = new html2pdf();

            // Create a new canvas to combine the pie charts
            var combinedCanvas = document.createElement('canvas');
            var ctx = combinedCanvas.getContext('2d');

            // Define the dimensions for the combined canvas
            var width = 1600; // Adjust as needed
            var height = 400; // Adjust as needed
            combinedCanvas.width = width;
            combinedCanvas.height = height;

            // Calculate the width for each pie chart
            var chartWidth = width / elementIds.length;

            // Iterate through each canvas element
            elementIds.forEach(function(elementId, index) {
                // Get the canvas element
                var canvas = document.getElementById(elementId);

                // Draw each pie chart on the combined canvas
                ctx.drawImage(canvas, index * chartWidth, 0, chartWidth, height);
            });

            // Export the combined canvas as PDF
            var options = {
                filename: filename + '.pdf',
                image: { type: 'pdf' },
                html2canvas: { scale: 1 },
                jsPDF: { format: 'a3', orientation: 'landscape' }
            };

            // Add the combined canvas to the PDF document
            pdf.set(options).from(combinedCanvas).save();
            }



    </script>
 @endsection
