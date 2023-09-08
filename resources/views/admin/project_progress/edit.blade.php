@extends(backpack_view('blank'))

@section('header')
    <div class="heading my-2 p-3">
        <h4>
            <a class="btn btn-primary btn-sm" href="{{ backpack_url('ministry-program-progress') }}" class="hidden-print back-btn"><i
                    class="fa fa-angle-double-left"></i> {{ trans('Back') }}</a>
        </h4>
    </div>
@endsection
@section('content')

<div class="card p-0">
    <div class="card-body dashboard-card-body">
        <div class="row">
            <div class="col-12 px-4">
                <div class="page-title-box">
                    <h2 class="page-title float-left"><i class="fa fa-arrow"></i> कार्यक्रम प्रगति विवरण</h2>
                    <div class="page-title-right" style="float: right;">
                    </div>
                </div>
            </div>
        </div>
        <hr class="hr-line1 mt-0">

        <section id="tabular_section" class="parallax-section">
            <div class="container">
                <form action="/admin/edit-progress-record" method="POST" id="add_progress_form">
                    {!! csrf_field() !!}
                    <input type="hidden" name="program_progess_id" value="{{$progress->id}}">

                    <div class="form-row">
                        @if(!backpack_user()->ministry_id)
                        <div class="form-group col-md-4">
                            <label class="label required" for="ministry_id">मंत्रालय</label>
                            <input class="form-control" type="text" value="{{$progress->ministry->name_lc}}" readonly>
                        </div>
                        @endif
                        <div class="form-group col-md-4">
                            <label class="label required" for="fiscal_year_id">कार्यक्रम आर्थिक वर्ष</label>
                            <input class="form-control" type="text" value="{{$progress->fiscalYear->code}}" readonly>
                        </div>
                        
                        <div class="form-group col-md-4">
                            <label class="label required" for="project_id">कार्यक्रम</label>
                            <div class="form-group check_validation_d">
                                <input class="form-control" type="text" value="{{$progress->project->project_name}}" readonly>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="label required" for="reporting_fiscal_year_id">प्रगति आर्थिक वर्ष</label>
                            <input class="form-control" type="text" value="{{isset($progress->reportingFiscalYear)?$progress->reportingFiscalYear->code:''}}" readonly>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="label required" for="month_id">प्रगति महिना</label>
                            <div class="form-group check_validation_d">
                                <input class="form-control" type="text" value="{{$progress->month->name_lc}}" readonly>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card h-100">
                                <div id="milstone_table_body">
                                    <div class="row mt-2">
                                        <div class="col">
                                            <center>
                                                <h5 class="font-weight-bold">कार्यक्रम माइलस्टोन प्रगति</h5>
                                            </center>
                                        </div>
                                    </div>
                                    <div class="row">
                                    </div>
                                    <div class="row mt-0">
                                        <div class="col-md-12">
                                            <div class="col-md-12 p-2" style="overflow-x:auto;">
                                                <table id="milestone_data_table" class="styled-table com-md-12">
                                                    <thead>
                                                        <tr>
                                                            <td rowspan="2">क्र.स.</td>
                                                            <td rowspan="2">माइलस्टोन</td>
                                                            <td rowspan="2">पछिल्लो अवस्था</td>
                                                            <td rowspan="2">हाल को अवस्था</td>
                                                            <td rowspan="2" class="col-md-3">माइलस्टोन प्रगति</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($milestone_progress as $progress)
                                                        {{-- {{dd($progress['milestone']->milestone_status_id)}} --}}
                                                            @if($progress['milestone'])
                                                            <tr>
                                                                <td class="report-data">{{$loop->index+1}}</td>
                                                                <td class="report-data">{{ $progress['milestone']->milestone->name }}</td>
                                                                @if($progress['is_earlier_completed'])
                                                                <td class="report-data"><span class="bg-success px-3 py-1 rounded">Completed</span></td>
                                                                
                                                                @else
                                                                <td class="report-data">{{ $progress['earlierStatusName'] }}</td>
                                                                @endif
                                                                <input type="hidden" name="milestone_id[]" value="{{$progress['milestone']->id}}">
                                                                
                                                                <td class="report-data">
                                                                    @if($progress['is_completed'])
                                                                    <input type="hidden" name="milestone_status[]" value="{{$progress['is_completed_status_id']}}">
                                                                    <span class="bg-success px-3 py-1 rounded">Completed</span>
                                                                    @else
                                                                    <select class="form-control" name="milestone_status[]"  onchange="setMilestoneProgress(this)" required>
                                                                        <option value="">स्थिति चयन गर्नुहोस्</option>
                                                                        @foreach ($progress['status'] as $status)
                                                                            <option value="{{ $status->id }}" data-score="{{ $status->progress_percent }}" <?php echo $progress['milestone']->milestone_status_id==$status->id?'selected':'';?>>{{ $status->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @endif
                                                                </td>
                                                                
                                                                <td class="report-data col-md-3">
                                                                    <div class="input-group">
                                                                        @if($progress['is_completed'])
                                                                        <input class="form-control milestone-progress" type="number" name="milestone_progress[]" value="100" max="100" step="any"  readonly="">
                                                                        @else
                                                                        <input class="form-control milestone-progress" type="number" name="milestone_progress[]" max="100" step="any" value="{{$progress['milestone']->milestone_percent}}" required>
                                                                        @endif
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text" id="percentage-addon">%</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">रद्द गर्नुहोस</button>
                        <button type="button" id="add_progress_data" class="btn btn-primary">प्रगति प्रविस्ट गर्नुहोस</button>
                      </div>
                </form>
                
            </div>
        </section>
    </div>
</div>
@endsection
@section('after_scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // getMilestoneData()

            $('#ministry_id').change(function(){
                getProjectData();
            });
            $('#fiscal_year_id').change(function(){
                getProjectData();
            });
            $('#project_id').change(function(){
                getMilestoneData();
            });

        });

        function getMilestoneData(){
            let data = {
                is_print : false,
                project_id : $('#project_id').val(),
            }
            if(data.project_id){
                $('#milstone_table_body').html('<div class="text-center"><img src="/css/images/loading.gif"/></div>');
                $.ajax({
                    type: "POST",
                    url: "/admin/getMilestoneData",
                    data: data,
                    success: function(response){
                        $('#milstone_table_body').html(response);
                    }
                });
            } else {
                $('#milstone_table_body').empty()
            }
            
        }



        function getProjectData(ministry, fiscal_year){
            $('#milstone_table_body').empty()
            let data = {
                is_print : false,
                ministry_id : $('#ministry_id').val(),
                fiscal_year_id : $('#fiscal_year_id').val()
            }

            ministry = data.ministry_id;
            fiscal_year = data.fiscal_year_id;

            



            

            if (ministry && fiscal_year) {
                $('#project_id').append('<option value="">-- Loading...  --</option>');
                $.ajax({
                    url: '/admin/getproject/' + ministry + '/' + fiscal_year,
                    type: "GET",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function (data) {

                        if (data) {
                            $('#project_id').empty();
                            $('#project_id').focus;
                            $('#project_id').append('<option value="">-- कार्यक्रम छान्नुहोस्  --</option>');
                            var selected_id = project_id;
                            $.each(data, function (key, value) {
                                var selected = "";
                                if (selected_id == value.id) {
                                    // console.log('ok');
                                    selected = "SELECTED";
                                }


                                $('select[name="project_id"]').append('<option class="form-control" value="' + value.id + '" ' + selected + '>' + value.project_name + '</option>');
                                if (selected == "") {
                                    $("#project_id").trigger("change");
                                }
                            });
                            $('.searchselect_1').select2();
                        } else {
                            $('#project_id').empty();
                        }
                    }
                });
            } else {
                $('#project_id').empty();
            }
        }


        $(document).ready(function() {

            $('#add_progress_data').click(function (e) {
            
                e.preventDefault();
                inputsValid = validateInputs(this);
                if(inputsValid){
                    $.LoadingOverlay('show', { text: "Saving ..." });
                    $.ajax({
                        url: $('#add_progress_form').attr('action'),
                        type: 'POST',
                        data : $('#add_progress_form').serialize(),
                        success: function(response){
                        $.LoadingOverlay('hide', { text: "Loading ..." });
                            Swal.fire({
                                icon: response.status,
                                title: response.title,
                                text: response.message,
                            });

                            if(response.status=='success'){
                                setTimeout(window.location.href = response.url, 3000);
                            }

                        }
                    });
                }
                
            
                return false;
            });

            function validateInputs(ths) {
                let inputsValid = true;

                const inputs =
                    ths.parentElement.parentElement.querySelectorAll("input");
                
                for (let i = 0; i < inputs.length; i++) {
                    const valid = inputs[i].checkValidity();
                    
                    if (!valid) {
                        inputsValid = false;
                        inputs[i].classList.add("invalid-input");
                    } else {
                        inputs[i].classList.remove("invalid-input");
                    }
                }


                const selects =
                    ths.parentElement.parentElement.querySelectorAll("select");
                
                for (let i = 0; i < selects.length; i++) {
                    const valid = selects[i].checkValidity();
                    
                    if (!valid) {
                        inputsValid = false;
                        selects[i].classList.add("invalid-input");
                    } else {
                        selects[i].classList.remove("invalid-input");
                    }
                }

                return inputsValid;
            }
        });


        

    </script>
    <script>
                                    
        function setMilestoneProgress(selectElement) {
            var score = $(selectElement).find('option:selected').data('score');
        
            $(selectElement).closest('tr').find('.milestone-progress').val(score);
        }
        
        </script>
 @endsection
