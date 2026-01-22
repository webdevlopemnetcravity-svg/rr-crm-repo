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
        if (!Schema::hasColumn('social_auth_settings', 'microsoft_client_id')) {
            Schema::table('social_auth_settings', function (Blueprint $table) {
                $table->string('microsoft_client_id')->nullable()->after('linkedin_status');
            });
        }
        
        if (!Schema::hasColumn('social_auth_settings', 'microsoft_secret_id')) {
            Schema::table('social_auth_settings', function (Blueprint $table) {
                $table->string('microsoft_secret_id')->nullable()->after('microsoft_client_id');
            });
        }
        
        if (!Schema::hasColumn('social_auth_settings', 'microsoft_status')) {
            Schema::table('social_auth_settings', function (Blueprint $table) {
                $table->enum('microsoft_status', ['enable', 'disable'])->default('disable')->after('microsoft_secret_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('social_auth_settings', 'microsoft_client_id') ||
            Schema::hasColumn('social_auth_settings', 'microsoft_secret_id') ||
            Schema::hasColumn('social_auth_settings', 'microsoft_status')) {
            Schema::table('social_auth_settings', function (Blueprint $table) {
                $columnsToDrop = [];
                if (Schema::hasColumn('social_auth_settings', 'microsoft_client_id')) {
                    $columnsToDrop[] = 'microsoft_client_id';
                }
                if (Schema::hasColumn('social_auth_settings', 'microsoft_secret_id')) {
                    $columnsToDrop[] = 'microsoft_secret_id';
                }
                if (Schema::hasColumn('social_auth_settings', 'microsoft_status')) {
                    $columnsToDrop[] = 'microsoft_status';
                }
                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }
    }
};
