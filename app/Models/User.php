<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'two_factor_enabled',
        'wallet_address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_enabled' => 'boolean',
    ];

    // Relationships
    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function stakingRecords()
    {
        return $this->hasMany(StakingRecord::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Accessors
    public function getTotalBalanceAttribute()
    {
        return $this->wallets()->sum('balance');
    }

    public function getTotalStakingBalanceAttribute()
    {
        return $this->wallets()->sum('staking_balance');
    }

    public function getTotalEarnedAttribute()
    {
        return $this->wallets()->sum('total_earned');
    }

    public function getFormattedTotalBalanceAttribute()
    {
        return number_format($this->total_balance, 8);
    }

    public function getFormattedTotalStakingBalanceAttribute()
    {
        return number_format($this->total_staking_balance, 8);
    }
}