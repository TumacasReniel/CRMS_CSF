<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCCRating extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'cc_id',
        'answer',
    ];
}
