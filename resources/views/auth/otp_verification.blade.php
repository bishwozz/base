@extends(backpack_view('layouts.plain'))

@section('content')


<link rel="stylesheet" href="/css/login.css">

<style>
    .password {
  position: relative;
}


</style>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container">
    <div class="row ">
      <div class="col-lg-6 left d-flex flex-column justify-content-center align-items-center  ">
        <div class="logo_wrapper text-center text-white">
          <img src="/img/nepallogo.png" alt="" class="mb-3" width="25%" />
          <h4>लुम्बिनी प्रदेश सरकार</h4>
          <h4>मुख्यमन्त्री तथा मन्त्रिपरिषद्को कार्यालय</h4>
          <h5>राप्ती उपत्यका (देउखुरी), नेपाल</h5>
        
        <div class="company_label">
          &copy; Governance Automation Pvt.Ltd.  All rights reserverd.
        </div>
      </div>
      </div>
      <div class="col-lg-6 p-5 right d-flex align-items-center justify-content-center">
        <div class="right-form p-5 w-100" style="z-index: 1">
          <div class="mb-3 text-center">
            <img src="/img/ecabinet.png" alt="" width="50%" />
          </div>
          <div class="">
            <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('verify-otp') }}">
                {!! csrf_field() !!}
                <input type="hidden" class="form-control" name="user_id" value="{{ $user->id }}" id="user_id">
                <input type="hidden" class="form-control" name="email" value="{{ $user->email }}" id="email">
                <input type="hidden" class="form-control" name="password" value="{{ $password }}" id="password">
                @php
                    $is_project_in_prod = env('APP_DEBUG', False);
                @endphp
                @if ($is_project_in_prod)
                    <h4 class="text-primary text-center">{{ $user->verification_code }}</h4>
                @endif
              <div class="mb-3">
                <label for="otp_number" class="form-label">OTP</label>
                <input type="text" class="form-control" name="otp_number" value="" id="otp_number"
                    placeholder="OTP NUMBER">
              </div>
              <div class="d-flex justify-content-center">
                <button type="submit" class="btn">लग - इन</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


  @if (isset($message))
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
      integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
      crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
      swal({
          title: "Error",
          text: "{{ $message }}",
          icon: "error",
      });
  </script>
@endif

@endsection
