<?php
// database/migrations/2024_01_03_create_staking_records_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStakingRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('staking_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('staking_pool_id')->constrained()->onDelete('cascade');
            $table->string('transaction_hash')->unique()->nullable();
            $table->decimal('amount', 20, 8);
            $table->decimal('expected_reward', 20, 8);
            $table->decimal('actual_reward', 20, 8)->nullable();
            $table->timestamp('start_date')->useCurrent();
            $table->timestamp('end_date')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled', 'pending'])->default('pending');
            $table->boolean('reward_claimed')->default(false);
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('status');
            $table->index(['status', 'end_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('staking_records');
    }
}