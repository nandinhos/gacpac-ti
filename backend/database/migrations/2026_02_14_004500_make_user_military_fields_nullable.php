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
        Schema::table('users', function (Blueprint $table) {
            $table->string('force')->nullable()->change();
            $table->string('organization')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverter para NOT NULL com default (atenção: dados NULL causarão erro na reversão)
            // Para segurança, definimos um default antes de alterar se houver nulls
            DB::table('users')->whereNull('force')->update(['force' => 'FAB']);
            DB::table('users')->whereNull('organization')->update(['organization' => 'GAC-PAC']);
            
            $table->string('force')->default('FAB')->nullable(false)->change();
            $table->string('organization')->default('GAC-PAC')->nullable(false)->change();
        });
    }
};
