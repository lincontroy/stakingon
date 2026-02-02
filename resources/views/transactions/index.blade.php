@extends('layouts.app')

@section('title', 'Transaction History')

@section('content')
<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-6 fw-bold mb-2" style="background: linear-gradient(135deg, #ffffff 0%, #b4b4c8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        <i class="bi bi-clock-history me-2"></i>Transaction History
                    </h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-filter-circle me-2"></i>View all your deposits, withdrawals, and staking transactions
                    </p>
                </div>
                <div class="transaction-stats">
                    <div class="stat-badge">
                        <small>Total</small>
                        <strong>{{ $transactions->total() }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters Card -->
<div class="card filter-card-modern mb-4">
    <div class="card-body">
        <div class="filter-header mb-4">
            <div class="filter-icon">
                <i class="bi bi-funnel-fill"></i>
            </div>
            <h6 class="mb-0">Filter Transactions</h6>
        </div>
        
        <form method="GET" action="{{ route('transactions.index') }}">
            <div class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <div class="input-modern">
                        <label for="type" class="form-label">
                            <i class="bi bi-tags me-1"></i>Type
                        </label>
                        <select class="form-select-modern" id="type" name="type">
                            <option value="">All Types</option>
                            <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                            <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                            <option value="staking" {{ request('type') == 'staking' ? 'selected' : '' }}>Staking</option>
                            <option value="reward" {{ request('type') == 'reward' ? 'selected' : '' }}>Reward</option>
                            <option value="unstaking" {{ request('type') == 'unstaking' ? 'selected' : '' }}>Unstaking</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="input-modern">
                        <label for="coin_type" class="form-label">
                            <i class="bi bi-currency-bitcoin me-1"></i>Coin
                        </label>
                        <select class="form-select-modern" id="coin_type" name="coin_type">
                            <option value="">All Coins</option>
                            <option value="HIVE" {{ request('coin_type') == 'HIVE' ? 'selected' : '' }}>HIVE</option>
                            <option value="STEEM" {{ request('coin_type') == 'STEEM' ? 'selected' : '' }}>STEEM</option>
                            <option value="BTC" {{ request('coin_type') == 'BTC' ? 'selected' : '' }}>BTC</option>
                            <option value="ETH" {{ request('coin_type') == 'ETH' ? 'selected' : '' }}>ETH</option>
                            <option value="BNB" {{ request('coin_type') == 'BNB' ? 'selected' : '' }}>BNB</option>
                            <option value="SOL" {{ request('coin_type') == 'SOL' ? 'selected' : '' }}>SOL</option>
                            <option value="ADA" {{ request('coin_type') == 'ADA' ? 'selected' : '' }}>ADA</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="input-modern">
                        <label for="status" class="form-label">
                            <i class="bi bi-check-circle me-1"></i>Status
                        </label>
                        <select class="form-select-modern" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="input-modern">
                        <label for="start_date" class="form-label">
                            <i class="bi bi-calendar-range me-1"></i>Start Date
                        </label>
                        <input type="date" class="form-control-modern" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="input-modern">
                        <label for="end_date" class="form-label">
                            <i class="bi bi-calendar-check me-1"></i>End Date
                        </label>
                        <input type="date" class="form-control-modern" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                </div>
                
                <div class="col-md-6 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel me-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-clockwise me-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Transactions Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-1">
                <i class="bi bi-receipt me-2"></i>All Transactions
            </h5>
            <small class="text-muted">Showing {{ $transactions->firstItem() ?? 0 }}-{{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }} transactions</small>
        </div>
        <div class="export-actions">
            <button class="btn btn-sm btn-outline-primary">
                <i class="bi bi-download me-1"></i>Export
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        @if($transactions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0 modern-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Coin</th>
                            <th>Amount</th>
                            <th>Fee</th>
                            <th>From/To</th>
                            <th>Status</th>
                            <th>TXID</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr class="transaction-row">
                            <td>
                                <div class="transaction-time">
                                    <div class="fw-semibold">{{ $transaction->created_at->format('M d') }}</div>
                                    <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="transaction-type">
                                    <div class="type-icon {{ $transaction->type }}">
                                        <i class="bi {{ $transaction->type_icon }}"></i>
                                    </div>
                                    <div class="type-label">{{ ucfirst($transaction->type) }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="coin-badge">
                                    <span class="coin-icon">
                                        <i class="bi bi-currency-bitcoin"></i>
                                    </span>
                                    <span class="coin-label">{{ $transaction->coin_type }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="transaction-amount">
                                    <div class="amount-value {{ $transaction->type == 'deposit' || $transaction->type == 'reward' ? 'text-success' : 'text-primary' }}">
                                        @if(in_array($transaction->type, ['deposit', 'reward']))
                                        <i class="bi bi-plus-circle me-1"></i>
                                        @else
                                        <i class="bi bi-dash-circle me-1"></i>
                                        @endif
                                        {{ number_format($transaction->amount, 8) }}
                                    </div>
                                    <small class="text-muted">{{ $transaction->coin_type }}</small>
                                </div>
                            </td>
                            <td>
                                @if($transaction->fee > 0)
                                <div class="transaction-fee">
                                    <div class="fee-value">{{ number_format($transaction->fee, 8) }}</div>
                                    <small class="text-muted">Fee</small>
                                </div>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="transaction-address">
                                    <small class="text-muted d-block">
                                        @if($transaction->type == 'deposit')
                                            To:
                                        @elseif($transaction->type == 'withdrawal')
                                            To:
                                        @else
                                            System
                                        @endif
                                    </small>
                                    <div class="address-truncated">
                                        {{ substr($transaction->to_address ?? 'System', 0, 10) }}...
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge {{ $transaction->status }}">
                                    <i class="bi bi-circle-fill me-1"></i>
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="txid-truncated" title="{{ $transaction->txid }}">
                                    {{ substr($transaction->txid, 0, 12) }}...
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('transactions.show', $transaction->id) }}" 
                                   class="btn btn-sm btn-outline-primary view-btn">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($transactions->hasPages())
            <div class="pagination-modern">
                {{ $transactions->links('pagination::bootstrap-5') }}
            </div>
            @endif
            
        @else
            <div class="empty-state py-5">
                <div class="empty-icon">
                    <i class="bi bi-receipt"></i>
                </div>
                <h5 class="mt-3 mb-2">No Transactions Found</h5>
                <p class="text-muted mb-4">No transactions match your current filters</p>
                <a href="{{ route('transactions.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                </a>
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

.transaction-stats {
    display: flex;
    gap: 1rem;
}

.stat-badge {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 0.75rem 1.25rem;
    text-align: center;
    min-width: 80px;
}

.stat-badge small {
    display: block;
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-bottom: 0.25rem;
}

.stat-badge strong {
    display: block;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
}

/* Filter Card */
.filter-card-modern {
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    overflow: hidden;
}

.filter-header {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.filter-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

/* Modern Input & Select */
.input-modern {
    margin-bottom: 0;
}

.input-modern .form-label {
    font-size: 0.813rem;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.form-select-modern,
.form-control-modern {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    color: var(--text-primary);
    font-size: 0.875rem;
    transition: all 0.3s ease;
    width: 100%;
}

.form-select-modern:focus,
.form-control-modern:focus {
    background: var(--card-bg);
    border-color: rgba(255, 255, 255, 0.3);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    color: var(--text-primary);
}

/* Modern Table */
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

/* Transaction Row Components */
.transaction-time {
    min-width: 70px;
}

.transaction-type {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.type-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.type-icon.deposit {
    background: rgba(0, 255, 136, 0.15);
    color: var(--success);
}

.type-icon.withdrawal {
    background: rgba(255, 51, 102, 0.15);
    color: var(--danger);
}

.type-icon.staking {
    background: rgba(255, 170, 0, 0.15);
    color: var(--warning);
}

.type-icon.reward {
    background: rgba(67, 233, 123, 0.15);
    color: #43e97b;
}

.type-icon.unstaking {
    background: rgba(102, 126, 234, 0.15);
    color: #667eea;
}

.type-label {
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text-primary);
}

.coin-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 8px;
}

.coin-icon {
    color: var(--warning);
}

.coin-label {
    font-size: 0.813rem;
    font-weight: 600;
    color: var(--text-primary);
}

.transaction-amount {
    min-width: 120px;
}

.amount-value {
    font-size: 0.938rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.amount-value.text-success {
    color: var(--success) !important;
}

.transaction-fee {
    min-width: 80px;
}

.fee-value {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
}

.transaction-address {
    min-width: 120px;
}

.address-truncated {
    font-family: 'Courier New', monospace;
    font-size: 0.813rem;
    color: var(--text-primary);
    word-break: break-all;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.completed {
    background: rgba(0, 255, 136, 0.15);
    color: var(--success);
    border: 1px solid rgba(0, 255, 136, 0.3);
}

.status-badge.pending {
    background: rgba(255, 170, 0, 0.15);
    color: var(--warning);
    border: 1px solid rgba(255, 170, 0, 0.3);
}

.status-badge.failed {
    background: rgba(255, 51, 102, 0.15);
    color: var(--danger);
    border: 1px solid rgba(255, 51, 102, 0.3);
}

.status-badge.cancelled {
    background: rgba(108, 117, 125, 0.15);
    color: var(--secondary);
    border: 1px solid rgba(108, 117, 125, 0.3);
}

.txid-truncated {
    font-family: 'Courier New', monospace;
    font-size: 0.813rem;
    color: var(--text-muted);
    cursor: help;
    max-width: 120px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.view-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

/* Pagination Modern */
.pagination-modern {
    padding: 1.5rem;
    border-top: 1px solid var(--glass-border);
}

.pagination-modern .pagination {
    margin-bottom: 0;
    justify-content: center;
}

.pagination-modern .page-item .page-link {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    color: var(--text-primary);
    border-radius: 8px;
    margin: 0 0.25rem;
    transition: all 0.3s ease;
}

.pagination-modern .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    color: white;
}

.pagination-modern .page-item .page-link:hover {
    background: var(--card-hover);
    border-color: rgba(255, 255, 255, 0.2);
}

/* Empty State */
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

/* Export Actions */
.export-actions {
    display: flex;
    gap: 0.5rem;
}

/* Responsive */
@media (max-width: 992px) {
    .transaction-type {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
    
    .type-label {
        font-size: 0.75rem;
    }
    
    .coin-badge {
        padding: 0.375rem 0.5rem;
        gap: 0.25rem;
    }
    
    .modern-table thead th,
    .modern-table tbody td {
        padding: 0.75rem;
    }
}

@media (max-width: 768px) {
    .welcome-header .d-flex {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .transaction-stats {
        justify-content: center;
    }
    
    .filter-header {
        justify-content: center;
        text-align: center;
    }
    
    .modern-table {
        font-size: 0.813rem;
    }
    
    .transaction-time {
        min-width: 60px;
    }
    
    .amount-value {
        font-size: 0.875rem;
    }
}
</style>
@endsection