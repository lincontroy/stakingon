@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Welcome Header -->
<div class="dashboard-welcome mb-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-4">
        <div>
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="user-avatar">
                    @php
                        $userName = Auth::user()->name;
                        if (is_array($userName)) {
                            $userName = $userName['name'] ?? $userName[0] ?? 'U';
                        }
                        $firstLetter = strtoupper(substr((string)$userName, 0, 1));
                    @endphp
                    {{ $firstLetter }}
                </div>
                <div>
                    <h1 class="display-6 fw-bold mb-2 text-gradient">
                        Welcome back, {{ $userName }}! 👋
                    </h1>
                    <div class="d-flex align-items-center gap-3">
                        <p class="text-muted mb-0">
                            <i class="bi bi-calendar-check me-2"></i>{{ now()->format('l, F j, Y') }}
                        </p>
                        <div class="status-indicator">
                            <span class="status-dot online"></span>
                            <span class="status-text">Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('wallet.index') }}" class="btn btn-action btn-outline">
                <i class="bi bi-plus-circle me-2"></i>Add Funds
            </a>
            <a href="{{ route('staking.index') }}" class="btn btn-action btn-primary">
                <i class="bi bi-graph-up-arrow me-2"></i>Start Staking
            </a>
        </div>
    </div>
</div>

<!-- Stats Overview -->
<div class="stats-grid mb-5">
    <div class="row g-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-primary">
                <div class="stat-decoration"></div>
                <div class="stat-content">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div class="stat-badge positive">
                            <i class="bi bi-arrow-up"></i> 12.5%
                        </div>
                    </div>
                    <h3 class="stat-title">Total Balance</h3>
                    <h2 class="stat-value">{{ number_format($stats['total_balance'], 8) }}</h2>
                    <p class="stat-description">Across all wallets</p>
                </div>
                <div class="stat-footer">
                    <div class="sparkline" id="balance-sparkline"></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-warning">
                <div class="stat-decoration"></div>
                <div class="stat-content">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon">
                            <i class="bi bi-lock-fill"></i>
                        </div>
                        <div class="stat-badge positive">
                            <i class="bi bi-arrow-up"></i> 8.2%
                        </div>
                    </div>
                    <h3 class="stat-title">Currently Staking</h3>
                    <h2 class="stat-value">{{ number_format($stats['total_staking'], 8) }}</h2>
                    <p class="stat-description">Active stakes</p>
                </div>
                <div class="stat-footer">
                    <div class="sparkline" id="staking-sparkline"></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-success">
                <div class="stat-decoration"></div>
                <div class="stat-content">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon">
                            <i class="bi bi-gift-fill"></i>
                        </div>
                        <div class="stat-badge positive">
                            <i class="bi bi-arrow-up"></i> 24.8%
                        </div>
                    </div>
                    <h3 class="stat-title">Total Earned</h3>
                    <h2 class="stat-value">{{ number_format($stats['total_earned'], 8) }}</h2>
                    <p class="stat-description">All-time rewards</p>
                </div>
                <div class="stat-footer">
                    <div class="sparkline" id="earnings-sparkline"></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-info">
                <div class="stat-decoration"></div>
                <div class="stat-content">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="stat-icon">
                            <i class="bi bi-activity"></i>
                        </div>
                        <div class="stat-badge neutral">
                            <i class="bi bi-dash"></i> 0%
                        </div>
                    </div>
                    <h3 class="stat-title">Active Stakes</h3>
                    <h2 class="stat-value">{{ $stats['active_stakes'] }}</h2>
                    <p class="stat-description">Running now</p>
                </div>
                <div class="stat-footer">
                    <div class="sparkline" id="activity-sparkline"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="dashboard-content">
    <div class="row g-4">
        <!-- Active Stakes Panel -->
        <div class="col-lg-8">
            <div class="dashboard-panel">
                <div class="panel-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="panel-title">
                            <i class="bi bi-lightning-charge-fill"></i>
                            <h3>Active Stakes</h3>
                            <span class="panel-subtitle">Track your ongoing investments</span>
                        </div>
                        <div class="panel-actions">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-filter" data-bs-toggle="dropdown">
                                    <i class="bi bi-funnel"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">All Stakes</a></li>
                                    <li><a class="dropdown-item" href="#">High APY</a></li>
                                    <li><a class="dropdown-item" href="#">Ending Soon</a></li>
                                </ul>
                            </div>
                            <a href="{{ route('staking.index') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg me-1"></i>New Stake
                            </a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    @if($activeStakes->count() > 0)
                        <div class="stakes-container">
                            @foreach($activeStakes as $stake)
                            <div class="stake-card">
                                <div class="stake-header">
                                    <div class="stake-asset">
                                        <div class="asset-icon">
                                            <i class="bi {{ $stake->stakingPool->coin_icon }}"></i>
                                        </div>
                                        <div class="asset-info">
                                            <h5>{{ $stake->stakingPool->name }}</h5>
                                            <span>{{ $stake->stakingPool->coin_type }}</span>
                                        </div>
                                    </div>
                                    <div class="stake-apy">
                                        <span class="apy-badge">{{ $stake->stakingPool->apy }}% APY</span>
                                    </div>
                                </div>
                                <div class="stake-details">
                                    <div class="detail-row">
                                        <div class="detail-item">
                                            <label>Staked Amount</label>
                                            <span class="value">{{ number_format($stake->amount, 4) }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <label>Progress</label>
                                            <div class="progress-wrapper">
                                                <div class="progress-bar" style="width: {{ $stake->progress_percentage }}%">
                                                    <div class="progress-fill"></div>
                                                </div>
                                                <span class="progress-value">{{ round($stake->progress_percentage) }}%</span>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <label>Time Remaining</label>
                                            <span class="value time-remaining">
                                                <i class="bi bi-clock me-1"></i>{{ $stake->remaining_time }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="stake-footer">
                                    <div class="footer-left">
                                        <small class="text-muted">Started {{ $stake->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="footer-right">
                                        <button class="btn btn-sm btn-outline">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                        <button class="btn btn-sm btn-light">
                                            <i class="bi bi-box-arrow-in-right"></i> Claim
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-illustration">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <div class="empty-content">
                                <h4>No Active Stakes</h4>
                                <p>Start earning passive income by staking your crypto</p>
                                <a href="{{ route('staking.index') }}" class="btn btn-primary">
                                    <i class="bi bi-rocket-takeoff me-2"></i>Start Staking Now
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                @if($activeStakes->count() > 0)
                <div class="panel-footer">
                    <a href="{{ route('staking.active') }}" class="btn btn-link">
                        View All Stakes <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Recent Activity Panel -->
        <div class="col-lg-4">
            <div class="dashboard-panel">
                <div class="panel-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="panel-title">
                            <i class="bi bi-clock-history"></i>
                            <h3>Recent Activity</h3>
                            <span class="panel-subtitle">Latest transactions</span>
                        </div>
                        <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-link">
                            View All <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    @if($recentTransactions->count() > 0)
                        <div class="activity-feed">
                            @foreach($recentTransactions as $transaction)
                            <div class="activity-item">
                                <div class="activity-indicator {{ $transaction->type }}"></div>
                                <div class="activity-content">
                                    <div class="activity-header">
                                        <div class="activity-title">
                                            <h6>{{ ucfirst($transaction->type) }}</h6>
                                            <span class="coin-badge">{{ $transaction->coin_type }}</span>
                                        </div>
                                        <div class="activity-amount {{ $transaction->type == 'deposit' ? 'positive' : 'negative' }}">
                                            {{ $transaction->type == 'deposit' ? '+' : '-' }}{{ number_format($transaction->amount, 4) }}
                                        </div>
                                    </div>
                                    <div class="activity-footer">
                                        <span class="activity-time">
                                            <i class="bi bi-clock me-1"></i>{{ $transaction->created_at->diffForHumans() }}
                                        </span>
                                        <span class="status-badge {{ $transaction->status }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state small">
                            <div class="empty-illustration">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <div class="empty-content">
                                <p>No transactions yet</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Wallets Overview -->
    <div class="dashboard-panel mt-4">
        <div class="panel-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="panel-title">
                    <i class="bi bi-wallet-fill"></i>
                    <h3>Your Wallets</h3>
                    <span class="panel-subtitle">Manage your crypto assets</span>
                </div>
                <div class="panel-actions">
                    <a href="{{ route('wallet.index') }}" class="btn btn-sm btn-outline">
                        <i class="bi bi-gear me-1"></i>Manage
                    </a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            @if($wallets->count() > 0)
                <div class="wallets-grid">
                    @foreach($wallets as $wallet)
                    <div class="wallet-card">
                        <div class="wallet-header">
                            <div class="wallet-icon" style="background: linear-gradient(135deg, {{ $wallet->gradient_start ?? '#667eea' }} 0%, {{ $wallet->gradient_end ?? '#764ba2' }} 100%);">
                                <i class="bi {{ $wallet->coinIcon }}"></i>
                            </div>
                            <div class="wallet-actions">
                                <button class="btn btn-icon" title="Send">
                                    <i class="bi bi-arrow-up-right"></i>
                                </button>
                                <button class="btn btn-icon" title="Receive">
                                    <i class="bi bi-arrow-down-left"></i>
                                </button>
                                <button class="btn btn-icon" title="More">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                            </div>
                        </div>
                        <div class="wallet-info">
                            <h5 class="wallet-name">{{ $wallet->coin_type }}</h5>
                            <h2 class="wallet-balance">{{ number_format($wallet->balance, 4) }}</h2>
                            <p class="wallet-address">{{ substr($wallet->address, 0, 8) }}...{{ substr($wallet->address, -6) }}</p>
                        </div>
                        <div class="wallet-stats">
                            <div class="stat">
                                <label>Available</label>
                                <span class="value">{{ number_format($wallet->available_balance, 4) }}</span>
                            </div>
                            <div class="stat-divider"></div>
                            <div class="stat">
                                <label>Staked</label>
                                <span class="value staked">{{ number_format($wallet->staking_balance, 4) }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-illustration">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="empty-content">
                        <h4>No Wallets Yet</h4>
                        <p>Create your first wallet to start managing crypto</p>
                        <a href="{{ route('wallet.index') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Create Wallet
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* CSS Variables */
:root {
    --primary: #667eea;
    --primary-light: rgba(102, 126, 234, 0.1);
    --secondary: #764ba2;
    --success: #43e97b;
    --warning: #fa709a;
    --info: #4facfe;
    --dark: #1a1a2e;
    --light: #f8f9fa;
    --gray: #6c757d;
    --glass-bg: rgba(255, 255, 255, 0.05);
    --glass-border: rgba(255, 255, 255, 0.1);
    --shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
}

/* Welcome Section */
.dashboard-welcome {
    background: linear-gradient(135deg, var(--dark) 0%, #16213e 100%);
    border-radius: 24px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
}

.dashboard-welcome::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, var(--primary-light) 0%, transparent 70%);
    opacity: 0.3;
}

.user-avatar {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    color: white;
    box-shadow: var(--shadow);
}

.text-gradient {
    background: linear-gradient(135deg, #ffffff 0%, #b4b4c8 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.75rem;
    background: rgba(67, 233, 123, 0.1);
    border-radius: 20px;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.status-dot.online {
    background: var(--success);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.btn-action {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 500;
    transition: var(--transition);
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

/* Stat Cards */
.stats-grid {
    position: relative;
}

.stat-card {
    background: var(--glass-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    padding: 1.5rem;
    height: 100%;
    position: relative;
    overflow: hidden;
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-5px);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.stat-decoration {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card:hover .stat-decoration {
    opacity: 1;
}

.stat-card.stat-primary .stat-icon {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-card.stat-warning .stat-icon {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.stat-card.stat-success .stat-icon {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.stat-card.stat-info .stat-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.stat-badge {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.stat-badge.positive {
    background: rgba(67, 233, 123, 0.15);
    color: var(--success);
}

.stat-badge.neutral {
    background: rgba(255, 255, 255, 0.1);
    color: var(--gray);
}

.stat-title {
    font-size: 0.875rem;
    color: var(--gray);
    margin-bottom: 0.5rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.stat-description {
    font-size: 0.813rem;
    color: var(--gray);
    margin: 0;
}

.stat-footer {
    margin-top: 1rem;
}

.sparkline {
    height: 40px;
    background: linear-gradient(90deg, 
        rgba(255, 255, 255, 0.05) 0%, 
        rgba(255, 255, 255, 0.1) 50%, 
        rgba(255, 255, 255, 0.05) 100%);
    border-radius: 8px;
    position: relative;
    overflow: hidden;
}

.sparkline::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, 
        transparent 0%, 
        rgba(255, 255, 255, 0.3) 50%, 
        transparent 100%);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Dashboard Panels */
.dashboard-panel {
    background: var(--glass-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    overflow: hidden;
}

.panel-header {
    padding: 1.5rem 1.5rem 1rem;
    border-bottom: 1px solid var(--glass-border);
}

.panel-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.panel-title i {
    font-size: 1.25rem;
    color: var(--primary);
}

.panel-title h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: white;
    margin: 0;
}

.panel-subtitle {
    font-size: 0.813rem;
    color: var(--gray);
    margin-left: 2rem;
}

.panel-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-filter {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid var(--glass-border);
    background: transparent;
    color: var(--gray);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
}

.btn-filter:hover {
    background: var(--glass-bg);
    color: white;
}

.panel-body {
    padding: 1.5rem;
}

.panel-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--glass-border);
    text-align: center;
}

/* Stake Cards */
.stakes-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stake-card {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.5rem;
    transition: var(--transition);
}

.stake-card:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.2);
}

.stake-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.stake-asset {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.asset-icon {
    width: 48px;
    height: 48px;
    background: var(--glass-bg);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.asset-info h5 {
    font-size: 1rem;
    font-weight: 600;
    color: white;
    margin: 0 0 0.25rem 0;
}

.asset-info span {
    font-size: 0.813rem;
    color: var(--gray);
}

.apy-badge {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.stake-details {
    margin-bottom: 1.5rem;
}

.detail-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-item label {
    font-size: 0.75rem;
    color: var(--gray);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-item .value {
    font-size: 1rem;
    font-weight: 600;
    color: white;
}

.time-remaining {
    display: flex;
    align-items: center;
    color: var(--warning);
}

.progress-wrapper {
    position: relative;
}

.progress-bar {
    height: 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    overflow: hidden;
    position: relative;
}

.progress-fill {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
    border-radius: 4px;
    transition: width 1s ease;
}

.progress-value {
    position: absolute;
    top: -20px;
    right: 0;
    font-size: 0.75rem;
    color: var(--gray);
}

.stake-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.footer-right {
    display: flex;
    gap: 0.5rem;
}

.btn-outline {
    border: 1px solid var(--glass-border);
    background: transparent;
    color: white;
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-size: 0.875rem;
    transition: var(--transition);
}

.btn-outline:hover {
    background: var(--glass-bg);
    border-color: rgba(255, 255, 255, 0.3);
}

/* Activity Feed */
.activity-feed {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 12px;
    transition: var(--transition);
}

.activity-item:hover {
    background: rgba(255, 255, 255, 0.05);
}

.activity-indicator {
    width: 8px;
    border-radius: 4px;
    flex-shrink: 0;
}

.activity-indicator.deposit {
    background: var(--success);
}

.activity-indicator.withdraw {
    background: var(--warning);
}

.activity-indicator.stake {
    background: var(--primary);
}

.activity-content {
    flex: 1;
}

.activity-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.5rem;
}

.activity-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.activity-title h6 {
    font-size: 0.875rem;
    font-weight: 600;
    color: white;
    margin: 0;
}

.coin-badge {
    background: rgba(255, 255, 255, 0.1);
    color: var(--gray);
    padding: 0.125rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
}

.activity-amount {
    font-size: 0.875rem;
    font-weight: 600;
}

.activity-amount.positive {
    color: var(--success);
}

.activity-amount.negative {
    color: var(--warning);
}

.activity-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.activity-time {
    font-size: 0.75rem;
    color: var(--gray);
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.status-badge {
    font-size: 0.75rem;
    padding: 0.125rem 0.5rem;
    border-radius: 12px;
    font-weight: 500;
}

.status-badge.completed {
    background: rgba(67, 233, 123, 0.15);
    color: var(--success);
}

.status-badge.pending {
    background: rgba(254, 225, 64, 0.15);
    color: #fee140;
}

/* Wallets Grid */
.wallets-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.wallet-card {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.5rem;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.wallet-card:hover {
    transform: translateY(-5px);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
}

.wallet-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.wallet-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.wallet-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid var(--glass-border);
    background: transparent;
    color: var(--gray);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
}

.btn-icon:hover {
    background: var(--glass-bg);
    color: white;
    border-color: rgba(255, 255, 255, 0.3);
}

.wallet-info {
    margin-bottom: 1.5rem;
}

.wallet-name {
    font-size: 0.875rem;
    color: var(--gray);
    margin-bottom: 0.5rem;
    font-weight: 600;
    text-transform: uppercase;
}

.wallet-balance {
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.5rem;
}

.wallet-address {
    font-size: 0.75rem;
    color: var(--gray);
    font-family: 'Courier New', monospace;
    margin: 0;
}

.wallet-stats {
    display: flex;
    gap: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--glass-border);
}

.stat {
    flex: 1;
}

.stat label {
    display: block;
    font-size: 0.75rem;
    color: var(--gray);
    margin-bottom: 0.25rem;
}

.stat .value {
    font-size: 0.875rem;
    font-weight: 600;
    color: white;
}

.stat .value.staked {
    color: #fee140;
}

.stat-divider {
    width: 1px;
    background: var(--glass-border);
}

/* Empty States */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-state.small {
    padding: 2rem 1rem;
}

.empty-illustration {
    width: 80px;
    height: 80px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--gray);
    margin-bottom: 1.5rem;
}

.empty-state.small .empty-illustration {
    width: 60px;
    height: 60px;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.empty-content h4 {
    font-size: 1.25rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.5rem;
}

.empty-content p {
    color: var(--gray);
    margin-bottom: 1.5rem;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .detail-row {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 992px) {
    .dashboard-welcome {
        padding: 1.5rem;
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
    
    .wallets-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
}

@media (max-width: 768px) {
    .dashboard-welcome {
        text-align: center;
    }
    
    .user-avatar {
        margin: 0 auto;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        font-size: 1.25rem;
    }
    
    .detail-row {
        grid-template-columns: 1fr;
    }
    
    .wallets-grid {
        grid-template-columns: 1fr;
    }
    
    .stake-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .footer-right {
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .stat-card {
        padding: 1.25rem;
    }
    
    .stat-value {
        font-size: 1.25rem;
    }
    
    .panel-body {
        padding: 1rem;
    }
    
    .wallet-card {
        padding: 1.25rem;
    }
    
    .wallet-balance {
        font-size: 1.5rem;
    }
}
</style>

<script>
// Add subtle animations and interactions
document.addEventListener('DOMContentLoaded', function() {
    // Animate stat cards on scroll
    const statCards = document.querySelectorAll('.stat-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }, { threshold: 0.1 });

    statCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Add click animations to buttons
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function(e) {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });

    // Add hover effects to wallet cards
    document.querySelectorAll('.wallet-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.querySelector('.wallet-icon').style.transform = 'scale(1.1) rotate(5deg)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.querySelector('.wallet-icon').style.transform = 'scale(1) rotate(0deg)';
        });
    });

    // Simulate sparkline animations
    const sparklines = document.querySelectorAll('.sparkline');
    sparklines.forEach(sparkline => {
        setInterval(() => {
            sparkline.style.opacity = '0.5';
            setTimeout(() => {
                sparkline.style.opacity = '1';
            }, 300);
        }, 3000);
    });
});
</script>
@endsection