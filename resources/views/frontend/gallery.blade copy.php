
@extends('layout.base')
@section('content')
    <!--End of Header Area-->
    <!--Breadcrumb Banner Area Start-->
    <div class="breadcrumb-banner-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-text">
                        <h1 class="text-center">Gallery Four Column</h1>
                        <div class="breadcrumb-bar">
                            <ul class="breadcrumb text-center">
                                <li><a href="index.html">Home</a></li>
                                <li>Gallery Four Column</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Breadcrumb Banner Area-->
    <!--Gallery Area Start-->
    <div class="gallery-area pt-100 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="hp__portfolio__area gallery__masonry__activation ptb--110 text-center">
                        <div class="gallery__menu">
                            <button data-filter="*" class="is-checked">ALL</button>
                            @foreach ($categories as $category)
                                @if(count($category->galleries))
                                    <button data-filter=".cat--{{$category->id}}" class="">{{$category->name}}</button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="row masonry__wrap">
                <!--Single Gallery Image Start-->
                @if(count($galleries))
                    @foreach ($galleries as $gallery)
                        <div class="col-md-4 col-lg-3 col-sm-6 gallery__item cat--{{$gallery->category->id}}">
                            <div class="single-gallery-img mb-30">
                                <a href="{{url('/') . '/storage/uploads/' . $gallery->file_upload}}" data-fancybox="images"><img src="{{url('/') . '/storage/uploads/' . $gallery->file_upload}}" alt=""></a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection