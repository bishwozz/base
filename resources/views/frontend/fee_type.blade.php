@extends('layout.base')
@section('content')
    <div class="breadcrumb-banner-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-text">
                        <h1 class="text-center">OUR Fee Structure</h1>
                        <div class="breadcrumb-bar">
                            <ul class="breadcrumb text-center">
                                <li><a href="{{url('/home')}}">Home</a></li>
                                <li>Fee Structure</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div style="margin-top:20px;" class="fee_structure mb-30">
            <h3 class="sidebar-title">Fee Structure </h3>
            <table class="table table-hover">
                <thead class="title">
                    <tr>
                    <th scope="col">SN</th>
                    <th scope="col">Course</th>
                    <th scope="col">Total Fee</th>
                    <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                        @php
                            $course_total_fee=0;
                            $course_fees = json_decode($course->courses_fee);
                        @endphp
                        @foreach ($course_fees as $fee)
                            @php
                                if($fee->total_fee){
                                    $course_total_fee = (int) $course_total_fee + (int) $fee->total_fee;
                                }
                            @endphp
                        @endforeach
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$course->name}}</td>
                        <td>$ {{$course_total_fee}}</td>
                        <td onclick="showSubTable({{$course->id}})" class="expand" id="expand{{$course->id}}"><b style="font-size:22px;">+</b></td><td onclick="hideSubTable({{$course->id}})" class="reduce" id="reduce{{$course->id}}"><b style="font-size:22px;">-</b></td></
                    </tr>
                    <tr style="background: rgba(87, 192, 110, .4)" id="sub-table{{$course->id}}" class="sub-table">
                        <td></td>
                        <td colspan="3">
                            <table class="table table-hover">
                                <thead class="title">
                                    <tr style="background: #b91791; color:white;">
                                        <th scope="col">SN</th>
                                        <th scope="col">Fee Type</th>
                                        <th scope="col">Fee</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($course_fees as $fee)
                                        @php
                                            $fee_type = App\Models\FeeType::where('id',$fee->fee_type_id)->pluck('title')->first();
                                        @endphp
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$fee_type}}</td>
                                            <td>$ {{$fee->total_fee}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
        </div>
    </div>
@endsection
@section('css')
<style>
    .expand:hover, .reduce:hover{
        cursor: pointer;
    }
</style>
@endsection
@section('js')
<script type="text/javascript" src="{{url('frontend/js/vendor/jquery-3.6.0.min.js')}}"></script>
<script>
    function showSubTable(id){
        $('#expand'+id).hide();
        $('#sub-table'+id).show();
        $('#reduce'+id).show();
    }
    function hideSubTable(id){
        $('#reduce'+id).hide();
        $('#sub-table'+id).hide();
        $('#expand'+id).show();
    }
    $(document).ready(function(){
        $('.reduce').hide();
        $('.sub-table').hide();
    });
</script>
@endsection