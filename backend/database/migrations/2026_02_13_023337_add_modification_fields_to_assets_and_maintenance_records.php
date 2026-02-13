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
            $table->boolean('is_modified')->default(false)->after('notes');
        });

        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->boolean('is_upgrade')->default(false)->after('type');
            $table->text('parts_replaced')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('is_modified');
        });

        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->dropColumn(['is_upgrade', 'parts_replaced']);
        });
    }
};
