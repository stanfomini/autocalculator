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
            // The user who owns this calculator
            $table->unsignedBigInteger('user_id');

            $table->string('calc_type')->default('lease');

            // Shared fields:
            $table->decimal('vehicle_price', 18, 2)->default(0);
            $table->decimal('rebates_and_discounts', 18, 2)->default(0);
            $table->decimal('down_payment', 18, 2)->default(0);
            $table->integer('term_months')->default(36);

            // taxes & fees:
            $table->decimal('tax_percent', 10, 2)->nullable();
            $table->decimal('tax_total', 18, 2)->nullable();
            $table->boolean('capitalize_taxes')->default(false);
            $table->decimal('additional_fees', 18, 2)->default(0);
            $table->boolean('capitalize_fees')->default(false);

            // lease-specific:
            $table->decimal('residual_percent', 10, 2)->nullable();
            $table->decimal('residual_value', 18, 2)->nullable();
            $table->decimal('money_factor', 18, 5)->nullable();

            // financing-specific:
            $table->decimal('annual_interest_rate', 10, 3)->nullable();
            $table->boolean('taxes_and_fees_financed')->default(false);

            // monthly or yearly costs:
            $table->decimal('maintenance_cost', 18, 2)->default(0);
            $table->decimal('monthly_insurance', 18, 2)->default(0);
            $table->decimal('monthly_fuel', 18, 2)->default(0);

            $table->timestamps();

            // typical foreign key constraint
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('awesome_calcs');
    }
};