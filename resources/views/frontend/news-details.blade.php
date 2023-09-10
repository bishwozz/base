@extends('layout.base')
@section('content')
    <!--Breadcrumb Banner Area Start-->
    <div class="breadcrumb-banner-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-text">
                        <h1 class="text-center">News Details</h1>
                        <div class="breadcrumb-bar">
                            <ul class="breadcrumb text-center">
                                <li><a href="{{url('/home')}}">Home</a></li>
                                <li>News Details</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Breadcrumb Banner Area-->
    <!--Blog Area Start--> 
    <div class="blog-area pt-100 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 blog-post-item">
                    <div class="blog-wrapper blog-details">
                        <div class="blog-img img-full">
                            <img src="{{url('/') . '/storage/uploads/' . $news->file_upload}}" alt="">
                        </div>
                        <div class="blog-content">
                            <div class="single-item-comment-view">
                                <span><i class="zmdi zmdi-calendar-check"></i>{{$news->date_ad}}</span>
                                <span><i class="zmdi zmdi-eye"></i>{{$news->visit_counts}}</span>
                            </div>
                            <h3>{{$news->title}}</h3>
                            <p>{{$news->description}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="sidebar-widget">
                        @if(count($categories))
                            <div class="single-sidebar-widget">
                                <h4 class="title">Categories</h4>
                                <ul class="course-categoris">
                                    @foreach($categories as $category)
                                        <li><a href="{{url('/'). '/news/' . $category->id}}">{{$category->name}}<span>{{count($category->news_notices)}}</span></a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="single-sidebar-widget">
                            <h4 class="title">Recent News</h4>
                            <div class="recent-content">
                                @foreach ($latest_news as $item)
                                    <div class="recent-content-item">
                                        <a href="{{url('/'). '/news-detail/' . $item->id}}"><img style="height: 70px;" src="{{url('/') . '/storage/uploads/' . $item->file_upload}}" alt=""></a>
                                        <div class="recent-text">
                                            <h4><a href="{{url('/'). '/news-detail/' . $item->id}}">{{$item->title}}</a></h4>
                                            <div class="single-item-comment-view">
                                                <span><i class="zmdi zmdi-calendar-check"></i>{{date('d', strtotime($item->date))}} {{date('M', strtotime($item->date))}} {{date('Y', strtotime($item->date))}}</span>
                                                <span><i class="zmdi zmdi-eye"></i>{{$item->visit_counts}}</span>
                                            </div>
                                            <p>{!! Str::limit($item->description, 50) !!}</p>
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