<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Inertia\Inertia;
use App\Models\UnitPsto;
use App\Models\psto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\PSTO as ResourcesPSTO;

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
        $selected_unit_id = null;
        if($request->form){
            $selected_unit_id = $request->form['unit_id'];
        }
        


        $units = Unit::all();

        $units = Unit::when($search, function ($query,  $search) {
            $query->where('unit_name', 'like', '%' . $search . '%');
        })
        ->orderByDesc('created_at')
        ->paginate(10);

        $unit_pstos =[];
        if($selected_unit_id){
            $unit_pstos = UnitPsto::where('unit_id', $selected_unit_id)
            ->orderByDesc('created_at')
            ->get();   
            
            
            $psto_ids =   $unit_pstos->pluck('psto_id');

            //dd($psto_ids);
            $unit_pstos = psto::whereIn('id',  $psto_ids)->get() ;
            $unit_pstos = ResourcesPSTO::collection($unit_pstos);
        }

        $pstos = psto::all(); // for options when assigning unit pstos
        $pstos = ResourcesPSTO::collection($pstos);



        return Inertia::render('Libraries/UnitPSTOs/Index')
                    ->with('units', $units)
                    ->with('unit_pstos', $unit_pstos)
                    ->with('pstos', $pstos)
                    ->with('user', $user);
    }


    public function assignUnitPSTOs(Request $request)
    {
        // dd($request->all());
        $unit_psto = Unit::findOrFail($request->unit_id);
        $psto_ids = collect($request->pstos)->pluck('id')->toArray();
        $unit_psto->pstos()->sync($psto_ids);
        $unit_psto->update();

        return Redirect::back();
    }

    
    
}
