@extends('layouts.app')
@section('content')
<div class="auth-w">
    <div class="auth-c">
        <div class="auth-c-h">{{ __('Register') }}</div>
        <div class="auth-c-b">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <label class="form-l" for="name">{{ __('Name') }}</label>
                <input id="name" type="text" class="form-i @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                @error('name')
                    <span class="inv" role="alert"><strong>{{ $message }}</strong></span>
                @enderror

                <label class="form-l" for="email">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-i @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
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

                <button type="submit" class="btn-p mb-3 w-full">{{ __('Register') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection