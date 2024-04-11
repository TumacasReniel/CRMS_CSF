<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\SubUnit;
use Illuminate\Http\Request;
use App\Http\Resources\UnitSubUnit as SubUnitResource;

class SubUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function getSubUnit(Request $request)
    {
        dd($request->all());
  
        // get unit sub units
        $sub_unit = SubUnit::where('id',$request)->get();
        $sub_unit = SubUnitResource::collection($unit_sub_units);
        
        return response()->json($sub_unit_pstos);
    }
  
}
