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
        Schema::table('new_lead_appointments', function (Blueprint $table) {
            $table->string('zoom_link')->nullable()->after('google_meet_link');
            $table->string('zoom_meeting_id')->nullable()->after('zoom_link');
            $table->string('zoom_meeting_password')->nullable()->after('zoom_meeting_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('new_lead_appointments', function (Blueprint $table) {
            $table->dropColumn(['zoom_link', 'zoom_meeting_id', 'zoom_meeting_password']);
        });
    }
};
