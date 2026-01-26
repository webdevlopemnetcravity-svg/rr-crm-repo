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
        if (Schema::hasTable('new_facebook_token')) {
            return;
        }
        
        Schema::create('new_facebook_token', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->enum('verification_status', ['verified', 'non_verified'])->default('non_verified');
            $table->timestamps();
            
            $table->unique(['user_id', 'company_id']);
            $table->index(['user_id', 'verification_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_facebook_token');
    }
};
