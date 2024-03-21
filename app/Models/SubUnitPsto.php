<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubUnitPsto extends Model
{
    use HasFactory;
    protected $fillable = [
        'sub_unit_id',
        'psto_id',
    ];

    public function sub_unit()
    {
        return $this->belongsTo(SubUnit::class);
    }

    public function psto()
    {
        return $this->belongsTo(psto::class);
    }
}
