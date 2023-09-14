@extends('layout.base')
@section('content')
<div class="page-content header-clear-small">
    @include('frontend.index_slider')
    @include('frontend.index_platform')
    @include('frontend.index_winings')
    @include('frontend.index_review')
    @include('frontend.index_dark_mode')
    @include('frontend.index_services')
    @include('frontend.index_payment')
    @include('frontend.index_footer')
</div>
    @include('frontend.index_extra')
@endsection
