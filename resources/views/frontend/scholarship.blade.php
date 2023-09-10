@extends('layout.base')
@section('css')
<style>
.social-info>a{
    background:orange;
    color:white !important;
    border:white 2px solid;
}
.social-info>a:hover{
    background:white;
    color:orange !important;
}
</style>
@endsection
@section('content')
    <div class="breadcrumb-banner-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-text">
                        <h1 class="text-center">Scholarship</h1>
                        <div class="breadcrumb-bar">
                            <ul class="breadcrumb text-center">
                                <li><a href="{{url('/home')}}">About Us</a></li>
                                <li>Scholarship</li>
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
            @foreach ($scholarships as $scholarship)
                <div class="row">
                    <div class="col-lg-9 blog-post-item order-1 order-lg-2">
                        <div class="blog-wrapper blog-details">
                            
                            <div class="blog-content">
                                <h3>{{$scholarship->title}}</h3>
                                <p>{{$scholarship->description}}</p>
                                <div class="blog-bottom-content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4 style="color:orange; padding-top:30px;"><b><span style="cursor: pointer;" class="showEligibility" onclick="showEligibility()">Eligibility & selection criteria +</span><span style="cursor: pointer;" class="hideEligibility" onclick="hideEligibility()">Eligibility & selection criteria -</span></b></h4>
                                            <p class="eligibility">{{$scholarship->eligibility}}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h4 style="color:orange; padding-top:30px;"><b><span style="cursor: pointer;" class="showProcess" onclick="showProcess()">Application process +</span><span style="cursor: pointer;" class="hideProcess" onclick="hideProcess()">Application process -</span></b></h4>
                                            <p class="process">{{$scholarship->process}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: 10px" class="common-tag-and-next-prev mt-60">
                            <div class="common-tag">
                                <h6>Applicatio Open: </h6>
                                <ul>
                                    <li><a href="#">{{$scholarship->application_open}}</a></li>
                                </ul>
                            </div>
                            <div class="blog-share">
                                <h6>Application Will Close:</h6>
                                <ul>
                                    <li><a href="#">{{$scholarship->application_close}}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="comment-list mt-60">
                            <div class="comment">
                                <div class="comment-content">
                                    <div class="comment-content-bottom">
                                        <h4  class="small-title">When will i get the outcomes</h4>
                                        <p>{{$scholarship->outcome}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12 order-2 order-lg-1">
                        <div class="sidebar-widget">
                        <div style="background:orange; padding:20px;" class="single-sidebar-widget">
                                <h4 style="color: white; margin-top:20px;" class="title"><b>Department Contact info</b></h4>
                                <ul class="course-categoris">
                                    <li><a style="color: white;" href="#">{{$scholarship->contact}}</a>
                                    @if(count($header_footer_data['college_details']->socialMedias))
                                        <h4 style="color: white;padding-top:20px;"><b>Social Info</b></h4>
                                        <div class="social-info social-icons">
                                            @foreach($header_footer_data['college_details']->socialMedias as $socialMedia)
                                                <a href="{{$socialMedia->url}}"><i class="zmdi zmdi-{{strtolower($socialMedia->socialMedia->name)}}"></i></a>
                                            @endforeach
                                        </div>
                                    @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('js')
<script type="text/javascript" src="{{url('frontend/js/vendor/jquery-3.6.0.min.js')}}"></script>
<script>
    function showEligibility(){
        $('.eligibility').show();
        $('.showEligibility').hide();
        $('.hideEligibility').show();
    }
    function hideEligibility(){
        $('.eligibility').hide();
        $('.hideEligibility').hide();
        $('.showEligibility').show();
    }
    function showProcess(){
        $('.process').show();
        $('.showProcess').hide();
        $('.hideProcess').show();
    }
    function hideProcess(){
        $('.process').hide();
        $('.hideProcess').hide();
        $('.showProcess').show();
    }
    $(document).ready(function(){
        $('.eligibility').hide();
        $('.hideEligibility').hide();
        $('.process').hide();
        $('.hideProcess').hide();
    });
</script>
@endsection