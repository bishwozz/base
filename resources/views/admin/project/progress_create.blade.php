@extends(backpack_view('blank'))
@section('content')

<div class="card p-0">
    <div class="card-body dashboard-card-body">
        <div class="row">
            <div class="col-12 px-4">
                <div class="page-title-box">
                    <h5 class="page-title float-left"><i class="fa fa-arrow"></i> Project milstone</h5>
                    <div class="page-title-right" style="float: right;">
                    </div>
                </div>
            </div>
        </div>
        <hr class="hr-line1 mt-0">

        <section id="tabular_section" class="parallax-section">
            <div class="container">
                <form>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="ministry_id">ministry</label>
                            <select class="form-control" id="ministry_id">
                                <option value="">-</option>
                                @foreach ($ministries as $ministry)
                                    <option value="{{ $ministry->id }}">{{ $ministry->name_lc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="fiscal_year_id">fiscal_year</label>
                            <select class="form-control" id="fiscal_year_id">
                                <option value="">-</option>
                                @foreach ($fiscal_years as $fiscal_year)
                                    <option value="{{ $fiscal_year->id }}">{{ $fiscal_year->code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="project_id">project</label>
                            <select class="form-control" id="project_id">
                                <option value="">-</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </form>
                <div class="row">
                    <div class="col">
                        <div class="card h-100">
                            <div id="milstone_table_body"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
@section('after_scripts')
    <script>
        $(document).ready(function() {
            getMilestoneData()

            $('#ministry_id').change(function(){
                getMilestoneData();
            });
            $('#fiscal_year_id').change(function(){
                getMilestoneData();
            });
            $('#project_id').change(function(){
                getMilestoneData();
            });

        });

        function getMilestoneData(){
            let data = {
            is_print : false,
            ministry_id : $('#ministry_id').val(),
            fiscal_year_id : $('#fiscal_year_id').val(),
            project_id : $('#project_id').val(),
        }
        $('#milstone_table_body').html('<div class="text-center"><img src="/css/images/loading.gif"/></div>');
        $.ajax({
            type: "POST",
            url: "/admin/getMilestoneData",
            data: data,
            success: function(response){
                $('#milstone_table_body').html(response);
            }
        });
    }

    </script>
 @endsection