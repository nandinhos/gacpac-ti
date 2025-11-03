<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('military_users', function (Blueprint $table) {
            $table->string('password')->nullable()->after('email');
            $table->timestamp('email_verified_at')->nullable()->after('password');
            $table->rememberToken()->after('email_verified_at');
            $table->enum('user_role', ['user', 'commission', 'admin'])->default('user')->after('remember_token');
            $table->json('commission_inventories')->nullable()->after('user_role'); // IDs dos inventários que o usuário pode gerenciar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('military_users', function (Blueprint $table) {
            $table->dropColumn([
                'password',
                'email_verified_at', 
                'remember_token',
                'user_role',
                'commission_inventories'
            ]);
        });
    }
};
