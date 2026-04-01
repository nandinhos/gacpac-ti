<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('assets')) {
            return;
        }

        Schema::table('assets', function (Blueprint $table) {
            if (! Schema::hasColumn('assets', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('category')->constrained('categories')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('assets')) {
            return;
        }

        Schema::table('assets', function (Blueprint $table) {
            if (Schema::hasColumn('assets', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
        });
    }
};
