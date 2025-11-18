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
        Schema::create('inventory_reopen_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventory_records')->onDelete('cascade');
            $table->foreignId('reopened_by_user_id')->constrained('military_users')->onDelete('cascade');
            $table->text('justification');
            $table->timestamp('reopened_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_reopen_histories');
    }
};
