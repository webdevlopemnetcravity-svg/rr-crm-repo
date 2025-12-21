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
        if (Schema::hasTable('new_lead_documents')) {
            return;
        }
        
        Schema::create('new_lead_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');
            $table->foreign('lead_id')->references('id')->on('new_leads')->onDelete('cascade')->onUpdate('cascade');
            $table->json('main_applicant_documents')->nullable();
            $table->json('father_documents')->nullable();
            $table->json('mother_documents')->nullable();
            $table->json('spouse_documents')->nullable();
            $table->json('children_documents')->nullable();
            $table->timestamps();
            
            // Ensure one row per lead
            $table->unique('lead_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_lead_documents');
    }
};
