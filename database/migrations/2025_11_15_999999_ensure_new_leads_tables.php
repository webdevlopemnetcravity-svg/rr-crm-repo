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
        // Create new_leads table if it doesn't exist
        if (!Schema::hasTable('new_leads')) {
            Schema::create('new_leads', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('company_id')->nullable();
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
                $table->string('client_name')->nullable();
                $table->string('client_email')->nullable();
                $table->string('mobile')->nullable();
                $table->string('lead_source')->nullable();
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

        // Create lead_step_statuses table if it doesn't exist
        if (!Schema::hasTable('lead_step_statuses')) {
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

        // Create lead_step_logs table if it doesn't exist
        if (!Schema::hasTable('lead_step_logs')) {
            Schema::create('lead_step_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lead_id')->index();
                $table->foreign('lead_id')->references('id')->on('new_leads')->onDelete('cascade');
                $table->integer('step_number')->comment('Step number (1-9)');
                $table->enum('status', ['pending', 'completed'])->default('pending');
                $table->timestamp('completed_at')->nullable();
                $table->unsignedInteger('completed_by')->nullable();
                $table->foreign('completed_by')->references('id')->on('users')->onDelete('set null');
                $table->text('notes')->nullable();
                $table->timestamps();
                
                $table->index(['lead_id', 'step_number']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_step_logs');
        Schema::dropIfExists('lead_step_statuses');
        Schema::dropIfExists('new_leads');
    }
};

