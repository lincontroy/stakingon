@extends('layouts.app')

@section('title', 'Wallet Management')

@section('content')
<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-6 fw-bold mb-2" style="background: linear-gradient(135deg, #ffffff 0%, #b4b4c8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        <i class="bi bi-wallet2 me-2"></i>My Wallets
                    </h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-shield-check me-2"></i>Manage your cryptocurrency wallets
                    </p>
                </div>
                <div class="wallet-stats-header">
                    <div class="stat-badge">
                        <small>Total Wallets</small>
                        <strong>{{ $wallets->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create New Wallet -->
<div class="card create-wallet-modern mb-5">
    <div class="card-body">
        <div class="create-wallet-header mb-4">
            <div class="create-icon">
                <i class="bi bi-plus-circle-fill"></i>
            </div>
            <div>
                <h5 class="mb-1">Create New Wallet</h5>
                <p class="text-muted mb-0">Add a new cryptocurrency wallet to your account</p>
            </div>
        </div>
        
        <form action="{{ route('wallet.create') }}" method="POST">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-8 col-lg-9">
                    <div class="input-modern">
                        <label for="coin_type" class="form-label">
                            <i class="bi bi-currency-bitcoin me-2"></i>Select Cryptocurrency
                        </label>
                        <select class="form-select-modern" id="coin_type" name="coin_type" required>
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
                    <button type="submit" class="btn btn-primary w-100 create-wallet-btn">
                        <i class="bi bi-plus-lg me-2"></i>Create Wallet
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
        <div class="wallet-card-modern">
            <div class="wallet-header">
                <div class="wallet-icon-large" style="background: linear-gradient(135deg, {{ $wallet->gradient_start ?? '#f59e0b' }} 0%, {{ $wallet->gradient_end ?? '#fbbf24' }} 100%);">
                    <i class="bi {{ $wallet->coinIcon }}"></i>
                </div>
                <div class="wallet-title">
                    <h4 class="mb-1">{{ $wallet->coin_type }}</h4>
                    <span class="coin-name">
                        {{ 
                            ['HIVE' => 'Hive', 'STEEM' => 'Steem', 'BTC' => 'Bitcoin', 
                             'ETH' => 'Ethereum', 'BNB' => 'Binance Coin', 'SOL' => 'Solana', 
                             'ADA' => 'Cardano'][$wallet->coin_type] ?? $wallet->coin_type 
                        }}
                    </span>
                </div>
                <div class="wallet-balance-badge">
                    <small>Balance</small>
                    <strong>{{ number_format($wallet->balance, 4) }}</strong>
                </div>
            </div>
            
            <!-- Wallet Address -->
            <div class="wallet-address-section">
                <small class="text-muted mb-2 d-block">Wallet Address</small>
                <div class="address-container" onclick="copyToClipboard('address-{{ $wallet->id }}')">
                    <div class="address-value" id="address-{{ $wallet->id }}">
                        {{ $wallet->address }}
                    </div>
                    <button class="btn-copy-small" onclick="copyToClipboard('address-{{ $wallet->id }}', event)">
                        <i class="bi bi-copy"></i>
                    </button>
                    <span class="copy-hint">Click to copy</span>
                </div>
            </div>
            
            <!-- Balance Details -->
            <div class="wallet-balance-details">
                <div class="balance-item">
                    <div class="balance-icon available">
                        <i class="bi bi-arrow-down-circle"></i>
                    </div>
                    <div class="balance-info">
                        <small class="text-muted">Available</small>
                        <div class="fw-semibold">{{ number_format($wallet->available_balance, 4) }}</div>
                    </div>
                </div>
                <div class="balance-divider"></div>
                <div class="balance-item">
                    <div class="balance-icon staked">
                        <i class="bi bi-lock"></i>
                    </div>
                    <div class="balance-info">
                        <small class="text-muted">Staked</small>
                        <div class="fw-semibold">{{ number_format($wallet->staking_balance, 4) }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="wallet-actions">
                <a href="{{ route('wallet.deposit', $wallet->coin_type) }}" 
                   class="btn btn-success action-btn deposit-btn">
                    <i class="bi bi-arrow-down-circle me-2"></i>Deposit
                </a>
                <a href="{{ route('wallet.withdraw', $wallet->coin_type) }}" 
                   class="btn btn-outline-primary action-btn withdraw-btn">
                    <i class="bi bi-arrow-up-circle me-2"></i>Withdraw
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<!-- Empty State -->
<div class="empty-state-modern">
    <div class="empty-icon-large">
        <i class="bi bi-wallet2"></i>
    </div>
    <h4 class="mt-4 mb-3">No Wallets Yet</h4>
    <p class="text-muted mb-4">Create your first wallet to start staking and earning rewards</p>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWalletModal">
        <i class="bi bi-plus-circle me-2"></i>Create First Wallet
    </button>
</div>
@endif

<!-- Supported Coins -->
<div class="card supported-coins-modern">
    <div class="card-header d-flex align-items-center">
        <div class="coins-icon">
            <i class="bi bi-currency-exchange"></i>
        </div>
        <h5 class="mb-0 ms-2">Supported Cryptocurrencies</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @foreach($supportedCoins as $coin)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="coin-item-modern">
                    <div class="coin-icon" style="background: linear-gradient(135deg, {{ $coin['gradient_start'] ?? '#f59e0b' }} 0%, {{ $coin['gradient_end'] ?? '#fbbf24' }} 100%);">
                        <i class="bi {{ $coin['icon'] }}"></i>
                    </div>
                    <div class="coin-info">
                        <div class="coin-symbol">{{ $coin['type'] }}</div>
                        <small class="coin-name">{{ $coin['name'] }}</small>
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
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="bi bi-plus-circle"></i>
                </div>
                <h5 class="modal-title ms-2">Create New Wallet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('wallet.create') }}" method="POST" id="modalWalletForm">
                    @csrf
                    <div class="input-modern mb-4">
                        <label for="modal_coin_type" class="form-label">
                            <i class="bi bi-currency-bitcoin me-2"></i>Select Cryptocurrency
                        </label>
                        <select class="form-select-modern" id="modal_coin_type" name="coin_type" required>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="modalWalletForm" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Create Wallet
                </button>
            </div>
        </div>
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

.wallet-stats-header {
    display: flex;
    gap: 1rem;
}

.stat-badge {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 0.75rem 1.5rem;
    text-align: center;
    min-width: 100px;
}

.stat-badge small {
    display: block;
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.stat-badge strong {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
}

/* Create Wallet Modern */
.create-wallet-modern {
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    overflow: hidden;
}

.create-wallet-header {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.create-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.create-wallet-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 12px;
    padding: 0.75rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.create-wallet-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

/* Wallet Card Modern */
.wallet-card-modern {
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    padding: 1.5rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    position: relative;
    overflow: hidden;
}

.wallet-card-modern::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.03) 0%, transparent 70%);
    pointer-events: none;
}

.wallet-card-modern:hover {
    transform: translateY(-8px);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
}

.wallet-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    position: relative;
}

.wallet-icon-large {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    flex-shrink: 0;
}

.wallet-title {
    flex: 1;
    min-width: 0;
}

.wallet-title h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
    line-height: 1.2;
}

.coin-name {
    font-size: 0.813rem;
    color: var(--text-muted);
    font-weight: 500;
}

.wallet-balance-badge {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 10px;
    padding: 0.5rem 0.75rem;
    text-align: center;
    min-width: 80px;
}

.wallet-balance-badge small {
    display: block;
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.wallet-balance-badge strong {
    display: block;
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-primary);
}

/* Wallet Address Section */
.wallet-address-section {
    margin-bottom: 1.5rem;
}

.address-container {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 1rem;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
}

.address-container:hover {
    background: var(--card-hover);
    border-color: rgba(255, 255, 255, 0.2);
}

.address-value {
    font-family: 'Courier New', monospace;
    font-size: 0.813rem;
    color: var(--text-primary);
    word-break: break-all;
    padding-right: 40px;
}

.btn-copy-small {
    position: absolute;
    top: 50%;
    right: 1rem;
    transform: translateY(-50%);
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--card-bg);
    border: 1px solid var(--glass-border);
    color: var(--text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-copy-small:hover {
    background: var(--card-hover);
    color: var(--text-primary);
    border-color: rgba(255, 255, 255, 0.2);
}

.copy-hint {
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.75rem;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
    white-space: nowrap;
}

.address-container:hover .copy-hint {
    opacity: 1;
}

/* Wallet Balance Details */
.wallet-balance-details {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    margin-bottom: 1.5rem;
}

.balance-item {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.balance-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.balance-icon.available {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
}

.balance-icon.staked {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
}

.balance-info {
    flex: 1;
    min-width: 0;
}

.balance-info small {
    display: block;
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-bottom: 0.25rem;
}

.balance-info .fw-semibold {
    font-size: 0.938rem;
    color: var(--text-primary);
    font-weight: 600;
}

.balance-divider {
    width: 1px;
    background: var(--glass-border);
}

/* Wallet Actions */
.wallet-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}

.action-btn {
    padding: 0.75rem;
    border-radius: 12px;
    font-weight: 500;
    transition: all 0.3s ease;
    text-align: center;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.deposit-btn {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    border: none;
    color: white;
}

.deposit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    color: white;
}

.withdraw-btn:hover {
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.2);
}

/* Empty State Modern */
.empty-state-modern {
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    padding: 4rem 2rem;
    text-align: center;
    margin-bottom: 2rem;
}

.empty-icon-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: var(--text-muted);
    margin-bottom: 1.5rem;
}

.empty-state-modern h4 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

/* Supported Coins Modern */
.supported-coins-modern {
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    overflow: hidden;
}

.supported-coins-modern .card-header {
    background: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid var(--glass-border);
    padding: 1rem 1.5rem;
}

.coins-icon {
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

.coin-item-modern {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    height: 100%;
}

.coin-item-modern:hover {
    transform: translateY(-4px);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.coin-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    flex-shrink: 0;
}

.coin-info {
    flex: 1;
    min-width: 0;
}

.coin-symbol {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.coin-name {
    font-size: 0.813rem;
    color: var(--text-muted);
    display: block;
}

/* Modern Input & Select (from previous designs) */
.input-modern {
    margin-bottom: 1rem;
}

.input-modern .form-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
}

.form-select-modern {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    color: var(--text-primary);
    font-size: 0.875rem;
    transition: all 0.3s ease;
    width: 100%;
}

.form-select-modern:focus {
    background: var(--card-bg);
    border-color: rgba(255, 255, 255, 0.3);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    color: var(--text-primary);
}

/* Modal */
.modal-content {
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    overflow: hidden;
}

.modal-header {
    background: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid var(--glass-border);
    padding: 1.5rem;
}

.modal-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.modal-title {
    color: var(--text-primary);
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    background: rgba(255, 255, 255, 0.05);
    border-top: 1px solid var(--glass-border);
    padding: 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-header .d-flex {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .wallet-stats-header {
        justify-content: center;
    }
    
    .wallet-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .wallet-balance-badge {
        width: 100%;
        max-width: 200px;
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
        grid-template-columns: 1fr;
    }
    
    .coin-item-modern {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .coin-icon {
        width: 60px;
        height: 60px;
        font-size: 1.75rem;
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
        
        // Create temporary input element
        const tempInput = document.createElement('input');
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        tempInput.setSelectionRange(0, 99999);
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        // Show feedback
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
    
    // Initialize modal functionality
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