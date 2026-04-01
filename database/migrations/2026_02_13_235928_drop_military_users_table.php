<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('military_users');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Opcional: recriar se necessário
    }
};
