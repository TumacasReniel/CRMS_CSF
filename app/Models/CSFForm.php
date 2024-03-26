<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CSFForm extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'region_id',
        'service_id',
        'unit_id',
        'sub_unit_id',
        'psto_id',
    ];
}
