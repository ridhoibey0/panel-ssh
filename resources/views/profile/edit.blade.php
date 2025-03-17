@extends('layouts.member')

@section('content')
    <!--begin::details View-->
    <div class="page-content">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Account Profile</h3>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/member') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Profile</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <section class="section">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <div class="avatar avatar-2xl">
                                        <img class="avatar-round" alt="Default Avatar"
                                            src="{{ asset('assets/images/user/7.jpg') }}">
                                    </div>

                                    <h5 class="mt-3">{{ Auth::user()->name }}</h5>
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Api Key <a href="javascript:generate_new()" style="font-style:italic"><small>
                                                    (Generate new key)</small></a>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-key"></i></span>
                                            <input class="form-control" type="text" id="api"
                                                value="{{ auth()->user()->api_key ?? '' }}" disabled="">
                                            <button class="input-group-text btn btn-primary" type="button" id="copy"
                                                style="display:none">Copy</button>
                                            <button class="input-group-text btn btn-primary" type="button"
                                                id="gen">Generate</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                @if (session('status'))
                                    <div class="alert alert-success">{{ session('status') }}</div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('profile.update') }}" method="POST">
                                    @csrf
                                    @method('patch')
                                    <div class="form-group">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Your Name" value="{{ old('name', $user->name) }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email-Address</label>
                                        <input class="form-control" id="email" name="email"
                                            value="{{ old('email', $user->email) }}"
                                            placeholder="{{ old('email', $user->email) }}" required autocomplete="email"
                                            disabled>
                                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                            <div>
                                                <p class="text-sm mt-2 text-gray-800">
                                                    {{ __('Your email address is unverified.') }}

                                                    <button form="send-verification"
                                                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        {{ __('Click here to re-send the verification email.') }}
                                                    </button>
                                                </p>

                                                @if (session('status') === 'verification-link-sent')
                                                    <p class="mt-2 font-medium text-sm text-green-600">
                                                        {{ __('A new verification link has been sent to your email address.') }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input class="form-control" id="phone" name="phone" type="text"
                                            value="{{ old('phone', $user->phone) }}" required autofocus
                                            autocomplete="phone">
                                        @if (session('number'))
                                            <span class="text-danger">{{ Session::get('number') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="form-label">New Password</label>
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="Enter new password" value="">
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password" class="form-label">Confirm Password</label>
                                        <input type="password" name="confirm_password" id="confirm_password"
                                            class="form-control" placeholder="Enter confirm password" value="">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!--end::details View-->
@endsection
@push('addon-script')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
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