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
        Schema::table('new_lead_account', function (Blueprint $table) {
            $table->enum('status', ['pending', 'received'])->default('pending')->after('invoice_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('new_lead_account', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};

