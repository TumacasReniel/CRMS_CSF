<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAttributeRating extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',  
        'dimension_id',
        'rate_score',
        'importance_rate_score',
    ];
    
}