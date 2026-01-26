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
            $table->text('facebook_secret_id')->nullable()->change();
            $table->text('google_secret_id')->nullable()->change();
            $table->text('twitter_secret_id')->nullable()->change();
            $table->text('linkedin_secret_id')->nullable()->change();
            
            if (Schema::hasColumn('social_auth_settings', 'microsoft_secret_id')) {
                $table->text('microsoft_secret_id')->nullable()->change();
            }

            if (Schema::hasColumn('social_auth_settings', 'zoom_client_secret')) {
                $table->text('zoom_client_secret')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_auth_settings', function (Blueprint $table) {
            $table->string('facebook_secret_id')->nullable()->change();
            $table->string('google_secret_id')->nullable()->change();
            $table->string('twitter_secret_id')->nullable()->change();
            $table->string('linkedin_secret_id')->nullable()->change();

            if (Schema::hasColumn('social_auth_settings', 'microsoft_secret_id')) {
                $table->string('microsoft_secret_id')->nullable()->change();
            }

            if (Schema::hasColumn('social_auth_settings', 'zoom_client_secret')) {
                $table->string('zoom_client_secret')->nullable()->change();
            }
        });
    }
};
