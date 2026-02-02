<?php
// database/migrations/2024_01_05_add_profile_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_image')->nullable()->after('email');
            $table->boolean('two_factor_enabled')->default(false)->after('profile_image');
            $table->string('wallet_address')->nullable()->after('two_factor_enabled');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_image', 'two_factor_enabled', 'wallet_address']);
        });
    }
}