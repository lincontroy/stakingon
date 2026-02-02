<?php
// database/migrations/2024_01_04_create_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('txid')->unique();
            $table->enum('type', ['deposit', 'withdrawal', 'staking', 'reward', 'unstaking']);
            $table->enum('coin_type', ['HIVE', 'STEEM', 'BTC', 'ETH', 'BNB', 'SOL', 'ADA']);
            $table->decimal('amount', 20, 8);
            $table->decimal('fee', 20, 8)->default(0);
            $table->string('from_address')->nullable();
            $table->string('to_address')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'type']);
            $table->index(['coin_type', 'status']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}