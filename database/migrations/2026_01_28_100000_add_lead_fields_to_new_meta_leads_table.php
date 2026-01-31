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
        if (!Schema::hasTable('new_meta_leads')) {
            return;
        }

        Schema::table('new_meta_leads', function (Blueprint $table) {
            if (!Schema::hasColumn('new_meta_leads', 'new_lead_id')) {
                $table->unsignedBigInteger('new_lead_id')->nullable()->after('status');
                $table->foreign('new_lead_id')->references('id')->on('new_leads')->onDelete('set null')->onUpdate('cascade');
            }
            if (!Schema::hasColumn('new_meta_leads', 'lead_number')) {
                $table->string('lead_number')->nullable()->after('new_lead_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('new_meta_leads')) {
            return;
        }

        Schema::table('new_meta_leads', function (Blueprint $table) {
            if (Schema::hasColumn('new_meta_leads', 'new_lead_id')) {
                $table->dropForeign(['new_lead_id']);
                $table->dropColumn('new_lead_id');
            }
            if (Schema::hasColumn('new_meta_leads', 'lead_number')) {
                $table->dropColumn('lead_number');
            }
        });
    }
};
