<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\SubUnitPsto;
use Illuminate\Http\Request;
use App\Http\Resources\SubUnitPSTO as SubUnitPSTOResource;


class SubUnitPstoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($sub_unit_id)
    {

        $sub_unit_psto = SubUnitPsto::where('sub_unit_id', $sub_unit_id)->get(); 
        $sub_unit_pstos = SubUnitPSTOResource::collection($sub_unit_psto);


        $sub_unit_pstos = $sub_unit_pstos->pluck('psto');
        
        //return $sub_unit_pstos;
        return response()->json($sub_unit_pstos);
        // // return $sub_unit_pstos;
        // return Inertia::render('CSI/Index')->with('sub_unit_pstos', $sub_unit_pstos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SubUnitPsto $subUnitPsto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubUnitPsto $subUnitPsto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubUnitPsto $subUnitPsto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubUnitPsto $subUnitPsto)
    {
        //
    }
}
