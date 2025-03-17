@extends('layouts.app')
@section('title')
    Register
@endsection
@section('content')
<div class="container-fluid p-0">
      <div class="row m-0">
        <div class="col-12 p-0">    
          <div class="login-card login-dark">
            <div>
              <div class="login-main"> 
                <form class="theme-form" action="{{ route('register') }}" method="POST">
                 @csrf
                  <div><a class="logo" href="{{ route('register') }}"><h2> {{env('APP_NAME')}}</h2></a></div>
                  <h4>Sign in to account</h4>
                  <p>Enter your email & password to login</p>
                  <div class="form-group">
                    <label class="col-form-label">Nama</label>
                    <input class="form-control" type="text" name="name" required="" placeholder="Nama lengkap">
                    @error('name')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Email Address</label>
                    <input class="form-control" type="email" name="email" required="" placeholder="Email">
                    @error('email')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">No Hp</label>
                    <input class="form-control" type="number" name="phone" required="" placeholder="No hp">
                    @error('phone')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Password</label>
                    <div class="form-input position-relative">
                      <input class="form-control" type="password" name="password" required="" placeholder="Password">
                      @error('password')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Konfirmasi Password</label>
                    <div class="form-input position-relative">
                      <input class="form-control" type="password" name="password_confirmation" required="" placeholder="Konfirmasi Password">
                      @error('password')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group mb-0">
                    <div class="text-end mt-3">
                      <button class="btn btn-primary btn-block w-100" type="submit">Sign in</button>
                    </div>
                  </div>
                <p class="mt-4 mb-0 text-center">Sudah Punya akun?<a class="ms-2" href="#">Login disini</a></p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
   </div>
@endsection
