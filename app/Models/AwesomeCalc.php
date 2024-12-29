<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * The AwesomeCalc model can handle lease, financing, and cash
 * calculations in one table. `calc_type` indicates which scenario
 * (e.g. 'lease', 'financing', or 'cash').
 *
 * We can expand in the future by adding user_id or vehicle_id
 * for user-based or vehicle-based relationships.
 */
class AwesomeCalc extends Model
{
    use HasFactory;

    protected $table = 'awesome_calcs';

    /**
     * Fillable fields for mass assignment (from JSON input).
     */
    protected $fillable = [
        'calc_type',
        'vehicle_price',
        'rebates_and_discounts',
        'down_payment',
        'term_months',
        'tax_percent',
        'tax_total',
        'capitalize_taxes',
        'additional_fees',
        'capitalize_fees',
        'maintenance_cost',
        'monthly_insurance',
        'monthly_fuel',
        // lease-specific
        'residual_percent',
        'residual_value',
        'money_factor',
        // financing-specific
        'annual_interest_rate',
        'taxes_and_fees_financed',
    ];
}