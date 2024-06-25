<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubUnit extends Model
{
    use HasFactory;

    public function unit(){
        return $this->belongsTo(Unit::class);
    }

    public function sub_unit_types(){
        return $this->hasMany(SubUnitType::class);
    }

    public function pstos(){ 
        return $this->belongsToMany(psto::class, 'sub_unit_pstos', 'sub_unit_id', 'psto_id');
    }
}
