<?php
// app/Http/Controllers/TransactionController.php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Transaction::where('user_id', $user->id);
        
        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('coin_type')) {
            $query->where('coin_type', $request->coin_type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('transactions.index', compact('transactions'));
    }
    
    public function show($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
            
        return view('transactions.show', compact('transaction'));
    }
}