<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\StakingRecord;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'total_balance' => $user->wallets()->sum('balance'),
            'total_staking' => $user->wallets()->sum('staking_balance'),
            'total_earned' => $user->wallets()->sum('total_earned'),
            'active_stakes' => $user->stakingRecords()->where('status', 'active')->count(),
        ];
        
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        $activeStakes = StakingRecord::with('stakingPool')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->orderBy('end_date', 'asc')
            ->limit(5)
            ->get();
            
        $wallets = Wallet::where('user_id', $user->id)
            ->orderBy('balance', 'desc')
            ->get();
        
        return view('dashboard.index', compact('stats', 'recentTransactions', 'activeStakes', 'wallets'));
    }
}