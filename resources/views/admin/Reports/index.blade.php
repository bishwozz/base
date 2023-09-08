@extends(backpack_view('blank'))
@push('after_styles')
    <style>
        .dashboard-card-body {
            color: black;
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
    </style>
@endpush
@section('content')
    <div class="card">
        <div class="card-body dashboard-card-body">
            @include('admin.Reports.office_detail')
        </div>
    </div>
@endsection
@section('after_scripts')
    {{-- <script src="{{ asset('js/plotchart.js') }}"></script> --}}
    <script>
        $(document).ready(function() {
        });

        function excelPrint(){

            let data = `is_excel=${true}`;
                if($('#ministry_id').val() !== '') {
                    data += '&ministry_id=' + $('#ministry_id').val();
                }
                if($('#fiscal_year_id').val() !== '') {
                    data += '&fiscal_year_id=' + $('#fiscal_year_id').val();
                }
                if($('#month_id').val() !== '') {
                    data += '&month_id=' + $('#month_id').val();
                }
                if($('#report_type').val() !== '') {
                    data += '&report_type=' + $('#report_type').val();
                }

            window.open('/admin/office-report/excelexport?' + data);
        }

    </script>
@endsection
