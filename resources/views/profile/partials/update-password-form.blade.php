<div class="col-xl-8">
    <form class="card" method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')
      <div class="card-header">
        <h4 class="card-title mb-0">{{ __('Update Password') }}</h4>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
      </div>
      <div class="card-body">
          <div class="col-md-12">
            <div class="mb-3">
              <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
              <input class="form-control" type="password" id="current_password" name="current_password" autocomplete="current-password" placeholder="Current Password">
            </div>
          </div>
          <div class="col-md-12">
            <div class="mb-3">
              <label for="password" class="form-label">{{ __('New Password') }}</label>
              <input class="form-control" type="password" id="password" name="password" autocomplete="new-password" placeholder="New Password">
            </div>
          </div>
          <div class="col-md-12">
            <div class="mb-3">
              <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
              <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" placeholder="Confirm Password">
            </div>
          </div>
        <div class="card-footer text-end">
          <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
        </div>
        <div class="mb-3">
        <label class="form-label">
            Api Key <a href="javascript:generate_new()" style="font-style:italic"><small> (Generate new key)</small></a>
        </label>
        <div class="input-group">
            <span class="input-group-text"><i class="fa fa-key"></i></span>
            <input class="form-control" type="text" id="api" value="{{ auth()->user()->api_key ?? '' }}" disabled="">
            <button class="input-group-text btn btn-primary" type="button" id="copy" style="display:none">Copy</button>
            <button class="input-group-text btn btn-primary" type="button" id="gen">Generate</button>
        </div>
        </div>
      </div>
    </form>
  </div>
