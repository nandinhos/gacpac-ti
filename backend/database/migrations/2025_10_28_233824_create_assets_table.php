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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('qr_code')->unique();
            $table->string('name');
            $table->string('category');
            $table->string('subcategory')->nullable();
            $table->text('description')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('patrimony_id')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->date('acquisition_date')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->string('status');
            $table->integer('condition_rating')->nullable();
            $table->foreignId('sector_id')->nullable()->constrained('sectors')->onDelete('set null');
            $table->string('location')->nullable();
            $table->foreignId('custodian_user_id')->nullable()->constrained('military_users')->onDelete('set null');
            $table->text('notes')->nullable();
            // New inventory fields
            $table->string('conta')->nullable();
            $table->string('categoria_inventario')->nullable();
            $table->string('bmp')->nullable();
            $table->string('componente')->nullable();
            $table->string('situacao')->nullable();
            $table->integer('qtd')->nullable();
            $table->decimal('valor_atualizado', 15, 2)->nullable();
            $table->decimal('deprec_acumulada', 15, 2)->nullable();
            $table->decimal('valor_liquido', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
