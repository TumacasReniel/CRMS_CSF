<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class psto extends Model
{
    use HasFactory;
    protected $fillable = [
        'psto_name', 
        'region_id'
    ];


    public function units()
    {
        return $this->belongsToMany(Unit::class, 'unit_pstos', 'psto_id', 'unit_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function scopePstosWithRegion($query, $regionId)
    {
        return $query->where('region_id', $regionId);
    }



}
