@extends('layouts.errors.master')
@section('title', 'Permission Dennied')

@section('css')
@endsection

@section('style')
@endsection


@section('content')
<div class="page-wrapper compact-wrapper" id="pageWrapper">
  <!-- error-403 start-->
  <div class="error-wrapper">
    <div class="container"><img class="img-100" src="{{asset('assets/images/other-images/sad.png')}}" alt="">
      <div class="error-heading">
      </div>
      <div class="col-md-8 offset-md-2">
        <p class="sub-content">You Don't Have Permission To Download This File.</p>
      </div>
      <div><a class="btn btn-success-gradien btn-lg" href="https://premiumssh.net">BACK TO HOME PAGE</a></div>
    </div>
  </div>
  <!-- error-403 end-->
</div>
@endsection

@section('script')

@endsection