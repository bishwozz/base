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

<div class="container ">
    <div class="row ">
      <div class="col-lg-6 left d-flex flex-column justify-content-center align-items-center  ">
        <div class="logo_wrapper text-center text-white">
          <img src="/img/nepallogo.png" alt="" class="mb-3" width="25%" />
          <h4>लुम्बिनी प्रदेश सरकार</h4>
          <h4>मुख्यमन्त्री तथा मन्त्रिपरिषद्को कार्यालय</h4>
          <h5>राप्ती उपत्यका (देउखुरी), नेपाल</h5>
        </div>
        <div class="company_label">
          &copy; Governance Automation Pvt.Ltd.  All rights reserverd.
        </div>
      </div>
      <div class="col-lg-6 p-5 right d-flex align-items-center justify-content-center">
        {{-- <div class="center_icon">
          <img src="/img/ecabinet.png" alt="" width="100%" class="image" />
        </div> --}}
        <div class="right-form p-5 w-100" style="z-index: 1">
          <div class="mb-3 text-center">
            <img src="/img/ecabinet.png" alt="" width="50%" />
          </div>
          <div class="">
            <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('backpack.auth.login') }}">
                {!! csrf_field() !!}
              <div class="mb-3">
                 <label for="username" class="form-label">इमेल</label>
                 <input type="text" class="form-control {{ $errors->has($email_or_phone_no) ? ' is-invalid' : '' }}" name="{{ $email_or_phone_no }}" value="{{ old($email_or_phone_no) }}" id="username" placeholder="इमेल ठेगाना वा फोन नं">
                    @if ($errors->has($email_or_phone_no))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first($email_or_phone_no) }}</strong>
                        </span>
                    @endif
              </div>
              <div class="">
                <label for="password" class="form-label">पासवर्ड</label>
                <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" placeholder="**************" name="password" class="password">
                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
              </div>
              <div class="mb-3 form-check py-2" style="color: #33579F">
                  <input class="form-check-input" type="checkbox" name="toggle" id="togglePassword">
                  <label class="form-check-label" for="togglePassword">पासवर्ड देखाउनुहोस्</label>
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


  <script>
    const togglePassword = document.querySelector("#togglePassword");
    const passwordInput = document.querySelector("#password");

    togglePassword.addEventListener("click", function () {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    });

</script>
@endsection
