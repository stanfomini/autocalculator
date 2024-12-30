<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AwesomeCalc extends Model
{
    use HasFactory;

    protected $table = 'awesome_calcs';

    protected $fillable = [
        'user_id',
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
        'residual_percent',
        'residual_value',
        'money_factor',
        'annual_interest_rate',
        'taxes_and_fees_financed',
        'maintenance_cost',
        'monthly_insurance',
        'monthly_fuel',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}