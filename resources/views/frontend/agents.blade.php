@extends('layout.base')
@section('content')
    <!--Breadcrumb Banner Area Start-->
    <div class="breadcrumb-banner-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-text">
                        <h1 class="text-center">agent</h1>
                        <div class="breadcrumb-bar">
                            <ul class="breadcrumb text-center">
                                <li><a href="{{url('/home')}}">Home</a></li>
                                <li>Our Agents</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Breadcrumb Banner Area-->
    <!--Latest News Area Start--> 
    <div class="latest-area section-padding bg-white agent-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title-wrapper">
                        <div class="section-title">
                            <h3>Our Agents</h3>
                            <p>Our agents are always available for you</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($agents as $agent)
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-latest-item">
                            <div class="single-latest-image">
                                <a href="{{url('/'). '/agent-detail/' . $agent->id}}"><img style="max-height:150px;" src="{{url('/') . '/storage/uploads/' . $agent->file_upload}}" alt=""></a>
                            </div>
                            <div class="single-latest-text">
                                <h4><b><a href="{{url('/'). '/agent-detail/' . $agent->id}}">{{Str::limit($agent->agent_name,40)}}</a></b></h4>
                                <div class="single-item-comment-view">
                                <span><i class="zmdi zmdi-pin"></i>{{$agent->city}}, {{$agent->country}}</span>
                                <span><i class="zmdi zmdi-phone"></i>{{$agent->phone}}</span><br>
                                <span><i class="zmdi zmdi-email"></i>{{$agent->email}}</span>
                            </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12">
                        {{ $agents->links('inc.pagination') }}
                </div>
            </div>
        </div>
    </div>
    <!--End of Latest News Area--> 
@endsection
