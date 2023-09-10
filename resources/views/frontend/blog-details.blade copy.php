@extends('layout.base')
@section('content')
    <!--Breadcrumb Banner Area Start-->
    <div class="breadcrumb-banner-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-text">
                        <h1 class="text-center">Blog Details</h1>
                        <div class="breadcrumb-bar">
                            <ul class="breadcrumb text-center">
                                <li><a href="{{url('/home')}}">Home</a></li>
                                <li>Blog Details</li>
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
                            <img src="{{url('/') . '/storage/uploads/' . $blog->image}}" alt="">
                        </div>
                        <div class="blog-content">
                            <div class="single-item-comment-view">
                                <span><i class="zmdi zmdi-calendar-check"></i>{{$blog->date}}</span>
                                <span><i class="zmdi zmdi-eye"></i>{{$blog->visit_counts}}</span>
                            </div>
                            <h3>{{$blog->title}}</h3>
                            <p>{{$blog->description}}</p>
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
                                        <li><a href="{{url('/'). '/blogs/' . $category->id}}">{{$category->name}}<span>{{count($category->blogs)}}</span></a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="single-sidebar-widget">
                            <h4 class="title">Recent Blog Post</h4>
                            <div class="recent-content">
                                @foreach ($latest_blogs as $latest_blog)
                                    <div class="recent-content-item">
                                        <a href="{{url('/'). '/blog-detail/' . $latest_blog->id}}"><img style="height: 70px;" src="{{url('/') . '/storage/uploads/' . $latest_blog->image}}" alt=""></a>
                                        <div class="recent-text">
                                            <h4><a href="{{url('/'). '/blog-detail/' . $latest_blog->id}}">{{$latest_blog->title}}</a></h4>
                                            <div class="single-item-comment-view">
                                                <span><i class="zmdi zmdi-calendar-check"></i>{{date('d', strtotime($latest_blog->date))}} {{date('M', strtotime($latest_blog->date))}} {{date('Y', strtotime($latest_blog->date))}}</span>
                                                <span><i class="zmdi zmdi-eye"></i>{{$latest_blog->visit_counts}}</span>
                                            </div>
                                            <p>{!! Str::limit($blog->description, 50) !!}</p>
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