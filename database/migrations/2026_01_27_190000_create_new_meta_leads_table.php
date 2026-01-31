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
        if (Schema::hasTable('new_meta_leads')) {
            return;
        }

        Schema::create('new_meta_leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('page_id')->nullable();
            $table->string('page_name')->nullable();
            $table->string('form_id')->nullable();
            $table->string('form_name')->nullable();
            $table->string('meta_lead_id')->unique();
            $table->text('field_data')->nullable(); // JSON from Facebook
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('lead_created_time')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();

            $table->index(['user_id', 'company_id']);
            $table->index('page_id');
            $table->index('form_id');
            $table->index('lead_created_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_meta_leads');
    }
};
