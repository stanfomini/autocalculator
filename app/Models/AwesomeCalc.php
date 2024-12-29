<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwesomeCalc extends Model
{
    use HasFactory;

    protected $table = 'awesome_calcs';

    protected $fillable = [        'calc_type',        'vehicle_price',        'rebates_and_discounts',        'down_payment',        'term_months',        'residual_percent',        'residual_value',        'money_factor',        'tax_percent',        'tax_total',        'capitalize_taxes',        'additional_fees',        'capitalize_fees',        'maintenance_cost',        'monthly_insurance',        'monthly_fuel',    ];
}