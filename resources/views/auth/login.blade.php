@extends('layouts.app')

@section('title', 'Sign In - ProFitness')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1 class="auth-title">Sign In</h1>
        <p class="auth-subtitle">Enter your details to access your account</p>
        
        @if($errors->any())
            <div class="error-message">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        
        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" 
                       name="email" 
                       required 
                       value="{{ old('email') }}"
                       placeholder="your.email@example.com"
                       class="form-input">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" 
                       name="password" 
                       required 
                       placeholder="Enter your password"
                       class="form-input">
            </div>
            
            <button type="submit" class="submit-btn">
                Sign In
            </button>
            
            <div class="auth-footer">
                <p class="footer-text">Don't have an account?</p>
                <a href="{{ route('register') }}" class="footer-link">
                    Create an account
                </a>
            </div>
        </form>
    </div>
</div>
@endsection