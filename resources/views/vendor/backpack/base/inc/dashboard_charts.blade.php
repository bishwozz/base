@php 
$name = 'name_'.$lang;

// if(isset($new_meetings)){
//     dd($new_meetings);
// }else{
//     dd('okok');
// }
@endphp
<div class="col-md-12 gis-map-container">

    <style>
        @font-face {
            font-family: "Kalimati";
            src: url("/fonts/Kalimati.ttf") format("truetype");
        }

    </style>
    <!-- leaflet -->
    <link rel="stylesheet" href="{{ asset('homepage/css/leaflet.css') }}" />
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('homepage/css/map.css') }}" />

    <!-- custom count css -->
    <link rel="stylesheet" href="{{ asset('homepage/css/markerCluster.css') }}" />
    <!-- chartjs -->
    <link rel="stylesheet" href="{{ asset('homepage/css/chart.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('homepage/css/custom.css') }}">

    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/fontawesome.min.css" rel="stylesheet"> --}}


    <script src="{{ asset('homepage/js/leaflet.js') }}"></script>
    <!-- PRE LOADER -->

    {{-- <div class="preloader">
        <div class="spinner">
            <span class="sk-inner-circle"></span>
        </div>
    </div>
    <div class="spinner-border" id="custom_spinner" role="status">
        <span class="sr-only">Loading...</span>
    </div> --}}
    {{-- {{dd($data)}} --}}
    <!-- Fed Area -->
    <div id="dashboardDataT1">

    </div>



    {{-- <div id="dashboardDataT2">
    <section id="fed_area" class="parallax-section">
        <div class="container bootstrap snippet" id="dashboardData">
                <h2>Titile2</h2>
            <div id="dashboard" class="form-row">
                 @foreach (isset($step_wise_datas)? $step_wise_datas:$new_agendas as $step_wise_data)
                        <div class="col-md-3">
                        <div class="box-{{ $step_wise_data->id  }}">
                            <div class="box-icon">
                                <span class="circle-tile-number" id="total_bill_count" >{{ $step_wise_data->count }}</span>
                            </div>
                            <div class="circle-tile-description" id="total_bill_label">
                                {{ $step_wise_data->$name }}
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </section>

    </div> --}}




    {{-- chart section --}}

    {{-- <section id="project_by_members" class="parallax-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-lg-6">
                    <div class="card text-center font-weight-bold mb-3"
                        style="border-top:5px solid red; border-bottom:5px solid lightgray; border-radius:20px">
                        <canvas id="bar_chart" height="300"
                            style="background-color:white; border-radius:20px;"></canvas>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6">
                    <div class="card text-center font-weight-bold mb-3"
                        style="border-top:5px solid green; border-bottom:5px solid lightgray; border-radius:20px">
                        <canvas id="pie_chart" height="300"
                            style="background-color:white; border-radius:20px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

</div>

@section('after_scripts')

@endsection
