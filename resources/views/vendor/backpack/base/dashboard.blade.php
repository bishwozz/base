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
                                    
                                    {{-- @unless($ministry_dashboard)
                                    <td>
                                        <label for="ministry_id" class="font-weight-bold">मन्त्रालय</label>
                                        <select class="form-control-sm" name="ministry_id" id="ministry_id" onchange="loadDashboardData()">
                                            <option value="all" selected>सबै</option>
                                            @foreach ($ministries as $option)
                                            <option value="{{ $option->getKey() }}">{{ $option->name_lc }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    @endunless --}}
                                    
                           
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
   
@endsection
