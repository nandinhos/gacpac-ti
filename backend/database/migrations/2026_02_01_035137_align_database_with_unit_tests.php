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
        Schema::table('sectors', function (Blueprint $table) {
            if (!Schema::hasColumn('sectors', 'code')) {
                $table->string('code')->unique()->nullable()->after('name');
            }
            if (!Schema::hasColumn('sectors', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('military_users', function (Blueprint $table) {
            if (!Schema::hasColumn('military_users', 'registration')) {
                $table->string('registration')->unique()->nullable()->after('military_id');
            }
            if (!Schema::hasColumn('military_users', 'role')) {
                $table->string('role')->nullable()->after('user_role');
            }
            // Garantir que email seja único (não era no esquema base)
            $table->string('email')->unique()->change();
            
            if (!Schema::hasColumn('military_users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('assets', function (Blueprint $table) {
            if (!Schema::hasColumn('assets', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sectors', function (Blueprint $table) {
            $table->dropColumn(['code', 'deleted_at']);
        });

        Schema::table('military_users', function (Blueprint $table) {
            $table->dropColumn(['registration', 'role', 'deleted_at']);
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
