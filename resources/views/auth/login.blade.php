@extends('layouts.app')
@section('title')
    Login
@endsection
@section('content')
<div class="container-fluid p-0">
      <div class="row m-0">
        <div class="col-12 p-0">    
          <div class="login-card login-dark">
            <div>
              <div class="login-main"> 
                <form class="theme-form" action="{{ route('login') }}" method="POST">
                 @csrf
                  <div><a class="logo" href="{{ route('login') }}"><h2> {{env('APP_NAME')}}</h2></a></div>
                  <h4>Sign in to account</h4>
                  <p>Enter your email & password to login</p>
                  <div class="form-group">
                    <label class="col-form-label">Email Address</label>
                    <input class="form-control" type="email" name="email" required="" placeholder="Email or username">
                    @error('email')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Password</label>
                    <div class="form-input position-relative">
                      <input class="form-control" type="password" name="password" required="" placeholder="Password">
                      <div class="show-hide"><span class="show"></span></div>
                      @error('password')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group mb-0">
                    <div class="checkbox p-0">
                      <input id="checkbox1" type="checkbox">
                      <label class="text-muted" for="checkbox1">Remember password</label>
                    </div><a class="link" href="{{ route('password.request') }}">Forgot password?</a>
                    <div class="text-end mt-3">
                      <button class="btn btn-primary btn-block w-100" type="submit">Sign in</button>
                    </div>
                  </div>
                <p class="mt-4 mb-0 text-center">Don't have account?<a class="ms-2" href="{{url('register')}}">Create Account</a></p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
   </div>
@endsection
