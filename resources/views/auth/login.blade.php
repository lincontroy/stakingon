{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-container">
    <div class="auth-wrapper">
        <!-- Left Side - Welcome -->
        <div class="auth-left">
            <div class="auth-brand">
                <div class="brand-logo">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h1 class="brand-title">Welcome <span class="gradient-text">Back</span></h1>
                <p class="brand-subtitle">Secure crypto staking platform</p>
            </div>
            
            <div class="features">
                <div class="feature">
                    <i class="bi bi-shield-check"></i>
                    <div>
                        <h6>Secure Staking</h6>
                        <p>Your assets are protected</p>
                    </div>
                </div>
                <div class="feature">
                    <i class="bi bi-graph-up"></i>
                    <div>
                        <h6>High Returns</h6>
                        <p>Earn competitive APY</p>
                    </div>
                </div>
                <div class="feature">
                    <i class="bi bi-lightning"></i>
                    <div>
                        <h6>Instant Access</h6>
                        <p>Manage your portfolio</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Form -->
        <div class="auth-right">
            <div class="auth-form-container">
                <div class="auth-header">
                    <h2 class="auth-title">Sign In</h2>
                    <p class="auth-subtitle">Access your account</p>
                </div>
                
                <form method="POST" action="{{ route('login') }}" class="auth-form">
                    @csrf
                    
                    <div class="input-group">
                        <label for="email">
                            <i class="bi bi-envelope"></i> Email Address
                        </label>
                        <input id="email" type="email" 
                               class="form-control @error('email') error @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Your email"
                               required autofocus>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="input-group">
                        <label for="password">
                            <i class="bi bi-lock"></i> Password
                        </label>
                        <div class="password-wrapper">
                            <input id="password" type="password" 
                                   class="form-control @error('password') error @enderror" 
                                   name="password" 
                                   placeholder="Your password"
                                   required>
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-options">
                        <div class="remember">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">Remember me</label>
                        </div>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot">
                            Forgot password?
                        </a>
                        @endif
                    </div>
                    
                    <button type="submit" class="btn-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Sign In
                    </button>
                    
                    <div class="auth-footer">
                        <p>Don't have an account? 
                            <a href="{{ route('register') }}">Create Account</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.auth-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.auth-wrapper {
    max-width: 1000px;
    width: 100%;
    background: rgba(30, 41, 59, 0.7);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    overflow: hidden;
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.auth-left {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(34, 197, 94, 0.1) 100%);
    padding: 3rem 2rem;
    display: flex;
    flex-direction: column;
}

.auth-brand {
    text-align: center;
    margin-bottom: 3rem;
}

.brand-logo {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    font-size: 2rem;
    color: white;
    box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
}

.brand-title {
    font-size: 2rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.5rem;
}

.gradient-text {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.brand-subtitle {
    color: #94a3b8;
    font-size: 1rem;
}

.features {
    flex: 1;
}

.feature {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 14px;
    margin-bottom: 1rem;
}

.feature i {
    font-size: 1.5rem;
    color: #10b981;
}

.feature h6 {
    font-size: 1rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.25rem;
}

.feature p {
    font-size: 0.875rem;
    color: #94a3b8;
    margin: 0;
}

.auth-right {
    padding: 3rem;
}

.auth-form-container {
    max-width: 400px;
    margin: 0 auto;
}

.auth-header {
    margin-bottom: 2rem;
}

.auth-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.5rem;
}

.auth-subtitle {
    color: #94a3b8;
    font-size: 1rem;
}

.input-group {
    margin-bottom: 1.5rem;
}

.input-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #94a3b8;
    margin-bottom: 0.5rem;
}

.input-group label i {
    font-size: 1rem;
}

.form-control {
    width: 100%;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1rem;
    color: white;
    font-size: 1rem;
    transition: all 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
}

.form-control.error {
    border-color: #ff3366;
}

.error-message {
    color: #ff3366;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.password-wrapper {
    position: relative;
}

.toggle-password {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    font-size: 1rem;
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 1.5rem 0;
}

.remember {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #94a3b8;
}

.remember input {
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.forgot {
    font-size: 0.875rem;
    color: #10b981;
    text-decoration: none;
}

.forgot:hover {
    text-decoration: underline;
}

.btn-primary {
    width: 100%;
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    border: none;
    border-radius: 12px;
    padding: 1rem;
    color: white;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
}

.auth-footer {
    text-align: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.auth-footer p {
    color: #94a3b8;
}

.auth-footer a {
    color: #10b981;
    text-decoration: none;
    font-weight: 600;
}

.auth-footer a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .auth-wrapper {
        grid-template-columns: 1fr;
        max-width: 400px;
    }
    
    .auth-left {
        display: none;
    }
    
    .auth-right {
        padding: 2rem;
    }
}
</style>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = input.nextElementSibling.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>
@endsection