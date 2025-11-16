<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the old foreign key
        try {
            DB::statement('ALTER TABLE `lead_step_statuses` DROP FOREIGN KEY `lead_step_statuses_lead_id_foreign`');
        } catch (\Exception $e) {
            // Foreign key might not exist or have a different name
            // Try to find and drop any foreign key on lead_id column
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'lead_step_statuses' 
                AND COLUMN_NAME = 'lead_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            foreach ($foreignKeys as $fk) {
                try {
                    DB::statement("ALTER TABLE `lead_step_statuses` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
                } catch (\Exception $e) {
                    // Ignore if it doesn't exist
                }
            }
        }

        // Add the correct foreign key
        DB::statement("
            ALTER TABLE `lead_step_statuses` 
            ADD CONSTRAINT `lead_step_statuses_lead_id_foreign` 
            FOREIGN KEY (`lead_id`) 
            REFERENCES `new_leads` (`id`) 
            ON DELETE CASCADE 
            ON UPDATE CASCADE
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key
        try {
            DB::statement('ALTER TABLE `lead_step_statuses` DROP FOREIGN KEY `lead_step_statuses_lead_id_foreign`');
        } catch (\Exception $e) {
            // Ignore if it doesn't exist
        }
    }
};

