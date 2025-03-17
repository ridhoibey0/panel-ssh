@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
      <div class="row m-0">
        <div class="col-12 p-0">    
          <div class="login-card login-dark">
            <div>
              <div class="login-main"> 
                <div><a class="logo" href="{{ route('login') }}"><h2> VVIP PANEL </h2></a></div>
                <h4>Thanks for signing up!</h4>
                     <p>Before getting started.</p>
                    <form class="theme-form" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success" role="alert">
                            <strong>A new verification link has been sent to the email address you provided during registration, Check Inbox/Spam Email</strong>
                        </div>
                        @endif
                        <p>could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</hp>
                        <div class="form-group">
                            <button class="btn btn-primary btn-block w-100" type="submit">Resend Verification Email</button>
                        </div>
                     </form>
                     <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-block w-100">
                            {{ __('Log Out') }}
                        </button>
                     </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
