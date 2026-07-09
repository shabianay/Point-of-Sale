@extends('layouts.app')
@section('content')
<div class="auth-w">
    <div class="auth-c">
        <div class="auth-c-h">{{ __('Confirm Password') }}</div>
        <div class="auth-c-b">
            <p>{{ __('Please confirm your password before continuing.') }}</p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf
                <label class="form-l" for="password">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-i @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="inv" role="alert"><strong>{{ $message }}</strong></span>
                @enderror

                <button type="submit" class="btn-p mb-3 w-full">{{ __('Confirm Password') }}</button>

                @if (Route::has('password.request'))
                    <div class="ta-c">
                        <a class="btn-l" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection