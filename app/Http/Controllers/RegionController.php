<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Region;
use Illuminate\Http\Request;
use App\Http\Resources\Account as AccountResource;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $regions = Region::all();
        // $data = AccountResource::collection($regions);
        // return Inertia::render('Account/Index')
        //             ->with('accounts', $data);
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
    public function show(Regions $regions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Regions $regions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Regions $regions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Regions $regions)
    {
        //
    }
}
