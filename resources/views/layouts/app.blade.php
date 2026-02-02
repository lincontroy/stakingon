<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Crypto Staking Platform')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --accent-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --purple-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            
            --dark-bg: #0f0f1e;
            --darker-bg: #08080f;
            --card-bg: rgba(25, 25, 45, 0.6);
            --card-hover: rgba(35, 35, 60, 0.8);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            
            --text-primary: #ffffff;
            --text-secondary: #b4b4c8;
            --text-muted: #6b6b84;
            
            --success: #00ff88;
            --danger: #ff3366;
            --warning: #ffaa00;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: var(--dark-bg);
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(72, 149, 239, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(168, 85, 247, 0.08) 0%, transparent 50%);
            background-attachment: fixed;
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-weight: 400;
            @auth padding-bottom: 90px; @endauth
            overflow-x: hidden;
        }
        
        /* Animated background particles */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(2px 2px at 20% 30%, rgba(255, 255, 255, 0.15), transparent),
                radial-gradient(2px 2px at 60% 70%, rgba(255, 255, 255, 0.1), transparent),
                radial-gradient(1px 1px at 50% 50%, rgba(255, 255, 255, 0.1), transparent),
                radial-gradient(1px 1px at 80% 10%, rgba(255, 255, 255, 0.15), transparent);
            background-size: 200% 200%;
            animation: stars 20s linear infinite;
            pointer-events: none;
            z-index: 0;
        }
        
        @keyframes stars {
            0% { background-position: 0% 0%; }
            100% { background-position: 100% 100%; }
        }
        
        /* Top Navbar */
        .navbar {
            background: var(--card-bg) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transition: transform 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
        }
        
        .navbar-brand i {
            background: var(--success-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav-link.dropdown-toggle {
            color: var(--text-secondary);
            padding: 0.5rem 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: var(--glass-bg);
            border: 1px solid transparent;
        }
        
        .nav-link.dropdown-toggle:hover {
            background: var(--card-hover);
            border-color: var(--glass-border);
            color: var(--text-primary);
        }
        
        .dropdown-menu {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }
        
        .dropdown-item {
            color: var(--text-secondary);
            border-radius: 8px;
            padding: 0.7rem 1rem;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }
        
        .dropdown-item:hover {
            background: var(--glass-bg);
            color: var(--text-primary);
            border-color: var(--glass-border);
        }
        
        /* Bottom Navigation */
        .navbar-bottom {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border-top: 1px solid var(--glass-border);
            z-index: 1000;
            padding: 0.75rem 0;
            box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.2);
        }
        
        .navbar-bottom .nav-link {
            color: var(--text-muted);
            padding: 0.75rem 1rem;
            border-radius: 14px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
            text-decoration: none;
        }
        
        .navbar-bottom .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%) scaleX(0);
            width: 80%;
            height: 3px;
            background: var(--success-gradient);
            border-radius: 0 0 10px 10px;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .navbar-bottom .nav-link.active::before {
            transform: translateX(-50%) scaleX(1);
        }
        
        .navbar-bottom .nav-link:hover,
        .navbar-bottom .nav-link.active {
            color: var(--text-primary);
            background: var(--glass-bg);
            transform: translateY(-2px);
        }
        
        .nav-icon {
            font-size: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .navbar-bottom .nav-link.active .nav-icon {
            background: var(--success-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transform: scale(1.1);
        }
        
        .nav-text {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        
        /* Cards */
        .card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            margin-bottom: 1.5rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary-gradient);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }
        
        .card:hover {
            background: var(--card-hover);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-4px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .card:hover::before {
            transform: scaleX(1);
        }
        
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--glass-border);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Buttons */
        .btn {
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.875rem;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .btn:hover::before {
            transform: translateX(0);
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary:hover {
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: var(--success-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 242, 254, 0.3);
        }
        
        .btn-success:hover {
            box-shadow: 0 8px 25px rgba(0, 242, 254, 0.5);
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background: var(--secondary-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);
        }
        
        .btn-danger:hover {
            box-shadow: 0 8px 25px rgba(245, 87, 108, 0.5);
            transform: translateY(-2px);
        }
        
        .btn-outline-primary {
            border: 2px solid;
            border-image: var(--primary-gradient) 1;
            background: transparent;
            color: var(--text-primary);
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-gradient);
            color: white;
            border-image: none;
        }
        
        /* Alerts */
        .alert {
            border-radius: 16px;
            border: 1px solid;
            backdrop-filter: blur(10px);
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: rgba(0, 255, 136, 0.1);
            border-color: var(--success);
            color: var(--success);
        }
        
        .alert-danger {
            background: rgba(255, 51, 102, 0.1);
            border-color: var(--danger);
            color: var(--danger);
        }
        
        .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.7;
        }
        
        .btn-close:hover {
            opacity: 1;
        }
        
        /* Forms */
        .form-control, .form-select {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: var(--text-primary);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            background: var(--card-bg);
            border-color: rgba(102, 126, 234, 0.5);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }
        
        .form-control::placeholder {
            color: var(--text-muted);
        }
        
        .form-label {
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        /* Main content container */
        .container {
            position: relative;
            z-index: 1;
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--darker-bg);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--glass-border);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        /* Stat cards & badges */
        .stat-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            pointer-events: none;
        }
        
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        
        .badge-success {
            background: rgba(0, 255, 136, 0.2);
            color: var(--success);
        }
        
        .badge-danger {
            background: rgba(255, 51, 102, 0.2);
            color: var(--danger);
        }
        
        .badge-warning {
            background: rgba(255, 170, 0, 0.2);
            color: var(--warning);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.25rem;
            }
            
            .navbar-bottom .nav-link {
                padding: 0.5rem 0.5rem;
            }
            
            .nav-icon {
                font-size: 1.25rem;
            }
            
            .nav-text {
                font-size: 0.65rem;
            }
        }
        
        /* Loading animation */
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }
        
        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    @auth
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="bi bi-coin me-2"></i>StakingOn
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-2"></i>{{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>

    <!-- Bottom Navigation (only for authenticated users) -->
    @auth
    <nav class="navbar-bottom">
        <div class="container">
            <div class="d-flex justify-content-around align-items-center">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door-fill nav-icon"></i>
                    <span class="nav-text">Home</span>
                </a>
                
                <a href="{{ route('staking.index') }}" class="nav-link {{ request()->routeIs('staking.*') ? 'active' : '' }}">
                    <i class="bi bi-graph-up-arrow nav-icon"></i>
                    <span class="nav-text">Stake</span>
                </a>
                
                <a href="{{ route('wallet.index') }}" class="nav-link {{ request()->routeIs('wallet.*') ? 'active' : '' }}">
                    <i class="bi bi-wallet2 nav-icon"></i>
                    <span class="nav-text">Wallet</span>
                </a>
                
                <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history nav-icon"></i>
                    <span class="nav-text">History</span>
                </a>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>