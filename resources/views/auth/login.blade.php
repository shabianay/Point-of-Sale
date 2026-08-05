@extends('layouts.app')
@section('content')
<div class="auth-w">
    <div class="auth-c">
        <div class="auth-c-h">{{ __('Login') }}</div>
        <div class="auth-c-b">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="auth-f field">
                    <label class="form-l" for="email">{{ __('Email Address') }}</label>
                    <input id="email" type="email" class="form-i @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="inv" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="auth-f field">
                    <label class="form-l" for="password">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-i @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                        <span class="inv" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="chk">
                    <input class="chk-i" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="chk-l" for="remember">{{ __('Remember Me') }}</label>
                </div>

                <button type="submit" class="btn-p w-full">{{ __('Login') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection