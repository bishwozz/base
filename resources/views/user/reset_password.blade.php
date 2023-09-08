@extends(backpack_view('layouts.plain'))

@section('content')

<style>
    .password {
  position: relative;
}


</style>
<div class="login-container py-5">
    <div class="d-flex justify-content-center mb-2 mr-5">
      <img src="{{url('img/login-logo.png')}}" alt="government logo" class="gov-logo">
    </div>
    <div class="login-panel mx-auto">
        <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('password.update') }}">
            {!! csrf_field() !!}
            <img src="{{url('img/cabinet-logo-np.png')}}" alt="cabinet-logo" class="cabinet-logo mt-1">
            <input type="hidden" name="email" value="{{$email}}">
            <input type="hidden" name="token" value="{{$token}}">
            <div class="text-left mb-2 mt-3">
                <label for="password" class="form-label">नयाँ पास्वर्ड</label>
                <div class="input-group">

                    <input type="password" class="form-control" id="password" placeholder="**************" name="new_password" class="password" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="percentage-addon"><i class="la la-eye-slash" id="togglePassword"></i></span>
                    </div>
                    
                </div>
                <label for="password" class="form-label mt-3">नयाँ पास्वर्ड पुष्टी गर्नु होस्</label>
                <div class="input-group">

                    <input type="password" class="form-control" id="confirm_password" placeholder="**************" name="confirm_password" class="password" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="percentage-addon"><i class="la la-eye-slash" id="toggleConfirmPassword"></i></span>
                    </div>
                    
                </div>
            </div>
            <div class="d-grid mt-4 mb-3 mt-5">
                <button type="submit" class="btn btn-light la la-lock">&nbsp;रिसेट गर्नुहोस</button>
            </div>
            <img src="{{url('img/map.png')}}" alt="sudurpachim map" class="map-img">
        </form>
    </div>
  </div>

  <script>
    const togglePassword = document.querySelector("#togglePassword");
    const passwordInput = document.querySelector("#password");
    const toggleConfirmPassword = document.querySelector("#toggleConfirmPassword");
    const confirmPasswordInput = document.querySelector("#confirm_password");

    togglePassword.addEventListener("click", function () {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePassword.classList.remove('la-eye-slash');
            togglePassword.classList.add('la-eye');
        } else {
            passwordInput.type = 'password';
            togglePassword.classList.remove('la-eye');
            togglePassword.classList.add('la-eye-slash');
        }
    });

    toggleConfirmPassword.addEventListener("click", function () {
        if (confirmPasswordInput.type === 'password') {
            confirmPasswordInput.type = 'text';
            toggleConfirmPassword.classList.remove('la-eye-slash');
            toggleConfirmPassword.classList.add('la-eye');
        } else {
            confirmPasswordInput.type = 'password';
            toggleConfirmPassword.classList.remove('la-eye');
            toggleConfirmPassword.classList.add('la-eye-slash');
        }
    });
    
</script>
@endsection