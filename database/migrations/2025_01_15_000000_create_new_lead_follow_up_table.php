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
        if (Schema::hasTable('new_lead_follow_up')) {
            return;
        }
        
        Schema::create('new_lead_follow_up', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('new_lead_id');
            $table->foreign('new_lead_id')->references('id')->on('new_leads')->onDelete('cascade')->onUpdate('cascade');
            $table->string('follow_up_type')->default('call')->comment('call, meeting, sms, email');
            $table->string('subject')->nullable();
            $table->string('outcome')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('next_follow_up_date')->nullable();
            $table->enum('send_reminder', ['yes', 'no'])->default('no');
            $table->string('remind_time')->nullable()->comment('e.g., 15 Minutes Before, 30 Minutes Before');
            $table->string('follow_up_subject_line')->nullable();
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending');
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
        Schema::dropIfExists('new_lead_follow_up');
    }
};

