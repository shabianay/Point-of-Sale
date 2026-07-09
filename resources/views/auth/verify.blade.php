@extends('layouts.app')
@section('content')
<div class="auth-w">
    <div class="auth-c">
        <div class="auth-c-h">{{ __('Verify Your Email Address') }}</div>
        <div class="auth-c-b">
            @if (session('resent'))
                <div class="al-s" role="alert">{{ __('A fresh verification link has been sent to your email address.') }}</div>
            @endif

            <p>{{ __('Before proceeding, please check your email for a verification link.') }}
            {{ __('If you did not receive the email') }},</p>

            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn-l">{{ __('click here to request another') }}</button>.
            </form>
        </div>
    </div>
</div>
@endsection