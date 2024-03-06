<?php

namespace App\Http\Controllers;

use App\Models\CustomerAttributeRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
// use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;


class CustomerAttributeRatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return Inertia::render('Contacts/Index', [
        //     'filters' => Request::all('search', 'trashed'),
        //     'contacts' => Auth::user()->account->contacts()
        //         ->with('organization')
        //         ->orderByName()
        //         ->filter(Request::only('search', 'trashed'))
        //         ->paginate(10)
        //         ->withQueryString()
        //         ->through(fn ($contact) => [
        //             'id' => $contact->id,
        //             'name' => $contact->name,
        //             'phone' => $contact->phone,
        //             'city' => $contact->city,
        //             'deleted_at' => $contact->deleted_at,
        //             'organization' => $contact->organization ? $contact->organization->only('name') : null,
        //         ]),
        // ]);
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
    public function show(CustomerAttributeRating $customerAttributeRating)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerAttributeRating $customerAttributeRating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerAttributeRating $customerAttributeRating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerAttributeRating $customerAttributeRating)
    {
        //
    }
}
