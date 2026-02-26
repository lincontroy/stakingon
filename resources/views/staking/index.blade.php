@extends('layouts.app')

@section('title', 'Staking Pools')

@section('content')
<div class="staking-page">
    <div class="container-fluid px-4 py-4">
        <!-- Enhanced Header with Stats -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="welcome-header-enhanced">
                    <div class="header-content">
                        <div class="header-text">
                            <div class="header-badge mb-3">
                                <i class="bi bi-graph-up-arrow"></i>
                                <span>Staking Platform</span>
                            </div>
                            <h1 class="display-4 fw-bold mb-3">
                                Earn Passive Income with <span class="gradient-text">Crypto Staking</span>
                            </h1>
                            <p class="lead-text mb-4">
                                Stake your assets, earn rewards, and watch your portfolio grow with our secure and transparent staking pools.
                            </p>
                            <div class="header-actions">
                                <a href="#pools-section" class="btn btn-primary-enhanced">
                                    <i class="bi bi-rocket-takeoff"></i>
                                    <span>Start Staking</span>
                                </a>
                                <a href="#active-stakes" class="btn btn-secondary-enhanced">
                                    <i class="bi bi-lightning-charge"></i>
                                    <span>My Stakes</span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Quick Stats Cards -->
                        @php
                            $usdRates = [
                                'STEEM' => (float) (env('STEEMUSD') ?? 0.051),
                                'HIVE' => (float) (env('HIVEUSD') ?? 0.0674),
                                'USDT' => (float) (env('USDTUSD') ?? 1),
                            ];
                            
                            $totalLockedUsd = 0;
                            foreach($pools as $pool) {
                                if(isset($usdRates[$pool->coin_type])) {
                                    $totalLockedUsd += $pool->total_staked * $usdRates[$pool->coin_type];
                                }
                            }
                        @endphp
                        
                        <div class="quick-stats">
                            <div class="stat-card">
                                <div class="stat-icon">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>{{ number_format($pools->sum('total_staked'), 4) }}</h3>
                                    <p>Total Value Locked</p>
                                    <small class="text-white-50">≈ ${{ number_format($totalLockedUsd, 2) }}</small>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon success">
                                    <i class="bi bi-percent"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>{{ number_format($pools->max('apy'), 2) }}%</h3>
                                    <p>Highest APY</p>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon warning">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>{{ number_format($pools->sum('total_stakers')) }}</h3>
                                    <p>Active Stakers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Decorative Elements -->
                    <div class="header-decoration">
                        <div class="glow glow-1"></div>
                        <div class="glow glow-2"></div>
                        <div class="glow glow-3"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Sort Section -->
        <div class="row mb-4" id="pools-section">
            <div class="col-12">
                <div class="filter-bar">
                    <div class="filter-section">
                        <h5 class="filter-title">
                            <i class="bi bi-funnel"></i>
                            Available Pools
                        </h5>
                        <div class="filter-tags">
                            <button class="filter-tag active">All Pools</button>
                            <button class="filter-tag">High APY</button>
                            <button class="filter-tag">Low Risk</button>
                            <button class="filter-tag">Short Term</button>
                        </div>
                    </div>
                    <div class="sort-section">
                        <select class="sort-select">
                            <option>Sort by APY (High to Low)</option>
                            <option>Sort by APY (Low to High)</option>
                            <option>Sort by Duration</option>
                            <option>Sort by Min Stake</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Staking Pools Grid -->
        <div class="row g-4 mb-5">
            @foreach($pools as $pool)
            @php
                $poolUsdRate = $usdRates[$pool->coin_type] ?? 0;
                $totalStakedUsd = $pool->total_staked * $poolUsdRate;
                $minStakeUsd = $pool->min_stake * $poolUsdRate;
            @endphp
            <div class="col-12 col-md-6 col-xl-4">
                <div class="pool-card-premium">
                    <!-- Card Header with Gradient -->
                    <div class="pool-card-header">
                        <div class="pool-badge-container">
                            <div class="pool-icon" style="background: linear-gradient(135deg, {{ $pool->coin_type == 'USDT' ? '#22c55e' : '#f59e0b' }} 0%, {{ $pool->coin_type == 'USDT' ? '#10b981' : '#fbbf24' }} 100%);">
                                <i class="bi {{ $pool->coin_icon ?? ($pool->coin_type == 'USDT' ? 'bi-currency-dollar' : 'bi-currency-bitcoin') }}"></i>
                            </div>
                            <div class="apy-badge-premium">
                                <div class="apy-glow"></div>
                                <div class="apy-content">
                                    <span class="apy-number">{{ number_format($pool->apy, 2) }}%</span>
                                    <span class="apy-label">APY</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pool-info">
                            <h3 class="pool-name">{{ $pool->name }}</h3>
                            <span class="pool-coin-badge">{{ $pool->coin_type }}</span>
                        </div>
                        
                        <p class="pool-desc">{{ $pool->description ?? 'Earn passive income by staking your crypto with industry-leading returns.' }}</p>
                        
                        @if($poolUsdRate > 0)
                        <div class="pool-rate mt-2">
                            <small class="text-white-50">
                                <i class="bi bi-currency-exchange me-1"></i>1 {{ $pool->coin_type }} = ${{ number_format($poolUsdRate, $pool->coin_type == 'USDT' ? 2 : 4) }}
                            </small>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Card Stats Grid -->
                    <div class="pool-stats-grid">
                        <div class="stat-box">
                            <div class="stat-icon-mini">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="stat-details">
                                <span class="stat-label">Duration</span>
                                <span class="stat-value">{{ $pool->duration_text }}</span>
                            </div>
                        </div>
                        
                        <div class="stat-box">
                            <div class="stat-icon-mini">
                                <i class="bi bi-wallet2"></i>
                            </div>
                            <div class="stat-details">
                                <span class="stat-label">Min Stake</span>
                                <span class="stat-value">{{ number_format($pool->min_stake, 4) }}</span>
                                @if($poolUsdRate > 0)
                                <small class="text-white-50">${{ number_format($minStakeUsd, 2) }}</small>
                                @endif
                            </div>
                        </div>
                        
                        <div class="stat-box">
                            <div class="stat-icon-mini">
                                <i class="bi bi-lock"></i>
                            </div>
                            <div class="stat-details">
                                <span class="stat-label">Total Staked</span>
                                <span class="stat-value">{{ number_format($pool->total_staked, 4) }}</span>
                                @if($poolUsdRate > 0)
                                <small class="text-white-50">${{ number_format($totalStakedUsd, 2) }}</small>
                                @endif
                            </div>
                        </div>
                        
                        <div class="stat-box">
                            <div class="stat-icon-mini">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="stat-details">
                                <span class="stat-label">Stakers</span>
                                <span class="stat-value">{{ number_format($pool->total_stakers) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Footer with CTA -->
                    <div class="pool-card-footer">
                        <a href="{{ route('staking.show', $pool->id) }}" class="btn-stake-now">
                            <span class="btn-text">Stake Now</span>
                            <span class="btn-icon">
                                <i class="bi bi-arrow-right"></i>
                            </span>
                        </a>
                    </div>
                    
                    <!-- Hover Effect -->
                    <div class="card-shine"></div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Active Stakes Section -->
        <div id="active-stakes" class="section-enhanced mb-5">
            <div class="section-header-enhanced">
                <div class="section-title-group">
                    <div class="section-icon">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <div>
                        <h2 class="section-title">Active Stakes</h2>
                        <p class="section-subtitle">Monitor your ongoing investments and rewards</p>
                    </div>
                </div>
                <div class="section-badge">
                    <span>{{ $activeStakes->count() }}</span> Active
                </div>
            </div>
            
            <div class="section-content">
                @if($activeStakes->count() > 0)
                    <div class="stakes-list">
                        @foreach($activeStakes as $stake)
                        @php
                            $stakeUsdRate = $usdRates[$stake->stakingPool->coin_type] ?? 0;
                            $stakedUsd = $stake->amount * $stakeUsdRate;
                            $rewardUsd = $stake->expected_reward * $stakeUsdRate;
                        @endphp
                        <div class="stake-item">
                            <div class="stake-main">
                                <div class="stake-pool-info">
                                    <div class="stake-coin-icon">
                                        <i class="bi {{ $stake->stakingPool->coin_icon }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="stake-pool-name">{{ $stake->stakingPool->name }}</h4>
                                        <span class="stake-coin-type">{{ $stake->stakingPool->coin_type }}</span>
                                    </div>
                                </div>
                                
                                <div class="stake-details-grid">
                                    <div class="stake-detail">
                                        <span class="detail-label">Staked Amount</span>
                                        <span class="detail-value">{{ number_format($stake->amount, 4) }}</span>
                                        @if($stakeUsdRate > 0)
                                        <small class="text-white-50">${{ number_format($stakedUsd, 2) }}</small>
                                        @endif
                                    </div>
                                    
                                    <div class="stake-detail">
                                        <span class="detail-label">Expected Reward</span>
                                        <span class="detail-value text-success-glow">
                                            <i class="bi bi-trophy-fill"></i>
                                            {{ number_format($stake->expected_reward, 4) }}
                                        </span>
                                        @if($stakeUsdRate > 0)
                                        <small class="text-success">${{ number_format($rewardUsd, 2) }}</small>
                                        @endif
                                    </div>
                                    
                                    <div class="stake-detail">
                                        <span class="detail-label">Duration</span>
                                        <span class="detail-badge">{{ $stake->stakingPool->duration_text }}</span>
                                    </div>
                                    
                                    <div class="stake-detail">
                                        <span class="detail-label">Time Remaining</span>
                                        <span class="detail-time">
                                            <i class="bi bi-clock-fill"></i>
                                            <span class="countdown-text" 
                                                  data-end-date="{{ $stake->end_date->timestamp }}"
                                                  data-start-date="{{ $stake->start_date->timestamp }}">
                                                {{ $stake->remaining_time }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="stake-progress-section">
                                <div class="progress-header">
                                    <span class="progress-label">Progress</span>
                                    <span class="progress-percentage" data-progress="{{ $stake->progress_percentage }}">{{ round($stake->progress_percentage) }}%</span>
                                </div>
                                <div class="progress-bar-modern">
                                    <div class="progress-fill" 
                                         style="width: {{ $stake->progress_percentage }}%"
                                         data-end-date="{{ $stake->end_date->timestamp }}"
                                         data-start-date="{{ $stake->start_date->timestamp }}">
                                        <div class="progress-shine"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="stake-action">
                                @if($stake->progress_percentage >= 100)
                                <form action="{{ route('staking.claim', $stake->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-claim">
                                        <i class="bi bi-gift-fill"></i>
                                        <span>Claim Rewards</span>
                                    </button>
                                </form>
                                @else
                                <div class="status-pending">
                                    <i class="bi bi-hourglass-split"></i>
                                    <span>In Progress</span>
                                    @if($stakeUsdRate > 0)
                                    <small class="d-block text-white-50 mt-1">
                                        ≈ ${{ number_format($stakedUsd + $rewardUsd, 2) }} total value
                                    </small>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state-modern">
                        <div class="empty-illustration">
                            <div class="empty-icon-large">
                                <i class="bi bi-lightning-charge"></i>
                            </div>
                            <div class="empty-circles">
                                <div class="circle circle-1"></div>
                                <div class="circle circle-2"></div>
                                <div class="circle circle-3"></div>
                            </div>
                        </div>
                        <h3 class="empty-title">No Active Stakes Yet</h3>
                        <p class="empty-description">Start your staking journey and earn passive income on your crypto assets</p>
                        <a href="#pools-section" class="btn-empty-action">
                            <i class="bi bi-plus-circle"></i>
                            <span>Browse Pools</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Completed Stakes Section -->
        <div id="completed-stakes" class="section-enhanced">
            <div class="section-header-enhanced">
                <div class="section-title-group">
                    <div class="section-icon success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <h2 class="section-title">Staking History</h2>
                        <p class="section-subtitle">View your completed stakes and claimed rewards</p>
                    </div>
                </div>
                <div class="section-badge secondary">
                    <span>{{ $completedStakes->count() }}</span> Completed
                </div>
            </div>
            
            <div class="section-content">
                @if($completedStakes->count() > 0)
                    <div class="history-grid">
                        @foreach($completedStakes as $stake)
                        @php
                            $historyUsdRate = $usdRates[$stake->stakingPool->coin_type] ?? 0;
                            $stakedUsd = $stake->amount * $historyUsdRate;
                            $rewardUsd = ($stake->actual_reward ?? $stake->expected_reward) * $historyUsdRate;
                        @endphp
                        <div class="history-card">
                            <div class="history-header">
                                <div class="history-pool">
                                    <div class="history-icon">
                                        <i class="bi {{ $stake->stakingPool->coin_icon }}"></i>
                                    </div>
                                    <div>
                                        <h4>{{ $stake->stakingPool->name }}</h4>
                                        <span>{{ $stake->stakingPool->coin_type }}</span>
                                    </div>
                                </div>
                                @if($stake->reward_claimed)
                                <div class="status-badge success">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Claimed
                                </div>
                                @else
                                <div class="status-badge warning">
                                    <i class="bi bi-clock-fill"></i>
                                    Pending
                                </div>
                                @endif
                            </div>
                            
                            <div class="history-stats">
                                <div class="history-stat">
                                    <span class="history-label">Amount Staked</span>
                                    <span class="history-value">{{ number_format($stake->amount, 4) }}</span>
                                    @if($historyUsdRate > 0)
                                    <small class="text-white-50 d-block">${{ number_format($stakedUsd, 2) }}</small>
                                    @endif
                                </div>
                                <div class="history-stat">
                                    <span class="history-label">Reward Earned</span>
                                    <span class="history-value reward">
                                        {{ number_format($stake->actual_reward ?? $stake->expected_reward, 4) }}
                                    </span>
                                    @if($historyUsdRate > 0)
                                    <small class="text-success d-block">${{ number_format($rewardUsd, 2) }}</small>
                                    @endif
                                </div>
                                <div class="history-stat">
                                    <span class="history-label">Completed On</span>
                                    <span class="history-value">{{ $stake->end_date->format('M d, Y') }}</span>
                                </div>
                            </div>
                            
                            @if(!$stake->reward_claimed)
                            <div class="history-action">
                                <form action="{{ route('staking.claim', $stake->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-claim-small">
                                        <i class="bi bi-gift-fill"></i>
                                        Claim Reward
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state-modern">
                        <div class="empty-illustration">
                            <div class="empty-icon-large">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="empty-circles">
                                <div class="circle circle-1"></div>
                                <div class="circle circle-2"></div>
                                <div class="circle circle-3"></div>
                            </div>
                        </div>
                        <h3 class="empty-title">No Staking History</h3>
                        <p class="empty-description">Your completed stakes and rewards will appear here</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* ===================================
   CSS VARIABLES & ROOT STYLES
   =================================== */
:root {
    /* Colors */
    --color-bg: #0a0c10;
    --color-bg-secondary: #0f1115;
    --color-surface: #141820;
    --color-surface-light: #1a1f2e;
    --color-surface-hover: #252b3b;
    --color-border: #1f2937;
    --color-border-light: #2d3748;
    
    /* Text */
    --color-text-primary: #f9fafb;
    --color-text-secondary: #9ca3af;
    --color-text-tertiary: #6b7280;
    --color-text-disabled: #4b5563;
    
    /* Accents */
    --color-primary: #3b82f6;
    --color-primary-light: #60a5fa;
    --color-primary-dark: #2563eb;
    --color-success: #10b981;
    --color-success-light: #34d399;
    --color-warning: #f59e0b;
    --color-warning-light: #fbbf24;
    --color-danger: #ef4444;
    --color-info: #6366f1;
    
    /* Gradients */
    --gradient-primary: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    --gradient-success: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    --gradient-danger: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
    --gradient-dark: linear-gradient(180deg, #141820 0%, #0a0c10 100%);
    
    /* Shadows */
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.4);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.6);
    --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
    --shadow-glow: 0 0 20px rgba(59, 130, 246, 0.3);
    --shadow-glow-success: 0 0 20px rgba(16, 185, 129, 0.3);
    --shadow-glow-warning: 0 0 20px rgba(245, 158, 11, 0.3);
    
    /* Spacing */
    --spacing-xs: 0.5rem;
    --spacing-sm: 0.75rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --spacing-xl: 2rem;
    --spacing-2xl: 3rem;
    
    /* Border Radius */
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;
    --radius-2xl: 24px;
    --radius-full: 9999px;
    
    /* Transitions */
    --transition-fast: 150ms ease;
    --transition-base: 200ms ease;
    --transition-slow: 300ms ease;
    --transition-slower: 500ms ease;
    
    /* Typography */
    --font-sans: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    --font-mono: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, monospace;
}

/* Base Styles */
.staking-page {
    min-height: 100vh;
    background: var(--color-bg);
    font-family: var(--font-sans);
    color: var(--color-text-primary);
}

/* ===================================
   ENHANCED HEADER SECTION
   =================================== */
.welcome-header-enhanced {
    position: relative;
    background: var(--color-surface);
    border-radius: var(--radius-2xl);
    padding: var(--spacing-2xl);
    overflow: hidden;
    border: 1px solid var(--color-border);
}

.header-content {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--spacing-xl);
}

.header-badge {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--gradient-primary);
    border-radius: var(--radius-full);
    font-size: 0.875rem;
    font-weight: 600;
    color: white;
}

.header-badge i {
    font-size: 1rem;
}

.gradient-text {
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.lead-text {
    font-size: 1.125rem;
    color: var(--color-text-secondary);
    line-height: 1.7;
    max-width: 600px;
}

.header-actions {
    display: flex;
    gap: var(--spacing-md);
    flex-wrap: wrap;
}

.btn-primary-enhanced {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-md) var(--spacing-xl);
    background: var(--gradient-primary);
    border: none;
    border-radius: var(--radius-lg);
    color: white;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all var(--transition-base);
    box-shadow: var(--shadow-lg);
    text-decoration: none;
}

.btn-primary-enhanced:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl), var(--shadow-glow);
    color: white;
}

.btn-secondary-enhanced {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-md) var(--spacing-xl);
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    color: var(--color-text-primary);
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all var(--transition-base);
    text-decoration: none;
}

.btn-secondary-enhanced:hover {
    background: var(--color-surface-hover);
    border-color: var(--color-primary);
    transform: translateY(-2px);
    color: var(--color-text-primary);
}

/* Quick Stats */
.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-lg);
    margin-top: var(--spacing-xl);
}

.stat-card {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-lg);
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-xl);
    transition: all var(--transition-base);
}

.stat-card:hover {
    background: var(--color-surface-hover);
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.stat-icon {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gradient-primary);
    border-radius: var(--radius-lg);
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.stat-icon.success {
    background: var(--gradient-success);
}

.stat-icon.warning {
    background: var(--gradient-warning);
}

.stat-info h3 {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0 0 0.25rem 0;
}

.stat-info p {
    font-size: 0.875rem;
    color: var(--color-text-secondary);
    margin: 0;
}

.stat-info small {
    font-size: 0.75rem;
    margin-top: 0.25rem;
    display: block;
}

/* Header Decoration */
.header-decoration {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
    overflow: hidden;
}

.glow {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.15;
}

.glow-1 {
    width: 400px;
    height: 400px;
    background: var(--color-primary);
    top: -200px;
    right: -100px;
}

.glow-2 {
    width: 300px;
    height: 300px;
    background: var(--color-success);
    bottom: -150px;
    left: -100px;
}

.glow-3 {
    width: 250px;
    height: 250px;
    background: var(--color-warning);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* ===================================
   FILTER BAR
   =================================== */
.filter-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--spacing-lg);
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-xl);
    gap: var(--spacing-lg);
    flex-wrap: wrap;
}

.filter-section {
    display: flex;
    align-items: center;
    gap: var(--spacing-lg);
    flex: 1;
}

.filter-title {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    font-size: 1rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0;
}

.filter-tags {
    display: flex;
    gap: var(--spacing-sm);
    flex-wrap: wrap;
}

.filter-tag {
    padding: var(--spacing-sm) var(--spacing-md);
    background: transparent;
    border: 1px solid var(--color-border);
    border-radius: var(--radius-full);
    color: var(--color-text-secondary);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all var(--transition-fast);
}

.filter-tag:hover {
    background: var(--color-surface-light);
    color: var(--color-text-primary);
}

.filter-tag.active {
    background: var(--gradient-primary);
    border-color: transparent;
    color: white;
}

.sort-select {
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    color: var(--color-text-primary);
    font-size: 0.875rem;
    cursor: pointer;
    outline: none;
    transition: all var(--transition-fast);
}

.sort-select:hover {
    background: var(--color-surface-hover);
    border-color: var(--color-primary);
}

/* ===================================
   PREMIUM POOL CARDS
   =================================== */
.pool-card-premium {
    position: relative;
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-2xl);
    overflow: hidden;
    transition: all var(--transition-slow);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.pool-card-premium::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--gradient-primary);
    opacity: 0;
    transition: opacity var(--transition-base);
}

.pool-card-premium:hover {
    transform: translateY(-8px);
    border-color: var(--color-primary);
    box-shadow: var(--shadow-2xl), var(--shadow-glow);
}

.pool-card-premium:hover::before {
    opacity: 1;
}

.pool-card-header {
    padding: var(--spacing-xl);
}

.pool-badge-container {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: var(--spacing-lg);
}

.pool-icon {
    width: 64px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-lg);
    font-size: 2rem;
    color: white;
    box-shadow: var(--shadow-lg);
}

.apy-badge-premium {
    position: relative;
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--gradient-success);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg), var(--shadow-glow-success);
}

.apy-glow {
    position: absolute;
    inset: -2px;
    background: var(--gradient-success);
    border-radius: var(--radius-lg);
    filter: blur(8px);
    opacity: 0.5;
    z-index: -1;
}

.apy-content {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.apy-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
    line-height: 1;
}

.apy-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.8);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.pool-info {
    margin-bottom: var(--spacing-md);
}

.pool-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0 0 var(--spacing-sm) 0;
}

.pool-coin-badge {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.pool-desc {
    font-size: 0.875rem;
    color: var(--color-text-secondary);
    line-height: 1.6;
    margin: 0 0 var(--spacing-md) 0;
}

.pool-rate {
    font-size: 0.75rem;
    color: var(--color-text-tertiary);
    border-top: 1px dashed var(--color-border);
    padding-top: var(--spacing-sm);
}

.pool-stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-md);
    padding: var(--spacing-lg) var(--spacing-xl);
    background: var(--color-bg-secondary);
    border-top: 1px solid var(--color-border);
    border-bottom: 1px solid var(--color-border);
}

.stat-box {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-sm);
}

.stat-icon-mini {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    color: var(--color-text-secondary);
    font-size: 1rem;
    flex-shrink: 0;
}

.stat-details {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 0.75rem;
    color: var(--color-text-tertiary);
    margin-bottom: 2px;
}

.stat-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text-primary);
}

.stat-details small {
    font-size: 0.75rem;
    color: var(--color-text-tertiary);
    margin-top: 2px;
}

.pool-card-footer {
    padding: var(--spacing-xl);
    margin-top: auto;
}

.btn-stake-now {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-sm);
    width: 100%;
    padding: var(--spacing-md);
    background: var(--gradient-primary);
    border: none;
    border-radius: var(--radius-lg);
    color: white;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all var(--transition-base);
    text-decoration: none;
    position: relative;
    overflow: hidden;
}

.btn-stake-now::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-stake-now:hover::before {
    width: 300px;
    height: 300px;
}

.btn-stake-now:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg), var(--shadow-glow);
    color: white;
}

.btn-text {
    position: relative;
    z-index: 1;
}

.btn-icon {
    position: relative;
    z-index: 1;
    transition: transform var(--transition-base);
}

.btn-stake-now:hover .btn-icon {
    transform: translateX(4px);
}

.card-shine {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        45deg,
        transparent 30%,
        rgba(255, 255, 255, 0.03) 50%,
        transparent 70%
    );
    transform: rotate(45deg);
    pointer-events: none;
    transition: all 0.6s ease;
    opacity: 0;
}

.pool-card-premium:hover .card-shine {
    opacity: 1;
    animation: shine 1.5s ease-in-out infinite;
}

@keyframes shine {
    0% {
        transform: translateX(-100%) rotate(45deg);
    }
    100% {
        transform: translateX(100%) rotate(45deg);
    }
}

/* ===================================
   SECTION ENHANCED
   =================================== */
.section-enhanced {
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-2xl);
    overflow: hidden;
}

.section-header-enhanced {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--spacing-xl);
    border-bottom: 1px solid var(--color-border);
    background: var(--color-bg-secondary);
}

.section-title-group {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.section-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gradient-primary);
    border-radius: var(--radius-md);
    font-size: 1.25rem;
    color: white;
}

.section-icon.success {
    background: var(--gradient-success);
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0 0 0.25rem 0;
}

.section-subtitle {
    font-size: 0.875rem;
    color: var(--color-text-secondary);
    margin: 0;
}

.section-badge {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--gradient-primary);
    border-radius: var(--radius-full);
    font-size: 0.875rem;
    font-weight: 600;
    color: white;
}

.section-badge.secondary {
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    color: var(--color-text-secondary);
}

.section-badge span {
    font-size: 1rem;
}

.section-content {
    padding: var(--spacing-xl);
}

/* ===================================
   STAKES LIST
   =================================== */
.stakes-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.stake-item {
    padding: var(--spacing-xl);
    background: var(--color-bg-secondary);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-xl);
    transition: all var(--transition-base);
}

.stake-item:hover {
    background: var(--color-surface-light);
    border-color: var(--color-primary);
    transform: translateX(4px);
}

.stake-main {
    margin-bottom: var(--spacing-lg);
}

.stake-pool-info {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
}

.stake-coin-icon {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gradient-primary);
    border-radius: var(--radius-lg);
    font-size: 1.75rem;
    color: white;
    flex-shrink: 0;
}

.stake-pool-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0 0 0.25rem 0;
}

.stake-coin-type {
    font-size: 0.875rem;
    color: var(--color-text-secondary);
}

.stake-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: var(--spacing-lg);
}

.stake-detail {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.detail-label {
    font-size: 0.75rem;
    color: var(--color-text-tertiary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.detail-value {
    font-size: 1rem;
    font-weight: 600;
    color: var(--color-text-primary);
}

.text-success-glow {
    color: var(--color-success-light);
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
}

.detail-badge {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text-primary);
    width: fit-content;
}

.detail-time {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-primary-light);
    animation: timeCountdown 2s ease-in-out infinite;
}

.detail-time i {
    animation: spin 4s linear infinite;
}

.countdown-text {
    position: relative;
    display: inline-block;
}

.countdown-text::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100%;
    height: 1px;
    background: var(--color-primary-light);
    animation: countdown-underline 3s ease-in-out infinite;
}

@keyframes countdown-underline {
    0%, 100% {
        transform: scaleX(1);
        opacity: 0.3;
    }
    50% {
        transform: scaleX(0.7);
        opacity: 0.8;
    }
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

.stake-progress-section {
    padding: var(--spacing-lg);
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    margin-bottom: var(--spacing-lg);
    position: relative;
    overflow: hidden;
}

.stake-progress-section::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(
        circle,
        rgba(16, 185, 129, 0.03) 0%,
        transparent 70%
    );
    animation: progress-bg-pulse 4s ease-in-out infinite;
}

@keyframes progress-bg-pulse {
    0%, 100% {
        transform: translate(0, 0) scale(1);
        opacity: 0.5;
    }
    50% {
        transform: translate(-10%, -10%) scale(1.1);
        opacity: 0.8;
    }
}

.progress-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--spacing-sm);
    position: relative;
    z-index: 1;
}

.progress-header::before {
    content: '';
    position: absolute;
    left: -12px;
    top: 50%;
    transform: translateY(-50%);
    width: 6px;
    height: 6px;
    background: var(--color-success);
    border-radius: 50%;
    animation: pulse-dot 2s ease-in-out infinite;
}

@keyframes pulse-dot {
    0%, 100% {
        opacity: 1;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    }
    50% {
        opacity: 0.7;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0);
    }
}

.progress-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text-secondary);
}

.progress-percentage {
    font-size: 1rem;
    font-weight: 700;
    color: var(--color-text-primary);
    animation: timeCountdown 2s ease-in-out infinite;
}

.progress-bar-modern {
    height: 12px;
    background: var(--color-surface-light);
    border-radius: var(--radius-full);
    overflow: hidden;
    position: relative;
    z-index: 1;
}

.progress-fill {
    height: 100%;
    background: var(--gradient-success);
    border-radius: var(--radius-full);
    position: relative;
    transition: width var(--transition-slow);
    animation: progressPulse 3s ease-in-out infinite, progressIncrement 2s ease-in-out infinite;
    transform-origin: left center;
}

.progress-shine {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.3),
        transparent
    );
    animation: shimmer 2s infinite;
}

.progress-fill::after {
    content: '';
    position: absolute;
    top: 0;
    right: -2px;
    width: 4px;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 0 var(--radius-full) var(--radius-full) 0;
    box-shadow: 0 0 8px rgba(255, 255, 255, 0.6);
    animation: edge-glow 1.5s ease-in-out infinite;
}

@keyframes edge-glow {
    0%, 100% {
        opacity: 0.6;
        box-shadow: 0 0 8px rgba(255, 255, 255, 0.6);
    }
    50% {
        opacity: 1;
        box-shadow: 0 0 12px rgba(255, 255, 255, 0.9);
    }
}

@keyframes shimmer {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(100%);
    }
}

@keyframes progressPulse {
    0%, 100% {
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
    }
    50% {
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.6);
    }
}

@keyframes timeCountdown {
    0% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.7;
        transform: scale(0.98);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes progressIncrement {
    0% {
        transform: scaleX(0.99);
    }
    50% {
        transform: scaleX(1.001);
    }
    100% {
        transform: scaleX(0.99);
    }
}

.stake-action {
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: flex-end;
}

.btn-claim {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-md) var(--spacing-xl);
    background: var(--gradient-success);
    border: none;
    border-radius: var(--radius-lg);
    color: white;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all var(--transition-base);
    box-shadow: var(--shadow-md);
}

.btn-claim:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg), var(--shadow-glow-success);
}

.status-pending {
    display: inline-flex;
    flex-direction: column;
    align-items: flex-end;
    gap: var(--spacing-xs);
    padding: var(--spacing-md) var(--spacing-xl);
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text-secondary);
    position: relative;
    overflow: hidden;
}

.status-pending::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(59, 130, 246, 0.1),
        transparent
    );
    animation: status-sweep 3s ease-in-out infinite;
}

.status-pending i {
    animation: hourglass-rotate 2s ease-in-out infinite;
}

@keyframes status-sweep {
    0% {
        left: -100%;
    }
    100% {
        left: 100%;
    }
}

@keyframes hourglass-rotate {
    0%, 100% {
        transform: rotate(0deg);
    }
    50% {
        transform: rotate(180deg);
    }
}

/* ===================================
   HISTORY GRID
   =================================== */
.history-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: var(--spacing-lg);
}

.history-card {
    padding: var(--spacing-lg);
    background: var(--color-bg-secondary);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-xl);
    transition: all var(--transition-base);
}

.history-card:hover {
    background: var(--color-surface-light);
    border-color: var(--color-primary);
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.history-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--color-border);
}

.history-pool {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.history-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gradient-primary);
    border-radius: var(--radius-md);
    font-size: 1.25rem;
    color: white;
}

.history-pool h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 0.25rem 0;
}

.history-pool span {
    font-size: 0.75rem;
    color: var(--color-text-tertiary);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: 0.375rem 0.75rem;
    border-radius: var(--radius-md);
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge.success {
    background: var(--gradient-success);
    color: white;
}

.status-badge.warning {
    background: var(--gradient-warning);
    color: white;
}

.history-stats {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
}

.history-stat {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
}

.history-label {
    font-size: 0.875rem;
    color: var(--color-text-secondary);
}

.history-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text-primary);
}

.history-value.reward {
    color: var(--color-success-light);
}

.history-action {
    margin-top: var(--spacing-md);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--color-border);
}

.btn-claim-small {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-sm) var(--spacing-md);
    background: var(--gradient-warning);
    border: none;
    border-radius: var(--radius-md);
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all var(--transition-base);
    width: 100%;
    justify-content: center;
}

.btn-claim-small:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md), var(--shadow-glow-warning);
}

/* ===================================
   EMPTY STATE
   =================================== */
.empty-state-modern {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: var(--spacing-2xl) var(--spacing-lg);
    text-align: center;
}

.empty-illustration {
    position: relative;
    margin-bottom: var(--spacing-xl);
}

.empty-icon-large {
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-surface-light);
    border: 2px solid var(--color-border);
    border-radius: 50%;
    font-size: 3rem;
    color: var(--color-text-tertiary);
    position: relative;
    z-index: 2;
    transition: all var(--transition-slow);
}

.empty-state-modern:hover .empty-icon-large {
    color: var(--color-primary);
    border-color: var(--color-primary);
    transform: scale(1.1) rotate(10deg);
}

.empty-circles {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
}

.circle {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 2px solid var(--color-border);
    border-radius: 50%;
    opacity: 0.3;
    animation: pulse 3s ease-in-out infinite;
}

.circle-1 {
    width: 140px;
    height: 140px;
    animation-delay: 0s;
}

.circle-2 {
    width: 160px;
    height: 160px;
    animation-delay: 0.5s;
}

.circle-3 {
    width: 180px;
    height: 180px;
    animation-delay: 1s;
}

@keyframes pulse {
    0%, 100% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 0.3;
    }
    50% {
        transform: translate(-50%, -50%) scale(1.1);
        opacity: 0.1;
    }
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0 0 var(--spacing-sm) 0;
}

.empty-description {
    font-size: 1rem;
    color: var(--color-text-secondary);
    margin: 0 0 var(--spacing-xl) 0;
    max-width: 400px;
}

.btn-empty-action {
    display: inline-flex;
    align-items: center;
    gap: var(--spacing-sm);
    padding: var(--spacing-md) var(--spacing-xl);
    background: var(--gradient-primary);
    border: none;
    border-radius: var(--radius-lg);
    color: white;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all var(--transition-base);
    text-decoration: none;
}

.btn-empty-action:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg), var(--shadow-glow);
    color: white;
}

/* ===================================
   RESPONSIVE DESIGN
   =================================== */
@media (max-width: 1200px) {
    .header-content {
        grid-template-columns: 1fr;
    }
    
    .quick-stats {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    }
}

@media (max-width: 768px) {
    .welcome-header-enhanced {
        padding: var(--spacing-xl);
    }
    
    .header-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-primary-enhanced,
    .btn-secondary-enhanced {
        width: 100%;
        justify-content: center;
    }
    
    .quick-stats {
        grid-template-columns: 1fr;
    }
    
    .filter-bar {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .filter-section {
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
    }
    
    .sort-section {
        width: 100%;
    }
    
    .sort-select {
        width: 100%;
    }
    
    .pool-stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stake-details-grid {
        grid-template-columns: 1fr;
    }
    
    .history-grid {
        grid-template-columns: 1fr;
    }
    
    .section-header-enhanced {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--spacing-md);
    }
    
    .stake-action {
        align-items: stretch;
    }
    
    .status-pending {
        align-items: center;
        width: 100%;
    }
}

@media (max-width: 480px) {
    .welcome-header-enhanced {
        padding: var(--spacing-lg);
    }
    
    .display-4 {
        font-size: 1.75rem;
    }
    
    .lead-text {
        font-size: 1rem;
    }
    
    .pool-card-premium {
        border-radius: var(--radius-xl);
    }
    
    .pool-card-header,
    .pool-card-footer {
        padding: var(--spacing-lg);
    }
    
    .pool-stats-grid {
        padding: var(--spacing-md);
    }
    
    .section-enhanced {
        border-radius: var(--radius-xl);
    }
    
    .section-content {
        padding: var(--spacing-lg);
    }
    
    .stake-item {
        padding: var(--spacing-lg);
    }
}

/* ===================================
   CUSTOM SCROLLBAR
   =================================== */
::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

::-webkit-scrollbar-track {
    background: var(--color-surface);
}

::-webkit-scrollbar-thumb {
    background: var(--color-border);
    border-radius: var(--radius-sm);
}

::-webkit-scrollbar-thumb:hover {
    background: var(--color-border-light);
}

/* ===================================
   UTILITIES
   =================================== */
.text-gradient-primary {
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.text-gradient-success {
    background: var(--gradient-success);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Function to format time remaining
    function formatTimeRemaining(seconds) {
        if (seconds <= 0) {
            return 'Completed';
        }
        
        const days = Math.floor(seconds / (24 * 60 * 60));
        const hours = Math.floor((seconds % (24 * 60 * 60)) / (60 * 60));
        const minutes = Math.floor((seconds % (60 * 60)) / 60);
        const secs = Math.floor(seconds % 60);
        
        if (days > 0) {
            return `${days}d ${hours}h ${minutes}m`;
        } else if (hours > 0) {
            return `${hours}h ${minutes}m ${secs}s`;
        } else if (minutes > 0) {
            return `${minutes}m ${secs}s`;
        } else {
            return `${secs}s`;
        }
    }
    
    // Function to calculate progress percentage
    function calculateProgress(startTimestamp, endTimestamp) {
        const now = Math.floor(Date.now() / 1000);
        const totalDuration = endTimestamp - startTimestamp;
        const elapsed = now - startTimestamp;
        const progress = (elapsed / totalDuration) * 100;
        
        return Math.min(Math.max(progress, 0), 100);
    }
    
    // Update all countdowns and progress bars
    function updateAll() {
        const now = Math.floor(Date.now() / 1000);
        
        // Update countdown timers
        document.querySelectorAll('.countdown-text').forEach(function(element) {
            const endDate = parseInt(element.getAttribute('data-end-date'));
            const timeRemaining = endDate - now;
            
            if (timeRemaining > 0) {
                element.textContent = formatTimeRemaining(timeRemaining);
                element.style.color = 'var(--color-primary-light)';
            } else {
                element.textContent = 'Completed';
                element.style.color = 'var(--color-success)';
            }
        });
        
        // Update progress bars
        document.querySelectorAll('.progress-fill').forEach(function(element) {
            const startDate = parseInt(element.getAttribute('data-start-date'));
            const endDate = parseInt(element.getAttribute('data-end-date'));
            
            if (startDate && endDate) {
                const progress = calculateProgress(startDate, endDate);
                const progressPercentage = element.closest('.stake-progress-section').querySelector('.progress-percentage');
                
                // Smooth transition
                element.style.width = progress.toFixed(2) + '%';
                
                if (progressPercentage) {
                    progressPercentage.textContent = Math.round(progress) + '%';
                }
                
                // Change color when completed
                if (progress >= 100) {
                    element.style.background = 'var(--gradient-success)';
                    if (progressPercentage) {
                        progressPercentage.style.color = 'var(--color-success)';
                    }
                }
            }
        });
        
        // Check for completed stakes and update action buttons
        document.querySelectorAll('.stake-item').forEach(function(stakeItem) {
            const progressFill = stakeItem.querySelector('.progress-fill');
            const actionDiv = stakeItem.querySelector('.stake-action');
            
            if (progressFill && actionDiv) {
                const startDate = parseInt(progressFill.getAttribute('data-start-date'));
                const endDate = parseInt(progressFill.getAttribute('data-end-date'));
                const progress = calculateProgress(startDate, endDate);
                
                if (progress >= 100) {
                    const statusPending = actionDiv.querySelector('.status-pending');
                    if (statusPending) {
                        // Replace "In Progress" with "Claim" button
                        actionDiv.innerHTML = `
                            <form action="{{ route('staking.claim', $stake->id) }}" method="POST">
                                @csrf
                                <button class="btn-claim" type="submit">
                                    <i class="bi bi-gift-fill"></i>
                                    <span>Claim Rewards</span>
                                </button>
                            </form>
                        `;
                    }
                }
            }
        });
    }
    
    // Update immediately
    updateAll();
    
    // Update every second for smooth countdown
    setInterval(updateAll, 1000);
    
    // Add visual feedback when progress reaches milestones
    setInterval(function() {
        document.querySelectorAll('.progress-fill').forEach(function(element) {
            const width = parseFloat(element.style.width);
            
            // Celebrate milestones
            if (width >= 25 && width < 25.1) {
                element.style.boxShadow = '0 0 20px rgba(16, 185, 129, 0.8)';
                setTimeout(() => element.style.boxShadow = '', 500);
            } else if (width >= 50 && width < 50.1) {
                element.style.boxShadow = '0 0 20px rgba(16, 185, 129, 0.8)';
                setTimeout(() => element.style.boxShadow = '', 500);
            } else if (width >= 75 && width < 75.1) {
                element.style.boxShadow = '0 0 20px rgba(16, 185, 129, 0.8)';
                setTimeout(() => element.style.boxShadow = '', 500);
            } else if (width >= 100) {
                // Celebration effect when complete
                element.style.boxShadow = '0 0 30px rgba(16, 185, 129, 1)';
            }
        });
    }, 100);

    // Filter tags functionality
    document.querySelectorAll('.filter-tag').forEach(tag => {
        tag.addEventListener('click', function() {
            document.querySelectorAll('.filter-tag').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Here you can add filtering logic
            const filterValue = this.textContent.trim().toLowerCase();
            filterPools(filterValue);
        });
    });

    // Sort select functionality
    document.querySelector('.sort-select').addEventListener('change', function() {
        const sortValue = this.value;
        sortPools(sortValue);
    });

    function filterPools(filter) {
        const poolCards = document.querySelectorAll('.pool-card-premium');
        
        poolCards.forEach(card => {
            if (filter === 'all pools') {
                card.style.display = 'flex';
                return;
            }
            
            const apy = parseFloat(card.querySelector('.apy-number').textContent);
            const duration = card.querySelector('.stat-box:first-child .stat-value').textContent.toLowerCase();
            
            // Simple filtering logic - you can expand this
            if (filter.includes('high') && apy > 10) {
                card.style.display = 'flex';
            } else if (filter.includes('short') && (duration.includes('day') || duration.includes('week'))) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function sortPools(sortBy) {
        const poolGrid = document.querySelector('.row.g-4');
        const poolCards = Array.from(document.querySelectorAll('.pool-card-premium'));
        
        poolCards.sort((a, b) => {
            if (sortBy.includes('APY')) {
                const apyA = parseFloat(a.querySelector('.apy-number').textContent);
                const apyB = parseFloat(b.querySelector('.apy-number').textContent);
                return sortBy.includes('High') ? apyB - apyA : apyA - apyB;
            } else if (sortBy.includes('Duration')) {
                // Implement duration sorting logic
                return 0;
            } else if (sortBy.includes('Min Stake')) {
                const stakeA = parseFloat(a.querySelector('.stat-box:nth-child(2) .stat-value').textContent);
                const stakeB = parseFloat(b.querySelector('.stat-box:nth-child(2) .stat-value').textContent);
                return stakeA - stakeB;
            }
            return 0;
        });
        
        // Reorder cards
        poolCards.forEach(card => poolGrid.appendChild(card));
    }
});
</script>
@endsection