<?php
// app/Models/StakingPool.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StakingPool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'coin_icon',
        'coin_type',
        'apy',
        'duration_minutes',
        'min_stake',
        'max_stake',
        'total_staked',
        'total_stakers',
        'is_active',
        'description',
    ];

    protected $casts = [
        'apy' => 'decimal:2',
        'min_stake' => 'decimal:8',
        'max_stake' => 'decimal:8',
        'total_staked' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    public function stakingRecords()
    {
        return $this->hasMany(StakingRecord::class);
    }

    public function getDurationTextAttribute()
    {
        $minutes = $this->duration_minutes;
        
        if ($minutes < 60) {
            return "{$minutes} minutes";
        } elseif ($minutes < 1440) {
            $hours = floor($minutes / 60);
            return "{$hours} hour" . ($hours > 1 ? 's' : '');
        } else {
            $days = floor($minutes / 1440);
            return "{$days} day" . ($days > 1 ? 's' : '');
        }
    }

    public function getDailyRewardRateAttribute()
    {
        return ($this->apy / 365) / 100;
    }

    public function calculateReward($amount, $durationMinutes = null)
    {
        $duration = $durationMinutes ?? $this->duration_minutes;
        $dailyRate = $this->daily_reward_rate;
        $days = $duration / 1440; // Convert minutes to days
        
        return $amount * $dailyRate * $days;
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