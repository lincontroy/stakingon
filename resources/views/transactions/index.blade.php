@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')
<div class="transactions-page">
    <!-- Enhanced Header with Stats -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-header-enhanced">
                <div class="header-content-wrapper">
                    <div class="header-text-section">
                        <div class="header-badge-modern mb-3">
                            <i class="bi bi-clock-history"></i>
                            <span>Transaction History</span>
                        </div>
                        <h1 class="display-5 fw-bold mb-3">
                            Track Your <span class="gradient-text">Financial Activity</span>
                        </h1>
                        <p class="lead-description">
                            View and manage all your deposits, withdrawals, and staking transactions in one place
                        </p>
                    </div>
                    
                    <!-- Stats Grid -->
                    @php
                        $usdRates = [
                            'STEEM' => (float) (env('STEEMUSD') ?? 0.051),
                            'HIVE' => (float) (env('HIVEUSD') ?? 0.0674),
                            'USDT' => (float) (env('USDTUSD') ?? 1),
                        ];
                        
                        $totalDepositUsd = 0;
                        $totalWithdrawalUsd = 0;
                        
                        foreach($transactions as $transaction) {
                            if(isset($usdRates[$transaction->coin_type])) {
                                $rate = $usdRates[$transaction->coin_type];
                                if($transaction->type == 'deposit' || $transaction->type == 'reward') {
                                    $totalDepositUsd += $transaction->amount * $rate;
                                } else if($transaction->type == 'withdrawal') {
                                    $totalWithdrawalUsd += $transaction->amount * $rate;
                                }
                            }
                        }
                    @endphp
                    
                    <div class="stats-grid">
                        <div class="stat-card-modern">
                            <div class="stat-icon-wrapper primary">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <div class="stat-details">
                                <h3>{{ $transactions->total() }}</h3>
                                <p>Total Transactions</p>
                                @if($totalDepositUsd + $totalWithdrawalUsd > 0)
                                <small class="text-white-50">${{ number_format($totalDepositUsd + $totalWithdrawalUsd, 2) }} volume</small>
                                @endif
                            </div>
                        </div>
                        
                        <div class="stat-card-modern">
                            <div class="stat-icon-wrapper success">
                                <i class="bi bi-arrow-down-circle"></i>
                            </div>
                            <div class="stat-details">
                                <h3>{{ $transactions->where('type', 'deposit')->count() }}</h3>
                                <p>Deposits</p>
                                @if($totalDepositUsd > 0)
                                <small class="text-success">${{ number_format($totalDepositUsd, 2) }}</small>
                                @endif
                            </div>
                        </div>
                        
                        <div class="stat-card-modern">
                            <div class="stat-icon-wrapper warning">
                                <i class="bi bi-arrow-up-circle"></i>
                            </div>
                            <div class="stat-details">
                                <h3>{{ $transactions->where('type', 'withdrawal')->count() }}</h3>
                                <p>Withdrawals</p>
                                @if($totalWithdrawalUsd > 0)
                                <small class="text-warning">${{ number_format($totalWithdrawalUsd, 2) }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Background Decoration -->
                <div class="header-decoration">
                    <div class="glow glow-1"></div>
                    <div class="glow glow-2"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-card-premium">
                <div class="filter-card-header">
                    <div class="filter-title-section">
                        <div class="filter-icon-modern">
                            <i class="bi bi-funnel-fill"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Advanced Filters</h5>
                            <p class="text-muted small mb-0">Narrow down your transaction history</p>
                        </div>
                    </div>
                    
                    <button class="btn-toggle-filters" onclick="toggleFilters()">
                        <i class="bi bi-chevron-down" id="filter-toggle-icon"></i>
                    </button>
                </div>
                
                <div class="filter-card-body" id="filter-section">
                    <form method="GET" action="{{ route('transactions.index') }}" id="filter-form">
                        <div class="row g-4">
                            <!-- Type Filter -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="filter-input-group">
                                    <label class="filter-label">
                                        <i class="bi bi-tags-fill"></i>
                                        Transaction Type
                                    </label>
                                    <div class="select-wrapper">
                                        <select class="form-select-premium" id="type" name="type">
                                            <option value="">All Types</option>
                                            <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>
                                                💰 Deposit
                                            </option>
                                            <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>
                                                💸 Withdrawal
                                            </option>
                                            <option value="staking" {{ request('type') == 'staking' ? 'selected' : '' }}>
                                                🔒 Staking
                                            </option>
                                            <option value="reward" {{ request('type') == 'reward' ? 'selected' : '' }}>
                                                🎁 Reward
                                            </option>
                                            <option value="unstaking" {{ request('type') == 'unstaking' ? 'selected' : '' }}>
                                                🔓 Unstaking
                                            </option>
                                        </select>
                                        <i class="bi bi-chevron-down select-icon"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Coin Filter -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="filter-input-group">
                                    <label class="filter-label">
                                        <i class="bi bi-currency-bitcoin"></i>
                                        Cryptocurrency
                                    </label>
                                    <div class="select-wrapper">
                                        <select class="form-select-premium" id="coin_type" name="coin_type">
                                            <option value="">All Coins</option>
                                            <option value="HIVE" {{ request('coin_type') == 'HIVE' ? 'selected' : '' }}>HIVE</option>
                                            <option value="STEEM" {{ request('coin_type') == 'STEEM' ? 'selected' : '' }}>STEEM</option>
                                            <option value="USDT" {{ request('coin_type') == 'USDT' ? 'selected' : '' }}>USDT</option>
                                            <option value="BTC" {{ request('coin_type') == 'BTC' ? 'selected' : '' }}>BTC</option>
                                            <option value="ETH" {{ request('coin_type') == 'ETH' ? 'selected' : '' }}>ETH</option>
                                            <option value="BNB" {{ request('coin_type') == 'BNB' ? 'selected' : '' }}>BNB</option>
                                            <option value="SOL" {{ request('coin_type') == 'SOL' ? 'selected' : '' }}>SOL</option>
                                            <option value="ADA" {{ request('coin_type') == 'ADA' ? 'selected' : '' }}>ADA</option>
                                        </select>
                                        <i class="bi bi-chevron-down select-icon"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status Filter -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="filter-input-group">
                                    <label class="filter-label">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Status
                                    </label>
                                    <div class="select-wrapper">
                                        <select class="form-select-premium" id="status" name="status">
                                            <option value="">All Statuses</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>❌ Failed</option>
                                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>🚫 Cancelled</option>
                                        </select>
                                        <i class="bi bi-chevron-down select-icon"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Date Range -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="filter-input-group">
                                    <label class="filter-label">
                                        <i class="bi bi-calendar-range"></i>
                                        Date Range
                                    </label>
                                    <input type="date" class="form-input-premium" id="start_date" name="start_date" 
                                           value="{{ request('start_date') }}" placeholder="Start Date">
                                </div>
                            </div>
                            
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="filter-input-group">
                                    <label class="filter-label">
                                        <i class="bi bi-calendar-check"></i>
                                        End Date
                                    </label>
                                    <input type="date" class="form-input-premium" id="end_date" name="end_date" 
                                           value="{{ request('end_date') }}" placeholder="End Date">
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="col-lg-6 col-md-8 col-sm-12">
                                <div class="filter-actions">
                                    <button type="submit" class="btn-filter-apply">
                                        <i class="bi bi-search"></i>
                                        <span>Apply Filters</span>
                                    </button>
                                    <a href="{{ route('transactions.index') }}" class="btn-filter-reset">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                        <span>Reset All</span>
                                    </a>
                                    <button type="button" class="btn-filter-export">
                                        <i class="bi bi-download"></i>
                                        <span>Export</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Section -->
    <div class="row">
        <div class="col-12">
            <div class="transactions-card-premium">
                <div class="transactions-card-header">
                    <div>
                        <h5 class="mb-1">
                            <i class="bi bi-list-ul me-2"></i>All Transactions
                        </h5>
                        <p class="text-muted small mb-0">
                            Showing {{ $transactions->firstItem() ?? 0 }}-{{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }} results
                            @if($totalDepositUsd + $totalWithdrawalUsd > 0)
                            | Total volume: <span class="text-white-50">${{ number_format($totalDepositUsd + $totalWithdrawalUsd, 2) }}</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="view-switcher">
                        <button class="view-btn active" data-view="table" onclick="switchView('table')">
                            <i class="bi bi-table"></i>
                        </button>
                        <button class="view-btn" data-view="grid" onclick="switchView('grid')">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </button>
                    </div>
                </div>
                
                <div class="transactions-card-body">
                    @if($transactions->count() > 0)
                        <!-- Table View -->
                        <div id="table-view" class="view-container active">
                            <div class="table-responsive">
                                <table class="table-premium">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="th-content">
                                                    <i class="bi bi-calendar3"></i>
                                                    <span>Date & Time</span>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="th-content">
                                                    <i class="bi bi-tag"></i>
                                                    <span>Type</span>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="th-content">
                                                    <i class="bi bi-currency-exchange"></i>
                                                    <span>Coin</span>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="th-content">
                                                    <i class="bi bi-cash-stack"></i>
                                                    <span>Amount</span>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="th-content">
                                                    <i class="bi bi-currency-dollar"></i>
                                                    <span>USD Value</span>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="th-content">
                                                    <i class="bi bi-receipt"></i>
                                                    <span>Fee</span>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="th-content">
                                                    <i class="bi bi-send"></i>
                                                    <span>Destination</span>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="th-content">
                                                    <i class="bi bi-check2-circle"></i>
                                                    <span>Status</span>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="th-content">
                                                    <i class="bi bi-hash"></i>
                                                    <span>TX ID</span>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="th-content">
                                                    <span>Actions</span>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transactions as $transaction)
                                        @php
                                            $txUsdRate = $usdRates[$transaction->coin_type] ?? 0;
                                            $txUsdValue = $transaction->amount * $txUsdRate;
                                            $feeUsdValue = $transaction->fee * $txUsdRate;
                                        @endphp
                                        <tr class="transaction-row-premium">
                                            <td>
                                                <div class="date-cell">
                                                    <div class="date-primary">{{ $transaction->created_at->format('M d, Y') }}</div>
                                                    <div class="date-secondary">{{ $transaction->created_at->format('h:i A') }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="type-cell">
                                                    <div class="type-icon-premium {{ $transaction->type }}">
                                                        @if($transaction->type == 'deposit')
                                                            <i class="bi bi-arrow-down-circle-fill"></i>
                                                        @elseif($transaction->type == 'withdrawal')
                                                            <i class="bi bi-arrow-up-circle-fill"></i>
                                                        @elseif($transaction->type == 'staking')
                                                            <i class="bi bi-lock-fill"></i>
                                                        @elseif($transaction->type == 'reward')
                                                            <i class="bi bi-gift-fill"></i>
                                                        @else
                                                            <i class="bi bi-unlock-fill"></i>
                                                        @endif
                                                    </div>
                                                    <div class="type-info">
                                                        <div class="type-name">{{ ucfirst($transaction->type) }}</div>
                                                        <div class="type-subtitle">Transaction</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="coin-cell">
                                                    <div class="coin-icon-premium">
                                                        <i class="bi bi-currency-bitcoin"></i>
                                                    </div>
                                                    <span class="coin-name">{{ $transaction->coin_type }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="amount-cell">
                                                    <div class="amount-primary {{ in_array($transaction->type, ['deposit', 'reward']) ? 'positive' : 'negative' }}">
                                                        @if(in_array($transaction->type, ['deposit', 'reward']))
                                                            <i class="bi bi-plus-circle-fill"></i>
                                                        @else
                                                            <i class="bi bi-dash-circle-fill"></i>
                                                        @endif
                                                        <span>{{ number_format($transaction->amount, 8) }}</span>
                                                    </div>
                                                    <div class="amount-secondary">{{ $transaction->coin_type }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="usd-cell">
                                                    @if($txUsdRate > 0)
                                                    <div class="usd-value {{ in_array($transaction->type, ['deposit', 'reward']) ? 'positive' : 'negative' }}">
                                                        ${{ number_format($txUsdValue, 2) }}
                                                    </div>
                                                    <div class="usd-rate small text-white-50">
                                                        @ {{ number_format($txUsdRate, $transaction->coin_type == 'USDT' ? 2 : 4) }}
                                                    </div>
                                                    @else
                                                    <span class="text-muted-premium">—</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($transaction->fee > 0)
                                                <div class="fee-cell">
                                                    <div class="fee-amount">{{ number_format($transaction->fee, 8) }}</div>
                                                    <div class="fee-label">Network Fee</div>
                                                    @if($feeUsdValue > 0)
                                                    <div class="fee-usd small text-white-50">${{ number_format($feeUsdValue, 2) }}</div>
                                                    @endif
                                                </div>
                                                @else
                                                <span class="text-muted-premium">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="address-cell">
                                                    <div class="address-label">
                                                        @if($transaction->type == 'deposit')
                                                            <i class="bi bi-arrow-down"></i> Received
                                                        @elseif($transaction->type == 'withdrawal')
                                                            <i class="bi bi-arrow-up"></i> Sent to
                                                        @else
                                                            <i class="bi bi-gear"></i> System
                                                        @endif
                                                    </div>
                                                    <div class="address-value">
                                                        {{ substr($transaction->to_address ?? 'Internal', 0, 10) }}...
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="status-badge-premium {{ $transaction->status }}">
                                                    <span class="status-dot"></span>
                                                    <span class="status-text">{{ ucfirst($transaction->status) }}</span>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="txid-cell">
                                                    <button class="txid-copy" onclick="copyTxid('{{ $transaction->txid }}')" 
                                                            title="Click to copy">
                                                        <span class="txid-text">{{ substr($transaction->txid, 0, 8) }}...</span>
                                                        <i class="bi bi-clipboard"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('transactions.show', $transaction->id) }}" 
                                                   class="btn-action-premium">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Grid View -->
                        <div id="grid-view" class="view-container">
                            <div class="transactions-grid">
                                @foreach($transactions as $transaction)
                                @php
                                    $txUsdRate = $usdRates[$transaction->coin_type] ?? 0;
                                    $txUsdValue = $transaction->amount * $txUsdRate;
                                @endphp
                                <div class="transaction-card-grid">
                                    <div class="transaction-card-header-grid">
                                        <div class="type-badge-grid {{ $transaction->type }}">
                                            @if($transaction->type == 'deposit')
                                                <i class="bi bi-arrow-down-circle-fill"></i>
                                            @elseif($transaction->type == 'withdrawal')
                                                <i class="bi bi-arrow-up-circle-fill"></i>
                                            @elseif($transaction->type == 'staking')
                                                <i class="bi bi-lock-fill"></i>
                                            @elseif($transaction->type == 'reward')
                                                <i class="bi bi-gift-fill"></i>
                                            @else
                                                <i class="bi bi-unlock-fill"></i>
                                            @endif
                                            <span>{{ ucfirst($transaction->type) }}</span>
                                        </div>
                                        <span class="status-badge-premium {{ $transaction->status }}">
                                            <span class="status-dot"></span>
                                            <span class="status-text">{{ ucfirst($transaction->status) }}</span>
                                        </span>
                                    </div>
                                    
                                    <div class="transaction-amount-grid {{ in_array($transaction->type, ['deposit', 'reward']) ? 'positive' : 'negative' }}">
                                        @if(in_array($transaction->type, ['deposit', 'reward']))
                                            <i class="bi bi-plus-circle-fill"></i>
                                        @else
                                            <i class="bi bi-dash-circle-fill"></i>
                                        @endif
                                        <span>{{ number_format($transaction->amount, 8) }} {{ $transaction->coin_type }}</span>
                                    </div>
                                    
                                    @if($txUsdRate > 0)
                                    <div class="transaction-usd-grid mb-3 text-center">
                                        <span class="badge bg-primary bg-opacity-25 text-white px-3 py-2">
                                            ≈ ${{ number_format($txUsdValue, 2) }} USD
                                        </span>
                                    </div>
                                    @endif
                                    
                                    <div class="transaction-details-grid">
                                        <div class="detail-item-grid">
                                            <span class="detail-label-grid">Date</span>
                                            <span class="detail-value-grid">{{ $transaction->created_at->format('M d, Y h:i A') }}</span>
                                        </div>
                                        <div class="detail-item-grid">
                                            <span class="detail-label-grid">Fee</span>
                                            <span class="detail-value-grid">{{ $transaction->fee > 0 ? number_format($transaction->fee, 8) : '—' }}</span>
                                        </div>
                                        @if($txUsdRate > 0)
                                        <div class="detail-item-grid">
                                            <span class="detail-label-grid">Rate</span>
                                            <span class="detail-value-grid">${{ number_format($txUsdRate, $transaction->coin_type == 'USDT' ? 2 : 4) }}</span>
                                        </div>
                                        @endif
                                        <div class="detail-item-grid">
                                            <span class="detail-label-grid">TX ID</span>
                                            <span class="detail-value-grid">{{ substr($transaction->txid, 0, 12) }}...</span>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('transactions.show', $transaction->id) }}" class="btn-view-grid">
                                        <span>View Details</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        @if($transactions->hasPages())
                        <div class="pagination-premium">
                            {{ $transactions->links('pagination::bootstrap-5') }}
                        </div>
                        @endif
                        
                    @else
                        <div class="empty-state-premium">
                            <div class="empty-illustration-premium">
                                <div class="empty-icon-premium">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <div class="empty-circles-premium">
                                    <div class="circle-premium circle-1"></div>
                                    <div class="circle-premium circle-2"></div>
                                    <div class="circle-premium circle-3"></div>
                                </div>
                            </div>
                            <h3 class="empty-title-premium">No Transactions Found</h3>
                            <p class="empty-description-premium">
                                We couldn't find any transactions matching your current filters. 
                                Try adjusting your search criteria or clearing all filters.
                            </p>
                            <a href="{{ route('transactions.index') }}" class="btn-empty-action-premium">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                <span>Clear All Filters</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ===================================
   CSS VARIABLES
   =================================== */
:root {
    --color-bg: #0a0c10;
    --color-bg-secondary: #0f1115;
    --color-surface: #141820;
    --color-surface-light: #1a1f2e;
    --color-surface-hover: #252b3b;
    --color-border: #1f2937;
    --color-border-light: #2d3748;
    
    --color-text-primary: #f9fafb;
    --color-text-secondary: #9ca3af;
    --color-text-tertiary: #6b7280;
    
    --color-primary: #3b82f6;
    --color-success: #10b981;
    --color-warning: #f59e0b;
    --color-danger: #ef4444;
    --color-info: #6366f1;
    
    --gradient-primary: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    --gradient-success: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    --gradient-danger: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
    
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.4);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.6);
    
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;
    --radius-2xl: 24px;
    --radius-full: 9999px;
    
    --transition-fast: 150ms ease;
    --transition-base: 200ms ease;
    --transition-slow: 300ms ease;
}

/* ===================================
   BASE STYLES
   =================================== */
.transactions-page {
    min-height: 100vh;
    padding-bottom: 2rem;
}

/* ===================================
   ENHANCED HEADER
   =================================== */
.welcome-header-enhanced {
    position: relative;
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-2xl);
    padding: 2rem;
    overflow: hidden;
    margin-bottom: 2rem;
}

.header-content-wrapper {
    position: relative;
    z-index: 2;
}

.header-badge-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--gradient-primary);
    border-radius: var(--radius-full);
    font-size: 0.875rem;
    font-weight: 600;
    color: white;
}

.gradient-text {
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.lead-description {
    font-size: 1.125rem;
    color: var(--color-text-secondary);
    line-height: 1.7;
    max-width: 600px;
    margin-bottom: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}

.stat-card-modern {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    transition: all var(--transition-base);
}

.stat-card-modern:hover {
    background: var(--color-surface-hover);
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.stat-icon-wrapper {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-md);
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.stat-icon-wrapper.primary {
    background: var(--gradient-primary);
}

.stat-icon-wrapper.success {
    background: var(--gradient-success);
}

.stat-icon-wrapper.warning {
    background: var(--gradient-warning);
}

.stat-details h3 {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0 0 0.25rem 0;
    line-height: 1;
}

.stat-details p {
    font-size: 0.875rem;
    color: var(--color-text-secondary);
    margin: 0;
}

.stat-details small {
    font-size: 0.75rem;
    margin-top: 0.25rem;
    display: block;
}

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
    opacity: 0.1;
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

/* ===================================
   FILTER CARD PREMIUM
   =================================== */
.filter-card-premium {
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-2xl);
    overflow: hidden;
}

.filter-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem;
    border-bottom: 1px solid var(--color-border);
    background: var(--color-bg-secondary);
}

.filter-title-section {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.filter-icon-modern {
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

.filter-title-section h5 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0;
}

.btn-toggle-filters {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    color: var(--color-text-secondary);
    cursor: pointer;
    transition: all var(--transition-base);
}

.btn-toggle-filters:hover {
    background: var(--color-surface-hover);
    color: var(--color-text-primary);
}

.btn-toggle-filters i {
    transition: transform var(--transition-base);
}

.btn-toggle-filters.active i {
    transform: rotate(180deg);
}

.filter-card-body {
    padding: 1.5rem;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease, padding 0.3s ease;
}

.filter-card-body.active {
    max-height: 800px;
    padding: 1.5rem;
}

.filter-input-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text-secondary);
}

.select-wrapper {
    position: relative;
}

.form-select-premium {
    width: 100%;
    padding: 0.75rem 2.5rem 0.75rem 1rem;
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    color: var(--color-text-primary);
    font-size: 0.875rem;
    cursor: pointer;
    transition: all var(--transition-base);
    appearance: none;
}

.form-select-premium:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.select-icon {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-text-tertiary);
    pointer-events: none;
}

.form-input-premium {
    width: 100%;
    padding: 0.75rem 1rem;
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    color: var(--color-text-primary);
    font-size: 0.875rem;
    transition: all var(--transition-base);
}

.form-input-premium:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-actions {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    height: 100%;
    padding-top: 1.75rem;
}

.btn-filter-apply {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: var(--gradient-primary);
    border: none;
    border-radius: var(--radius-md);
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all var(--transition-base);
}

.btn-filter-apply:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-filter-reset,
.btn-filter-export {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    color: var(--color-text-primary);
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    text-decoration: none;
    transition: all var(--transition-base);
}

.btn-filter-reset:hover,
.btn-filter-export:hover {
    background: var(--color-surface-hover);
    transform: translateY(-2px);
    color: var(--color-text-primary);
}

/* ===================================
   TRANSACTIONS CARD
   =================================== */
.transactions-card-premium {
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-2xl);
    overflow: hidden;
}

.transactions-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem;
    border-bottom: 1px solid var(--color-border);
    background: var(--color-bg-secondary);
}

.transactions-card-header h5 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0;
}

.view-switcher {
    display: flex;
    gap: 0.5rem;
    background: var(--color-surface-light);
    padding: 0.25rem;
    border-radius: var(--radius-md);
}

.view-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    border-radius: var(--radius-sm);
    color: var(--color-text-tertiary);
    cursor: pointer;
    transition: all var(--transition-fast);
}

.view-btn:hover {
    color: var(--color-text-primary);
}

.view-btn.active {
    background: var(--gradient-primary);
    color: white;
}

.transactions-card-body {
    padding: 0;
}

.view-container {
    display: none;
}

.view-container.active {
    display: block;
}

/* ===================================
   TABLE PREMIUM
   =================================== */
.table-responsive {
    overflow-x: auto;
}

.table-premium {
    width: 100%;
    border-collapse: collapse;
}

.table-premium thead th {
    padding: 1.25rem 1.5rem;
    background: var(--color-bg-secondary);
    border-bottom: 2px solid var(--color-border);
    text-align: left;
}

.th-content {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--color-text-tertiary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.transaction-row-premium {
    border-bottom: 1px solid var(--color-border);
    transition: all var(--transition-base);
}

.transaction-row-premium:hover {
    background: var(--color-surface-light);
}

.transaction-row-premium td {
    padding: 1.25rem 1.5rem;
    vertical-align: middle;
}

.date-cell {
    min-width: 120px;
}

.date-primary {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin-bottom: 0.25rem;
}

.date-secondary {
    font-size: 0.75rem;
    color: var(--color-text-tertiary);
}

.type-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.type-icon-premium {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-md);
    font-size: 1.125rem;
    flex-shrink: 0;
}

.type-icon-premium.deposit {
    background: rgba(16, 185, 129, 0.15);
    color: var(--color-success);
}

.type-icon-premium.withdrawal {
    background: rgba(239, 68, 68, 0.15);
    color: var(--color-danger);
}

.type-icon-premium.staking {
    background: rgba(245, 158, 11, 0.15);
    color: var(--color-warning);
}

.type-icon-premium.reward {
    background: rgba(52, 211, 153, 0.15);
    color: #34d399;
}

.type-icon-premium.unstaking {
    background: rgba(99, 102, 241, 0.15);
    color: var(--color-info);
}

.type-info {
    display: flex;
    flex-direction: column;
}

.type-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text-primary);
}

.type-subtitle {
    font-size: 0.75rem;
    color: var(--color-text-tertiary);
}

.coin-cell {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
}

.coin-icon-premium {
    font-size: 1rem;
    color: var(--color-warning);
}

.coin-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text-primary);
}

.amount-cell {
    min-width: 140px;
}

.amount-primary {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.amount-primary.positive {
    color: var(--color-success);
}

.amount-primary.negative {
    color: var(--color-text-primary);
}

.amount-secondary {
    font-size: 0.75rem;
    color: var(--color-text-tertiary);
}

.usd-cell {
    min-width: 100px;
}

.usd-value {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.usd-value.positive {
    color: var(--color-success);
}

.usd-value.negative {
    color: var(--color-danger);
}

.usd-rate {
    font-size: 0.7rem;
}

.fee-cell {
    min-width: 100px;
}

.fee-amount {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin-bottom: 0.25rem;
}

.fee-label {
    font-size: 0.75rem;
    color: var(--color-text-tertiary);
}

.fee-usd {
    font-size: 0.7rem;
    margin-top: 0.25rem;
}

.address-cell {
    min-width: 140px;
}

.address-label {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: var(--color-text-tertiary);
    margin-bottom: 0.25rem;
}

.address-value {
    font-family: 'Courier New', monospace;
    font-size: 0.813rem;
    color: var(--color-text-primary);
}

.status-badge-premium {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    animation: pulse-status 2s ease-in-out infinite;
}

@keyframes pulse-status {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.status-badge-premium.completed {
    background: rgba(16, 185, 129, 0.15);
    color: var(--color-success);
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.status-badge-premium.completed .status-dot {
    background: var(--color-success);
}

.status-badge-premium.pending {
    background: rgba(245, 158, 11, 0.15);
    color: var(--color-warning);
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.status-badge-premium.pending .status-dot {
    background: var(--color-warning);
}

.status-badge-premium.failed {
    background: rgba(239, 68, 68, 0.15);
    color: var(--color-danger);
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.status-badge-premium.failed .status-dot {
    background: var(--color-danger);
}

.status-badge-premium.cancelled {
    background: rgba(107, 114, 128, 0.15);
    color: var(--color-text-tertiary);
    border: 1px solid rgba(107, 114, 128, 0.3);
}

.status-badge-premium.cancelled .status-dot {
    background: var(--color-text-tertiary);
}

.txid-cell {
    min-width: 120px;
}

.txid-copy {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    color: var(--color-text-secondary);
    font-family: 'Courier New', monospace;
    font-size: 0.813rem;
    cursor: pointer;
    transition: all var(--transition-base);
}

.txid-copy:hover {
    background: var(--color-surface-hover);
    border-color: var(--color-primary);
    color: var(--color-primary);
}

.btn-action-premium {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--gradient-primary);
    border: none;
    border-radius: var(--radius-md);
    color: white;
    cursor: pointer;
    transition: all var(--transition-base);
    text-decoration: none;
}

.btn-action-premium:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: white;
}

.text-muted-premium {
    color: var(--color-text-tertiary);
    font-size: 0.875rem;
}

/* ===================================
   GRID VIEW
   =================================== */
.transactions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
}

.transaction-card-grid {
    padding: 1.5rem;
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-xl);
    transition: all var(--transition-base);
}

.transaction-card-grid:hover {
    background: var(--color-surface-hover);
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.transaction-card-header-grid {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--color-border);
}

.type-badge-grid {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 600;
}

.type-badge-grid.deposit {
    background: rgba(16, 185, 129, 0.15);
    color: var(--color-success);
}

.type-badge-grid.withdrawal {
    background: rgba(239, 68, 68, 0.15);
    color: var(--color-danger);
}

.type-badge-grid.staking {
    background: rgba(245, 158, 11, 0.15);
    color: var(--color-warning);
}

.type-badge-grid.reward {
    background: rgba(52, 211, 153, 0.15);
    color: #34d399;
}

.type-badge-grid.unstaking {
    background: rgba(99, 102, 241, 0.15);
    color: var(--color-info);
}

.transaction-amount-grid {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.transaction-amount-grid.positive {
    color: var(--color-success);
}

.transaction-amount-grid.negative {
    color: var(--color-text-primary);
}

.transaction-usd-grid {
    margin-bottom: 1rem;
}

.transaction-usd-grid .badge {
    background: rgba(59, 130, 246, 0.15);
    border: 1px solid rgba(59, 130, 246, 0.3);
    font-weight: 500;
}

.transaction-details-grid {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.detail-item-grid {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.detail-label-grid {
    font-size: 0.875rem;
    color: var(--color-text-tertiary);
}

.detail-value-grid {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-text-primary);
}

.btn-view-grid {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    padding: 0.75rem;
    background: var(--gradient-primary);
    border: none;
    border-radius: var(--radius-md);
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    text-decoration: none;
    transition: all var(--transition-base);
}

.btn-view-grid:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: white;
}

/* ===================================
   PAGINATION
   =================================== */
.pagination-premium {
    padding: 1.5rem;
    border-top: 1px solid var(--color-border);
}

.pagination-premium .pagination {
    margin: 0;
    justify-content: center;
}

.pagination-premium .page-link {
    background: var(--color-surface-light);
    border: 1px solid var(--color-border);
    color: var(--color-text-primary);
    border-radius: var(--radius-md);
    margin: 0 0.25rem;
    padding: 0.5rem 0.75rem;
    transition: all var(--transition-base);
}

.pagination-premium .page-item.active .page-link {
    background: var(--gradient-primary);
    border-color: transparent;
    color: white;
}

.pagination-premium .page-link:hover {
    background: var(--color-surface-hover);
    border-color: var(--color-primary);
    color: var(--color-primary);
}

/* ===================================
   EMPTY STATE
   =================================== */
.empty-state-premium {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
    text-align: center;
}

.empty-illustration-premium {
    position: relative;
    margin-bottom: 2rem;
}

.empty-icon-premium {
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
}

.empty-circles-premium {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
}

.circle-premium {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 2px solid var(--color-border);
    border-radius: 50%;
    opacity: 0.3;
    animation: pulse-circle 3s ease-in-out infinite;
}

.circle-1 {
    width: 140px;
    height: 140px;
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

@keyframes pulse-circle {
    0%, 100% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 0.3;
    }
    50% {
        transform: translate(-50%, -50%) scale(1.1);
        opacity: 0.1;
    }
}

.empty-title-premium {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-text-primary);
    margin: 0 0 0.75rem 0;
}

.empty-description-premium {
    font-size: 1rem;
    color: var(--color-text-secondary);
    line-height: 1.6;
    max-width: 500px;
    margin: 0 0 2rem 0;
}

.btn-empty-action-premium {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: var(--gradient-primary);
    border: none;
    border-radius: var(--radius-md);
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    text-decoration: none;
    transition: all var(--transition-base);
}

.btn-empty-action-premium:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    color: white;
}

/* ===================================
   RESPONSIVE
   =================================== */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    }
}

@media (max-width: 768px) {
    .welcome-header-enhanced {
        padding: 1.5rem;
    }
    
    .lead-description {
        font-size: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .filter-actions {
        flex-direction: column;
        width: 100%;
        padding-top: 0;
    }
    
    .btn-filter-apply,
    .btn-filter-reset,
    .btn-filter-export {
        width: 100%;
    }
    
    .transactions-card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .transactions-grid {
        grid-template-columns: 1fr;
    }
    
    .table-premium {
        font-size: 0.813rem;
    }
    
    .table-premium thead th,
    .table-premium tbody td {
        padding: 0.75rem;
    }
}

@media (max-width: 480px) {
    .display-5 {
        font-size: 1.75rem;
    }
    
    .header-badge-modern {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }
}
</style>

<script>
// Toggle Filters
function toggleFilters() {
    const filterSection = document.getElementById('filter-section');
    const toggleIcon = document.getElementById('filter-toggle-icon');
    const toggleBtn = document.querySelector('.btn-toggle-filters');
    
    filterSection.classList.toggle('active');
    toggleBtn.classList.toggle('active');
}

// Switch View (Table/Grid)
function switchView(view) {
    const tableView = document.getElementById('table-view');
    const gridView = document.getElementById('grid-view');
    const viewBtns = document.querySelectorAll('.view-btn');
    
    if (view === 'table') {
        tableView.classList.add('active');
        gridView.classList.remove('active');
    } else {
        gridView.classList.add('active');
        tableView.classList.remove('active');
    }
    
    viewBtns.forEach(btn => {
        if (btn.dataset.view === view) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
}

// Copy TXID to Clipboard
function copyTxid(txid) {
    navigator.clipboard.writeText(txid).then(() => {
        // Show success feedback
        const btn = event.currentTarget;
        const originalHTML = btn.innerHTML;
        
        btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Copied!';
        btn.style.borderColor = 'var(--color-success)';
        btn.style.color = 'var(--color-success)';
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.style.borderColor = '';
            btn.style.color = '';
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}

// Format number as currency
function formatCurrency(value) {
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(value);
}

// Auto-open filters if any filter is active
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const hasFilters = Array.from(urlParams.keys()).some(key => 
        ['type', 'coin_type', 'status', 'start_date', 'end_date'].includes(key) && urlParams.get(key)
    );
    
    if (hasFilters) {
        toggleFilters();
    }
});
</script>
@endsection