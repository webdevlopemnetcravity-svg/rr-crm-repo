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
        Schema::table('social_auth_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('social_auth_settings', 'zoom_account_id')) {
                $table->string('zoom_account_id')->nullable()->after('microsoft_status');
            }
            if (!Schema::hasColumn('social_auth_settings', 'zoom_client_id')) {
                $table->string('zoom_client_id')->nullable()->after('zoom_account_id');
            }
            if (!Schema::hasColumn('social_auth_settings', 'zoom_client_secret')) {
                $table->text('zoom_client_secret')->nullable()->after('zoom_client_id');
            }
            if (!Schema::hasColumn('social_auth_settings', 'zoom_status')) {
                $table->enum('zoom_status', ['enable', 'disable'])->default('disable')->after('zoom_client_secret');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_auth_settings', function (Blueprint $table) {
            $table->dropColumn(['zoom_account_id', 'zoom_client_id', 'zoom_client_secret', 'zoom_status']);
        });
    }
};
