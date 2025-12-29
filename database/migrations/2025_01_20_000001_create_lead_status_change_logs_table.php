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
        if (Schema::hasTable('new_lead_status_change_logs')) {
            return;
        }
        
        Schema::create('new_lead_status_change_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id')->index();
            $table->foreign('lead_id')->references('id')->on('new_leads')->onDelete('cascade');
            $table->enum('change_type', ['status', 'quality']);
            $table->string('old_value')->nullable();
            $table->string('new_value');
            $table->text('remark')->nullable();
            $table->unsignedInteger('changed_by');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['lead_id', 'change_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_lead_status_change_logs');
    }
};

