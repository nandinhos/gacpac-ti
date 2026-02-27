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
        Schema::table('assets', function (Blueprint $table) {
            // Adicionar novos campos esperados pelos Form Requests
            $table->string('brand')->nullable()->after('model'); // manufacturer -> brand
            $table->string('type')->nullable()->after('category'); // novo campo
            $table->string('condition')->nullable()->after('condition_rating'); // condition_rating -> condition
            $table->string('patrimony_number')->nullable()->after('patrimony_id'); // patrimony_id -> patrimony_number
            $table->decimal('purchase_value', 10, 2)->nullable()->after('purchase_price'); // purchase_price -> purchase_value
            
            // Manter campos antigos por compatibilidade (não remover ainda)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn([
                'brand',
                'type', 
                'condition',
                'patrimony_number',
                'purchase_value'
            ]);
        });
    }
};
