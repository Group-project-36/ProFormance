@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">

        <h1 class="auth-title">Forgot Password</h1>
        <p class="auth-subtitle">Enter your email and we'll send you a link to reset your password.</p>

        @if(session('success'))
            <div class="success-message">
                <i class="bi bi-check-circle" style="margin-right:6px;"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="error-message">
                <i class="bi bi-exclamation-circle" style="margin-right:6px;"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error-message">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.sendResetLink') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-input"
                       value="{{ old('email') }}" required autofocus
                       placeholder="you@example.com">
            </div>

            <button type="submit" class="submit-btn">
                <span class="btn-text">Send Reset Link</span>
                <span class="btn-loader"><i class="bi bi-arrow-repeat"></i></span>
            </button>
        </form>

        <div class="auth-footer">
            <p class="footer-text">Remembered your password?</p>
            <a href="{{ route('login') }}" class="footer-link">Back to Sign In</a>
        </div>
    </div>
</div>
@endsection
