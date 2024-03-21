<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitSubUnit extends Model
{
    use HasFactory;
    protected $fillable = [
        'unit_id',
        'sub_unit_id',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function sub_unit()
    {
        return $this->belongsTo(SubUnit::class);
    }
}
