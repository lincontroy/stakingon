<?php
// database/migrations/2024_01_01_create_wallets_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('coin_type', ['HIVE', 'STEEM', 'BTC', 'ETH', 'BNB', 'SOL', 'ADA']);
            $table->string('address')->unique();
            $table->decimal('balance', 20, 8)->default(0);
            $table->decimal('staking_balance', 20, 8)->default(0);
            $table->decimal('available_balance', 20, 8)->default(0);
            $table->decimal('total_earned', 20, 8)->default(0);
            $table->timestamps();
            
            $table->unique(['user_id', 'coin_type']);
            $table->index('coin_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallets');
    }
}