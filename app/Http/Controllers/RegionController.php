<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\Region as RegionResource;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //get user
        $user = Auth::user();

        $search = $request->search;

        $regions = Region::all();

        $regions = Region::when($search, function ($query,  $search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%')
                  ->orWhere('short_name', 'like', '%' . $search . '%')
                  ->orWhere('order', 'like', '%' . $search . '%');
        })
        ->orderByDesc('created_at')
        ->paginate(10);

        return Inertia::render('Libraries/Regions/Index')
                    ->with('regions', $regions)
                    ->with('user', $user);
    }

    public function store(Request $request)
    {
        $region = new Region();
        $region->name = $request->name;
        $region->code = $request->code;
        $region->short_name = $request->short_name;
        $region->order = $request->order;
        $region->save();

        return Redirect::back();
       
    }

    public function update(Request $request)
    {
        $region = Region::findorFail($request->id);
        $region->name = $request->name;
        $region->code = $request->code;
        $region->short_name = $request->short_name;
        $region->order = $request->order;
        $region->update();

        return Redirect::back();
       
    }
}
