@extends('layout.base')
@section('content')
    <div class="site-wrapper-reveal">
        <!--====================  Accordion area ====================-->
        <div class="accordion-wrapper section-space--ptb_100">
            <div class="container">

                <div class="row">
                    <div class="col-lg-12">
                        <!-- section-title-wrap Start -->
                        <div class="section-title-wrap text-center section-space--mb_20">
                            <h6 class="section-sub-title mb-20">Style 01</h6>
                            <h3 class="heading">Frequently asked questions</h3>
                        </div>
                        <!-- section-title-wrap Start -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="faq-wrapper  section-space--mt_40">
                            <div id="accordion">
                                <div class="card">
                                    <div class="card-header" id="headingTwo">
                                        <h5 class="mb-0">
                                            <button class="btn-link collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#collapseTwo" aria-expanded="false"
                                                aria-controls="collapseTwo">
                                                How can we help your business?<span> <i class="fas fa-chevron-down"></i>
                                                    <i class="fas fa-chevron-up"></i> </span>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                        data-bs-parent="#accordion">
                                        <div class="card-body">
                                            <p>Mitech takes into consideration every little detail to make sure the system
                                                run smoothly and responsively. Mitech employs a new technique called
                                                Minified Technology for securing customers' database & building up highly
                                                confidential firewalls. </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingThree">
                                        <h5 class="mb-0">
                                            <button class="btn-link collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#collapseThree" aria-expanded="false"
                                                aria-controls="collapseThree">
                                                What are the advantages of Mitech? <span> <i
                                                        class="fas fa-chevron-down"></i>
                                                    <i class="fas fa-chevron-up"></i> </span>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                        data-bs-parent="#accordion">
                                        <div class="card-body">
                                            <p>We reduce redundant complex calculations and lengthy erroneous code texts
                                                with simpler ones to ensure Mitech would run seamlessly and the design is
                                                reserved in its best form when viewed from a wide range of mobile devices &
                                                browsers. </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingFour">
                                        <h5 class="mb-0">
                                            <button class="collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#collapseFour" aria-expanded="false"
                                                aria-controls="collapseFour">
                                                How working process is simplified?<span> <i class="fas fa-chevron-down"></i>
                                                    <i class="fas fa-chevron-up"></i> </span>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                                        data-bs-parent="#accordion">
                                        <div class="card-body">
                                            <p>Our service offerings to enhance customer experience throughout the product
                                                lifecycle includes – test and repair, service management, and end-to-end
                                                warranty management. </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-6">
                        <!-- Start toggles -->
                        <div class="toggles-wrapper section-space--mt_40">
                            <div class="faq-wrapper">
                                <div id="faq-toggles">
                                    <div class="card">
                                        <div class="card-header" id="faq_2">
                                            <h5 class="mb-0">
                                                <button class="btn-link collapsed" data-bs-toggle="collapse"
                                                    data-bs-target="#faq_two" aria-expanded="false" aria-controls="faq_two">
                                                    What are the advantages of Mitech?<span> <i
                                                            class="fas fa-chevron-down"></i>
                                                        <i class="fas fa-chevron-up"></i> </span>
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="faq_two" class="collapse" aria-labelledby="faq_2">
                                            <div class="card-body">
                                                <p>Mitech takes into consideration every little detail to make sure the
                                                    system run smoothly and responsively. Mitech employs a new technique
                                                    called Minified Technology for securing customers' database & building
                                                    up highly confidential firewalls. </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="faq_3">
                                            <h5 class="mb-0">
                                                <button class="btn-link collapsed" data-bs-toggle="collapse"
                                                    data-bs-target="#faq_three" aria-expanded="false"
                                                    aria-controls="faq_three">
                                                    How working process is simplified? <span> <i
                                                            class="fas fa-chevron-down"></i>
                                                        <i class="fas fa-chevron-up"></i> </span>
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="faq_three" class="collapse" aria-labelledby="faq_3">
                                            <div class="card-body">
                                                <p>We reduce redundant complex calculations and lengthy erroneous code texts
                                                    with simpler ones to ensure Mitech would run seamlessly and the design
                                                    is reserved in its best form when viewed from a wide range of mobile
                                                    devices & browsers. </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="faq_4">
                                            <h5 class="mb-0">
                                                <button class="collapsed" data-bs-toggle="collapse"
                                                    data-bs-target="#faq_four" aria-expanded="false"
                                                    aria-controls="faq_four">
                                                    Product Engineering & Services <span> <i
                                                            class="fas fa-chevron-down"></i>
                                                        <i class="fas fa-chevron-up"></i> </span>
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="faq_four" class="collapse" aria-labelledby="faq_4"
                                            data-bs-parent="#faq-toggles">
                                            <div class="card-body">
                                                <p>We are available to freelance hiring with on-demand extra services,
                                                    including WordPress site design/ redesign, WordPress installation,
                                                    all-in-one customization, video production, video editing and stop
                                                    motion video producing.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End toggles -->
                    </div>
                </div>
            </div>
        </div>
        <!--====================  Accordion area  ====================-->


        <!--====================  Accordion area ====================-->
        <div class="accordion-wrapper section-space--ptb_100 border-top">
            <div class="container">

                <div class="row">
                    <div class="col-lg-12">
                        <!-- section-title-wrap Start -->
                        <div class="section-title-wrap text-center section-space--mb_20">
                            <h6 class="section-sub-title mb-20">Style 02</h6>
                            <h3 class="heading">Frequently asked questions</h3>
                        </div>
                        <!-- section-title-wrap Start -->
                    </div>
                </div>


                <div class="row ">
                    <div class="col-lg-6 pe-5">
                        <div class="faq-two-wrapper section-space--mt_40">
                            <div id="accordion_two">
                                <div class="card">
                                    <div class="card-header" id="heading__10">
                                        <h5 class="mb-0 font-weight--bold">
                                            <button class="btn-link" data-bs-toggle="collapse" data-bs-target="#tab__10"
                                                aria-expanded="true" aria-controls="tab__10">
                                                How can we help your business? <span>
                                                    <i class="fas fa-chevron-circle-down"></i>
                                                    <i class="fas fa-chevron-circle-right"></i> </span>
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="tab__10" class="show" aria-labelledby="heading__10"
                                        data-bs-parent="#accordion_two">
                                        <div class="card-body">
                                            <p>Through the collaboration with customers in discussing needs and demand,
                                                we're able to attain mutual understanding, gain customer trust to offer
                                                appropriate advice, and bring about suggestions on suitable technology to
                                                transform your business. </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="heading__11">
                                        <h5 class="mb-0">
                                            <button class="btn-link collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#collapse__11" aria-expanded="false"
                                                aria-controls="collapse__11">
                                                What are the advantages of Mitech?<span>
                                                    <i class="fas fa-chevron-circle-down"></i>
                                                    <i class="fas fa-chevron-circle-right"></i> </span>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapse__11" class="collapse" aria-labelledby="heading__11"
                                        data-bs-parent="#accordion_two">
                                        <div class="card-body">
                                            <p>Mitech takes into consideration every little detail to make sure the system
                                                run smoothly and responsively. Mitech employs a new technique called
                                                Minified Technology for securing customers' database & building up highly
                                                confidential firewalls. </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="heading__12">
                                        <h5 class="mb-0">
                                            <button class="btn-link collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#collapse__12" aria-expanded="false"
                                                aria-controls="collapse__12">
                                                How working process is simplified? <span><i
                                                        class="fas fa-chevron-circle-down"></i>
                                                    <i class="fas fa-chevron-circle-right"></i> </span>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapse__12" class="collapse" aria-labelledby="heading__12"
                                        data-bs-parent="#accordion_two">
                                        <div class="card-body">
                                            <p>We reduce redundant complex calculations and lengthy erroneous code texts
                                                with simpler ones to ensure Mitech would run seamlessly and the design is
                                                reserved in its best form when viewed from a wide range of mobile devices &
                                                browsers. </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="heading__13">
                                        <h5 class="mb-0">
                                            <button class="collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#collapse__13" aria-expanded="false"
                                                aria-controls="collapse__13">
                                                Product Engineering & Services<span><i
                                                        class="fas fa-chevron-circle-down"></i>
                                                    <i class="fas fa-chevron-circle-right"></i> </span>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapse__13" class="collapse" aria-labelledby="heading__13"
                                        data-bs-parent="#accordion_two">
                                        <div class="card-body">
                                            <p>Our service offerings to enhance customer experience throughout the product
                                                lifecycle includes – test and repair, service management, and end-to-end
                                                warranty management. </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <!-- Start single-popup-wrap -->
                        <div class="video-interview section-space--mt_40 video-popup">
                            <a href="https://www.youtube.com/watch?v=9No-FiEInLA" class="video-link mt-30">
                                <div class="single-popup-wrap">
                                    <img class="img-fluid border-radus-5" src="assets/images/bg/faqs-video-poster.webp"
                                        alt="">
                                    <div class="ht-popup-video video-button">
                                        <div class="video-mark">
                                            <div class="wave-pulse wave-pulse-1"></div>
                                            <div class="wave-pulse wave-pulse-2"></div>
                                        </div>
                                        <div class="video-button__two">
                                            <div class="video-play">
                                                <span class="video-play-icon"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- End single-popup-wrap -->
                    </div>
                </div>
            </div>
        </div>
        <!--====================  Accordion area  ====================-->
        <!--====================  Conact us Section Start ====================-->
        <div class="contact-us-section-wrappaer infotechno-contact-us-bg section-space--ptb_120">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-lg-6">
                        <div class="conact-us-wrap-one">
                            <h3 class="heading">Obtaining further information by <span class="text-color-primary">make a
                                    contact</span> with our experienced IT staffs. </h3>

                            <div class="sub-heading">We’re available for 8 hours a day!<br>Contact to require a detailed
                                analysis and assessment of your plan.</div>

                        </div>
                    </div>

                    <div class="col-lg-6 col-lg-6">
                        <div class="contact-info-one text-center">
                            <div class="icon">
                                <span class="fas fa-phone"></span>
                            </div>
                            <h6 class="heading font-weight--reguler">Reach out now!</h6>
                            <h2 class="call-us"><a href="tel:190068668">1900 68668</a></h2>
                            <div class="contact-us-button mt-20">
                                <a href="#" class="btn btn--secondary">Contact us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--====================  Conact us Section End  ====================-->

    </div>
@endsection
