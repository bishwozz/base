@extends('layout.base')
@section('content')
    <!--Breadcrumb Banner Area Start-->
    <div class="breadcrumb-banner-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-text">
                        <h1 class="text-center">Blog</h1>
                        <div class="breadcrumb-bar">
                            <ul class="breadcrumb text-center">
                                <li><a href="{{url('/home')}}">Home</a></li>
                                <li>Blog</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Breadcrumb Banner Area-->
    <!--Latest News Area Start--> 
    <div class="latest-area section-padding bg-white blog-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title-wrapper">
                        <div class="section-title">
                            <h3>Blogs</h3>
                            <p>There are many variations of passages</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="single-latest-item">
                            <div class="single-latest-image">
                                <a href="{{url('/'). '/blog-detail/' . $blog->id}}"><img src="{{url('/') . '/storage/uploads/' . $blog->image}}" alt=""></a>
                            </div>
                            <div class="single-latest-text">
                                <h3><a href="{{url('/'). '/blog-detail/' . $blog->id}}">{{$blog->title}}</a></h3>
                                <div class="single-item-comment-view">
                                <span><i class="zmdi zmdi-calendar-check"></i>{{date('d', strtotime($blog->date))}} {{date('M', strtotime($blog->date))}} {{date('Y', strtotime($blog->date))}}</span>
                                <span><i class="zmdi zmdi-eye"></i>{{$blog->visit_counts}}</span>
                            </div>
                            <p>{!! Str::limit($blog->description, 140) !!}</p>
                            <a href="{{url('/'). '/blog-detail/' . $blog->id}}" class="button-default">Read More</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12">
                        {{ $blogs->links('inc.pagination') }}
                </div>
            </div>
        </div>
    </div>
    <!--End of Latest News Area--> 
@endsection
