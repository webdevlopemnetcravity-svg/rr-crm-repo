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
        if (Schema::hasTable('new_leads')) {
            return;
        }
        
        Schema::create('new_leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->string('client_name')->nullable();
            $table->string('client_email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('lead_source')->nullable();
            $table->enum('priority', ['Select Priority', '1st Priority', '2nd Priority', '3rd Priority', '4th Priority', '5th Priority'])->default('Select Priority');
            $table->string('lead_status')->default('Open Lead');
            $table->string('lead_quality')->default('Open');
            $table->unsignedInteger('lead_owner')->nullable();
            $table->foreign('lead_owner')->references('id')->on('users')->onDelete('set null');
            $table->unsignedInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('set null');
            $table->unsignedInteger('last_updated_by')->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('set null');
            $table->json('step_1_data')->nullable()->comment('Personal Details - Step 1 data');
            $table->json('step_2_data')->nullable()->comment('Client Preference - Step 2 data');
            $table->json('step_3_data')->nullable()->comment('Passport Details - Step 3 data');
            $table->json('step_4_data')->nullable()->comment('Relative Contact Information - Step 4 data');
            $table->json('step_5_data')->nullable()->comment('Family Information - Step 5 data');
            $table->json('step_6_data')->nullable()->comment('Education - Step 6 data');
            $table->json('step_7_data')->nullable()->comment('Professional Experience - Step 7 data');
            $table->json('step_8_data')->nullable()->comment('Property Details - Step 8 data');
            $table->json('step_9_data')->nullable()->comment('Financial Status - Step 9 data');
            $table->text('note')->nullable();
            $table->string('hash')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_leads');
    }
};
