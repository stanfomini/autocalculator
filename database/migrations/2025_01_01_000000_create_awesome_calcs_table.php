<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * This table is used to store all calculations for lease, financing, and cash.
     * We keep some "universal" fields (e.g., vehicle_price, down_payment),
     * plus specific columns for lease (money_factor, residual_percent, etc.)
     * and columns for financing (annual_interest_rate, taxes_and_fees_financed, etc.)
     * In the future, we can add "user_id" (FK) or "vehicle_id" columns to
     * reference which user or vehicle it belongs to.
     */
    public function up()
    {
        Schema::create('awesome_calcs', function (Blueprint $table) {
            $table->id();
            $table->string('calc_type')->default('lease'); // 'lease', 'financing', or 'cash'

            // Shared fields:
            $table->decimal('vehicle_price', 18, 2)->default(0);
            $table->decimal('rebates_and_discounts', 18, 2)->default(0);
            $table->decimal('down_payment', 18, 2)->default(0);
            $table->integer('term_months')->default(36);
            
            // For taxes & fees (used by lease or financing or cash):
            $table->decimal('tax_percent', 10, 2)->nullable();
            $table->decimal('tax_total', 18, 2)->nullable();
            $table->boolean('capitalize_taxes')->default(false);
            $table->decimal('additional_fees', 18, 2)->default(0);
            $table->boolean('capitalize_fees')->default(false);

            // For lease:
            $table->decimal('residual_percent', 10, 2)->nullable();
            $table->decimal('residual_value', 18, 2)->nullable();
            $table->decimal('money_factor', 18, 5)->nullable();

            // For financing:
            $table->decimal('annual_interest_rate', 10, 3)->nullable();  // e.g. 5.5 => 5.5% annual interest
            $table->boolean('taxes_and_fees_financed')->default(false);  // If true, we add taxes+fees to financed amount

            // For maintenance or monthly expenses:
            $table->decimal('maintenance_cost', 18, 2)->default(0);      // e.g. yearly maintenance
            $table->decimal('monthly_insurance', 18, 2)->default(0);
            $table->decimal('monthly_fuel', 18, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('awesome_calcs');
    }
};