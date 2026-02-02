@extends('layouts.app')

@section('title', 'Staking Pools')

@section('content')
<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-6 fw-bold mb-2" style="background: linear-gradient(135deg, #ffffff 0%, #b4b4c8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        Staking Pools <i class="bi bi-graph-up-arrow"></i>
                    </h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-currency-exchange me-2"></i>Stake your crypto and earn rewards
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="#active-stakes" class="btn btn-outline-primary">
                        <i class="bi bi-lightning-charge me-2"></i>Active Stakes
                    </a>
                    <a href="#completed-stakes" class="btn btn-outline-primary">
                        <i class="bi bi-clock-history me-2"></i>History
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Staking Pools Grid -->
<div class="row g-4 mb-5">
    @foreach($pools as $pool)
    <div class="col-md-6 col-lg-4">
        <div class="pool-card-modern">
            <div class="apy-badge-modern">
                <span class="apy-value">{{ number_format($pool->apy, 2) }}%</span>
                <small>APY</small>
            </div>
            
            <div class="pool-header mb-4">
                <div class="pool-icon-wrapper" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);">
                    <i class="bi {{ $pool->coin_icon ?? 'bi-currency-bitcoin' }}"></i>
                </div>
                <div class="pool-title">
                    <h4 class="mb-1">{{ $pool->name }}</h4>
                    <span class="badge bg-dark">{{ $pool->coin_type }}</span>
                </div>
            </div>
            
            <p class="pool-description mb-4">{{ $pool->description ?? 'Earn passive income by staking your crypto.' }}</p>
            
            <div class="pool-stats">
                <div class="stat-row">
                    <div class="stat-item">
                        <small class="text-muted">Duration</small>
                        <strong>{{ $pool->duration_text }}</strong>
                    </div>
                    <div class="stat-item">
                        <small class="text-muted">Min Stake</small>
                        <strong>{{ number_format($pool->min_stake, 4) }}</strong>
                    </div>
                </div>
                <div class="stat-row">
                    <div class="stat-item">
                        <small class="text-muted">Total Staked</small>
                        <strong>{{ number_format($pool->total_staked, 2) }}</strong>
                    </div>
                    <div class="stat-item">
                        <small class="text-muted">Stakers</small>
                        <strong>{{ number_format($pool->total_stakers) }}</strong>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('staking.show', $pool->id) }}" class="btn btn-primary w-100 mt-4">
                <i class="bi bi-lock-fill me-2"></i>Stake Now
            </a>
        </div>
    </div>
    @endforeach
</div>

<!-- Active Stakes -->
<div id="active-stakes" class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-1">
                <i class="bi bi-lightning-charge-fill me-2" style="color: #ffd700;"></i>Active Stakes
            </h5>
            <small class="text-muted">Track your ongoing investments</small>
        </div>
        <span class="badge bg-primary">{{ $activeStakes->count() }} Active</span>
    </div>
    <div class="card-body p-0">
        @if($activeStakes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0 modern-table">
                    <thead>
                        <tr>
                            <th>Pool</th>
                            <th>Amount</th>
                            <th>Expected Reward</th>
                            <th>Duration</th>
                            <th>Progress</th>
                            <th>Time Left</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeStakes as $stake)
                        <tr class="stake-row">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="coin-icon-badge">
                                        <i class="bi {{ $stake->stakingPool->coin_icon }}"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $stake->stakingPool->name }}</div>
                                        <small class="text-muted">{{ $stake->stakingPool->coin_type }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ number_format($stake->amount, 4) }}</div>
                                <small class="text-muted">Staked</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-success">{{ number_format($stake->expected_reward, 4) }}</div>
                                <small class="text-muted">Reward</small>
                            </td>
                            <td>
                                <span class="badge bg-dark">
                                    {{ $stake->stakingPool->duration_text }}
                                </span>
                            </td>
                            <td>
                                <div class="progress-container">
                                    <div class="progress modern-progress">
                                        <div class="progress-bar" 
                                             style="width: {{ $stake->progress_percentage }}%; background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);">
                                        </div>
                                    </div>
                                    <small class="text-muted mt-1">{{ round($stake->progress_percentage) }}%</small>
                                </div>
                            </td>
                            <td>
                                <span class="time-badge">
                                    <i class="bi bi-clock me-1"></i>{{ $stake->remaining_time }}
                                </span>
                            </td>
                            <td>
                                @if($stake->progress_percentage >= 100)
                                <form action="{{ route('staking.claim', $stake->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-gift me-1"></i>Claim
                                    </button>
                                </form>
                                @else
                                <span class="badge bg-info">{{ $stake->remaining_time }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state py-5">
                <div class="empty-icon">
                    <i class="bi bi-lightning-charge"></i>
                </div>
                <h5 class="mt-3 mb-2">No Active Stakes</h5>
                <p class="text-muted mb-4">Start earning passive income by staking your crypto</p>
                <a href="#pools-section" class="btn btn-primary">
                    <i class="bi bi-rocket-takeoff me-2"></i>Browse Pools
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Completed Stakes -->
<div id="completed-stakes" class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-1">
                <i class="bi bi-check-circle-fill me-2" style="color: #43e97b;"></i>Staking History
            </h5>
            <small class="text-muted">Completed stakes and rewards</small>
        </div>
        <span class="badge bg-secondary">{{ $completedStakes->count() }} Total</span>
    </div>
    <div class="card-body p-0">
        @if($completedStakes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0 modern-table">
                    <thead>
                        <tr>
                            <th>Pool</th>
                            <th>Amount Staked</th>
                            <th>Reward Earned</th>
                            <th>End Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completedStakes as $stake)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="coin-icon-badge">
                                        <i class="bi {{ $stake->stakingPool->coin_icon }}"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $stake->stakingPool->name }}</div>
                                        <small class="text-muted">{{ $stake->stakingPool->coin_type }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ number_format($stake->amount, 4) }}</div>
                                <small class="text-muted">Principal</small>
                            </td>
                            <td>
                                @if($stake->reward_claimed)
                                <div class="fw-semibold text-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    {{ number_format($stake->actual_reward ?? $stake->expected_reward, 4) }}
                                </div>
                                <small class="text-success">Claimed</small>
                                @else
                                <div class="fw-semibold text-warning">
                                    {{ number_format($stake->expected_reward, 4) }}
                                </div>
                                <small class="text-warning">Pending</small>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $stake->end_date->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $stake->end_date->format('H:i') }}</small>
                            </td>
                            <td>
                                @if($stake->reward_claimed)
                                <span class="badge bg-success">Claimed</span>
                                @else
                                <form action="{{ route('staking.claim', $stake->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="bi bi-gift me-1"></i>Claim Reward
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state py-5">
                <div class="empty-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h5 class="mt-3 mb-2">No Staking History</h5>
                <p class="text-muted mb-4">Your completed stakes will appear here</p>
            </div>
        @endif
    </div>
</div>

<style>
/* Welcome Header (from dashboard) */
.welcome-header {
    padding: 1.5rem;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    margin-bottom: 1.5rem;
}

/* Modern Pool Cards */
.pool-card-modern {
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    padding: 1.5rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    height: 100%;
}

.pool-card-modern:hover {
    transform: translateY(-8px);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
}

.apy-badge-modern {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    border-radius: 12px;
    padding: 0.5rem 1rem;
    text-align: center;
    color: white;
    font-weight: 700;
    box-shadow: 0 8px 20px rgba(67, 233, 123, 0.3);
}

.apy-badge-modern .apy-value {
    font-size: 1.25rem;
    display: block;
    line-height: 1;
}

.apy-badge-modern small {
    font-size: 0.75rem;
    opacity: 0.9;
    font-weight: 500;
}

.pool-header {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.pool-icon-wrapper {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: white;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    flex-shrink: 0;
}

.pool-title h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.pool-description {
    color: var(--text-muted);
    font-size: 0.875rem;
    line-height: 1.6;
}

.pool-stats {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1rem;
}

.stat-row {
    display: flex;
    margin-bottom: 1rem;
}

.stat-row:last-child {
    margin-bottom: 0;
}

.stat-item {
    flex: 1;
    text-align: center;
    padding: 0 0.5rem;
}

.stat-item small {
    display: block;
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-bottom: 0.25rem;
}

.stat-item strong {
    display: block;
    font-size: 0.875rem;
    color: var(--text-primary);
    font-weight: 600;
}

/* Modern Table (from dashboard) */
.modern-table {
    color: var(--text-primary);
}

.modern-table thead th {
    border-bottom: 1px solid var(--glass-border);
    padding: 1rem 1.5rem;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.modern-table tbody td {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    vertical-align: middle;
}

.modern-table tbody tr {
    transition: all 0.3s ease;
}

.modern-table tbody tr:hover {
    background: var(--glass-bg);
}

.coin-icon-badge {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.modern-progress {
    height: 6px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    overflow: hidden;
}

.modern-progress .progress-bar {
    border-radius: 10px;
    transition: width 0.6s ease;
}

.progress-container {
    min-width: 100px;
}

.time-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.4rem 0.8rem;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 8px;
    font-size: 0.813rem;
    font-weight: 500;
    color: var(--text-secondary);
}

/* Empty State (from dashboard) */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: var(--text-muted);
    margin-bottom: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .pool-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .pool-icon-wrapper {
        width: 70px;
        height: 70px;
        font-size: 2rem;
    }
    
    .apy-badge-modern {
        position: relative;
        top: 0;
        right: 0;
        display: inline-block;
        margin-bottom: 1rem;
    }
    
    .stat-row {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .stat-item {
        text-align: left;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modern-table {
        font-size: 0.875rem;
    }
    
    .modern-table thead th,
    .modern-table tbody td {
        padding: 0.75rem;
    }
}
</style>
@endsection