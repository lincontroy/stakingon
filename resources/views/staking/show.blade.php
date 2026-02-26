@extends('layouts.app')

@section('title', 'Stake ' . $pool->name)

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="welcome-header p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
                    <div class="d-flex align-items-center gap-4">
                        <a href="{{ route('staking.index') }}" class="btn-back d-flex align-items-center justify-content-center" aria-label="Back to staking">
                            <i class="bi bi-arrow-left fs-4"></i>
                        </a>
                        <div>
                            <h1 class="display-5 fw-semibold mb-2 text-white">
                                Stake {{ $pool->name }}
                            </h1>
                            <p class="text-white-50 mb-0 fs-6">
                                <i class="bi bi-currency-bitcoin me-2"></i>Stake {{ $pool->coin_type }} and earn <span class="text-success fw-medium">{{ number_format($pool->apy, 2) }}% APY</span>
                            </p>
                        </div>
                    </div>
                    @php
                        $usdRates = [
                            'STEEM' => (float) (env('STEEMUSD') ?? env('steemusd')),
                            'HIVE' => (float) (env('HIVEUSD') ?? 0.0674),
                            'USDT' => (float) (env('USDTUSD') ?? 1),
                        ];
                        $poolUsdRate = $usdRates[$pool->coin_type] ?? 0;
                    @endphp
                    <div class="apy-badge-modern d-flex flex-column align-items-center px-4 py-3">
                        <span class="apy-value display-6 fw-bold text-white lh-1">{{ number_format($pool->apy, 2) }}%</span>
                        <small class="small text-white-50 fw-medium">APY</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-5">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-body p-4">
                    <!-- Pool Overview -->
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <div class="pool-overview-card d-flex align-items-center gap-4 p-4 h-100">
                                <div class="pool-icon-large d-flex align-items-center justify-content-center flex-shrink-0">
                                    <i class="bi {{ $pool->coin_icon ?? ($pool->coin_type == 'USDT' ? 'bi-currency-dollar' : 'bi-currency-bitcoin') }} fs-1"></i>
                                </div>
                                <div class="pool-info">
                                    <h3 class="fs-2 fw-semibold text-white mb-1">{{ $pool->coin_type }}</h3>
                                    <p class="text-white-50 small mb-0">{{ $pool->name }} Pool</p>
                                    @if($poolUsdRate > 0)
                                    <div class="pool-rate mt-2">
                                        <small class="text-white-50">
                                            <i class="bi bi-currency-exchange me-1"></i>1 {{ $pool->coin_type }} = ${{ number_format($poolUsdRate, $pool->coin_type == 'USDT' ? 2 : 4) }}
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stats-grid h-100">
                                <div class="stat-item-modern p-3 text-center">
                                    <small class="d-block text-white-50 small fw-medium mb-2">Duration</small>
                                    <strong class="d-block fs-5 fw-semibold text-white">{{ $pool->duration_text }}</strong>
                                </div>
                                <div class="stat-item-modern p-3 text-center">
                                    <small class="d-block text-white-50 small fw-medium mb-2">Min Stake</small>
                                    <strong class="d-block fs-5 fw-semibold text-white">{{ number_format($pool->min_stake, 4) }}</strong>
                                    @if($poolUsdRate > 0)
                                    <small class="text-white-50 d-block">${{ number_format($pool->min_stake * $poolUsdRate, 2) }}</small>
                                    @endif
                                </div>
                                <div class="stat-item-modern p-3 text-center">
                                    <small class="d-block text-white-50 small fw-medium mb-2">Max Stake</small>
                                    <strong class="d-block fs-5 fw-semibold text-white">
                                        @if($pool->max_stake)
                                            {{ number_format($pool->max_stake, 4) }}
                                        @else
                                            Unlimited
                                        @endif
                                    </strong>
                                    @if($poolUsdRate > 0 && $pool->max_stake)
                                    <small class="text-white-50 d-block">${{ number_format($pool->max_stake * $poolUsdRate, 2) }}</small>
                                    @endif
                                </div>
                                <div class="stat-item-modern p-3 text-center">
                                    <small class="d-block text-white-50 small fw-medium mb-2">Total Staked</small>
                                    <strong class="d-block fs-5 fw-semibold text-white">{{ number_format($pool->total_staked, 4) }}</strong>
                                    @if($poolUsdRate > 0)
                                    <small class="text-white-50 d-block">${{ number_format($pool->total_staked * $poolUsdRate, 2) }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Staking Form -->
                    <form action="{{ route('staking.stake', $pool->id) }}" method="POST">
                        @csrf
                        
                        <!-- Amount Input -->
                        <div class="input-modern mb-5">
                            <label for="amount" class="form-label d-flex align-items-center gap-2 small fw-medium text-white-50 mb-2">
                                <i class="bi bi-cash-stack"></i>Stake Amount
                            </label>
                            <div class="input-with-suffix position-relative">
                                <input type="number" 
                                       class="form-control-modern w-100" 
                                       id="amount" 
                                       name="amount" 
                                       step="0.00000001"
                                       min="{{ $pool->min_stake }}"
                                       @if($pool->max_stake) max="{{ $pool->max_stake }}" @endif
                                       placeholder="Enter amount to stake"
                                       required
                                       oninput="updateUsdValue(); calculateRewards();">
                                <span class="input-suffix position-absolute fw-medium">{{ $pool->coin_type }}</span>
                            </div>
                            @if($poolUsdRate > 0)
                            <div class="input-info d-flex align-items-center gap-2 small text-white-50 mt-2" id="usdAmountDisplay">
                                <i class="bi bi-currency-dollar"></i>
                                <span>≈ $0.00 USD</span>
                            </div>
                            @endif
                            <div class="input-info d-flex align-items-center gap-2 small text-white-50 mt-2">
                                <i class="bi bi-info-circle"></i>
                                <span>Available: 
                                    @if($wallet)
                                        <span id="availableBalance" class="text-success fw-medium">{{ number_format($wallet->available_balance, 8) }}</span> {{ $pool->coin_type }}
                                        @if($poolUsdRate > 0)
                                        <span class="text-white-50">(${{ number_format($wallet->available_balance * $poolUsdRate, 2) }})</span>
                                        @endif
                                    @else
                                        <span class="text-danger">No wallet for {{ $pool->coin_type }}. Please create one first.</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        
                        <!-- Reward Calculator -->
                        <div class="reward-calculator-modern mb-5">
                            <div class="calculator-header d-flex align-items-center gap-2 px-4 py-3">
                                <i class="bi bi-calculator-fill fs-5"></i>
                                <h6 class="fs-6 fw-semibold text-white mb-0">Reward Calculation</h6>
                            </div>
                            <div class="calculator-body p-4">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="total-reward-card d-flex justify-content-between align-items-center p-4">
                                            <div>
                                                <small class="d-block text-white-50 small fw-medium mb-2">Returns</small>
                                                <div class="total-reward-value fs-2 fw-bold font-monospace" id="returns">0 {{ $pool->coin_type }}</div>
                                                @if($poolUsdRate > 0)
                                                <small class="d-block text-white-50 small mt-1" id="returnsUsd">$0.00</small>
                                                @endif
                                            </div>
                                            <div class="apy-display text-end">
                                                <small class="d-block text-white-50 small fw-medium mb-2"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>

                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="total-reward-card d-flex justify-content-between align-items-center p-4">
                                            <div>
                                                <small class="d-block text-white-50 small fw-medium mb-2">Total Return</small>
                                                <div class="total-reward-value fs-2 fw-bold font-monospace" id="totalReturn">0 {{ $pool->coin_type }}</div>
                                                @if($poolUsdRate > 0)
                                                <small class="d-block text-white-50 small mt-1" id="totalReturnUsd">$0.00</small>
                                                @endif
                                            </div>
                                            <div class="apy-display text-end">
                                                <small class="d-block text-white-50 small fw-medium mb-2"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        @if($wallet && $wallet->available_balance >= $pool->min_stake)
                        <button type="submit" class="btn btn-primary btn-lg w-100 stake-confirm-btn d-inline-flex align-items-center justify-content-center gap-2 border-0 py-3 fw-medium">
                            <i class="bi bi-lock-fill fs-5"></i>Confirm Stake
                            @if($poolUsdRate > 0)
                            <span class="badge bg-white bg-opacity-25 text-white ms-2" id="stakeTotalUsd">$0.00</span>
                            @endif
                        </button>
                        @elseif(!$wallet)
                        <a href="{{ route('wallet.index') }}" class="btn btn-warning btn-lg w-100 d-inline-flex align-items-center justify-content-center gap-2 border-0 py-3 fw-medium">
                            <i class="bi bi-wallet fs-5"></i>Create {{ $pool->coin_type }} Wallet First
                        </a>
                        @else
                        <button type="button" class="btn btn-secondary btn-lg w-100 d-inline-flex align-items-center justify-content-center gap-2 border-0 py-3 fw-medium" disabled>
                            <i class="bi bi-x-circle fs-5"></i>Insufficient Balance
                        </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Information Card -->
            <div class="card info-card-modern border-0 mb-4">
                <div class="card-header d-flex align-items-center gap-3 bg-transparent border-0 px-4 pt-4 pb-0">
                    <div class="info-icon d-flex align-items-center justify-content-center">
                        <i class="bi bi-info-circle-fill fs-5"></i>
                    </div>
                    <h6 class="fs-6 fw-semibold text-white mb-0">Staking Information</h6>
                </div>
                <div class="card-body p-4">
                    <div class="vstack gap-4">
                        <div class="info-item d-flex gap-3">
                            <div class="info-icon-small d-flex align-items-center justify-content-center flex-shrink-0">
                                <i class="bi bi-clock-history fs-6"></i>
                            </div>
                            <div class="info-content">
                                <small class="d-block text-white-50 small fw-medium mb-1">How it works</small>
                                <p class="small text-white-50 mb-0">Your funds will be locked for {{ $pool->duration_text }}. After this period, you can claim your original stake plus rewards.</p>
                            </div>
                        </div>
                        
                        <div class="info-item d-flex gap-3">
                            <div class="info-icon-small d-flex align-items-center justify-content-center flex-shrink-0">
                                <i class="bi bi-gift-fill fs-6"></i>
                            </div>
                            <div class="info-content">
                                <small class="d-block text-white-50 small fw-medium mb-1">Reward Distribution</small>
                                <p class="small text-white-50 mb-0">Rewards are calculated based on the APY and staking duration. Rewards are paid in the same cryptocurrency.</p>
                            </div>
                        </div>
                        
                        <div class="info-item d-flex gap-3">
                            <div class="info-icon-small d-flex align-items-center justify-content-center flex-shrink-0">
                                <i class="bi bi-shield-lock-fill fs-6"></i>
                            </div>
                            <div class="info-content">
                                <small class="d-block text-white-50 small fw-medium mb-1">Security</small>
                                <p class="small text-white-50 mb-0">Your funds are secured using smart contracts. No one can access your staked funds except you.</p>
                            </div>
                        </div>
                        
                        <div class="info-item d-flex gap-3">
                            <div class="info-icon-small d-flex align-items-center justify-content-center flex-shrink-0">
                                <i class="bi bi-exclamation-triangle-fill fs-6"></i>
                            </div>
                            <div class="info-content">
                                <small class="d-block text-white-50 small fw-medium mb-1">Early Unstaking</small>
                                <p class="small text-white-50 mb-0">Early unstaking is not allowed. Funds remain locked until the staking period completes.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Wallet Card -->
            @if($wallet)
            @php
                $availableUsd = $wallet->available_balance * $poolUsdRate;
                $stakedUsd = $wallet->staking_balance * $poolUsdRate;
                $earnedUsd = $wallet->total_earned * $poolUsdRate;
            @endphp
            <div class="card wallet-card-detail border-0">
                <div class="card-header d-flex align-items-center gap-3 bg-transparent border-0 px-4 pt-4 pb-0">
                    <div class="wallet-icon-small d-flex align-items-center justify-content-center">
                        <i class="bi bi-wallet2 fs-5"></i>
                    </div>
                    <h6 class="fs-6 fw-semibold text-white mb-0">Your {{ $pool->coin_type }} Wallet</h6>
                </div>
                <div class="card-body p-4">
                    <div class="wallet-balance-detail vstack gap-3">
                        <div class="balance-item d-flex justify-content-between align-items-center pb-2">
                            <span class="small text-white-50">Available Balance</span>
                            <div class="text-end">
                                <strong class="small text-white fw-semibold d-block">{{ number_format($wallet->available_balance, 8) }}</strong>
                                @if($poolUsdRate > 0)
                                <small class="text-white-50">${{ number_format($availableUsd, 2) }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="balance-item d-flex justify-content-between align-items-center pb-2">
                            <span class="small text-white-50">Staked Balance</span>
                            <div class="text-end">
                                <strong class="small text-white fw-semibold d-block">{{ number_format($wallet->staking_balance, 8) }}</strong>
                                @if($poolUsdRate > 0)
                                <small class="text-white-50">${{ number_format($stakedUsd, 2) }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="balance-item d-flex justify-content-between align-items-center pb-2">
                            <span class="small text-white-50">Total Earned</span>
                            <div class="text-end">
                                <strong class="small text-success fw-semibold d-block">{{ number_format($wallet->total_earned, 8) }}</strong>
                                @if($poolUsdRate > 0)
                                <small class="text-success">${{ number_format($earnedUsd, 2) }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Modern Typography System */
:root {
    /* Font Families */
    --font-primary: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    --font-mono: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace;
    
    /* Font Sizes */
    --text-xs: 0.75rem;    /* 12px */
    --text-sm: 0.875rem;   /* 14px */
    --text-base: 1rem;     /* 16px */
    --text-lg: 1.125rem;   /* 18px */
    --text-xl: 1.25rem;    /* 20px */
    --text-2xl: 1.5rem;    /* 24px */
    --text-3xl: 1.875rem;  /* 30px */
    --text-4xl: 2.25rem;   /* 36px */
    --text-5xl: 3rem;      /* 48px */
    
    /* Font Weights */
    --weight-light: 300;
    --weight-normal: 400;
    --weight-medium: 500;
    --weight-semibold: 600;
    --weight-bold: 700;
    
    /* Line Heights */
    --leading-tight: 1.2;
    --leading-normal: 1.5;
    --leading-relaxed: 1.75;
    
    /* Letter Spacing */
    --tracking-tight: -0.02em;
    --tracking-normal: 0;
    --tracking-wide: 0.02em;
    --tracking-wider: 0.05em;
    
    /* Colors */
    --text-primary: #ffffff;
    --text-secondary: rgba(255, 255, 255, 0.65);
    --text-tertiary: rgba(255, 255, 255, 0.4);
    
    /* Backgrounds */
    --bg-card: rgba(255, 255, 255, 0.03);
    --bg-card-hover: rgba(255, 255, 255, 0.05);
    --bg-glass: rgba(255, 255, 255, 0.02);
    
    /* Borders */
    --border-light: rgba(255, 255, 255, 0.06);
    --border-medium: rgba(255, 255, 255, 0.1);
    --border-heavy: rgba(255, 255, 255, 0.15);
}

/* Base Typography */
body {
    font-family: var(--font-primary);
    color: var(--text-primary);
    line-height: var(--leading-normal);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* Font Utilities */
.fs-xs { font-size: var(--text-xs); }
.fs-sm { font-size: var(--text-sm); }
.fs-base { font-size: var(--text-base); }
.fs-lg { font-size: var(--text-lg); }
.fs-xl { font-size: var(--text-xl); }
.fs-2xl { font-size: var(--text-2xl); }
.fs-3xl { font-size: var(--text-3xl); }
.fs-4xl { font-size: var(--text-4xl); }
.fs-5xl { font-size: var(--text-5xl); }

.fw-light { font-weight: var(--weight-light); }
.fw-normal { font-weight: var(--weight-normal); }
.fw-medium { font-weight: var(--weight-medium); }
.fw-semibold { font-weight: var(--weight-semibold); }
.fw-bold { font-weight: var(--weight-bold); }

.lh-tight { line-height: var(--leading-tight); }
.lh-normal { line-height: var(--leading-normal); }
.lh-relaxed { line-height: var(--leading-relaxed); }

.tracking-tight { letter-spacing: var(--tracking-tight); }
.tracking-normal { letter-spacing: var(--tracking-normal); }
.tracking-wide { letter-spacing: var(--tracking-wide); }
.tracking-wider { letter-spacing: var(--tracking-wider); }

/* Text Colors */
.text-white { color: var(--text-primary); }
.text-white-50 { color: var(--text-secondary); }
.text-white-25 { color: var(--text-tertiary); }

/* Font Monospace */
.font-monospace {
    font-family: var(--font-mono);
}

/* Welcome Header */
.welcome-header {
    background: var(--bg-glass);
    border: 1px solid var(--border-light);
    border-radius: 24px;
    backdrop-filter: blur(10px);
}

.btn-back {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    color: var(--text-primary);
    transition: all 0.2s ease;
}

.btn-back:hover {
    background: var(--bg-card-hover);
    border-color: var(--border-medium);
    transform: translateY(-1px);
}

/* APY Badge */
.apy-badge-modern {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    border-radius: 20px;
    min-width: 120px;
    box-shadow: 0 10px 30px -10px rgba(16, 185, 129, 0.3);
}

/* Pool Overview */
.pool-overview-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 24px;
    transition: all 0.3s ease;
}

.pool-overview-card:hover {
    background: var(--bg-card-hover);
    border-color: var(--border-medium);
    transform: translateY(-2px);
}

.pool-icon-large {
    width: 80px;
    height: 80px;
    border-radius: 22px;
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    color: white;
    box-shadow: 0 10px 30px -10px rgba(245, 158, 11, 0.4);
}

.pool-rate {
    font-size: var(--text-xs);
    border-top: 1px dashed var(--border-light);
    padding-top: 0.5rem;
    margin-top: 0.5rem;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.stat-item-modern {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 18px;
    transition: all 0.2s ease;
    padding: 1rem;
}

.stat-item-modern:hover {
    background: var(--bg-card-hover);
    border-color: var(--border-medium);
}

/* Form Elements */
.form-control-modern {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 16px;
    padding: 1rem 1.5rem;
    color: var(--text-primary);
    font-size: var(--text-lg);
    font-weight: var(--weight-medium);
    transition: all 0.2s ease;
}

.form-control-modern:focus {
    background: var(--bg-card-hover);
    border-color: #3b82f6;
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-control-modern::placeholder {
    color: var(--text-tertiary);
    font-weight: var(--weight-normal);
}

.input-suffix {
    right: 1.5rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-tertiary);
    font-size: var(--text-lg);
}

.input-info {
    font-size: var(--text-xs);
}

/* Reward Calculator */
.reward-calculator-modern {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 24px;
    overflow: hidden;
}

.calculator-header {
    background: rgba(16, 185, 129, 0.05);
    border-bottom: 1px solid var(--border-light);
    color: #10b981;
}

.reward-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 20px;
    transition: all 0.2s ease;
}

.reward-card:hover {
    background: var(--bg-card-hover);
    border-color: var(--border-medium);
    transform: translateY(-2px);
}

.reward-value {
    font-size: var(--text-2xl);
    letter-spacing: var(--tracking-tight);
}

.total-reward-card {
    background: rgba(16, 185, 129, 0.05);
    border: 1px solid rgba(16, 185, 129, 0.2);
    border-radius: 20px;
}

.total-reward-value {
    color: #10b981;
    font-size: var(--text-2xl);
    letter-spacing: var(--tracking-tight);
}

.apy-value {
    font-size: var(--text-2xl);
}

/* Info Card */
.info-card-modern {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 24px;
    backdrop-filter: blur(10px);
}

.info-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    color: white;
}

.info-icon-small {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    color: var(--text-secondary);
    transition: all 0.2s ease;
}

.info-item:hover .info-icon-small {
    background: var(--bg-card-hover);
    border-color: var(--border-medium);
    color: var(--text-primary);
}

.info-content p {
    line-height: var(--leading-relaxed);
}

/* Wallet Card */
.wallet-card-detail {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 24px;
    backdrop-filter: blur(10px);
}

.wallet-icon-small {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    color: white;
}

.balance-item {
    border-bottom: 1px solid var(--border-light);
}

.balance-item:last-child {
    border-bottom: none;
}

.balance-item strong {
    font-size: var(--text-sm);
    font-feature-settings: "tnum";
}

.balance-item small {
    font-size: var(--text-xs);
}

.wallet-address-detail {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 14px;
}

.address-value {
    font-size: var(--text-xs);
    word-break: break-all;
}

/* Submit Button */
.stake-confirm-btn {
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    border-radius: 16px;
    font-size: var(--text-lg);
    transition: all 0.3s ease;
}

.stake-confirm-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px -10px rgba(59, 130, 246, 0.5);
}

.badge {
    font-size: var(--text-sm);
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-header .d-flex {
        flex-direction: column;
        text-align: center;
    }
    
    .pool-overview-card {
        flex-direction: column;
        text-align: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .reward-card {
        padding: 1rem;
    }
    
    .reward-value,
    .total-reward-value,
    .apy-value {
        font-size: var(--text-xl);
    }
    
    .apy-badge-modern {
        width: 100%;
    }
    
    .form-control-modern {
        font-size: var(--text-base);
    }
    
    .input-suffix {
        font-size: var(--text-base);
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--bg-card);
}

::-webkit-scrollbar-thumb {
    background: var(--border-medium);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--border-heavy);
}
</style>

@push('scripts')
<script>
    const apy = {{ $pool->apy }};
    const durationMinutes = {{ $pool->duration_minutes }};
    const usdRate = {{ $poolUsdRate }};
    const coinType = '{{ $pool->coin_type }}';
    
    function formatNumber(value) {
        if (value === 0) return '0';
        return value.toFixed(8).replace(/\.?0+$/, '');
    }
    
    function updateUsdValue() {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const usdDisplay = document.getElementById('usdAmountDisplay');
        const stakeTotalUsd = document.getElementById('stakeTotalUsd');
        
        if (usdRate > 0) {
            const usdValue = amount * usdRate;
            if (usdDisplay) {
                usdDisplay.innerHTML = `
                    <i class="bi bi-currency-dollar"></i>
                    <span>≈ $${usdValue.toFixed(2)} USD</span>
                `;
            }
            if (stakeTotalUsd) {
                stakeTotalUsd.textContent = `$${usdValue.toFixed(2)}`;
            }
        }
    }
    
    function calculateRewards() {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        
        // Get all the elements
        const returnsEl = document.getElementById('returns');
        const returnsUsdEl = document.getElementById('returnsUsd');
        const totalReturnEl = document.getElementById('totalReturn');
        const totalReturnUsdEl = document.getElementById('totalReturnUsd');
        
        if (amount <= 0 || isNaN(amount)) {
            // Set all to zero if amount is invalid
            if (returnsEl) returnsEl.textContent = '0 {{ $pool->coin_type }}';
            if (totalReturnEl) totalReturnEl.textContent = '0 {{ $pool->coin_type }}';
            
            if (usdRate > 0) {
                if (returnsUsdEl) returnsUsdEl.textContent = '$0.00';
                if (totalReturnUsdEl) totalReturnUsdEl.textContent = '$0.00';
            }
            return;
        }
        
        // Calculate reward (interest only)
        const apyDecimal = apy / 100;
        const reward = amount * apyDecimal;
        
        // Calculate total return (principal + reward)
        const totalReturn = amount + reward;
        
        // Update Returns (interest only)
        if (returnsEl) {
            returnsEl.textContent = formatNumber(reward) + ' {{ $pool->coin_type }}';
        }
        
        // Update Total Return (principal + interest)
        if (totalReturnEl) {
            totalReturnEl.textContent = formatNumber(totalReturn) + ' {{ $pool->coin_type }}';
        }
        
        // Update USD values if rate exists
        if (usdRate > 0) {
            const rewardUsd = reward * usdRate;
            const totalReturnUsd = totalReturn * usdRate;
            
            if (returnsUsdEl) returnsUsdEl.textContent = `$${rewardUsd.toFixed(2)}`;
            if (totalReturnUsdEl) totalReturnUsdEl.textContent = `$${totalReturnUsd.toFixed(2)}`;
        }
    }
    
    const amountInput = document.getElementById('amount');
    if (amountInput) {
        amountInput.addEventListener('input', function() {
            calculateRewards();
            updateUsdValue();
        });
        // Initial calculation
        calculateRewards();
        updateUsdValue();
    }
</script>
@endpush
@endsection