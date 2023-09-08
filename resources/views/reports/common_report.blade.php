
@php
    $check_no_of_columns = count($columns);
    $all_reports = array('Cash Report', 'Collection Report', 'Overall Collection Details', 'Cancel Bill Report', 'Due Collection Report');
@endphp
<div class="card p-3">
    <div class="row mt-2">
        <div class="col">
            <h5 class="font-weight-bold" style="text-align: center;"> Report </h5>
            <hr>
        </div>
    </div>
    <div class="row mt-0">
        <div class="col-md-12">
                <div class="col-md-12 p-2" style="overflow-x:auto;">
                    <table id="report_data_table" class="table table-bordered table-striped table-sm" style="width:100%;background-color:lightgrey;">
                        <thead>
                            <tr>
                                @foreach ($columns as $key => $column)
                                        <th class="report-heading">{{$column}}</th>
                                    @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($output as $key => $data)
                            <tr>
                                @foreach ($data as $d)
                                    <td class="report-data">{{$d}}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>

<style>
.report-heading {
    text-align: center;
    font-size:13px;
}
.report-data {
    font-size:12px;
    color:black;
    text-align: center;
    width: auto;
}
.report-data-td{
    color:white;
    text-align: center;
}
tr>th{
    border-bottom: 1px solid white !important;
    border-right: 1px solid white !important;
    background-color:#3B72A0 !important;
    color:white;
}
</style>

<script>
$(document).ready(function () {
    $('#report_data_table').DataTable({
        searching: false,
        paging: true,
        ordering:false,
        select: false,
        bInfo : true,
        lengthChange: false
    });
});
</script>

