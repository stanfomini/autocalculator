<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelloItem extends Model
{
    use HasFactory;

    // By default, this will use "hello_items" table
    protected $fillable = [        'message',    ];
}