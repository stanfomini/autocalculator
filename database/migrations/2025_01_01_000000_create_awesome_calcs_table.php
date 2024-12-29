<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('awesome_calcs', function (Blueprint $table) {
            $table->id();
            $table->string('calc_type')->default('lease');
            $table->decimal('vehicle_price', 18, 2)->default(0);
            $table->decimal('rebates_and_discounts', 18, 2)->default(0);
            $table->decimal('down_payment', 18, 2)->default(0);
            $table->integer('term_months')->default(36);
            $table->decimal('residual_percent', 10, 2)->nullable();
            $table->decimal('residual_value', 18, 2)->nullable();
            $table->decimal('money_factor', 18, 5)->nullable();
            $table->decimal('tax_percent', 10, 2)->nullable();
            $table->decimal('tax_total', 18, 2)->nullable();
            $table->boolean('capitalize_taxes')->default(false);
            $table->decimal('additional_fees', 18, 2)->default(0);
            $table->boolean('capitalize_fees')->default(false);
            $table->decimal('maintenance_cost', 18, 2)->default(0);
            $table->decimal('monthly_insurance', 18, 2)->default(0);
            $table->decimal('monthly_fuel', 18, 2)->default(0);

            // For financing
            $table->decimal('annual_interest_rate', 10, 3)->nullable();  // e.g. 4.50
            $table->boolean('taxes_and_fees_financed')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('awesome_calcs');
    }
};