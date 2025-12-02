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
        if (!Schema::hasColumn('new_lead_process', 'additional_documents')) {
            Schema::table('new_lead_process', function (Blueprint $table) {
                $table->json('additional_documents')->nullable()->after('accommodation_letter');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('new_lead_process', 'additional_documents')) {
            Schema::table('new_lead_process', function (Blueprint $table) {
                $table->dropColumn('additional_documents');
            });
        }
    }
};
