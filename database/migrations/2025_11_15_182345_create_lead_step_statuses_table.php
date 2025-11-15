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
        if (Schema::hasTable('lead_step_statuses')) {
            return;
        }
        
        Schema::create('lead_step_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id')->unique()->index();
            $table->foreign('lead_id')->references('id')->on('new_leads')->onDelete('cascade');
            $table->boolean('step_1_completed')->default(false);
            $table->boolean('step_2_completed')->default(false);
            $table->boolean('step_3_completed')->default(false);
            $table->boolean('step_4_completed')->default(false);
            $table->boolean('step_5_completed')->default(false);
            $table->boolean('step_6_completed')->default(false);
            $table->boolean('step_7_completed')->default(false);
            $table->boolean('step_8_completed')->default(false);
            $table->boolean('step_9_completed')->default(false);
            $table->enum('final_status', ['draft', 'complete'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_step_statuses');
    }
};
