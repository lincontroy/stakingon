<?php
// app/Models/Transaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'txid',
        'type',
        'coin_type',
        'amount',
        'fee',
        'from_address',
        'to_address',
        'status',
        'description',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'fee' => 'decimal:8',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeIconAttribute()
    {
        $icons = [
            'deposit' => 'bi-arrow-down-circle text-success',
            'withdrawal' => 'bi-arrow-up-circle text-danger',
            'staking' => 'bi-lock text-primary',
            'reward' => 'bi-gift text-warning',
            'unstaking' => 'bi-unlock text-info',
        ];
        
        return $icons[$this->type] ?? 'bi-arrow-left-right';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'completed' => 'success',
            'failed' => 'danger',
            'cancelled' => 'secondary',
        ];
        
        return $badges[$this->status] ?? 'secondary';
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
}