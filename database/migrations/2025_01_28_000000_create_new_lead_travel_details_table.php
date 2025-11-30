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
        if (Schema::hasTable('new_lead_travel_details')) {
            return;
        }
        
        Schema::create('new_lead_travel_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('new_lead_id');
            $table->foreign('new_lead_id')->references('id')->on('new_leads')->onDelete('cascade')->onUpdate('cascade');
            
            // Travel Details
            $table->text('purpose_of_trip')->nullable();
            $table->text('place_to_visit')->nullable();
            $table->date('date_of_arrival')->nullable();
            $table->string('arrival_flight')->nullable();
            $table->string('arrival_city')->nullable();
            $table->date('date_of_departure')->nullable();
            $table->string('departure_flight')->nullable();
            $table->string('departure_city')->nullable();
            $table->string('phone_number_other_country')->nullable();
            
            // Address Where You Will Stay
            $table->text('address_stay')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            
            // Personal Information
            $table->text('person_paying')->nullable();
            $table->string('mother_in_country')->nullable();
            $table->string('immediate_relatives')->nullable();
            $table->string('other_relatives')->nullable();
            
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
        Schema::dropIfExists('new_lead_travel_details');
    }
};

