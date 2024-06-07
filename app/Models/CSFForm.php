<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->control_number = self::generateUniqueControlNumber();
        });
    }

    /**
     * Generate a unique control number with custom format.
     *
     * @return string
     */
    public static function generateUniqueControlNumber()
    {
        $prefix = 'CSF';
        $date = now()->format('mdY'); // Current date in MMDDYYYY format
        $random = Str::upper(Str::random(8)); // Random 5-character alphanumeric string

        do {
            $controlNumber = "{$prefix}-{$date}-{$random}";
        } while (self::where('control_number', $controlNumber)->exists());

        return $controlNumber;
    }
}


