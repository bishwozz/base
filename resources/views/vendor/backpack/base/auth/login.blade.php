@extends(backpack_view('layouts.plain'))
<style>
    body{
        background-image: url("/assets/login-02.png");
        background-repeat: no-repeat;
        background-size: cover;
        
    }
    .btn-signin{
        width: 200px;
        background-color: rgb(0, 153, 255) !important;
        color: #ffffff !important;
        border-radius: 20px !important;
    }
    .text-color{
        color: rgb(0, 153, 255) !important;
    }
    .card input{
        background-color: #dfdede !important;
    }
    ::placeholder{
        color: #797979 !important;
    }
    /* .login-img{
        background-image: url('/assets/login-04.png');
        background-repeat: no-repeat;
        background-size: cover;
    } */
    .login-img img{
        width: 100%;
        object-fit: cover;
    }
    .card-back{
        background-color: #ffffff !important;
        max-width: 40%;
        border-radius: 20px !important;
        box-shadow: 5px 5px 5px 5px #00000020 !important;
    }
</style>
@section('content')
    <div class="row justify-content-center align-items-center card-back mx-auto py-3">
        <div class="col-md-12">
            <div class="py-3">
                <h3 class="text-left mb-1 ml-3 font-weight-bold text-color"> login </h3>
                <div class="card-body">
                    <form class="col-md-12 p-t-10" role="form" method="POST" action="{{ route('backpack.auth.login') }}">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <div>
                                <input type="text" class="form-control{{ $errors->has($username) ? ' is-invalid' : '' }}" name="{{ $username }}" value="{{ old($username) }}" id="{{ $username }}" placeholder="Enter Email">
                                @if ($errors->has($username))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first($username) }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password" placeholder="Enter Password">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> {{ trans('backpack::base.remember_me') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-signin">
                                    Sign In
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
