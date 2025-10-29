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
        Schema::create('custody_logs', function (Blueprint $table) {
            $table->id();
            $table->string('cautela_number')->unique();
            $table->foreignId('user_id')->constrained('military_users')->onDelete('cascade');
            $table->dateTime('checkout_date');
            $table->dateTime('checkin_date')->nullable();
            $table->string('term_url')->nullable();
            $table->string('signed_term_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custody_logs');
    }
};
