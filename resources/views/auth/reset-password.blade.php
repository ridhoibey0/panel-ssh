@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row">
      <div class="col-12">
        <div class="login-card">
          <div>
            <div class="login-main">
               <div><a class="logo" href="{{ route('login') }}"><h2> VVIP PANEL </h2></a></div>
               <form class="theme-form" action="{{ route('password.store') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <h4>Reset Your Password</h4>
                <div class="form-group">
                    <label class="col-form-label" for="email">Email</label>
                    <input type="email" class="form-control " name="email" aria-describedby="emailHelp" placeholder="Email" value="{{ old('email', $request->email) }}">
                    @if($errors->has('email'))
                        <div class="alert alert-danger mt-2">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                  <label class="col-form-label" for="password">New Password</label>
                  <div class="form-input position-relative">
                    <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="Password" required autocomplete="new-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <div class="show-hide">
                      <span class="show"></span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-form-label" for="password-confirm">Repeat New Password</label>
                  <input id="password-confirm" class="form-control " type="password" name="password_confirmation" placeholder="Repeat Password" required autocomplete="new-password">
                  <div class="invalid-feedback"></div>
                </div>
                <div class="form-group mt-5">
                  <button class="btn btn-primary btn-block w-100" type="submit">Reset Password</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
