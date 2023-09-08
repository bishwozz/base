@extends(backpack_view('blank'))

@section('header')
  <div class="container-fluid">
    <h2>
      <span class="text-capitalize">रिपोर्ट</span>
    </h2>
  </div>
@endsection

@section('content')
    <style>
            /* Demo Code: */
        body {
        font-family: 'Open Sans', arial, sans-serif;
        color: #333;
        font-size: 14px;
        }
        .projcard-container {
            margin: 20px 0;
        }

        /* Actual Code: */
        .projcard-container,
        .projcard-container * {
        box-sizing: border-box;
        }
        .projcard-container {
        margin-left: auto;
        margin-right: auto;
        width: 100%;
        }
        .projcard {
        position: relative;
        margin-bottom: 10px;
        border-radius: 10px;
        background-color: #fff;
        border: 2px solid #ddd;
        font-size: 18px;
        overflow: hidden;
        cursor: pointer;
        box-shadow: 0 4px 21px -12px rgba(0, 0, 0, .66);
        }

        .projcard-textbox {
            padding: 1em;
            font-size: 17px;
        }

        .projcard-title {
            font-family: 'Voces', 'Open Sans', arial, sans-serif;
            font-size: 18px;
            font-weight: 700;
        }
        .projcard-subtitle {
            font-family: 'Voces', 'Open Sans', arial, sans-serif;
            color: #888;
        }

        .projcard-blue .projcard-bar { background-color: #0088FF; }
        .projcard-blue::before { background-image: linear-gradient(-70deg, #0088FF, transparent 50%); }
        .projcard-blue:nth-child(2n)::before { background-image: linear-gradient(-250deg, #0088FF, transparent 50%); }
        .projcard-red .projcard-bar { background-color: #D62F1F; }
        .projcard-red::before { background-image: linear-gradient(-70deg, #D62F1F, transparent 50%); }
        .projcard-red:nth-child(2n)::before { background-image: linear-gradient(-250deg, #D62F1F, transparent 50%); }
        .projcard-green .projcard-bar { background-color: #40BD00; }
        .projcard-green::before { background-image: linear-gradient(-70deg, #40BD00, transparent 50%); }
        .projcard-green:nth-child(2n)::before { background-image: linear-gradient(-250deg, #40BD00, transparent 50%); }
        .projcard-yellow .projcard-bar { background-color: #F5AF41; }
        .projcard-yellow::before { background-image: linear-gradient(-70deg, #F5AF41, transparent 50%); }
        .projcard-yellow:nth-child(2n)::before { background-image: linear-gradient(-250deg, #F5AF41, transparent 50%); }
        .projcard-orange .projcard-bar { background-color: #FF5722; }
        .projcard-orange::before { background-image: linear-gradient(-70deg, #FF5722, transparent 50%); }
        .projcard-orange:nth-child(2n)::before { background-image: linear-gradient(-250deg, #FF5722, transparent 50%); }
        .projcard-brown .projcard-bar { background-color: #C49863; }
        .projcard-brown::before { background-image: linear-gradient(-70deg, #C49863, transparent 50%); }
        .projcard-brown:nth-child(2n)::before { background-image: linear-gradient(-250deg, #C49863, transparent 50%); }
        .projcard-grey .projcard-bar { background-color: #424242; }
        .projcard-grey::before { background-image: linear-gradient(-70deg, #424242, transparent 50%); }
        .projcard-grey:nth-child(2n)::before { background-image: linear-gradient(-250deg, #424242, transparent 50%); }
        .projcard-customcolor .projcard-bar { background-color: var(--projcard-color); }
        .projcard-customcolor::before { background-image: linear-gradient(-70deg, var(--projcard-color), transparent 50%); }
        .projcard-customcolor:nth-child(2n)::before { background-image: linear-gradient(-250deg, var(--projcard-color), transparent 50%); }
        .projcard-description {
            z-index: 10;
            font-size: 15px;
            color: #424242;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: .5em;
        }
        .projcard-tagbox {
        position: absolute;
        bottom: 3%;
        font-size: 14px;
        cursor: default;
        user-select: none;
        pointer-events: none;
        }

    </style>
    <div class="projcard-container">
        <div class="projcard projcard-blue">
            <div class="projcard-textbox">
              <div class="projcard-title">Report Filter</div>
              <a onclick="clearData()" href="#" class="btn btn-danger float-right" style="margin-top:-3em;"><i class="la la-times"></i> Clear </a>
              <hr>
                <div class="projcard-description">
                    <form class="form" action="{{ route('excel_export') }}" method="POST" id="filter-form">
                        @csrf
                        <div class="form-group">
                            <div class="row">

                                <div class="col-3">
                                    <label for="fiscal_year_id"> आर्थिक वर्ष </label>
                                    <select class="form-control" id="fiscal_year_id" name="fiscal_year_id">
                                        <option value="" selected>आर्थिक वर्ष छानुहोस</option>
                                        @foreach ($fiscal_years as $fy)
                                            <option value="{{ $fy->id }}">{{ $fy->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="month"> महिना </label>
                                    <select class="form-control" id="month" name="month">
                                        <option value="" selected>महिना छानुहोस</option>
                                        @foreach ($months as $month)
                                            <option value="{{ $month->id }}">{{ $month->name_lc }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-3">
                                    <label for="ministry_id"> प्रस्ताव दर्ता गर्ने मन्त्रालय </label>
                                    <select class="form-control" id="ministry_id" name="ministry_id">
                                        <option value="" selected> प्रस्ताव दर्ता गर्ने मन्त्रालय  छानुहोस</option>
                                        @foreach ($ministries as $ministry)
                                            <option value="{{ $ministry->id }}">{{ $ministry->name_lc }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-3">
                                    <label for="agenda_type_id"> प्रस्तावको प्रकार </label>
                                    <select class="form-control" id="agenda_type_id" name="agenda_type_id">
                                        <option value="" selected>प्रस्तावको प्रकार  छानुहोस</option>
                                        @foreach ($agenda_types as $agenda_type)
                                            <option value="{{ $agenda_type->id }}">{{ $agenda_type->name_lc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary"><i class="la la-file-excel"></i> Export to Excel</button>
                        </form>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card h-100">
                    <div id="cabinet_report_data"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after_scripts')
    <script>
        function clearData(){
            $('#filter-form')[0].reset();
            getReportData();
        }

        $(document).ready(function () {

            // FISCAL YEAR
                $('#fiscal_year_id').change(function(){
                    getReportData();
                });

            // MONTH
                $('#month').change(function(){
                    getReportData();
                });

            // MINISTRY
                $('#ministry_id').change(function(){
                    getReportData();
                });

            // AGENDA TYPE
                $('#agenda_type_id').change(function(){
                    getReportData();
                });

            getReportData();

        });

        function getReportData(){
            let data = {
                fiscal_year_id : $('#fiscal_year_id').val(),
                month : $('#month').val(),
                ministry_id : $('#ministry_id').val(),
                agenda_type_id : $('#agenda_type_id').val(),
                is_report_data : true,
            }
            // debugger;
            $('#cabinet_report_data').html('<div class="text-center"><img src="/css/images/loading.gif"/></div>');
            $.ajax({
                type: "POST",
                url: "/admin/getreportdata",
                data: data,
                success: function(response){
                    $('#cabinet_report_data').html(response);
                }
            });
        }

    </script>
@endpush
