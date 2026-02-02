{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="auth-container">
    <div class="auth-wrapper">
        <!-- Left Side - Benefits -->
        <div class="auth-left">
            <div class="auth-brand">
                <div class="brand-logo">
                    <i class="bi bi-rocket-takeoff"></i>
                </div>
                <h1 class="brand-title">Start Earning <span class="gradient-text">Now</span></h1>
                <p class="brand-subtitle">Join our crypto staking platform</p>
            </div>
            
            <div class="benefits-list">
                <div class="benefit">
                    <i class="bi bi-graph-up"></i>
                    <div>
                        <h6>High APY Returns</h6>
                        <p>Earn up to 25% on your crypto</p>
                    </div>
                </div>
                <div class="benefit">
                    <i class="bi bi-shield-check"></i>
                    <div>
                        <h6>Secure Platform</h6>
                        <p>Enterprise-grade security</p>
                    </div>
                </div>
                <div class="benefit">
                    <i class="bi bi-lightning"></i>
                    <div>
                        <h6>Instant Access</h6>
                        <p>Start staking immediately</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Form -->
        <div class="auth-right">
            <div class="auth-form-container">
                <div class="auth-header">
                    <h2 class="auth-title">Create Account</h2>
                    <p class="auth-subtitle">Start your staking journey</p>
                </div>
                
                <form method="POST" action="{{ route('register') }}" class="auth-form" id="registerForm">
                    @csrf
                    
                    <div class="input-group">
                        <label for="name">
                            <i class="bi bi-person"></i> Full Name
                        </label>
                        <input id="name" type="text" 
                               class="form-control @error('name') error @enderror" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="Your full name"
                               required autofocus>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="input-group">
                        <label for="email">
                            <i class="bi bi-envelope"></i> Email Address
                        </label>
                        <input id="email" type="email" 
                               class="form-control @error('email') error @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Your email"
                               required>
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
                                   placeholder="Create password"
                                   required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                        
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="segment" id="seg1"></div>
                                <div class="segment" id="seg2"></div>
                                <div class="segment" id="seg3"></div>
                                <div class="segment" id="seg4"></div>
                            </div>
                            <small id="strength-text">Password strength</small>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <label for="password_confirmation">
                            <i class="bi bi-lock-fill"></i> Confirm Password
                        </label>
                        <div class="password-wrapper">
                            <input id="password_confirmation" type="password" 
                                   class="form-control" 
                                   name="password_confirmation" 
                                   placeholder="Confirm password"
                                   required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="password-match" id="match-check">
                            <i class="bi bi-check-circle"></i>
                            <span>Passwords match</span>
                        </div>
                    </div>
                    
                    <div class="terms">
                        <input type="checkbox" name="terms" id="terms" required>
                        <label for="terms">
                            I agree to the <a href="#">Terms</a> and <a href="#">Privacy Policy</a>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn-primary">
                        <i class="bi bi-rocket-takeoff"></i> Create Account
                    </button>
                    
                    <div class="auth-footer">
                        <p>Already have an account? 
                            <a href="{{ route('login') }}">Sign In</a>
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
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    font-size: 2rem;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.brand-title {
    font-size: 2rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.5rem;
}

.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.brand-subtitle {
    color: #94a3b8;
    font-size: 1rem;
}

.benefits-list {
    flex: 1;
}

.benefit {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 14px;
    margin-bottom: 1rem;
}

.benefit i {
    font-size: 1.5rem;
    color: #667eea;
}

.benefit h6 {
    font-size: 1rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.25rem;
}

.benefit p {
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
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
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

.password-strength {
    margin-top: 0.75rem;
}

.strength-bar {
    display: flex;
    gap: 4px;
    margin-bottom: 0.25rem;
}

.segment {
    flex: 1;
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    transition: all 0.3s;
}

.password-match {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #10b981;
    margin-top: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s;
}

.password-match.show {
    opacity: 1;
}

.terms {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 2rem 0;
    font-size: 0.875rem;
    color: #94a3b8;
}

.terms input {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.terms a {
    color: #667eea;
    text-decoration: none;
}

.terms a:hover {
    text-decoration: underline;
}

.btn-primary {
    width: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
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
    color: #667eea;
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
function togglePassword(fieldId) {
    const input = document.getElementById(fieldId);
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

function checkStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    const segments = ['seg1', 'seg2', 'seg3', 'seg4'];
    const text = document.getElementById('strength-text');
    
    segments.forEach((seg, i) => {
        const el = document.getElementById(seg);
        el.style.background = i < strength ? getColor(strength) : 'rgba(255, 255, 255, 0.1)';
    });
    
    const messages = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    text.textContent = messages[strength];
    text.style.color = getColor(strength);
}

function getColor(strength) {
    const colors = ['#ff3366', '#ff3366', '#f59e0b', '#10b981', '#10b981'];
    return colors[strength] || '#ff3366';
}

function checkMatch() {
    const pass = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirmation').value;
    const match = document.getElementById('match-check');
    
    if (confirm === '') {
        match.classList.remove('show');
        return;
    }
    
    match.classList.add('show');
    if (pass === confirm) {
        match.innerHTML = '<i class="bi bi-check-circle"></i><span>Passwords match</span>';
        match.style.color = '#10b981';
    } else {
        match.innerHTML = '<i class="bi bi-x-circle"></i><span>Passwords don\'t match</span>';
        match.style.color = '#ff3366';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const passInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    
    if (passInput) {
        passInput.addEventListener('input', function() {
            checkStrength(this.value);
            checkMatch();
        });
    }
    
    if (confirmInput) {
        confirmInput.addEventListener('input', checkMatch);
    }
});
</script>
@endsection