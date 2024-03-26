<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Customer;
use App\Models\Dimension;
use App\Models\CcQuestion;
use Illuminate\Http\Request;
use App\Models\CustomerComment;
use App\Models\CustomerCCRating;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerAttributeRating;
use App\Http\Requests\SurveyFormRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Models\CustomerRecommendationRating;
use App\Models\CustomerOtherAttributeIndication;
use Illuminate\Support\Facades\Session;
use Mews\Captcha\Facades\Captcha;


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

 
    public function store(SurveyFormRequest $request)
    {      
      
        try{
            DB::beginTransaction();  
            
            //Save Customer
            $customer = Customer::create([
                'email' => $request->email,
                'name' => $request->name,
                'client_type' => $request->client_type,
                'sex' => $request->sex,
                'age_group' => $request->age_group,
                'pwd' => $request->pwd,
                'pregnant' => $request->pregnant,
                'senior_citizen' => $request->senior_citizen,
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
                    'comment' =>  $request->comment,
                    'is_complaint' =>  $request->is_complaint,
                ]
             );
       
            // Save Customer Recommendation Rating
            CustomerOtherAttributeIndication::create(
                [
                    'customer_id' => $customer->id,
                    'indication' =>  $request->indication,         
                ]
            );
    
            // Save Customer Recommendation Rating
            CustomerRecommendationRating::create(
                [
                    'customer_id' => $customer->id,
                    'recommend_rate_score' =>  $request->recommend_rate_score,
                ]
            );

            
            DB::commit();
           
            return Inertia::render('Survey-Forms/ThankYou')
                ->with('message', "Successfully Submitted Thank you.")
                ->with('status', "success");
            

        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::route('csf_form')->with('error', 'Something went wrong please check.');
        }

        
   
    }


    
}
