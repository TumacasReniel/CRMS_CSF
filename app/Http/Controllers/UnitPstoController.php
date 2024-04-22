<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Inertia\Inertia;
use App\Models\UnitPsto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\UnitPSTO as ResourcesUnitPSTO;

class UnitPstoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //dd($request->all());
        //get user
        $user = Auth::user();

        $search = $request->search;
        $selected_unit = $request->selected_unit;

        $units = Unit::all();

        $units = Unit::when($search, function ($query,  $search) {
            $query->where('unit_name', 'like', '%' . $search . '%');
        })
        ->orderByDesc('created_at')
        ->paginate(10);

        $unit_pstos =[];
        if($selected_unit){
            $unit_pstos = UnitPsto::where('unit_id', $selected_unit->id)
            ->orderByDesc('created_at')
            ->get();
            // dd($request->all());
        }

    
        return Inertia::render('Libraries/UnitPSTOs/Index')
                    ->with('units', $units)
                    ->with('unit_pstos', $unit_pstos)
                    ->with('user', $user);
    }

    public function store(Request $request)
    {
        $region = new UnitPSTO();
        $region->name = $request->name;
        $region->code = $request->code;
        $region->short_name = $request->short_name;
        $region->order = $request->order;
        $region->save();

        return Redirect::back();
       
    }

    public function update(Request $request)
    {
        $region = UnitPSTO::findorFail($request->id);
        $region->name = $request->name;
        $region->code = $request->code;
        $region->short_name = $request->short_name;
        $region->order = $request->order;
        $region->update();

        return Redirect::back();
       
    }
}
