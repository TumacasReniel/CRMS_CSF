<?php

namespace App\Http\Controllers;
use App\Models\psto;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PstoController extends Controller
{
    public function index(Request $request)
    {
        //get user
        $user = Auth::user();

        //dd($user->region_id);

        $search = $request->search;

        $pstos = psto::when($search, function ($query,  $search) {
            $query->where('psto_name', 'like', '%' . $search . '%');
        })
        ->where('region_id', $user->region_id)
        ->orderByDesc('created_at')
        ->paginate(10);

        return Inertia::render('Libraries/PSTOs/Index')
                    ->with('pstos', $pstos)
                    ->with('user', $user);
    }

    public function store(Request $request)
    {
        //get user
        $user = Auth::user();

        $psto = new psto();
        $psto->region_id = $user->region_id;
        $psto->psto_name = $request->psto_name;
        $psto->slug = Str::slug($request->psto_name, '-');

        $psto->save();

        return Redirect::back();
       
    }

    public function update(Request $request)
    {
        $psto = psto::findorFail($request->id);
        $psto->psto_name = $request->psto_name;
        $psto->slug = Str::slug($request->psto_name, '-');
        $psto->update();

        return Redirect::back();
       
    }

    public function destroy(Request $request)
    {
        $psto = psto::findorFail($request->id);
        $psto->delete();

        return Redirect::back();
       
    }
}
