<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Inertia\Inertia;
use App\Models\CSFForm;
use App\Models\SubUnit;
use App\Models\Customer;
use App\Models\Dimension;
use App\Models\CcQuestion;
use Illuminate\Http\Request;
use App\Models\CustomerComment;
use App\Models\UnitPsto;
use App\Models\CustomerCCRating;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Support\Facades\DB;
use App\Models\SubUnitPsto;
use App\Models\CustomerAttributeRating;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\SurveyFormRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Models\CustomerRecommendationRating;
use App\Models\CustomerOtherAttributeIndication;

use App\Http\Resources\Unit as UnitResource;
use App\Http\Resources\SubUnit as SubUnitResource;
use App\Http\Resources\UnitPSTO as UnitPSTOResource;
use App\Http\Resources\SubUnitPSTO as SubUnitPSTOResource;
use App\Models\CustomerSignature;

class SurveyFormController extends Controller
{
    public function index(Request $request)
    {
        //dd($request->all());
        $cc_questions = CcQuestion::all();
        $dimensions = Dimension::all();
        $unit = Unit::where('id', $request->unit_id)->get();
        $sub_unit = SubUnit::where('unit_id', $request->unit_id)
                           ->where('id', $request->sub_unit_id)->get();
        $unit_psto = UnitPsto::where('unit_id', $request->unit_id)
                             ->where('psto_id', $request->psto_id)->get();
        $sub_unit_psto = SubUnitPsto::where('sub_unit_id', $request->sub_unit_id)
                                     ->where('psto_id', $request->psto_id)->get();
        
        $unit = UnitResource::collection($unit);
        $sub_unit = SubUnitResource::collection($sub_unit);
        $unit_psto = UnitPSTOResource::collection($unit_psto);
        $sub_unit_psto = SubUnitPSTOResource::collection($sub_unit_psto);

        return Inertia::render('Survey-Forms/Index')
            ->with('cc_questions', $cc_questions)
            ->with('dimensions', $dimensions)
            ->with('unit', $unit)
            ->with('sub_unit', $sub_unit)
            ->with('unit_psto', $unit_psto)
            ->with('sub_unit_psto', $sub_unit_psto);  
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
                'cc_form.answer.*' => ['required', 'max:1'],
            ]);
    
            // Associate ratings with cc for the customer
            foreach ($ccData['cc_form']['id'] as $index => $ccId) {
                CustomerCCRating::create([
                    'customer_id' => $customer->id,
                    'cc_id' => $ccId,
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
            
            return Inertia::redirect('msg_index');

        } catch (\Exception $e) {
            DB::rollBack();
            //return $e;
            return Inertia::render('Survey-Forms/ThankYou')
                ->with('message', "Somehting went wrong please check." + $e->getMessage() )
                ->with('status', "error")
                ->with('current_url', $request->current_url);
        }

        
   
    }

    public function saveCSFForm($request, $customer){
        $csf_form = new CSFForm();
        $csf_form->customer_id = $customer->id;
        $csf_form->region_id = $request->region_id;
        $csf_form->service_id = $request->service_id;
        $csf_form->unit_id = $request->unit_id;
        $csf_form->sub_unit_id = $request->sub_unit_id;
        $csf_form->psto_id = $request->psto_id;
        $csf_form->client_type = $request->client_type;
        $csf_form->sub_unit_type = $request->sub_unit_type;
        $csf_form->save();

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
            // 'signature_path' => $request->signature,
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
