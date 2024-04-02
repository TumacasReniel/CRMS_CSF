<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_id',  
        'unit_name',
    ];
    public function service(){
        return $this->belongsTo(Services::class);
    }

    public function sub_units(){
        return $this->hasMany(SubUnit::class);
    }

}
