@extends(backpack_view('blank'))

@section('after_styles')
    <style>
        .btn-check+.btn-primary {
            background-color: #F5F5F5 !important;
            border-color: transparent !important;
            color: #000 !important;
        }

        .btn-check:checked+.btn-primary {
            background-color: var(--primary) !important;
            color: #fff !important;
            outline-style: none !important;
            outline-color: transparent !important;
            box-shadow: none !important;

        }


        .btn-check:focus+.btn-primary {
            background-color: var(--primary) !important;
            color: #fff !important;
            outline-style: none !important;
            outline-color: transparent !important;
            box-shadow: none !important;

        }



        .field [type="radio"] {
            display: none;
        }
    </style>

    {{-- <link rel="stylesheet" href="{{asset('css/patient-info.css')}}"> --}}

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection


@section('header')
    <div class="heading my-2 p-3">
        <h4>
            <a class="btn btn-primary btn-sm" href="{{ backpack_url('pt-project') }}" class="hidden-print back-btn"><i
                    class="fa fa-angle-double-left"></i> {{ trans('Back') }}</a>
        </h4>
    </div>
@endsection

@section('content')
    <div id="loadingDialog" class="hidden">
        <div class="loadingSpinner"></div>
        <h3 class="text-white">Please Wait...</h3>
    </div>


    <div class="container">
        <h2 class="section-heading">आयोजना विवरण</h2>
        <div class="row mt-4">
            <div class="col">
                <div class="card custom-card">
                    <div class="card-body">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>क्र.सं.</th>
                                    <th>आर्थिक वर्ष</th>
                                    <th>मन्त्रालय</th>
                                    <th>आयोजनाको नाम</th>
                                    <th>आयोजना कोड</th>
                                    <th>आयोजना बजेट</th>
                                    <th>मिति देखि</th>
                                    <th>मिति सम्म</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach ($projects as $project)
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $project->fiscalYear->code }}</td>
                                        <td>{{ $project->ministry->name_lc }}</td>
                                        <td>{{ $project->project_name }}</td>
                                        <td>{{ $project->project_code }}</td>
                                        <td>{{ $project->project_budget }}</td>
                                        <td>{{ $project->from_date_bs }}</td>
                                        <td>{{ $project->to_date_bs }}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-5">
            <h2 class="section-heading">आयोजनाको माइलस्टोन विवरण</h2>
            <button id="openModalButton" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> माइलस्टोन थाप</button>
        </div>

        <div class="row mt-4">
            <div class="col">
                <div class="card custom-card">
                    <div class="card-body">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>क्र.सं.</th>
                                    <th>नाम</th>
                                    <th>माइलस्टोन स्कोर</th>
                                    <th>मिति सम्म (बि.स.)</th>
                                    <th>मिति सम्म (ई.स.)</th>
                                    <th>सक्रिय हो ?</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($milestones as $milestone)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $milestone->name }}</td>
                                        <td>{{ $milestone->milestone_score }}</td>
                                        <td>{{ $milestone->to_date_bs }}</td>
                                        <td>{{ $milestone->to_date_ad }}</td>
                                        <td>
                                            @if ($milestone->is_active == 1)
                                                <i class="fas fa-check-circle text-success"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-sm btn-edit MilestoneEditBtn"
                                                data-id="{{ $milestone->id }}" data-name="{{ $milestone->name }}"
                                                data-milestone_score="{{ $milestone->milestone_score }}"
                                                data-to_date_bs="{{ $milestone->to_date_bs }}"
                                                data-to_date_ad="{{ $milestone->to_date_ad }}"
                                                data-description="{{ $milestone->description }}"
                                                data-is_active="{{ $milestone->is_active }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-sm btn-delete milestoneDeleteBtn"
                                                data-id="{{ $milestone->id }}">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <!-- Add Milestone Modal -->
    <div id="modalContent" style="display: none;width:80%;">
        <div class="modal-body">
            <h4 style="text-align:center;">माइलस्टोन थप गर्नुहोस</h4>
            <form action="/admin/add-milestone" method="POST" class="row" id="add_milestone_form">
                {!! csrf_field() !!}
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <input type="hidden" name="milestone_id" value="">
                <div class="form-group col-md-6">
                    <label for="milestoneName">नाम</label>
                    <input type="text" name="name" class="form-control" id="milestoneName" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="milestoneScore">माइलस्टोन स्कोर</label>
                    <input type="number" max="100" step="any" name="milestone_score" class="form-control"
                        id="milestoneScore" required>
                </div>

                <div class="field col-md-6 pt-3">
                    <label class="required" for="date-bs">म्याद मिति(बि.स.)</label>
                    <input placeholder="YYYY-MM-DD" class="form-control " id="date_bs" type="text" name="to_date_bs"
                        required>
                </div>
                <div class="field col-md-6 pt-3">
                    <label class="required" for="date-ad">म्याद मिति(ई.स.)</label>
                    <input class="form-control" id="date_ad" type="date" name="to_date_ad" tabindex="1" required>
                </div>




                <div class="form-group col-md-12">
                    <label for="milestoneDescription">विवरण</label>
                    <textarea class="form-control" name="description" value="" id="milestoneDescription" rows="3"></textarea>
                </div>
                <div class="field form-group col-md-6">
                    <label for="isActive">सक्रिय हो ?</label><br>
                    <input type="radio" class="btn-check form-control" value="1" name="is_active" id="is_active_yes"
                        autocomplete="off" checked>
                    <label class="btn btn-primary" for="is_active_yes">हो</label>

                    <input type="radio" class="btn-check" value="0" name="is_active" id="is_active_no"
                        autocomplete="off">
                    <label class="btn btn-primary" for="is_active_no">होइन</label>
                </div>


            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" onclick="cancel()" data-dismiss="modal">रद्द गर्नुहोस्</button>
            <button type="button" id="add_milestone" class="btn btn-primary">थप</button>
        </div>
    </div>
    <!-- End Modal Add Milestone-->

    <!-- Modal Delete milestone-->
    <form action="{{ route('milestone.delete') }}" id="milestoneDeleteForm" method="post">
        @csrf
        <div class="modal fade" id="milestoneDeleteModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <h4>Are you sure want to delete this product?</h4>

                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="milestone_id" class="milestone_id">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- End Modal Delete milestone-->
@endsection




@section('after_scripts')
    <script>
        var loadingDialog = document.getElementById("loadingDialog");
        $(document).ready(function() {

            function cancel() {
                $.fancybox.close();
            }

            $('#openModalButton').click(function() {
                $('#add_milestone_form')[0].reset();
                $.fancybox.open({
                    src: '#modalContent',
                    type: 'inline',
                    opts: {
                        // Fancybox options (if needed)
                    }
                });
            });

            $('#date_bs').nepaliDatePicker({
                npdMonth: true,
                npdYear: true,
                onChange: function() {
                    $('#date_ad').val(BS2AD($('#date_bs').val()));
                }
            });

            $('#date_bs').change(function() {
                $('#date_ad').val(BS2AD($('#date_bs').val()));
            });

            $('#date_ad').change(function() {
                $('#date_bs').val(AD2BS($('#date_ad').val()));
            });

        });

        $('#add_milestone').click(function(e) {

            e.preventDefault();
            inputsValid = validateInputs(this);
            if (inputsValid) {
                $.LoadingOverlay('show', {
                    text: "Saving ..."
                });
                $.ajax({
                    url: $('#add_milestone_form').attr('action'),
                    type: 'POST',
                    data: $('#add_milestone_form').serialize(),
                    success: function(response) {
                        $.LoadingOverlay('hide', {
                            text: "Loading ..."
                        });
                        Swal.fire({
                            icon: response.status,
                            title: response.title,
                            text: response.message,
                        });

                        if (response.status == 'success') {

                            setTimeout(location.reload(), 3000);
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

            const textarea = ths.parentElement.parentElement.querySelector("textarea");

            const valid = textarea.checkValidity();

            if (!valid) {
                inputsValid = false;
                textarea.classList.add("invalid-input");
            } else {
                textarea.classList.remove("invalid-input");
            }
            return inputsValid;
        }


        $(".MilestoneEditBtn").click(function() {

            const id = $(this).data('id');
            const name = $(this).data('name');
            const milestone_score = $(this).data('milestone_score');
            const to_date_bs = $(this).data('to_date_bs');
            const to_date_ad = $(this).data('to_date_ad');
            const description = $(this).data('description');
            const is_active = $(this).data('is_active');
            debugger;
            $.fancybox.open({
                src: '#modalContent',
                type: 'inline',

            });

            // Set data to Form Edit
            $('input[name="milestone_id"]').val(id);
            $('input[name="name"]').val(name);
            $('input[name="milestone_score"]').val(milestone_score);
            $('input[name="to_date_bs"]').val(to_date_bs);
            $('input[name="to_date_ad"]').val(to_date_ad);
            $('textarea[name="description"]').val(description);

            if (is_active == 1) {
                $('input[id="is_active_yes"]').prop('checked', true);
            } else {
                $('input[id="is_active_no"]').prop('checked', true);
            }

        });


        // JavaScript code
        // Click event handler for delete button
        $('.btn-delete').click(function(e) {
            e.preventDefault();

            var milestoneId = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure?',
                text: 'This action cannot be undone.',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Delete the milestone using AJAX
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('milestone.delete') }}',
                        data: {
                            milestone_id: milestoneId
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.title,
                                    text: response.message,
                                }).then(() => {
                                    // Reload the window
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: response.title,
                                    text: response.message,
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops',
                                text: 'Please Try Again',
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
