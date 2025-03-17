@extends('layouts.app')
@section('title')
    Forgot Password
@endsection
@section('content')

<div class="container-fluid p-0">
    <div class="row">
      <div class="col-12">
        <div class="login-card">
          <div class="login-main">
            <form class="theme-form" action="{{ route('password.email') }}" method="post">
              @csrf
              <div><a class="logo" href="{{ route('login') }}"><h2> {{env('APP_NAME')}} </h2></a></div>
              <h4>Forgot Your Password?</h4>
               @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif
                @error('email')
                <div class="alert alert-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
                @enderror
              <p>No problem! Enter your email below and we will send instructions to reset your password.</p>
              <div class="form-group">
                <label class="col-form-label" for="email">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus aria-describedby="emailHelp" placeholder="{{ __('Email Address') }}">
              </div>
              <div class="form-group mb-0">
                <button class="btn btn-primary btn-block w-100" type="submit">{{ __('Send Instructions') }}</button>
              </div>
              <p class="mt-4 mb-0 text-center">Already have an password? <a class="ms-2" href="{{ route('login') }}">Sign in</a>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection