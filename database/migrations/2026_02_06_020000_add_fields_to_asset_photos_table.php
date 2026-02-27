<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_photos', function (Blueprint $table) {
            $table->boolean('is_primary')->default(false)->after('mime_type');
            $table->unsignedInteger('file_size')->nullable()->after('is_primary');
        });
    }

    public function down(): void
    {
        Schema::table('asset_photos', function (Blueprint $table) {
            $table->dropColumn(['is_primary', 'file_size']);
        });
    }
};
