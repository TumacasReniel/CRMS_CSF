<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubUnitType extends Model
{
    use HasFactory;
    protected $fillable = [
        'sub_unit_id',
        'type_name',
    ];

    public function sub_unit()
    {
        return $this->belongsTo(SubUnit::class);
    }

}
