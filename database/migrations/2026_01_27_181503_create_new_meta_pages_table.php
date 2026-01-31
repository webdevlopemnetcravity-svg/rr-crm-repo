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
        if (Schema::hasTable('new_meta_pages')) {
            return;
        }
        
        Schema::create('new_meta_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('page_id')->unique();
            $table->string('page_name');
            $table->text('page_access_token')->nullable();
            $table->text('page_category')->nullable();
            $table->text('page_picture_url')->nullable();
            $table->text('page_about')->nullable();
            $table->text('page_website')->nullable();
            $table->string('page_verification_status')->nullable();
            $table->integer('page_followers_count')->nullable();
            $table->integer('page_likes_count')->nullable();
            $table->enum('sync_status', ['synced', 'pending', 'failed'])->default('pending');
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'company_id']);
            $table->index('page_id');
            $table->index('sync_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_meta_pages');
    }
};
