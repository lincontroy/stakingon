<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'CryptoStake') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Styles -->
    <style>
        :root {
            /* Modern Dark Theme Variables */
            --primary: #667eea;
            --primary-dark: #764ba2;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ff3366;
            --info: #4facfe;
            
            /* Background Colors */
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: rgba(30, 41, 59, 0.7);
            --bg-glass: rgba(255, 255, 255, 0.05);
            
            /* Text Colors */
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            
            /* Border Colors */
            --border-light: rgba(255, 255, 255, 0.1);
            --border-medium: rgba(255, 255, 255, 0.2);
            --border-dark: rgba(255, 255, 255, 0.05);
            
            /* Glass Morphism */
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --glass-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            
            /* Card Styles */
            --card-bg: rgba(30, 41, 59, 0.7);
            --card-border: rgba(255, 255, 255, 0.1);
            --card-hover: rgba(255, 255, 255, 0.08);
            
            /* Transition */
            --transition-fast: 150ms ease;
            --transition-normal: 300ms ease;
            --transition-slow: 500ms ease;
            
            /* Shadows */
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.2);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.4);
            --shadow-xl: 0 16px 64px rgba(0, 0, 0, 0.5);
            
            /* Gradients */
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            --gradient-danger: linear-gradient(135deg, #ff3366 0%, #ff6b9d 100%);
            --gradient-info: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            color: var(--text-primary);
            min-height: 100vh;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Selection */
        ::selection {
            background: rgba(102, 126, 234, 0.3);
            color: var(--text-primary);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--glass-border);
            border-radius: 4px;
            transition: var(--transition-normal);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--border-medium);
        }

        /* Utilities */
        .gradient-text {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass-effect {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .animate-slide-in {
            animation: slideIn 0.4s ease-out;
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        /* Loading States */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Focus States */
        *:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        *:focus:not(:focus-visible) {
            outline: none;
        }

        *:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* Accessibility */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Print Styles */
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            
            .no-print {
                display: none !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="animate-fade-in">
    <div id="app">
        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Notifications -->
        <div class="notifications-container" id="notifications"></div>

        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay" style="display: none;">
            <div class="loading-spinner">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global configuration
        window.App = {
            csrfToken: '{{ csrf_token() }}',
            baseUrl: '{{ url("/") }}',
            environment: '{{ app()->environment() }}'
        };

        // Loading overlay
        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        // Notifications system
        function showNotification(message, type = 'success', duration = 5000) {
            const container = document.getElementById('notifications');
            const notification = document.createElement('div');
            
            const icons = {
                success: 'bi-check-circle-fill',
                error: 'bi-x-circle-fill',
                warning: 'bi-exclamation-triangle-fill',
                info: 'bi-info-circle-fill'
            };
            
            const colors = {
                success: 'var(--success)',
                error: 'var(--danger)',
                warning: 'var(--warning)',
                info: 'var(--info)'
            };
            
            notification.className = 'notification';
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: var(--card-bg);
                backdrop-filter: blur(20px);
                border: 1px solid var(--glass-border);
                border-left: 4px solid ${colors[type]};
                border-radius: 12px;
                padding: 1rem 1.5rem;
                max-width: 350px;
                z-index: 9999;
                animation: slideIn 0.3s ease-out;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            `;
            
            notification.innerHTML = `
                <i class="bi ${icons[type]}" style="color: ${colors[type]}; font-size: 1.25rem;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 500;">${message}</div>
                </div>
                <button onclick="this.parentElement.remove()" style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
                    <i class="bi bi-x"></i>
                </button>
            `;
            
            container.appendChild(notification);
            
            if (duration > 0) {
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.style.opacity = '0';
                        notification.style.transform = 'translateX(100%)';
                        setTimeout(() => notification.remove(), 300);
                    }
                }, duration);
            }
        }

        // Form validation helper
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            return strength;
        }

        // Copy to clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showNotification('Copied to clipboard!', 'success');
            }).catch(err => {
                console.error('Failed to copy: ', err);
                showNotification('Failed to copy', 'error');
            });
        }

        // Format currency
        function formatCurrency(amount, currency = 'USD') {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: currency,
                minimumFractionDigits: 2,
                maximumFractionDigits: 8
            }).format(amount);
        }

        // Format number
        function formatNumber(number, decimals = 4) {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: decimals
            }).format(number);
        }

        // Debounce function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Throttle function
        function throttle(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        }

        // Handle AJAX errors
        function handleAjaxError(error) {
            console.error('AJAX Error:', error);
            
            let message = 'An error occurred. Please try again.';
            
            if (error.response) {
                // The request was made and the server responded with a status code
                // that falls out of the range of 2xx
                if (error.response.data && error.response.data.message) {
                    message = error.response.data.message;
                } else if (error.response.status === 422) {
                    message = 'Validation error. Please check your input.';
                } else if (error.response.status === 401) {
                    message = 'Please login to continue.';
                    setTimeout(() => window.location.href = '/login', 1500);
                } else if (error.response.status === 403) {
                    message = 'You do not have permission to perform this action.';
                } else if (error.response.status === 404) {
                    message = 'The requested resource was not found.';
                } else if (error.response.status === 500) {
                    message = 'Server error. Please try again later.';
                }
            } else if (error.request) {
                // The request was made but no response was received
                message = 'Network error. Please check your connection.';
            }
            
            showNotification(message, 'error');
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading state to all buttons with type submit
            document.querySelectorAll('button[type="submit"]').forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('form');
                    if (form && form.checkValidity()) {
                        this.classList.add('loading');
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Processing...';
                        
                        form.addEventListener('submit', function() {
                            setTimeout(() => {
                                button.classList.remove('loading');
                                button.innerHTML = originalText;
                            }, 2000);
                        });
                    }
                });
            });

            // Add spinner class
            const style = document.createElement('style');
            style.textContent = `
                .spin {
                    animation: spin 1s linear infinite;
                }
                
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
                
                .loading-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(15, 23, 42, 0.9);
                    backdrop-filter: blur(10px);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 99999;
                }
                
                .loading-spinner {
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                    background: var(--glass-bg);
                    border: 1px solid var(--glass-border);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                
                .spinner-border {
                    width: 30px;
                    height: 30px;
                    border: 3px solid transparent;
                    border-top-color: var(--primary);
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                }
            `;
            document.head.appendChild(style);

            // Add active states to form inputs
            document.querySelectorAll('.form-input-modern').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });
        });

        // Online/Offline detection
        window.addEventListener('online', () => {
            showNotification('You are back online', 'success');
        });

        window.addEventListener('offline', () => {
            showNotification('You are offline. Some features may not work.', 'warning');
        });

        // Service Worker Registration (if needed)
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').then(() => {
                console.log('Service Worker registered');
            }).catch(error => {
                console.log('Service Worker registration failed:', error);
            });
        }
    </script>

    @stack('scripts')
</body>
</html>