
<footer>
    </div>
    <div class="footer-widget-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="single-footer-widget">
                        <h3 style="line-height: 30px;">{{$header_footer_data['college_details']->title}}</h3>
                        {{-- <div class="footer-logo">
                            <a href="index.html"><img style="height: 60px;" src="{{url('/') . '/storage/uploads/' . $header_footer_data['college_details']->logo}}" alt=""></a>
                        </div> --}}
                        <p>{{Str::limit($header_footer_data['college_details']->description,100)}}</p>

                        <div class="social-icons">
                            @if(count($header_footer_data['college_details']->socialMedias))
                                @foreach ($header_footer_data['college_details']->socialMedias as $socailmedia)
                                <a target="_blank" href="{{$socailmedia->url}}" class="{{strtolower($socailmedia->socialMedia->name)}}"><i class="fa fa-{{strtolower($socailmedia->socialMedia->name)}}"></i></a>
                                @endforeach
                             @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="single-footer-widget">
                        <h3>GET IN TOUCH</h3>
                        <a style="color: white;" href="tel:.'{{$header_footer_data['college_details']->phone}}'."><i class="fa fa-phone"></i>{{$header_footer_data['college_details']->phone}}</a>
                        <span><a style="color: white;" href="mailto:'.{{$header_footer_data['college_details']->email}}.'"><i class="fa fa-envelope"></i>{{$header_footer_data['college_details']->email}}</a></span>
                        <span><a style="color: white;" href="http://maps.google.com/?q='.{{$header_footer_data['college_details']->full_address}}.'"><i class="fa fa-map-marker"></i>{{$header_footer_data['college_details']->full_address}}</a></span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="single-footer-widget">
                        <h3>Useful Links</h3>
                        <ul class="footer-list">
                            <li><a href="https://info.australia.gov.au/information-and-services/immigration-and-visas/work-visas">Work Permit</a></li>
                            <li><a href="https://www.ato.gov.au/Individuals/Tax-File-Number/Apply-for-a-TFN/">Tax file application</a></li>
                            <li><a href="https://www.usi.gov.au/students/get-a-usi">Unique student Identifier</a></li>
                            <li><a href="{{url('/about_us/scholarships')}}">Scholarships</a></li>
                            <li><a href="{{url('/news_notices')}}">Latest News & updates</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="single-footer-widget">
                        <h3>Agent Portal</h3>
                        <ul id="Instafeed"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Footer Widget Area-->
    <!--Footer Area Start-->
    <div class="footer-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-12">
                    <span>Copyright &copy; All right reserved.Created by <a href="#">{{$header_footer_data['college_details']->title}}</a></span>
                </div>
                <div class="col-lg-4 col-md-4 col-12">
                    <div class="column-right">
                        <span>Privacy Policy , Terms &amp; Conditions</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Footer Area-->
</footer>