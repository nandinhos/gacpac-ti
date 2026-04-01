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
        Schema::table('custody_logs', function (Blueprint $table) {
            $table->string('signed_document_url')->nullable()->after('notes');
            $table->timestamp('signed_document_uploaded_at')->nullable()->after('signed_document_url');
            $table->text('signed_document_justification')->nullable()->after('signed_document_uploaded_at');
            $table->timestamp('signed_document_removed_at')->nullable()->after('signed_document_justification');
            $table->text('signed_document_removal_justification')->nullable()->after('signed_document_removed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custody_logs', function (Blueprint $table) {
            $table->dropColumn([
                'signed_document_url',
                'signed_document_uploaded_at',
                'signed_document_justification',
                'signed_document_removed_at',
                'signed_document_removal_justification',
            ]);
        });
    }
};
