<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestItem extends Model
{
    use HasFactory;

    // The table name can be inferred as 'test_items' by default.
    // Fillable properties for mass assignment:
    protected $fillable = [        'message',    ];
}