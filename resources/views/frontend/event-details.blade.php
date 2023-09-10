@extends('layout.base')
@section('content')
    <!--Breadcrumb Banner Area Start-->
    <div class="breadcrumb-banner-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-text">
                        <h1 class="text-center">Our Events</h1>
                        <div class="breadcrumb-bar">
                            <ul class="breadcrumb text-center">
                                <li><a href="{{url('/home')}}">Home</a></li>
                                <li>Events</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Breadcrumb Banner Area-->
    <!--Event Area Start-->
    <div class="event-details-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col--12">
                    <div class="events-details-right-sidebar">
                        <div class="events-details-img1 img-full">
                            <h3>{{$event->name}}</h3>
                            <img alt="" src="{{url('/') . '/storage/uploads/' . $event->file_upload}}">
                        </div>
                        <div class="events-details-all">
                            <div class="events-details-time">
                                <div class="time-icon">
                                    <i class="zmdi zmdi-time"></i>
                                </div>
                                <div class="time-text">
                                    <span>Event Time</span>
                                    <p class="time-mrg">{{$event->time}} &emsp;{{$event->date}}</p>
                                </div>
                            </div>
                            <div class="events-details-time mrg-xs">
                                <div class="time-icon">
                                    <i class="zmdi zmdi-pin"></i>
                                </div>
                                <div class="time-text">
                                    <span>Address</span>
                                    <p class="time-mrg">{{$event->location}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="about-lectures">
                            <h3>Event Description</h3>
                            <p>{{$event->description}}</p>
                        </div>
                        <div class="event-content">
                            <h3 class="content2">Event Content</h3>
                            <ul>
                                <li>{{$event->content}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="sidebar-widget">
                        @if(count($categories))
                            <div class="single-sidebar-widget">
                                <h4 class="title">Categories</h4>
                                <ul class="course-categoris">
                                    @foreach($categories as $category)
                                        <li><a href="{{url('/'). '/events/' . $category->id}}">{{$category->name}}<span>{{count($category->events)}}</span></a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="single-sidebar-widget">
                            <h4 class="title">Recent Events</h4>
                            <div class="recent-content">
                                @foreach ($latest_events as $latest_event)
                                    <div class="recent-content-item">
                                        <a href="{{url('/'). '/event-detail/' . $latest_event->id}}"><img style="height: 70px;" src="{{url('/') . '/storage/uploads/' . $latest_event->file_upload}}" alt=""></a>
                                        <div class="recent-text">
                                            <h4 style="margin-top: 15px;"><a href="{{url('/'). '/event-detail/' . $latest_event->id}}">{{$latest_event->name}}</a></h4>
                                            <div class="single-item-comment-view">
                                                <span><i class="zmdi zmdi-time"></i>{{$latest_event->time}}</span>
                                                <span><i class="zmdi zmdi-calendar"></i>{{$latest_event->date}}</span>
                                            </div>
                                            <p>{!! Str::limit($latest_event->description, 50) !!}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection