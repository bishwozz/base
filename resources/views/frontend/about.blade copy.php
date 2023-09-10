@extends('layout.base')
@section('content')
    <!--Breadcrumb Banner Area Start-->
    <div class="breadcrumb-banner-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-text">
                        <h1 class="text-center">ABOUT US</h1>
                        <div class="breadcrumb-bar">
                            <ul class="breadcrumb text-center">
                                <li><a href="{{url('/home')}}">Home</a></li>
                                <li>ABOUT US</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Breadcrumb Banner Area-->
    <!--About Area Start--> 
    <div class="about-area mt-95">
        <div class="container">
            @foreach ($about_us as $about)
                <div class="row">
                    <div class="col-lg-7">
                        <div class="about-container">
                            <h3><span class="orange-color">{{$about->title}}</span></h3>
                            <p>{{$about->details}}</p>
                            {{-- <a class="button-default" href="#">Learn Now</a>	       --}}
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <!--About Image Area Start-->
                        <div class="about-image-area img-full">
                            <img src="{{url('/') . '/storage/uploads/' . $about->file_upload}}" alt="">
                        </div>
                        <!--About Image Area End-->
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!--End of About Area-->
    <!--Fun Factor Area Start-->
    <div class="fun-factor-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-fun-factor mb-30">
                        <h2><span class="counter">{{$teacher_count}}</span>+</h2>
                        <h4>Teachers</h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-fun-factor mb-30">
                        <h2><span class="counter">{{$department_count}}</span>+</h2>
                        <h4>Departments</h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-fun-factor mb-30">
                        <h2><span class="counter">{{$course_count}}</span>+</h2>
                        <h4>Courses</h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="single-fun-factor mb-30">
                        <h2><span class="counter">{{$member_count}}</span>+</h2>
                        <h4>Members</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Fun Factor Area-->   
    <!--Teachers Area Start--> 
    <div class="teachers-area section-padding bg-white">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title-wrapper">
                        <div class="section-title">
                            <h3>OUR TEACHERS</h3>
                            <p>There are many variations of passages</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($teachers as $teacher)
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="agent mb-30">
                            <div class="image img-full">
                                <a class="img" href="{{url('/'). '/team-detail/' . $teacher->id}}"><img src="{{url('/') . '/storage/uploads/' . $teacher->file_upload}}" alt=""></a>
                                @if(count($teacher->hrSocialMedias))
                                    <div class="social">
                                        @foreach ($teacher->hrSocialMedias as $hrSocialMedia)
                                            <a href="{{$hrSocialMedia->url}}" class="{{strtolower($hrSocialMedia->socialMedia->name)}}"><i class="fa fa-{{strtolower($hrSocialMedia->socialMedia->name)}}"></i></a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="content">
                                <h4 class="title"><a href="{{url('/'). '/team-detail/' . $teacher->id}}">{{$teacher->name}}</a></h4>
                                <a href="tel:{{$teacher->phone}}" class="phone">{{$teacher->phone}}</a>
                                <a href="mailto:{{$teacher->email}}" class="email">{{$teacher->email}}</a>
                                <span class="properties">Teacher</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!--Teachers Area End--> 
    <!--Testimonial Area Start-->
    <div class="testimonial-area">
        <div class="container">
            <div class="row">
                <div class="testimonial-slider-2 owl-carousel">
                    @foreach ($sayings as $saying)
                        <div class="col-12">
                            <div class="single-testimonial testimonial-style-2">
                                <div class="des-testimonial">
                                    <p>{!! $saying->saying !!}</p>
                                </div>
                                <div class="testimonial-author">
                                    <div class="author-img">
                                        <img src="{{url('/') . '/storage/uploads/' . $saying->image}}" alt="">
                                    </div>
                                    <div class="author-content">
                                        <h5>{{$saying->said_by}}</h5>
                                        @if($saying->human_resource_id)
                                            <p>{{$saying->humanResource->email}}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        {{-- {{substr($header_footer_data['college_details']->gps,13,-88)}} --}}
        @if(strpos($header_footer_data['college_details']->gps, '<iframe') !== false)
            <iframe src="{{substr($header_footer_data['college_details']->gps,13,-88)}}" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        @else
            <iframe src="{{$header_footer_data['college_details']->gps}}" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        @endif
    </div>
@endsection
