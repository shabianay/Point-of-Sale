@extends('layouts.app')
@section('content')
<div class="auth-w">
    <div class="auth-c">
        <div class="auth-c-h">{{ __('Reset Password') }}</div>
        <div class="auth-c-b">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <label class="form-l" for="email">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-i @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="inv" role="alert"><strong>{{ $message }}</strong></span>
                @enderror

                <label class="form-l" for="password">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-i @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')
                    <span class="inv" role="alert"><strong>{{ $message }}</strong></span>
                @enderror

                <label class="form-l" for="password-confirm">{{ __('Confirm Password') }}</label>
                <input id="password-confirm" type="password" class="form-i" name="password_confirmation" required autocomplete="new-password">

                <button type="submit" class="btn-p w-full">{{ __('Reset Password') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection