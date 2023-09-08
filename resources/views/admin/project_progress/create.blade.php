@extends(backpack_view('blank'))

@section('header')
    <div class="heading ml-4">
            <a class="btn btn-primary btn-sm" href="{{ backpack_url('ministry-program-progress') }}" class="hidden-print back-btn"><i
                    class="fa fa-angle-double-left"></i> {{ trans('Back') }}</a>
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
                <form action="/admin/add-progress-record" method="POST" id="add_progress_form">
                    {!! csrf_field() !!}
                    <div class="form-row">
                        @if(backpack_user()->ministry_id)
                        <input type="hidden" name="ministry_id"  id="ministry_id" value="{{$ministries->id}}">
                        @else
                        <div class="form-group col-md-4">
                            <label class="label required" for="ministry_id">मंत्रालय</label>
                            <select class="form-control" name="ministry_id" id="ministry_id" required>
                                @foreach ($ministries as $option)
                                    <option value="{{ $option->getKey() }}">{{ $option->name_lc }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
                        <div class="form-group col-md-4">
                            <label class="label required" for="fiscal_year_id">कार्यक्रम आर्थिक वर्ष</label>
                            <select name="fiscal_year_id" class="form-control" id="fiscal_year_id">
                                <option value="">-</option>
                                @foreach ($fiscal_years as $fiscal_year)
                                    <option value="{{ $fiscal_year->id }}" <?php  echo $default_fiscal_year?$default_fiscal_year==$fiscal_year->id? 'selected':'':'' ?>>{{ $fiscal_year->code }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group col-md-4">
                            <label class="label required" for="project_id">कार्यक्रम</label>
                            <div class="form-group check_validation_d">
                                <select  class="form-control" name="project_id" id="project_id" required>
                                    <option selected value="">कृपया मन्त्रालय र आर्थिक वर्ष छान्नुहोस्</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="label required" for="reporting_fiscal_year_id">प्रगति आर्थिक वर्ष</label>
                            <select name="reporting_fiscal_year_id" class="form-control" id="reporting_fiscal_year_id" required>
                                <option value="">-</option>
                                @foreach ($fiscal_years as $fiscal_year)
                                    <option value="{{ $fiscal_year->id }}" <?php  echo $default_fiscal_year?$default_fiscal_year==$fiscal_year->id? 'selected':'':'' ?>>{{ $fiscal_year->code }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="label required" for="month_id">प्रगति महिना</label>
                            <div class="form-group check_validation_d">
                                <select  class="form-control" name="month_id" id="month_id" required>
                                    @foreach ($months as $month)
                                        <option value="{{ $month->id }}">{{ $month->name_lc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card h-100">
                                <div id="milstone_table_body"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-none" id="submit_area">
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
                $('#submit_area').addClass('d-none');
                getProjectData();
            });
            $('#fiscal_year_id').change(function(){
                $('#submit_area').addClass('d-none');
                getProjectData();
            });
            $('#project_id').change(function(){
                getMilestoneData();
            });
            getProjectData();

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
                        $('#submit_area').removeClass('d-none');
                    }
                });

            } else {
                $('#milstone_table_body').empty()
                $('#submit_area').addClass('d-none');
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

                        if(response.status == "success"){
                            Swal.fire({
                                icon: response.status,
                                title: response.title,
                                text: response.message,
                            });

                            setTimeout(window.location.href = response.url, 3000);
                        }else if(response.status == 'fail'){
                            Swal.fire({
                                icon: response.status,
                                title: response.title,
                                text: response.message,
                            });
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
 @endsection
