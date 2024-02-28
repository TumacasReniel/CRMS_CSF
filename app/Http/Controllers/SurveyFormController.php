<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Customer;
use App\Models\Dimension;
use App\Models\CcQuestion;
use App\Models\CustomerComment;
use App\Models\CustomerCCRating;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CustomerRequest;
use App\Models\CustomerAttributeRating;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\CustomerCommentRequest;
use App\Http\Requests\CustomerOtherRequest;
use App\Models\CustomerOtherAttributeIndication;
use App\Models\CustomerRecommendationRating;

class SurveyFormController extends Controller
{
    public function index()
    {
        $cc_questions = CcQuestion::all();
        $dimensions = Dimension::all();

        return Inertia::render('Survey-Forms/Index')
            ->with('cc_questions', $cc_questions)
            ->with('dimensions', $dimensions);
    }

    public function store(
        CustomerRequest $customer_request , 
        CustomerCommentRequest $comment_request , 
        CustomerOtherRequest $other_request ,
    )
    {
        DB::beginTransaction();  

        // Save Customer
        $customer = Customer::create([
            'email' => $customer_request->email,
            'name' => $customer_request->name,
            'client_type' => $customer_request->client_type,
            'sex' => $customer_request->sex,
            'age_group' => $customer_request->age_group,
            'digital_literacy' => $customer_request->digital_literacy,
            'pwd' => $customer_request->pwd,
            'pregnant' => $customer_request->pregnant,
            'senior_citizen' => $customer_request->senior_citizen,
        ]);

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
            'cc_form.id.*' => ['required', 'exists:cc_questions,id'],
            'cc_form.title.*' => ['required', 'max:255'],
            'cc_form.answer.*' => ['required', 'max:1'],
        ]);

        // Associate ratings with cc for the customer
        foreach ($ccData['cc_form']['id'] as $index => $ccId) {
            CustomerCCRating::create([
                'customer_id' => $customer->id,
                'cc_id' => $ccId,
                'title' => $ccData['cc_form']['title'][$index],
                'answer' => $ccData['cc_form']['answer'][$index],
            ]);
        }

         // Save Comment
         $comment = CustomerComment::create(
            [
                'customer_id' => $customer->id,
                'comment' =>  $comment_request->comment,
                'is_complaint' =>  $comment_request->is_complaint,
            ]
         );


        // // Validate Other data
        // $other_data = request()->validate([
        //     'indication' => ['nullable', 'max:255'],
        //     'recommend_rate_score' => ['nullable', 'max:255'],

        // ]);

   
        // Save Customer Recommendation Rating
        CustomerOtherAttributeIndication::create(
            [
                'customer_id' => $customer->id,
                'indication' =>  $other_request->indication,         
            ]
        );

        // Save Customer Recommendation Rating
        CustomerRecommendationRating::create(
            [
                'customer_id' => $customer->id,
                'recommend_rate_score' =>  $other_request->recommend_rate_score,
            ]
        );

         
        // DB::commit();
        return Redirect::route('csf_form')->with('success', 'Customer satisfaction feedback successfully submitted.');
    }
    
}
