<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Dimension;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class DimensionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dimensions = Dimension::all();
        return Inertia::render('Survey-Forms/Index', [
            'dimensions' => $dimensions,
        ]);
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
    public function show(Dimension $dimension)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dimension $dimension)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dimension $dimension)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dimension $dimension)
    {
        //
    }
}
