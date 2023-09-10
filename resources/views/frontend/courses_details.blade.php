@extends('layout.base')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
   .button-download:hover,.button-apply:hover,.button-enquary:hover{
        color:white;
        opacity: 1;
        font-size: 14.5px;
        background: green !important;
    }
    .button-download{
        padding: 15px !important;
        background: green;
        color: white;
        opacity: .8;
        float: left;
        font-size: 13px !important;
        margin-right: 1%;
        margin-bottom: 5px;
        text-decoration: none;
    }
    .button-apply{
        padding: 15px !important;
        background: green;
        color: white;
        opacity: .8;
        font-size: 13px !important;
        margin-bottom: 5px;
        text-decoration: none;
    }
    .button-enquary{
        width: 97% !important;
        padding: 15px !important;
        background: green;
        color: white;
        margin-bottom: 5px;
        opacity: .8;
        text-align: center;
        text-decoration: none;
    }


    .accordion-button::after {
      background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='%23333' xmlns='http://www.w3.org/2000/svg'%3e%3cpath fill-rule='evenodd' d='M8 0a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2H9v6a1 1 0 1 1-2 0V9H1a1 1 0 0 1 0-2h6V1a1 1 0 0 1 1-1z' clip-rule='evenodd'/%3e%3c/svg%3e");
      transform: scale(.7) !important;
    }
    .accordion-button:not(.collapsed)::after {
      background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='%23333' xmlns='http://www.w3.org/2000/svg'%3e%3cpath fill-rule='evenodd' d='M0 8a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2H1a1 1 0 0 1-1-1z' clip-rule='evenodd'/%3e%3c/svg%3e");
    }
   
</style>
@endsection
@section('content')
    <div class="breadcrumb-banner-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-text">
                        <h1 class="text-center">COURSES DETAILS</h1>
                        <div class="breadcrumb-bar">
                            <ul class="breadcrumb text-center">
                                <li><a href="{{url('/home')}}">Home</a></li>
                                <li>COURSES DETAILS</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End of Breadcrumb Banner Area-->
    <!--Course Area Start-->
    <div class="course-details-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col--12">
                    <div class="course-details-right-sidebar">
                        <div class="events-details-img1 img-full">
                            <h2>{{$course_detail->name}}</h2>
                            <img src="{{url('/') . '/storage/uploads/' . $course_detail->file_upload}}" alt="">
                        </div>
                        <div class="single-item-content">
                            <div class="single-item-comment-view">
                                <span><i class="zmdi zmdi-eye"></i>{{$course_detail->visit_counts}}</span>
                            </div>
                            <div class="single-item-rating">
                                <i class="zmdi zmdi-star"></i>
                                <i class="zmdi zmdi-star"></i>
                                <i class="zmdi zmdi-star"></i>
                                <i class="zmdi zmdi-star"></i>
                                <i class="zmdi zmdi-star-half"></i>
                            </div>
                        </div>
                            @php
                        $course_fees = json_decode($course_detail->courses_fee);
                        @endphp
                        
                        <div class="about-lectures">
                            <h3><b>Programme Overview</b></h3>
                            <p>{!!$course_detail->description!!}</p>
                        </div>
                     </br>

                     {{-- Section For Collabsible --}}
                        <div id="accordion">
                            <div class="card">
                              <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                  <button type="button" class="accordion-button" data-toggle="collapse" data-target="#collapseOne"><b>Delivery Mode</b></button>
                                </h5>
                              </div>
                          
                              <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <p>{!!$course_detail->delivery_mode!!}</p>
                              </div>
                            </div>
                            <div class="card">
                              <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                  <button type="button" class="accordion-button" data-toggle="collapse" data-target="#collapseTwo"><b>Course Facts</b></button>
                                </h5>
                              </div>
                              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <p>{!!$course_detail->course_facts!!}</p>
                                </div>
                              </div>
                            </div>
                            <div class="card">
                              <div class="card-header" id="headingThree">
                                <h5 class="mb-0">
                                  <button type="button" class="accordion-button" data-toggle="collapse" data-target="#collapseThree"><b>Course Structure Summary</b></button>
                                </h5>
                              </div>
                              <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                <div class="card-body">
                                    <p>{!!$course_detail->course_structure_summary!!}</p>
                                </div>
                              </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingFour">
                                  <h5 class="mb-0">
                                  <button type="button" class="accordion-button" data-toggle="collapse" data-target="#collapseFour"><b>Fee Structure</b></button>
                                  </h5>
                                </div>
                                <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                                  <div class="card-body">
                                      <p>{!!$course_detail->fee_structure!!}</p>
                                  </div>
                                </div>
                              </div>
                              <div class="card">
                                <div class="card-header" id="headingFive">
                                  <h5 class="mb-0">
                                  <button type="button" class="accordion-button" data-toggle="collapse" data-target="#collapseFive"><b>Entry Requirement</b></button>
                                  </h5>
                                </div>
                                <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                                  <div class="card-body">
                                      <p>{!!$course_detail->entry_requirement!!}</p>
                                  </div>
                                </div>
                              </div>
                              <div class="card">
                                <div class="card-header" id="headingSix">
                                  <h5 class="mb-0">
                                  <button type="button" class="accordion-button" data-toggle="collapse" data-target="#collapseSix"><b>Application Process</b></button>
                                  </h5>
                                </div>
                                <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
                                  <div class="card-body">
                                      <p>{!!$course_detail->application_process!!}</p>
                                  </div>
                                </div>
                              </div>
                              <div class="card">
                                <div class="card-header" id="headingSeven">
                                  <h5 class="mb-0">
                                  <button type="button" class="accordion-button" data-toggle="collapse" data-target="#collapseSeven"><b>Process Under Law</b></button>
                                  </h5>
                                </div>
                                <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
                                  <div class="card-body">
                                      <p>{!!$course_detail->process_under_law!!}</p>
                                  </div>
                                </div>
                              </div> 
                              <div class="card">
                                <div class="card-header" id="headingEight">
                                  <h5 class="mb-0">
                                  <button type="button" class="accordion-button" data-toggle="collapse" data-target="#collapseEight"><b>For further information please contact</b></button>
                                  </h5>
                                </div>
                                <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion">
                                  <div class="card-body">
                                      <p>{!!$course_detail->contact_details!!}</p>
                                  </div>
                                </div>
                              </div>
                          </div>
                        </div>
                          {{-- End Section For Collabsible --}}
                        </br>
                        <div class="fee_structure mb-30">
                            <h3 class="sidebar-title">Fee Structure </h3>
                            <table class="table table-hover">
                                <thead class="title">
                                    <tr>
                                    <th scope="col">SN</th>
                                    <th scope="col">Fee Type</th>
                                    <th scope="col">Total Fee</th>
                                    <th scope="col">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($course_fees as $fee)
                                        @php
                                        $fee_type = App\Models\FeeType::where('id',$fee->fee_type_id)->pluck('title')->first();
                                        @endphp
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$fee_type}}</td>
                                            <td>$ {{$fee->total_fee}}</td>
                                            <td>{{$fee->description}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="sidebar-widget">
                        <a class="button-default button-download" href="{{ ($course_detail->syllabus)?asset('storage/uploads/').'/'.$course_detail->syllabus:'#' }}">Download <i class="fa fa-download"></i></a>
                        <a class="button-default button-apply" href="{{ ($header_footer_data['college_details']->enrolment_form)?asset('storage/uploads/').'/'.$header_footer_data['college_details']->enrolment_form:'#' }}">Apply Now <i class="fa fa-check-circle"></i></a>
                        <a class="button-default button-enquary" href="{{url('/contact_us')}}">Course Enquary &nbsp;<i class="fa fa-phone-square"></i></a>
                        <div class="single-sidebar-widget">
                            <h3 class="sidebar-title">Course Structure </h3>
                            <ul class="course-menu">
                                <li>Levels :<span>{{$course_detail->levels}}</span></li>
                                <li>Start On :<span>{{$course_detail->start_on}}</span></li>
                                <li>Duration :<span>{{$course_detail->duration}} Hours</span></li>
                                <li>Class Size :<span>{{$course_detail->class_size}} Persone</span></li>
                                <li>Lectures :<span>{{$course_detail->lectures}} Session</span></li>
                                <li>Time :<span>{{$course_detail->time}}</span></li>
                            </ul>
                        </div>
                      
                        <div class="single-sidebar-widget">
                            <h4 class="title">Recent Course</h4>
                            <div class="recent-content">
                                <div class="recent-content-item">
                                    @foreach ($new_course as $item)
                                    <a href="#"><img src="img/event/7.jpg" alt=""></a>
                                    <div class="recent-text">
                                        <h4><a class="mt-2" href="{{url('/'). '/courses/' . $item->id}}">{{$item->name}}</a></h4>
                                        <div class="single-item-comment-view">
                                            <span><i class="zmdi zmdi-eye"></i>{{$item->visit_counts}}</span>
                                        </div>
                                        <p>{!! Str::limit($item->description, 80) !!}</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
<script type="text/javascript" src="{{url('frontend/js/vendor/jquery-3.6.0.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
  $('.collapse').collapse();
</script>
@endsection

