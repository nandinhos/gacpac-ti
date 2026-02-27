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
            $table->boolean('is_military')->default(true)->after('email');
            $table->string('force')->default('FAB')->after('is_military'); // FAB, EB, MB, SC
            $table->string('rank')->nullable()->after('force'); // Posto/Graduação ou Cargo
            $table->string('military_id')->nullable()->after('rank'); // SARAM ou CPF
            $table->string('organization')->default('GAC-PAC')->after('military_id'); // GAC-PAC, ECP-GPX, ECP-IJA, ECP-POA
            $table->foreignId('sector_id')->nullable()->constrained()->onDelete('set null')->after('organization');
            $table->boolean('is_active')->default(true)->after('sector_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sector_id']);
            $table->dropColumn([
                'is_military',
                'force',
                'rank',
                'military_id',
                'organization',
                'sector_id',
                'is_active'
            ]);
        });
    }
};
