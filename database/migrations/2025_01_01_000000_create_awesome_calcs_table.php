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
            $table->string('calc_type')->default('lease'); // "lease", "finance", or "cash"
            $table->decimal('vehicle_price', 10, 2)->default(0);
            $table->decimal('rebates_and_discounts', 10, 2)->default(0);
            $table->decimal('down_payment', 10, 2)->default(0);
            $table->integer('term_months')->default(36);
            $table->decimal('residual_percent', 5, 2)->nullable();
            $table->decimal('residual_value', 10, 2)->nullable();
            $table->decimal('money_factor', 10, 5)->nullable();
            $table->decimal('tax_percent', 5, 2)->nullable();
            $table->decimal('tax_total', 10, 2)->nullable();
            $table->boolean('capitalize_taxes')->default(false);
            $table->decimal('additional_fees', 10, 2)->default(0);
            $table->boolean('capitalize_fees')->default(false);
            $table->decimal('maintenance_cost', 10, 2)->default(0);
            $table->decimal('monthly_insurance', 10, 2)->default(0);
            $table->decimal('monthly_fuel', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('awesome_calcs');
    }
};