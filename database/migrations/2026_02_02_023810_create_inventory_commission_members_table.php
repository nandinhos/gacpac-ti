<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("inventory_commission_members", function (Blueprint $table) {
            $table->id();
            $table->foreignId("inventory_record_id")->constrained("inventory_records")->onDelete("cascade");
            $table->foreignId("military_user_id")->constrained("military_users")->onDelete("cascade");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("inventory_commission_members");
    }
};
