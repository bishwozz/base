@extends(backpack_view('layouts.top_left'))

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card" >
            <div class="card-header bg-primary p-1">
                <div class="row">
                    <div class="col-md-8">
                        <i class="la la-chart-pie"> <i class="la la-chart-bar"></i> Pivot Report Data</i>
                    </div>
                    <div class="col">
                        <button class="button float-right  pdf-button export-r btn btn-warning text-dark" onclick="print('.pvtRendererArea')"> <i class="la la-print" aria-hidden="true"></i> Print </button>
                        <button class="button float-right  export-button export-r btn btn-warning text-dark mr-2" onclick="tableToExcel('.pvtRendererArea', 'pivotTableWorking')"><i class="la la-file-excel-o" aria-hidden="true"></i> Export To Excel</button>
                    </div>
                </div>
            </div>
                
                <div id="output" style="overflow-x:scroll;"></div>
        </div>
    </div>
</div>

@endSection