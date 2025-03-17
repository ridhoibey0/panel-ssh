<div class="col-xl-4">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0">{{ __('Profile Information') }}</h4>
        <p class="mt-1 text-sm text-gray-600">
          {{ __("Update your account's profile information and email address.") }}
      </p>
      @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
      @endif
      @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
      @endif
      </div>
      <div class="card-body">
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
          @csrf
        </form>
        <form method="post" action="{{ route('profile.update') }}">
          @csrf
          @method('patch')
          <div class="row mb-2">
            <div class="profile-title">
              <div class="media">
                @if(auth()->user()->google_id && auth()->user()->avatar)
                    <img class="avatar-round" alt="User Avatar" src="{{ auth()->user()->avatar }}">
                @else
                    <img class="avatar-round" alt="Default Avatar" src="{{ asset('assets/images/user/7.jpg') }}">
                @endif
                <div class="media-body">
                  <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                  <p>{{ Auth::user()->roles->pluck('name')->implode(', ') }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input class="form-control" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input class="form-control" id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" required autofocus autocomplete="phone">
            @if (session('number'))
              <span class="text-danger">{{ Session::get('number') }}</span>
            @endif
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email-Address</label>
            <input class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="{{ old('email', $user->email) }}" required autocomplete="email" disabled>
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                  <div>
                      <p class="text-sm mt-2 text-gray-800">
                          {{ __('Your email address is unverified.') }}
  
                          <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
          <div class="form-footer">
            {{-- @if (session('status') === 'profile-updated') --}}
              <button type="submit" class="btn btn-primary btn-block">Save</button>
            {{-- @endif --}}
          </div>
        </form>
      </div>
    </div>
  </div>