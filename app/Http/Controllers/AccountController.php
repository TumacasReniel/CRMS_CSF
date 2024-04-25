<?php

namespace App\Http\Controllers;

use App\Models\psto;
use App\Models\Unit;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Region;
use App\Models\SubUnit;
use App\Models\Services;
use App\Models\UnitSubUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\PSTO as PSTOResource;
use App\Http\Resources\Unit as UnitResource;
use App\Http\Resources\Account as AccountResource;
use App\Http\Resources\Services as ServicesResource;
use App\Http\Resources\UnitSubUnit as UnitSubUnitResource;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $regions = Region::all();
        $services = Services::all();

        $accounts = User::when($search, function ($query,  $search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('region_id', 'like', '%' . $search . '%');
        })
        ->orderByDesc('created_at')
        ->paginate(10);

        $data = AccountResource::collection($accounts);
        $services = ServicesResource::collection($services);

        return Inertia::render('Account/Index')
                    ->with('accounts', $data)
                    ->with('regions', $regions)
                    ->with('services', $services);
    }

    public function resetPassword(Request $request)
    {
        $default_password = "*1234#";
        $account = User::findorFail($request->id);

        $account->password = Hash::make($default_password);
        $account->update();

        return Redirect::back();
       
    }

    public function store(Request $request)
    {
        $account = new User();
        $account->name = $request->name;
        $account->designation = strtoupper($request->designation);
        $account->email = $request->email;
        $account->password = Hash::make('*1234#');
        $account->region_id = $request->selected_region;
        $account->account_type = $request->selected_account_type;
        $account->service_id = $request->selected_service;
        $account->unit_id = $request->selected_unit;
        $account->save();

        return Redirect::back();
       
    }

    public function update(Request $request)
    {
        //dd($request->designation);
        $account = User::findorFail($request->id);
        $account->name = $request->name;
        $account->designation = strtoupper($request->designation);
        $account->email = $request->email;
        $account->region_id = $request->selected_region;
        $account->account_type = $request->selected_account_type;
        $account->service_id = $request->selected_service;
        $account->unit_id = $request->selected_unit;
        $account->update();

        return Redirect::back();
       
    }
}
