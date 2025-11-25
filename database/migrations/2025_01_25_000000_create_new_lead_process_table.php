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
        if (Schema::hasTable('new_lead_process')) {
            return;
        }
        
        Schema::create('new_lead_process', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('new_lead_id');
            $table->foreign('new_lead_id')->references('id')->on('new_leads')->onDelete('cascade')->onUpdate('cascade');
            
            // Agent & Applicant Details
            $table->string('applicant_name')->nullable();
            $table->string('visa_category')->nullable();
            $table->string('subclass')->nullable();
            $table->string('passport_name')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('agent_name')->nullable();
            
            // All Fees
            $table->string('advance_fees')->nullable();
            $table->date('advance_fees_due_date')->nullable();
            $table->string('remaining_fees')->nullable();
            $table->date('remaining_fees_due_date')->nullable();
            $table->string('agent_fees')->nullable();
            $table->string('submission_fees')->nullable();
            
            // Process & Status
            $table->string('status')->nullable();
            $table->string('processing_time')->nullable();
            $table->date('bank_cheque_handover_date')->nullable();
            $table->date('passport_handover_date')->nullable();
            $table->text('process_note')->nullable();
            
            // Upload Documents (file paths)
            $table->string('contract_letter')->nullable();
            $table->string('grant_letter')->nullable();
            $table->string('offer_letter')->nullable();
            $table->string('medical_letter')->nullable();
            $table->string('air_ticket')->nullable();
            $table->string('accommodation_letter')->nullable();
            
            $table->unsignedInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->unsignedInteger('last_updated_by')->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_lead_process');
    }
};

