@extends('layouts.app')

@section('title', 'Stake ' . $pool->name)

@section('content')
<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('staking.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="display-6 fw-bold mb-2" style="background: linear-gradient(135deg, #ffffff 0%, #b4b4c8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            Stake {{ $pool->name }}
                        </h1>
                        <p class="text-muted mb-0">
                            <i class="bi bi-currency-bitcoin me-2"></i>Stake {{ $pool->coin_type }} and earn {{ number_format($pool->apy, 2) }}% APY
                        </p>
                    </div>
                </div>
                <div class="apy-badge-modern">
                    <span class="apy-value">{{ number_format($pool->apy, 2) }}%</span>
                    <small>APY</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <!-- Pool Overview -->
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="pool-overview-card">
                            <div class="pool-icon-large">
                                <i class="bi {{ $pool->coin_icon ?? 'bi-currency-bitcoin' }}"></i>
                            </div>
                            <div class="pool-info">
                                <h3 class="mb-1">{{ $pool->coin_type }}</h3>
                                <p class="text-muted mb-0">{{ $pool->name }} Pool</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stats-grid">
                            <div class="stat-item-modern">
                                <small class="text-muted">Duration</small>
                                <strong class="fs-5">{{ $pool->duration_text }}</strong>
                            </div>
                            <div class="stat-item-modern">
                                <small class="text-muted">Min Stake</small>
                                <strong class="fs-5">{{ number_format($pool->min_stake, 4) }}</strong>
                            </div>
                            <div class="stat-item-modern">
                                <small class="text-muted">Max Stake</small>
                                <strong class="fs-5">
                                    @if($pool->max_stake)
                                        {{ number_format($pool->max_stake, 4) }}
                                    @else
                                        Unlimited
                                    @endif
                                </strong>
                            </div>
                            <div class="stat-item-modern">
                                <small class="text-muted">Total Staked</small>
                                <strong class="fs-5">{{ number_format($pool->total_staked, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Staking Form -->
                <form action="{{ route('staking.stake', $pool->id) }}" method="POST">
                    @csrf
                    
                    <!-- Amount Input -->
                    <div class="input-modern mb-5">
                        <label for="amount" class="form-label">
                            <i class="bi bi-cash-stack me-2"></i>Stake Amount
                        </label>
                        <div class="input-with-suffix">
                            <input type="number" 
                                   class="form-control-modern" 
                                   id="amount" 
                                   name="amount" 
                                   step="0.00000001"
                                   min="{{ $pool->min_stake }}"
                                   @if($pool->max_stake) max="{{ $pool->max_stake }}" @endif
                                   placeholder="Enter amount to stake"
                                   required>
                            <span class="input-suffix">{{ $pool->coin_type }}</span>
                        </div>
                        <div class="input-info">
                            <i class="bi bi-info-circle me-1"></i>
                            Available: 
                            @if($wallet)
                                <span id="availableBalance" class="text-success">{{ number_format($wallet->available_balance, 8) }}</span> {{ $pool->coin_type }}
                            @else
                                <span class="text-danger">No wallet for {{ $pool->coin_type }}. Please create one first.</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Reward Calculator -->
                    <div class="reward-calculator-modern mb-5">
                        <div class="calculator-header">
                            <i class="bi bi-calculator-fill me-2"></i>
                            <h6 class="mb-0">Reward Calculation</h6>
                        </div>
                        <div class="calculator-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="reward-card">
                                        <small class="text-muted">Daily Reward</small>
                                        <div class="reward-value" id="dailyReward">0</div>
                                        <small class="text-muted">{{ $pool->coin_type }}/day</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="reward-card">
                                        <small class="text-muted">Duration Reward</small>
                                        <div class="reward-value" id="durationReward">0</div>
                                        <small class="text-muted">{{ $pool->coin_type }} total</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="total-reward-card">
                                        <div>
                                            <small class="text-muted">Total Return</small>
                                            <div class="total-reward-value" id="totalReward">0 {{ $pool->coin_type }}</div>
                                        </div>
                                        <div class="apy-display">
                                            <small class="text-muted">APY</small>
                                            <div class="apy-value">{{ number_format($pool->apy, 2) }}%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    @if($wallet && $wallet->available_balance >= $pool->min_stake)
                    <button type="submit" class="btn btn-primary btn-lg w-100 stake-confirm-btn">
                        <i class="bi bi-lock-fill me-2"></i>Confirm Stake
                    </button>
                    @elseif(!$wallet)
                    <a href="{{ route('wallet.index') }}" class="btn btn-warning btn-lg w-100">
                        <i class="bi bi-wallet me-2"></i>Create {{ $pool->coin_type }} Wallet First
                    </a>
                    @else
                    <button type="button" class="btn btn-secondary btn-lg w-100" disabled>
                        <i class="bi bi-x-circle me-2"></i>Insufficient Balance
                    </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Information Card -->
        <div class="card info-card-modern">
            <div class="card-header d-flex align-items-center">
                <div class="info-icon">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
                <h6 class="mb-0 ms-2">Staking Information</h6>
            </div>
            <div class="card-body">
                <div class="info-item">
                    <div class="info-icon-small">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="info-content">
                        <small class="text-muted">How it works</small>
                        <p class="mb-0">Your funds will be locked for {{ $pool->duration_text }}. After this period, you can claim your original stake plus rewards.</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon-small">
                        <i class="bi bi-gift-fill"></i>
                    </div>
                    <div class="info-content">
                        <small class="text-muted">Reward Distribution</small>
                        <p class="mb-0">Rewards are calculated based on the APY and staking duration. Rewards are paid in the same cryptocurrency.</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon-small">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div class="info-content">
                        <small class="text-muted">Security</small>
                        <p class="mb-0">Your funds are secured using smart contracts. No one can access your staked funds except you.</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon-small">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="info-content">
                        <small class="text-muted">Early Unstaking</small>
                        <p class="mb-0">Early unstaking is not allowed. Funds remain locked until the staking period completes.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Wallet Card -->
        @if($wallet)
        <div class="card wallet-card-detail mt-4">
            <div class="card-header d-flex align-items-center">
                <div class="wallet-icon-small">
                    <i class="bi bi-wallet2"></i>
                </div>
                <h6 class="mb-0 ms-2">Your {{ $pool->coin_type }} Wallet</h6>
            </div>
            <div class="card-body">
                <div class="wallet-balance-detail">
                    <div class="balance-item">
                        <span>Available Balance</span>
                        <strong>{{ number_format($wallet->available_balance, 8) }}</strong>
                    </div>
                    <div class="balance-item">
                        <span>Staked Balance</span>
                        <strong>{{ number_format($wallet->staking_balance, 8) }}</strong>
                    </div>
                    <div class="balance-item">
                        <span>Total Earned</span>
                        <strong class="text-success">{{ number_format($wallet->total_earned, 8) }}</strong>
                    </div>
                </div>
                <div class="wallet-address-detail mt-3">
                    <small class="text-muted">Address</small>
                    <div class="address-value">{{ substr($wallet->address, 0, 20) }}...</div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
/* Welcome Header */
.welcome-header {
    padding: 1.5rem;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    margin-bottom: 1.5rem;
}

/* Pool Overview */
.pool-overview-card {
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    height: 100%;
    transition: all 0.3s ease;
}

.pool-overview-card:hover {
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
}

.pool-icon-large {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
}

.pool-info h3 {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    height: 100%;
}

.stat-item-modern {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1rem;
    text-align: center;
}

.stat-item-modern small {
    display: block;
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-bottom: 0.5rem;
}

.stat-item-modern strong {
    display: block;
    font-size: 1.125rem;
    color: var(--text-primary);
    font-weight: 600;
}

/* Modern Input */
.input-modern {
    margin-bottom: 2rem;
}

.input-modern .form-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
}

.input-with-suffix {
    position: relative;
    display: flex;
    align-items: center;
}

.form-control-modern {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 14px;
    padding: 1rem 1.5rem;
    color: var(--text-primary);
    font-size: 1.125rem;
    font-weight: 500;
    transition: all 0.3s ease;
    flex: 1;
}

.form-control-modern:focus {
    background: var(--card-bg);
    border-color: rgba(255, 255, 255, 0.3);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    color: var(--text-primary);
}

.input-suffix {
    position: absolute;
    right: 1.5rem;
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-muted);
    pointer-events: none;
}

.input-info {
    font-size: 0.813rem;
    color: var(--text-muted);
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
}

/* Reward Calculator */
.reward-calculator-modern {
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    overflow: hidden;
}

.calculator-header {
    background: linear-gradient(135deg, rgba(67, 233, 123, 0.1) 0%, rgba(56, 249, 215, 0.1) 100%);
    border-bottom: 1px solid var(--glass-border);
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    color: #43e97b;
}

.calculator-body {
    padding: 1.5rem;
}

.reward-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
}

.reward-card:hover {
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.reward-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0.5rem 0;
    font-family: 'Courier New', monospace;
}

.total-reward-card {
    background: linear-gradient(135deg, rgba(67, 233, 123, 0.15) 0%, rgba(56, 249, 215, 0.15) 100%);
    border: 1px solid rgba(67, 233, 123, 0.3);
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
}

.total-reward-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #43e97b;
    margin-top: 0.25rem;
}

.apy-display {
    text-align: right;
}

.apy-display .apy-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

/* Info Card */
.info-card-modern {
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    overflow: hidden;
}

.info-card-modern .card-header {
    background: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid var(--glass-border);
    padding: 1rem 1.5rem;
}

.info-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.info-item {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.info-item:last-child {
    border-bottom: none;
}

.info-icon-small {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    flex-shrink: 0;
    font-size: 0.875rem;
}

.info-content {
    flex: 1;
}

.info-content small {
    display: block;
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-bottom: 0.25rem;
    font-weight: 600;
}

.info-content p {
    font-size: 0.813rem;
    color: var(--text-secondary);
    line-height: 1.5;
    margin: 0;
}

/* Wallet Card Detail */
.wallet-card-detail {
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    overflow: hidden;
}

.wallet-card-detail .card-header {
    background: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid var(--glass-border);
    padding: 1rem 1.5rem;
}

.wallet-icon-small {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.wallet-balance-detail {
    padding: 0.5rem 0;
}

.balance-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.balance-item:last-child {
    border-bottom: none;
}

.balance-item span {
    font-size: 0.875rem;
    color: var(--text-muted);
}

.balance-item strong {
    font-size: 0.938rem;
    font-weight: 600;
    color: var(--text-primary);
}

.wallet-address-detail {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 1rem;
}

.address-value {
    font-family: 'Courier New', monospace;
    font-size: 0.813rem;
    color: var(--text-primary);
    margin-top: 0.25rem;
    word-break: break-all;
}

/* Submit Button */
.stake-confirm-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 14px;
    padding: 1rem;
    font-size: 1.125rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.stake-confirm-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
}

/* APY Badge */
.apy-badge-modern {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    border-radius: 12px;
    padding: 0.75rem 1.5rem;
    text-align: center;
    color: white;
    font-weight: 700;
    box-shadow: 0 8px 20px rgba(67, 233, 123, 0.3);
}

.apy-badge-modern .apy-value {
    font-size: 1.5rem;
    display: block;
    line-height: 1;
}

.apy-badge-modern small {
    font-size: 0.813rem;
    opacity: 0.9;
    font-weight: 500;
}

/* Responsive */
@media (max-width: 768px) {
    .pool-overview-card {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .reward-card {
        padding: 1rem;
    }
    
    .reward-value {
        font-size: 1.25rem;
    }
    
    .total-reward-value {
        font-size: 1.5rem;
    }
    
    .apy-badge-modern {
        padding: 0.5rem 1rem;
    }
    
    .apy-badge-modern .apy-value {
        font-size: 1.25rem;
    }
}
</style>

@push('scripts')
<script>
    const apy = {{ $pool->apy }};
    const durationMinutes = {{ $pool->duration_minutes }};
    
    function calculateRewards() {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        
        if (amount <= 0) {
            document.getElementById('durationReward').textContent = '0';
            document.getElementById('dailyReward').textContent = '0';
            document.getElementById('totalReward').textContent = '0 {{ $pool->coin_type }}';
            return;
        }
        
        // Daily reward rate
        const dailyRate = (apy / 365) / 100;
        const dailyReward = amount * dailyRate;
        
        // Duration reward
        const durationInDays = durationMinutes / 1440; // Convert minutes to days
        const durationReward = amount * dailyRate * durationInDays;
        
        // Total reward (same as duration reward for this calculation)
        const totalReward = durationReward;
        
        document.getElementById('durationReward').textContent = durationReward.toFixed(8);
        document.getElementById('dailyReward').textContent = dailyReward.toFixed(8);
        document.getElementById('totalReward').textContent = totalReward.toFixed(8) + ' {{ $pool->coin_type }}';
    }
    
    document.getElementById('amount').addEventListener('input', calculateRewards);
    
    // Calculate on page load if there's a value
    calculateRewards();
</script>
@endpush
@endsection