@extends('layout.base')
@section('content')
    <!--Breadcrumb Banner Area Start-->
    <div class="breadcrumb-banner-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-text">
                        <h1 class="text-center">Agent Detail</h1>
                        <div class="breadcrumb-bar">
                            <ul class="breadcrumb text-center">
                                <li><a href="{{url('/home')}}">Home</a></li>
                                <li>Agent Detail</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Breadcrumb Banner Area-->
    <!--Team Details Area Start-->
    <div class="team-details-area section-padding">
        <div class="container">
            <div class="row row-25">
                <!--Team Image-->
                <div class="col-lg-5 col-12">
                    <div class="team-image">
                        <img src="{{url('/') . '/storage/uploads/' . $agent->file_upload}}" alt="">
                    </div>
                </div>
                <!--Team Content-->
                <div class="col-lg-7 col-12">
                    <div class="team-content">
                        <h3 class="title">{{$agent->agent_name}}</h3>
                        <p>{!! $agent->description !!}</p>
                        <div class="row">
                            <div class="col-md-6 col-12 mb-30">
                                <h4>Agent Info</h4>
                                <ul>
                                    <li><i class="zmdi zmdi-home"></i>{{$agent->address}}, {{$agent->city}}, {{$agent->country}}</li>
                                    <li><i class="zmdi zmdi-phone-in-talk"></i><a href="#">{{$agent->phone}}</a></li>
                                    <li><i class="zmdi zmdi-email"></i><a href="#">{{$agent->email}}</a></li>
                                    <li><i class="zmdi zmdi-view-web"></i><a href="#">{{$agent->website}}</a></li>
                                </ul>
                                @if(count($agent->socialMedias))
                                    <div class="social">
                                        @foreach ($agent->socialMedias as $socailmedia)
                                            <a href="{{$socailmedia->url}}" class="{{strtolower($socailmedia->socialMedia->name)}}"><i class="fa fa-{{strtolower($socailmedia->socialMedia->name)}}"></i></a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection