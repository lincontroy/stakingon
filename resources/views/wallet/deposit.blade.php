@extends('layouts.app')

@section('title', 'Deposit ' . $wallet->coin_type)

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
                            Deposit {{ $wallet->coin_type }}
                        </h1>
                        <p class="text-muted mb-0">
                            <i class="bi bi-wallet2 me-2"></i>Send funds to your wallet
                        </p>
                    </div>
                </div>
                <div class="wallet-info-badge">
                    <div class="balance-display">
                        <small>Current Balance</small>
                        <strong>{{ number_format($wallet->balance, 3) }} {{ $wallet->coin_type }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Deposit Card -->
        <div class="card deposit-card-glass mb-4">
            <div class="card-header d-flex align-items-center">
                <div class="header-icon deposit-icon">
                    <i class="bi bi-arrow-down-circle-fill"></i>
                </div>
                <div class="header-content">
                    <h5 class="mb-1">Deposit {{ $wallet->coin_type }}</h5>
                    <p class="text-muted mb-0">Follow these simple steps to add funds</p>
                </div>
            </div>
            <div class="card-body">
                
                <!-- Step 1: Copy Details -->
                <div class="deposit-step active" id="step1">
                    <div class="step-header">
                        <div class="step-number">1</div>
                        <div class="step-title">
                            <h6>Copy Deposit Details</h6>
                            <p class="mb-0">Save these details for your transfer</p>
                        </div>
                    </div>
                    
                    <div class="details-grid">
                        <!-- Deposit Address -->
                        <div class="detail-card">
                            <div class="detail-header">
                                <div class="detail-icon">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <div class="detail-label">
                                    <small>Deposit Account</small>
                                    <h6>Official Account</h6>
                                </div>
                            </div>
                            <div class="detail-value">
                                <div class="copy-field" id="depositAccountField">
                                    <code class="copy-text">
                                        @if($wallet->coin_type === 'HIVE')
                                            @stakeonhive
                                        @elseif($wallet->coin_type === 'STEEM')
                                            @stakeonsteem
                                        @endif
                                    </code>
                                    <button class="btn-copy" onclick="copyToClipboard('depositAccountField')">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- MEMO -->
                        <div class="detail-card important">
                            <div class="detail-header">
                                <div class="detail-icon">
                                    <i class="bi bi-key-fill"></i>
                                </div>
                                <div class="detail-label">
                                    <small>MEMO (Required)</small>
                                    <h6>Your Unique ID</h6>
                                </div>
                                <span class="badge bg-warning">Required</span>
                            </div>
                            <div class="detail-value">
                                <div class="copy-field" id="memoField">
                                    <code class="copy-text memo-text">MEMO-{{ strtoupper(\Illuminate\Support\Str::random(8)) }}-{{ Auth::id() }}</code>
                                    <button class="btn-copy" onclick="copyToClipboard('memoField')">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                    <button class="btn-refresh" onclick="generateNewMemo()">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                </div>
                                <small class="text-warning mt-2 d-block">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    You MUST include this memo
                                </small>
                            </div>
                        </div>
                        
                        <!-- Your Wallet -->
                        <div class="detail-card">
                            <div class="detail-header">
                                <div class="detail-icon">
                                    <i class="bi bi-wallet2"></i>
                                </div>
                                <div class="detail-label">
                                    <small>Your Wallet</small>
                                    <h6>Receiving Address</h6>
                                </div>
                            </div>
                            <div class="detail-value">
                                <div class="copy-field" id="walletField">
                                    <code class="copy-text">{{ $wallet->address }}</code>
                                    <button class="btn-copy" onclick="copyToClipboard('walletField')">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Copy All Button -->
                        <div class="detail-card action-card">
                            <div class="detail-content">
                                <div class="action-icon">
                                    <i class="bi bi-clipboard-check"></i>
                                </div>
                                <div class="action-text">
                                    <h6>Copy All Details</h6>
                                    <p class="mb-0">Copy everything with one click</p>
                                </div>
                                <button class="btn-action" onclick="copyAllDetails()">
                                    Copy All
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="step-footer">
                        <button class="btn-next" onclick="nextStep()">
                            Next: Send Funds <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 2: Send Funds -->
                <div class="deposit-step" id="step2">
                    <div class="step-header">
                        <div class="step-number">2</div>
                        <div class="step-title">
                            <h6>Send {{ $wallet->coin_type }}</h6>
                            <p class="mb-0">Transfer from your external wallet</p>
                        </div>
                    </div>
                    
                    <div class="transfer-guide">
                        <div class="guide-card">
                            <div class="guide-icon">
                                <i class="bi bi-arrow-right-circle"></i>
                            </div>
                            <div class="guide-content">
                                <h6>Send to Deposit Account</h6>
                                <p class="mb-2">Open your external wallet (Hive Keychain, Hive wallet, etc.)</p>
                                <code class="guide-address">
                                    @if($wallet->coin_type === 'HIVE')
                                        @stakeonhive
                                    @elseif($wallet->coin_type === 'STEEM')
                                        @stakeonsteem
                                    @endif
                                </code>
                            </div>
                        </div>
                        
                        <div class="guide-arrow">
                            <i class="bi bi-arrow-down"></i>
                        </div>
                        
                        <div class="guide-card important">
                            <div class="guide-icon">
                                <i class="bi bi-chat-square-text"></i>
                            </div>
                            <div class="guide-content">
                                <h6>Include the MEMO</h6>
                                <p class="mb-2">Paste the memo in the memo/message field</p>
                                <code class="guide-memo">MEMO-****-{{ Auth::id() }}</code>
                            </div>
                        </div>
                        
                        <div class="guide-arrow">
                            <i class="bi bi-arrow-down"></i>
                        </div>
                        
                        <div class="guide-card success">
                            <div class="guide-icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="guide-content">
                                <h6>Confirm Transfer</h6>
                                <p class="mb-0">Review details and confirm the transaction</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="step-footer">
                        <button class="btn-prev" onclick="prevStep()">
                            <i class="bi bi-arrow-left me-2"></i> Back
                        </button>
                        <button class="btn-next" onclick="nextStep()">
                            Next: Wait for Confirmation <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Step 3: Confirmation -->
                <div class="deposit-step" id="step3">
                    <div class="step-header">
                        <div class="step-number">3</div>
                        <div class="step-title">
                            <h6>Wait for Confirmation</h6>
                            <p class="mb-0">Funds will appear in your wallet automatically</p>
                        </div>
                    </div>
                    
                    <div class="confirmation-section">
                        <div class="confirmation-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="confirmation-content">
                            <h4 class="mb-3">Processing Your Deposit</h4>
                            <div class="progress-container">
                                <div class="progress-bar" id="progressBar"></div>
                                <div class="progress-labels">
                                    <span>Processing</span>
                                    <span>1-3 minutes</span>
                                    <span>Complete</span>
                                </div>
                            </div>
                            
                            <div class="estimated-time">
                                <i class="bi bi-clock me-2"></i>
                                <span>Estimated time: 1-3 minutes</span>
                            </div>
                            
                            <div class="success-message">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>Funds will appear in your wallet automatically</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="completion-actions">
                        <a href="{{ route('wallet.index') }}" class="btn-back-to-wallet">
                            <i class="bi bi-wallet2 me-2"></i>Back to Wallets
                        </a>
                        <button class="btn-view-transactions" onclick="viewTransactions()">
                            <i class="bi bi-list-check me-2"></i>View Transactions
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card quick-action-card">
                    <div class="card-body">
                        <div class="quick-action-icon">
                            <i class="bi bi-download"></i>
                        </div>
                        <h6 class="mb-2">Need Help?</h6>
                        <p class="text-muted small mb-3">Download detailed deposit guide</p>
                        <button class="btn-quick-action">
                            Download Guide
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card quick-action-card">
                    <div class="card-body">
                        <div class="quick-action-icon">
                            <i class="bi bi-chat-dots"></i>
                        </div>
                        <h6 class="mb-2">Support</h6>
                        <p class="text-muted small mb-3">Contact support for deposit issues</p>
                        <button class="btn-quick-action" onclick="contactSupport()">
                            Contact Support
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Important Notice -->
        <div class="card notice-card glass">
            <div class="card-header">
                <div class="notice-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h6 class="mb-0">Important Notice</h6>
            </div>
            <div class="card-body">
                <div class="notice-item warning">
                    <div class="notice-item-icon">
                        <i class="bi bi-key"></i>
                    </div>
                    <div class="notice-item-content">
                        <h6>MEMO Required</h6>
                        <p class="mb-0">Without the correct memo, we cannot identify your deposit and funds may be lost permanently.</p>
                    </div>
                </div>
                
                <div class="notice-item info">
                    <div class="notice-item-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="notice-item-content">
                        <h6>Processing Time</h6>
                        <p class="mb-0">Deposits are processed within 1-3 minutes after blockchain confirmation.</p>
                    </div>
                </div>
                
                <div class="notice-item success">
                    <div class="notice-item-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="notice-item-content">
                        <h6>Secure & Safe</h6>
                        <p class="mb-0">Your funds are secured with enterprise-grade security measures.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="card activity-card glass">
            <div class="card-header">
                <div class="activity-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h6 class="mb-0">Recent Deposits</h6>
            </div>
            <div class="card-body">
                @php
                    $recentTransactions = \App\Models\Transaction::where('user_id', Auth::id())
                        ->where('coin_type', $wallet->coin_type)
                        ->where('type', 'deposit')
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($recentTransactions->count() > 0)
                    <div class="activity-list">
                        @foreach($recentTransactions as $tx)
                        <div class="activity-item">
                            <div class="activity-item-icon">
                                <i class="bi bi-arrow-down-circle text-success"></i>
                            </div>
                            <div class="activity-item-content">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ number_format($tx->amount, 3) }} {{ $tx->coin_type }}</strong>
                                    <span class="badge status-{{ $tx->status }}">{{ $tx->status }}</span>
                                </div>
                                <small class="text-muted">{{ $tx->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="#" class="btn-view-all">
                        <i class="bi bi-list-ul me-2"></i>View All Transactions
                    </a>
                @else
                    <div class="empty-activity">
                        <div class="empty-icon">
                            <i class="bi bi-inbox"></i>
                        </div>
                        <p class="mb-2">No deposit history yet</p>
                        <small class="text-muted">Your first deposit will appear here</small>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Quick Info -->
        <div class="card info-card glass">
            <div class="card-body">
                <h6 class="mb-3">Deposit Information</h6>
                <div class="info-item">
                    <i class="bi bi-currency-exchange"></i>
                    <span>Coin:</span>
                    <strong>{{ $wallet->coin_type }}</strong>
                </div>
                <div class="info-item">
                    <i class="bi bi-infinity"></i>
                    <span>Minimum:</span>
                    <strong>No minimum</strong>
                </div>
                <div class="info-item">
                    <i class="bi bi-lightning"></i>
                    <span>Speed:</span>
                    <strong>1-3 minutes</strong>
                </div>
                <div class="info-item">
                    <i class="bi bi-shield-check"></i>
                    <span>Status:</span>
                    <strong class="text-success">Active</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Glass Design */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    --danger-gradient: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
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
    background: var(--success-gradient);
    border-radius: 16px;
    padding: 1rem 1.5rem;
    color: white;
    box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
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

/* Deposit Card */
.deposit-card-glass {
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 24px;
    overflow: hidden;
    box-shadow: var(--glass-shadow);
}

.deposit-card-glass .card-header {
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
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
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
.deposit-step {
    padding: 2rem;
    display: none;
}

.deposit-step.active {
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

/* Details Grid */
.details-grid {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.detail-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.detail-card:hover {
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-4px);
}

.detail-card.important {
    border: 2px solid rgba(245, 158, 11, 0.3);
    background: rgba(245, 158, 11, 0.05);
}

.detail-card.important:hover {
    border-color: rgba(245, 158, 11, 0.5);
}

.detail-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.detail-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.detail-card.important .detail-icon {
    background: var(--warning-gradient);
}

.detail-label {
    flex: 1;
}

.detail-label small {
    display: block;
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.detail-label h6 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.badge {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Copy Fields */
.copy-field {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 1rem;
    position: relative;
}

.copy-text {
    font-family: 'JetBrains Mono', 'Fira Code', monospace;
    font-size: 0.875rem;
    color: var(--text-primary);
    flex: 1;
    word-break: break-all;
    margin: 0;
}

.memo-text {
    color: #fbbf24;
}

.btn-copy, .btn-refresh {
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
    flex-shrink: 0;
}

.btn-copy:hover {
    background: var(--success-gradient);
    border-color: transparent;
}

.btn-refresh:hover {
    background: var(--primary-gradient);
    border-color: transparent;
}

/* Action Card */
.action-card {
    background: var(--primary-gradient);
    border: none;
}

.action-card .detail-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.action-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.action-text {
    flex: 1;
}

.action-text h6 {
    font-size: 1rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.25rem;
}

.action-text p {
    font-size: 0.875rem;
    color: rgba(255, 255, 255, 0.8);
    margin: 0;
}

.btn-action {
    background: white;
    color: #667eea;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 255, 255, 0.2);
}

/* Step Navigation Buttons */
.step-footer {
    display: flex;
    justify-content: space-between;
    padding-top: 2rem;
    border-top: 1px solid var(--glass-border);
}

.btn-prev, .btn-next {
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
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

/* Transfer Guide */
.transfer-guide {
    max-width: 600px;
    margin: 0 auto 3rem;
}

.guide-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.guide-card.important {
    border-color: rgba(245, 158, 11, 0.3);
    background: rgba(245, 158, 11, 0.05);
}

.guide-card.success {
    border-color: rgba(16, 185, 129, 0.3);
    background: rgba(16, 185, 129, 0.05);
}

.guide-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.guide-card.important .guide-icon {
    background: var(--warning-gradient);
}

.guide-card.success .guide-icon {
    background: var(--success-gradient);
}

.guide-content h6 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.guide-content p {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin-bottom: 0.5rem;
}

.guide-address, .guide-memo {
    background: rgba(0, 0, 0, 0.3);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-family: monospace;
    font-size: 0.875rem;
    border: 1px solid var(--glass-border);
}

.guide-memo {
    background: rgba(245, 158, 11, 0.1);
    border-color: rgba(245, 158, 11, 0.3);
    color: #fbbf24;
}

.guide-arrow {
    text-align: center;
    color: var(--text-secondary);
    font-size: 1.5rem;
    margin: 0.5rem 0;
}

/* Confirmation Section */
.confirmation-section {
    text-align: center;
    padding: 3rem 2rem;
}

.confirmation-icon {
    width: 100px;
    height: 100px;
    border-radius: 25px;
    background: var(--glass-bg);
    border: 2px solid var(--glass-border);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 3rem;
    margin-bottom: 2rem;
}

.confirmation-content h4 {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 2rem;
}

.progress-container {
    max-width: 500px;
    margin: 0 auto 2rem;
}

.progress-bar {
    height: 8px;
    background: var(--glass-bg);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 1rem;
}

.progress-bar::after {
    content: '';
    display: block;
    height: 100%;
    width: 0%;
    background: var(--primary-gradient);
    animation: progress 3s ease-in-out infinite;
}

@keyframes progress {
    0% { width: 0%; }
    50% { width: 70%; }
    100% { width: 100%; }
}

.progress-labels {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    color: var(--text-secondary);
}

.estimated-time, .success-message {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
}

.estimated-time {
    color: var(--text-secondary);
}

.success-message {
    color: #10b981;
}

/* Completion Actions */
.completion-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    padding-top: 2rem;
    border-top: 1px solid var(--glass-border);
}

.btn-back-to-wallet, .btn-view-transactions {
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.btn-back-to-wallet {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    color: var(--text-primary);
}

.btn-back-to-wallet:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

.btn-view-transactions {
    background: var(--primary-gradient);
    color: white;
    border: none;
}

.btn-view-transactions:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
}

/* Quick Action Cards */
.quick-action-card {
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    transition: all 0.3s ease;
}

.quick-action-card:hover {
    transform: translateY(-8px);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.quick-action-card .card-body {
    padding: 1.5rem;
    text-align: center;
}

.quick-action-icon {
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

.quick-action-card h6 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.btn-quick-action {
    width: 100%;
    padding: 0.75rem;
    border-radius: 12px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    color: var(--text-primary);
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-quick-action:hover {
    background: var(--primary-gradient);
    border-color: transparent;
    transform: translateY(-2px);
}

/* Sidebar Cards */
.notice-card, .activity-card, .info-card {
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
    background: var(--warning-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
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
}

.activity-item-content strong {
    font-size: 0.938rem;
    color: var(--text-primary);
}

.activity-item-content small {
    font-size: 0.75rem;
    color: var(--text-secondary);
    display: block;
    margin-top: 0.25rem;
}

.status-completed {
    background: var(--success-gradient);
}

.status-pending {
    background: var(--warning-gradient);
}

.status-failed {
    background: var(--danger-gradient);
}

.btn-view-all {
    display: block;
    text-align: center;
    padding: 0.75rem;
    border-radius: 12px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    color: var(--text-primary);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-view-all:hover {
    background: var(--primary-gradient);
    border-color: transparent;
    transform: translateY(-2px);
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
    
    .detail-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .guide-card {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .guide-arrow {
        display: none;
    }
    
    .step-footer {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn-prev, .btn-next {
        width: 100%;
        text-align: center;
    }
    
    .completion-actions {
        flex-direction: column;
    }
    
    .copy-field {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn-copy, .btn-refresh {
        width: 100%;
        margin-top: 0.5rem;
    }
    
    .action-card .detail-content {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .btn-action {
        width: 100%;
    }
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
</style>

@push('scripts')
<script>
    // Step Navigation
    let currentStep = 1;
    
    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.deposit-step').forEach(el => {
            el.classList.remove('active');
        });
        
        // Show current step
        document.getElementById(`step${step}`).classList.add('active');
        currentStep = step;
    }
    
    function nextStep() {
        if (currentStep < 3) {
            showStep(currentStep + 1);
        }
    }
    
    function prevStep() {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    }
    
    // Copy Functions
    function copyToClipboard(fieldId) {
        const field = document.getElementById(fieldId);
        const text = field.querySelector('.copy-text').innerText;
        
        navigator.clipboard.writeText(text).then(() => {
            const btn = field.querySelector('.btn-copy');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check"></i>';
            btn.classList.add('copied');
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('copied');
            }, 2000);
            
            // Show toast notification
            showToast('Copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy:', err);
            showToast('Failed to copy', 'error');
        });
    }
    
    function copyAllDetails() {
        const account = document.querySelector('#depositAccountField .copy-text').innerText;
        const memo = document.querySelector('#memoField .copy-text').innerText;
        const wallet = document.querySelector('#walletField .copy-text').innerText;
        const coinType = '{{ $wallet->coin_type }}';
        
        const details = `
🔹 Deposit Account: ${account}
🔹 MEMO (Required): ${memo}
🔹 Your Wallet: ${wallet}

Instructions:
1. Send ${coinType} to ${account}
2. Include the MEMO above
3. Funds will appear in your wallet within 1-3 minutes

⚠️ IMPORTANT: Without the correct MEMO, your deposit will be lost!
        `.trim();
        
        navigator.clipboard.writeText(details).then(() => {
            showToast('All details copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy:', err);
            showToast('Failed to copy details', 'error');
        });
    }
    
    function generateNewMemo() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let newMemo = 'MEMO-';
        
        for (let i = 0; i < 8; i++) {
            newMemo += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        
        newMemo += '-{{ Auth::id() }}';
        document.querySelector('#memoField .copy-text').innerText = newMemo;
        
        showToast('New memo generated!');
    }
    
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
    });
    
    // Placeholder functions
    function viewTransactions() {
        alert('Transaction history page would open here');
    }
    
    function contactSupport() {
        alert('Support contact modal would open here');
    }
</script>
@endpush
@endsection