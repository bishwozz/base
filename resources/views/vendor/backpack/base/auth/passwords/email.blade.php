@extends(backpack_view('layouts.plain'))

<!-- Main Content -->
@section('content')
<div class="login-container py-5">
    <div class="d-flex justify-content-center mb-2 mr-5">
      <img src="{{url('img/login-logo.png')}}" alt="government logo" class="gov-logo">
    </div>
    <div class="login-panel mx-auto">
        <h3 class="text-center my-4 text-white">पासवर्ड रिसेट गर्नु होस्</h3>
            <div class="nav-steps-wrapper">
                <ul class="nav nav-tabs">
                  <li class="nav-item active"><a class="nav-link active" href="#tab_1" data-toggle="tab"><strong>स्टेप १.</strong> इमेल पुष्टी गर्नुहोस</a></li>
                  <li class="nav-item"><a class="nav-link disabled text-muted"><strong>स्टेप २</strong> नया पासवर्ड राख्नुहोस</a></li>
                </ul>
            </div>
            <div class="nav-tabs-custom">
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                    @if (session('status'))
                        <div class="alert alert-success mt-3">
                            {{ session('status') }}
                        </div>
                    @else
                    <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('backpack.auth.password.email') }}">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="control-label" for="email">{{ trans('backpack::base.email_address') }}</label>

                            <div>
                                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div>
                                <button type="submit" class="btn btn-block btn-primary">
                                    पासवर्ड रिसेट लिंक पठाउनु होस्
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif
                    <div class="clearfix"></div>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div>

              <div class="text-center mt-4">
                <a class="text-white" style="text-decoration: none;" href="{{ route('backpack.auth.login') }}">लग इन</a>
            </div>
    </div>
@endsection
