<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\CSFForm;
use App\Models\Customer;
use App\Models\Dimension;
use App\Models\CcQuestion;
use Illuminate\Http\Request;
use App\Models\CustomerComment;
use App\Models\CustomerCCRating;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerAttributeRating;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\SurveyFormRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Models\CustomerRecommendationRating;
use App\Models\CustomerOtherAttributeIndication;


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

    // SurveyFormRequest
    public function store(SurveyFormRequest $request)
    {          
        
        try{
            DB::beginTransaction();    
           
            //Save Customer
            $customer = $this->saveCustomer($request);
       
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
            $this->saveComment($request, $customer);
        
            // Save Customer Recommendation Rating
           $this->saveCustomerRecommendationRating($request, $customer);

            // SAve Other Attributes Indication
            $this->saveCustomerOtherAttributeIndication($request, $customer);

            // Save csf form
            $this->saveCSFForm($request, $customer);

            DB::commit();
           
            return Inertia::render('Survey-Forms/ThankYou')
                ->with('message', "Successfully Submitted Thank you.")
                ->with('status', "success")
                ->with('current_url', $request->current_url);
            

        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
            //return Inertia::location($request->current_url);
        }

        
   
    }

    public function saveCSFForm($request, $customer){
        $csf_form = CSFForm::create(
            [
                'customer_id' => $customer->id,
                'region_id' => $request->region_id,
                'service_id' => $request->service_id,
                'unit_id' => $request->unit_id,
                'sub_unit_id' => $request->sub_unit_id,
                'psto_id' => $request->psto_id,
            ]
        );

        return $csf_form;
    }

    public function saveCustomer($request){
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

        return $customer;
    }

    public function saveComment($request, $customer){
         $comment = CustomerComment::create(
            [
                'customer_id' => $customer->id,
                'comment' =>  $request->comment,
                'is_complaint' =>  $request->is_complaint,
            ]
         );

        return $comment;
    }

    public function saveCustomerRecommendationRating($request, $customer){
        $recommentdation_rating = CustomerRecommendationRating::create(
                [
                    'customer_id' => $customer->id,
                    'recommend_rate_score' =>  $request->recommend_rate_score, 
                   
                ]
            );
        return $recommentdation_rating;
    }

    public function saveCustomerOtherAttributeIndication($request, $customer){
        $customer_indication = CustomerOtherAttributeIndication::create(
                [
                    'customer_id' => $customer->id,   
                    'indication' =>  $request->indication,           
                ]
            );
       return $customer_indication;
   }

  
    
}
