    <!--====================  header area ====================-->
    <div class="header-area">

        <!-- Header Top Wrap Start -->
        <div class="header-top-wrap border-bottom">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <p class="text-center top-message"><a href="#">Now Hiring:</a> Are you a driven and
                            motivated 1st Line IT Support Engineer?</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header Top Wrap End -->

        <div class="header-bottom-wrap">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="header position-relative">
                            <!-- brand logo -->
                            <div class="header__logo top-logo">
                                <a href="index.html">
                                    @if (isset($header_footer_data->logo) && $header_footer_data->logo)
                                        <img src="{{ assets($header_footer_data->logo) }}" aria-label="Mitech Logo"
                                            width="160" height="48" class="img-fluid" alt="">
                                    @else
                                        <img src="frontend/images/logo/logo-dark.webp" aria-label="Mitech Logo"
                                            width="160" height="48" class="img-fluid" alt="">
                                    @endif
                                </a>
                            </div>

                            <div class="header-right flexible-image-slider-wrap">

                                <div class="header-right-inner" id="hidden-icon-wrapper">
                                    <!-- Header Search Form -->
                                    <div class="header-search-form d-md-none d-block">
                                        <form action="#" class="search-form-top">
                                            <input class="search-field" type="text" placeholder="Search…">
                                            <button class="search-submit">
                                                <i class="search-btn-icon fa fa-search"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Header Top Info -->
                                    <div
                                        class="swiper-container header-top-info-slider-werap top-info-slider__container">
                                        <div class="swiper-wrapper header-top-info-inner default-color">
                                            <div class="swiper-slide">
                                                <div class="info-item">
                                                    <div class="info-icon">
                                                        <span class="fa fa-phone"></span>
                                                    </div>
                                                    <div class="info-content">
                                                        <h6 class="info-title">{{ $header_footer_data->phone??''}}</h6>
                                                        <div class="info-sub-title">{{ $header_footer_data->email??''}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="info-item">
                                                    <div class="info-icon">
                                                        <span class="fa fa-map-marker-alt"></span>
                                                    </div>
                                                    <div class="info-content">
                                                        <h6 class="info-title">{{ $header_footer_data->full_address??''}}</h6>
                                                        <div class="info-sub-title">kathmandu,putalishadak</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="info-item">
                                                    <div class="info-icon">
                                                        <span class="fa fa-clock"></span>
                                                    </div>
                                                    <div class="info-content">
                                                        <h6 class="info-title">8:00AM - 6:00PM</h6>
                                                        <div class="info-sub-title">Monday to Saturday</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="swiper-slide">
                                                <div class="info-item">
                                                    <div class="info-icon">
                                                        <span class="fas fa-comment-alt"></span>
                                                    </div>
                                                    <div class="info-content">
                                                        <h6 class="info-title">Online 24/7</h6>
                                                        <div class="info-sub-title">+122 123 4567</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Header Social Networks -->
                                    <div class="header-social-networks style-icons">
                                        <div class="inner">
                                            <a class=" social-link hint--black hint--bottom-left" aria-label="Twitter"
                                                href="https://twitter.com/" data-hover="Twitter" target="_blank">
                                                <i class="social-icon fab fa-twitter"></i>
                                            </a>
                                            <a class=" social-link hint--black hint--bottom-left" aria-label="Facebook"
                                                href="https://facebook.com/" data-hover="Facebook" target="_blank">
                                                <i class="social-icon fab fa-facebook-f"></i>
                                            </a>
                                            <a class=" social-link hint--black hint--bottom-left" aria-label="Instagram"
                                                href="https://instagram.com/" data-hover="Instagram" target="_blank">
                                                <i class="social-icon fab fa-instagram"></i>
                                            </a>
                                            <a class=" social-link hint--black hint--bottom-left" aria-label="Linkedin"
                                                href="https://linkedin.com/" data-hover="Linkedin" target="_blank">
                                                <i class="social-icon fab fa-linkedin"></i>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                                <!-- mobile menu -->
                                <div class="mobile-navigation-icon d-block d-xl-none" id="mobile-menu-trigger">
                                    <i></i>
                                </div>
                                <!-- hidden icons menu -->
                                <div class="hidden-icons-menu d-block d-md-none" id="hidden-icon-trigger">
                                    <a href="javascript:void(0)">
                                        <i class="far fa-ellipsis-h-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="header-bottom-wrap bg-theme-default d-md-block d-none header-sticky">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="header-bottom-inner position-relative">
                                <div class="header-bottom-left-wrap">
                                    <!-- navigation menu -->
                                    <div class="header__navigation d-none d-xl-block">
                                        <nav class="navigation-menu navigation-menu--text_white">
                                            <ul>
                                                <li class="">
                                                    <a href="/home"><span>Home</span></a>
                                                </li>
                                                <li class="has-children has-children--multilevel-submenu">
                                                    <a href="#"><span>Company</span></a>
                                                    <ul class="submenu">
                                                        <li><a href="/about-us"><span>About us</span></a></li>
                                                        <li><a href="contact-us.html"><span>Contact us</span></a></li>
                                                        <li><a href="leadership.html"><span>Leadership</span></a></li>
                                                        <li><a href="why-choose-us.html"><span>Why choose us</span></a>
                                                        </li>
                                                        <li><a href="our-history.html"><span>Our history</span></a>
                                                        </li>
                                                        <li><a href="faqs.html"><span>FAQs</span></a></li>
                                                        <li><a href="careers.html"><span>Careers</span></a></li>
                                                        <li><a href="pricing-plans.html"><span>Pricing plans</span></a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="">
                                                    <a href="/services"><span>Services</span></a>
                                                </li>

                                                <li class="">
                                                    <a href="/blog"><span>Blog</span></a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!--====================  End of header area  ====================-->
