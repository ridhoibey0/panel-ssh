@extends('layouts.master')
@section('content')
<style>
.loader-box .loader-15 {
  background: #ffffff;
  position: relative;
  animation: loader-15 1s ease-in-out infinite;
  animation-delay: 0.4s;
  width: 0.25em;
  height: 0.5em;
  margin: 0 0.5em;
}
.loader-box .loader-15:after, .loader-box .loader-15:before {
  content: "";
  position: absolute;
  width: inherit;
  height: inherit;
  background: inherit;
  animation: inherit;
}
.loader-box .loader-15:before {
  right: 0.5em;
  animation-delay: 0.2s;
}
.loader-box .loader-15:after {
  left: 0.5em;
  animation-delay: 0.6s;
}
@keyframes loader-15 {
  0%, 100% {
    box-shadow: 0 0 0 #ffffff, 0 0 0 #ffffff;
  }
  50% {
    box-shadow: 0 -0.25em 0 #ffffff, 0 0.25em 0 #ffffff;
  }
}
.avatar-round {
    width: 50px;
    height: 50px; 
    object-fit: cover;
    border-radius: 50%;
}
</style>
<div class="page-body">
    <div class="container-fluid">
      <div class="page-title">
        <div class="row">
          <div class="col-6">
            <h3>Edit Profile</h3>
          </div>
          <div class="col-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">                                       
                  <svg class="stroke-icon">
                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                  </svg></a></li>
              <li class="breadcrumb-item">Users</li>
              <li class="breadcrumb-item active"> Edit Profile</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="edit-profile">
        <div class="row">
          @include('profile.partials.update-profile-information-form')
          @include('profile.partials.update-password-form')
        </div>
      </div>
    </div>
    <!-- Container-fluid Ends-->
  </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        // Check if the input value is null or empty
        if ($('#api').val() === null || $('#api').val().trim() === '') {
            $('#copy').hide();
            $('#gen').show();
        } else {
            $('#gen').hide();
            $('#copy').show();
        }
    });
    function generate_new() {
        $('#copy').hide();
        $('#gen').show();
    }
    $('#copy').on('click', function() {
        $('#copy').html('Copied!')
        setInterval(function() {
            $('#copy').html('Copy');
        }, 3000);
        var copyText = document.getElementById("api");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
    });
   $('#gen').on('click', function() {
    $('#gen').attr('disabled', true);
    var token = $('meta[name="csrf-token"]').attr('content');
    $('#gen').html('<div class="loader-box"><div class="loader-15"></div></div>');
    $.ajax({
        url: "/settings/profile/apikey",
        type: 'post',
        data: {
            _token: token
        },
        success: function(hasil) {
           var data = hasil;
            if (data.error) {
                alert('Gagal, ulangi beberapa saat');
                $('#gen').attr('disabled', false);
                $('#gen').html('Generate');
            } else {
                // Set the new API key as the input value
                $('#api').val(data.success.api_key);

                // Switch to "Copy" button
                $('#gen').hide();
                $('#copy').show();
            }
        },
        error: function() {
            $('#gen').html('Error');
            alert('Something went wrong!');
        },
        complete: function() {
            // This block will be executed whether the request is successful or not
            $('#gen').html('Generate');
            $('#gen').removeAttr('disabled');
        }
    });
});
</script>
@endpush