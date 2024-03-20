<?php

namespace App\Http\Controllers;
use App\Models\Services;
use App\Models\Unit;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Resources\Services as ServicesUnitsResource;


class ServiceUnitController extends Controller
{
    public function index()
    {
        $service_units = Services::all();

        $data = ServicesUnitsResource::collection($service_units);

        return Inertia::render('Libraries/Service-Units/Index')
            ->with('service_units', $data);
    }

    public function unit_index(Request $request)
    {
        $unit = $request->unit;

        return Inertia::render('Libraries/Service-Units/Views/Index')
            ->with('unit', $unit);
    }

    
}
