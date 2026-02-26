@extends('layouts.app')

@section('title', 'Deposit ' . $wallet->coin_type)

@section('content')
<!-- Header -->
<div class="container-fluid px-4 py-4">
    <div class="row mb-5">
        <div class="col-12">
            <div class="welcome-header p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
                    <div class="d-flex align-items-center gap-4">
                        <a href="{{ route('wallet.index') }}" class="btn-back" aria-label="Back to wallets">
                            <i class="bi bi-arrow-left fs-4"></i>
                        </a>
                        <div>
                            <h1 class="display-5 fw-semibold mb-2 text-white">
                                Deposit {{ $wallet->coin_type }}
                            </h1>
                            <p class="text-white-50 mb-0 fs-6">
                                <i class="bi bi-wallet2 me-2"></i>Securely add funds to your wallet
                            </p>
                        </div>
                    </div>
                    @php
                        $usdRate = 0;
                        if(in_array($wallet->coin_type, ['STEEM', 'HIVE', 'USDT'])) {
                            $usdRate = (float) (env($wallet->coin_type.'USD', 
                                $wallet->coin_type == 'STEEM' ? env('steemusd') : 
                                ($wallet->coin_type == 'HIVE' ? 0.0674 : 1)
                            ));
                        }
                        $totalUsd = $wallet->balance * $usdRate;
                    @endphp
                    <div class="wallet-info-badge px-4 py-3">
                        <div class="balance-display">
                            <span class="d-block small text-white-50 mb-1 fw-medium">Current Balance</span>
                            <strong class="fs-3 fw-bold text-white">{{ number_format($wallet->balance, 4) }} {{ $wallet->coin_type }}</strong>
                            @if($usdRate > 0)
                            <small class="d-block text-white-50 mt-1">≈ ${{ number_format($totalUsd, 2) }} USD</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-5">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Deposit Card -->
            <div class="card deposit-card border-0 mb-5">
                <div class="card-header bg-transparent border-0 px-4 pt-4 pb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="header-icon d-flex align-items-center justify-content-center">
                            <i class="bi bi-arrow-down-circle-fill fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fs-5 fw-semibold text-white mb-1">Deposit {{ $wallet->coin_type }}</h5>
                            <p class="text-white-50 small mb-0">Follow these simple steps to add funds</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
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

                    <!-- Step 1: Copy Details -->
                    <div class="deposit-step active" id="step1">
                        <div class="step-header d-flex align-items-center gap-4 pb-4 mb-4">
                            <div class="step-number d-flex align-items-center justify-content-center fs-4 fw-bold">
                                1
                            </div>
                            <div>
                                <h6 class="fs-6 fw-semibold text-white mb-1">Copy Deposit Details</h6>
                                <p class="text-white-50 small mb-0">Save these details for your transfer</p>
                            </div>
                        </div>
                        
                        <div class="vstack gap-4 mb-4">
                            <!-- Deposit Address -->
                            <div class="detail-card p-4">
                                <div class="d-flex align-items-start gap-3 mb-3">
                                    <div class="detail-icon d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person-badge fs-5"></i>
                                    </div>
                                    <div>
                                        <span class="d-block small text-white-50 fw-medium mb-1">Deposit Account</span>
                                        <h6 class="fs-6 fw-semibold text-white mb-0">Official Account</h6>
                                    </div>
                                </div>
                                
                                <div class="copy-field d-flex align-items-center gap-2 p-3" id="depositAccountField">
                                    <code class="flex-grow-1 small text-white bg-transparent border-0">
                                        @if($wallet->coin_type === 'HIVE')
                                            hivexpay
                                        @elseif($wallet->coin_type === 'USDT')
                                            THsZkYq3hcbDGE4pNPoyGns9nwp8swS84P
                                        @elseif($wallet->coin_type === 'STEEM')
                                            stakeonsteem
                                        
                                        @endif
                                    </code>
                                    <button class="btn-copy d-flex align-items-center justify-content-center border-0" onclick="copyToClipboard('depositAccountField')" title="Copy to clipboard">
                                       Copy <i class="bi bi-copy fs-6"></i>
                                    </button>
                                </div>
                            </div>
                          
                        </div>
                        
                        <!-- Copy All Button -->
                        <div class="d-flex justify-content-end pt-4 border-top border-white-10">
                            <button class="btn-next d-inline-flex align-items-center gap-2 px-4 py-2 border-0 fw-medium" onclick="nextStep()">
                                Next: Send Funds
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 2: Send Funds -->
                    <div class="deposit-step" id="step2">
                        <div class="step-header d-flex align-items-center gap-4 pb-4 mb-4">
                            <div class="step-number d-flex align-items-center justify-content-center fs-4 fw-bold">
                                2
                            </div>
                            <div>
                                <h6 class="fs-6 fw-semibold text-white mb-1">Send {{ $wallet->coin_type }}</h6>
                                <p class="text-white-50 small mb-0">Transfer from your external wallet</p>
                            </div>
                        </div>
                        
                        <div class="vstack align-items-center gap-3 mb-5">
                            <!-- Step 1: Send -->
                            <div class="guide-card w-100 p-4">
                                <div class="d-flex align-items-center gap-4">
                                    <div class="guide-icon d-flex align-items-center justify-content-center">
                                        <i class="bi bi-arrow-right-circle fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fs-6 fw-semibold text-white mb-2">Send to Deposit Account</h6>
                                        <p class="small text-white-50 mb-3">Open your external wallet (Hive Keychain, Hive wallet, etc.)</p>
                                        <code class="d-inline-block p-2 small bg-black-30 rounded-3 border border-white-10">
                                            @if($wallet->coin_type === 'HIVE')
                                                hivexpay
                                            @elseif($wallet->coin_type === 'USDT')
                                                THsZkYq3hcbDGE4pNPoyGns9nwp8swS84P
                                            @elseif($wallet->coin_type === 'STEEM')
                                                stakeonsteem
                                            @endif
                                        </code>
                                        @if($usdRate > 0)
                                        <div class="mt-2 small text-white-50">
                                            <i class="bi bi-info-circle"></i> 
                                            Current value: 1 {{ $wallet->coin_type }} = ${{ number_format($usdRate, $wallet->coin_type == 'USDT' ? 2 : 4) }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="guide-arrow text-white-50">
                                <i class="bi bi-arrow-down fs-4"></i>
                            </div>
                            
                            <!-- Step 3: Confirm -->
                            <div class="guide-card success w-100 p-4">
                                <div class="d-flex align-items-center gap-4">
                                    <div class="guide-icon d-flex align-items-center justify-content-center">
                                        <i class="bi bi-check-circle fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fs-6 fw-semibold text-white mb-2">Confirm Transfer</h6>
                                        <p class="small text-white-50 mb-0">Review all details and confirm the transaction</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between pt-4 border-top border-white-10">
                            <button class="btn-prev d-inline-flex align-items-center gap-2 px-4 py-2 border-0 fw-medium" onclick="prevStep()">
                                <i class="bi bi-arrow-left"></i>
                                Back
                            </button>
                            <button class="btn-next d-inline-flex align-items-center gap-2 px-4 py-2 border-0 fw-medium" onclick="nextStep()">
                                Next: Wait for Confirmation
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 3: Confirmation -->
                    <div class="deposit-step" id="step3">
                        <div class="step-header d-flex align-items-center gap-4 pb-4 mb-4">
                            <div class="step-number d-flex align-items-center justify-content-center fs-4 fw-bold">
                                3
                            </div>
                            <div>
                                <h6 class="fs-6 fw-semibold text-white mb-1">Wait for Confirmation</h6>
                                <p class="text-white-50 small mb-0">Funds will appear automatically</p>
                            </div>
                        </div>
                        
                        <div class="text-center py-5">
                            <div class="confirmation-icon d-inline-flex align-items-center justify-content-center mb-4">
                                <i class="bi bi-clock-history fs-1"></i>
                            </div>
                            
                            <h4 class="fs-3 fw-semibold text-white mb-4">Processing Your Deposit</h4>
                            
                            <div class="mx-auto mb-4" style="max-width: 400px;">
                                <div class="progress bg-black-30 rounded-pill mb-3" style="height: 8px;">
                                    <div class="progress-bar bg-gradient rounded-pill" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between small text-white-50">
                                    <span>Processing</span>
                                    <span>1-3 minutes</span>
                                    <span>Complete</span>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center justify-content-center gap-2 text-white-50 mb-2">
                                <i class="bi bi-clock"></i>
                                <span class="small">Estimated time: 1-3 minutes</span>
                            </div>
                            
                            @if($usdRate > 0)
                            <div class="d-flex align-items-center justify-content-center gap-2 text-success mb-2">
                                <i class="bi bi-currency-dollar"></i>
                                <span class="small fw-medium">1 {{ $wallet->coin_type }} = ${{ number_format($usdRate, $wallet->coin_type == 'USDT' ? 2 : 4) }}</span>
                            </div>
                            @endif
                            
                            <div class="d-flex align-items-center justify-content-center gap-2 text-success">
                                <i class="bi bi-check-circle-fill"></i>
                                <span class="small fw-medium">Funds will appear automatically</span>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-3 justify-content-center pt-4 border-top border-white-10">
                            <a href="{{ route('wallet.index') }}" class="btn-back-to-wallet d-inline-flex align-items-center gap-2 px-4 py-2 border-0 fw-medium">
                                <i class="bi bi-wallet2"></i>
                                Back to Wallets
                            </a>
                            <button class="btn-view-transactions d-inline-flex align-items-center gap-2 px-4 py-2 border-0 fw-medium" onclick="viewTransactions()">
                                <i class="bi bi-list-check"></i>
                                View Transactions
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="row g-4">
                

                <div class="col-md-6">
                    <div class="card quick-action-card border-0 h-100">
                        <div class="card-body p-4 text-center">
                            <div class="quick-action-icon d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="bi bi-chat-dots fs-3"></i>
                            </div>
                            <h6 class="fs-6 fw-semibold text-white mb-2">Support</h6>
                            <p class="small text-white-50 mb-3">Get help with your deposit</p>
                            <button class="btn-quick-action w-100 py-2 border-0 small fw-medium" onclick="contactSupport()">
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
            <div class="card notice-card border-0 mb-4">
                <div class="card-header bg-transparent border-0 px-4 pt-4 pb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="notice-icon d-flex align-items-center justify-content-center">
                            <i class="bi bi-exclamation-triangle fs-5"></i>
                        </div>
                        <h6 class="fs-6 fw-semibold text-white mb-0">Important Notice</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="vstack gap-4">
                       
                        
                        <div class="d-flex gap-3">
                            <div class="notice-item-icon info d-flex align-items-center justify-content-center flex-shrink-0">
                                <i class="bi bi-clock fs-6"></i>
                            </div>
                            <div>
                                <h6 class="fs-6 fw-semibold text-white mb-1">Processing Time</h6>
                                <p class="small text-white-50 mb-0">1-3 minutes after blockchain confirmation</p>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-3">
                            <div class="notice-item-icon success d-flex align-items-center justify-content-center flex-shrink-0">
                                <i class="bi bi-shield-check fs-6"></i>
                            </div>
                            <div>
                                <h6 class="fs-6 fw-semibold text-white mb-1">Secure & Safe</h6>
                                <p class="small text-white-50 mb-0">Enterprise-grade security for your funds</p>
                            </div>
                        </div>

                        @if($usdRate > 0)
                        <div class="d-flex gap-3">
                            <div class="notice-item-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);">
                                <i class="bi bi-currency-dollar fs-6"></i>
                            </div>
                            <div>
                                <h6 class="fs-6 fw-semibold text-white mb-1">Exchange Rate</h6>
                                <p class="small text-white-50 mb-0">1 {{ $wallet->coin_type }} = ${{ number_format($usdRate, $wallet->coin_type == 'USDT' ? 2 : 4) }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="card activity-card border-0 mb-4">
                <div class="card-header bg-transparent border-0 px-4 pt-4 pb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="activity-icon d-flex align-items-center justify-content-center">
                            <i class="bi bi-clock-history fs-5"></i>
                        </div>
                        <h6 class="fs-6 fw-semibold text-white mb-0">Recent Deposits</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    @php
                        $recentTransactions = \App\Models\Transaction::where('user_id', Auth::id())
                            ->where('coin_type', $wallet->coin_type)
                            ->where('type', 'deposit')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($recentTransactions->count() > 0)
                        <div class="vstack gap-3 mb-4">
                            @foreach($recentTransactions as $tx)
                            @php
                                $txUsdValue = $tx->amount * $usdRate;
                            @endphp
                            <div class="d-flex align-items-start gap-3">
                                <div class="activity-item-icon d-flex align-items-center justify-content-center flex-shrink-0">
                                    <i class="bi bi-arrow-down-circle text-success"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div>
                                            <strong class="text-white">{{ number_format($tx->amount, 4) }} {{ $tx->coin_type }}</strong>
                                            @if($usdRate > 0)
                                            <small class="text-white-50 d-block">≈ ${{ number_format($txUsdValue, 2) }}</small>
                                            @endif
                                        </div>
                                        <span class="badge status-{{ $tx->status }} small fw-medium px-2 py-1">{{ $tx->status }}</span>
                                    </div>
                                    <small class="text-white-50">{{ $tx->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <a href="#" class="btn-view-all d-block text-center py-2 border-0 small fw-medium text-decoration-none">
                            <i class="bi bi-list-ul me-2"></i>View All Transactions
                        </a>
                    @else
                        <div class="text-center py-4">
                            <div class="empty-icon d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="bi bi-inbox fs-3"></i>
                            </div>
                            <p class="text-white mb-1">No deposit history yet</p>
                            <small class="text-white-50">Your first deposit will appear here</small>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Quick Info -->
            <div class="card info-card border-0">
                <div class="card-body p-4">
                    <h6 class="small text-white-50 text-uppercase tracking-wide fw-semibold mb-4">Deposit Information</h6>
                    <div class="vstack gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-currency-exchange d-flex align-items-center justify-content-center flex-shrink-0"></i>
                            <span class="flex-grow-1 small text-white-50">Coin:</span>
                            <strong class="small text-white">{{ $wallet->coin_type }}</strong>
                        </div>
                        
                        @if($usdRate > 0)
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-currency-dollar d-flex align-items-center justify-content-center flex-shrink-0"></i>
                            <span class="flex-grow-1 small text-white-50">USD Rate:</span>
                            <strong class="small text-white">${{ number_format($usdRate, $wallet->coin_type == 'USDT' ? 2 : 4) }}</strong>
                        </div>
                        @endif
                        
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-infinity d-flex align-items-center justify-content-center flex-shrink-0"></i>
                            <span class="flex-grow-1 small text-white-50">Minimum:</span>
                            <strong class="small text-white">$51</strong>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-lightning d-flex align-items-center justify-content-center flex-shrink-0"></i>
                            <span class="flex-grow-1 small text-white-50">Speed:</span>
                            <strong class="small text-white">1-3 minutes</strong>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-shield-check d-flex align-items-center justify-content-center flex-shrink-0"></i>
                            <span class="flex-grow-1 small text-white-50">Status:</span>
                            <strong class="small text-success">Active</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Typography & White Space */
:root {
    --font-primary: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    --font-mono: 'SF Mono', 'Fira Code', 'Fira Mono', 'Roboto Mono', monospace;
    
    --space-xs: 0.5rem;
    --space-sm: 1rem;
    --space-md: 1.5rem;
    --space-lg: 2rem;
    --space-xl: 3rem;
    --space-2xl: 4rem;
    
    --text-xs: 0.75rem;
    --text-sm: 0.875rem;
    --text-base: 1rem;
    --text-lg: 1.125rem;
    --text-xl: 1.25rem;
    --text-2xl: 1.5rem;
    --text-3xl: 2rem;
    
    --bg-dark: #0a0c10;
    --bg-card: rgba(255, 255, 255, 0.03);
    --bg-hover: rgba(255, 255, 255, 0.05);
    --border-light: rgba(255, 255, 255, 0.06);
    --border-medium: rgba(255, 255, 255, 0.1);
    
    --text-primary: #ffffff;
    --text-secondary: rgba(255, 255, 255, 0.65);
    --text-tertiary: rgba(255, 255, 255, 0.4);
    
    --accent-primary: #3b82f6;
    --accent-success: #10b981;
    --accent-warning: #f59e0b;
    --accent-danger: #ef4444;
}

body {
    font-family: var(--font-primary);
    background: var(--bg-dark);
    color: var(--text-primary);
    line-height: 1.6;
}

/* Header Styles */
.welcome-header {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
    backdrop-filter: blur(10px);
    border-radius: 24px;
    border: 1px solid var(--border-medium);
}

.btn-back {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: var(--bg-card);
    border: 1px solid var(--border-medium);
    color: var(--text-primary);
    transition: all 0.2s ease;
}

.btn-back:hover {
    background: var(--bg-hover);
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-1px);
}

.wallet-info-badge {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    border-radius: 20px;
    box-shadow: 0 10px 30px -10px rgba(16, 185, 129, 0.3);
}

/* Typography Utilities */
.fs-6 { font-size: var(--text-base); }
.fs-5 { font-size: var(--text-lg); }
.fs-4 { font-size: var(--text-xl); }
.fs-3 { font-size: var(--text-2xl); }
.fs-2 { font-size: var(--text-3xl); }

.fw-light { font-weight: 300; }
.fw-normal { font-weight: 400; }
.fw-medium { font-weight: 500; }
.fw-semibold { font-weight: 600; }
.fw-bold { font-weight: 700; }

.text-white-50 { color: var(--text-secondary); }
.text-white-25 { color: var(--text-tertiary); }

/* Card Styles */
.deposit-card {
    background: var(--bg-card);
    backdrop-filter: blur(10px);
    border-radius: 24px;
    transition: transform 0.2s ease;
}

.deposit-card:hover {
    transform: translateY(-2px);
}

.header-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    color: white;
}

/* Step Navigation */
.step-number {
    width: 56px;
    height: 56px;
    border-radius: 18px;
    background: var(--bg-card);
    border: 2px solid var(--border-medium);
    color: var(--text-primary);
}

.deposit-step {
    display: none;
}

.deposit-step.active {
    display: block;
}

/* Detail Cards */
.detail-card {
    background: var(--bg-card);
    border: 1px solid var(--border-medium);
    border-radius: 20px;
    transition: all 0.2s ease;
}

.detail-card:hover {
    border-color: rgba(255, 255, 255, 0.15);
    background: var(--bg-hover);
}

.detail-card.important {
    background: rgba(245, 158, 11, 0.05);
    border-color: rgba(245, 158, 11, 0.2);
}

.detail-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    color: white;
}

.detail-card.important .detail-icon {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
}

/* Copy Fields */
.copy-field {
    background: rgba(0, 0, 0, 0.2);
    border: 1px solid var(--border-medium);
    border-radius: 14px;
}

.copy-field code {
    font-family: var(--font-mono);
    font-size: var(--text-sm);
}

.btn-copy {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: var(--bg-card);
    color: var(--text-secondary);
    transition: all 0.2s ease;
}

.btn-copy:hover {
    background: var(--accent-success);
    color: white;
    transform: scale(1.05);
}

.btn-refresh {
    background: var(--bg-card);
    border: 1px solid var(--border-medium);
    border-radius: 12px;
    color: var(--text-secondary);
    transition: all 0.2s ease;
}

.btn-refresh:hover {
    background: var(--bg-hover);
    color: var(--text-primary);
}

/* Action Card */
.action-card {
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    border-radius: 20px;
}

.action-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    color: white;
}

.btn-action {
    background: white;
    color: #3b82f6;
    border-radius: 12px;
    font-size: var(--text-sm);
    transition: all 0.2s ease;
}

.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

/* Navigation Buttons */
.btn-prev {
    background: var(--bg-card);
    border: 1px solid var(--border-medium);
    border-radius: 12px;
    color: var(--text-primary);
    font-size: var(--text-sm);
    transition: all 0.2s ease;
}

.btn-prev:hover {
    background: var(--bg-hover);
    transform: translateY(-1px);
}

.btn-next {
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    border-radius: 12px;
    color: white;
    font-size: var(--text-sm);
    transition: all 0.2s ease;
}

.btn-next:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px -5px rgba(59, 130, 246, 0.5);
}

/* Guide Cards */
.guide-card {
    background: var(--bg-card);
    border: 1px solid var(--border-medium);
    border-radius: 20px;
}

.guide-card.important {
    background: rgba(245, 158, 11, 0.05);
    border-color: rgba(245, 158, 11, 0.2);
}

.guide-card.success {
    background: rgba(16, 185, 129, 0.05);
    border-color: rgba(16, 185, 129, 0.2);
}

.guide-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    color: white;
}

.guide-card.important .guide-icon {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
}

.guide-card.success .guide-icon {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
}

.guide-arrow {
    color: var(--text-tertiary);
}

/* Confirmation Section */
.confirmation-icon {
    width: 100px;
    height: 100px;
    border-radius: 30px;
    background: var(--bg-card);
    border: 2px solid var(--border-medium);
    color: #3b82f6;
}

.progress {
    background: rgba(255, 255, 255, 0.05);
}

.progress-bar {
    background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 100%);
}

/* Completion Buttons */
.btn-back-to-wallet {
    background: var(--bg-card);
    border: 1px solid var(--border-medium);
    border-radius: 12px;
    color: var(--text-primary);
    font-size: var(--text-sm);
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-back-to-wallet:hover {
    background: var(--bg-hover);
    transform: translateY(-1px);
}

.btn-view-transactions {
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    border-radius: 12px;
    color: white;
    font-size: var(--text-sm);
    transition: all 0.2s ease;
}

.btn-view-transactions:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px -5px rgba(59, 130, 246, 0.5);
}

/* Quick Action Cards */
.quick-action-card {
    background: var(--bg-card);
    backdrop-filter: blur(10px);
    transition: all 0.2s ease;
}

.quick-action-card:hover {
    transform: translateY(-4px);
    background: var(--bg-hover);
}

.quick-action-icon {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    color: white;
}

.btn-quick-action {
    background: var(--bg-card);
    border: 1px solid var(--border-medium);
    border-radius: 12px;
    color: var(--text-primary);
    font-size: var(--text-sm);
    transition: all 0.2s ease;
}

.btn-quick-action:hover {
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    border-color: transparent;
}

/* Sidebar Cards */
.notice-card, .activity-card, .info-card {
    background: var(--bg-card);
    backdrop-filter: blur(10px);
    border-radius: 24px;
}

.notice-icon, .activity-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    color: white;
}

.activity-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
}

.notice-item-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    font-size: var(--text-base);
}

.notice-item-icon.warning {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    color: white;
}

.notice-item-icon.info {
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
    color: white;
}

.notice-item-icon.success {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    color: white;
}

/* Activity */
.activity-item-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: var(--bg-card);
    border: 1px solid var(--border-medium);
    color: var(--text-secondary);
}

.status-completed {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border-radius: 20px;
}

.status-pending {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
    border-radius: 20px;
}

.status-failed {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border-radius: 20px;
}

.btn-view-all {
    background: var(--bg-card);
    border: 1px solid var(--border-medium);
    border-radius: 12px;
    color: var(--text-primary);
    transition: all 0.2s ease;
}

.btn-view-all:hover {
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    color: white;
}

.empty-icon {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    background: var(--bg-card);
    border: 1px solid var(--border-medium);
    color: var(--text-tertiary);
}

/* Info Card */
.info-card i {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: var(--bg-card);
    border: 1px solid var(--border-medium);
    color: var(--text-secondary);
    font-size: var(--text-sm);
}

.tracking-wide {
    letter-spacing: 0.05em;
}

/* Borders */
.border-white-10 {
    border-color: rgba(255, 255, 255, 0.1) !important;
}

.border-warning-20 {
    border-color: rgba(245, 158, 11, 0.2) !important;
}

.bg-black-30 {
    background: rgba(0, 0, 0, 0.3);
}

/* Toast Notification */
.toast-notification {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    background: var(--bg-card);
    backdrop-filter: blur(20px);
    border: 1px solid var(--border-medium);
    border-radius: 16px;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    z-index: 9999;
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s ease;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.toast-notification.show {
    transform: translateY(0);
    opacity: 1;
}

.toast-notification.success .toast-icon {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
}

.toast-notification.error .toast-icon {
    background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
}

.toast-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-header .d-flex {
        flex-direction: column;
        text-align: center;
    }
    
    .step-header {
        flex-direction: column;
        text-align: center;
    }
    
    .guide-card > div {
        flex-direction: column;
        text-align: center;
    }
    
    .guide-arrow {
        display: none;
    }
    
    .btn-prev, .btn-next {
        width: 100%;
        justify-content: center;
    }
    
    .completion-actions {
        flex-direction: column;
    }
    
    .copy-field {
        flex-direction: column;
    }
    
    .btn-copy {
        width: 100%;
    }
}
</style>

@push('scripts')
<script>
    // Step Navigation
    let currentStep = 1;
    
    function showStep(step) {
        document.querySelectorAll('.deposit-step').forEach(el => {
            el.classList.remove('active');
        });
        
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
        const text = field.querySelector('code').innerText;
        
        navigator.clipboard.writeText(text).then(() => {
            const btn = field.querySelector('.btn-copy');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check"></i>';
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
            }, 2000);
            
            showToast('Copied to clipboard!');
        }).catch(() => {
            showToast('Failed to copy', 'error');
        });
    }
    
    function copyAllDetails() {
        const account = document.querySelector('#depositAccountField code').innerText;
        const memo = document.querySelector('#memoField code').innerText;
        const wallet = document.querySelector('#walletField code').innerText;
        const coinType = '{{ $wallet->coin_type }}';
        
        const details = `Deposit Details for ${coinType}
━━━━━━━━━━━━━━━━━━━━━━
Deposit Account: ${account}
MEMO (Required): ${memo}
Your Wallet: ${wallet}

Instructions:
1. Send ${coinType} to ${account}
2. Include the MEMO in your transfer
3. Funds will appear within 1-3 minutes

⚠️ Important: Without the correct MEMO, your deposit will be lost!`;
        
        navigator.clipboard.writeText(details).then(() => {
            showToast('All details copied!');
        }).catch(() => {
            showToast('Failed to copy', 'error');
        });
    }
    
    function generateNewMemo() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let newMemo = 'MEMO-';
        
        for (let i = 0; i < 8; i++) {
            newMemo += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        
        newMemo += '-{{ Auth::id() }}';
        document.querySelector('#memoField code').innerText = newMemo;
        
        showToast('New memo generated!');
    }
    
    function showToast(message, type = 'success') {
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) existingToast.remove();
        
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'}"></i>
            </div>
            <div class="small text-white">${message}</div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    function viewTransactions() {
        window.location.href = '{{ route("transactions.index") }}';
    }
    
    function contactSupport() {
        window.location.href = '{{ route("support") }}';
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', () => showStep(1));
</script>
@endpush
@endsection