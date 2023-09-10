@extends('layout.base')
@section('content')
    <!--Breadcrumb Banner Area Start-->
    <div class="breadcrumb-banner-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-text">
                        <h1 class="text-center">Our Team</h1>
                        <div class="breadcrumb-bar">
                            <ul class="breadcrumb text-center">
                                <li><a href="{{url('/home')}}">Home</a></li>
                                <li>Team</li>
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
                        <img src="{{url('/') . '/storage/uploads/' . $team_detail->file_upload}}" alt="">
                    </div>
                </div>
                <!--Team Content-->
                <div class="col-lg-7 col-12">
                    <div class="team-content">
                        <h3 class="title">{{$team_detail->name}}</h3>
                        <p>{!! $team_detail->description !!}</p>
                        <div class="row">
                            <div class="col-md-6 col-12 mb-30">
                                <h4>Persoanl Info</h4>
                                <ul>
                                    <li><i class="zmdi zmdi-home"></i>{{$team_detail->address}}</li>
                                    <li><i class="zmdi zmdi-phone-in-talk"></i><a href="tel:{{$team_detail->phone}}">{{$team_detail->phone}}9</a></li>
                                    <li><i class="zmdi zmdi-email"></i><a href="mailto:{{$team_detail->email}}">{{$team_detail->email}}</a></li>
                                </ul>
                                @if(count($team_detail->hrSocialMedias))
                                    <div class="social">
                                        @foreach ($team_detail->hrSocialMedias as $hrSocialMedia)
                                            <a href="{{$hrSocialMedia->url}}" class="{{strtolower($hrSocialMedia->socialMedia->name)}}"><i class="fa fa-{{strtolower($hrSocialMedia->socialMedia->name)}}"></i></a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 col-12 mb-30">
                                <h4>Professional Info</h4>
                                <ul>
                                    <li><span>Degrees:</span> <span><a href="#">{{$team_detail->degree}}</a></span></li>
                                    <li><span>Institute:</span> <span>{{$team_detail->institute}}</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection