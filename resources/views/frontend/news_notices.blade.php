@extends('layout.base')
@section('content')
    <!--Breadcrumb Banner Area Start-->
    <div class="breadcrumb-banner-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-text">
                        <h1 class="text-center">News & Notices</h1>
                        <div class="breadcrumb-bar">
                            <ul class="breadcrumb text-center">
                                <li><a href="{{url('/home')}}">Home</a></li>
                                <li>News & Notices</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Breadcrumb Banner Area-->
    <!--Event Area Start-->
    <div class="event-area section-padding bg-white event-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title-wrapper">
                        <div class="section-title">
                            <h3>News & Notice</h3>
                            <p>Read News and Notice Related To Lincon International College</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($news as $item)
                    <div class="col-lg-4 col-md-4">
                        <div class="single-event-item event-style-2">
                            <div class="single-event-image">
                                <a href="{{url('/'). '/news-detail/' . $item->id}}">
                                    <img src="{{url('/') . '/storage/uploads/' . $item->file_upload}}" alt="">
                                    {{-- <span></span> --}}
                                </a>
                            </div>
                            <div class="single-event-text">
                                <h3><a href="{{url('/'). '/news-detail/' . $item->id}}">{{$item->title}}</a></h3>
                                <div class="single-item-comment-view">
                                    <span><i class="zmdi zmdi-calendar"></i>{{date('d', strtotime($item->date))}} {{date('M', strtotime($item->date))}}</span>
                                    <span><i class="zmdi zmdi-eye"></i>{{$item->visit_counts}}</span>
                                </div>
                                <p>{!! Str::limit($item->description, 80) !!}</p>
                                <a class="button-default" href="{{url('/'). '/news-detail/' . $item->id}}">Know More</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12">
                        {{ $news->links('inc.pagination') }}
                </div>
            </div>
        </div>
    </div>
@endsection
