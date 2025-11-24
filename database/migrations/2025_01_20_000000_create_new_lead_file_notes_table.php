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
        if (Schema::hasTable('new_lead_file_notes')) {
            return;
        }
        
        Schema::create('new_lead_file_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('new_lead_id');
            $table->foreign('new_lead_id')->references('id')->on('new_leads')->onDelete('cascade')->onUpdate('cascade');
            $table->longText('note');
            $table->unsignedInteger('added_by')->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_lead_file_notes');
    }
};

