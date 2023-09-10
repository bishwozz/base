@extends('layout.base')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/gas_style.css') }}">
@endsection
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
                                <li>Our Team</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Breadcrumb Banner Area-->
    <!-- Team Section Start -->
    <div class="rs-team modify1 pt-120 pb-95 md-pt-80 md-pb-75"> 
        <div class="container">
            <div class="row">
                @foreach ($teams as $team)
                    <div class="col-lg-4 col-md-6 mb-50">
                        <div class="team-item-wrap">
                            <div class="team-wrap">
                                <div class="image-inner">
                                    <a href="{{url('/'). '/team-detail/' . $team->id}}"><img src="{{url('/') . '/storage/uploads/'.$team->file_upload}}" alt=""></a>
                                </div>
                            </div>
                            <div class="team-content text-center">
                                <h4 class="person-name"><a href="{{url('/'). '/team-detail/' . $team->id}}">{{$team->name}}</a></h4>
                                <span class="designation">{{$team->designation}}</span>
                                @if(count($team->hrSocialMedias))
                                    <ul class="team-social">
                                        @foreach ($team->hrSocialMedias as $hrSocialMedia)
                                            <li><a href="{{$hrSocialMedia->url}}"><i class="fa fa-{{strtolower($hrSocialMedia->socialMedia->name)}}"></i></a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12">
                        {{ $teams->links('inc.pagination') }}
                </div>
            </div><br>
        </div> 
    </div>
    <!-- Team Section End -->
@endsection
