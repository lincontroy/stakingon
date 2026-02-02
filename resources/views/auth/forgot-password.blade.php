{{-- resources/views/auth/forgot-password.blade.php --}}
@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<div class="auth-container">
    <div class="auth-wrapper">
        <!-- Left Side -->
        <div class="auth-left">
            <div class="auth-brand">
                <div class="brand-logo">
                    <i class="bi bi-key"></i>
                </div>
                <h1 class="brand-title">Reset <span class="gradient-text">Password</span></h1>
                <p class="brand-subtitle">We'll help you get back in</p>
            </div>
            
            <div class="features">
                <div class="feature">
                    <i class="bi bi-shield-check"></i>
                    <div>
                        <h6>Secure Process</h6>
                        <p>Your account stays protected</p>
                    </div>
                </div>
                <div class="feature">
                    <i class="bi bi-envelope"></i>
                    <div>
                        <h6>Email Instructions</h6>
                        <p>Get a reset link in your inbox</p>
                    </div>
                </div>
                <div class="feature">
                    <i class="bi bi-clock"></i>
                    <div>
                        <h6>Quick Recovery</h6>
                        <p>Reset in just a few minutes</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side -->
        <div class="auth-right">
            <div class="form-container">
                <div class="form-header">
                    <h2>Reset Password</h2>
                    <p>Enter your email to receive a reset link</p>
                </div>
                
                <form method="POST" action="{{ route('password.email') }}" class="auth-form">
                    @csrf
                    
                    <div class="input-group">
                        <label for="email">
                            <i class="bi bi-envelope"></i> Email Address
                        </label>
                        <input id="email" type="email" 
                               class="input @error('email') error @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Your email"
                               required autofocus>
                        @error('email')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    @if (session('status'))
                    <div class="alert success">
                        <i class="bi bi-check-circle"></i>
                        {{ session('status') }}
                    </div>
                    @endif
                    
                    <button type="submit" class="btn">
                        <i class="bi bi-send"></i> Send Reset Link
                    </button>
                    
                    <div class="form-footer">
                        <p>Remember your password? 
                            <a href="{{ route('login') }}">Sign In</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.alert {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-radius: 12px;
    padding: 1rem;
    margin: 1.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #10b981;
}

.alert i {
    font-size: 1.25rem;
}

.auth-left {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
}

.brand-logo {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
}

.gradient-text {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.feature i {
    color: #3b82f6;
}

.btn {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.btn:hover {
    box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
}

.form-footer a {
    color: #3b82f6;
}
</style>
@endsection