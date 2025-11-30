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
        if (Schema::hasTable('new_lead_account')) {
            return;
        }
        
        Schema::create('new_lead_account', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('new_lead_id');
            $table->foreign('new_lead_id')->references('id')->on('new_leads')->onDelete('cascade')->onUpdate('cascade');
            
            // Client Information (predefined from lead - stored for reference)
            $table->string('client_name')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            
            // Invoice Details
            $table->string('bill_to')->nullable();
            $table->unsignedInteger('agent')->nullable()->comment('Invoice belongs to agent');
            $table->foreign('agent')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            
            // Service Details
            $table->string('service')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->string('tax')->nullable();
            $table->decimal('discount', 15, 2)->nullable()->default(0);
            $table->decimal('net_amount', 15, 2)->nullable();
            $table->decimal('sub_total', 15, 2)->nullable();
            $table->decimal('tax_amount', 15, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->text('service_description')->nullable();
            
            // Installment Payment
            $table->boolean('installment_payment')->default(false);
            $table->integer('installment_months')->nullable();
            
            // Invoice Notes
            $table->text('invoice_notes')->nullable();
            
            // File/Document
            $table->string('invoice_file')->nullable();
            
            // Metadata
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
        Schema::dropIfExists('new_lead_account');
    }
};

