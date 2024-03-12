<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;
    protected $fillable = [
        'services_name',
        'services_url',
    ];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }


}
