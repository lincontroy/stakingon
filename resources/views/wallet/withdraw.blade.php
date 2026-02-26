@extends('layouts.app')

@section('title', 'Withdraw ' . $wallet->coin_type)

@section('content')
<!-- Header -->
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-header">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('wallet.index') }}" class="btn-back" aria-label="Back to wallets">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="h2 fw-bold mb-1 gradient-text">
                                Withdraw {{ $wallet->coin_type }}
                            </h1>
                            <p class="text-muted mb-0 small">
                                <i class="bi bi-wallet2 me-2"></i>Send funds to external address
                            </p>
                        </div>
                    </div>
                    
                    @php
                        $usdRate = 0;
                        if(in_array($wallet->coin_type, ['STEEM', 'HIVE', 'USDT'])) {
                            $usdRate = (float) (env($wallet->coin_type.'USD', 
                                $wallet->coin_type == 'STEEM' ? 0.051 : 
                                ($wallet->coin_type == 'HIVE' ? 0.0674 : 1)
                            ));
                        }
                        $availableUsd = $wallet->available_balance * $usdRate;
                    @endphp
                    
                    <div class="wallet-info-badge">
                        <div class="balance-display">
                            <small>Available Balance</small>
                            <strong class="fs-4">{{ number_format($wallet->available_balance, 4) }} {{ $wallet->coin_type }}</strong>
                            @if($usdRate > 0)
                            <small class="d-block mt-1 small opacity-75">≈ ${{ number_format($availableUsd, 2) }} USD</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Withdrawal Card -->
            <div class="card withdraw-card-glass">
                <div class="card-header">
                    <div class="header-icon withdraw-icon">
                        <i class="bi bi-arrow-up-circle-fill"></i>
                    </div>
                    <div class="header-content">
                        <h5 class="mb-1">Withdraw {{ $wallet->coin_type }}</h5>
                        <p class="text-muted small mb-0">Transfer funds to any {{ $wallet->coin_type }} address</p>
                    </div>
                </div>
                
                <div class="card-body p-3 p-md-4">
                    <!-- Exchange Rate Info -->
                    @if($usdRate > 0)
                    <div class="exchange-rate-info mb-4">
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-currency-exchange text-success"></i>
                                <span class="small text-white-50">Current Rate:</span>
                            </div>
                            <strong class="text-white">1 {{ $wallet->coin_type }} = ${{ number_format($usdRate, $wallet->coin_type == 'USDT' ? 2 : 4) }}</strong>
                        </div>
                    </div>
                    @endif

                    <!-- Withdrawal Form -->
                    <form action="{{ route('wallet.withdraw', $wallet->coin_type) }}" method="POST" id="withdrawForm">
                        @csrf
                        
                        <!-- Step 1: Amount -->
                        <div class="withdraw-step active" id="step1">
                            <div class="step-header">
                                <div class="step-number">1</div>
                                <div class="step-title">
                                    <h6 class="fw-semibold">Enter Amount</h6>
                                    <p class="small mb-0">How much would you like to withdraw?</p>
                                </div>
                            </div>
                            
                            <div class="amount-section">
                                <div class="amount-input-container">
                                    <label for="amount" class="form-label">
                                        <i class="bi bi-cash-coin me-2"></i>Amount to Withdraw
                                    </label>
                                    
                                    <div class="amount-input-wrapper">
                                        <input type="number" 
                                               class="form-control-modern" 
                                               id="amount" 
                                               name="amount"
                                               step="0.001"
                                               min="0.001"
                                               max="{{ $wallet->available_balance }}"
                                               placeholder="0.000"
                                               required
                                               autofocus
                                               oninput="updateUsdValue()">
                                        <span class="input-suffix">{{ $wallet->coin_type }}</span>
                                    </div>
                                    
                                    @if($usdRate > 0)
                                    <div class="mt-2 text-end" id="usdAmountDisplay">
                                        <small class="text-white-50">≈ $0.00 USD</small>
                                    </div>
                                    @endif
                                    
                                    <div class="amount-controls">
                                        <div class="quick-amounts">
                                            <small class="text-white-50">Quick:</small>
                                            <div class="quick-buttons">
                                                <button type="button" class="btn-quick-amount" data-percent="25">25%</button>
                                                <button type="button" class="btn-quick-amount" data-percent="50">50%</button>
                                                <button type="button" class="btn-quick-amount" data-percent="75">75%</button>
                                                <button type="button" class="btn-quick-amount" data-percent="100">MAX</button>
                                            </div>
                                        </div>
                                        
                                        <div class="balance-info">
                                            <i class="bi bi-wallet2"></i>
                                            <span class="text-white-50">Available:</span>
                                            <strong class="text-white">{{ number_format($wallet->available_balance, 4) }}</strong>
                                            @if($usdRate > 0)
                                            <small class="text-white-50 d-none d-sm-inline">(${{ number_format($availableUsd, 2) }})</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="step-footer">
                                <button type="button" class="btn-next w-100" onclick="nextStep()">
                                    Continue: Enter Address <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Step 2: Address -->
                        <div class="withdraw-step" id="step2">
                            <div class="step-header">
                                <div class="step-number">2</div>
                                <div class="step-title">
                                    <h6 class="fw-semibold">Recipient Address</h6>
                                    <p class="small mb-0">Where should we send the funds?</p>
                                </div>
                            </div>
                            
                            <div class="address-section">
                                <div class="address-input-container">
                                    <label for="address" class="form-label">
                                        <i class="bi bi-person-badge me-2"></i>Destination Address
                                    </label>
                                    
                                    <div class="input-with-icon">
                                        <i class="bi bi-wallet2 input-icon"></i>
                                        <input type="text" 
                                               class="form-control-modern" 
                                               id="address" 
                                               name="address"
                                               placeholder="Enter {{ $wallet->coin_type }} address"
                                               required>
                                    </div>
                                    
                                    <!-- Memo Section - Hidden for USDT -->
                                    @if($wallet->coin_type !== 'USDT')
                                    <div class="memo-section">
                                        <label for="memo" class="form-label">
                                            <i class="bi bi-key me-2"></i>Memo (Optional)
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-chat-text input-icon"></i>
                                            <input type="text" 
                                                   class="form-control-modern" 
                                                   id="memo" 
                                                   name="memo"
                                                   placeholder="Enter memo/tag if required"
                                                   maxlength="255">
                                        </div>
                                        <small class="text-white-50 memo-hint">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Some exchanges require a memo/tag
                                        </small>
                                    </div>
                                    @else
                                    <input type="hidden" name="memo" value="">
                                    @endif
                                </div>
                            </div>
                            
                            <div class="step-footer d-flex flex-column flex-sm-row gap-2">
                                <button type="button" class="btn-prev" onclick="prevStep()">
                                    <i class="bi bi-arrow-left me-2"></i>Back
                                </button>
                                <button type="button" class="btn-next" onclick="nextStep()">
                                    Continue: Review <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Step 3: Review -->
                        <div class="withdraw-step" id="step3">
                            <div class="step-header">
                                <div class="step-number">3</div>
                                <div class="step-title">
                                    <h6 class="fw-semibold">Review & Confirm</h6>
                                    <p class="small mb-0">Verify all details before submitting</p>
                                </div>
                            </div>
                            
                            <div class="review-section">
                                <!-- Transaction Summary -->
                                <div class="summary-card">
                                    <div class="summary-header">
                                        <i class="bi bi-receipt"></i>
                                        <h6 class="mb-0">Transaction Summary</h6>
                                    </div>
                                    
                                    <div class="summary-content">
                                        <div class="summary-item">
                                            <span class="summary-label">Amount to Send</span>
                                            <span class="summary-value" id="reviewAmount">0.000 {{ $wallet->coin_type }}</span>
                                        </div>
                                        
                                        @if($usdRate > 0)
                                        <div class="summary-item">
                                            <span class="summary-label">USD Value</span>
                                            <span class="summary-value text-success" id="reviewUsdAmount">$0.00</span>
                                        </div>
                                        @endif
                                        
                                        <div class="summary-item">
                                            <span class="summary-label">Network Fee</span>
                                            <span class="summary-value" id="reviewFee">0.001 {{ $wallet->coin_type }}</span>
                                        </div>
                                        
                                        @if($usdRate > 0)
                                        <div class="summary-item">
                                            <span class="summary-label">Fee (USD)</span>
                                            <span class="summary-value text-warning" id="reviewFeeUsd">$0.00</span>
                                        </div>
                                        @endif
                                        
                                        <div class="summary-divider"></div>
                                        
                                        <div class="summary-item total">
                                            <span class="summary-label fw-bold">Total Deduction</span>
                                            <span class="summary-value text-danger fw-bold" id="reviewTotal">0.001 {{ $wallet->coin_type }}</span>
                                        </div>
                                        
                                        @if($usdRate > 0)
                                        <div class="summary-item">
                                            <span class="summary-label">Total (USD)</span>
                                            <span class="summary-value text-danger" id="reviewTotalUsd">$0.00</span>
                                        </div>
                                        @endif
                                        
                                        <div class="summary-item">
                                            <span class="summary-label">Recipient</span>
                                            <span class="summary-value address-value small" id="reviewAddress">Enter address</span>
                                        </div>
                                        
                                        @if($wallet->coin_type !== 'USDT')
                                        <div class="summary-item">
                                            <span class="summary-label">Memo</span>
                                            <span class="summary-value address-value small" id="reviewMemo">None</span>
                                        </div>
                                        @endif
                                        
                                        <div class="summary-item">
                                            <span class="summary-label">Processing</span>
                                            <span class="summary-value">3-5 minutes</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Security Check -->
                                <div class="security-check">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="confirmDetails" required>
                                        <label class="form-check-label small" for="confirmDetails">
                                            I confirm the 
                                            @if($wallet->coin_type !== 'USDT')
                                            <strong>address and memo</strong>
                                            @else
                                            <strong>address</strong>
                                            @endif
                                            are correct
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="confirmIrreversible" required>
                                        <label class="form-check-label small" for="confirmIrreversible">
                                            I understand this transaction cannot be reversed
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="confirmNetwork" required>
                                        <label class="form-check-label small" for="confirmNetwork">
                                            Network fees are non-refundable
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="step-footer d-flex flex-column flex-sm-row gap-2">
                                <button type="button" class="btn-prev" onclick="prevStep()">
                                    <i class="bi bi-arrow-left me-2"></i>Back
                                </button>
                                <button type="submit" class="btn-confirm" id="submitBtn">
                                    <i class="bi bi-send me-2"></i>Confirm Withdrawal
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Recent Withdrawals -->
            <div class="card activity-card glass mt-4">
                <div class="card-header">
                    <div class="activity-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h6 class="mb-0">Recent Withdrawals</h6>
                </div>
                <div class="card-body p-3 p-md-4">
                    @php
                        $recentWithdrawals = \App\Models\Transaction::where('user_id', Auth::id())
                            ->where('coin_type', $wallet->coin_type)
                            ->where('type', 'withdrawal')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($recentWithdrawals->count() > 0)
                        <div class="activity-list">
                            @foreach($recentWithdrawals as $tx)
                            @php
                                $txUsdValue = $tx->amount * $usdRate;
                            @endphp
                            <div class="activity-item">
                                <div class="activity-item-icon">
                                    <i class="bi bi-arrow-up-circle"></i>
                                </div>
                                <div class="activity-item-content">
                                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                                        <div>
                                            <strong>{{ number_format($tx->amount, 4) }} {{ $tx->coin_type }}</strong>
                                            @if($usdRate > 0)
                                            <small class="text-white-50 d-block">≈ ${{ number_format($txUsdValue, 2) }}</small>
                                            @endif
                                        </div>
                                        <span class="badge status-{{ $tx->status }}">{{ $tx->status }}</span>
                                    </div>
                                    <small class="text-muted d-block mt-1">{{ $tx->created_at->diffForHumans() }}</small>
                                    @if($tx->to_address)
                                    <small class="text-muted d-block text-truncate">
                                        To: {{ $tx->to_address }}
                                    </small>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-activity">
                            <div class="empty-icon">
                                <i class="bi bi-arrow-up-circle"></i>
                            </div>
                            <p class="mb-2">No withdrawal history</p>
                            <small class="text-muted">Your withdrawals will appear here</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Important Notice -->
            <div class="card notice-card glass mb-4">
                <div class="card-header">
                    <div class="notice-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <h6 class="mb-0">Important Notice</h6>
                </div>
                <div class="card-body p-3 p-md-4">
                    <div class="notice-item">
                        <div class="notice-item-icon warning">
                            <i class="bi bi-shield-exclamation"></i>
                        </div>
                        <div class="notice-item-content">
                            <h6>Irreversible</h6>
                            <p class="small mb-0">Cannot be reversed once confirmed</p>
                        </div>
                    </div>
                    
                    <div class="notice-item">
                        <div class="notice-item-icon info">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="notice-item-content">
                            <h6>Processing Time</h6>
                            <p class="small mb-0">10-30 minutes typically</p>
                        </div>
                    </div>
                    
                    <div class="notice-item">
                        <div class="notice-item-icon danger">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="notice-item-content">
                            <h6>Address Verification</h6>
                            <p class="small mb-0">Double-check the recipient address</p>
                        </div>
                    </div>
                    
                    @if($wallet->coin_type !== 'USDT')
                    <div class="notice-item">
                        <div class="notice-item-icon success">
                            <i class="bi bi-key"></i>
                        </div>
                        <div class="notice-item-content">
                            <h6>Memo/Tag Required</h6>
                            <p class="small mb-0">Some exchanges require a memo</p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="notice-item">
                        <div class="notice-item-icon success">
                            <i class="bi bi-currency-exchange"></i>
                        </div>
                        <div class="notice-item-content">
                            <h6>Network Fees</h6>
                            <p class="small mb-0">Standard fees apply, non-refundable</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Info -->
            <div class="card info-card glass mb-4">
                <div class="card-body p-3 p-md-4">
                    <h6 class="small text-uppercase text-white-50 mb-3">Withdrawal Info</h6>
                    
                    <div class="info-item">
                        <i class="bi bi-currency-exchange"></i>
                        <span>Coin:</span>
                        <strong>{{ $wallet->coin_type }}</strong>
                    </div>
                    
                    @if($usdRate > 0)
                    <div class="info-item">
                        <i class="bi bi-currency-dollar"></i>
                        <span>USD Rate:</span>
                        <strong>${{ number_format($usdRate, $wallet->coin_type == 'USDT' ? 2 : 4) }}</strong>
                    </div>
                    @endif
                    
                    <div class="info-item">
                        <i class="bi bi-cash-coin"></i>
                        <span>Minimum:</span>
                        <strong>0.001 {{ $wallet->coin_type }}</strong>
                    </div>
                    
                    @if($usdRate > 0)
                    <div class="info-item">
                        <i class="bi bi-currency-dollar"></i>
                        <span>Min (USD):</span>
                        <strong>${{ number_format(0.001 * $usdRate, 2) }}</strong>
                    </div>
                    @endif
                    
                    <div class="info-item">
                        <i class="bi bi-lightning-charge"></i>
                        <span>Network Fee:</span>
                        <strong>0.001 {{ $wallet->coin_type }}</strong>
                    </div>
                    
                    @if($usdRate > 0)
                    <div class="info-item">
                        <i class="bi bi-currency-dollar"></i>
                        <span>Fee (USD):</span>
                        <strong>${{ number_format(0.001 * $usdRate, 2) }}</strong>
                    </div>
                    @endif
                    
                    <div class="info-item">
                        <i class="bi bi-clock"></i>
                        <span>Processing:</span>
                        <strong>10-30 min</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Glass Design */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --danger-gradient: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
    --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    --success-gradient: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    --info-gradient: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
    --glass-bg: rgba(255, 255, 255, 0.05);
    --glass-border: rgba(255, 255, 255, 0.1);
    --glass-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    --card-bg: rgba(15, 23, 42, 0.7);
    --text-primary: #f8fafc;
    --text-secondary: #94a3b8;
}

/* Header Styles */
.welcome-header {
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: var(--glass-shadow);
}

@media (min-width: 768px) {
    .welcome-header {
        padding: 2rem;
    }
}

.gradient-text {
    background: linear-gradient(135deg, #fff 0%, #c7d2fe 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.btn-back {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    color: var(--text-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

@media (min-width: 768px) {
    .btn-back {
        width: 48px;
        height: 48px;
    }
}

.btn-back:hover {
    background: var(--primary-gradient);
    border-color: transparent;
    transform: translateY(-2px);
}

.wallet-info-badge {
    background: var(--danger-gradient);
    border-radius: 16px;
    padding: 1rem 1.5rem;
    color: white;
    box-shadow: 0 10px 30px rgba(239, 68, 68, 0.3);
}

.balance-display small {
    display: block;
    font-size: 0.75rem;
    opacity: 0.9;
    margin-bottom: 0.25rem;
    font-weight: 500;
}

/* Withdrawal Card */
.withdraw-card-glass {
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 24px;
    overflow: hidden;
    box-shadow: var(--glass-shadow);
}

.withdraw-card-glass .card-header {
    background: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid var(--glass-border);
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

@media (min-width: 768px) {
    .withdraw-card-glass .card-header {
        padding: 1.5rem 2rem;
    }
}

.header-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

@media (min-width: 768px) {
    .header-icon {
        width: 56px;
        height: 56px;
        font-size: 1.5rem;
    }
}

.withdraw-icon {
    background: var(--danger-gradient);
}

.exchange-rate-info {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.2);
    border-radius: 14px;
    padding: 1rem;
}

/* Step Navigation */
.withdraw-step {
    display: none;
}

.withdraw-step.active {
    display: block;
}

.step-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--glass-border);
}

@media (min-width: 768px) {
    .step-header {
        gap: 1.5rem;
    }
}

.step-number {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    background: var(--glass-bg);
    border: 2px solid var(--glass-border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    flex-shrink: 0;
}

@media (min-width: 768px) {
    .step-number {
        width: 64px;
        height: 64px;
        font-size: 1.5rem;
    }
}

/* Amount Section */
.amount-input-container {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
}

.amount-input-wrapper {
    position: relative;
    margin-bottom: 1rem;
}

.form-control-modern {
    background: var(--card-bg);
    border: 2px solid var(--glass-border);
    border-radius: 12px;
    padding: 1rem;
    color: var(--text-primary);
    font-size: 1.125rem;
    font-weight: 500;
    transition: all 0.3s ease;
    width: 100%;
}

.form-control-modern:focus {
    background: var(--glass-bg);
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
}

.input-suffix {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
    font-weight: 600;
}

.amount-controls {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

@media (min-width: 576px) {
    .amount-controls {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
}

.quick-amounts {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.quick-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-quick-amount {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    color: var(--text-primary);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-quick-amount:hover {
    background: var(--primary-gradient);
    border-color: transparent;
    transform: translateY(-2px);
}

.balance-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    flex-wrap: wrap;
}

/* Address Section */
.address-input-container {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
}

.input-with-icon {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
}

.input-with-icon .form-control-modern {
    padding-left: 3rem;
}

.memo-section {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--glass-border);
}

.memo-hint {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
    font-size: 0.75rem;
}

/* Step Navigation Buttons */
.step-footer {
    display: flex;
    gap: 1rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--glass-border);
}

.btn-prev, .btn-next, .btn-confirm {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
}

@media (min-width: 768px) {
    .btn-prev, .btn-next, .btn-confirm {
        padding: 1rem 2rem;
        font-size: 1rem;
    }
}

.btn-prev {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    color: var(--text-primary);
}

.btn-prev:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

.btn-next {
    background: var(--primary-gradient);
    color: white;
    flex: 1;
}

.btn-next:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
}

.btn-confirm {
    background: var(--danger-gradient);
    color: white;
    flex: 1;
}

.btn-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(239, 68, 68, 0.4);
}

/* Review Section */
.summary-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.summary-header {
    background: rgba(255, 255, 255, 0.05);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border-bottom: 1px solid var(--glass-border);
}

.summary-header i {
    color: var(--text-primary);
}

.summary-content {
    padding: 1.25rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-item.total {
    padding-top: 1rem;
    margin-top: 0.5rem;
    border-top: 2px solid var(--glass-border);
}

.summary-label {
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.summary-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    text-align: right;
}

.summary-value.address-value {
    font-family: monospace;
    max-width: 200px;
    word-break: break-all;
}

.summary-divider {
    height: 1px;
    background: var(--glass-border);
    margin: 0.75rem 0;
}

/* Security Check */
.security-check {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.25rem;
}

.form-check {
    margin-bottom: 1rem;
    padding-left: 2rem;
    position: relative;
}

.form-check:last-child {
    margin-bottom: 0;
}

.form-check-input {
    position: absolute;
    left: 0;
    top: 0.125rem;
    width: 1.25rem;
    height: 1.25rem;
    background-color: var(--card-bg);
    border: 2px solid var(--glass-border);
    border-radius: 6px;
    cursor: pointer;
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

/* Sidebar Cards */
.notice-card, .activity-card, .info-card {
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    overflow: hidden;
}

.notice-card .card-header, .activity-card .card-header {
    background: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid var(--glass-border);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.notice-icon, .activity-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.notice-icon {
    background: var(--warning-gradient);
}

.activity-icon {
    background: var(--primary-gradient);
}

.notice-item {
    display: flex;
    gap: 0.75rem;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.notice-item:last-child {
    border-bottom: none;
}

.notice-item-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.notice-item-icon.warning {
    background: var(--warning-gradient);
}

.notice-item-icon.info {
    background: var(--info-gradient);
}

.notice-item-icon.danger {
    background: var(--danger-gradient);
}

.notice-item-icon.success {
    background: var(--success-gradient);
}

.notice-item-content h6 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

/* Activity List */
.activity-item {
    display: flex;
    gap: 0.75rem;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    color: var(--text-primary);
    flex-shrink: 0;
}

.activity-item-content {
    flex: 1;
    min-width: 0;
}

/* Status Badges */
.status-completed,
.status-pending,
.status-failed {
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
}

.status-completed {
    background: var(--success-gradient);
    color: white;
}

.status-pending {
    background: var(--warning-gradient);
    color: white;
}

.status-failed {
    background: var(--danger-gradient);
    color: white;
}

/* Info Card */
.info-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.info-item:last-child {
    border-bottom: none;
}

.info-item i {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    font-size: 0.75rem;
    flex-shrink: 0;
}

.info-item span {
    font-size: 0.875rem;
    color: var(--text-secondary);
    flex: 1;
}

.info-item strong {
    font-size: 0.875rem;
    color: var(--text-primary);
    font-weight: 600;
}

/* Empty States */
.empty-activity {
    text-align: center;
    padding: 2rem 1rem;
}

.empty-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    font-size: 1.25rem;
    margin-bottom: 1rem;
}

/* Toast Notification */
.toast-notification {
    position: fixed;
    bottom: 1rem;
    left: 1rem;
    right: 1rem;
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    z-index: 9999;
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s ease;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    max-width: 400px;
    margin: 0 auto;
}

@media (min-width: 576px) {
    .toast-notification {
        left: auto;
        right: 2rem;
        bottom: 2rem;
        margin: 0;
    }
}

.toast-notification.show {
    transform: translateY(0);
    opacity: 1;
}

.toast-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.toast-notification.success .toast-icon {
    background: var(--success-gradient);
    color: white;
}

.toast-notification.error .toast-icon {
    background: var(--danger-gradient);
    color: white;
}

/* Utility Classes */
.text-white-50 {
    color: var(--text-secondary) !important;
}

.text-primary-gradient {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>

@push('scripts')
<script>
    const usdRate = {{ $usdRate }};
    const coinType = '{{ $wallet->coin_type }}';
    let currentStep = 1;
    
    function showStep(step) {
        document.querySelectorAll('.withdraw-step').forEach(el => {
            el.classList.remove('active');
        });
        document.getElementById(`step${step}`).classList.add('active');
        currentStep = step;
        updateReview();
    }
    
    function nextStep() {
        if (validateStep(currentStep) && currentStep < 3) {
            showStep(currentStep + 1);
        }
    }
    
    function prevStep() {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    }
    
    function validateStep(step) {
        if (step === 1) {
            const amount = parseFloat(document.getElementById('amount').value);
            const maxAmount = parseFloat('{{ $wallet->available_balance }}');
            
            if (!amount || amount <= 0) {
                showToast('Please enter a valid amount', 'error');
                return false;
            }
            if (amount > maxAmount) {
                showToast('Amount exceeds available balance', 'error');
                return false;
            }
            if (amount < 0.001) {
                showToast('Minimum withdrawal is 0.001 {{ $wallet->coin_type }}', 'error');
                return false;
            }
            return true;
        }
        
        if (step === 2) {
            const address = document.getElementById('address').value.trim();
            if (!address) {
                showToast('Please enter a recipient address', 'error');
                return false;
            }
            return true;
        }
        
        return true;
    }
    
    document.querySelectorAll('.btn-quick-amount').forEach(button => {
        button.addEventListener('click', function() {
            const percent = this.dataset.percent;
            const maxAmount = parseFloat('{{ $wallet->available_balance }}');
            let amount = percent === '100' ? maxAmount : maxAmount * (parseInt(percent) / 100);
            amount = Math.floor(amount * 1000) / 1000;
            document.getElementById('amount').value = amount;
            updateUsdValue();
            updateReview();
        });
    });
    
    function updateUsdValue() {
        if (usdRate > 0) {
            const amount = parseFloat(document.getElementById('amount').value) || 0;
            const usdValue = amount * usdRate;
            const display = document.getElementById('usdAmountDisplay');
            if (display) {
                display.innerHTML = `<small class="text-white-50">≈ $${usdValue.toFixed(2)} USD</small>`;
            }
        }
    }
    
    document.getElementById('amount').addEventListener('input', function() {
        updateUsdValue();
        updateReview();
    });
    
    document.getElementById('address').addEventListener('input', updateReview);
    
    @if($wallet->coin_type !== 'USDT')
    document.getElementById('memo').addEventListener('input', updateReview);
    @endif
    
    function updateReview() {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const fee = 0.001;
        const total = amount + fee;
        const address = document.getElementById('address').value || 'Enter address';
        
        document.getElementById('reviewAmount').textContent = amount.toFixed(3) + ' {{ $wallet->coin_type }}';
        document.getElementById('reviewFee').textContent = fee.toFixed(3) + ' {{ $wallet->coin_type }}';
        document.getElementById('reviewTotal').textContent = total.toFixed(3) + ' {{ $wallet->coin_type }}';
        document.getElementById('reviewAddress').textContent = address.length > 30 ? address.substring(0, 30) + '...' : address;
        
        if (usdRate > 0) {
            const amountUsd = amount * usdRate;
            const feeUsd = fee * usdRate;
            const totalUsd = total * usdRate;
            
            const reviewUsdAmount = document.getElementById('reviewUsdAmount');
            const reviewFeeUsd = document.getElementById('reviewFeeUsd');
            const reviewTotalUsd = document.getElementById('reviewTotalUsd');
            
            if (reviewUsdAmount) reviewUsdAmount.textContent = '$' + amountUsd.toFixed(2);
            if (reviewFeeUsd) reviewFeeUsd.textContent = '$' + feeUsd.toFixed(2);
            if (reviewTotalUsd) reviewTotalUsd.textContent = '$' + totalUsd.toFixed(2);
        }
        
        @if($wallet->coin_type !== 'USDT')
        const memo = document.getElementById('memo').value;
        const memoElement = document.getElementById('reviewMemo');
        if (memoElement) {
            memoElement.textContent = memo ? (memo.length > 20 ? memo.substring(0, 20) + '...' : memo) : 'None';
        }
        @endif
    }
    
    document.getElementById('withdrawForm').addEventListener('submit', function(e) {
        if (!validateStep(1) || !validateStep(2)) {
            e.preventDefault();
            showToast('Please complete all steps correctly', 'error');
            return;
        }
        
        const confirm1 = document.getElementById('confirmDetails');
        const confirm2 = document.getElementById('confirmIrreversible');
        const confirm3 = document.getElementById('confirmNetwork');
        
        if (!confirm1.checked || !confirm2.checked || !confirm3.checked) {
            e.preventDefault();
            showToast('Please confirm all security checks', 'error');
            return;
        }
        
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processing...';
        submitBtn.disabled = true;
    });
    
    function showToast(message, type = 'success') {
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) existingToast.remove();
        
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'}"></i>
            </div>
            <div class="toast-message">${message}</div>
        `;
        
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        showStep(1);
        updateReview();
        document.getElementById('amount').focus();
    });
</script>
@endpush
@endsection