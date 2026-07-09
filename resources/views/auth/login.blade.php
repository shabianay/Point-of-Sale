@extends('layouts.app')
@section('content')
<div class="auth-w">
    <div class="auth-c">
        <div class="auth-c-h">{{ __('Login') }}</div>
        <div class="auth-c-b">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <label class="form-l" for="email">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-i @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="inv" role="alert"><strong>{{ $message }}</strong></span>
                @enderror

                <label class="form-l" for="password">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-i @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="inv" role="alert"><strong>{{ $message }}</strong></span>
                @enderror

                <div class="chk">
                    <input class="chk-i" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="chk-l" for="remember">{{ __('Remember Me') }}</label>
                </div>

                <button type="submit" class="btn-p mb-3 w-full">{{ __('Login') }}</button>

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