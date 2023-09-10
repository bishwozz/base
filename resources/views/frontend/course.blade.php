@extends('layout.base')
@section('content')
    <div class="breadcrumb-banner-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-text">
                        <h1 class="text-center">Our Course</h1>
                        <div class="breadcrumb-bar">
                            <ul class="breadcrumb text-center">
                                <li><a href="{{url('/home')}}">Home</a></li>
                                <li>Course</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Search Category End-->
    <!--Course Area Start-->
    <div class="course-area bg-white section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title-wrapper">
                        <div class="section-title">
                            <h3>OUR COURSES</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($courses as $course)
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-item mb-50">
                            <div class="single-item-image overlay-effect">
                                <a href="{{url('/'). '/courses/' . $course->id}}"><img src="{{url('/') . '/storage/uploads/' . $course->file_upload}}" alt=""></a>
                            </div>
                            <div class="single-item-text">
                                <h4><a href="{{url('/'). '/courses/' . $course->id}}">{{$course->name}}</a></h4>
                                <p>{!! Str::limit($course->description, 150) !!}</p>
                                <div class="single-item-content">
                                <div class="single-item-comment-view">
                                    <span><i class="zmdi zmdi-accounts"></i>{{$course->visit_counts}}</span>
                                </div>
                                <div class="single-item-rating">
                                    <i class="zmdi zmdi-star"></i>
                                    <i class="zmdi zmdi-star"></i>
                                    <i class="zmdi zmdi-star"></i>
                                    <i class="zmdi zmdi-star"></i>
                                    <i class="zmdi zmdi-star-half"></i>
                                </div>
                                </div>   
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12">
                        {{ $courses->links('inc.pagination') }}
                </div>
            </div>
        </div>
    </div>
    <!--End of Course Area-->
@endsection