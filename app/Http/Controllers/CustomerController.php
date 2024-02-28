<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Customer;
use App\Models\Dimension;
use App\Models\CustomerAttributeRating;
use App\Models\CcRating;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        DB::beginTransaction();  
        // dd($request);

        // Validate customer
        $customerData = request()->validate([
            'email' => ['required', 'max:50', 'email'],
            'name' => ['nullable', 'max:50'],
            'client_type' => ['required', 'max:50'],
            'sex' => ['required', 'max:50'],
            'age_group' => ['required', 'max:150'],
            'digital_literacy' => ['nullable'],
            'pwd' => ['nullable'],
            'pregnant' => ['nullable'],
            'senior_citizen' => ['nullable'],
        ]);
        // Save Customer
        $customer = Customer::create($customerData);

        // Validate dimension_form data
        $dimensionData = request()->validate([
            'dimension_form.id.*' => ['required', 'exists:dimensions,id'],
            'dimension_form.name.*' => ['required', 'max:255'],
            'dimension_form.rate_score.*' => ['required', 'max:1'],
            'dimension_form.importance_rate_score.*' => ['required', 'max:1'],
        ]);

        // Associate ratings with dimensions for the customer
        foreach ($dimensionData['dimension_form']['id'] as $index => $dimensionId) {
            CustomerAttributeRating::create([
                'customer_id' => $customer->id,
                'dimension_id' => $dimensionId,
                'rate_score' => $dimensionData['dimension_form']['rate_score'][$index],
                'importance_rate_score' => $dimensionData['dimension_form']['importance_rate_score'][$index],
            ]);
        }

        // Validate CC
        $ccData = request()->validate([
            'cc_form.id.*' => ['required', 'max:10'],
            'cc_form.name.*' => ['required', 'max:50'],
        ]);

        // Save CC 
        foreach ($ccData['cc_form']['id'] as $index) {
            CustomerAttributeRating::create([
                'customer_id' => $customer->id,
                'name' => $dimensionData['cc_form']['name'][$index],
                'answer' => $dimensionData['cc_form']['answer'][$index],
            ]);
        }


        // CustomerAttributeRating::create(
        //     Request::validate([

        //         // 'cc1' => ['required', 'max:2'],
        //         // 'cc2' => ['required', 'max:2'],
        //         // 'cc3' => ['required', 'max:2'],
        //         'customer_id' => ['nullable', Rule::exists('customers', 'id')],
        //         'dimension_id' => ['nullable', Rule::exists('dimensions', 'id')],
        //         'rate_score' => ['required', 'max:1'],
        //         'importance_rate_score' =>['required', 'max:1'],

        //         // 'responsiveness' => ['required', 'max:1'],
        //         // 'responsiveness_attr_number' => ['required', 'max:1'],
        //         // 'reliability' => ['required', 'max:1'],
        //         // 'reliability_attr_number' => ['required', 'max:1'],
        //         // 'access_and_facilities' => ['required', 'max:1'],
        //         // 'access_and_facilities_attr_number' => ['required', 'max:1'],
        //         // 'communication' => ['required', 'max:1'],
        //         // 'communication_attr_number' => ['required', 'max:1'],
        //         // 'integrity' => ['required', 'max:1'],
        //         // 'integrity_attr_number' => ['required', 'max:1'],
        //         // 'assurance' => ['required', 'max:1'],
        //         // 'assurance_attr_number' => ['required', 'max:1'],
        //         // 'outcome' => ['required', 'max:1'],
        //         // 'outcome_attr_number' => ['required', 'max:1'],

        //         // 'recommend_rate' => ['required', 'max:1'],
        //         // 'comment' => ['required', 'max:255'],
        //         // 'other_attr_indication' => ['required', 'max:1'],
        //         // 'signature' => ['nullable', 'max:1024'],

        //     ])

            
        // );
        DB::commit();
        return Redirect::route('csf_form')->with('success', 'Customer satisfaction feedback successfully submitted.');
    }

 
}
