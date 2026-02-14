<?php
// app/Http/Controllers/WalletController.php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wallets = Wallet::where('user_id', $user->id)->get();
        
        // Only support Hive and Steem initially
        $supportedCoins = [
            [
                'type' => 'HIVE', 
                'name' => 'Hive', 
                'icon' => 'bi-currency-bitcoin',
                'gradient_start' => '#FF0066',
                'gradient_end' => '#FF6600'
            ],
            [
                'type' => 'STEEM', 
                'name' => 'Steem', 
                'icon' => 'bi-currency-exchange',
                'gradient_start' => '#4A90E2',
                'gradient_end' => '#2ECC71'
            ],
            [
                'type' => 'USDT',
                'name' => 'usdt',
                'icon' => 'bi-currency-exchange',   // good choice, or use 'bi-cash-coin' / custom USDT icon
                'gradient_start' => '#26A17B',      // official Jungle Green
                'gradient_end' => '#009393',        // official Aqua / darker-lighter variant for nice flow
            ]
        ];
        
        return view('wallet.index', compact('wallets', 'supportedCoins'));
    }
    
    public function createWallet(Request $request)
    {
        $request->validate([
            'coin_type' => 'required|in:HIVE,STEEM,USDT',
        ]);
        
        $user = Auth::user();
        $coinType = $request->coin_type;
        
        // Check if wallet already exists
        $existing = Wallet::where('user_id', $user->id)
            ->where('coin_type', $coinType)
            ->exists();
            
        if ($existing) {
            return back()->withErrors(['error' => 'Wallet for this coin already exists']);
        }
        
        // Generate the appropriate username-based address
        $address = $this->generateSteemHiveAddress($coinType, $user);
        
        Wallet::create([
            'user_id' => $user->id,
            'coin_type' => $coinType,
            'address' => $address,
            'balance' => 0,
            'staking_balance' => 0,
            'available_balance' => 0,
            'total_earned' => 0,
            'coinIcon' => $coinType === 'HIVE' ? 'bi-currency-bitcoin' : 'bi-currency-exchange',
        ]);
        
        return back()->with('success', "{$coinType} wallet created successfully");
    }
    
    public function deposit($coinType)
    {
        $wallet = Wallet::where('user_id', Auth::id())
            ->where('coin_type', $coinType)
            ->firstOrFail();
            
        return view('wallet.deposit', compact('wallet'));
    }
    
    public function withdraw($coinType)
    {
        $wallet = Wallet::where('user_id', Auth::id())
            ->where('coin_type', $coinType)
            ->firstOrFail();
            
        return view('wallet.withdraw', compact('wallet'));
    }
    
    public function processWithdrawal(Request $request, $coinType)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.001',
            'address' => 'required',
            'memo' => 'max:255',
        ]);
        
        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)
            ->where('coin_type', $coinType)
            ->firstOrFail();
            
        $amount = $request->amount;
        $fee = $this->calculateWithdrawalFee($coinType, $amount);
        $total = $amount + $fee;
        
        if ($wallet->available_balance < $total) {
            return back()->withErrors(['amount' => 'Insufficient balance including fee']);
        }
        
        DB::beginTransaction();
        
        try {
            // Update wallet
            $wallet->available_balance -= $total;
            $wallet->balance -= $total;
            $wallet->save();
            
            // Create withdrawal transaction
            Transaction::create([
                'user_id' => $user->id,
                'txid' => 'WDL-' . strtoupper(Str::random(16)),
                'type' => 'withdrawal',
                'coin_type' => $coinType,
                'amount' => $amount,
                'fee' => $fee,
                'from_address' => $wallet->address,
                'to_address' => $request->address,
                'status' => 'pending',
                'description' => "Withdrawal to {$request->address}",
                'memo' => $request->memo,
                'metadata' => [
                    'original_amount' => $amount,
                    'fee' => $fee,
                    'total_debited' => $total,
                    'network' => strtolower($coinType),
                ],
            ]);
            
            DB::commit();
            
            return redirect()->route('wallet.index')
                ->with('success', "Withdrawal submitted! {$amount} {$coinType} will be sent to bdhivesteem memo {$request->memo}");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Withdrawal failed: ' . $e->getMessage()]);
        }
    }
    
    public function simulateDeposit(Request $request, $coinType)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.001',
        ]);
        
        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)
            ->where('coin_type', $coinType)
            ->firstOrFail();
            
        DB::beginTransaction();
        
        try {
            $amount = $request->amount;
            
            // Update wallet
            $wallet->balance += $amount;
            $wallet->available_balance += $amount;
            $wallet->save();
            
            // Create deposit transaction
            Transaction::create([
                'user_id' => $user->id,
                'txid' => 'DEP-' . strtoupper(Str::random(16)),
                'type' => 'deposit',
                'coin_type' => $coinType,
                'amount' => $amount,
                'to_address' => $wallet->address,
                'status' => 'completed',
                'description' => "Test deposit (simulated)",
                'metadata' => [
                    'simulated' => true,
                    'timestamp' => now()->toIso8601String(),
                ],
            ]);
            
            DB::commit();
            
            return back()->with('success', "Successfully deposited {$amount} {$coinType}");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Deposit failed: ' . $e->getMessage()]);
        }
    }
    
    public function processManualDeposit(Request $request, $coinType)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.001',
            'txid' => 'required|string|max:255',
            'from_address' => 'required|string',
        ]);
        
        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)
            ->where('coin_type', $coinType)
            ->firstOrFail();
            
        DB::beginTransaction();
        
        try {
            $amount = $request->amount;
            
            // Update wallet
            $wallet->balance += $amount;
            $wallet->available_balance += $amount;
            $wallet->save();
            
            // Create deposit transaction
            Transaction::create([
                'user_id' => $user->id,
                'txid' => $request->txid,
                'type' => 'deposit',
                'coin_type' => $coinType,
                'amount' => $amount,
                'from_address' => $request->from_address,
                'to_address' => $wallet->address,
                'status' => 'completed',
                'description' => "Manual deposit",
                'metadata' => [
                    'manual' => true,
                    'processed_by' => $user->username,
                    'timestamp' => now()->toIso8601String(),
                ],
            ]);
            
            DB::commit();
            
            return back()->with('success', "Successfully processed deposit of {$amount} {$coinType}");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Deposit processing failed: ' . $e->getMessage()]);
        }
    }
    
    private function generateSteemHiveAddress($coinType, $user)
    {
        // Generate a username-based address for Hive/Steem
        $username = strtolower($user->username ?? $user->name ?? 'user' . $user->id);
        $username = preg_replace('/[^a-z0-9]/', '', $username);
        
        // For Hive and Steem, we use the platform's account system
        // In production, this would be connected to Hive/Steem blockchain
        return '@' . $username . '-' . strtolower($coinType);
    }
    
    private function calculateWithdrawalFee($coinType, $amount)
    {
        // Hive and Steem have minimal fees
        $fees = [
            'HIVE' => 0.001,
            'STEEM' => 0.001,
        ];
        
        return $fees[$coinType] ?? 0.001;
    }
    
    public function getDepositAddress($coinType)
    {
        $user = Auth::user();
        
        // For Hive and Steem, return the stakeonhive/stakeonsteem addresses
        $depositAddresses = [
            'HIVE' => 'stakeonhive',
            'STEEM' => 'stakeonsteem',
        ];
        
        if (!isset($depositAddresses[$coinType])) {
            return response()->json(['error' => 'Unsupported coin'], 400);
        }
        
        // Get or create wallet for the user
        $wallet = Wallet::firstOrCreate(
            [
                'user_id' => $user->id,
                'coin_type' => $coinType,
            ],
            [
                'address' => $this->generateSteemHiveAddress($coinType, $user),
                'balance' => 0,
                'staking_balance' => 0,
                'available_balance' => 0,
                'total_earned' => 0,
                'coinIcon' => $coinType === 'HIVE' ? 'bi-currency-bitcoin' : 'bi-currency-exchange',
            ]
        );
        
        return response()->json([
            'deposit_address' => $depositAddresses[$coinType],
            'coin_type' => $coinType,
            'memo' => 'MEMO-' . strtoupper(Str::random(8)) . '-' . $user->id,
            'instructions' => "Send {$coinType} to @" . $depositAddresses[$coinType] . " with memo",
            'your_wallet' => $wallet->address,
        ]);
    }
}