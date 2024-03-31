<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\Account as AccountResource;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $regions = Region::all();
        $accounts = User::when($search, function ($query,  $search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('region_id', 'like', '%' . $search . '%');
        })
        ->orderByDesc('created_at')
        ->paginate(10);

        $data = AccountResource::collection($accounts);
        return Inertia::render('Account/Index')
                    ->with('accounts', $data)
                    ->with('regions', $regions);
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
        $account->email = $request->email;
        $account->password = Hash::make('*1234#');
        $account->region_id = $request->selected_region;
        $account->save();

        return Redirect::back();
       
    }

    public function update(Request $request)
    {
        //dd($request->all());
        $account = User::findorFail($request->id);
        $account->name = $request->name;
        $account->email = $request->email;
        $account->region_id = $request->selected_region['id'];
        $account->update();

        return Redirect::back();
       
    }
}
