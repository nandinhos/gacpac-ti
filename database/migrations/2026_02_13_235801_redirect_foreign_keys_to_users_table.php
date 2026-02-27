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
        // 1. Assets
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['custodian_user_id']);
            $table->foreign('custodian_user_id')->references('id')->on('users')->onDelete('set null');
        });

        // 2. Inventory Records
        Schema::table('inventory_records', function (Blueprint $table) {
            $table->dropForeign(['responsible_user_id']);
            $table->foreign('responsible_user_id')->references('id')->on('users')->onDelete('set null');
        });

        // 3. Custody Logs
        Schema::table('custody_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 4. Inventory Commission Members (tabela pivot)
        Schema::table('inventory_commission_members', function (Blueprint $table) {
            $table->dropForeign(['military_user_id']);
            $table->foreign('military_user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 5. Reopen History
        Schema::table('reopen_history', function (Blueprint $table) {
            $table->dropForeign(['reopened_by_user_id']);
            $table->foreign('reopened_by_user_id')->references('id')->on('users')->onDelete('set null');
        });

        // 6. Uncatalogued Items
        Schema::table('uncatalogued_items', function (Blueprint $table) {
            $table->dropForeign(['created_by_user_id']);
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverter para military_users se necessário (opcional no contexto de unificação)
    }
};
