<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StakingPool;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create sample staking pools
        StakingPool::create([
            'name' => 'HIVE Basic Pool',
            'coin_type' => 'HIVE',
            'apy' => 5.25,
            'duration_minutes' => 15, // 15 minutes for testing
            'min_stake' => 1.0,
            'max_stake' => 10000.0,
            'description' => 'Basic HIVE staking pool with competitive returns',
            'is_active' => true,
        ]);
        
        StakingPool::create([
            'name' => 'STEEM Starter Pool',
            'coin_type' => 'STEEM',
            'apy' => 4.75,
            'duration_minutes' => 15,
            'min_stake' => 5.0,
            'max_stake' => 5000.0,
            'description' => 'Perfect for STEEM holders looking to earn passive income',
            'is_active' => true,
        ]);
        
        StakingPool::create([
            'name' => 'Bitcoin Premium',
            'coin_type' => 'BTC',
            'apy' => 3.50,
            'duration_minutes' => 30,
            'min_stake' => 0.001,
            'max_stake' => 10.0,
            'description' => 'Bitcoin staking with premium security',
            'is_active' => true,
        ]);
        
        StakingPool::create([
            'name' => 'Ethereum Smart Pool',
            'coin_type' => 'ETH',
            'apy' => 4.20,
            'duration_minutes' => 20,
            'min_stake' => 0.1,
            'max_stake' => 100.0,
            'description' => 'Ethereum staking powered by smart contracts',
            'is_active' => true,
        ]);
        
        StakingPool::create([
            'name' => 'BNB High Yield',
            'coin_type' => 'BNB',
            'apy' => 6.50,
            'duration_minutes' => 25,
            'min_stake' => 0.5,
            'max_stake' => 5000.0,
            'description' => 'High yield BNB staking pool',
            'is_active' => true,
        ]);
        
        // Add more pools as needed
    }
}