<?php
// app/Models/Wallet.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coin_type',
        'address',
        'balance',
        'staking_balance',
        'available_balance',
        'total_earned',
    ];

    protected $casts = [
        'balance' => 'decimal:8',
        'staking_balance' => 'decimal:8',
        'available_balance' => 'decimal:8',
        'total_earned' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCoinIconAttribute()
    {
        $icons = [
            'HIVE' => 'bi-currency-bitcoin',
            'STEEM' => 'bi-currency-exchange',
            'BTC' => 'bi-currency-bitcoin',
            'ETH' => 'bi-currency-ethereum',
            'BNB' => 'bi-coin',
            'SOL' => 'bi-lightning',
            'ADA' => 'bi-circle',
        ];
        
        return $icons[$this->coin_type] ?? 'bi-coin';
    }

    public function getCoinColorAttribute()
    {
        $colors = [
            'HIVE' => 'warning',
            'STEEM' => 'info',
            'BTC' => 'warning',
            'ETH' => 'secondary',
            'BNB' => 'warning',
            'SOL' => 'primary',
            'ADA' => 'success',
        ];
        
        return $colors[$this->coin_type] ?? 'primary';
    }

    public function getFormattedBalanceAttribute()
    {
        return number_format($this->balance, 8);
    }

    public function getFormattedAvailableBalanceAttribute()
    {
        return number_format($this->available_balance, 8);
    }

    public function getFormattedStakingBalanceAttribute()
    {
        return number_format($this->staking_balance, 8);
    }
}