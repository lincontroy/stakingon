<?php
// app/Http/Controllers/StakingController.php

namespace App\Http\Controllers;

use App\Models\StakingPool;
use App\Models\StakingRecord;
use App\Mail\RewardClaimedMail;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class StakingController extends Controller
{
    public function index()
    {
        $pools = StakingPool::where('is_active', true)->get();
        $user = Auth::user();
        
        $activeStakes = StakingRecord::with('stakingPool')
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'pending'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $completedStakes = StakingRecord::with('stakingPool')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('end_date', 'desc')
            ->limit(10)
            ->get();
        
        return view('staking.index', compact('pools', 'activeStakes', 'completedStakes'));
    }
    
    public function show($id)
    {
        $pool = StakingPool::findOrFail($id);
        $user = Auth::user();
        
        $wallet = Wallet::where('user_id', $user->id)
            ->where('coin_type', $pool->coin_type)
            ->first();
            
        return view('staking.show', compact('pool', 'wallet'));
    }
    
    public function stake(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.00000001',
        ]);
        
        $pool = StakingPool::findOrFail($id);
        $user = Auth::user();
        $amount = $request->amount;
        
        // Check pool requirements
        if ($amount < $pool->min_stake) {
            return back()->withErrors(['amount' => "Minimum stake is {$pool->min_stake} {$pool->coin_type}"]);
        }
        
        if ($pool->max_stake && $amount > $pool->max_stake) {
            return back()->withErrors(['amount' => "Maximum stake is {$pool->max_stake} {$pool->coin_type}"]);
        }
        
        // Check wallet balance
        $wallet = Wallet::where('user_id', $user->id)
            ->where('coin_type', $pool->coin_type)
            ->firstOrFail();
            
        if ($wallet->available_balance < $amount) {
            return back()->withErrors(['amount' => 'Insufficient balance']);
        }
        
        DB::beginTransaction();
        
        try {
            // Calculate reward
            $reward = $pool->calculateReward($amount);
            
            // Create staking record
            $stakingRecord = StakingRecord::create([
                'user_id' => $user->id,
                'staking_pool_id' => $pool->id,
                'amount' => $amount,
                'expected_reward' => $reward,
                'start_date' => now(),
                'end_date' => now()->addMinutes($pool->duration_minutes),
                'status' => 'active',
            ]);
            
            // Update wallet
            $wallet->available_balance -= $amount;
            $wallet->balance -= $amount;
            $wallet->staking_balance += $amount;
            $wallet->save();
            
            // Update pool statistics
            $pool->total_staked += $amount;
            $pool->total_stakers += 1;
            $pool->save();
            
            // Create transaction record
            Transaction::create([
                'user_id' => $user->id,
                'txid' => 'STK-' . strtoupper(uniqid()),
                'type' => 'staking',
                'coin_type' => $pool->coin_type,
                'amount' => $amount,
                'status' => 'completed',
                'description' => "Staked {$amount} {$pool->coin_type} in {$pool->name}",
                'metadata' => [
                    'staking_record_id' => $stakingRecord->id,
                    'pool_name' => $pool->name,
                    'duration' => $pool->duration_minutes,
                    'expected_reward' => $reward,
                ],
            ]);
            
            DB::commit();
            
            return redirect()->route('staking.index')
                ->with('success', "Successfully staked {$amount} {$pool->coin_type}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Staking failed. Please try again.']);
        }
    }
    
    public function claimReward($id)
    {
        $stakingRecord = StakingRecord::with('stakingPool')
            ->where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
            
        if ($stakingRecord->status !== 'active' || now() < $stakingRecord->end_date) {
            return back()->withErrors(['error' => 'Reward is not ready to claim yet']);
        }
        
        DB::beginTransaction();
        
        try {
            // Calculate actual reward (could add dynamic calculation here)
            $actualReward = $stakingRecord->expected_reward+$stakingRecord->amount; // For simplicity, reward is amount + expected_reward


            dd($actualReward);
            
            
            // Update staking record
            $stakingRecord->status = 'completed';
            $stakingRecord->actual_reward = $actualReward;
            $stakingRecord->reward_claimed = true;
            $stakingRecord->save();
            
            // Update wallet
            $wallet = Wallet::where('user_id', Auth::id())
                ->where('coin_type', $stakingRecord->stakingPool->coin_type)
                ->firstOrFail();
                
            $wallet->staking_balance -= $stakingRecord->amount;
            $wallet->available_balance  += $actualReward;
            $wallet->balance += $actualReward;
            $wallet->total_earned += $actualReward;
            $wallet->save();
            
            // Create reward transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'txid' => 'REW-' . strtoupper(uniqid()),
                'type' => 'reward',
                'coin_type' => $stakingRecord->stakingPool->coin_type,
                'amount' => $actualReward,
                'status' => 'completed',
                'description' => "Staking reward from {$stakingRecord->stakingPool->name}",
                'metadata' => [
                    'staking_record_id' => $stakingRecord->id,
                    'original_stake' => $stakingRecord->amount,
                    'pool_name' => $stakingRecord->stakingPool->name,
                ],
            ]);
            
            DB::commit();

            // Send email notification
            try {
                Mail::to(Auth::user()->email)->send(new RewardClaimedMail($stakingRecord, $wallet, $transaction));
            } catch (\Exception $e) {
                // Log email error but don't stop the process
                \Log::error('Failed to send reward email: ' . $e->getMessage());
            }
            
            return back()->with('success', "Reward of {$actualReward} {$stakingRecord->stakingPool->coin_type} claimed successfully. Check your email for confirmation!");
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Reward claim failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to claim reward']);
        }
    }
}