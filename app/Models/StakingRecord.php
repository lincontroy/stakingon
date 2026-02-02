<?php
// app/Models/StakingRecord.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StakingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'staking_pool_id',
        'transaction_hash',
        'amount',
        'expected_reward',
        'actual_reward',
        'start_date',
        'end_date',
        'status',
        'reward_claimed',
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'expected_reward' => 'decimal:8',
        'actual_reward' => 'decimal:8',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'reward_claimed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stakingPool()
    {
        return $this->belongsTo(StakingPool::class);
    }

    public function getRemainingTimeAttribute()
    {
        if ($this->status !== 'active') {
            return 'Completed';
        }
        
        $now = now();
        $end = $this->end_date;
        
        if ($now >= $end) {
            return 'Ready to claim';
        }
        
        $diff = $end->diff($now);
        
        if ($diff->days > 0) {
            return "{$diff->days}d {$diff->h}h";
        } elseif ($diff->h > 0) {
            return "{$diff->h}h {$diff->i}m";
        } else {
            return "{$diff->i}m {$diff->s}s";
        }
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->status !== 'active') {
            return 100;
        }
        
        $total = $this->end_date->diffInSeconds($this->start_date);
        $elapsed = now()->diffInSeconds($this->start_date);
        
        return min(100, ($elapsed / $total) * 100);
    }
}