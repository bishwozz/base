@php 
$name = 'name_'.$lang;

@endphp

<section class="parallax-section">
    <div class="card bootstrap snippet" id="dashboardData">
        <div class="card-header p-2" style="background: #32579F; color:white;">
                <h5 style="padding-left: .3em;">{{ ($lang == 'lc')?'प्रस्तावको प्रकार':'Agenda Type' }}</h5>
        </div>
        <div id="dashboard" class="card-body form-row px-5">
            @foreach ($new_agendas as $agenda)
                <div class="col-lg-2-4 col-md-3 col-md-4 col-12 p-2 px-3">
                    <div class="box-{{ isset($agenda->id)?$agenda->id:$i  }}">
                        <div class="circle-tile-description" id="total_bill_label">
                            {{ $agenda->$name }}
                        </div>
                        <div class="box-icon">
                            <span class="circle-tile-number" id="total_bill_count" >{{ $agenda->count }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


<div id="dashboardDataT2">
    <section  class="parallax-section">
        <div class="card bootstrap snippet" id="dashboardData">
            <div class="card-header p-2" style="background: #32579F; color:white;">
                <h5 style="padding-left: .3em;">{{ ($lang == 'lc')?'चरण':'Step' }}</h5>
            </div>
            <div id="dashboard" class="card-body form-row px-5">
                @foreach ($step_wise_datas as $step_wise_data)
                    <div class="col-md-3 col-12 p-2 px-3">
                        <div class="box-{{ $step_wise_data->id  }}">
                            <div class="circle-tile-description" id="total_bill_label">
                                {{ $step_wise_data->$name }}
                            </div>
                            <div class="box-icon">
                                <span class="circle-tile-number" id="total_bill_count" >{{ $step_wise_data->count }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    </div> 


