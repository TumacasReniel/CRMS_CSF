<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\psto;
use App\Models\SubUnit;
use App\Models\SubUnitPsto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\PSTO as ResourcesPSTO;


class SubUnitPstoController extends Controller
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
        $selected_sub_unit_id = null;
        if($request->form){
            $selected_sub_unit_id = $request->form['sub_unit_id'];
        }
        


        $sub_units = SubUnit::all();

        $sub_units = SubUnit::when($search, function ($query,  $search) {
            $query->where('sub_unit_name', 'like', '%' . $search . '%');
        })
        ->orderByDesc('created_at')
        ->paginate(10);

        $sub_unit_pstos =[];
        if($selected_sub_unit_id){
            $sub_unit_pstos = SubUnitPsto::where('sub_unit_id', $selected_sub_unit_id)
            ->orderByDesc('created_at')
            ->get();   
            
            
            $psto_ids =   $sub_unit_pstos->pluck('psto_id');

            //dd($psto_ids);
            $sub_unit_pstos = psto::whereIn('id',  $psto_ids)->get() ;
            $sub_unit_pstos = ResourcesPSTO::collection($sub_unit_pstos);
        }

        $pstos = psto::all(); // for options when assigning unit pstos
        $pstos = ResourcesPSTO::collection($pstos);



        return Inertia::render('Libraries/SubUnitPSTOs/Index')
                    ->with('sub_units', $sub_units)
                    ->with('sub_unit_pstos', $sub_unit_pstos)
                    ->with('pstos', $pstos)
                    ->with('user', $user);
    }


    public function assignSubUnitPSTOs(Request $request)
    {
        // dd($request->all());
        $sub_unit_psto = SubUnit::findOrFail($request->sub_unit_id);
        $psto_ids = collect($request->pstos)->pluck('id')->toArray();
        $sub_unit_psto->pstos()->sync($psto_ids);
        $sub_unit_psto->update();

        return Redirect::back();
    }
}
