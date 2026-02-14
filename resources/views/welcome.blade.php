<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>Secure P2P Crypto Trading Platform | StakingOn</title>
    <meta name="title" content="Secure P2P Crypto Trading Platform | StakingOn">
    <meta name="description" content="Trade Bitcoin, USDT & Ethereum with verified users. Transparent fees and a smooth experience on StakingOn.">
    <meta name="keywords" content="P2P crypto trading, Bitcoin P2P, USDT exchange, secure crypto marketplace, crypto trading platform">
    <meta name="author" content="StakingOn">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;500;600;700;800;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bybit-yellow: #F7A600;
            --bybit-gold: #FFC107;
            --bybit-dark: #0B0E11;
            --bybit-dark-secondary: #1A1D24;
            --bybit-dark-tertiary: #25282F;
            --bybit-accent: #FFD54F;
            --bybit-text: #E0E3E7;
            --bybit-text-secondary: #B1B5C3;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            font-family: 'Exo 2', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            background: var(--bybit-dark);
            color: var(--bybit-text);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Navigation */
        .nav {
            background: rgba(11, 14, 17, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(247, 166, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 72px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--bybit-yellow);
            text-decoration: none;
            letter-spacing: -0.02em;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--bybit-yellow), var(--bybit-gold));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.25rem;
            color: var(--bybit-dark);
            box-shadow: 0 4px 12px rgba(247, 166, 0, 0.3);
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--bybit-text-secondary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s;
            position: relative;
        }

        .nav-links a:hover {
            color: var(--bybit-yellow);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--bybit-yellow);
            transition: width 0.3s;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--bybit-yellow), var(--bybit-gold));
            color: var(--bybit-dark);
            padding: 0.75rem 2rem;
            border-radius: 6px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            box-shadow: 0 4px 16px rgba(247, 166, 0, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(247, 166, 0, 0.4);
        }

        /* Hero Section */
        .hero {
            max-width: 1400px;
            margin: 0 auto;
            padding: 6rem 2rem;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-content h1 {
            font-size: 4.5rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -0.03em;
        }

        .hero-number {
            color: var(--bybit-yellow);
            text-shadow: 0 0 40px rgba(247, 166, 0, 0.3);
        }

        .hero-content p {
            font-size: 1.25rem;
            color: var(--bybit-text-secondary);
            margin-bottom: 2.5rem;
            line-height: 1.7;
        }

        .signup-form {
            display: flex;
            gap: 1rem;
            max-width: 500px;
        }

        .signup-input {
            flex: 1;
            padding: 1rem 1.5rem;
            background: var(--bybit-dark-secondary);
            border: 1px solid rgba(247, 166, 0, 0.2);
            border-radius: 6px;
            color: var(--bybit-text);
            font-size: 1rem;
            transition: all 0.3s;
        }

        .signup-input:focus {
            outline: none;
            border-color: var(--bybit-yellow);
            box-shadow: 0 0 0 3px rgba(247, 166, 0, 0.1);
        }

        .signup-input::placeholder {
            color: var(--bybit-text-secondary);
        }

        .btn-signup {
            background: linear-gradient(135deg, var(--bybit-yellow), var(--bybit-gold));
            color: var(--bybit-dark);
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 16px rgba(247, 166, 0, 0.3);
        }

        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(247, 166, 0, 0.4);
        }

        /* Crypto Ticker */
        .crypto-ticker {
            background: var(--bybit-dark-secondary);
            border: 1px solid rgba(247, 166, 0, 0.15);
            border-radius: 12px;
            padding: 2rem;
        }

        .ticker-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .ticker-header span:first-child {
            font-weight: 700;
            color: var(--bybit-yellow);
        }

        .ticker-header span:last-child {
            color: var(--bybit-text-secondary);
            font-size: 0.9rem;
            cursor: pointer;
            transition: color 0.2s;
        }

        .ticker-header span:last-child:hover {
            color: var(--bybit-yellow);
        }

        .crypto-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(247, 166, 0, 0.05);
        }

        .crypto-row:last-child {
            border-bottom: none;
        }

        .crypto-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .crypto-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--bybit-dark-tertiary), var(--bybit-dark-secondary));
            border: 1px solid rgba(247, 166, 0, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .crypto-details h4 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--bybit-text);
        }

        .crypto-details p {
            font-size: 0.85rem;
            color: var(--bybit-text-secondary);
        }

        .crypto-price {
            text-align: right;
        }

        .crypto-price .price {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--bybit-text);
        }

        .crypto-price .change {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .change.positive {
            color: #26A69A;
        }

        .change.negative {
            color: #EF5350;
        }

        /* Features Section */
        .features {
            background: var(--bybit-dark-secondary);
            padding: 6rem 2rem;
        }

        .features-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-header h2 {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .section-header p {
            font-size: 1.25rem;
            color: var(--bybit-text-secondary);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--bybit-dark);
            border: 1px solid rgba(247, 166, 0, 0.1);
            border-radius: 12px;
            padding: 2.5rem 2rem;
            text-align: center;
            transition: all 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            border-color: rgba(247, 166, 0, 0.3);
            box-shadow: 0 12px 32px rgba(247, 166, 0, 0.15);
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, var(--bybit-yellow), var(--bybit-gold));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: var(--bybit-text-secondary);
            line-height: 1.7;
        }

        /* Stats Section */
        .stats {
            padding: 6rem 2rem;
        }

        .stats-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            text-align: center;
        }

        .stat-value {
            font-size: 3.5rem;
            font-weight: 900;
            color: var(--bybit-yellow);
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .stat-label {
            font-size: 1.1rem;
            color: var(--bybit-text-secondary);
            font-weight: 600;
        }

        /* CTA Section */
        .cta {
            background: linear-gradient(135deg, rgba(247, 166, 0, 0.1), rgba(255, 193, 7, 0.05));
            padding: 6rem 2rem;
            text-align: center;
        }

        .cta-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .cta h2 {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
        }

        .cta p {
            font-size: 1.25rem;
            color: var(--bybit-text-secondary);
            margin-bottom: 2.5rem;
        }

        /* Footer */
        .footer {
            background: var(--bybit-dark-secondary);
            border-top: 1px solid rgba(247, 166, 0, 0.1);
            padding: 4rem 2rem 2rem;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr repeat(3, 1fr);
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-brand h3 {
            font-size: 1.75rem;
            font-weight: 900;
            color: var(--bybit-yellow);
            margin-bottom: 1rem;
        }

        .footer-brand p {
            color: var(--bybit-text-secondary);
            line-height: 1.7;
        }

        .footer-links h4 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--bybit-text);
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: var(--bybit-text-secondary);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--bybit-yellow);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(247, 166, 0, 0.1);
            color: var(--bybit-text-secondary);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-content, .crypto-ticker {
            animation: fadeInUp 0.8s ease-out;
        }

        .crypto-ticker {
            animation-delay: 0.2s;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .hero {
                grid-template-columns: 1fr;
                padding: 4rem 1.5rem;
            }

            .hero-content h1 {
                font-size: 3rem;
            }

            .nav-links {
                display: none;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }

            .section-header h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="nav">
        <div class="nav-container">
            <a href="#" class="logo">
                <div class="logo-icon">S</div>
                <span>stakingon</span>
            </a>
            <ul class="nav-links">
                <li><a href="#how-it-works">How It Works</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="#security">Security</a></li>
                <li><a href="#learn">Learn</a></li>
                <li><a href="#support">Support</a></li>
            </ul>
            <a href="/login" class="btn-primary">
                Login
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>
                <span class="hero-number">289,966,416</span><br>
                USERS<br>
                TRUST US
            </h1>
            <p>Trade Bitcoin, USDT & Ethereum with verified users. Transparent fees and a smooth experience on stakingon.</p>
            <form class="signup-form">
                <input type="email" class="signup-input" placeholder="Email/Phone number">
                <a href="/register" class="btn-signup">Sign Up</a>
            </form>
        </div>

        <div class="crypto-ticker">
            <div class="ticker-header">
                <span>Popular</span>
                <span>More coins →</span>
            </div>

            <div class="crypto-row">
                <div class="crypto-info">
                    <div class="crypto-icon">₿</div>
                    <div class="crypto-details">
                        <h4>BTC</h4>
                        <p>Bitcoin</p>
                    </div>
                </div>
                <div class="crypto-price">
                    <div class="price">$111,795</div>
                    <div class="change positive">+2.10%</div>
                </div>
            </div>

            <div class="crypto-row">
                <div class="crypto-info">
                    <div class="crypto-icon">Ξ</div>
                    <div class="crypto-details">
                        <h4>ETH</h4>
                        <p>Ethereum</p>
                    </div>
                </div>
                <div class="crypto-price">
                    <div class="price">$4,112.03</div>
                    <div class="change positive">+2.47%</div>
                </div>
            </div>

            <div class="crypto-row">
                <div class="crypto-info">
                    <div class="crypto-icon">₮</div>
                    <div class="crypto-details">
                        <h4>USDT</h4>
                        <p>Tether USD</p>
                    </div>
                </div>
                <div class="crypto-price">
                    <div class="price">$1.00</div>
                    <div class="change negative">-0.01%</div>
                </div>
            </div>

            <div class="crypto-row">
                <div class="crypto-info">
                    <div class="crypto-icon">Ł</div>
                    <div class="crypto-details">
                        <h4>LTC</h4>
                        <p>Litecoin</p>
                    </div>
                </div>
                <div class="crypto-price">
                    <div class="price">$106.10</div>
                    <div class="change positive">+1.87%</div>
                </div>
            </div>

            <div class="crypto-row">
                <div class="crypto-info">
                    <div class="crypto-icon">◎</div>
                    <div class="crypto-details">
                        <h4>SOL</h4>
                        <p>Solana</p>
                    </div>
                </div>
                <div class="crypto-price">
                    <div class="price">$209.01</div>
                    <div class="change positive">+3.50%</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="features-container">
            <div class="section-header">
                <h2>Why Choose stakingon?</h2>
                <p>Built for security, designed for simplicity</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🛡️</div>
                    <h3>Bank-Level Security</h3>
                    <p>2FA, withdrawal allowlists, and device alerts protect your account</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Instant Settlements</h3>
                    <p>Fast escrow system for quick and secure transactions</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">✓</div>
                    <h3>Verified Users</h3>
                    <p>Trade with confidence knowing all users are verified</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🌐</div>
                    <h3>Multiple Cryptocurrencies</h3>
                    <p>Trade BTC, USDT, ETH, LTC, and SOL on one platform</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-container">
            <div class="section-header">
                <h2>Trusted by Thousands</h2>
                <p>Join a growing community of secure P2P traders</p>
            </div>

            <div class="stats-grid">
                <div>
                    <div class="stat-value">289M+</div>
                    <div class="stat-label">Users Trust Us</div>
                </div>
                <div>
                    <div class="stat-value">$50M+</div>
                    <div class="stat-label">Trading Volume</div>
                </div>
                <div>
                    <div class="stat-value">99.9%</div>
                    <div class="stat-label">Uptime</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-container">
            <h2>Ready to Start Trading?</h2>
            <p>Join thousands of traders on stakingon today</p>
            <a href="/login" class="btn-primary" style="font-size: 1.1rem; padding: 1rem 3rem;">
                Get Started Now
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <h3>stakingon</h3>
                    <p>Secure P2P cryptocurrency trading platform</p>
                </div>

                <div class="footer-links">
                    <h4>Platform</h4>
                    <ul>
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="#pricing">Pricing</a></li>
                        <li><a href="#security">Security</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="#learn">Learn</a></li>
                        <li><a href="#support">Support</a></li>
                        <li><a href="#about">About</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="#terms">Terms of Service</a></li>
                        <li><a href="#privacy">Privacy Policy</a></li>
                        <li><a href="#risk">Risk Disclosure</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© 2026 stakingon. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>