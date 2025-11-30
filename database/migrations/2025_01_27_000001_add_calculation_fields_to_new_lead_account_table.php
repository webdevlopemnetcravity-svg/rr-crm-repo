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
            Schema::table('new_lead_account', function (Blueprint $table) {
                if (!Schema::hasColumn('new_lead_account', 'sub_total')) {
                    $table->decimal('sub_total', 15, 2)->nullable()->after('net_amount');
                }
                if (!Schema::hasColumn('new_lead_account', 'tax_amount')) {
                    $table->decimal('tax_amount', 15, 2)->nullable()->after('sub_total');
                }
                if (!Schema::hasColumn('new_lead_account', 'discount_amount')) {
                    $table->decimal('discount_amount', 15, 2)->nullable()->after('tax_amount');
                }
                if (!Schema::hasColumn('new_lead_account', 'total_amount')) {
                    $table->decimal('total_amount', 15, 2)->nullable()->after('discount_amount');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('new_lead_account')) {
            Schema::table('new_lead_account', function (Blueprint $table) {
                if (Schema::hasColumn('new_lead_account', 'sub_total')) {
                    $table->dropColumn('sub_total');
                }
                if (Schema::hasColumn('new_lead_account', 'tax_amount')) {
                    $table->dropColumn('tax_amount');
                }
                if (Schema::hasColumn('new_lead_account', 'discount_amount')) {
                    $table->dropColumn('discount_amount');
                }
                if (Schema::hasColumn('new_lead_account', 'total_amount')) {
                    $table->dropColumn('total_amount');
                }
            });
        }
    }
};

