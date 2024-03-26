<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\SubUnit;
use App\Models\UnitSubUnit;
use Illuminate\Http\Request;
use App\Http\Resources\UnitSubUnit as UnitSubUnitResource;

class SubUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    public function getSubUnits($unit_id)
    {

  
        // get unit sub units
        $unit_sub_units = UnitSubUnit::where('unit_id',$unit_id)->get();
        $unit_sub_units = UnitSubUnitResource::collection($unit_sub_units);

        $sub_units = $unit_sub_units->pluck('sub_unit');
        
        return Inertia::render('Libraries/Service-Units/Views/View')
                    ->withViewData('sub_units',  $sub_units);
    }
  
}
