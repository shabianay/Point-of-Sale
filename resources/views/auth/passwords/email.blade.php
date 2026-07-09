@extends('layouts.app')
@section('content')
<div class="auth-w">
    <div class="auth-c">
        <div class="auth-c-h">{{ __('Reset Password') }}</div>
        <div class="auth-c-b">
            @if (session('status'))
                <div class="al-s" role="alert">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <label class="form-l" for="email">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-i @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="inv" role="alert"><strong>{{ $message }}</strong></span>
                @enderror

                <button type="submit" class="btn-p w-full">{{ __('Send Password Reset Link') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection