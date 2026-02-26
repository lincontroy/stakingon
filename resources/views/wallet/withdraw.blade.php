@extends('layouts.app')

@section('title', 'Withdraw ' . $wallet->coin_type)

@section('content')
<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('wallet.index') }}" class="btn-back">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="display-6 fw-bold mb-2 gradient-text">
                            Withdraw {{ $wallet->coin_type }}
                        </h1>
                        <p class="text-muted mb-0">
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
                        <strong>{{ number_format($wallet->available_balance, 4) }} {{ $wallet->coin_type }}</strong>
                        @if($usdRate > 0)
                        <small class="d-block mt-1" style="font-size: 0.75rem; opacity: 0.9;">≈ ${{ number_format($availableUsd, 2) }} USD</small>
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
            <div class="card-header d-flex align-items-center">
                <div class="header-icon withdraw-icon">
                    <i class="bi bi-arrow-up-circle-fill"></i>
                </div>
                <div class="header-content">
                    <h5 class="mb-1">Withdraw {{ $wallet->coin_type }}</h5>
                    <p class="text-muted mb-0">Transfer funds to any {{ $wallet->coin_type }} address</p>
                </div>
            </div>
            <div class="card-body">
                
                <!-- Exchange Rate Info -->
                @if($usdRate > 0)
                <div class="exchange-rate-info mb-4 p-3" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 14px;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-currency-exchange text-success"></i>
                            <span class="small text-white-50">Current Exchange Rate:</span>
                        </div>
                        <strong class="text-white">1 {{ $wallet->coin_type }} = ${{ number_format($usdRate, $wallet->coin_type == 'USDT' ? 2 : 4) }} USD</strong>
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
                                <h6>Enter Amount</h6>
                                <p class="mb-0">How much would you like to withdraw?</p>
                            </div>
                        </div>
                        
                        <div class="amount-section">
                            <div class="amount-input-container">
                                <label for="amount" class="form-label">
                                    <i class="bi bi-cash-coin me-2"></i>Amount to Withdraw
                                </label>
                                <div class="input-with-suffix large">
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
                                        <small>Quick select:</small>
                                        <div class="quick-buttons">
                                            <button type="button" class="btn-quick-amount" data-percent="25">25%</button>
                                            <button type="button" class="btn-quick-amount" data-percent="50">50%</button>
                                            <button type="button" class="btn-quick-amount" data-percent="75">75%</button>
                                            <button type="button" class="btn-quick-amount" data-percent="100">MAX</button>
                                        </div>
                                    </div>
                                    <div class="balance-info">
                                        <i class="bi bi-wallet2 me-1"></i>
                                        <span>Available:</span>
                                        <strong>{{ number_format($wallet->available_balance, 4) }} {{ $wallet->coin_type }}</strong>
                                        @if($usdRate > 0)
                                        <small class="text-white-50 ms-2">(${{ number_format($availableUsd, 2) }})</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="step-footer">
                            <button type="button" class="btn-next" onclick="nextStep()">
                                Continue: Enter Address <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 2: Address -->
                    <div class="withdraw-step" id="step2">
                        <div class="step-header">
                            <div class="step-number">2</div>
                            <div class="step-title">
                                <h6>Recipient Address</h6>
                                <p class="mb-0">Where should we send the funds?</p>
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
                                           placeholder="Enter {{ $wallet->coin_type }} wallet address"
                                           required>
                                </div>
                                
                                <!-- Memo Section - Hidden for USDT -->
                                @if($wallet->coin_type !== 'USDT')
                                <div class="memo-section">
                                    <label for="memo" class="form-label">
                                        <i class="bi bi-key me-2"></i>Memo 
                                    </label>
                                    <div class="input-with-icon">
                                        <i class="bi bi-chat-text input-icon"></i>
                                        <input type="text" 
                                               class="form-control-modern" 
                                               id="memo" 
                                               name="memo"
                                               placeholder="Enter memo/tag "
                                               maxlength="255">
                                    </div>
                                    <small class="text-muted memo-hint">
                                        <i class="bi bi-info-circle me-1"></i>
                                        <span class="memo-hint-text">Some exchanges require a memo/tag for deposits. Check with your recipient.</span>
                                    </small>
                                </div>
                                @else
                                <!-- Hidden memo field for USDT to maintain form structure -->
                                <input type="hidden" name="memo" value="">
                                @endif
                            </div>
                        </div>
                        
                        <div class="step-footer">
                            <button type="button" class="btn-prev" onclick="prevStep()">
                                <i class="bi bi-arrow-left me-2"></i> Back
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
                                <h6>Review & Confirm</h6>
                                <p class="mb-0">Verify all details before submitting</p>
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
                                        <div class="summary-label">
                                            <i class="bi bi-cash-coin"></i>
                                            <span>Amount to Send</span>
                                        </div>
                                        <div class="summary-value" id="reviewAmount">
                                            0.000 {{ $wallet->coin_type }}
                                        </div>
                                    </div>
                                    
                                    @if($usdRate > 0)
                                    <div class="summary-item">
                                        <div class="summary-label">
                                            <i class="bi bi-currency-dollar"></i>
                                            <span>USD Value</span>
                                        </div>
                                        <div class="summary-value text-success" id="reviewUsdAmount">
                                            $0.00
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="summary-item">
                                        <div class="summary-label">
                                            <i class="bi bi-lightning-charge"></i>
                                            <span>Network Fee</span>
                                        </div>
                                        <div class="summary-value" id="reviewFee">
                                            0.001 {{ $wallet->coin_type }}
                                        </div>
                                    </div>
                                    
                                    @if($usdRate > 0)
                                    <div class="summary-item">
                                        <div class="summary-label">
                                            <i class="bi bi-currency-dollar"></i>
                                            <span>Fee (USD)</span>
                                        </div>
                                        <div class="summary-value text-warning" id="reviewFeeUsd">
                                            $0.00
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="summary-divider"></div>
                                    
                                    <div class="summary-item total">
                                        <div class="summary-label">
                                            <i class="bi bi-calculator"></i>
                                            <span>Total Deduction</span>
                                        </div>
                                        <div class="summary-value text-danger" id="reviewTotal">
                                            0.001 {{ $wallet->coin_type }}
                                        </div>
                                    </div>
                                    
                                    @if($usdRate > 0)
                                    <div class="summary-item">
                                        <div class="summary-label">
                                            <i class="bi bi-currency-dollar"></i>
                                            <span>Total (USD)</span>
                                        </div>
                                        <div class="summary-value text-danger" id="reviewTotalUsd">
                                            $0.00
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="summary-item">
                                        <div class="summary-label">
                                            <i class="bi bi-person-badge"></i>
                                            <span>Recipient</span>
                                        </div>
                                        <div class="summary-value address-value" id="reviewAddress">
                                            Enter address
                                        </div>
                                    </div>
                                    
                                    @if($wallet->coin_type !== 'USDT')
                                    <div class="summary-item">
                                        <div class="summary-label">
                                            <i class="bi bi-key"></i>
                                            <span>Memo</span>
                                        </div>
                                        <div class="summary-value address-value" id="reviewMemo">
                                            None
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="summary-item">
                                        <div class="summary-label">
                                            <i class="bi bi-clock"></i>
                                            <span>Processing Time</span>
                                        </div>
                                        <div class="summary-value">
                                            10-30 minutes
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Security Check -->
                            <div class="security-check">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="confirmDetails" required>
                                    <label class="form-check-label" for="confirmDetails">
                                        I have confirmed the 
                                        @if($wallet->coin_type !== 'USDT')
                                        <b>address and memo</b>
                                        @else
                                        <b>address</b>
                                        @endif
                                        are correct
                                    </label>
                                </div>
                                
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="confirmIrreversible" required>
                                    <label class="form-check-label" for="confirmIrreversible">
                                        I understand this transaction cannot be reversed
                                    </label>
                                </div>
                                
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="confirmNetwork" required>
                                    <label class="form-check-label" for="confirmNetwork">
                                        I understand network fees are non-refundable
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="step-footer">
                            <button type="button" class="btn-prev" onclick="prevStep()">
                                <i class="bi bi-arrow-left me-2"></i> Back
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
            <div class="card-body">
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
                                <i class="bi bi-arrow-up-circle text-primary"></i>
                            </div>
                            <div class="activity-item-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ number_format($tx->amount, 4) }} {{ $tx->coin_type }}</strong>
                                        @if($usdRate > 0)
                                        <small class="text-white-50 d-block">≈ ${{ number_format($txUsdValue, 2) }}</small>
                                        @endif
                                    </div>
                                    <span class="badge status-{{ $tx->status }}">{{ $tx->status }}</span>
                                </div>
                                <small class="text-muted">{{ $tx->created_at->diffForHumans() }}</small>
                                @if($tx->to_address)
                                <small class="text-muted d-block">
                                    To: {{ Str::limit($tx->to_address, 20) }}
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
            <div class="card-body">
                <div class="notice-item warning">
                    <div class="notice-item-icon">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>
                    <div class="notice-item-content">
                        <h6>Irreversible</h6>
                        <p class="mb-0">Withdrawals cannot be reversed once confirmed on the blockchain.</p>
                    </div>
                </div>
                
                <div class="notice-item info">
                    <div class="notice-item-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="notice-item-content">
                        <h6>Processing Time</h6>
                        <p class="mb-0">Withdrawals typically process within 10-30 minutes.</p>
                    </div>
                </div>
                
                <div class="notice-item danger">
                    <div class="notice-item-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="notice-item-content">
                        <h6>Address Verification</h6>
                        <p class="mb-0">Always double-check the recipient address before submitting.</p>
                    </div>
                </div>
                
                @if($wallet->coin_type !== 'USDT')
                <div class="notice-item success">
                    <div class="notice-item-icon">
                        <i class="bi bi-key"></i>
                    </div>
                    <div class="notice-item-content">
                        <h6>Memo/Tag Required</h6>
                        <p class="mb-0">Some exchanges require a memo. Check with your recipient.</p>
                    </div>
                </div>
                @endif
                
                <div class="notice-item success">
                    <div class="notice-item-icon">
                        <i class="bi bi-currency-exchange"></i>
                    </div>
                    <div class="notice-item-content">
                        <h6>Network Fees</h6>
                        <p class="mb-0">Standard network fees apply and are non-refundable.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Info -->
        <div class="card info-card glass mb-4">
            <div class="card-body">
                <h6 class="mb-3">Withdrawal Information</h6>
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
        
        <!-- Estimated Time -->
        <div class="card time-card glass">
            <div class="card-body">
                <div class="time-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <h6 class="mb-2">Estimated Timeline</h6>
                <div class="timeline">
                    <div class="timeline-item active">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <small>Submitted</small>
                            <p class="mb-0">Immediate</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <small>Processing</small>
                            <p class="mb-0">2-5 minutes</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <small>Blockchain Confirmation</small>
                            <p class="mb-0">10-30 minutes</p>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <small>Completed</small>
                            <p class="mb-0">30+ minutes</p>
                        </div>
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
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--glass-shadow);
}

.gradient-text {
    background: linear-gradient(135deg, #fff 0%, #c7d2fe 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.btn-back {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    color: var(--text-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
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

.balance-display strong {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
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
    padding: 1.5rem 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

.withdraw-icon {
    background: var(--danger-gradient);
}

.header-content h5 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.header-content p {
    font-size: 0.875rem;
    color: var(--text-secondary);
}

/* Step Navigation */
.withdraw-step {
    padding: 2rem;
    display: none;
}

.withdraw-step.active {
    display: block;
}

.step-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--glass-border);
}

.step-number {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    background: var(--glass-bg);
    border: 2px solid var(--glass-border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    flex-shrink: 0;
}

.step-title h6 {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.step-title p {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin: 0;
}

/* Amount Section */
.amount-section {
    max-width: 600px;
    margin: 0 auto 2rem;
}

.amount-input-container {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.5rem;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.input-with-suffix {
    position: relative;
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
}

.input-with-suffix.large .form-control-modern {
    font-size: 2rem;
    font-weight: 700;
    height: 80px;
    padding: 0 5rem 0 1.5rem;
}

.form-control-modern {
    background: var(--card-bg);
    border: 2px solid var(--glass-border);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
    flex: 1;
    width: 100%;
}

.form-control-modern:focus {
    background: var(--glass-bg);
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    outline: none;
}

.input-suffix {
    position: absolute;
    right: 1.5rem;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    pointer-events: none;
}

.input-with-suffix.large .input-suffix {
    font-size: 2rem;
}

.amount-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1rem;
}

.quick-amounts {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.quick-amounts small {
    font-size: 0.75rem;
    color: var(--text-secondary);
    font-weight: 500;
}

.quick-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-quick-amount {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 8px;
    padding: 0.5rem 1rem;
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

.balance-info i {
    color: var(--text-secondary);
}

.balance-info span {
    color: var(--text-secondary);
}

.balance-info strong {
    color: var(--text-primary);
    font-weight: 600;
}

/* Address Section */
.address-section {
    max-width: 600px;
    margin: 0 auto 2rem;
}

.address-input-container {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.5rem;
}

.input-with-icon {
    position: relative;
    margin-bottom: 1.5rem;
}

.input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
    font-size: 1rem;
}

.input-with-icon .form-control-modern {
    padding-left: 3rem;
}

.memo-section {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--glass-border);
}

.memo-hint {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.memo-hint-text {
    font-size: 0.75rem;
    color: var(--text-secondary);
}

/* Step Navigation Buttons */
.step-footer {
    display: flex;
    justify-content: space-between;
    padding-top: 2rem;
    border-top: 1px solid var(--glass-border);
}

.btn-prev, .btn-next, .btn-confirm {
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
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
}

.btn-next:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
}

.btn-confirm {
    background: var(--danger-gradient);
    color: white;
}

.btn-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(239, 68, 68, 0.4);
}

/* Review Section */
.review-section {
    max-width: 600px;
    margin: 0 auto 2rem;
}

.summary-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 2rem;
}

.summary-header {
    background: rgba(255, 255, 255, 0.05);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    border-bottom: 1px solid var(--glass-border);
}

.summary-header i {
    font-size: 1.25rem;
    color: var(--text-primary);
}

.summary-header h6 {
    margin: 0;
    color: var(--text-primary);
    font-weight: 600;
}

.summary-content {
    padding: 1.5rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-item.total {
    padding-top: 1.5rem;
    margin-top: 1.5rem;
    border-top: 2px solid var(--glass-border);
}

.summary-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.summary-label i {
    color: var(--text-secondary);
    font-size: 1rem;
}

.summary-label span {
    font-size: 0.875rem;
    color: var(--text-secondary);
    font-weight: 500;
}

.summary-value {
    font-size: 0.938rem;
    color: var(--text-primary);
    font-weight: 600;
    text-align: right;
}

.summary-value.address-value {
    font-family: 'JetBrains Mono', 'Fira Code', monospace;
    font-size: 0.813rem;
    word-break: break-all;
    max-width: 250px;
}

.summary-divider {
    height: 1px;
    background: var(--glass-border);
    margin: 1rem 0;
}

/* Security Check */
.security-check {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.5rem;
}

.form-check {
    margin-bottom: 1rem;
    padding-left: 2.5rem;
    position: relative;
}

.form-check:last-child {
    margin-bottom: 0;
}

.form-check-input {
    position: absolute;
    left: 0;
    top: 0.25rem;
    width: 1.25rem;
    height: 1.25rem;
    background-color: var(--card-bg);
    border: 2px solid var(--glass-border);
    border-radius: 6px;
    cursor: pointer;
}

.form-check-input:checked {
    background-color: var(--primary);
    border-color: var(--primary);
}

.form-check-input:focus {
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
}

.form-check-label {
    font-size: 0.875rem;
    color: var(--text-primary);
    line-height: 1.5;
    cursor: pointer;
}

/* Sidebar Cards */
.notice-card, .activity-card, .info-card, .time-card {
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    margin-bottom: 1.5rem;
}

.notice-card .card-header, .activity-card .card-header {
    background: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid var(--glass-border);
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.notice-icon, .activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.notice-icon {
    background: var(--warning-gradient);
}

.activity-icon {
    background: var(--primary-gradient);
}

.notice-item {
    display: flex;
    gap: 1rem;
    padding: 1.25rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.notice-item:last-child {
    border-bottom: none;
}

.notice-item-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    flex-shrink: 0;
}

.notice-item.warning .notice-item-icon {
    background: var(--warning-gradient);
}

.notice-item.info .notice-item-icon {
    background: var(--info-gradient);
}

.notice-item.danger .notice-item-icon {
    background: var(--danger-gradient);
}

.notice-item.success .notice-item-icon {
    background: var(--success-gradient);
}

.notice-item-content h6 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.notice-item-content p {
    font-size: 0.813rem;
    color: var(--text-secondary);
    line-height: 1.5;
    margin: 0;
}

/* Activity List */
.activity-list {
    margin-bottom: 1.5rem;
}

.activity-item {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: var(--text-primary);
    flex-shrink: 0;
}

.activity-item-content {
    flex: 1;
    min-width: 0;
}

.activity-item-content strong {
    font-size: 0.938rem;
    color: var(--text-primary);
    display: block;
}

.activity-item-content small {
    font-size: 0.75rem;
    color: var(--text-secondary);
    display: block;
    margin-top: 0.25rem;
}

.status-completed {
    background: var(--success-gradient);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-pending {
    background: var(--warning-gradient);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-failed {
    background: var(--danger-gradient);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.empty-activity {
    text-align: center;
    padding: 2rem 1rem;
}

.empty-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.empty-activity p {
    color: var(--text-secondary);
    margin-bottom: 0.5rem;
}

/* Info Card */
.info-card .card-body {
    padding: 1.5rem;
}

.info-card h6 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1.5rem;
}

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
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    font-size: 0.875rem;
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

/* Timeline Card */
.time-card .card-body {
    padding: 1.5rem;
    text-align: center;
}

.time-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: var(--primary-gradient);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.time-card h6 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1.5rem;
}

.timeline {
    position: relative;
    padding-left: 1rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--glass-border);
}

.timeline-item {
    position: relative;
    padding-left: 2rem;
    margin-bottom: 1.5rem;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-dot {
    position: absolute;
    left: -0.375rem;
    top: 0.25rem;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    background: var(--glass-bg);
    border: 2px solid var(--glass-border);
    z-index: 1;
}

.timeline-item.active .timeline-dot {
    background: var(--primary);
    border-color: var(--primary);
}

.timeline-content small {
    display: block;
    font-size: 0.75rem;
    color: var(--text-secondary);
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.timeline-content p {
    font-size: 0.813rem;
    color: var(--text-primary);
    margin: 0;
}

/* Toast Notification */
.toast-notification {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    z-index: 9999;
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s ease;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    max-width: 300px;
}

.toast-notification.show {
    transform: translateY(0);
    opacity: 1;
}

.toast-notification.success {
    border-color: rgba(16, 185, 129, 0.3);
    background: rgba(16, 185, 129, 0.05);
}

.toast-notification.error {
    border-color: rgba(239, 68, 68, 0.3);
    background: rgba(239, 68, 68, 0.05);
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

.toast-message {
    font-size: 0.875rem;
    color: var(--text-primary);
    line-height: 1.4;
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-header .d-flex {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
    }
    
    .step-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .step-number {
        width: 56px;
        height: 56px;
        font-size: 1.25rem;
    }
    
    .amount-controls {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .quick-amounts {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .quick-buttons {
        width: 100%;
    }
    
    .btn-quick-amount {
        flex: 1;
        text-align: center;
    }
    
    .step-footer {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn-prev, .btn-next, .btn-confirm {
        width: 100%;
        text-align: center;
    }
    
    .summary-value.address-value {
        max-width: 150px;
        font-size: 0.75rem;
    }
    
    .timeline {
        padding-left: 0.5rem;
    }
    
    .timeline-item {
        padding-left: 1.5rem;
    }
    
    .toast-notification {
        left: 1rem;
        right: 1rem;
        bottom: 1rem;
        max-width: none;
    }
}
</style>

@push('scripts')
<script>
    // USD Rate from PHP
    const usdRate = {{ $usdRate }};
    const coinType = '{{ $wallet->coin_type }}';
    
    // Step Navigation
    let currentStep = 1;
    
    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.withdraw-step').forEach(el => {
            el.classList.remove('active');
        });
        
        // Show current step
        document.getElementById(`step${step}`).classList.add('active');
        currentStep = step;
        
        // Update form validation
        updateReview();
    }
    
    function nextStep() {
        // Validate current step before proceeding
        if (validateStep(currentStep)) {
            if (currentStep < 3) {
                showStep(currentStep + 1);
            }
        }
    }
    
    function prevStep() {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    }
    
    // Validate step
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
            
            // Basic address validation for Hive/Steem
            if (address.includes('@')) {
                // Hive/Steem username validation
                const username = address.replace('@', '');
                if (!/^[a-z0-9\.-]+$/.test(username)) {
                    showToast('Invalid Hive/Steem address format', 'error');
                    return false;
                }
            }
            
            return true;
        }
        
        return true;
    }
    
    // Quick amount buttons
    document.querySelectorAll('.btn-quick-amount').forEach(button => {
        button.addEventListener('click', function() {
            const percent = this.dataset.percent;
            const maxAmount = parseFloat('{{ $wallet->available_balance }}');
            let amount = 0;
            
            if (percent === '100') {
                amount = maxAmount;
            } else {
                amount = maxAmount * (parseInt(percent) / 100);
            }
            
            // Round to 3 decimal places
            amount = Math.floor(amount * 1000) / 1000;
            
            document.getElementById('amount').value = amount;
            updateUsdValue();
            updateReview();
        });
    });
    
    // USD Value Update
    function updateUsdValue() {
        if (usdRate > 0) {
            const amount = parseFloat(document.getElementById('amount').value) || 0;
            const usdValue = amount * usdRate;
            document.getElementById('usdAmountDisplay').innerHTML = 
                `<small class="text-white-50">≈ $${usdValue.toFixed(2)} USD</small>`;
        }
    }
    
    // Real-time amount updates
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
        
        // Update review section
        document.getElementById('reviewAmount').textContent = 
            amount.toFixed(3) + ' {{ $wallet->coin_type }}';
        document.getElementById('reviewFee').textContent = 
            fee.toFixed(3) + ' {{ $wallet->coin_type }}';
        document.getElementById('reviewTotal').textContent = 
            total.toFixed(3) + ' {{ $wallet->coin_type }}';
        document.getElementById('reviewAddress').textContent = 
            address.length > 30 ? address.substring(0, 30) + '...' : address;
        
        // Update USD values if rate exists
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
    
    // Form submission
    document.getElementById('withdrawForm').addEventListener('submit', function(e) {
        // Validate all steps
        if (!validateStep(1) || !validateStep(2)) {
            e.preventDefault();
            showToast('Please complete all steps correctly', 'error');
            return;
        }
        
        // Check security confirmations
        const confirm1 = document.getElementById('confirmDetails');
        const confirm2 = document.getElementById('confirmIrreversible');
        const confirm3 = document.getElementById('confirmNetwork');
        
        if (!confirm1.checked || !confirm2.checked || !confirm3.checked) {
            e.preventDefault();
            showToast('Please confirm all security checks', 'error');
            return;
        }
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processing...';
        submitBtn.disabled = true;
    });
    
    // Toast Notification
    function showToast(message, type = 'success') {
        // Remove existing toast
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) {
            existingToast.remove();
        }
        
        // Create toast
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'}"></i>
            </div>
            <div class="toast-message">${message}</div>
        `;
        
        document.body.appendChild(toast);
        
        // Show toast
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        // Hide after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        showStep(1);
        updateReview();
        
        // Auto-focus amount input
        document.getElementById('amount').focus();
    });
</script>
@endpush
@endsection