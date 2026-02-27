<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->string('type')->default('corretiva')->after('asset_id');
            $table->date('next_maintenance_date')->nullable()->after('cost');
            $table->text('notes')->nullable()->after('next_maintenance_date');
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->dropColumn(['type', 'next_maintenance_date', 'notes']);
        });
    }
};
