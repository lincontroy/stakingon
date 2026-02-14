@extends('layouts.app')

@section('title', 'Wallet Management')

@section('content')
<!-- Header -->
<div class="container-fluid px-4 py-4">
    <div class="row mb-5">
        <div class="col-12">
            <div class="welcome-header p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
                    <div>
                        <h1 class="display-5 fw-semibold mb-2 text-white">
                            <i class="bi bi-wallet2 me-2"></i>My Wallets
                        </h1>
                        <p class="text-white-50 mb-0 fs-6">
                            <i class="bi bi-shield-check me-2"></i>Manage your cryptocurrency wallets
                        </p>
                    </div>
                    <div class="wallet-stats-header">
                        <div class="stat-badge px-4 py-3">
                            <span class="d-block small text-white-50 fw-medium mb-1">Total Wallets</span>
                            <strong class="fs-3 fw-bold text-white">{{ $wallets->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create New Wallet -->
    <div class="card create-wallet-modern border-0 mb-5">
        <div class="card-body p-4">
            <div class="create-wallet-header d-flex align-items-center gap-3 mb-4">
                <div class="create-icon d-flex align-items-center justify-content-center">
                    <i class="bi bi-plus-circle-fill fs-4"></i>
                </div>
                <div>
                    <h5 class="fs-5 fw-semibold text-white mb-1">Create New Wallet</h5>
                    <p class="text-white-50 small mb-0">Add a new cryptocurrency wallet to your account</p>
                </div>
            </div>
            
            <form action="{{ route('wallet.create') }}" method="POST">
                @csrf
                <div class="row g-4 align-items-end">
                    <div class="col-md-8 col-lg-9">
                        <div class="input-modern">
                            <label for="coin_type" class="form-label d-flex align-items-center gap-2 small fw-medium text-white-50 mb-2">
                                <i class="bi bi-currency-bitcoin"></i>Select Cryptocurrency
                            </label>
                            <select class="form-select-modern w-100" id="coin_type" name="coin_type" required>
                                <option value="">Choose a cryptocurrency...</option>
                                @foreach($supportedCoins as $coin)
                                <option value="{{ $coin['type'] }}">
                                    {{ $coin['name'] }} ({{ $coin['type'] }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <button type="submit" class="btn btn-primary w-100 create-wallet-btn d-inline-flex align-items-center justify-content-center gap-2 border-0 py-3 fw-medium">
                            <i class="bi bi-plus-lg"></i>Create Wallet
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Wallets Grid -->
    @if($wallets->count() > 0)
    <div class="row g-4 mb-5">
        @foreach($wallets as $wallet)
        <div class="col-md-6 col-lg-4">
            <div class="wallet-card-modern border-0 p-4 h-100">
                <div class="wallet-header d-flex align-items-start gap-3 mb-4">
                    <div class="wallet-icon-large d-flex align-items-center justify-content-center flex-shrink-0" style="background: linear-gradient(135deg, {{ $wallet->gradient_start ?? '#f59e0b' }} 0%, {{ $wallet->gradient_end ?? '#fbbf24' }} 100%);">
                        <i class="bi {{ $wallet->coinIcon }} fs-2"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="fs-4 fw-semibold text-white mb-1">{{ $wallet->coin_type }}</h4>
                        <span class="coin-name d-block small text-white-50">
                            {{ 
                                ['HIVE' => 'Hive', 'STEEM' => 'Steem', 'BTC' => 'Bitcoin', 
                                 'ETH' => 'Ethereum', 'BNB' => 'Binance Coin', 'SOL' => 'Solana', 
                                 'ADA' => 'Cardano'][$wallet->coin_type] ?? $wallet->coin_type 
                            }}
                        </span>
                    </div>
                    <div class="wallet-balance-badge px-3 py-2 text-center">
                        <span class="d-block small text-white-50 fw-medium mb-1">Balance</span>
                        <strong class="d-block fs-5 fw-semibold text-white">{{ number_format($wallet->balance, 4) }}</strong>
                    </div>
                </div>
                
               
                
                <!-- Balance Details -->
                <div class="wallet-balance-details d-flex align-items-center gap-3 p-3 mb-4">
                    <div class="balance-item d-flex align-items-center gap-3 flex-grow-1">
                        <div class="balance-icon available d-flex align-items-center justify-content-center">
                            <i class="bi bi-arrow-down-circle fs-5"></i>
                        </div>
                        <div class="balance-info">
                            <small class="d-block text-white-50 small fw-medium mb-1">Available</small>
                            <div class="fw-semibold text-white">{{ number_format($wallet->available_balance, 4) }}</div>
                        </div>
                    </div>
                    <div class="balance-divider"></div>
                    <div class="balance-item d-flex align-items-center gap-3 flex-grow-1">
                        <div class="balance-icon staked d-flex align-items-center justify-content-center">
                            <i class="bi bi-lock fs-5"></i>
                        </div>
                        <div class="balance-info">
                            <small class="d-block text-white-50 small fw-medium mb-1">Staked</small>
                            <div class="fw-semibold text-white">{{ number_format($wallet->staking_balance, 4) }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="wallet-actions d-flex gap-3">
                    <a href="{{ route('wallet.deposit', $wallet->coin_type) }}" 
                       class="btn btn-success action-btn deposit-btn flex-grow-1 d-inline-flex align-items-center justify-content-center gap-2 border-0 py-2 fw-medium">
                        <i class="bi bi-arrow-down-circle"></i>Deposit
                    </a>
                    <a href="{{ route('wallet.withdraw', $wallet->coin_type) }}" 
                       class="btn btn-outline-primary action-btn withdraw-btn flex-grow-1 d-inline-flex align-items-center justify-content-center gap-2 py-2 fw-medium">
                        <i class="bi bi-arrow-up-circle"></i>Withdraw
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="empty-state-modern border-0 p-5 mb-5 text-center">
        <div class="empty-icon-large d-inline-flex align-items-center justify-content-center mb-4">
            <i class="bi bi-wallet2 fs-1"></i>
        </div>
        <h4 class="fs-3 fw-semibold text-white mb-3">No Wallets Yet</h4>
        <p class="text-white-50 mb-4">Create your first wallet to start staking and earning rewards</p>
        <button class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2 border-0 px-4 py-2 fw-medium" data-bs-toggle="modal" data-bs-target="#createWalletModal">
            <i class="bi bi-plus-circle"></i>Create First Wallet
        </button>
    </div>
    @endif

    <!-- Supported Coins -->
    <div class="card supported-coins-modern border-0">
        <div class="card-header d-flex align-items-center gap-3 bg-transparent border-0 px-4 pt-4 pb-0">
            <div class="coins-icon d-flex align-items-center justify-content-center">
                <i class="bi bi-currency-exchange fs-5"></i>
            </div>
            <h5 class="fs-5 fw-semibold text-white mb-0">Supported Cryptocurrencies</h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                @foreach($supportedCoins as $coin)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="coin-item-modern d-flex align-items-center gap-3 p-3 h-100">
                        <div class="coin-icon d-flex align-items-center justify-content-center flex-shrink-0" style="background: linear-gradient(135deg, {{ $coin['gradient_start'] ?? '#f59e0b' }} 0%, {{ $coin['gradient_end'] ?? '#fbbf24' }} 100%);">
                            <i class="bi {{ $coin['icon'] }} fs-4"></i>
                        </div>
                        <div class="coin-info">
                            <div class="coin-symbol fw-semibold text-white">{{ $coin['type'] }}</div>
                            <small class="coin-name d-block text-white-50 small">{{ $coin['name'] }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Create Wallet Modal (for empty state) -->
    <div class="modal fade" id="createWalletModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header d-flex align-items-center gap-3 border-0">
                    <div class="modal-icon d-flex align-items-center justify-content-center">
                        <i class="bi bi-plus-circle fs-4"></i>
                    </div>
                    <h5 class="modal-title fs-5 fw-semibold text-white ms-0">Create New Wallet</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('wallet.create') }}" method="POST" id="modalWalletForm">
                        @csrf
                        <div class="input-modern mb-4">
                            <label for="modal_coin_type" class="form-label d-flex align-items-center gap-2 small fw-medium text-white-50 mb-2">
                                <i class="bi bi-currency-bitcoin"></i>Select Cryptocurrency
                            </label>
                            <select class="form-select-modern w-100" id="modal_coin_type" name="coin_type" required>
                                <option value="">Choose a cryptocurrency...</option>
                                @foreach($supportedCoins as $coin)
                                <option value="{{ $coin['type'] }}">
                                    {{ $coin['name'] }} ({{ $coin['type'] }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex gap-3 border-0">
                    <button type="button" class="btn btn-outline-primary flex-grow-1 py-2 fw-medium" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="modalWalletForm" class="btn btn-primary flex-grow-1 d-inline-flex align-items-center justify-content-center gap-2 border-0 py-2 fw-medium">
                        <i class="bi bi-plus-lg"></i>Create Wallet
                    </button>
                </div>
            </div>
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

/* Font Size Utilities */
.fs-xs { font-size: var(--text-xs); }
.fs-sm { font-size: var(--text-sm); }
.fs-base { font-size: var(--text-base); }
.fs-lg { font-size: var(--text-lg); }
.fs-xl { font-size: var(--text-xl); }
.fs-2xl { font-size: var(--text-2xl); }
.fs-3xl { font-size: var(--text-3xl); }
.fs-4xl { font-size: var(--text-4xl); }

/* Font Weight Utilities */
.fw-light { font-weight: var(--weight-light); }
.fw-normal { font-weight: var(--weight-normal); }
.fw-medium { font-weight: var(--weight-medium); }
.fw-semibold { font-weight: var(--weight-semibold); }
.fw-bold { font-weight: var(--weight-bold); }

/* Line Height Utilities */
.lh-tight { line-height: var(--leading-tight); }
.lh-normal { line-height: var(--leading-normal); }
.lh-relaxed { line-height: var(--leading-relaxed); }

/* Letter Spacing */
.tracking-tight { letter-spacing: var(--tracking-tight); }
.tracking-normal { letter-spacing: var(--tracking-normal); }
.tracking-wide { letter-spacing: var(--tracking-wide); }
.tracking-wider { letter-spacing: var(--tracking-wider); }

/* Text Colors */
.text-white { color: var(--text-primary); }
.text-white-50 { color: var(--text-secondary); }
.text-white-25 { color: var(--text-tertiary); }

/* Welcome Header */
.welcome-header {
    background: var(--bg-glass);
    border: 1px solid var(--border-light);
    border-radius: 24px;
    backdrop-filter: blur(10px);
}

.stat-badge {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 16px;
    min-width: 120px;
}

/* Create Wallet Modern */
.create-wallet-modern {
    background: var(--bg-card);
    backdrop-filter: blur(10px);
    border-radius: 24px;
    transition: transform 0.2s ease;
}

.create-wallet-modern:hover {
    transform: translateY(-2px);
}

.create-icon {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    color: white;
    box-shadow: 0 8px 20px -5px rgba(59, 130, 246, 0.3);
}

.create-wallet-btn {
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    border-radius: 14px;
    font-size: var(--text-sm);
    transition: all 0.2s ease;
}

.create-wallet-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px -5px rgba(59, 130, 246, 0.4);
}

/* Form Elements */
.input-modern .form-label {
    font-size: var(--text-xs);
    text-transform: uppercase;
    letter-spacing: var(--tracking-wide);
}

.form-select-modern {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 14px;
    padding: 0.875rem 1rem;
    color: var(--text-primary);
    font-size: var(--text-sm);
    transition: all 0.2s ease;
    cursor: pointer;
}

.form-select-modern:hover {
    background: var(--bg-card-hover);
    border-color: var(--border-medium);
}

.form-select-modern:focus {
    background: var(--bg-card-hover);
    border-color: #3b82f6;
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-select-modern option {
    background: #1a1a1a;
    color: var(--text-primary);
}

/* Wallet Card Modern */
.wallet-card-modern {
    background: var(--bg-card);
    backdrop-filter: blur(10px);
    border-radius: 24px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.wallet-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.wallet-card-modern:hover {
    transform: translateY(-6px);
    background: var(--bg-card-hover);
    border-color: var(--border-medium) !important;
    box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
}

.wallet-card-modern:hover::before {
    opacity: 1;
}

.wallet-icon-large {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.3);
}

.wallet-balance-badge {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 12px;
    min-width: 90px;
}

/* Wallet Address */
.wallet-address-section small {
    text-transform: uppercase;
    letter-spacing: var(--tracking-wide);
}

.address-container {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 14px;
    position: relative;
    cursor: pointer;
    transition: all 0.2s ease;
}

.address-container:hover {
    background: var(--bg-card-hover);
    border-color: var(--border-medium);
}

.address-value {
    font-family: var(--font-mono);
    font-size: var(--text-xs);
    line-height: var(--leading-normal);
    word-break: break-all;
    padding-right: 60px;
}

.btn-copy-small {
    position: absolute;
    top: 50%;
    right: 0.75rem;
    transform: translateY(-50%);
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    color: var(--text-secondary);
    font-size: var(--text-xs);
    font-weight: var(--weight-medium);
    transition: all 0.2s ease;
}

.btn-copy-small:hover {
    background: var(--bg-card-hover);
    color: var(--text-primary);
    border-color: var(--border-medium);
}

.btn-copy-small.copied {
    background: #10b981;
    color: white;
    border-color: #10b981;
}

.copy-hint {
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: var(--text-xs);
    font-weight: var(--weight-medium);
    opacity: 0;
    transition: opacity 0.2s ease;
    pointer-events: none;
    white-space: nowrap;
}

.address-container:hover .copy-hint {
    opacity: 1;
}

/* Balance Details */
.wallet-balance-details {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 18px;
}

.balance-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
}

.balance-icon.available {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
}

.balance-icon.staked {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
}

.balance-info small {
    font-size: var(--text-xs);
    text-transform: uppercase;
    letter-spacing: var(--tracking-wide);
}

.balance-info .fw-semibold {
    font-size: var(--text-sm);
}

.balance-divider {
    width: 1px;
    height: 40px;
    background: var(--border-light);
}

/* Action Buttons */
.action-btn {
    border-radius: 14px;
    font-size: var(--text-sm);
    transition: all 0.2s ease;
    text-decoration: none;
}

.deposit-btn {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    color: white;
}

.deposit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px -5px rgba(16, 185, 129, 0.4);
    color: white;
}

.withdraw-btn {
    background: transparent;
    border: 1px solid var(--border-medium);
    color: var(--text-primary);
}

.withdraw-btn:hover {
    background: var(--bg-card-hover);
    border-color: #3b82f6;
    transform: translateY(-2px);
    color: var(--text-primary);
}

/* Empty State */
.empty-state-modern {
    background: var(--bg-card);
    backdrop-filter: blur(10px);
    border-radius: 32px;
}

.empty-icon-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: var(--bg-card);
    border: 2px solid var(--border-light);
    color: var(--text-secondary);
}

.empty-state-modern h4 {
    letter-spacing: var(--tracking-tight);
}

/* Supported Coins */
.supported-coins-modern {
    background: var(--bg-card);
    backdrop-filter: blur(10px);
    border-radius: 24px;
}

.coins-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    color: white;
}

.coin-item-modern {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: 18px;
    transition: all 0.2s ease;
}

.coin-item-modern:hover {
    transform: translateY(-4px);
    background: var(--bg-card-hover);
    border-color: var(--border-medium);
    box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.3);
}

.coin-icon {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    box-shadow: 0 8px 15px -5px rgba(0, 0, 0, 0.2);
}

.coin-symbol {
    font-size: var(--text-base);
    letter-spacing: var(--tracking-tight);
}

.coin-name {
    font-size: var(--text-xs);
}

/* Modal */
.modal-content {
    background: #1a1a1a;
    border-radius: 24px;
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
}

.modal-header {
    background: rgba(255, 255, 255, 0.02);
    border-bottom: 1px solid var(--border-light);
}

.modal-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
    color: white;
}

.btn-close-white {
    filter: brightness(0) invert(1);
    opacity: 0.5;
}

.btn-close-white:hover {
    opacity: 1;
}

.modal-footer {
    background: rgba(255, 255, 255, 0.02);
    border-top: 1px solid var(--border-light);
}

.btn-outline-primary {
    background: transparent;
    border: 1px solid var(--border-medium);
    color: var(--text-primary);
    border-radius: 14px;
    font-size: var(--text-sm);
    transition: all 0.2s ease;
}

.btn-outline-primary:hover {
    background: var(--bg-card-hover);
    border-color: #3b82f6;
    color: var(--text-primary);
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-header .d-flex {
        flex-direction: column;
        text-align: center;
    }
    
    .wallet-header {
        flex-direction: column;
        text-align: center;
    }
    
    .wallet-balance-badge {
        width: 100%;
        max-width: 150px;
        margin: 0 auto;
    }
    
    .wallet-balance-details {
        flex-direction: column;
        gap: 1rem;
    }
    
    .balance-divider {
        width: 100%;
        height: 1px;
    }
    
    .wallet-actions {
        flex-direction: column;
    }
    
    .coin-item-modern {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .coin-icon {
        width: 60px;
        height: 60px;
    }
}
</style>

@push('scripts')
<script>
    function copyToClipboard(elementId, event = null) {
        if (event) {
            event.stopPropagation();
        }
        
        const element = document.getElementById(elementId);
        const text = element.innerText;
        
        const tempInput = document.createElement('input');
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        tempInput.setSelectionRange(0, 99999);
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        const container = element.closest('.address-container');
        const button = container.querySelector('.btn-copy-small');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="bi bi-check"></i>';
        button.classList.add('copied');
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('copied');
        }, 2000);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const createWalletModal = document.getElementById('createWalletModal');
        if (createWalletModal) {
            createWalletModal.addEventListener('hidden.bs.modal', function () {
                const form = document.getElementById('modalWalletForm');
                form.reset();
            });
        }
    });
</script>
@endpush
@endsection