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
        if (Schema::hasTable('new_lead_appointments')) {
            return;
        }
        
        Schema::create('new_lead_appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('lead_id');
            $table->foreign('lead_id')->references('id')->on('new_leads')->onDelete('cascade')->onUpdate('cascade');
            $table->string('meeting_title');
            $table->text('description')->nullable();
            $table->dateTime('appointment_date');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('google_meet_link')->nullable();
            $table->string('google_event_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['lead_id', 'appointment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_lead_appointments');
    }
};

