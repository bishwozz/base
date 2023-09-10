<!--====================  footer area ====================-->
<div class="footer-area-wrapper bg-gray">
    <div class="footer-area section-space--ptb_80">
        <div class="container">
            <div class="row footer-widget-wrapper">
                <div class="col-lg-4 col-md-6 col-sm-6 footer-widget">
                    <div class="footer-widget__logo mb-30">
                        @if (isset($header_footer_data->logo) && $header_footer_data->logo)
                            <img src="{{ assets($header_footer_data->logo) }}" width="160" height="48"
                                class="img-fluid" alt="">
                        @else
                            <img src="frontend/images/logo/dark-logo-160x48.webp" width="160" height="48"
                                class="img-fluid" alt="">
                        @endif

                    </div>
                    <ul class="footer-widget__list">
                        <li>{{ $header_footer_data->full_address ?? '' }}</li>
                        <li><a href="mailto:contact@aeroland.com"
                                class="hover-style-link">{{ $header_footer_data->email ?? '' }}</a></li>
                        <li><a href="tel:123344556"
                                class="hover-style-link text-black font-weight--bold">(+977){{ $header_footer_data->phone ?? '' }}</a>
                        </li>
                        {{-- <li><a href="https://hasthemes.com/" lass="hover-style-link text-color-primary">www.mitech.xperts.com</a></li> --}}
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 footer-widget">

                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 footer-widget">
                    <h6 class="footer-widget__title mb-20">Quick links</h6>
                    <ul class="footer-widget__list">
                        <li><a href="#" class="hover-style-link">Pick up locations</a></li>
                        <li><a href="#" class="hover-style-link">Terms of Payment</a></li>
                        <li><a href="#" class="hover-style-link">Privacy Policy</a></li>
                        <li><a href="#" class="hover-style-link">Where to Find Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 footer-widget">
                    <h6 class="footer-widget__title mb-20">Support</h6>
                    <ul class="footer-widget__list">
                        <li><a href="#" class="hover-style-link">Forum Support</a></li>
                        <li><a href="#" class="hover-style-link">Help & FAQ</a></li>
                        <li><a href="#" class="hover-style-link">Contact Us</a></li>
                        <li><a href="#" class="hover-style-link">Pricing and plans</a></li>
                        <li><a href="#" class="hover-style-link">Cookies Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 footer-widget">
                    <h6 class="footer-widget__title mb-20">IT Services</h6>
                    <ul class="footer-widget__list">
                        <li><a href="#" class="hover-style-link">Managed IT</a></li>
                        <li><a href="#" class="hover-style-link">IT Support</a></li>
                        <li><a href="#" class="hover-style-link">IT Consultancy</a></li>
                        <li><a href="#" class="hover-style-link">Cloud Computing</a></li>
                        <li><a href="#" class="hover-style-link">Cyber Security</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-copyright-area section-space--pb_30">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <span class="copyright-text">&copy; 2023 Mitech. <a href="https://hasthemes.com/">All Rights
                            Reserved.</a></span>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <ul class="list ht-social-networks solid-rounded-icon">

                        <li class="item">
                            <a href="https://twitter.com/" target="_blank" aria-label="Twitter"
                                class="social-link hint--bounce hint--top hint--primary">
                                <i class="fab fa-twitter link-icon"></i>
                            </a>
                        </li>
                        <li class="item">
                            <a href="https://facebook.com/" target="_blank" aria-label="Facebook"
                                class="social-link hint--bounce hint--top hint--primary">
                                <i class="fab fa-facebook-f link-icon"></i>
                            </a>
                        </li>
                        <li class="item">
                            <a href="https://instagram.com/" target="_blank" aria-label="Instagram"
                                class="social-link hint--bounce hint--top hint--primary">
                                <i class="fab fa-instagram link-icon"></i>
                            </a>
                        </li>
                        <li class="item">
                            <a href="https://linkedin.com/" target="_blank" aria-label="Linkedin"
                                class="social-link hint--bounce hint--top hint--primary">
                                <i class="fab fa-linkedin link-icon"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--====================  End of footer area  ====================-->
