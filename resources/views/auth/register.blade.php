@extends('layouts.app')

@section('title', 'Create Account - ProFormance')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h1 class="auth-title">Create Account</h1>
        <p class="auth-subtitle">Join ProFitness in seconds</p>
        
        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="error-message">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="validation-error">
                <strong>Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('register.post') }}" id="registerForm">
            @csrf
            
            <div class="name-group">
                <div class="form-group">
                    <label>First Name *</label>
                    <input type="text" 
                           name="first_name" 
                           required 
                           value="{{ old('first_name') }}"
                           placeholder="John"
                           class="form-input @error('first_name') error-border @enderror">
                    @error('first_name')
                        <span class="error-text">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text" 
                           name="last_name" 
                           required 
                           value="{{ old('last_name') }}"
                           placeholder="Doe"
                           class="form-input @error('last_name') error-border @enderror">
                    @error('last_name')
                        <span class="error-text">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" 
                       name="email" 
                       required 
                       value="{{ old('email') }}"
                       placeholder="john.doe@example.com"
                       class="form-input @error('email') error-border @enderror">
                @error('email')
                    <span class="error-text">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Phone Number (Optional)</label>
                <input type="tel" 
                       name="phone_number" 
                       value="{{ old('phone_number') }}"
                       placeholder="1234567890"
                       class="form-input @error('phone_number') error-border @enderror">
                <small class="input-hint">
                    Enter 10-15 digits (optional)
                </small>
                @error('phone_number')
                    <span class="error-text">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Password *</label>
                <input type="password" 
                       name="password" 
                       required 
                       placeholder="At least 8 characters"
                       class="form-input @error('password') error-border @enderror">
                <small class="input-hint">
                    Must be at least 8 characters long
                </small>
                @error('password')
                    <span class="error-text">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Confirm Password *</label>
                <input type="password" 
                       name="password_confirmation" 
                       required 
                       placeholder="Re-enter your password"
                       class="form-input @error('password_confirmation') error-border @enderror">
                @error('password_confirmation')
                    <span class="error-text">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            
            <button type="submit" class="submit-btn" id="submitBtn">
                Create Account
            </button>
            
            <div class="auth-footer">
                <p class="footer-text">Already have an account?</p>
                <a href="{{ route('login') }}" class="footer-link">
                    Sign in here
                </a>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/register.css') }}">
<script src="{{ asset('js/register.js') }}" defer></script>
@endsection