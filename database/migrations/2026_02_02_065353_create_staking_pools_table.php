<?php
// database/migrations/2024_01_02_create_staking_pools_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStakingPoolsTable extends Migration
{
    public function up()
    {
        Schema::create('staking_pools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('coin_icon')->nullable();
            $table->enum('coin_type', ['HIVE', 'STEEM', 'BTC', 'ETH', 'BNB', 'SOL', 'ADA']);
            $table->decimal('apy', 5, 2);
            $table->integer('duration_minutes');
            $table->decimal('min_stake', 20, 8);
            $table->decimal('max_stake', 20, 8)->nullable();
            $table->decimal('total_staked', 20, 8)->default(0);
            $table->integer('total_stakers')->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('coin_type');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('staking_pools');
    }
}