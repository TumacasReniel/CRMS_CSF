<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\psto;
use App\Models\Unit;
use Inertia\Inertia;
use App\Models\CSFForm;
use App\Models\SubUnit;
use App\Models\Services;
use App\Models\UnitPsto;
use App\Models\Dimension;
use App\Models\SubUnitPsto;
use App\Models\SubUnitType;
use App\Models\UnitSubUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomerAttributeRating;
use App\Models\CustomerComment;
use App\Http\Resources\Unit as UnitResource;
use App\Models\CustomerRecommendationRating;
use App\Http\Resources\Services as ServiceResource;
use App\Http\Resources\SubUnit as SubUnitResource;
use App\Http\Resources\UnitPSTO as UnitPSTOResource;
use App\Http\Resources\SubUnitPSTO as SubUnitPSTOResource;
use App\Http\Resources\UnitSubUnit as UnitSubUnitResource;
use App\Http\Resources\CustomerAttributeRatings as CARResource;

class ReportController extends Controller
{
    public function index(Request $request )
    {
        //get user
        $user = Auth::user();

        $dimensions = Dimension::all();
        $service = Services::findOrFail($request->service_id);

        $units = Unit::where('id',$request->unit_id)->get();
        $unit = UnitResource::collection($units);

        //get unit pstos
        $unit_pstos = UnitPsto::where('unit_id',$request->unit_id)->get();
        $unit_pstos = UnitPSTOResource::collection($unit_pstos);

        $unit_pstos = $unit_pstos->pluck('psto');
 
        //get sub unit pstos

        $sub_unit_pstos = SubUnitPsto::where('sub_unit_id',$request->sub_unit_id)->get(); 
        $sub_unit_pstos = SubUnitPSTOResource::collection($sub_unit_pstos);

        $sub_unit_pstos = $sub_unit_pstos->pluck('psto');

        $sub_unit_types = SubUnitType::where('sub_unit_id', $request->sub_unit_id)->get();

        $sub_unit= [];
        $sub_unit =  SubUnit::when($request->sub_unit_id, function ($query, $sub_unit_id) {
            $query->where('id', $sub_unit_id);
        })->get();

        return Inertia::render('CSI/Index')
            ->with('dimensions', $dimensions)
            ->with('service', $service)
            ->with('unit', $unit)
            ->with('unit_pstos', $unit_pstos)
            ->with('sub_unit_pstos', $sub_unit_pstos)
            ->with('sub_unit_types', $sub_unit_types)
            ->with('user', $user)
            ->with('sub_unit', $sub_unit);
          
    
    }


    public function view(Request $request )
    {
        //get user
        $user = Auth::user();

        $dimensions = Dimension::all();
        $service = Services::findOrFail($request->service_id);

        $units = Unit::where('id',$request->unit_id)->get();
        $unit = UnitResource::collection($units);

        //get unit pstos
        $unit_pstos = UnitPsto::where('unit_id',$request->unit_id)->get();
        $unit_pstos = UnitPSTOResource::collection($unit_pstos);

        $unit_pstos = $unit_pstos->pluck('psto');
 
        //get sub unit pstos

        $sub_unit_pstos = SubUnitPsto::where('sub_unit_id', $request->sub_unit_id)->get(); 
        $sub_unit_pstos = SubUnitPSTOResource::collection($sub_unit_pstos);

        $sub_unit_pstos = $sub_unit_pstos->pluck('psto');

        $sub_unit_types = SubUnitType::where('sub_unit_id',  $request->sub_unit_id)->get();

        return Inertia::render('Libraries/Service-Units/Views/View')
            ->with('dimensions', $dimensions)
            ->with('service', $service)
            ->with('unit', $unit)
            ->with('unit_pstos', $unit_pstos)
            ->with('sub_unit_pstos', $sub_unit_pstos)
            ->with('sub_unit_types', $sub_unit_types)
            ->with('user', $user);
    
    }


    public function generateReports(Request $request )
    {
        //dd($request->all());
        $psto_id = null;
        if($request->selected_unit_psto){
            $psto_id = $request->selected_unit_psto;
        }
        else if($request->selected_sub_unit_psto){
            $psto_id = $request->selected_sub_unit_psto;
        }
       
        //get user
        $user = Auth::user();

        if($request->csi_type == 'By Date'){
            return $this->generateCSIByUnitByDate($request , $user->region_id, $psto_id);
        }
        else if($request->csi_type == "By Month"){
            return $this->generateCSIByUnitMonthly($request , $user->region_id, $psto_id);
        }
        else if($request->csi_type == "By Quarter"){
            if($request->selected_quarter == "FIRST QUARTER"){
                return $this->generateCSIByUnitFirstQuarter($request , $user->region_id, $psto_id);
            }
            else if($request->selected_quarter == "SECOND QUARTER"){
                return $this->generateCSIByUnitSecondQuarter($request , $user->region_id, $psto_id);
            }
            else if($request->selected_quarter == "THIRD QUARTER"){
                return $this->generateCSIByUnitThirdQuarter($request , $user->region_id, $psto_id);
            }
            else if($request->selected_quarter == "FOURTH QUARTER"){
                return $this->generateCSIByUnitFourthQuarter($request , $user->region_id, $psto_id);
            }
          
        }
        else if($request->csi_type == "By Year/Annual"){
            return $this->generateCSIByUnitYearly($request , $user->region_id, $psto_id);  
        }
    
    }


    public function generateCSIByUnitByDate($request, $region_id, $psto_id)
    {
        $sub_unit = $this->getSubUnit($request);
        $unit_pstos = $this->getUnitPSTOs($request);
        $sub_unit_pstos = $this->getSubUnitPSTOs($request);
        $sub_unit_types = $this->getSubUnitTypes($request);
        
        $service_id = $request->service['id'];
        $unit_id = $request->unit_id;
        $sub_unit_id = $request->selected_sub_unit;
        $client_type = $request->client_type; 
        $sub_unit_type = $request->sub_unit_type; 

       // search and check list of forms query  
       $customer_ids = $this->querySearchCSF( $region_id, $service_id, $unit_id ,$sub_unit_id , $psto_id, $client_type, $sub_unit_type );

        $date_range = CustomerAttributeRating::whereIn('customer_id',$customer_ids)
                                             ->whereBetween('created_at', [$request->date_from, $request->date_to])->get(); 

        $customer_recommendation_ratings = CustomerRecommendationRating::whereIn('customer_id',$customer_ids)
                                                                        ->whereBetween('created_at', [$request->date_from, $request->date_to])->get();        

        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();

        // total number of respondents/customer
        $total_respondents = $date_range->groupBy('customer_id')->count();

        // total number of respondents/customer who rated VS/S
        $total_vss_respondents = $date_range->where('rate_score', '>','3')->groupBy('customer_id')->count();
        
        // total number of promoters or respondents who rated 9-10 in recommendation rating
        $total_promoters = $customer_recommendation_ratings->where('recommend_rate_score', '>','8')->groupBy('customer_id')->count();
        
        // total number of detractors or respondents who rated 0-6 in recommendation rating
        $total_detractors = $customer_recommendation_ratings->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();

        $ilsr_grand_total =0;
        // loop for getting importance ls rating grand total for ws rating calculation
        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            $vi_total = $date_range->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->count();
            $i_total = $date_range->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->count();
            $mi_total = $date_range->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->count();
            $li_total = $date_range->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->count();
            $nai_total = $date_range->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->count();

            $x_vi_total = $vi_total * 5; 
            $x_i_total = $i_total * 4; 
            $x_mi_total = $mi_total * 3; 
            $x_li_total = $li_total * 2; 
            $x_nai_total = $nai_total * 1;
            $x_importance_total = $x_vi_total + $x_i_total + $x_mi_total + $x_li_total + $x_nai_total  ; 

            // Importance Likert Scale RAting 
            if($x_importance_total != 0){
                $ilsr_total = $x_importance_total / $total_respondents;
                $ilsr_grand_total =  $ilsr_grand_total + $ilsr_total;
            }

        }

        // PART I : CUSTOMER RATING OF SERVICE QUALITY 

        //set initial value of buttom side total scores
        $y_totals = [];
        $grand_vs_total = 0;
        $grand_s_total = 0;
        $grand_n_total = 0;
        $grand_vd_total = 0;
        $grand_d_total = 0;
        $grand_total = 0;
        
        //set initial value of right side total scores
        $x_vs_total = 0; 
        $x_s_total = 0; 
        $x_n_total = 0; 
        $x_d_total = 0; 
        $x_vd_total = 0; 
        $x_grand_total = 0 ; 

        $likert_scale_rating_totals = [];
        $lsr_total= 0;
        $lsr_grand_total= 0;

         // PART II : IMPORTANCE OF THIS ATTRIBUTE 

        //set importance rating score 
        $importance_rate_score_totals = [];
        $x_importance_totals = [];
        $x_importance_total=0; 

        $x_vi_total = 0; 
        $x_i_total =0; 
        $x_mi_total =0; 
        $x_li_total = 0; 
        $x_nai_total = 0;

        $importance_ilsr_totals = [];
        $ilsr_total = 0;

        $gap_totals = [];
        $gap_total = 0 ;
        $gap_grand_total=0;
        $ss_total= 0;
        $ss_totals = [];
        $wf_total= 0;
        $wf_totals = [];
        $ws_total= 0;
        $ws_totals = [];
        $ws_grand_total = 0;

        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            //PART I
            $vs_total = $date_range->where('rate_score', 5)->where('dimension_id', $dimensionId)->count();
            $s_total = $date_range->where('rate_score', 4)->where('dimension_id', $dimensionId)->count();
            $n_total = $date_range->where('rate_score', 3)->where('dimension_id', $dimensionId)->count();
            $d_total = $date_range->where('rate_score', 2)->where('dimension_id', $dimensionId)->count();
            $vd_total = $date_range->where('rate_score', 1)->where('dimension_id', $dimensionId)->count();          
       
            $x_vs_total = $vs_total * 5; 
            $x_s_total = $s_total * 4; 
            $x_n_total = $n_total * 3; 
            $x_d_total = $d_total * 2; 
            $x_vd_total = $vd_total * 1; 
            $x_grand_total = $x_vs_total + $x_s_total + $x_n_total + $x_d_total + $x_vd_total  ; 
         
            // right side total score divided by total repondents or customers
            if($x_grand_total != 0){
                $lsr_total = $x_grand_total / $total_respondents;
            }
           
            // SS = lsr with 3 decimals
            $ss_total = number_format($lsr_total, 3);
            $ss_totals[$dimensionId] = [
                'ss_total' => $ss_total,
            ];

            //likert sclae rating grandtotal

            $lsr_grand_total =  $lsr_grand_total + $lsr_total;
            $x_totals[$dimensionId] = [
                'x_total_score' => $x_grand_total,
            ];

            $lsr_total = number_format($lsr_total, 2);

            $likert_scale_rating_totals[$dimensionId] = [
                'lsr_total' => $lsr_total,
            ];

            $y_totals[$dimensionId] = [
                'vs_total' => $vs_total,
                's_total' => $s_total,
                'n_total' => $n_total,
                'd_total' => $d_total,
                'vd_total' => $vd_total,
            ];

            $grand_vs_total+=$vs_total;
            $grand_s_total+=$s_total;
            $grand_n_total+=$n_total;
            $grand_d_total+=$d_total;
            $grand_vd_total+=$vd_total;       
                     
            // PART II
            $vi_total = $date_range->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->count();
            $i_total = $date_range->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->count();
            $mi_total = $date_range->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->count();
            $li_total = $date_range->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->count();
            $nai_total = $date_range->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->count();
        
            $importance_rate_score_totals[$dimensionId] = [
                'vi_total' => $vi_total,
                'i_total' => $i_total,
                'mi_total' => $mi_total,
                'li_total' => $li_total,
                'nai_total' => $nai_total,
            ];

            $x_vi_total = $vi_total * 5; 
            $x_i_total = $i_total * 4; 
            $x_mi_total = $mi_total * 3; 
            $x_li_total = $li_total * 2; 
            $x_nai_total = $nai_total * 1;
            $x_importance_total = $x_vi_total + $x_i_total + $x_mi_total + $x_li_total + $x_nai_total  ; 
            
            //right side total importance rate scores 
            $x_importance_totals[$dimensionId] = [
                'x_importance_total_score' => $x_importance_total,
            ];
            
            // Likert Scale RAting 
            if($x_importance_total != 0){
                $ilsr_total = $x_importance_total / $total_respondents;
            }
            $ilsr_total = number_format($ilsr_total, 2);

            $importance_ilsr_totals[$dimensionId] = [
                'ilsr_total' => $ilsr_total,
            ];
 
            // GAP = attributes total score minus importance of attributes total score
            $gap_total=  $ilsr_total - $lsr_total;
            $gap_total = number_format($gap_total, 2);

            $gap_totals[$dimensionId] = [
                'gap_total' => $gap_total,
            ];

            $gap_grand_total += $gap_total;
            $gap_grand_total = number_format($gap_grand_total, 2);

            // WF = (importance LS Rating divided by importance grand total  of ls rating) * 100
            if($ilsr_total != 0){
                $wf_total =  ($ilsr_total / $ilsr_grand_total) * 100;
            }
            $wf_total = number_format($wf_total, 2);
            $wf_totals[$dimensionId] = [
                'wf_total' => $wf_total,
            ];

            // WS = (SS * WF) / 100  
            $ws_total = ($ss_total * $wf_total) / 100;   
            $ws_grand_total = $ws_grand_total + $ws_total;
            $ws_total = number_format($ws_total, 2);
            $ws_grand_total = number_format($ws_grand_total, 2);
            $ws_totals[$dimensionId] = [
                'ws_total' => $ws_total,
            ];
        }

        // round off Likert Scale Rating grand total and control decimal to 2 
        $lsr_grand_total = number_format($lsr_grand_total, 2);      

        // table below total score
        $grand_vs_total =   $grand_vs_total * 5;
        $grand_s_total =   $grand_s_total * 5;
        $grand_n_total =   $grand_n_total * 5;
        $grand_d_total =   $grand_d_total * 5;
        $grand_vd_total =   $grand_vd_total * 5;

        $x_grand_total =  $grand_vs_total +  $grand_s_total + $grand_n_total +  $grand_d_total +   $grand_vd_total;

        //Percentage of Respondents/Customers who rated VS/S: 
        // = total no. of respondents / total no. respondets who rated vs/s * 100
        $percentage_vss_respondents  = 0;
        if($total_respondents != 0){
            $percentage_vss_respondents  = ($total_respondents/$total_vss_respondents) * 100;
        }
        $percentage_vss_respondents = number_format( $percentage_vss_respondents , 2);

        $customer_satisfaction_rating = 0;
        if($total_vss_respondents != 0){
            $customer_satisfaction_rating = ($total_vss_respondents/$total_vss_respondents) * 100;
        }
        $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);

        // Customer Satisfaction Index (CSI) = (ws grand total / 5) * 100
        $customer_satisfaction_index = 0;
        if($ws_grand_total != 0){
            $customer_satisfaction_index = ($ws_grand_total/5) * 100;
        }
        $customer_satisfaction_index = number_format( $customer_satisfaction_index , 2);

        //Percentage of Promoters = number of promoters / total respondents
        $percentage_promoters = 0;
        if($total_promoters != 0){
            $percentage_promoters = number_format((($total_promoters / $total_respondents) * 100), 2);
        }

        //Percentage of Promoters = number of promoters / total respondents
        $percentage_detractors = 0;
        if($total_detractors != 0){
            $percentage_detractors = number_format((($total_detractors / $total_respondents) * 100),2);
        }

        // Net Promotion Scores(NPS) = Percentage of Promoters−Percentage of Detractors
        $net_promotion_score =  number_format(($percentage_promoters - $percentage_detractors),2);
  


        //send response to front end
        return Inertia::render('CSI/Index')
            ->with('sub_unit', $sub_unit)
            ->with('unit_pstos', $unit_pstos)
            ->with('sub_unit_pstos', $sub_unit_pstos)
            ->with('sub_unit_types', $sub_unit_types)
            ->with('dimensions', $dimensions)
            ->with('service', $request->service)
            ->with('unit', $request->unit)
            ->with('y_totals',$y_totals)
            ->with('grand_vs_total',$grand_vs_total)
            ->with('grand_s_total',$grand_s_total)
            ->with('grand_n_total',$grand_n_total)
            ->with('grand_d_total',$grand_d_total)
            ->with('grand_vd_total',$grand_vd_total)
            ->with('x_totals',$x_totals)
            ->with('x_grand_total',$x_grand_total)
            ->with('likert_scale_rating_totals',$likert_scale_rating_totals)
            ->with('lsr_grand_total',$lsr_grand_total)
            ->with('importance_rate_score_totals',$importance_rate_score_totals)
            ->with('x_importance_totals', $x_importance_totals)
            ->with('importance_ilsr_totals', $importance_ilsr_totals)
            ->with('gap_totals', $gap_totals)
            ->with('gap_grand_total', $gap_grand_total)
            ->with('wf_totals', $wf_totals)
            ->with('ss_totals', $ss_totals)
            ->with('wf_totals', $wf_totals)
            ->with('ws_totals', $ws_totals)
            ->with('total_respondents', $total_respondents)
            ->with('total_vss_respondents', $total_vss_respondents)
            ->with('percentage_vss_respondents', $percentage_vss_respondents)
            ->with('customer_satisfaction_rating', $customer_satisfaction_rating)
            ->with('customer_satisfaction_index', $customer_satisfaction_index)
            ->with('net_promotion_score', $net_promotion_score)
            ->with('percentage_promoters', $percentage_promoters)
            ->with('percentage_detractors', $percentage_detractors)
            ->with('request', $request);    
    }   


    public function generateCSIByUnitMonthly($request, $region_id, $psto_id)
    {
        $sub_unit = $this->getSubUnit($request);
        $unit_pstos = $this->getUnitPSTOs($request);
        $sub_unit_pstos = $this->getSubUnitPSTOs($request);
        $sub_unit_types = $this->getSubUnitTypes($request);

        $date_range = null;
        $customer_recommendation_ratings = null;
        $respondents_list = null;

        $service_id = $request->service['id'];
        $unit_id = $request->unit_id;
        $sub_unit_id = $request->selected_sub_unit;
        $client_type = $request->client_type; 
        $sub_unit_type = $request->sub_unit_type; 

       // search and check list of forms query  
       $customer_ids = $this->querySearchCSF( $region_id, $service_id, $unit_id ,$sub_unit_id , $psto_id, $client_type, $sub_unit_type );
          
    
        $numericMonth = Carbon::parse("1 {$request->selected_month}")->format('m');
        //$date_range = CustomerAttributeRating::whereMonth('created_at', $numericMonth)->get();
        $date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                             ->whereMonth('created_at', $numericMonth)->get();
        $customer_recommendation_ratings = CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                            ->whereMonth('created_at', $numericMonth)->get();
        // List of Respondents/Customers
        $respondents_list = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                                    ->whereMonth('created_at', $numericMonth)->get();
           
        // Dimensions or attributes
        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();

        // total number of respondents/customer
        $total_respondents = $date_range->groupBy('customer_id')->count();

        // total number of respondents/customer who rated VS/S
        $total_vss_respondents = $date_range->where('rate_score', '>','3')->groupBy('customer_id')->count();
        
        // total number of promoters or respondents who rated 9-10 in recommendation rating
        $total_promoters = $customer_recommendation_ratings->where('recommend_rate_score', '>','8')->groupBy('customer_id')->count();
        
        // total number of detractors or respondents who rated 0-6 in recommendation rating
        $total_detractors = $customer_recommendation_ratings->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();

        $ilsr_grand_total =0;
        // loop for getting importance ls rating grand total for ws rating calculation
        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            $vi_total = $date_range->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->count();
            $i_total = $date_range->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->count();
            $mi_total = $date_range->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->count();
            $li_total = $date_range->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->count();
            $nai_total = $date_range->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->count();

            $x_vi_total = $vi_total * 5; 
            $x_i_total = $i_total * 4; 
            $x_mi_total = $mi_total * 3; 
            $x_li_total = $li_total * 2; 
            $x_nai_total = $nai_total * 1;
            $x_importance_total = $x_vi_total + $x_i_total + $x_mi_total + $x_li_total + $x_nai_total  ; 

            // Importance Likert Scale RAting 
            if($x_importance_total != 0){
                $ilsr_total = $x_importance_total / $total_respondents;
                $ilsr_grand_total =  $ilsr_grand_total + $ilsr_total;
            }

        }

        // PART I : CUSTOMER RATING OF SERVICE QUALITY 

        //set initial value of buttom side total scores
        $y_totals = [];
        $grand_vs_total = 0;
        $grand_s_total = 0;
        $grand_n_total = 0;
        $grand_vd_total = 0;
        $grand_d_total = 0;
        $grand_total = 0;
        
        //set initial value of right side total scores
        $x_vs_total = 0; 
        $x_s_total = 0; 
        $x_n_total = 0; 
        $x_d_total = 0; 
        $x_vd_total = 0; 
        $x_grand_total = 0 ; 

        $likert_scale_rating_totals = [];
        $lsr_total= 0;
        $lsr_grand_total= 0;

         // PART II : IMPORTANCE OF THIS ATTRIBUTE 

        //set importance rating score 
        $importance_rate_score_totals = [];
        $x_importance_totals = [];
        $x_importance_total=0; 

        $x_vi_total = 0; 
        $x_i_total =0; 
        $x_mi_total =0; 
        $x_li_total = 0; 
        $x_nai_total = 0;

        $importance_ilsr_totals = [];
        $ilsr_total = 0;

        $gap_totals = [];
        $gap_total = 0 ;
        $gap_grand_total=0;
        $ss_total= 0;
        $ss_totals = [];
        $wf_total= 0;
        $wf_totals = [];
        $ws_total= 0;
        $ws_totals = [];
        $ws_grand_total = 0;

        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            //PART I
            $vs_total = $date_range->where('rate_score', 5)->where('dimension_id', $dimensionId)->count();
            $s_total = $date_range->where('rate_score', 4)->where('dimension_id', $dimensionId)->count();
            $n_total = $date_range->where('rate_score', 3)->where('dimension_id', $dimensionId)->count();
            $d_total = $date_range->where('rate_score', 2)->where('dimension_id', $dimensionId)->count();
            $vd_total = $date_range->where('rate_score', 1)->where('dimension_id', $dimensionId)->count();          
       
            $x_vs_total = $vs_total * 5; 
            $x_s_total = $s_total * 4; 
            $x_n_total = $n_total * 3; 
            $x_d_total = $d_total * 2; 
            $x_vd_total = $vd_total * 1; 
            $x_grand_total = $x_vs_total + $x_s_total + $x_n_total + $x_d_total + $x_vd_total  ; 
         
            // right side total score divided by total repondents or customers
            if($x_grand_total != 0){
                $lsr_total = $x_grand_total / $total_respondents;
            }
           
            // SS = lsr with 3 decimals
            $ss_total = number_format($lsr_total, 3);
            $ss_totals[$dimensionId] = [
                'ss_total' => $ss_total,
            ];

            //likert sclae rating grandtotal

            $lsr_grand_total =  $lsr_grand_total + $lsr_total;
            $x_totals[$dimensionId] = [
                'x_total_score' => $x_grand_total,
            ];

            $lsr_total = number_format($lsr_total, 2);

            $likert_scale_rating_totals[$dimensionId] = [
                'lsr_total' => $lsr_total,
            ];

            $y_totals[$dimensionId] = [
                'vs_total' => $vs_total,
                's_total' => $s_total,
                'n_total' => $n_total,
                'd_total' => $d_total,
                'vd_total' => $vd_total,
            ];

            $grand_vs_total+=$vs_total;
            $grand_s_total+=$s_total;
            $grand_n_total+=$n_total;
            $grand_d_total+=$d_total;
            $grand_vd_total+=$vd_total;       
                     
            // PART II
            $vi_total = $date_range->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->count();
            $i_total = $date_range->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->count();
            $mi_total = $date_range->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->count();
            $li_total = $date_range->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->count();
            $nai_total = $date_range->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->count();
        
            $importance_rate_score_totals[$dimensionId] = [
                'vi_total' => $vi_total,
                'i_total' => $i_total,
                'mi_total' => $mi_total,
                'li_total' => $li_total,
                'nai_total' => $nai_total,
            ];

            $x_vi_total = $vi_total * 5; 
            $x_i_total = $i_total * 4; 
            $x_mi_total = $mi_total * 3; 
            $x_li_total = $li_total * 2; 
            $x_nai_total = $nai_total * 1;
            $x_importance_total = $x_vi_total + $x_i_total + $x_mi_total + $x_li_total + $x_nai_total  ; 
            
            //right side total importance rate scores 
            $x_importance_totals[$dimensionId] = [
                'x_importance_total_score' => $x_importance_total,
            ];
            
            // Likert Scale RAting 
            if($x_importance_total != 0){
                $ilsr_total = $x_importance_total / $total_respondents;
            }
            $ilsr_total = number_format($ilsr_total, 2);

            $importance_ilsr_totals[$dimensionId] = [
                'ilsr_total' => $ilsr_total,
            ];
 
            // GAP = attributes total score minus importance of attributes total score
            $gap_total=  $ilsr_total - $lsr_total;
            $gap_total = number_format($gap_total, 2);

            $gap_totals[$dimensionId] = [
                'gap_total' => $gap_total,
            ];

            $gap_grand_total += $gap_total;
            $gap_grand_total = number_format($gap_grand_total, 2);

            // WF = (importance LS Rating divided by importance grand total  of ls rating) * 100
            if($ilsr_total != 0){
                $wf_total =  ($ilsr_total / $ilsr_grand_total) * 100;
            }
            $wf_total = number_format($wf_total, 2);
            $wf_totals[$dimensionId] = [
                'wf_total' => $wf_total,
            ];

            // WS = (SS * WF) / 100  
            $ws_total = ($ss_total * $wf_total) / 100;   
            $ws_grand_total = $ws_grand_total + $ws_total;
            $ws_total = number_format($ws_total, 2);
            $ws_grand_total = number_format($ws_grand_total, 2);
            $ws_totals[$dimensionId] = [
                'ws_total' => $ws_total,
            ];
        }

        // round off Likert Scale Rating grand total and control decimal to 2 
        $lsr_grand_total = number_format($lsr_grand_total, 2);      

        // table below total score
        $grand_vs_total =   $grand_vs_total * 5;
        $grand_s_total =   $grand_s_total * 5;
        $grand_n_total =   $grand_n_total * 5;
        $grand_d_total =   $grand_d_total * 5;
        $grand_vd_total =   $grand_vd_total * 5;

        $x_grand_total =  $grand_vs_total +  $grand_s_total + $grand_n_total +  $grand_d_total +   $grand_vd_total;

        //Percentage of Respondents/Customers who rated VS/S: 
        // = total no. of respondents / total no. respondets who rated vs/s * 100
        $percentage_vss_respondents  = 0;
        if($total_respondents != 0){
            $percentage_vss_respondents  = ($total_respondents/$total_vss_respondents) * 100;
        }
        $percentage_vss_respondents = number_format( $percentage_vss_respondents , 2);

        $customer_satisfaction_rating = 0;
        if($total_vss_respondents != 0){
            $customer_satisfaction_rating = ($total_vss_respondents/$total_vss_respondents) * 100;
        }
        $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);

        // Customer Satisfaction Index (CSI) = (ws grand total / 5) * 100
        $customer_satisfaction_index = 0;
        if($ws_grand_total != 0){
            $customer_satisfaction_index = ($ws_grand_total/5) * 100;
        }
        $customer_satisfaction_index = number_format( $customer_satisfaction_index , 2);

        //Percentage of Promoters = number of promoters / total respondents
        $percentage_promoters = 0;
        if($total_promoters != 0){
            $percentage_promoters = number_format((($total_promoters / $total_respondents) * 100), 2);
        }

        //Percentage of Promoters = number of promoters / total respondents
        $percentage_detractors = 0;
        if($total_detractors != 0){
            $percentage_detractors = number_format((($total_detractors / $total_respondents) * 100),2);
        }

        // Net Promotion Scores(NPS) = Percentage of Promoters−Percentage of Detractors
        $net_promoter_score =  number_format(($percentage_promoters - $percentage_detractors),2);
        //Respondents list
        $data = CARResource::collection($respondents_list);

        //comments and  complaints
        $comment_list = CustomerComment::whereIn('customer_id', $customer_ids)
                                    ->whereMonth('created_at', $numericMonth)
                                    ->whereYear('created_at', $request->selected_year)->get();
        
        $comments = $comment_list->where('comment','!=','')->pluck('comment'); 

        $total_comments = $comment_list->where('comment','!=','')->count();
        $total_complaints = $comment_list->where('is_complaint',1)->count();

        //send response to front end
        return Inertia::render('CSI/Index')
            ->with('sub_unit', $sub_unit)
            ->with('unit_pstos', $unit_pstos)
            ->with('sub_unit_pstos', $sub_unit_pstos)
            ->with('sub_unit_types', $sub_unit_types)
            ->with('dimensions', $dimensions)
            ->with('service', $request->service)
            ->with('unit', $request->unit)
            ->with('respondents_list',$data)
            ->with('y_totals',$y_totals)
            ->with('grand_vs_total',$grand_vs_total)
            ->with('grand_s_total',$grand_s_total)
            ->with('grand_n_total',$grand_n_total)
            ->with('grand_d_total',$grand_d_total)
            ->with('grand_vd_total',$grand_vd_total)
            ->with('x_totals',$x_totals)
            ->with('x_grand_total',$x_grand_total)
            ->with('likert_scale_rating_totals',$likert_scale_rating_totals)
            ->with('lsr_grand_total',$lsr_grand_total)
            ->with('importance_rate_score_totals',$importance_rate_score_totals)
            ->with('x_importance_totals', $x_importance_totals)
            ->with('importance_ilsr_totals', $importance_ilsr_totals)
            ->with('gap_totals', $gap_totals)
            ->with('gap_grand_total', $gap_grand_total)
            ->with('wf_totals', $wf_totals)
            ->with('ss_totals', $ss_totals)
            ->with('wf_totals', $wf_totals)
            ->with('ws_totals', $ws_totals)
            ->with('total_respondents', $total_respondents)
            ->with('total_vss_respondents', $total_vss_respondents)
            ->with('percentage_vss_respondents', $percentage_vss_respondents)
            ->with('customer_satisfaction_rating', $customer_satisfaction_rating)
            ->with('customer_satisfaction_index', $customer_satisfaction_index)
            ->with('net_promoter_score', $net_promoter_score)
            ->with('percentage_promoters', $percentage_promoters)
            ->with('percentage_detractors', $percentage_detractors)
            ->with('total_comments', $total_comments)
            ->with('total_complaints', $total_complaints)
            ->with('comments', $comments)
            ->with('request', $request);    
    }   

    public function generateCSIByUnitFirstQuarter($request, $region_id, $psto_id)
    {
        $sub_unit = $this->getSubUnit($request);
        $unit_pstos = $this->getUnitPSTOs($request);
        $sub_unit_pstos = $this->getSubUnitPSTOs($request);
        $sub_unit_types = $this->getSubUnitTypes($request);

        $date_range = null;
        $customer_recommendation_ratings = null;
        $respondents_list = null;
        $month_jan = [];
        $month_feb = [];
        $month_mar = [];

        $service_id = $request->service['id'];
        $unit_id = $request->unit_id;
        $sub_unit_id = $request->selected_sub_unit;
        $client_type = $request->client_type; 
        $sub_unit_type = $request->sub_unit_type; 

       // search and check list of forms query  
       $customer_ids = $this->querySearchCSF( $region_id, $service_id, $unit_id ,$sub_unit_id , $psto_id, $client_type, $sub_unit_type );
            
        $startDate = Carbon::create($request->selected_year, 1, 1)->startOfDay();
        $endDate = Carbon::create($request->selected_year, 3, 31)->endOfDay();
        $date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                            ->whereBetween('created_at', [$startDate, $endDate])
                                            ->whereYear('created_at', $request->selected_year)->get(); 
        $month_jan = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                            ->whereMonth('created_at', 01)
                                            ->whereYear('created_at', $request->selected_year)->get(); 
        $month_feb = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                            ->whereMonth('created_at', 02)
                                            ->whereYear('created_at', $request->selected_year)->get(); 
        $month_mar = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                            ->whereMonth('created_at', 03)
                                            ->whereYear('created_at', $request->selected_year)->get();

        $customer_recommendation_ratings = CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                                        ->whereBetween('created_at', [$startDate, $endDate])
                                                                        ->whereYear('created_at', $request->selected_year)->get();

        $jan_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereMonth('created_at',01)
                                                ->whereYear('created_at', $request->selected_year)->get();
        
        $feb_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereMonth('created_at',02)
                                                ->whereYear('created_at', $request->selected_year)->get();
                                                    
        $mar_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereMonth('created_at',03)
                                                ->whereYear('created_at', $request->selected_year)->get();


        $respondents_list = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$startDate, $endDate])
                                                ->whereYear('created_at', $request->selected_year)->get();
        

        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();
        $grand_total_raw_points = 0;
        $vs_grand_total_score =0;
        $s_grand_total_score = 0;
        $ndvd_grand_total_score = 0;
        $grand_total_score =0;


        $vs_grand_total_raw_points = 0;
        $s_grand_total_raw_points = 0;
        $ndvd_grand_total_raw_points = 0;
        $lsr_grand_total = 0 ;
        $lsr_average = 0;

        //Importance total raw points  
        $vi_grand_total_raw_points = 0;
        $i_grand_total_raw_points = 0;
        $misinai_grand_total_raw_points = 0;
        //Importance grand total score
          
        $vi_grand_total_score=0;
        $i_grand_total_score =0;
        $misinai_grand_total_score = 0;

        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            //PART I

            // April total responses with its dimensions and rate score
            $jan_vs_total = $month_jan->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $jan_s_total = $month_jan->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $jan_n_total = $month_jan->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $jan_d_total = $month_jan->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $jan_vd_total = $month_jan->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $jan_grand_total =  $jan_vs_total + $jan_s_total + $jan_n_total + $jan_d_total + $jan_vd_total ; 
            

            //  May total responses with its dimensions and rate score
            $feb_vs_total = $month_feb->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $feb_s_total = $month_feb->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $feb_n_total = $month_feb->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $feb_d_total = $month_feb->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $feb_vd_total = $month_feb->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $feb_grand_total =  $feb_vs_total + $feb_s_total + $feb_n_total + $feb_d_total + $feb_vd_total ; 

            //  June total responses with its dimensions and rate score
            $mar_vs_total = $month_mar->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $mar_s_total = $month_mar->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $mar_n_total = $month_mar->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $mar_d_total = $month_mar->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $mar_vd_total = $month_mar->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $mar_grand_total =  $mar_vs_total + $mar_s_total + $mar_n_total + $mar_d_total + $mar_vd_total ; 

            // Quarter 1 Very Satisfied total with specific dimention or attribute
            $q1_vs_totals[$dimensionId] = [
                'jan_vs_total' => $jan_vs_total,
                'feb_vs_total' => $feb_vs_total,
                'mar_vs_total' => $mar_vs_total,
            ];

            $q1_s_totals[$dimensionId] = [
                'jan_s_total' => $jan_s_total,
                'feb_s_total' => $feb_s_total,
                'mar_s_total' => $mar_s_total,
            ];

            $q1_n_totals[$dimensionId] = [
                'jan_n_total' => $jan_n_total,
                'feb_n_total' => $feb_n_total,
                'mar_n_total' => $mar_n_total,
            ];

            $q1_d_totals[$dimensionId] = [
                'jan_d_total' => $jan_d_total,
                'feb_d_total' => $feb_d_total,
                'mar_d_total' => $mar_d_total,
            ];

            $q1_vd_totals[$dimensionId] = [
                'jan_vd_total' => $jan_vd_total,
                'feb_vd_total' => $feb_vd_total,
                'mar_vd_total' => $mar_vd_total,
            ];

            $q1_grand_totals[$dimensionId] = [
                'jan_grand_total' => $jan_grand_total,
                'feb_grand_total' => $feb_grand_total,
                'mar_grand_total' => $mar_grand_total,
            ];

            //Total Raw Points totals
            $vs_total_raw_points = $jan_vs_total + $feb_vs_total + $mar_vs_total;
            $s_total_raw_points = $jan_s_total + $feb_s_total + $mar_s_total;
            $n_total_raw_points =$jan_n_total + $feb_n_total + $mar_n_total;
            $d_total_raw_points =$jan_d_total + $feb_d_total + $mar_d_total;
            $vd_total_raw_points =$jan_vd_total + $feb_vd_total + $mar_vd_total;
            $total_raw_points = $vs_total_raw_points + $s_total_raw_points + $n_total_raw_points +  $d_total_raw_points +  $vd_total_raw_points;           

            $vs_grand_total_raw_points += $vs_total_raw_points;
            $s_grand_total_raw_points +=  $s_total_raw_points;
            $ndvd_grand_total_raw_points +=  $n_total_raw_points + $d_total_raw_points + $vd_total_raw_points;
            $grand_total_raw_points+= $total_raw_points;

            $trp_totals[$dimensionId] = [
                'vs_total_raw_points' => $vs_total_raw_points,
                's_total_raw_points' => $s_total_raw_points,
                'n_total_raw_points' => $n_total_raw_points,
                'd_total_raw_points' => $d_total_raw_points,
                'vd_total_raw_points' => $vd_total_raw_points,
                'total_raw_points' => $total_raw_points,
            ];

            //Part 1 Quarter 1 total scores
            $x_vs_total = $vs_total_raw_points * 5; 
            $x_s_total = $s_total_raw_points * 4; 
            $x_n_total = $n_total_raw_points * 3; 
            $x_d_total = $d_total_raw_points * 3; 
            $x_vd_total = $vd_total_raw_points * 1; 
            $x_total_score =  $x_vs_total +  $x_s_total +  $x_n_total +  $x_d_total + $x_vd_total;
            
            $vs_grand_total_score += $x_vs_total ;
            $s_grand_total_score += $x_s_total ;
            $ndvd_grand_total_score += $x_n_total +  $x_d_total + $x_vd_total ;
            $grand_total_score += $x_total_score ;

            $p1_total_scores[$dimensionId] = [ 
                'x_vs_total' => $x_vs_total,
                'x_s_total' => $x_s_total,
                'x_n_total' => $x_n_total,
                'x_d_total' => $x_d_total,
                'x_vd_total' => $x_vd_total, 
                'x_total_score' => $x_total_score,
            ];

            // Likert Scale Rating = total score / grand total of total raw points
            if($total_raw_points != 0 ){
                $vs_lsr_total =   number_format(($x_vs_total  /  $total_raw_points),2);
                $s_lsr_total =    number_format(($x_s_total /  $total_raw_points),2);
                $n_lsr_total =   number_format(($x_n_total /  $total_raw_points),2);
                $d_lsr_total =   number_format(($x_d_total /  $total_raw_points),2);
                $vd_lsr_total =   number_format(($x_vd_total /  $total_raw_points),2);
                $lsr_total =  number_format(($vs_lsr_total +  $s_lsr_total  +  $n_lsr_total  +  $d_lsr_total  +  $vd_lsr_total),2);
                $lsr_grand_total +=  $lsr_total;
                $lsr_grand_total =number_format(($lsr_grand_total),2);
                $lsr_average =  number_format(($lsr_grand_total / $dimensionId), 2);
            }
            else{
                $vs_lsr_total =  0;
                $s_lsr_total =  0;
                $n_lsr_total =  0;
                $d_lsr_total = 0;
                $vd_lsr_total =  0;
                $lsr_total = 0;
                $lsr_grand_total +=  0;
                $lsr_average =  0;
            }

            $lsr_totals[$dimensionId] = [
                'vs_lsr_total' => $vs_lsr_total,
                's_lsr_total' => $s_lsr_total,
                'n_lsr_total' => $n_lsr_total,
                'd_lsr_total' => $d_lsr_total,
                'vd_lsr_total' => $vd_lsr_total,
                'lsr_total' => $lsr_total,
            ];
            
            // PART II
              // April total responses with its dimensions and rate score
              $jan_vi_total = $month_jan->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $jan_i_total = $month_jan->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $jan_mi_total = $month_jan->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $jan_si_total = $month_jan->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $jan_nai_total = $month_jan->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $jan_i_grand_total =  $jan_vi_total + $jan_i_total + $jan_mi_total + $jan_si_total + $jan_nai_total ; 
  
              //  MAy total responses with its dimensions and rate score
              $feb_vi_total = $month_feb->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $feb_i_total = $month_feb->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $feb_mi_total = $month_feb->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $feb_si_total = $month_feb->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $feb_nai_total = $month_feb->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $feb_i_grand_total =  $feb_vi_total + $feb_i_total + $feb_mi_total + $feb_si_total + $feb_nai_total ; 
  
              //  June total responses with its dimensions and rate score
              $mar_vi_total = $month_mar->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $mar_i_total = $month_mar->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $mar_mi_total = $month_mar->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $mar_si_total = $month_mar->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $mar_nai_total = $month_mar->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $mar_i_grand_total =  $mar_vi_total + $mar_i_total + $mar_mi_total + $mar_si_total + $mar_nai_total ; 

                //Very Important total with specific dimention or attribute
                $q1_vi_totals[$dimensionId] = [
                    'jan_vi_total' => $jan_vi_total,
                    'feb_vi_total' => $feb_vi_total,
                    'mar_vi_total' => $mar_vi_total,
                ];
                //Important total with specific dimention or attribute
                $q1_i_totals[$dimensionId] = [
                    'jan_i_total' => $jan_i_total,
                    'feb_i_total' => $feb_i_total,
                    'mar_i_total' => $mar_i_total,
                ];
                //Moderately Important total with specific dimention or attribute
                $q1_mi_totals[$dimensionId] = [
                    'jan_mi_total' => $jan_mi_total,
                    'feb_mi_total' => $feb_mi_total,
                    'mar_mi_total' => $mar_mi_total,
                ];
                // Quarter 1 slightly Important total with specific dimention or attribute
                $q1_si_totals[$dimensionId] = [
                    'jan_si_total' => $jan_si_total,
                    'feb_si_total' => $feb_si_total,
                    'mar_si_total' => $mar_si_total,
                ];

                $q1_nai_totals[$dimensionId] = [
                    'jan_nai_total' => $jan_nai_total,
                    'feb_nai_total' => $feb_nai_total,
                    'mar_nai_total' => $mar_nai_total,
                ];

                $q2_i_grand_totals[$dimensionId] = [
                    'jan_i_grand_total' => $jan_i_grand_total,
                    'feb_i_grand_total' => $feb_i_grand_total,
                    'mar_i_grand_total' => $mar_i_grand_total,
                ];


                
            //Importance Total Raw Points totals
            $vi_total_raw_points = $jan_vi_total + $feb_vi_total + $mar_vi_total;
            $i_total_raw_points = $jan_s_total + $feb_i_total + $mar_i_total;
            $mi_total_raw_points = $jan_mi_total + $feb_mi_total + $mar_mi_total;
            $si_total_raw_points = $jan_si_total + $feb_si_total + $mar_si_total;
            $nai_total_raw_points = $jan_nai_total + $feb_nai_total + $mar_nai_total;
            $i_total_raw_points = $vi_total_raw_points + $i_total_raw_points + $mi_total_raw_points +  $si_total_raw_points +  $nai_total_raw_points;           

            $vi_grand_total_raw_points += $vi_total_raw_points;
            $s_grand_total_raw_points +=  $i_total_raw_points;
            $misinai_grand_total_raw_points +=  $mi_total_raw_points + $si_total_raw_points + $nai_total_raw_points;
            $i_grand_total_raw_points+= $total_raw_points;

            $i_trp_totals[$dimensionId] = [
                'vi_total_raw_points' => $vi_total_raw_points,
                'i_total_raw_points' => $i_total_raw_points,
                'mi_total_raw_points' => $mi_total_raw_points,
                'si_total_raw_points' => $si_total_raw_points,
                'nai_total_raw_points' => $nai_total_raw_points,
                'i_total_raw_points' => $i_total_raw_points,
            ];

            //Part 1 Quarter 1 total scores
            $x_vi_total = $vi_total_raw_points * 5; 
            $x_i_total = $i_total_raw_points * 4; 
            $x_mi_total = $mi_total_raw_points * 3; 
            $x_si_total = $si_total_raw_points * 3; 
            $x_nai_total = $nai_total_raw_points * 1; 
            $x_i_total_score =  $x_vi_total +  $x_i_total +  $x_mi_total +  $x_si_total + $x_nai_total;
            
            $vi_grand_total_score += $x_vi_total ;
            $i_grand_total_score += $x_si_total ;
            $misinai_grand_total_score += $x_mi_total +  $x_si_total + $x_nai_total ;

            $i_total_scores[$dimensionId] = [ 
                'x_vi_total' => $x_vi_total,
                'x_i_total' => $x_i_total,
                'x_mi_total' => $x_mi_total,
                'x_si_total' => $x_si_total,
                'x_nai_total' => $x_nai_total, 
                'x_i_total_score' => $x_i_total_score,
            ];

        
        }

        // Total No. of Very Satisfied (VS) Responses of First Quarte
        $jan_total_vs_respondents = $month_jan->where('rate_score',5)->groupBy('customer_id')->count();
        $feb_total_vs_respondents = $month_feb->where('rate_score',5)->groupBy('customer_id')->count();
        $mar_total_vs_respondents = $month_mar->where('rate_score',5)->groupBy('customer_id')->count();

        // Total No. of Satisfied (S) Responses
        $jan_total_s_respondents = $month_jan->where('rate_score',4)->groupBy('customer_id')->count();
        $feb_total_s_respondents = $month_feb->where('rate_score',4)->groupBy('customer_id')->count();
        $mar_total_s_respondents = $month_mar->where('rate_score',4)->groupBy('customer_id')->count();

        // Total No. of Other (N, D, VD) Responses
        $jan_total_ndvd_respondents = $month_jan->where('rate_score','<', 4)->groupBy('customer_id')->count();
        $feb_total_ndvd_respondents = $month_feb->where('rate_score','<', 4)->groupBy('customer_id')->count();
        $mar_total_ndvd_respondents = $month_mar->where('rate_score','<', 4)->groupBy('customer_id')->count();
          
        // Total No. of All Responses
        $jan_total_respondents = $month_jan->groupBy('customer_id')->count();
        $feb_total_respondents = $month_feb->groupBy('customer_id')->count();
        $mar_total_respondents = $month_feb->groupBy('customer_id')->count();

        //Total respondents /Customers
        $total_respondents = $date_range->groupBy('customer_id')->count();

        // total number of respondents/customer who rated VS/S
        $total_vss_respondents = $date_range->where('rate_score', '>','3')->groupBy('customer_id')->count();
        // total number of respondents/customer who rated VS
        $total_vs_respondents = $date_range->where('rate_score', '=','5')->groupBy('customer_id')->count();
        // total number of respondents/customer who rated S
        $total_s_respondents = $date_range->where('rate_score', '=','4')->groupBy('customer_id')->count();
    
        // Frst quarter total number of promoters or respondents who rated 9-10 in recommendation rating
        $total_promoters = $customer_recommendation_ratings->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        $jan_total_promoters = $jan_crr->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        $feb_total_promoters = $feb_crr->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        $mar_total_promoters = $mar_crr->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        
        // total number of detractors or respondents who rated 0-6 in recommendation rating
        $total_detractors = $customer_recommendation_ratings->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
         $jan_total_detractors = $jan_crr->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
         $feb_total_detractors = $feb_crr->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
         $mar_total_detractors = $mar_crr->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
  
          //Percentage of Respondents/Customers who rated VS/S = total no. of respondents / total no. respondets who rated vs/s * 100
        $percentage_vss_respondents  = 0;
        if($total_respondents != 0){
            $percentage_vss_respondents  = ($total_respondents/$total_vss_respondents) * 100;
        }
        $percentage_vss_respondents = number_format( $percentage_vss_respondents , 2);

        $customer_satisfaction_rating = 0;
        if($total_vss_respondents != 0){
            $customer_satisfaction_rating = ($total_vss_respondents/$total_vss_respondents) * 100;
        }
        $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);

        //Percentage of Promoters = number of promoters / total respondents
        $percentage_promoters = 0;
        // january % promoters
        $jan_percentage_promoters = 0.00;
        $feb_percentage_promoters = 0.00;
        $mar_percentage_promoters = 0.00;

        // Percentage of promoter
        $jan_percentage_detractors = 0.00;
        $feb_percentage_detractors = 0.00;
        $mar_percentage_detractors = 0.00;

        //nps
        $jan_net_promoter_score =  0.00;
        $feb_net_promoter_score = 0.00;
        $mar_net_promoter_score =  0.00;

        //average
        $ave_net_promoter_score = 0.00;
        $average_percentage_promoters =  0.00;
        $average_percentage_detractors =  0.00;

        if($total_respondents != 0){
            $percentage_promoters = number_format((($total_promoters / $total_respondents) * 100), 2);
            $jan_percentage_promoters = number_format((($jan_total_promoters / $total_respondents) * 100), 2);
            $feb_percentage_promoters = number_format((($feb_total_promoters / $total_respondents) * 100), 2);
            $mar_percentage_promoters = number_format((($mar_total_promoters / $total_respondents) * 100), 2);
        
            //Percentage of Promoters = number of promoters / total respondents
            $percentage_detractors = number_format((($total_detractors / $total_respondents) * 100),2);
            $jan_percentage_detractors = number_format((($jan_total_detractors / $total_respondents) * 100),2);
            $feb_percentage_detractors = number_format((($feb_total_detractors / $total_respondents) * 100),2);
            $mar_percentage_detractors = number_format((($mar_total_detractors / $total_respondents) * 100),2);
            

            // Net Promotion Scores(NPS) = Percentage of Promoters−Percentage of Detractors
            $jan_net_promoter_score =  number_format(($jan_percentage_promoters - $jan_percentage_detractors),2);
            $feb_net_promoter_score =  number_format(($feb_percentage_promoters - $feb_percentage_detractors),2);
            $mar_net_promoter_score =  number_format(($mar_percentage_promoters - $mar_percentage_detractors),2);

            $ave_net_promoter_score =  number_format((($jan_net_promoter_score + $feb_net_promoter_score + $mar_net_promoter_score)/ 3),2);
            $average_percentage_promoters =  number_format((($jan_percentage_promoters + $feb_percentage_promoters + $mar_percentage_promoters)/ 3),2);
            $average_percentage_detractors =  number_format((($jan_percentage_detractors + $feb_percentage_detractors + $mar_percentage_detractors)/ 3),2);


            // CSAT = ((Total No. of Very Satisfied (VS) Responses + Total No. of Satisfied (S) Responses) / grand total respondents) * 100
            $customer_satisfaction_rating = 0;
            if($total_vss_respondents != 0){
                $customer_satisfaction_rating = (($total_vss_respondents / $total_respondents)) * 100;
            }
            $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);
        }
        // get Monthly CSI
        $first_month_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 1);
        $second_month_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 2);
        $third_month_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 3);

        $customer_satisfaction_index = number_format((($first_month_csi + $second_month_csi +  $third_month_csi)/3), 2);

        //comments and  complaints
        $comment_list = CustomerComment::whereIn('customer_id', $customer_ids)
                                        ->whereBetween('created_at', [$startDate, $endDate])
                                        ->whereYear('created_at', $request->selected_year)->get();

        $comments = $comment_list->where('comment','!=','')->pluck('comment'); 

        $total_comments = $comment_list->where('comment','!=','')->count();
        $total_complaints = $comment_list->where('is_complaint',1)->count();

        //Respondents list
        $data = CARResource::collection($respondents_list);

        //send response to front end
        return Inertia::render('CSI/Index')
            ->with('sub_unit', $sub_unit)
            ->with('unit_pstos', $unit_pstos)
            ->with('sub_unit_pstos', $sub_unit_pstos)
            ->with('sub_unit_types', $sub_unit_types)
            ->with('dimensions', $dimensions)
            ->with('service', $request->service)
            ->with('unit', $request->unit)
            ->with('respondents_list',$data)
            ->with('q1_vs_totals', $q1_vs_totals)
            ->with('q1_s_totals', $q1_s_totals)
            ->with('q1_n_totals', $q1_n_totals)
            ->with('q1_d_totals', $q1_d_totals)
            ->with('q1_vd_totals', $q1_vd_totals)
            ->with('q1_grand_totals', $q1_grand_totals)
            ->with('trp_totals', $trp_totals)
            ->with('grand_total_raw_points', $grand_total_raw_points)
            ->with('vs_grand_total_raw_points', $vs_grand_total_raw_points)
            ->with('s_grand_total_raw_points', $s_grand_total_raw_points)
            ->with('ndvd_grand_total_raw_points', $ndvd_grand_total_raw_points)
            ->with('p1_total_scores', $p1_total_scores)
            ->with('vs_grand_total_score', $vs_grand_total_score) 
            ->with('s_grand_total_score', $s_grand_total_score) 
            ->with('ndvd_grand_total_score', $ndvd_grand_total_score) 
            ->with('grand_total_score', $grand_total_score) 
            ->with('lsr_totals', $lsr_totals)
            ->with('lsr_grand_total', $lsr_grand_total)
            ->with('lsr_average', $lsr_average ) 
            ->with('jan_total_vs_respondents', $jan_total_vs_respondents)
            ->with('feb_total_vs_respondents', $feb_total_vs_respondents)
            ->with('mar_total_vs_respondents', $mar_total_vs_respondents)
            ->with('jan_total_s_respondents', $jan_total_s_respondents)
            ->with('feb_total_s_respondents', $feb_total_s_respondents)
            ->with('mar_total_s_respondents', $mar_total_s_respondents)
            ->with('jan_total_ndvd_respondents', $jan_total_ndvd_respondents)
            ->with('feb_total_ndvd_respondents', $feb_total_ndvd_respondents)
            ->with('mar_total_ndvd_respondents', $mar_total_ndvd_respondents)
            ->with('jan_total_respondents', $jan_total_respondents)
            ->with('feb_total_respondents', $feb_total_respondents)
            ->with('mar_total_respondents', $mar_total_respondents)
            ->with('total_respondents', $total_respondents)
            ->with('total_vss_respondents', $total_vss_respondents)
            ->with('percentage_vss_respondents', $percentage_vss_respondents)
            ->with('total_promoters', $total_promoters)
            ->with('total_detractors', $total_detractors)
            ->with('q1_vi_totals', $q1_vi_totals)
            ->with('q1_i_totals', $q1_i_totals)
            ->with('q1_mi_totals', $q1_mi_totals)
            ->with('q1_si_totals', $q1_si_totals)
            ->with('q1_nai_totals', $q1_nai_totals)
            ->with('q1_i_grand_totals', $q2_i_grand_totals)
            ->with('i_trp_totals', $i_trp_totals)
            ->with('i_grand_total_raw_points', $i_grand_total_raw_points)
            ->with('vi_grand_total_raw_points', $vi_grand_total_raw_points)
            ->with('s_grand_total_raw_points', $s_grand_total_raw_points)
            ->with('misinai_grand_total_raw_points', $misinai_grand_total_raw_points)
            ->with('i_total_scores', $i_total_scores)
            ->with('vi_grand_total_score', $vi_grand_total_score) 
            ->with('i_grand_total_score', $i_grand_total_score) 
            ->with('misinai_grand_total_score', $misinai_grand_total_score)
            ->with('percentage_promoters', $percentage_promoters)
            ->with('jan_percentage_promoters', $jan_percentage_promoters)
            ->with('feb_percentage_promoters', $feb_percentage_promoters)
            ->with('mar_percentage_promoters', $mar_percentage_promoters)
            ->with('average_percentage_promoters', $average_percentage_promoters)
            ->with('jan_percentage_detractors', $jan_percentage_detractors)
            ->with('feb_percentage_detractors', $feb_percentage_detractors)
            ->with('mar_percentage_detractors', $mar_percentage_detractors) 
            ->with('average_percentage_detractors', $average_percentage_detractors)
            ->with('jan_net_promoter_score', $jan_net_promoter_score)
            ->with('feb_net_promoter_score', $feb_net_promoter_score)
            ->with('mar_net_promoter_score', $mar_net_promoter_score)
            ->with('ave_net_promoter_score', $ave_net_promoter_score)
            ->with('customer_satisfaction_rating', $customer_satisfaction_rating)
            ->with('csi', $customer_satisfaction_index)
            ->with('first_month_csi', $first_month_csi)
            ->with('second_month_csi', $second_month_csi)
            ->with('third_month_csi', $third_month_csi)
            ->with('total_comments', $total_comments)
            ->with('total_complaints', $total_complaints)
            ->with('comments', $comments);
   }

    public function generateCSIByUnitSecondQuarter($request, $region_id, $psto_id)
    {
        $sub_unit = $this->getSubUnit($request);
        $unit_pstos = $this->getUnitPSTOs($request);
        $sub_unit_pstos = $this->getSubUnitPSTOs($request);
        $sub_unit_types = $this->getSubUnitTypes($request);

        $date_range = null;
        $customer_recommendation_ratings = null;
        $respondents_list = null;
        $month_apr = [];
        $month_may = [];
        $month_jun = [];

        $service_id = $request->service['id'];
        $unit_id = $request->unit_id;
        $sub_unit_id = $request->selected_sub_unit;
        $client_type = $request->client_type; 
        $sub_unit_type = $request->sub_unit_type; 

       // search and check list of forms query  
       $customer_ids = $this->querySearchCSF( $region_id, $service_id, $unit_id ,$sub_unit_id , $psto_id, $client_type, $sub_unit_type );
            
        $startDate = Carbon::create($request->selected_year, 4, 1)->startOfDay();
        $endDate = Carbon::create($request->selected_year, 6, 31)->endOfDay();
        $date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                            ->whereBetween('created_at', [$startDate, $endDate])
                                            ->whereYear('created_at', $request->selected_year)->get(); 
        $month_apr = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                            ->whereMonth('created_at', 04)
                                            ->whereYear('created_at', $request->selected_year)->get(); 
        $month_may = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                            ->whereMonth('created_at', 05)
                                            ->whereYear('created_at', $request->selected_year)->get(); 
        $month_jun = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                            ->whereMonth('created_at', 06)
                                            ->whereYear('created_at', $request->selected_year)->get();

        $customer_recommendation_ratings = CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                                        ->whereBetween('created_at', [$startDate, $endDate])
                                                                        ->whereYear('created_at', $request->selected_year)->get();

        $apr_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereMonth('created_at',04)
                                                ->whereYear('created_at', $request->selected_year)->get();
        
        $may_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereMonth('created_at',05)
                                                ->whereYear('created_at', $request->selected_year)->get();
                                                    
        $jun_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereMonth('created_at',06)
                                                ->whereYear('created_at', $request->selected_year)->get();


        $respondents_list = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$startDate, $endDate])
                                                ->whereYear('created_at', $request->selected_year)->get();
        

        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();
        $grand_total_raw_points = 0;
        $vs_grand_total_score =0;
        $s_grand_total_score = 0;
        $ndvd_grand_total_score = 0;
        $grand_total_score =0;


        $vs_grand_total_raw_points = 0;
        $s_grand_total_raw_points = 0;
        $ndvd_grand_total_raw_points = 0;
        $lsr_grand_total = 0 ;
        $lsr_average = 0;

        //Importance total raw points  
        $vi_grand_total_raw_points = 0;
        $i_grand_total_raw_points = 0;
        $misinai_grand_total_raw_points = 0;
        //Importance grand total score
          
        $vi_grand_total_score=0;
        $i_grand_total_score =0;
        $misinai_grand_total_score = 0;

        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            //PART I

            // April total responses with its dimensions and rate score
            $apr_vs_total = $month_apr->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $apr_s_total = $month_apr->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $apr_n_total = $month_apr->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $apr_d_total = $month_apr->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $apr_vd_total = $month_apr->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $apr_grand_total =  $apr_vs_total + $apr_s_total + $apr_n_total + $apr_d_total + $apr_vd_total ; 

            //  May total responses with its dimensions and rate score
            $may_vs_total = $month_may->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $may_s_total = $month_may->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $may_n_total = $month_may->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $may_d_total = $month_may->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $may_vd_total = $month_may->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $may_grand_total =  $may_vs_total + $may_s_total + $may_n_total + $may_d_total + $may_vd_total ; 

            //  June total responses with its dimensions and rate score
            $jun_vs_total = $month_jun->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $jun_s_total = $month_jun->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $jun_n_total = $month_jun->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $jun_d_total = $month_jun->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $jun_vd_total = $month_jun->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $jun_grand_total =  $jun_vs_total + $jun_s_total + $jun_n_total + $jun_d_total + $jun_vd_total ; 

            // Quarter 1 Very Satisfied total with specific dimention or attribute
            $q2_vs_totals[$dimensionId] = [
                'apr_vs_total' => $apr_vs_total,
                'may_vs_total' => $may_vs_total,
                'jun_vs_total' => $jun_vs_total,
            ];

            $q2_s_totals[$dimensionId] = [
                'apr_s_total' => $apr_s_total,
                'may_s_total' => $may_s_total,
                'jun_s_total' => $jun_s_total,
            ];

            $q2_n_totals[$dimensionId] = [
                'apr_n_total' => $apr_n_total,
                'may_n_total' => $may_n_total,
                'jun_n_total' => $jun_n_total,
            ];

            $q2_d_totals[$dimensionId] = [
                'apr_d_total' => $apr_d_total,
                'may_d_total' => $may_d_total,
                'jun_d_total' => $jun_d_total,
            ];

            $q2_vd_totals[$dimensionId] = [
                'apr_vd_total' => $apr_vd_total,
                'may_vd_total' => $may_vd_total,
                'jun_vd_total' => $jun_vd_total,
            ];

            $q2_grand_totals[$dimensionId] = [
                'apr_grand_total' => $apr_grand_total,
                'may_grand_total' => $may_grand_total,
                'jun_grand_total' => $jun_grand_total,
            ];

            //Total Raw Points totals
            $vs_total_raw_points = $apr_vs_total + $may_vs_total + $jun_vs_total;
            $s_total_raw_points = $apr_s_total + $may_s_total + $jun_s_total;
            $n_total_raw_points =$apr_n_total + $may_n_total + $jun_n_total;
            $d_total_raw_points =$apr_d_total + $may_d_total + $jun_d_total;
            $vd_total_raw_points =$apr_vd_total + $may_vd_total + $jun_vd_total;
            $total_raw_points = $vs_total_raw_points + $s_total_raw_points + $n_total_raw_points +  $d_total_raw_points +  $vd_total_raw_points;           

            $vs_grand_total_raw_points += $vs_total_raw_points;
            $s_grand_total_raw_points +=  $s_total_raw_points;
            $ndvd_grand_total_raw_points +=  $n_total_raw_points + $d_total_raw_points + $vd_total_raw_points;
            $grand_total_raw_points+= $total_raw_points;

            $trp_totals[$dimensionId] = [
                'vs_total_raw_points' => $vs_total_raw_points,
                's_total_raw_points' => $s_total_raw_points,
                'n_total_raw_points' => $n_total_raw_points,
                'd_total_raw_points' => $d_total_raw_points,
                'vd_total_raw_points' => $vd_total_raw_points,
                'total_raw_points' => $total_raw_points,
            ];

            //Part 1 Quarter 1 total scores
            $x_vs_total = $vs_total_raw_points * 5; 
            $x_s_total = $s_total_raw_points * 4; 
            $x_n_total = $n_total_raw_points * 3; 
            $x_d_total = $d_total_raw_points * 3; 
            $x_vd_total = $vd_total_raw_points * 1; 
            $x_total_score =  $x_vs_total +  $x_s_total +  $x_n_total +  $x_d_total + $x_vd_total;
            
            $vs_grand_total_score += $x_vs_total ;
            $s_grand_total_score += $x_s_total ;
            $ndvd_grand_total_score += $x_n_total +  $x_d_total + $x_vd_total ;
            $grand_total_score += $x_total_score ;

            $p1_total_scores[$dimensionId] = [ 
                'x_vs_total' => $x_vs_total,
                'x_s_total' => $x_s_total,
                'x_n_total' => $x_n_total,
                'x_d_total' => $x_d_total,
                'x_vd_total' => $x_vd_total, 
                'x_total_score' => $x_total_score,
            ];

            // Likert Scale Rating = total score / grand total of total raw points
            if($total_raw_points != 0 ){
                $vs_lsr_total =   number_format(($x_vs_total  /  $total_raw_points),2);
                $s_lsr_total =    number_format(($x_s_total /  $total_raw_points),2);
                $n_lsr_total =   number_format(($x_n_total /  $total_raw_points),2);
                $d_lsr_total =   number_format(($x_d_total /  $total_raw_points),2);
                $vd_lsr_total =   number_format(($x_vd_total /  $total_raw_points),2);
                $lsr_total =  number_format(($vs_lsr_total +  $s_lsr_total  +  $n_lsr_total  +  $d_lsr_total  +  $vd_lsr_total),2);
                $lsr_grand_total +=  $lsr_total;
                $lsr_grand_total =number_format(($lsr_grand_total),2);
                $lsr_average =  number_format(($lsr_grand_total / $dimensionId), 2);
            }
            else{
                $vs_lsr_total =  0;
                $s_lsr_total =  0;
                $n_lsr_total =  0;
                $d_lsr_total = 0;
                $vd_lsr_total =  0;
                $lsr_total = 0;
                $lsr_grand_total +=  0;
                $lsr_average =  0;
            }

            $lsr_totals[$dimensionId] = [
                'vs_lsr_total' => $vs_lsr_total,
                's_lsr_total' => $s_lsr_total,
                'n_lsr_total' => $n_lsr_total,
                'd_lsr_total' => $d_lsr_total,
                'vd_lsr_total' => $vd_lsr_total,
                'lsr_total' => $lsr_total,
            ];
            
            // PART II
              // April total responses with its dimensions and rate score
              $apr_vi_total = $month_apr->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $apr_i_total = $month_apr->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $apr_mi_total = $month_apr->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $apr_si_total = $month_apr->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $apr_nai_total = $month_apr->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $apr_i_grand_total =  $apr_vi_total + $apr_i_total + $apr_mi_total + $apr_si_total + $apr_nai_total ; 
  
              //  MAy total responses with its dimensions and rate score
              $may_vi_total = $month_may->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $may_i_total = $month_may->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $may_mi_total = $month_may->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $may_si_total = $month_may->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $may_nai_total = $month_may->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $may_i_grand_total =  $apr_vi_total + $may_i_total + $may_mi_total + $may_si_total + $may_nai_total ; 
  
              //  June total responses with its dimensions and rate score
              $jun_vi_total = $month_jun->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $jun_i_total = $month_jun->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $jun_mi_total = $month_jun->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $jun_si_total = $month_jun->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $jun_nai_total = $month_jun->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $jun_i_grand_total =  $jun_vi_total + $jun_i_total + $jun_mi_total + $jun_si_total + $jun_nai_total ; 

                //Very Important total with specific dimention or attribute
                $q2_vi_totals[$dimensionId] = [
                    'apr_vi_total' => $apr_vi_total,
                    'may_vi_total' => $may_vi_total,
                    'jun_vi_total' => $jun_vi_total,
                ];
                //Important total with specific dimention or attribute
                $q2_i_totals[$dimensionId] = [
                    'apr_i_total' => $apr_i_total,
                    'may_i_total' => $may_i_total,
                    'jun_i_total' => $jun_i_total,
                ];
                //Moderately Important total with specific dimention or attribute
                $q2_mi_totals[$dimensionId] = [
                    'apr_mi_total' => $apr_mi_total,
                    'may_mi_total' => $may_mi_total,
                    'jun_mi_total' => $jun_mi_total,
                ];
                // Quarter 1 slightly Important total with specific dimention or attribute
                $q2_si_totals[$dimensionId] = [
                    'apr_si_total' => $apr_si_total,
                    'may_si_total' => $may_si_total,
                    'jun_si_total' => $jun_si_total,
                ];

                $q2_nai_totals[$dimensionId] = [
                    'apr_nai_total' => $apr_nai_total,
                    'may_nai_total' => $may_nai_total,
                    'jun_nai_total' => $jun_nai_total,
                ];

                $q2_i_grand_totals[$dimensionId] = [
                    'apr_i_grand_total' => $apr_i_grand_total,
                    'may_i_grand_total' => $may_i_grand_total,
                    'jun_i_grand_total' => $jun_i_grand_total,
                ];


                
            //Importance Total Raw Points totals
            $vi_total_raw_points = $apr_vi_total + $may_vi_total + $jun_vi_total;
            $i_total_raw_points = $apr_s_total + $may_i_total + $jun_i_total;
            $mi_total_raw_points = $apr_mi_total + $may_mi_total + $jun_mi_total;
            $si_total_raw_points = $apr_si_total + $may_si_total + $jun_si_total;
            $nai_total_raw_points = $apr_nai_total + $may_nai_total + $jun_nai_total;
            $i_total_raw_points = $vi_total_raw_points + $i_total_raw_points + $mi_total_raw_points +  $si_total_raw_points +  $nai_total_raw_points;           

            $vi_grand_total_raw_points += $vi_total_raw_points;
            $s_grand_total_raw_points +=  $i_total_raw_points;
            $misinai_grand_total_raw_points +=  $mi_total_raw_points + $si_total_raw_points + $nai_total_raw_points;
            $i_grand_total_raw_points+= $total_raw_points;

            $i_trp_totals[$dimensionId] = [
                'vi_total_raw_points' => $vi_total_raw_points,
                'i_total_raw_points' => $i_total_raw_points,
                'mi_total_raw_points' => $mi_total_raw_points,
                'si_total_raw_points' => $si_total_raw_points,
                'nai_total_raw_points' => $nai_total_raw_points,
                'i_total_raw_points' => $i_total_raw_points,
            ];

            //Part 1 Quarter 1 total scores
            $x_vi_total = $vi_total_raw_points * 5; 
            $x_i_total = $i_total_raw_points * 4; 
            $x_mi_total = $mi_total_raw_points * 3; 
            $x_si_total = $si_total_raw_points * 3; 
            $x_nai_total = $nai_total_raw_points * 1; 
            $x_i_total_score =  $x_vi_total +  $x_i_total +  $x_mi_total +  $x_si_total + $x_nai_total;
            
            $vi_grand_total_score += $x_vi_total ;
            $i_grand_total_score += $x_si_total ;
            $misinai_grand_total_score += $x_mi_total +  $x_si_total + $x_nai_total ;

            $i_total_scores[$dimensionId] = [ 
                'x_vi_total' => $x_vi_total,
                'x_i_total' => $x_i_total,
                'x_mi_total' => $x_mi_total,
                'x_si_total' => $x_si_total,
                'x_nai_total' => $x_nai_total, 
                'x_i_total_score' => $x_i_total_score,
            ];

        
        }

        // Total No. of Very Satisfied (VS) Responses of First Quarte
        // -- April
        $apr_total_vs_respondents = $month_apr->where('rate_score',5)->groupBy('customer_id')->count();
        // -- May
        $may_total_vs_respondents = $month_may->where('rate_score',5)->groupBy('customer_id')->count();
        // -- June
        $jun_total_vs_respondents = $month_jun->where('rate_score',5)->groupBy('customer_id')->count();

        // Total No. of Satisfied (S) Responses
        // -- April
        $apr_total_s_respondents = $month_apr->where('rate_score',4)->groupBy('customer_id')->count();
        // -- May
        $may_total_s_respondents = $month_may->where('rate_score',4)->groupBy('customer_id')->count();
        // -- June
        $jun_total_s_respondents = $month_jun->where('rate_score',4)->groupBy('customer_id')->count();

        // Total No. of Other (N, D, VD) Responses
        // -- April
        $apr_total_ndvd_respondents = $month_apr->where('rate_score','<', 4)->groupBy('customer_id')->count();
        // -- May
        $may_total_ndvd_respondents = $month_may->where('rate_score','<', 4)->groupBy('customer_id')->count();
        // -- June
        $jun_total_ndvd_respondents = $month_jun->where('rate_score','<', 4)->groupBy('customer_id')->count();
          
        // Total No. of All Responses
        // -- April
        $apr_total_respondents = $month_apr->groupBy('customer_id')->count();
        // -- May
        $may_total_respondents = $month_may->groupBy('customer_id')->count();
        // -- June
        $jun_total_respondents = $month_jun->groupBy('customer_id')->count();

        //Total respondents /Customers
        $total_respondents = $date_range->groupBy('customer_id')->count();

        // total number of respondents/customer who rated VS/S
        $total_vss_respondents = $date_range->where('rate_score', '>','3')->groupBy('customer_id')->count();
        // total number of respondents/customer who rated VS
        $total_vs_respondents = $date_range->where('rate_score', '=','5')->groupBy('customer_id')->count();
        // total number of respondents/customer who rated S
        $total_s_respondents = $date_range->where('rate_score', '=','4')->groupBy('customer_id')->count();
    
        // Frst quarter total number of promoters or respondents who rated 9-10 in recommendation rating
        $total_promoters = $customer_recommendation_ratings->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        // April
        $apr_total_promoters = $apr_crr->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        // May
        $may_total_promoters = $may_crr->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        // June
        $jun_total_promoters = $jun_crr->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        
        // total number of detractors or respondents who rated 0-6 in recommendation rating
        $total_detractors = $customer_recommendation_ratings->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
         // April
         $apr_total_detractors = $apr_crr->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
         // May
         $may_total_detractors = $may_crr->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
         // June
         $jun_total_detractors = $jun_crr->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
  
          //Percentage of Respondents/Customers who rated VS/S = total no. of respondents / total no. respondets who rated vs/s * 100
        $percentage_vss_respondents  = 0;
        if($total_respondents != 0){
            $percentage_vss_respondents  = ($total_respondents/$total_vss_respondents) * 100;
        }
        $percentage_vss_respondents = number_format( $percentage_vss_respondents , 2);

        $customer_satisfaction_rating = 0;
        if($total_vss_respondents != 0){
            $customer_satisfaction_rating = ($total_vss_respondents/$total_vss_respondents) * 100;
        }
        $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);

        //Percentage of Promoters = number of promoters / total respondents
        $percentage_promoters = 0;
        // january % promoters
        $apr_percentage_promoters = 0.00;
        $may_percentage_promoters = 0.00;
        $jun_percentage_promoters = 0.00;

        // Percentage of promoter
        $apr_percentage_detractors = 0.00;
        $may_percentage_detractors = 0.00;
        $jun_percentage_detractors = 0.00;

        //nps
        $apr_net_promoter_score =  0.00;
        $may_net_promoter_score = 0.00;
        $jun_net_promoter_score =  0.00;

        //average
        $ave_net_promoter_score = 0.00;
        $average_percentage_promoters =  0.00;
        $average_percentage_detractors =  0.00;

        if($total_respondents != 0){
            $percentage_promoters = number_format((($total_promoters / $total_respondents) * 100), 2);
            $apr_percentage_promoters = number_format((($apr_total_promoters / $total_respondents) * 100), 2);
            $may_percentage_promoters = number_format((($may_total_promoters / $total_respondents) * 100), 2);
            $jun_percentage_promoters = number_format((($jun_total_promoters / $total_respondents) * 100), 2);
        
            //Percentage of Promoters = number of promoters / total respondents
            $percentage_detractors = number_format((($total_detractors / $total_respondents) * 100),2);
            $apr_percentage_detractors = number_format((($apr_total_detractors / $total_respondents) * 100),2);
            $may_percentage_detractors = number_format((($may_total_detractors / $total_respondents) * 100),2);
            $jun_percentage_detractors = number_format((($jun_total_detractors / $total_respondents) * 100),2);
            

            // Net Promotion Scores(NPS) = Percentage of Promoters−Percentage of Detractors
            $apr_net_promoter_score =  number_format(($apr_percentage_promoters - $apr_percentage_detractors),2);
            $may_net_promoter_score =  number_format(($may_percentage_promoters - $may_percentage_detractors),2);
            $jun_net_promoter_score =  number_format(($jun_percentage_promoters - $jun_percentage_detractors),2);

            $ave_net_promoter_score =  number_format((($apr_net_promoter_score + $may_net_promoter_score + $jun_net_promoter_score)/ 3),2);
            $average_percentage_promoters =  number_format((($apr_percentage_promoters + $may_percentage_promoters + $jun_percentage_promoters)/ 3),2);
            $average_percentage_detractors =  number_format((($apr_percentage_detractors + $may_percentage_detractors + $jun_percentage_detractors)/ 3),2);


            // CSAT = ((Total No. of Very Satisfied (VS) Responses + Total No. of Satisfied (S) Responses) / grand total respondents) * 100
            $customer_satisfaction_rating = 0;
            if($total_vss_respondents != 0){
                $customer_satisfaction_rating = (($total_vss_respondents / $total_respondents)) * 100;
            }
            $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);
        }

        // get Monthly CSI
        $first_month_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 4);
        $second_month_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 5);
        $third_month_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 6);
   
        $customer_satisfaction_index = number_format((($first_month_csi + $second_month_csi +  $third_month_csi)/3), 2);

        //comments and  complaints
        $comment_list = CustomerComment::whereIn('customer_id', $customer_ids)
                                        ->whereBetween('created_at', [$startDate, $endDate])
                                        ->whereYear('created_at', $request->selected_year)->get();

        $comments = $comment_list->where('comment','!=','')->pluck('comment'); 

        $total_comments = $comment_list->where('comment','!=','')->count();
        $total_complaints = $comment_list->where('is_complaint',1)->count();


        //Respondents list
        $data = CARResource::collection($respondents_list);

        //send response to front end
        return Inertia::render('CSI/Index')
            ->with('sub_unit', $sub_unit)
            ->with('unit_pstos', $unit_pstos)
            ->with('sub_unit_pstos', $sub_unit_pstos)
            ->with('sub_unit_types', $sub_unit_types)
            ->with('dimensions', $dimensions)
            ->with('service', $request->service)
            ->with('unit', $request->unit)
            ->with('respondents_list',$data)
            ->with('q2_vs_totals', $q2_vs_totals)
            ->with('q2_s_totals', $q2_s_totals)
            ->with('q2_n_totals', $q2_n_totals)
            ->with('q2_d_totals', $q2_d_totals)
            ->with('q2_vd_totals', $q2_vd_totals)
            ->with('q2_grand_totals', $q2_grand_totals)
            ->with('trp_totals', $trp_totals)
            ->with('grand_total_raw_points', $grand_total_raw_points)
            ->with('vs_grand_total_raw_points', $vs_grand_total_raw_points)
            ->with('s_grand_total_raw_points', $s_grand_total_raw_points)
            ->with('ndvd_grand_total_raw_points', $ndvd_grand_total_raw_points)
            ->with('p1_total_scores', $p1_total_scores)
            ->with('vs_grand_total_score', $vs_grand_total_score) 
            ->with('s_grand_total_score', $s_grand_total_score) 
            ->with('ndvd_grand_total_score', $ndvd_grand_total_score) 
            ->with('grand_total_score', $grand_total_score) 
            ->with('lsr_totals', $lsr_totals)
            ->with('lsr_grand_total', $lsr_grand_total)
            ->with('lsr_average', $lsr_average ) 
            ->with('apr_total_vs_respondents', $apr_total_vs_respondents)
            ->with('may_total_vs_respondents', $may_total_vs_respondents)
            ->with('jun_total_vs_respondents', $jun_total_vs_respondents)
            ->with('apr_total_s_respondents', $apr_total_s_respondents)
            ->with('may_total_s_respondents', $may_total_s_respondents)
            ->with('jun_total_s_respondents', $jun_total_s_respondents)
            ->with('apr_total_ndvd_respondents', $apr_total_ndvd_respondents)
            ->with('may_total_ndvd_respondents', $may_total_ndvd_respondents)
            ->with('jun_total_ndvd_respondents', $jun_total_ndvd_respondents)
            ->with('apr_total_respondents', $apr_total_respondents)
            ->with('may_total_respondents', $may_total_respondents)
            ->with('jun_total_respondents', $jun_total_respondents)
            ->with('total_respondents', $total_respondents)
            ->with('total_vss_respondents', $total_vss_respondents)
            ->with('percentage_vss_respondents', $percentage_vss_respondents)
            ->with('total_promoters', $total_promoters)
            ->with('total_detractors', $total_detractors)
            ->with('q2_vi_totals', $q2_vi_totals)
            ->with('q2_i_totals', $q2_i_totals)
            ->with('q2_mi_totals', $q2_mi_totals)
            ->with('q2_si_totals', $q2_si_totals)
            ->with('q2_nai_totals', $q2_nai_totals)
            ->with('q2_i_grand_totals', $q2_i_grand_totals)
            ->with('i_trp_totals', $i_trp_totals)
            ->with('i_grand_total_raw_points', $i_grand_total_raw_points)
            ->with('vi_grand_total_raw_points', $vi_grand_total_raw_points)
            ->with('s_grand_total_raw_points', $s_grand_total_raw_points)
            ->with('misinai_grand_total_raw_points', $misinai_grand_total_raw_points)
            ->with('i_total_scores', $i_total_scores)
            ->with('vi_grand_total_score', $vi_grand_total_score) 
            ->with('i_grand_total_score', $i_grand_total_score) 
            ->with('misinai_grand_total_score', $misinai_grand_total_score)
            ->with('percentage_promoters', $percentage_promoters)
            ->with('apr_percentage_promoters', $apr_percentage_promoters)
            ->with('may_percentage_promoters', $may_percentage_promoters)
            ->with('jun_percentage_promoters', $jun_percentage_promoters)
            ->with('average_percentage_promoters', $average_percentage_promoters)
            ->with('apr_percentage_detractors', $apr_percentage_detractors)
            ->with('may_percentage_detractors', $may_percentage_detractors)
            ->with('jun_percentage_detractors', $jun_percentage_detractors) 
            ->with('average_percentage_detractors', $average_percentage_detractors)
            ->with('apr_net_promoter_score', $apr_net_promoter_score)
            ->with('may_net_promoter_score', $may_net_promoter_score)
            ->with('jun_net_promoter_score', $jun_net_promoter_score)
            ->with('ave_net_promoter_score', $ave_net_promoter_score)
            ->with('customer_satisfaction_rating', $customer_satisfaction_rating)
            ->with('csi', $customer_satisfaction_index)
            ->with('first_month_csi', $first_month_csi)
            ->with('second_month_csi', $second_month_csi)
            ->with('third_month_csi', $third_month_csi)
            ->with('total_comments', $total_comments)
            ->with('total_complaints', $total_complaints)
            ->with('comments', $comments);

    }

    public function generateCSIByUnitThirdQuarter($request, $region_id, $psto_id)
    {
        $sub_unit = $this->getSubUnit($request);
        $unit_pstos = $this->getUnitPSTOs($request);
        $sub_unit_pstos = $this->getSubUnitPSTOs($request);
        $sub_unit_types = $this->getSubUnitTypes($request);

        $date_range = null;
        $customer_recommendation_ratings = null;
        $respondents_list = null;
        $month_jul = [];
        $month_aug = [];
        $month_sep = [];
            
        $service_id = $request->service['id'];
        $unit_id = $request->unit_id;
        $sub_unit_id = $request->selected_sub_unit;
        $client_type = $request->client_type; 
        $sub_unit_type = $request->sub_unit_type; 

       // search and check list of forms query  
       $customer_ids = $this->querySearchCSF( $region_id, $service_id, $unit_id ,$sub_unit_id , $psto_id, $client_type, $sub_unit_type );

        // Retrieve records for the specified quarter and year
        $startDate = Carbon::create($request->selected_year, 7, 1)->startOfDay();
        $endDate = Carbon::create($request->selected_year, 9, 31)->endOfDay();
        $date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                            ->whereBetween('created_at', [$startDate, $endDate])
                                            ->whereYear('created_at', $request->selected_year)->get(); 
        $month_jul = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                            ->whereMonth('created_at', 7)
                                             ->whereYear('created_at', $request->selected_year)->get(); 
        $month_aug = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                            ->whereMonth('created_at', 8)
                                            ->whereYear('created_at', $request->selected_year)->get(); 
        $month_sep = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                            ->whereMonth('created_at', 9)
                                            ->whereYear('created_at', $request->selected_year)->get();

        $customer_recommendation_ratings = CustomerRecommendationRating::whereBetween('created_at', [$startDate, $endDate])
                                                                        ->whereYear('created_at', $request->selected_year)->get();

        $jul_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereMonth('created_at', 7)
                                                ->whereYear('created_at', $request->selected_year)->get();
        
        $aug_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereMonth('created_at',8)
                                                ->whereYear('created_at', $request->selected_year)->get();
                                                    
        $sep_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereMonth('created_at',9)
                                                ->whereYear('created_at', $request->selected_year)->get();


        $respondents_list = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                                    ->whereBetween('created_at', [$startDate, $endDate])
                                                    ->whereYear('created_at', $request->selected_year)->get();
            

        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();
        $grand_total_raw_points = 0;
        $vs_grand_total_score =0;
        $s_grand_total_score = 0;
        $ndvd_grand_total_score = 0;
        $grand_total_score =0;


        $vs_grand_total_raw_points = 0;
        $s_grand_total_raw_points = 0;
        $ndvd_grand_total_raw_points = 0;
        $lsr_grand_total = 0 ;
        $lsr_average = 0;

        //Importance total raw points  
        $vi_grand_total_raw_points = 0;
        $i_grand_total_raw_points = 0;
        $misinai_grand_total_raw_points = 0;
        //Importance grand total score
          
        $vi_grand_total_score=0;
        $i_grand_total_score =0;
        $misinai_grand_total_score = 0;

        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            //PART I

            // July total responses with its dimensions and rate score
            $jul_vs_total = $month_jul->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $jul_s_total = $month_jul->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $jul_n_total = $month_jul->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $jul_d_total = $month_jul->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $jul_vd_total = $month_jul->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $jul_grand_total =  $jul_vs_total + $jul_s_total + $jul_n_total + $jul_d_total + $jul_vd_total ; 

            //  August total responses with its dimensions and rate score
            $aug_vs_total = $month_aug->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $aug_s_total = $month_aug->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $aug_n_total = $month_aug->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $aug_d_total = $month_aug->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $aug_vd_total = $month_aug->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $aug_grand_total =  $aug_vs_total + $aug_s_total + $aug_n_total + $aug_d_total + $aug_vd_total ; 

            //  September total responses with its dimensions and rate score
            $sep_vs_total = $month_sep->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $sep_s_total = $month_sep->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $sep_n_total = $month_sep->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $sep_d_total = $month_sep->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $sep_vd_total = $month_sep->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $sep_grand_total =  $sep_vs_total + $sep_s_total + $sep_n_total + $sep_d_total + $sep_vd_total ; 

            // Quarter 1 Very Satisfied total with specific dimention or attribute
            $q3_vs_totals[$dimensionId] = [
                'jul_vs_total' => $jul_vs_total,
                'aug_vs_total' => $aug_vs_total,
                'sep_vs_total' => $sep_vs_total,
            ];

            $q3_s_totals[$dimensionId] = [
                'jul_s_total' => $jul_s_total,
                'aug_s_total' => $aug_s_total,
                'sep_s_total' => $sep_s_total,
            ];

            $q3_n_totals[$dimensionId] = [
                'jul_n_total' => $jul_n_total,
                'aug_n_total' => $aug_n_total,
                'sep_n_total' => $sep_n_total,
            ];

            $q3_d_totals[$dimensionId] = [
                'jul_d_total' => $jul_d_total,
                'aug_d_total' => $aug_d_total,
                'sep_d_total' => $sep_d_total,
            ];

            $q3_vd_totals[$dimensionId] = [
                'jul_vd_total' => $jul_vd_total,
                'aug_vd_total' => $aug_vd_total,
                'sep_vd_total' => $sep_vd_total,
            ];

            $q3_grand_totals[$dimensionId] = [
                'jul_grand_total' => $jul_grand_total,
                'aug_grand_total' => $aug_grand_total,
                'sep_grand_total' => $sep_grand_total,
            ];

            //Total Raw Points totals
            $vs_total_raw_points = $jul_vs_total + $aug_vs_total + $sep_vs_total;
            $s_total_raw_points = $jul_s_total + $aug_s_total + $sep_s_total;
            $n_total_raw_points = $jul_n_total + $aug_n_total + $sep_n_total;
            $d_total_raw_points =$jul_d_total + $aug_d_total + $sep_d_total;
            $vd_total_raw_points =$jul_vd_total + $aug_vd_total + $sep_vd_total;
            $total_raw_points = $vs_total_raw_points + $s_total_raw_points + $n_total_raw_points +  $d_total_raw_points +  $vd_total_raw_points;           

            $vs_grand_total_raw_points += $vs_total_raw_points;
            $s_grand_total_raw_points +=  $s_total_raw_points;
            $ndvd_grand_total_raw_points +=  $n_total_raw_points + $d_total_raw_points + $vd_total_raw_points;
            $grand_total_raw_points+= $total_raw_points;

            $trp_totals[$dimensionId] = [
                'vs_total_raw_points' => $vs_total_raw_points,
                's_total_raw_points' => $s_total_raw_points,
                'n_total_raw_points' => $n_total_raw_points,
                'd_total_raw_points' => $d_total_raw_points,
                'vd_total_raw_points' => $vd_total_raw_points,
                'total_raw_points' => $total_raw_points,
            ];

            //Part 1 Quarter 1 total scores
            $x_vs_total = $vs_total_raw_points * 5; 
            $x_s_total = $s_total_raw_points * 4; 
            $x_n_total = $n_total_raw_points * 3; 
            $x_d_total = $d_total_raw_points * 3; 
            $x_vd_total = $vd_total_raw_points * 1; 
            $x_total_score =  $x_vs_total +  $x_s_total +  $x_n_total +  $x_d_total + $x_vd_total;
            
            $vs_grand_total_score += $x_vs_total ;
            $s_grand_total_score += $x_s_total ;
            $ndvd_grand_total_score += $x_n_total +  $x_d_total + $x_vd_total ;
            $grand_total_score += $x_total_score ;

            $p1_total_scores[$dimensionId] = [ 
                'x_vs_total' => $x_vs_total,
                'x_s_total' => $x_s_total,
                'x_n_total' => $x_n_total,
                'x_d_total' => $x_d_total,
                'x_vd_total' => $x_vd_total, 
                'x_total_score' => $x_total_score,
            ];

            // Likert Scale Rating = total score / grand total of total raw points
            if($total_raw_points != 0 ){
                $vs_lsr_total =   number_format(($x_vs_total  /  $total_raw_points),2);
                $s_lsr_total =    number_format(($x_s_total /  $total_raw_points),2);
                $n_lsr_total =   number_format(($x_n_total /  $total_raw_points),2);
                $d_lsr_total =   number_format(($x_d_total /  $total_raw_points),2);
                $vd_lsr_total =   number_format(($x_vd_total /  $total_raw_points),2);
                $lsr_total =  number_format(($vs_lsr_total +  $s_lsr_total  +  $n_lsr_total  +  $d_lsr_total  +  $vd_lsr_total),2);
                $lsr_grand_total +=  $lsr_total;
                $lsr_grand_total =number_format(($lsr_grand_total),2);
                $lsr_average =  number_format(($lsr_grand_total / $dimensionId), 2);
            }
            else{
                $vs_lsr_total =  0;
                $s_lsr_total =  0;
                $n_lsr_total =  0;
                $d_lsr_total = 0;
                $vd_lsr_total =  0;
                $lsr_total = 0;
                $lsr_grand_total +=  0;
                $lsr_average =  0;
            }

            $lsr_totals[$dimensionId] = [
                'vs_lsr_total' => $vs_lsr_total,
                's_lsr_total' => $s_lsr_total,
                'n_lsr_total' => $n_lsr_total,
                'd_lsr_total' => $d_lsr_total,
                'vd_lsr_total' => $vd_lsr_total,
                'lsr_total' => $lsr_total,
            ];
            
            // PART II
              // July total responses with its dimensions and rate score
              $jul_vi_total = $month_jul->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $jul_i_total = $month_jul->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $jul_mi_total = $month_jul->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $jul_si_total = $month_jul->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $jul_nai_total = $month_jul->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $jul_i_grand_total =  $jul_vi_total + $jul_i_total + $jul_mi_total + $jul_si_total + $jul_nai_total ; 
  
              //  August total responses with its dimensions and rate score
              $aug_vi_total = $month_aug->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $aug_i_total = $month_aug->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $aug_mi_total = $month_aug->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $aug_si_total = $month_aug->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $aug_nai_total = $month_aug->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $aug_i_grand_total =  $aug_vi_total + $aug_i_total + $aug_mi_total + $aug_si_total + $aug_nai_total ; 
  
              //  September total responses with its dimensions and rate score
              $sep_vi_total = $month_sep->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $sep_i_total = $month_sep->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $sep_mi_total = $month_sep->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $sep_si_total = $month_sep->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $sep_nai_total = $month_sep->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $sep_i_grand_total =  $sep_vi_total + $sep_i_total + $sep_mi_total + $sep_si_total + $sep_nai_total ; 

                // Very Important total with specific dimention or attribute
                $q3_vi_totals[$dimensionId] = [
                    'jul_vi_total' => $jul_vi_total,
                    'aug_vi_total' => $aug_vi_total,
                    'sep_vi_total' => $sep_vi_total,
                ];
                //Important total with specific dimention or attribute
                $q3_i_totals[$dimensionId] = [
                    'jul_i_total' => $jul_i_total,
                    'aug_i_total' => $aug_i_total,
                    'sep_i_total' => $sep_i_total,
                ];
                // Moderately Important total with specific dimention or attribute
                $q3_mi_totals[$dimensionId] = [
                    'jul_mi_total' => $jul_mi_total,
                    'aug_mi_total' => $aug_mi_total,
                    'sep_mi_total' => $sep_mi_total,
                ];
                // slightly Important total with specific dimention or attribute
                $q3_si_totals[$dimensionId] = [
                    'jul_si_total' => $jul_si_total,
                    'aug_si_total' => $aug_si_total,
                    'sep_si_total' => $sep_si_total,
                ];

                $q3_nai_totals[$dimensionId] = [
                    'jul_nai_total' => $jul_nai_total,
                    'aug_nai_total' => $aug_nai_total,
                    'sep_nai_total' => $sep_nai_total,
                ];

                $q3_i_grand_totals[$dimensionId] = [
                    'jul_i_grand_total' => $jul_i_grand_total,
                    'aug_i_grand_total' => $aug_i_grand_total,
                    'sep_i_grand_total' => $sep_i_grand_total,
                ];

                
            //Importance Total Raw Points totals
            $vi_total_raw_points = $jul_vi_total + $aug_vi_total + $sep_vi_total;
            $i_total_raw_points = $jul_i_total + $aug_i_total + $sep_i_total;
            $mi_total_raw_points = $jul_mi_total + $aug_mi_total + $sep_mi_total;
            $si_total_raw_points = $jul_si_total + $aug_si_total + $sep_si_total;
            $nai_total_raw_points = $jul_nai_total + $aug_nai_total + $sep_nai_total;
            $i_total_raw_points = $vi_total_raw_points + $i_total_raw_points + $mi_total_raw_points +  $si_total_raw_points +  $nai_total_raw_points;           

            $vi_grand_total_raw_points += $vi_total_raw_points;
            $s_grand_total_raw_points +=  $i_total_raw_points;
            $misinai_grand_total_raw_points +=  $mi_total_raw_points + $si_total_raw_points + $nai_total_raw_points;
            $i_grand_total_raw_points+= $total_raw_points;

            $i_trp_totals[$dimensionId] = [
                'vi_total_raw_points' => $vi_total_raw_points,
                'i_total_raw_points' => $i_total_raw_points,
                'mi_total_raw_points' => $mi_total_raw_points,
                'si_total_raw_points' => $si_total_raw_points,
                'nai_total_raw_points' => $nai_total_raw_points,
                'i_total_raw_points' => $i_total_raw_points,
            ];

            //Part 1 Quarter 1 total scores
            $x_vi_total = $vi_total_raw_points * 5; 
            $x_i_total = $i_total_raw_points * 4; 
            $x_mi_total = $mi_total_raw_points * 3; 
            $x_si_total = $si_total_raw_points * 3; 
            $x_nai_total = $nai_total_raw_points * 1; 
            $x_i_total_score =  $x_vi_total +  $x_i_total +  $x_mi_total +  $x_si_total + $x_nai_total;
            
            $vi_grand_total_score += $x_vi_total ;
            $i_grand_total_score += $x_si_total ;
            $misinai_grand_total_score += $x_mi_total +  $x_si_total + $x_nai_total ;

            $i_total_scores[$dimensionId] = [ 
                'x_vi_total' => $x_vi_total,
                'x_i_total' => $x_i_total,
                'x_mi_total' => $x_mi_total,
                'x_si_total' => $x_si_total,
                'x_nai_total' => $x_nai_total, 
                'x_i_total_score' => $x_i_total_score,
            ];

        
        }

        // Total No. of Very Satisfied (VS) Responses of First Quarte
        // -- July
        $jul_total_vs_respondents = $month_jul->where('rate_score',5)->groupBy('customer_id')->count();
        // -- August
        $aug_total_vs_respondents = $month_aug->where('rate_score',5)->groupBy('customer_id')->count();
        // -- September
        $sep_total_vs_respondents = $month_sep->where('rate_score',5)->groupBy('customer_id')->count();

        // Total No. of Satisfied (S) Responses
       // -- July
       $jul_total_s_respondents = $month_jul->where('rate_score',4)->groupBy('customer_id')->count();
       // -- August
       $aug_total_s_respondents = $month_aug->where('rate_score',4)->groupBy('customer_id')->count();
       // -- September
       $sep_total_s_respondents = $month_sep->where('rate_score',4)->groupBy('customer_id')->count();

        // Total No. of Other (N, D, VD) Responses
        // -- July
        $jul_total_ndvd_respondents = $month_jul->where('rate_score','<', 4)->groupBy('customer_id')->count();
        // -- August
        $aug_total_ndvd_respondents = $month_aug->where('rate_score','<', 4)->groupBy('customer_id')->count();
        // -- September
        $sep_total_ndvd_respondents = $month_sep->where('rate_score','<', 4)->groupBy('customer_id')->count();
          
        // Total No. of All Responses
        // -- July
        $jul_total_respondents = $month_jul->groupBy('customer_id')->count();
        // -- August
        $aug_total_respondents = $month_aug->groupBy('customer_id')->count();
        // -- September
        $sep_total_respondents = $month_sep->groupBy('customer_id')->count();

        //Total respondents /Customers
        $total_respondents = $date_range->groupBy('customer_id')->count();

        // total number of respondents/customer who rated VS/S
        $total_vss_respondents = $date_range->where('rate_score', '>','3')->groupBy('customer_id')->count();
        // total number of respondents/customer who rated VS
        $total_vs_respondents = $date_range->where('rate_score', '=','5')->groupBy('customer_id')->count();
        // total number of respondents/customer who rated S
        $total_s_respondents = $date_range->where('rate_score', '=','4')->groupBy('customer_id')->count();
    
        // Frst quarter total number of promoters or respondents who rated 9-10 in recommendation rating
        $total_promoters = $customer_recommendation_ratings->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        // July
        $jul_total_promoters = $jul_crr->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        // August
        $aug_total_promoters = $aug_crr->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        // September
        $sep_total_promoters = $sep_crr->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        
        // total number of detractors or respondents who rated 0-6 in recommendation rating
        $total_detractors = $customer_recommendation_ratings->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
         // July
         $jul_total_detractors = $jul_crr->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
         // August
         $aug_total_detractors = $aug_crr->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
         // September
         $sep_total_detractors = $sep_crr->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
  
          //Percentage of Respondents/Customers who rated VS/S = total no. of respondents / total no. respondets who rated vs/s * 100
        $percentage_vss_respondents  = 0;
        if($total_respondents != 0){
            $percentage_vss_respondents  = ($total_respondents/$total_vss_respondents) * 100;
        }
        $percentage_vss_respondents = number_format( $percentage_vss_respondents , 2);

        $customer_satisfaction_rating = 0;
        if($total_vss_respondents != 0){
            $customer_satisfaction_rating = ($total_vss_respondents/$total_vss_respondents) * 100;
        }
        $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);

        //Percentage of Promoters = number of promoters / total respondents
        $percentage_promoters = 0;
        //  Percentage promoters
        $jul_percentage_promoters = 0.00;
        $aug_percentage_promoters = 0.00;
        $sep_percentage_promoters = 0.00;

        // Percentage of promoter
        $jul_percentage_detractors = 0.00;
        $aug_percentage_detractors = 0.00;
        $sep_percentage_detractors = 0.00;

        //nps
        $jul_net_promoter_score =  0.00;
        $aug_net_promoter_score = 0.00;
        $sep_net_promoter_score =  0.00;

        //average
        $ave_net_promoter_score = 0.00;
        $average_percentage_promoters =  0.00;
        $average_percentage_detractors =  0.00;

        if($total_respondents != 0){
            $percentage_promoters = number_format((($total_promoters / $total_respondents) * 100), 2);
            $jul_percentage_promoters = number_format((($jul_total_promoters / $total_respondents) * 100), 2);
            $aug_percentage_promoters = number_format((($aug_total_promoters / $total_respondents) * 100), 2);
            $sep_percentage_promoters = number_format((($sep_total_promoters / $total_respondents) * 100), 2);
        
            //Percentage of Promoters = number of promoters / total respondents
            $percentage_detractors = number_format((($total_detractors / $total_respondents) * 100),2);
            $jul_percentage_detractors = number_format((($jul_total_detractors / $total_respondents) * 100),2);
            $aug_percentage_detractors = number_format((($aug_total_detractors / $total_respondents) * 100),2);
            $sep_percentage_detractors = number_format((($sep_total_detractors / $total_respondents) * 100),2);
            

            // Net Promotion Scores(NPS) = Percentage of Promoters−Percentage of Detractors
            $jul_net_promoter_score =  number_format(($jul_percentage_promoters - $jul_percentage_detractors),2);
            $aug_net_promoter_score =  number_format(($aug_percentage_promoters - $aug_percentage_detractors),2);
            $sep_net_promoter_score =  number_format(($sep_percentage_promoters - $sep_percentage_detractors),2);

            $ave_net_promoter_score =  number_format((($jul_net_promoter_score + $aug_net_promoter_score + $sep_net_promoter_score)/ 3),2);
            $average_percentage_promoters =  number_format((($jul_percentage_promoters + $aug_percentage_promoters + $sep_percentage_promoters)/ 3),2);
            $average_percentage_detractors =  number_format((($jul_percentage_detractors + $aug_percentage_detractors + $sep_percentage_detractors)/ 3),2);


            // CSAT = ((Total No. of Very Satisfied (VS) Responses + Total No. of Satisfied (S) Responses) / grand total respondents) * 100
            $customer_satisfaction_rating = 0;
            if($total_vss_respondents != 0){
                $customer_satisfaction_rating = (($total_vss_respondents / $total_respondents)) * 100;
            }
            $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);
        }

        // get Monthly CSI
        $first_month_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 7);
        $second_month_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 8);
        $third_month_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 9);

        $customer_satisfaction_index = number_format((($first_month_csi + $second_month_csi +  $third_month_csi)/3), 2);

         //comments and  complaints
         $comment_list = CustomerComment::whereIn('customer_id', $customer_ids)
                                        ->whereBetween('created_at', [$startDate, $endDate])
                                        ->whereYear('created_at', $request->selected_year)->get();

        $comments = $comment_list->where('comment','!=','')->pluck('comment'); 

        $total_comments = $comment_list->where('comment','!=','')->count();
        $total_complaints = $comment_list->where('is_complaint',1)->count();

        //Respondents list
        $data = CARResource::collection($respondents_list);

        //send response to front end
        return Inertia::render('CSI/Index')
            ->with('sub_unit', $sub_unit)
            ->with('unit_pstos', $unit_pstos)
            ->with('sub_unit_pstos', $sub_unit_pstos)
            ->with('sub_unit_types', $sub_unit_types)
            ->with('dimensions', $dimensions)
            ->with('service', $request->service)
            ->with('unit', $request->unit)
            ->with('respondents_list',$data)
            ->with('q3_vs_totals', $q3_vs_totals)
            ->with('q3_s_totals', $q3_s_totals)
            ->with('q3_n_totals', $q3_n_totals)
            ->with('q3_d_totals', $q3_d_totals)
            ->with('q3_vd_totals', $q3_vd_totals)
            ->with('q3_grand_totals', $q3_grand_totals)
            ->with('trp_totals', $trp_totals)
            ->with('grand_total_raw_points', $grand_total_raw_points)
            ->with('vs_grand_total_raw_points', $vs_grand_total_raw_points)
            ->with('s_grand_total_raw_points', $s_grand_total_raw_points)
            ->with('ndvd_grand_total_raw_points', $ndvd_grand_total_raw_points)
            ->with('p1_total_scores', $p1_total_scores)
            ->with('vs_grand_total_score', $vs_grand_total_score) 
            ->with('s_grand_total_score', $s_grand_total_score) 
            ->with('ndvd_grand_total_score', $ndvd_grand_total_score) 
            ->with('grand_total_score', $grand_total_score) 
            ->with('lsr_totals', $lsr_totals)
            ->with('lsr_grand_total', $lsr_grand_total)
            ->with('lsr_average', $lsr_average ) 
            ->with('jul_total_vs_respondents', $jul_total_vs_respondents)
            ->with('aug_total_vs_respondents', $aug_total_vs_respondents)
            ->with('sep_total_vs_respondents', $sep_total_vs_respondents)
            ->with('jul_total_s_respondents', $jul_total_s_respondents)
            ->with('aug_total_s_respondents', $aug_total_s_respondents)
            ->with('jun_total_s_respondents', $sep_total_s_respondents)
            ->with('jul_total_ndvd_respondents', $jul_total_ndvd_respondents)
            ->with('aug_total_ndvd_respondents', $aug_total_ndvd_respondents)
            ->with('sep_total_ndvd_respondents', $sep_total_ndvd_respondents)
            ->with('jul_total_respondents', $jul_total_respondents)
            ->with('aug_total_respondents', $aug_total_respondents)
            ->with('sep_total_respondents', $sep_total_respondents)
            ->with('total_respondents', $total_respondents)
            ->with('total_vss_respondents', $total_vss_respondents)
            ->with('percentage_vss_respondents', $percentage_vss_respondents)
            ->with('total_promoters', $total_promoters)
            ->with('total_detractors', $total_detractors)
            ->with('q3_vi_totals', $q3_vi_totals)
            ->with('q3_i_totals', $q3_i_totals)
            ->with('q3_mi_totals', $q3_mi_totals)
            ->with('q3_si_totals', $q3_si_totals)
            ->with('q3_nai_totals', $q3_nai_totals)
            ->with('q3_i_grand_totals', $q3_i_grand_totals)
            ->with('i_trp_totals', $i_trp_totals)
            ->with('i_grand_total_raw_points', $i_grand_total_raw_points)
            ->with('vi_grand_total_raw_points', $vi_grand_total_raw_points)
            ->with('s_grand_total_raw_points', $s_grand_total_raw_points)
            ->with('misinai_grand_total_raw_points', $misinai_grand_total_raw_points)
            ->with('i_total_scores', $i_total_scores)
            ->with('vi_grand_total_score', $vi_grand_total_score) 
            ->with('i_grand_total_score', $i_grand_total_score) 
            ->with('misinai_grand_total_score', $misinai_grand_total_score)
            ->with('percentage_promoters', $percentage_promoters)
            ->with('jul_percentage_promoters', $jul_percentage_promoters)
            ->with('aug_percentage_promoters', $aug_percentage_promoters)
            ->with('sep_percentage_promoters', $sep_percentage_promoters)
            ->with('average_percentage_promoters', $average_percentage_promoters)
            ->with('jul_percentage_detractors', $jul_percentage_detractors)
            ->with('aug_percentage_detractors', $aug_percentage_detractors)
            ->with('sep_percentage_detractors', $sep_percentage_detractors) 
            ->with('average_percentage_detractors', $average_percentage_detractors)
            ->with('jul_net_promoter_score', $jul_net_promoter_score)
            ->with('aug_net_promoter_score', $aug_net_promoter_score)
            ->with('sep_net_promoter_score', $sep_net_promoter_score)
            ->with('ave_net_promoter_score', $ave_net_promoter_score)
            ->with('customer_satisfaction_rating', $customer_satisfaction_rating)
            ->with('csi', $customer_satisfaction_index)
            ->with('first_month_csi', $first_month_csi)
            ->with('second_month_csi', $second_month_csi)
            ->with('third_month_csi', $third_month_csi)
            ->with('total_comments', $total_comments)
            ->with('total_complaints', $total_complaints)
            ->with('comments', $comments);
    }

    public function generateCSIByUnitFourthQuarter($request, $region_id, $psto_id)
    {
        $sub_unit = $this->getSubUnit($request);
        $unit_pstos = $this->getUnitPSTOs($request);
        $sub_unit_pstos = $this->getSubUnitPSTOs($request);
        $sub_unit_types = $this->getSubUnitTypes($request);

        $date_range = null;
        $customer_recommendation_ratings = null;
        $respondents_list = null;
        $month_oct = [];
        $month_nov = [];
        $month_dec = [];

        
        // store
        $service_id = $request->service['id'];
        $unit_id = $request->unit_id;
        $sub_unit_id = $request->selected_sub_unit;
        $client_type = $request->client_type; 
        $sub_unit_type = $request->sub_unit_type; 

       // search and check list of forms query  
       $customer_ids = $this->querySearchCSF( $region_id, $service_id, $unit_id ,$sub_unit_id , $psto_id, $client_type, $sub_unit_type );
            
        // Retrieve records for the specified quarter and year
        $startDate = Carbon::create($request->selected_year, 10, 1)->startOfDay();
        $endDate = Carbon::create($request->selected_year, 12, 31)->endOfDay();
        $date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                            ->whereBetween('created_at', [$startDate, $endDate])
                                            ->whereYear('created_at', $request->selected_year)->get(); 
        $month_oct = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                            ->whereMonth('created_at',10)
                                            ->whereYear('created_at', $request->selected_year)->get(); 
        $month_nov = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                            ->whereMonth('created_at',11)
                                            ->whereYear('created_at', $request->selected_year)->get(); 
        $month_dec = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                            ->whereMonth('created_at',12)
                                            ->whereYear('created_at', $request->selected_year)->get();

        $customer_recommendation_ratings = CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                            ->whereBetween('created_at', [$startDate, $endDate])
                                            ->whereYear('created_at', $request->selected_year)->get();

        $oct_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereMonth('created_at', 10)
                                                ->whereYear('created_at', $request->selected_year)->get();
        
        $nov_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereMonth('created_at',11)
                                                ->whereYear('created_at', $request->selected_year)->get();
                                                    
        $dec_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereMonth('created_at',12)
                                                ->whereYear('created_at', $request->selected_year)->get();


        $respondents_list = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$startDate, $endDate])
                                                ->whereYear('created_at', $request->selected_year)->get();
           

        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();
        $grand_total_raw_points = 0;
        $vs_grand_total_score =0;
        $s_grand_total_score = 0;
        $ndvd_grand_total_score = 0;
        $grand_total_score =0;

        $vs_grand_total_raw_points = 0;
        $s_grand_total_raw_points = 0;
        $ndvd_grand_total_raw_points = 0;
        $lsr_grand_total = 0 ;
        $lsr_average = 0;

        //Importance total raw points  
        $vi_grand_total_raw_points = 0;
        $i_grand_total_raw_points = 0;
        $misinai_grand_total_raw_points = 0;
        //Importance grand total score
        $vi_grand_total_score=0;
        $i_grand_total_score =0;
        $misinai_grand_total_score = 0;

        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            //PART I

            // October total responses with its dimensions and rate score
            $oct_vs_total = $month_oct->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $oct_s_total = $month_oct->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $oct_n_total = $month_oct->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $oct_d_total = $month_oct->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $oct_vd_total = $month_oct->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $oct_grand_total =  $oct_vs_total + $oct_s_total + $oct_n_total + $oct_d_total + $oct_vd_total ; 

            //  November total responses with its dimensions and rate score
            $nov_vs_total = $month_nov->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $nov_s_total = $month_nov->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $nov_n_total = $month_nov->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $nov_d_total = $month_nov->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $nov_vd_total = $month_nov->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $nov_grand_total =  $nov_vs_total + $nov_s_total + $nov_n_total + $nov_d_total + $nov_vd_total ; 

            //  Decmeber total responses with its dimensions and rate score
            $dec_vs_total = $month_dec->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $dec_s_total = $month_dec->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $dec_n_total = $month_dec->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $dec_d_total = $month_dec->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $dec_vd_total = $month_dec->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $dec_grand_total =  $dec_vs_total + $dec_s_total + $dec_n_total + $dec_d_total + $dec_vd_total ; 

            // Quarter 1 Very Satisfied total with specific dimention or attribute
            $q4_vs_totals[$dimensionId] = [
                'oct_vs_total' => $oct_vs_total,
                'nov_vs_total' => $nov_vs_total,
                'dec_vs_total' => $dec_vs_total,
            ];

            $q4_s_totals[$dimensionId] = [
                'oct_s_total' => $oct_s_total,
                'nov_s_total' => $nov_s_total,
                'dec_s_total' => $dec_s_total,
            ];

            $q4_n_totals[$dimensionId] = [
                'oct_n_total' => $oct_n_total,
                'nov_n_total' => $nov_n_total,
                'dec_n_total' => $dec_n_total,
            ];

            $q4_d_totals[$dimensionId] = [
                'oct_d_total' => $oct_d_total,
                'nov_d_total' => $nov_d_total,
                'dec_d_total' => $dec_d_total,
            ];

            $q4_vd_totals[$dimensionId] = [
                'oct_vd_total' => $oct_vd_total,
                'nov_vd_total' => $nov_vd_total,
                'dec_vd_total' => $dec_vd_total,
            ];

            $q4_grand_totals[$dimensionId] = [
                'oct_grand_total' => $oct_grand_total,
                'nov_grand_total' => $nov_grand_total,
                'dec_grand_total' => $dec_grand_total,
            ];

            //Total Raw Points totals
            $vs_total_raw_points = $oct_vs_total + $nov_vs_total + $dec_vs_total;
            $s_total_raw_points =  $oct_s_total + $nov_s_total + $dec_s_total;
            $n_total_raw_points =  $oct_n_total + $nov_n_total + $dec_n_total;
            $d_total_raw_points = $oct_d_total + $nov_d_total + $dec_d_total;
            $vd_total_raw_points =  $oct_vd_total + $nov_vd_total + $dec_vd_total;
            $total_raw_points = $vs_total_raw_points + $s_total_raw_points + $n_total_raw_points +  $d_total_raw_points +  $vd_total_raw_points;           

            $vs_grand_total_raw_points += $vs_total_raw_points;
            $s_grand_total_raw_points +=  $s_total_raw_points;
            $ndvd_grand_total_raw_points +=  $n_total_raw_points + $d_total_raw_points + $vd_total_raw_points;
            $grand_total_raw_points+= $total_raw_points;

            $trp_totals[$dimensionId] = [
                'vs_total_raw_points' => $vs_total_raw_points,
                's_total_raw_points' => $s_total_raw_points,
                'n_total_raw_points' => $n_total_raw_points,
                'd_total_raw_points' => $d_total_raw_points,
                'vd_total_raw_points' => $vd_total_raw_points,
                'total_raw_points' => $total_raw_points,
            ];

            //Part 1 Quarter 1 total scores
            $x_vs_total = $vs_total_raw_points * 5; 
            $x_s_total = $s_total_raw_points * 4; 
            $x_n_total = $n_total_raw_points * 3; 
            $x_d_total = $d_total_raw_points * 3; 
            $x_vd_total = $vd_total_raw_points * 1; 
            $x_total_score =  $x_vs_total +  $x_s_total +  $x_n_total +  $x_d_total + $x_vd_total;
            
            $vs_grand_total_score += $x_vs_total ;
            $s_grand_total_score += $x_s_total ;
            $ndvd_grand_total_score += $x_n_total +  $x_d_total + $x_vd_total ;
            $grand_total_score += $x_total_score ;

            $p1_total_scores[$dimensionId] = [ 
                'x_vs_total' => $x_vs_total,
                'x_s_total' => $x_s_total,
                'x_n_total' => $x_n_total,
                'x_d_total' => $x_d_total,
                'x_vd_total' => $x_vd_total, 
                'x_total_score' => $x_total_score,
            ];

            // Likert Scale Rating = total score / grand total of total raw points
            if($total_raw_points != 0 ){
                $vs_lsr_total =   number_format(($x_vs_total  /  $total_raw_points),2);
                $s_lsr_total =    number_format(($x_s_total /  $total_raw_points),2);
                $n_lsr_total =   number_format(($x_n_total /  $total_raw_points),2);
                $d_lsr_total =   number_format(($x_d_total /  $total_raw_points),2);
                $vd_lsr_total =   number_format(($x_vd_total /  $total_raw_points),2);
                $lsr_total =  number_format(($vs_lsr_total +  $s_lsr_total  +  $n_lsr_total  +  $d_lsr_total  +  $vd_lsr_total),2);
                $lsr_grand_total +=  $lsr_total;
                $lsr_grand_total =number_format(($lsr_grand_total),2);
                $lsr_average =  number_format(($lsr_grand_total / $dimensionId), 2);
            }
            else{
                $vs_lsr_total =  0;
                $s_lsr_total =  0;
                $n_lsr_total =  0;
                $d_lsr_total = 0;
                $vd_lsr_total =  0;
                $lsr_total = 0;
                $lsr_grand_total +=  0;
                $lsr_average =  0;
            }

            $lsr_totals[$dimensionId] = [
                'vs_lsr_total' => $vs_lsr_total,
                's_lsr_total' => $s_lsr_total,
                'n_lsr_total' => $n_lsr_total,
                'd_lsr_total' => $d_lsr_total,
                'vd_lsr_total' => $vd_lsr_total,
                'lsr_total' => $lsr_total,
            ];
            
            // PART II
              // October total responses with its dimensions and rate score
              $oct_vi_total = $month_oct->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $oct_i_total = $month_oct->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $oct_mi_total = $month_oct->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $oct_si_total = $month_oct->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $oct_nai_total = $month_oct->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $oct_i_grand_total =  $oct_vi_total + $oct_i_total + $oct_mi_total + $oct_si_total + $oct_nai_total ; 
  
              //  Novemeber total responses with its dimensions and rate score
              $nov_vi_total = $month_nov->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $nov_i_total = $month_nov->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $nov_mi_total = $month_nov->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $nov_si_total = $month_nov->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $nov_nai_total = $month_nov->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $nov_i_grand_total =  $nov_vi_total + $nov_i_total + $nov_mi_total + $nov_si_total + $nov_nai_total ; 
  
              //  December total responses with its dimensions and rate score
              $dec_vi_total = $month_dec->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $dec_i_total = $month_dec->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $dec_mi_total = $month_dec->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $dec_si_total = $month_dec->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $dec_nai_total = $month_dec->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $dec_i_grand_total =  $dec_vi_total + $dec_i_total + $dec_mi_total + $dec_si_total + $dec_nai_total ; 

                // Very Important total with specific dimention or attribute
                $q4_vi_totals[$dimensionId] = [
                    'oct_vi_total' => $oct_vi_total,
                    'nov_vi_total' => $nov_vi_total,
                    'dec_vi_total' => $dec_vi_total,
                ];
                //Important total with specific dimention or attribute
                $q4_i_totals[$dimensionId] = [
                    'oct_i_total' => $oct_i_total,
                    'nov_i_total' => $nov_i_total,
                    'dec_i_total' => $dec_i_total,
                ];
                // Moderately Important total with specific dimention or attribute
                $q4_mi_totals[$dimensionId] = [
                    'oct_mi_total' => $oct_mi_total,
                    'nov_mi_total' => $nov_mi_total,
                    'dec_mi_total' => $dec_mi_total,
                ];
                // slightly Important total with specific dimention or attribute
                $q4_si_totals[$dimensionId] = [
                    'oct_si_total' => $oct_si_total,
                    'nov_si_total' => $nov_si_total,
                    'dec_si_total' => $dec_si_total,
                ];

                $q4_nai_totals[$dimensionId] = [
                    'oct_nai_total' => $oct_nai_total,
                    'nov_nai_total' => $nov_nai_total,
                    'dec_nai_total' => $dec_nai_total,
                ];

                $q4_i_grand_totals[$dimensionId] = [
                    'oct_i_grand_total' => $oct_i_grand_total,
                    'nov_i_grand_total' => $nov_i_grand_total,
                    'dec_i_grand_total' => $dec_i_grand_total,
                ];

                
            //Importance Total Raw Points totals
            $vi_total_raw_points = $oct_vi_total + $nov_vi_total + $dec_vi_total;
            $i_total_raw_points = $oct_i_total + $nov_i_total + $dec_i_total;
            $mi_total_raw_points = $oct_mi_total + $nov_mi_total + $dec_mi_total;
            $si_total_raw_points = $oct_si_total + $nov_si_total + $dec_si_total;
            $nai_total_raw_points =$oct_nai_total + $nov_nai_total + $dec_nai_total;
            $i_total_raw_points = $vi_total_raw_points + $i_total_raw_points + $mi_total_raw_points +  $si_total_raw_points +  $nai_total_raw_points;           

            $vi_grand_total_raw_points += $vi_total_raw_points;
            $s_grand_total_raw_points +=  $i_total_raw_points;
            $misinai_grand_total_raw_points +=  $mi_total_raw_points + $si_total_raw_points + $nai_total_raw_points;
            $i_grand_total_raw_points+= $total_raw_points;

            $i_trp_totals[$dimensionId] = [
                'vi_total_raw_points' => $vi_total_raw_points,
                'i_total_raw_points' => $i_total_raw_points,
                'mi_total_raw_points' => $mi_total_raw_points,
                'si_total_raw_points' => $si_total_raw_points,
                'nai_total_raw_points' => $nai_total_raw_points,
                'i_total_raw_points' => $i_total_raw_points,
            ];

            //Part 1 Quarter 1 total scores
            $x_vi_total = $vi_total_raw_points * 5; 
            $x_i_total = $i_total_raw_points * 4; 
            $x_mi_total = $mi_total_raw_points * 3; 
            $x_si_total = $si_total_raw_points * 3; 
            $x_nai_total = $nai_total_raw_points * 1; 
            $x_i_total_score =  $x_vi_total +  $x_i_total +  $x_mi_total +  $x_si_total + $x_nai_total;
            
            $vi_grand_total_score += $x_vi_total ;
            $i_grand_total_score += $x_si_total ;
            $misinai_grand_total_score += $x_mi_total +  $x_si_total + $x_nai_total ;

            $i_total_scores[$dimensionId] = [ 
                'x_vi_total' => $x_vi_total,
                'x_i_total' => $x_i_total,
                'x_mi_total' => $x_mi_total,
                'x_si_total' => $x_si_total,
                'x_nai_total' => $x_nai_total, 
                'x_i_total_score' => $x_i_total_score,
            ];

        
        }

        // Total No. of Very Satisfied (VS) Responses of First Quarte
        // -- October
        $oct_total_vs_respondents = $month_oct->where('rate_score',5)->groupBy('customer_id')->count();
        // -- November
        $nov_total_vs_respondents = $month_nov->where('rate_score',5)->groupBy('customer_id')->count();
        // -- December
        $dec_total_vs_respondents = $month_dec->where('rate_score',5)->groupBy('customer_id')->count();

        // Total No. of Satisfied (S) Responses
       // -- October
       $oct_total_s_respondents = $month_oct->where('rate_score',4)->groupBy('customer_id')->count();
       // -- November
       $nov_total_s_respondents = $month_nov->where('rate_score',4)->groupBy('customer_id')->count();
       // -- December
       $dec_total_s_respondents = $month_dec->where('rate_score',4)->groupBy('customer_id')->count();

        // Total No. of Other (N, D, VD) Responses
       // -- October
       $oct_total_ndvd_respondents = $month_oct->where('rate_score','<', 4)->groupBy('customer_id')->count();
       // -- November
       $nov_total_ndvd_respondents = $month_nov->where('rate_score','<', 4)->groupBy('customer_id')->count();
       // -- December
       $dec_total_ndvd_respondents = $month_dec->where('rate_score','<', 4)->groupBy('customer_id')->count();
          
        // Total No. of All Responses
        // -- October
        $oct_total_respondents = $month_oct->groupBy('customer_id')->count();
        // -- November
        $nov_total_respondents = $month_nov->groupBy('customer_id')->count();
        // -- December
        $dec_total_respondents = $month_dec->groupBy('customer_id')->count();

        //Total respondents /Customers
        $total_respondents = $date_range->groupBy('customer_id')->count();

        // total number of respondents/customer who rated VS/S
        $total_vss_respondents = $date_range->where('rate_score', '>','3')->groupBy('customer_id')->count();
        // total number of respondents/customer who rated VS
        $total_vs_respondents = $date_range->where('rate_score', '=','5')->groupBy('customer_id')->count();
        // total number of respondents/customer who rated S
        $total_s_respondents = $date_range->where('rate_score', '=','4')->groupBy('customer_id')->count();
    
        // Frst quarter total number of promoters or respondents who rated 9-10 in recommendation rating
        $total_promoters = $customer_recommendation_ratings->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        // October
        $oct_total_promoters = $oct_crr->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        // November
        $nov_total_promoters = $nov_crr->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        // December
        $dec_total_promoters = $dec_crr->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        
        // total number of detractors or respondents who rated 0-6 in recommendation rating
        $total_detractors = $customer_recommendation_ratings->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
         // October
         $oct_total_detractors = $oct_crr->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
         // November
         $nov_total_detractors = $nov_crr->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
         // December
         $dec_total_detractors = $dec_crr->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
  
          //Percentage of Respondents/Customers who rated VS/S = total no. of respondents / total no. respondets who rated vs/s * 100
        $percentage_vss_respondents  = 0;
        if($total_respondents != 0){
            $percentage_vss_respondents  = ($total_respondents/$total_vss_respondents) * 100;
        }
        $percentage_vss_respondents = number_format( $percentage_vss_respondents , 2);

        $customer_satisfaction_rating = 0;
        if($total_vss_respondents != 0){
            $customer_satisfaction_rating = ($total_vss_respondents/$total_vss_respondents) * 100;
        }
        $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);

        //Percentage of Promoters = number of promoters / total respondents
        $percentage_promoters = 0;
        //  Percentage promoters
        $oct_percentage_promoters = 0.00;
        $nov_percentage_promoters = 0.00;
        $dec_percentage_promoters = 0.00;

        // Percentage of promoter
        $oct_percentage_detractors = 0.00;
        $nov_percentage_detractors = 0.00;
        $dec_percentage_detractors = 0.00;

        //nps
        $oct_net_promoter_score =  0.00;
        $nov_net_promoter_score = 0.00;
        $dec_net_promoter_score =  0.00;

        //average
        $ave_net_promoter_score = 0.00;
        $average_percentage_promoters =  0.00;
        $average_percentage_detractors =  0.00;

        if($total_respondents != 0){
            $percentage_promoters = number_format((($total_promoters / $total_respondents) * 100), 2);
            $oct_percentage_promoters = number_format((($oct_total_promoters / $total_respondents) * 100), 2);
            $nov_percentage_promoters = number_format((($nov_total_promoters / $total_respondents) * 100), 2);
            $dec_percentage_promoters = number_format((($dec_total_promoters / $total_respondents) * 100), 2);
        
            //Percentage of Promoters = number of promoters / total respondents
            $percentage_detractors = number_format((($total_detractors / $total_respondents) * 100),2);
            $oct_percentage_detractors = number_format((($oct_total_detractors / $total_respondents) * 100),2);
            $nov_percentage_detractors = number_format((($nov_total_detractors / $total_respondents) * 100),2);
            $dec_percentage_detractors = number_format((($dec_total_detractors / $total_respondents) * 100),2);
            

            // Net Promotion Scores(NPS) = Percentage of Promoters−Percentage of Detractors
            $oct_net_promoter_score =  number_format(($oct_percentage_detractors - $oct_percentage_detractors),2);
            $nov_net_promoter_score =  number_format(($nov_percentage_detractors - $nov_percentage_detractors),2);
            $dec_net_promoter_score =  number_format(($dec_percentage_detractors - $dec_percentage_detractors),2);

            $ave_net_promoter_score =  number_format((($oct_net_promoter_score + $nov_net_promoter_score + $dec_net_promoter_score)/ 3),2);
            $average_percentage_promoters =  number_format((($oct_percentage_promoters + $nov_percentage_promoters + $dec_percentage_promoters)/ 3),2);
            $average_percentage_detractors =  number_format((($oct_percentage_detractors + $nov_percentage_detractors + $dec_percentage_detractors)/ 3),2);


            // CSAT = ((Total No. of Very Satisfied (VS) Responses + Total No. of Satisfied (S) Responses) / grand total respondents) * 100
            $customer_satisfaction_rating = 0;
            if($total_vss_respondents != 0){
                $customer_satisfaction_rating = (($total_vss_respondents / $total_respondents)) * 100;
            }
            $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);
        }

        // get Monthly CSI
        $first_month_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 10);
        $second_month_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 11);
        $third_month_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 12);
  
        $customer_satisfaction_index = number_format((($first_month_csi + $second_month_csi +  $third_month_csi)/3), 2);

         //comments and  complaints
         $comment_list = CustomerComment::whereIn('customer_id', $customer_ids)
                                        ->whereBetween('created_at', [$startDate, $endDate])
                                        ->whereYear('created_at', $request->selected_year)->get();

        $comments = $comment_list->where('comment','!=','')->pluck('comment'); 

        $total_comments = $comment_list->where('comment','!=','')->count();
        $total_complaints = $comment_list->where('is_complaint',1)->count();

        //Respondents list
        $data = CARResource::collection($respondents_list);

        //send response to front end
        return Inertia::render('CSI/Index')
            ->with('sub_unit', $sub_unit)
            ->with('unit_pstos', $unit_pstos)
            ->with('sub_unit_pstos', $sub_unit_pstos)
            ->with('sub_unit_types', $sub_unit_types)
            ->with('dimensions', $dimensions)
            ->with('service', $request->service)
            ->with('unit', $request->unit)
            ->with('respondents_list',$data)
            ->with('q4_vs_totals', $q4_vs_totals)
            ->with('q4_s_totals', $q4_s_totals)
            ->with('q4_n_totals', $q4_n_totals)
            ->with('q4_d_totals', $q4_d_totals)
            ->with('q4_vd_totals', $q4_vd_totals)
            ->with('q4_grand_totals', $q4_grand_totals)
            ->with('trp_totals', $trp_totals)
            ->with('grand_total_raw_points', $grand_total_raw_points)
            ->with('vs_grand_total_raw_points', $vs_grand_total_raw_points)
            ->with('s_grand_total_raw_points', $s_grand_total_raw_points)
            ->with('ndvd_grand_total_raw_points', $ndvd_grand_total_raw_points)
            ->with('p1_total_scores', $p1_total_scores)
            ->with('vs_grand_total_score', $vs_grand_total_score) 
            ->with('s_grand_total_score', $s_grand_total_score) 
            ->with('ndvd_grand_total_score', $ndvd_grand_total_score) 
            ->with('grand_total_score', $grand_total_score) 
            ->with('lsr_totals', $lsr_totals)
            ->with('lsr_grand_total', $lsr_grand_total)
            ->with('lsr_average', $lsr_average ) 
            ->with('oct_total_vs_respondents', $oct_total_vs_respondents)
            ->with('nov_total_vs_respondents', $nov_total_vs_respondents)
            ->with('dec_total_vs_respondents', $dec_total_vs_respondents)
            ->with('oct_total_s_respondents', $oct_total_s_respondents)
            ->with('nov_total_s_respondents', $nov_total_s_respondents)
            ->with('dec_total_s_respondents', $dec_total_s_respondents)
            ->with('oct_total_ndvd_respondents', $oct_total_ndvd_respondents)
            ->with('nov_total_ndvd_respondents', $nov_total_ndvd_respondents)
            ->with('dec_total_ndvd_respondents', $dec_total_ndvd_respondents)
            ->with('oct_total_respondents', $oct_total_respondents)
            ->with('nov_total_respondents', $nov_total_respondents)
            ->with('dec_total_respondents', $dec_total_respondents)
            ->with('total_respondents', $total_respondents)
            ->with('total_vss_respondents', $total_vss_respondents)
            ->with('percentage_vss_respondents', $percentage_vss_respondents)
            ->with('total_promoters', $total_promoters)
            ->with('total_detractors', $total_detractors)
            ->with('q4_vi_totals', $q4_vi_totals)
            ->with('q4_i_totals', $q4_i_totals)
            ->with('q4_mi_totals', $q4_mi_totals)
            ->with('q4_si_totals', $q4_si_totals)
            ->with('q4_nai_totals', $q4_nai_totals)
            ->with('q4_i_grand_totals', $q4_i_grand_totals)
            ->with('i_trp_totals', $i_trp_totals)
            ->with('i_grand_total_raw_points', $i_grand_total_raw_points)
            ->with('vi_grand_total_raw_points', $vi_grand_total_raw_points)
            ->with('s_grand_total_raw_points', $s_grand_total_raw_points)
            ->with('misinai_grand_total_raw_points', $misinai_grand_total_raw_points)
            ->with('i_total_scores', $i_total_scores)
            ->with('vi_grand_total_score', $vi_grand_total_score) 
            ->with('i_grand_total_score', $i_grand_total_score) 
            ->with('misinai_grand_total_score', $misinai_grand_total_score)
            ->with('percentage_promoters', $percentage_promoters)
            ->with('oct_percentage_promoters', $oct_percentage_promoters)
            ->with('nov_percentage_promoters', $nov_percentage_promoters)
            ->with('dec_percentage_promoters', $dec_percentage_promoters)
            ->with('average_percentage_promoters', $average_percentage_promoters)
            ->with('oct_percentage_detractors', $oct_percentage_detractors)
            ->with('nov_percentage_detractors', $nov_percentage_detractors)
            ->with('dec_percentage_detractors', $dec_percentage_detractors) 
            ->with('average_percentage_detractors', $average_percentage_detractors)
            ->with('oct_net_promoter_score', $oct_net_promoter_score)
            ->with('nov_net_promoter_score', $nov_net_promoter_score)
            ->with('dec_net_promoter_score', $dec_net_promoter_score)
            ->with('ave_net_promoter_score', $ave_net_promoter_score)
            ->with('customer_satisfaction_rating', $customer_satisfaction_rating)
            ->with('csi', $customer_satisfaction_index)
            ->with('first_month_csi', $first_month_csi)
            ->with('second_month_csi', $second_month_csi)
            ->with('third_month_csi', $third_month_csi)
            ->with('total_comments', $total_comments)
            ->with('total_complaints', $total_complaints)
            ->with('comments', $comments);
    }

    public function generateCSIByUnitYearly($request, $region_id, $psto_id)
    {
        $sub_unit = $this->getSubUnit($request);
        $unit_pstos = $this->getUnitPSTOs($request);
        $sub_unit_pstos = $this->getSubUnitPSTOs($request);
        $sub_unit_types = $this->getSubUnitTypes($request);

        $date_range = [];
        $q1_date_range = [];
        $q2_date_range = [];
        $q3_date_range = [];
        $q4_date_range = [];
        $customer_recommendation_ratings = null;
        $respondents_list = null;
          
        $service_id = $request->service['id'];
        $unit_id = $request->unit_id;
        $sub_unit_id = $request->selected_sub_unit;
        $client_type = $request->client_type; 
        $sub_unit_type = $request->sub_unit_type; 

       // search and check list of forms query  
       $customer_ids = $this->querySearchCSF( $region_id, $service_id, $unit_id ,$sub_unit_id , $psto_id, $client_type, $sub_unit_type );
            
        // Retrieve records for the specified quarter and year
        $q1_start_date = Carbon::create($request->selected_year, 1, 1)->startOfDay();
        $q1_end_date = Carbon::create($request->selected_year, 3, 31)->endOfDay();

        $q2_start_date = Carbon::create($request->selected_year, 4, 1)->startOfDay();
        $q2_end_date = Carbon::create($request->selected_year, 6, 31)->endOfDay();

        $q3_start_date = Carbon::create($request->selected_year, 7, 1)->startOfDay();
        $q3_end_date = Carbon::create($request->selected_year, 9, 31)->endOfDay();

        $q4_start_date = Carbon::create($request->selected_year, 10, 1)->startOfDay();
        $q4_end_date = Carbon::create($request->selected_year, 12, 31)->endOfDay();

        $q1_date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$q1_start_date, $q1_end_date])
                                                ->whereYear('created_at', $request->selected_year)->get(); 
        $q2_date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$q2_start_date, $q2_end_date])
                                                ->whereYear('created_at', $request->selected_year)->get(); 
        $q3_date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$q3_start_date, $q3_end_date])
                                                ->whereYear('created_at', $request->selected_year)->get();
        $q4_date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$q4_start_date, $q4_end_date])
                                                ->whereYear('created_at', $request->selected_year)->get();
        
        $date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                                ->whereYear('created_at', $request->selected_year)->get(); 

        $customer_recommendation_ratings = CustomerRecommendationRating::whereYear('created_at', $request->selected_year)->get();

        $q1_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$q1_start_date, $q1_end_date])
                                                ->whereYear('created_at', $request->selected_year)->get();

        $q2_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$q2_start_date, $q2_end_date])
                                                ->whereYear('created_at', $request->selected_year)->get();
                                                    
        $q3_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$q3_start_date, $q3_end_date])
                                                ->whereYear('created_at', $request->selected_year)->get();

        $q4_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$q4_start_date, $q4_end_date])
                                                ->whereYear('created_at', $request->selected_year)->get();


        $respondents_list = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                                ->whereYear('created_at', $request->selected_year)->get();     
          
        

        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();
        $grand_total_raw_points = 0;
        $vs_grand_total_score =0;
        $s_grand_total_score = 0;
        $ndvd_grand_total_score = 0;
        $grand_total_score =0;

        $vs_grand_total_raw_points = 0;
        $s_grand_total_raw_points = 0;
        $ndvd_grand_total_raw_points = 0;
        $lsr_grand_total = 0 ;
        $lsr_average = 0;

        //Importance total raw points  
        $vi_grand_total_raw_points = 0;
        $i_grand_total_raw_points = 0;
        $misinai_grand_total_raw_points = 0;
        //Importance grand total score
        $vi_grand_total_score=0;
        $i_grand_total_score =0;
        $misinai_grand_total_score = 0;
        $overall_total_scores = 0;

        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            //PART I

            //  Quarter 1  with specific year total responses with its dimensions and rate score
            $q1_vs_total = $q1_date_range->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $q1_s_total = $q1_date_range->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $q1_n_total = $q1_date_range->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $q1_d_total = $q1_date_range->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $q1_vd_total = $q1_date_range->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $q1_grand_total =  $q1_vs_total + $q1_s_total + $q1_n_total + $q1_d_total + $q1_vd_total ; 
     
            //  Quarter 2  with specific year total responses with its dimensions and rate score
            $q2_vs_total = $q2_date_range->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $q2_s_total = $q2_date_range->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $q2_n_total = $q2_date_range->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $q2_d_total = $q2_date_range->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $q2_vd_total = $q2_date_range->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $q2_grand_total =  $q2_vs_total + $q2_s_total + $q2_n_total + $q2_d_total + $q2_vd_total ; 

             //  Quarter 3  with specific year total responses with its dimensions and rate score
             $q3_vs_total = $q3_date_range->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
             $q3_s_total = $q3_date_range->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
             $q3_n_total = $q3_date_range->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
             $q3_d_total = $q3_date_range->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
             $q3_vd_total = $q3_date_range->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
 
             $q3_grand_total =  $q3_vs_total + $q3_s_total + $q3_n_total + $q3_d_total + $q3_vd_total ; 
     
             //  Quarter 4  with specific year total responses with its dimensions and rate score
             $q4_vs_total = $q4_date_range->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
             $q4_s_total = $q4_date_range->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
             $q4_n_total = $q4_date_range->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
             $q4_d_total = $q4_date_range->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
             $q4_vd_total = $q4_date_range->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
 
             $q4_grand_total =  $q4_vs_total + $q4_s_total + $q4_n_total + $q4_d_total + $q4_vd_total ; 
            

            // Quarters Very Satisfied total with specific dimention or attribute
            $vs_totals[$dimensionId] = [
                'q1_vs_total' => $q1_vs_total,
                'q2_vs_total' => $q2_vs_total,
                'q3_vs_total' => $q3_vs_total,
                'q4_vs_total' => $q4_vs_total,
            ];
             // Quarters Satisfied total with specific dimention or attribute
            $s_totals[$dimensionId] = [
                'q1_s_total' => $q1_s_total,
                'q2_s_total' => $q2_s_total,
                'q3_s_total' => $q3_s_total,
                'q4_s_total' => $q4_s_total,
            ];
            // Quarters Neither total with specific dimention or attribute
            $n_totals[$dimensionId] = [
                'q1_n_total' => $q1_n_total,
                'q2_n_total' => $q2_n_total,
                'q3_n_total' => $q3_n_total,
                'q4_n_total' => $q4_n_total,
            ];
            // Quarters Dissatisfied total with specific dimention or attribute
            $d_totals[$dimensionId] = [
                'q1_d_total' => $q1_d_total,
                'q2_d_total' => $q2_d_total,
                'q3_d_total' => $q3_d_total,
                'q4_d_total' => $q4_d_total,
            ];
            // Quarters Very Dissatisfied total with specific dimention or attribute
            $vd_totals[$dimensionId] = [
                'q1_vd_total' => $q1_vd_total,
                'q2_vd_total' => $q2_vd_total,
                'q3_vd_total' => $q3_vd_total,
                'q4_vd_total' => $q4_vd_total,
            ];

            // Quarters grand total with specific dimention or attribute
            $grand_totals[$dimensionId] = [
                'q1_grand_total' => $q1_grand_total,
                'q2_grand_total' => $q2_grand_total,
                'q3_grand_total' => $q3_grand_total,
                'q4_grand_total' => $q4_grand_total,
            ];

            //Total Raw Points totals
            $vs_total_raw_points = $q1_vs_total + $q2_vs_total + $q3_vs_total + $q4_vs_total;
            $s_total_raw_points = $q1_s_total + $q2_s_total + $q3_s_total + $q4_s_total;
            $n_total_raw_points = $q1_n_total + $q2_n_total + $q3_n_total + $q4_n_total;
            $d_total_raw_points = $q1_n_total + $q2_n_total + $q3_n_total + $q4_n_total;
            $vd_total_raw_points = $q1_vd_total + $q2_vd_total + $q3_vd_total + $q4_vd_total;
            $total_raw_points = $vs_total_raw_points + $s_total_raw_points + $n_total_raw_points +  $d_total_raw_points +  $vd_total_raw_points;           

            $vs_grand_total_raw_points += $vs_total_raw_points;
            $s_grand_total_raw_points +=  $s_total_raw_points;
            $ndvd_grand_total_raw_points +=  $n_total_raw_points + $d_total_raw_points + $vd_total_raw_points;
            $grand_total_raw_points+= $total_raw_points;

            $trp_totals[$dimensionId] = [
                'vs_total_raw_points' => $vs_total_raw_points,
                's_total_raw_points' => $s_total_raw_points,
                'n_total_raw_points' => $n_total_raw_points,
                'd_total_raw_points' => $d_total_raw_points,
                'vd_total_raw_points' => $vd_total_raw_points,
                'total_raw_points' => $total_raw_points,
            ];

            //Part 1 Quarter 1 total scores
            $x_vs_total = $vs_total_raw_points * 5; 
            $x_s_total = $s_total_raw_points * 4; 
            $x_n_total = $n_total_raw_points * 3; 
            $x_d_total = $d_total_raw_points * 3; 
            $x_vd_total = $vd_total_raw_points * 1; 
            $x_total_score =  $x_vs_total +  $x_s_total +  $x_n_total +  $x_d_total + $x_vd_total;
            
            $vs_grand_total_score += $x_vs_total ;
            $s_grand_total_score += $x_s_total ;
            $ndvd_grand_total_score += $x_n_total +  $x_d_total + $x_vd_total ;
            $grand_total_score += $x_total_score ;

            $p1_total_scores[$dimensionId] = [ 
                'x_vs_total' => $x_vs_total,
                'x_s_total' => $x_s_total,
                'x_n_total' => $x_n_total,
                'x_d_total' => $x_d_total,
                'x_vd_total' => $x_vd_total, 
                'x_total_score' => $x_total_score,
            ];

            // Likert Scale Rating = total score / grand total of total raw points
            if($total_raw_points != 0 ){
                $vs_lsr_total =   number_format(($x_vs_total  /  $total_raw_points),2);
                $s_lsr_total =    number_format(($x_s_total /  $total_raw_points),2);
                $n_lsr_total =   number_format(($x_n_total /  $total_raw_points),2);
                $d_lsr_total =   number_format(($x_d_total /  $total_raw_points),2);
                $vd_lsr_total =   number_format(($x_vd_total /  $total_raw_points),2);
                $lsr_total =  number_format(($vs_lsr_total +  $s_lsr_total  +  $n_lsr_total  +  $d_lsr_total  +  $vd_lsr_total),2);
                $lsr_grand_total +=  $lsr_total;
                $lsr_grand_total =number_format(($lsr_grand_total),2);
                $lsr_average =  number_format(($lsr_grand_total / $dimensionId), 2);
            }
            else{
                $vs_lsr_total =  0;
                $s_lsr_total =  0;
                $n_lsr_total =  0;
                $d_lsr_total = 0;
                $vd_lsr_total =  0;
                $lsr_total = 0;
                $lsr_grand_total +=  0;
                $lsr_average =  0;
            }

            $lsr_totals[$dimensionId] = [
                'vs_lsr_total' => $vs_lsr_total,
                's_lsr_total' => $s_lsr_total,
                'n_lsr_total' => $n_lsr_total,
                'd_lsr_total' => $d_lsr_total,
                'vd_lsr_total' => $vd_lsr_total,
                'lsr_total' => $lsr_total,
            ];
            
            // PART II
              // Quarter 1 total responses with its dimensions and rate score
              $q1_vi_total = $q1_date_range->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q1_i_total = $q1_date_range->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q1_mi_total = $q1_date_range->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q1_si_total = $q1_date_range->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q1_nai_total = $q1_date_range->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $q1_i_grand_total =  $q1_vi_total + $q1_i_total + $q1_mi_total + $q1_si_total + $q1_nai_total ; 
  
              // Quarter 2 total responses with its dimensions and rate score
              $q2_vi_total = $q2_date_range->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q2_i_total = $q2_date_range->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q2_mi_total = $q2_date_range->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q2_si_total = $q2_date_range->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q2_nai_total = $q2_date_range->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $q2_i_grand_total =  $q2_vi_total + $q2_i_total + $q2_mi_total + $q2_si_total + $q2_nai_total ; 
  
              //  Quarter 3 total responses with its dimensions and rate score
              $q3_vi_total = $q3_date_range->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q3_i_total = $q3_date_range->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q3_mi_total = $q3_date_range->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q3_si_total = $q3_date_range->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q3_nai_total = $q3_date_range->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $q3_i_grand_total =  $q3_vi_total + $q3_i_total + $q3_mi_total + $q3_si_total + $q3_nai_total ;
              
              //  Quarter 4 total responses with its dimensions and rate score
              $q4_vi_total = $q3_date_range->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q4_i_total = $q3_date_range->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q4_mi_total = $q3_date_range->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q4_si_total = $q3_date_range->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q4_nai_total = $q3_date_range->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

              $q4_i_grand_total =  $q4_vi_total + $q4_i_total + $q4_mi_total + $q4_si_total + $q4_nai_total ;

              
                // Very Important total with specific dimention or attribute
                $vi_totals[$dimensionId] = [
                    'q1_vi_total' => $q1_vi_total,
                    'q2_vi_total' => $q2_vi_total,
                    'q3_vi_total' => $q3_vi_total,
                    'q4_vi_total' => $q4_vi_total,
                ];
                //Important total with specific dimention or attribute
                $i_totals[$dimensionId] = [
                    'q1_i_total' => $q1_i_total,
                    'q2_i_total' => $q2_i_total,
                    'q3_i_total' => $q3_i_total,
                    'q4_i_total' => $q4_i_total,
                ];
                // Moderately Important total with specific dimention or attribute
                $mi_totals[$dimensionId] = [
                    'q1_mi_total' => $q1_mi_total,
                    'q2_mi_total' => $q2_mi_total,
                    'q3_mi_total' => $q3_mi_total,
                    'q4_mi_total' => $q4_mi_total,
                ];
                // slightly Important total with specific dimention or attribute
                $si_totals[$dimensionId] = [
                    'q1_si_total' => $q1_si_total,
                    'q2_si_total' => $q2_si_total,
                    'q3_si_total' => $q3_si_total,
                    'q4_si_total' => $q4_si_total,
                ];

                $nai_totals[$dimensionId] = [
                    'q1_nai_total' => $q1_nai_total,
                    'q2_nai_total' => $q2_nai_total,
                    'q3_nai_total' => $q3_nai_total,
                    'q4_nai_total' => $q4_nai_total,
                ];

                $i_grand_totals[$dimensionId] = [
                    'q1_i_grand_total' => $q1_i_grand_total,
                    'q2_i_grand_total' => $q2_i_grand_total,
                    'q3_i_grand_total' => $q3_i_grand_total,
                    'q4_i_grand_total' => $q4_i_grand_total,
                ];

                
            //Importance Total Raw Points totals
            $vi_total_raw_points = $q1_vi_total + $q2_vi_total + $q3_vi_total + $q4_vi_total;
            $i_total_raw_points = $q1_i_total + $q2_i_total + $q3_i_total + $q4_i_total;
            $mi_total_raw_points =  $q1_mi_total + $q2_mi_total + $q3_mi_total + $q4_mi_total;
            $si_total_raw_points = $q1_si_total + $q2_si_total + $q3_si_total + $q4_si_total;
            $nai_total_raw_points = $q1_nai_total + $q2_nai_total + $q3_nai_total + $q4_nai_total;
            $i_total_raw_points = $vi_total_raw_points + $i_total_raw_points + $mi_total_raw_points +  $si_total_raw_points +  $nai_total_raw_points;           

            $vi_grand_total_raw_points += $vi_total_raw_points;
            $s_grand_total_raw_points +=  $i_total_raw_points;
            $misinai_grand_total_raw_points +=  $mi_total_raw_points + $si_total_raw_points + $nai_total_raw_points;
            $i_grand_total_raw_points+= $total_raw_points;

            $i_trp_totals[$dimensionId] = [
                'vi_total_raw_points' => $vi_total_raw_points,
                'i_total_raw_points' => $i_total_raw_points,
                'mi_total_raw_points' => $mi_total_raw_points,
                'si_total_raw_points' => $si_total_raw_points,
                'nai_total_raw_points' => $nai_total_raw_points,
                'i_total_raw_points' => $i_total_raw_points,
            ];

            //Part 1 Quarter 1 total scores
            $x_vi_total = $vi_total_raw_points * 5; 
            $x_i_total = $i_total_raw_points * 4; 
            $x_mi_total = $mi_total_raw_points * 3; 
            $x_si_total = $si_total_raw_points * 3; 
            $x_nai_total = $nai_total_raw_points * 1; 
            $x_i_total_score =  $x_vi_total +  $x_i_total +  $x_mi_total +  $x_si_total + $x_nai_total;
            $overall_total_scores += $x_i_total_score;
            
            $vi_grand_total_score += $x_vi_total ;
            $i_grand_total_score += $x_si_total ;
            $misinai_grand_total_score += $x_mi_total +  $x_si_total + $x_nai_total ;

            $i_total_scores[$dimensionId] = [ 
                'x_vi_total' => $x_vi_total,
                'x_i_total' => $x_i_total,
                'x_mi_total' => $x_mi_total,
                'x_si_total' => $x_si_total,
                'x_nai_total' => $x_nai_total, 
                'x_i_total_score' => $x_i_total_score,
            ];

        
        }

        //ALL quarters Total No. of Very Satisfied (VS) Responses of First Quarte
        $q1_total_vs_respondents = $q1_date_range->where('rate_score',5)->groupBy('customer_id')->count();
        $q2_total_vs_respondents = $q2_date_range->where('rate_score',5)->groupBy('customer_id')->count();
        $q3_total_vs_respondents = $q3_date_range->where('rate_score',5)->groupBy('customer_id')->count();
        $q4_total_vs_respondents = $q4_date_range->where('rate_score',5)->groupBy('customer_id')->count();

        // Total No. of Satisfied (S) Responses
        $q1_total_s_respondents = $q1_date_range->where('rate_score',4)->groupBy('customer_id')->count();
        $q2_total_s_respondents = $q2_date_range->where('rate_score',4)->groupBy('customer_id')->count();
        $q3_total_s_respondents = $q3_date_range->where('rate_score',4)->groupBy('customer_id')->count();
        $q4_total_s_respondents = $q4_date_range->where('rate_score',4)->groupBy('customer_id')->count();

        // Total No. of Other (N, D, VD) Responses
        $q1_total_ndvd_respondents = $q1_date_range->where('rate_score','<',4)->groupBy('customer_id')->count();
        $q2_total_ndvd_respondents = $q2_date_range->where('rate_score','<',4)->groupBy('customer_id')->count();
        $q3_total_ndvd_respondents = $q3_date_range->where('rate_score','<',4)->groupBy('customer_id')->count();
        $q4_total_ndvd_respondents = $q4_date_range->where('rate_score','<',4)->groupBy('customer_id')->count();
          
        // Total No. of All Responses
        $q1_total_ndvd_respondents = $q1_date_range->groupBy('customer_id')->count();
        $q2_total_ndvd_respondents = $q2_date_range->groupBy('customer_id')->count();
        $q3_total_ndvd_respondents = $q3_date_range->groupBy('customer_id')->count();
        $q4_total_ndvd_respondents = $q4_date_range->groupBy('customer_id')->count();
          

        //Total respondents /Customers
        $total_respondents = $date_range->groupBy('customer_id')->count();
        $q1_total_respondents = $q1_date_range->groupBy('customer_id')->count();
        $q2_total_respondents = $q2_date_range->groupBy('customer_id')->count();
        $q3_total_respondents = $q3_date_range->groupBy('customer_id')->count();
        $q4_total_respondents = $q4_date_range->groupBy('customer_id')->count();

        // total number of respondents/customer who rated VS/S
        $total_vss_respondents = $date_range->where('rate_score', '>','3')->groupBy('customer_id')->count();
        $q1_total_vss_respondents = $q1_date_range->where('rate_score', '>','3')->groupBy('customer_id')->count();
        $q2_total_vss_respondents = $q2_date_range->where('rate_score', '>','3')->groupBy('customer_id')->count();
        $q3_total_vss_respondents = $q3_date_range->where('rate_score', '>','3')->groupBy('customer_id')->count();
        $q4_total_vss_respondents = $q4_date_range->where('rate_score', '>','3')->groupBy('customer_id')->count();

        // total number of respondents/customer who rated VS
        $total_vs_respondents = $date_range->where('rate_score', '=','5')->groupBy('customer_id')->count();
        $q1_total_vs_respondents = $q1_date_range->where('rate_score', '=','5')->groupBy('customer_id')->count();
        $q2_total_vs_respondents = $q2_date_range->where('rate_score', '=','5')->groupBy('customer_id')->count();
        $q3_total_vs_respondents = $q3_date_range->where('rate_score', '=','5')->groupBy('customer_id')->count();
        $q4_total_vs_respondents = $q4_date_range->where('rate_score', '=','5')->groupBy('customer_id')->count();

        // total number of respondents/customer who rated S
        $total_s_respondents = $date_range->where('rate_score', '=','4')->groupBy('customer_id')->count();
        $q1_total_s_respondents = $q1_date_range->where('rate_score', '=','4')->groupBy('customer_id')->count();
        $q2_total_s_respondents = $q2_date_range->where('rate_score', '=','4')->groupBy('customer_id')->count();
        $q3_total_s_respondents = $q3_date_range->where('rate_score', '=','4')->groupBy('customer_id')->count();
        $q4_total_s_respondents = $q4_date_range->where('rate_score', '=','4')->groupBy('customer_id')->count();
    
        // Frst quarter total number of promoters or respondents who rated 7-10 in recommendation rating
        $total_promoters = $customer_recommendation_ratings->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        $q1_total_promoters = $q1_crr->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        $q2_total_promoters = $q2_crr->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        $q3_total_promoters = $q3_crr->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        $q4_total_promoters = $q4_crr->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        
        // total number of detractors or respondents who rated 0-6 in recommendation rating
        $total_detractors = $customer_recommendation_ratings->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
        $q1_total_detractors = $q1_crr->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
        $q2_total_detractors = $q2_crr->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
        $q3_total_detractors = $q3_crr->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();
        $q4_total_detractors = $q4_crr->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();

  
        //Percentage of Respondents/Customers who rated VS/S = total no. of respondents / total no. respondets who rated vs/s * 100
        $percentage_vss_respondents  = 0;
        if($total_respondents != 0){
            $percentage_vss_respondents  = ($total_respondents/$total_vss_respondents) * 100;
        }
        $percentage_vss_respondents = number_format( $percentage_vss_respondents , 2);

        $customer_satisfaction_rating = 0;
        if($total_vss_respondents != 0){
            $customer_satisfaction_rating = ($total_vss_respondents/$total_vss_respondents) * 100;
        }
        $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);

        //Percentage of Promoters = number of promoters / total respondents
        $percentage_promoters = 0;
        //  Percentage promoters
        $q1_percentage_promoters = 0.00;
        $q2_percentage_promoters = 0.00;
        $q3_percentage_promoters = 0.00;
        $q4_percentage_promoters = 0.00;

        // Percentage of promoter
        $q1_percentage_detractors = 0.00;
        $q2_percentage_detractors = 0.00;
        $q3_percentage_detractors = 0.00;
        $q4_percentage_detractors = 0.00;

        // Net Promoter Score
        $q1_net_promoter_score =  0.00;
        $q2_net_promoter_score = 0.00;
        $q3_net_promoter_score =  0.00;
        $q4_net_promoter_score =  0.00;

        // average
        $ave_net_promoter_score = 0.00;
        $average_percentage_promoters =  0.00;
        $average_percentage_detractors =  0.00;

        if($total_respondents != 0){
            $percentage_promoters = number_format((($total_promoters / $total_respondents) * 100), 2);
            $q1_percentage_promoters = number_format((($q1_total_promoters / $total_respondents) * 100), 2);
            $q2_percentage_promoters = number_format((($q2_total_promoters / $total_respondents) * 100), 2);
            $q3_percentage_promoters = number_format((($q3_total_promoters / $total_respondents) * 100), 2);
            $q4_percentage_promoters = number_format((($q4_total_promoters / $total_respondents) * 100), 2);
        
            //Percentage of Promoters = number of promoters / total respondents
            $percentage_detractors = number_format((($total_detractors / $total_respondents) * 100),2);
            $q1_percentage_detractors = number_format((($q1_total_detractors / $total_respondents) * 100),2);
            $q2_percentage_detractors = number_format((($q2_total_detractors / $total_respondents) * 100),2);
            $q3_percentage_detractors = number_format((($q3_total_detractors / $total_respondents) * 100),2);       
            $q4_percentage_detractors = number_format((($q4_total_detractors / $total_respondents) * 100),2);
            
            // Net Promotion Scores(NPS) = Percentage of Promoters−Percentage of Detractors
            $q1_net_promoter_score =  number_format(($q1_percentage_detractors - $q1_percentage_detractors),2);
            $q2_net_promoter_score =  number_format(($q2_percentage_detractors - $q2_percentage_detractors),2);
            $q3_net_promoter_score =  number_format(($q3_percentage_detractors - $q3_percentage_detractors),2);
            $q4_net_promoter_score =  number_format(($q4_percentage_detractors - $q4_percentage_detractors),2);

            $ave_net_promoter_score =  number_format((($q1_net_promoter_score + $q2_net_promoter_score + $q3_net_promoter_score + $q4_net_promoter_score)/ 4),2);
            $average_percentage_promoters =  number_format((($q1_percentage_promoters + $q2_percentage_promoters + $q3_percentage_promoters + $q4_percentage_promoters)/ 4),2);
            $average_percentage_detractors =  number_format((($q1_percentage_detractors + $q2_percentage_detractors + $q3_percentage_detractors + $q4_percentage_detractors)/ 4),2);

            // CSAT = ((Total No. of Very Satisfied (VS) Responses + Total No. of Satisfied (S) Responses) / grand total respondents) * 100
            $customer_satisfaction_rating = 0;
            if($total_vss_respondents != 0){
                $customer_satisfaction_rating = (($total_vss_respondents / $total_respondents)) * 100;
            }
            $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);
        }


        // get Yearly CSI
        $jan_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 1);
        $feb_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 2);
        $mar_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 3);

        $q1_csi = 0;
        $q1_csi =  $jan_csi +  $feb_csi + $mar_csi;

        $apr_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 4);
        $may_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 5);
        $jun_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 6);
        
        $q2_csi = 0;
        $q2_csi =  $apr_csi +  $may_csi + $jun_csi;

        $jul_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 7);
        $aug_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 8);
        $sep_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 9);

        $q3_csi = 0;
        $q3_csi =  $jul_csi +  $aug_csi + $sep_csi;

        $oct_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 10);
        $nov_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 11);
        $dec_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 12);

        $q4_csi = 0;
        $q4_csi =  $oct_csi +  $nov_csi + $dec_csi;

        $customer_satisfaction_index = number_format((($q1_csi + $q2_csi +  $q3_csi + $q4_csi)/4), 2);

         //comments and  complaints
         $comment_list = CustomerComment::whereIn('customer_id', $customer_ids)
                                        ->whereYear('created_at', $request->selected_year)->get();

        $comments = $comment_list->where('comment','!=','')->pluck('comment'); 

        $total_comments = $comment_list->where('comment','!=','')->count();
        $total_complaints = $comment_list->where('is_complaint',1)->count();

        //Respondents list
        $data = CARResource::collection($respondents_list);

        //send response to front end
        return Inertia::render('CSI/Index')
            ->with('sub_unit', $sub_unit)
            ->with('unit_pstos', $unit_pstos)
            ->with('sub_unit_pstos', $sub_unit_pstos)
            ->with('sub_unit_types', $sub_unit_types)
            ->with('dimensions', $dimensions)
            ->with('service', $request->service)
            ->with('unit', $request->unit)
            ->with('respondents_list',$data)
            ->with('vs_totals', $vs_totals)
            ->with('s_totals', $s_totals)
            ->with('n_totals', $n_totals)
            ->with('d_totals', $d_totals)
            ->with('vd_totals', $vd_totals)
            ->with('grand_totals', $grand_totals)
            ->with('trp_totals', $trp_totals)
            ->with('grand_total_raw_points', $grand_total_raw_points)
            ->with('vs_grand_total_raw_points', $vs_grand_total_raw_points)
            ->with('s_grand_total_raw_points', $s_grand_total_raw_points)
            ->with('ndvd_grand_total_raw_points', $ndvd_grand_total_raw_points)
            ->with('p1_total_scores', $p1_total_scores)
            ->with('vs_grand_total_score', $vs_grand_total_score) 
            ->with('s_grand_total_score', $s_grand_total_score) 
            ->with('ndvd_grand_total_score', $ndvd_grand_total_score) 
            ->with('grand_total_score', $grand_total_score) 
            ->with('lsr_totals', $lsr_totals)
            ->with('lsr_grand_total', $lsr_grand_total)
            ->with('lsr_average', $lsr_average ) 
            ->with('q1_total_vs_respondents', $q1_total_vs_respondents)
            ->with('q2_total_vs_respondents', $q2_total_vs_respondents)
            ->with('q3_total_vs_respondents', $q3_total_vs_respondents)
            ->with('q4_total_vs_respondents', $q4_total_vs_respondents)
            ->with('q1_total_s_respondents', $q1_total_s_respondents)
            ->with('q2_total_s_respondents', $q2_total_s_respondents)
            ->with('q3_total_s_respondents', $q3_total_s_respondents)
            ->with('q4_total_s_respondents', $q4_total_s_respondents)
            ->with('q1_total_ndvd_respondents', $q1_total_ndvd_respondents)
            ->with('q2_total_ndvd_respondents', $q2_total_ndvd_respondents)
            ->with('q3_total_ndvd_respondents', $q3_total_ndvd_respondents)
            ->with('q4_total_ndvd_respondents', $q4_total_ndvd_respondents)
            ->with('q1_total_respondents', $q1_total_respondents)
            ->with('q2_total_respondents', $q2_total_respondents)
            ->with('q3_total_respondents', $q3_total_respondents)
            ->with('q4_total_respondents', $q4_total_respondents)
            ->with('total_respondents', $total_respondents)
            ->with('q1_total_vss_respondents', $q1_total_vss_respondents)
            ->with('q2_total_vss_respondents', $q2_total_vss_respondents)
            ->with('q3_total_vss_respondents', $q3_total_vss_respondents)
            ->with('q4_total_vss_respondents', $q4_total_vss_respondents)
            ->with('total_vss_respondents', $total_vss_respondents)
            ->with('percentage_vss_respondents', $percentage_vss_respondents)
            ->with('total_promoters', $total_promoters)
            ->with('total_detractors', $total_detractors)
            ->with('vi_totals', $vi_totals)
            ->with('i_totals', $i_totals)
            ->with('mi_totals', $mi_totals)
            ->with('si_totals', $si_totals)
            ->with('nai_totals', $nai_totals)
            ->with('i_grand_totals', $i_grand_totals)
            ->with('i_trp_totals', $i_trp_totals)
            ->with('i_grand_total_raw_points', $i_grand_total_raw_points)
            ->with('vi_grand_total_raw_points', $vi_grand_total_raw_points)
            ->with('s_grand_total_raw_points', $s_grand_total_raw_points)
            ->with('misinai_grand_total_raw_points', $misinai_grand_total_raw_points)
            ->with('i_total_scores', $i_total_scores)
            ->with('vi_grand_total_score', $vi_grand_total_score) 
            ->with('i_grand_total_score', $i_grand_total_score) 
            ->with('misinai_grand_total_score', $misinai_grand_total_score)
            ->with('percentage_promoters', $percentage_promoters)
            ->with('q1_percentage_promoters', $q1_percentage_promoters)
            ->with('q2_percentage_promoters', $q2_percentage_promoters)
            ->with('q3_percentage_promoters', $q3_percentage_promoters)
            ->with('q4_percentage_promoters', $q4_percentage_promoters)
            ->with('average_percentage_promoters', $average_percentage_promoters)
            ->with('q1_percentage_detractors', $q1_percentage_detractors)
            ->with('q2_percentage_detractors', $q2_percentage_detractors)
            ->with('q3_percentage_detractors', $q3_percentage_detractors) 
            ->with('q4_percentage_detractors', $q4_percentage_detractors) 
            ->with('average_percentage_detractors', $average_percentage_detractors)
            ->with('q1_net_promoter_score', $q1_net_promoter_score)
            ->with('q2_net_promoter_score', $q2_net_promoter_score)
            ->with('q3_net_promoter_score', $q3_net_promoter_score)
            ->with('q4_net_promoter_score', $q4_net_promoter_score)
            ->with('ave_net_promoter_score', $ave_net_promoter_score)
            ->with('customer_satisfaction_rating', $customer_satisfaction_rating)
            ->with('q1_csi', $q1_csi)
            ->with('q2_csi', $q2_csi)
            ->with('q3_csi', $q3_csi)
            ->with('q4_csi', $q4_csi)
            ->with('csi', $customer_satisfaction_index)
            ->with('total_comments', $total_comments)
            ->with('total_complaints', $total_complaints)
            ->with('comments', $comments);
    }

    
    public function getSubUnit($request)
    {
         //get unit pstos
         $sub_unit = SubUnit::where('id',$request->selected_sub_unit)->get();
         return $sub_unit;
    
    }

    public function getUnitPSTOs($request)
    {
         //get unit pstos
         $unit_pstos = UnitPsto::where('unit_id',$request->unit)->get();
         $unit_pstos = UnitPSTOResource::collection($unit_pstos);
   
         $unit_pstos = $unit_pstos->pluck('psto');

         return $unit_pstos;
    
    }

    public function getSubUnitPSTOs($request)
    {
        //get sub unit pstos
 
       $sub_unit_pstos = SubUnitPsto::where('sub_unit_id', $request->selected_sub_unit)->get(); 
       $sub_unit_pstos = SubUnitPSTOResource::collection($sub_unit_pstos);
 
       $sub_unit_pstos = $sub_unit_pstos->pluck('psto');

       return $sub_unit_pstos;
    
    }

    public function getSubUnitTypes($request)
    {
        //get sub unit pstos
 
       $sub_unit_types = SubUnitType::where('sub_unit_id', $request->selected_sub_unit)->get(); 

       return $sub_unit_types;
    
    }

    public function querySearchCSF($region_id, $service_id, $unit_id , $sub_unit_id , $psto_id, $client_type, $sub_unit_type )
    {
        $customer_ids = CSFForm::when($region_id, function ($query, $region_id) {
            $query->where('region_id', $region_id);
        })->when($service_id, function ($query, $service_id) {
            $query->where('service_id', $service_id);
        })->when($unit_id, function ($query, $unit_id) {
            $query->where('unit_id', $unit_id);
        })->when($sub_unit_id, function ($query, $sub_unit_id) {
            $query->where('sub_unit_id', $sub_unit_id);
        })->when($psto_id, function ($query, $psto_id) {
            $query->where('psto_id', $psto_id);
        })->when($client_type, function ($query, $client_type) {
            if($client_type == "Internal"){
                $query->where('client_type', "Internal Employees");
            }
            else if($client_type == "External"){
                $query->where('client_type', "General Public")
                      ->orWhere('client_type', "Government Employees")
                      ->orWhere('client_type', "Business/Organization");
            }        
        })->when($sub_unit_type, function ($query, $sub_unit_type) {
            $query->where('sub_unit_type', $sub_unit_type['type_name']);
       })->select('customer_id')   // select all customers id to find other data for customer satifaction index report
        ->get();

        return  $customer_ids;
    
    }


    public function getMonthlyCSI($request, $region_id, $psto_id, $month)
    {

        $service_id = $request->service['id'];
        $unit_id = $request->unit_id;
        $sub_unit_id = $request->selected_sub_unit;
        $client_type = $request->client_type; 
        $sub_unit_type = $request->sub_unit_type; 

       // search and check list of forms query  
       $customer_ids = $this->querySearchCSF( $region_id, $service_id, $unit_id ,$sub_unit_id , $psto_id, $client_type, $sub_unit_type );
    
        $date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                             ->whereMonth('created_at', $month)->get();
           
        // Dimensions or attributes
        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();

        // total number of respondents/customer
        $total_respondents = $date_range->groupBy('customer_id')->count();

        // total number of respondents/customer who rated VS/S
        $total_vss_respondents = $date_range->where('rate_score', '>','3')->groupBy('customer_id')->count();

        $ilsr_grand_total =0;
        // loop for getting importance ls rating grand total for ws rating calculation
        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            $vi_total = $date_range->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->count();
            $i_total = $date_range->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->count();
            $mi_total = $date_range->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->count();
            $li_total = $date_range->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->count();
            $nai_total = $date_range->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->count();

            $x_vi_total = $vi_total * 5; 
            $x_i_total = $i_total * 4; 
            $x_mi_total = $mi_total * 3; 
            $x_li_total = $li_total * 2; 
            $x_nai_total = $nai_total * 1;
            $x_importance_total = $x_vi_total + $x_i_total + $x_mi_total + $x_li_total + $x_nai_total  ; 

            // Importance Likert Scale RAting 
            if($x_importance_total != 0){
                $ilsr_total = $x_importance_total / $total_respondents;
                $ilsr_grand_total =  $ilsr_grand_total + $ilsr_total;
            }

        }

        // PART I : CUSTOMER RATING OF SERVICE QUALITY 

        //set initial value of buttom side total scores
        $y_totals = [];
        $grand_vs_total = 0;
        $grand_s_total = 0;
        $grand_n_total = 0;
        $grand_vd_total = 0;
        $grand_d_total = 0;
        $grand_total = 0;
        
        //set initial value of right side total scores
        $x_vs_total = 0; 
        $x_s_total = 0; 
        $x_n_total = 0; 
        $x_d_total = 0; 
        $x_vd_total = 0; 
        $x_grand_total = 0 ; 

        $likert_scale_rating_totals = [];
        $lsr_total= 0;
        $lsr_grand_total= 0;

         // PART II : IMPORTANCE OF THIS ATTRIBUTE 

        //set importance rating score 
        $importance_rate_score_totals = [];
        $x_importance_totals = [];
        $x_importance_total=0; 

        $x_vi_total = 0; 
        $x_i_total =0; 
        $x_mi_total =0; 
        $x_li_total = 0; 
        $x_nai_total = 0;

        $importance_ilsr_totals = [];
        $ilsr_total = 0;

        $gap_totals = [];
        $gap_total = 0 ;
        $gap_grand_total=0;
        $ss_total= 0;
        $ss_totals = [];
        $wf_total= 0;
        $wf_totals = [];
        $ws_total= 0;
        $ws_totals = [];
        $ws_grand_total = 0;

        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            //PART I
            $vs_total = $date_range->where('rate_score', 5)->where('dimension_id', $dimensionId)->count();
            $s_total = $date_range->where('rate_score', 4)->where('dimension_id', $dimensionId)->count();
            $n_total = $date_range->where('rate_score', 3)->where('dimension_id', $dimensionId)->count();
            $d_total = $date_range->where('rate_score', 2)->where('dimension_id', $dimensionId)->count();
            $vd_total = $date_range->where('rate_score', 1)->where('dimension_id', $dimensionId)->count();          
       
            $x_vs_total = $vs_total * 5; 
            $x_s_total = $s_total * 4; 
            $x_n_total = $n_total * 3; 
            $x_d_total = $d_total * 2; 
            $x_vd_total = $vd_total * 1; 
            $x_grand_total = $x_vs_total + $x_s_total + $x_n_total + $x_d_total + $x_vd_total  ; 
         
            // right side total score divided by total repondents or customers
            if($x_grand_total != 0){
                $lsr_total = $x_grand_total / $total_respondents;
            }
           
            // SS = lsr with 3 decimals
            $ss_total = number_format($lsr_total, 3);
            $ss_totals[$dimensionId] = [
                'ss_total' => $ss_total,
            ];

            //likert sclae rating grandtotal

            $lsr_grand_total =  $lsr_grand_total + $lsr_total;
            $x_totals[$dimensionId] = [
                'x_total_score' => $x_grand_total,
            ];

            $lsr_total = number_format($lsr_total, 2);

            $likert_scale_rating_totals[$dimensionId] = [
                'lsr_total' => $lsr_total,
            ];

            $y_totals[$dimensionId] = [
                'vs_total' => $vs_total,
                's_total' => $s_total,
                'n_total' => $n_total,
                'd_total' => $d_total,
                'vd_total' => $vd_total,
            ];

            $grand_vs_total+=$vs_total;
            $grand_s_total+=$s_total;
            $grand_n_total+=$n_total;
            $grand_d_total+=$d_total;
            $grand_vd_total+=$vd_total;       
                     
            // PART II
            $vi_total = $date_range->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->count();
            $i_total = $date_range->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->count();
            $mi_total = $date_range->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->count();
            $li_total = $date_range->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->count();
            $nai_total = $date_range->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->count();
        
            $importance_rate_score_totals[$dimensionId] = [
                'vi_total' => $vi_total,
                'i_total' => $i_total,
                'mi_total' => $mi_total,
                'li_total' => $li_total,
                'nai_total' => $nai_total,
            ];

            $x_vi_total = $vi_total * 5; 
            $x_i_total = $i_total * 4; 
            $x_mi_total = $mi_total * 3; 
            $x_li_total = $li_total * 2; 
            $x_nai_total = $nai_total * 1;
            $x_importance_total = $x_vi_total + $x_i_total + $x_mi_total + $x_li_total + $x_nai_total  ; 
            
            //right side total importance rate scores 
            $x_importance_totals[$dimensionId] = [
                'x_importance_total_score' => $x_importance_total,
            ];
            
            // Likert Scale RAting 
            if($x_importance_total != 0){
                $ilsr_total = $x_importance_total / $total_respondents;
            }
            $ilsr_total = number_format($ilsr_total, 2);

            $importance_ilsr_totals[$dimensionId] = [
                'ilsr_total' => $ilsr_total,
            ];
 
            // GAP = attributes total score minus importance of attributes total score
            $gap_total=  $ilsr_total - $lsr_total;
            $gap_total = number_format($gap_total, 2);

            $gap_totals[$dimensionId] = [
                'gap_total' => $gap_total,
            ];

            $gap_grand_total += $gap_total;
            $gap_grand_total = number_format($gap_grand_total, 2);

            // WF = (importance LS Rating divided by importance grand total  of ls rating) * 100
            if($ilsr_total != 0){
                $wf_total =  ($ilsr_total / $ilsr_grand_total) * 100;
            }
            $wf_total = number_format($wf_total, 2);
            $wf_totals[$dimensionId] = [
                'wf_total' => $wf_total,
            ];

            // WS = (SS * WF) / 100  
            $ws_total = ($ss_total * $wf_total) / 100;   
            $ws_grand_total = $ws_grand_total + $ws_total;
            $ws_total = number_format($ws_total, 2);
            $ws_grand_total = number_format($ws_grand_total, 2);
            $ws_totals[$dimensionId] = [
                'ws_total' => $ws_total,
            ];
        }

        // round off Likert Scale Rating grand total and control decimal to 2 
        $lsr_grand_total = number_format($lsr_grand_total, 2);      

        // table below total score
        $grand_vs_total =   $grand_vs_total * 5;
        $grand_s_total =   $grand_s_total * 5;
        $grand_n_total =   $grand_n_total * 5;
        $grand_d_total =   $grand_d_total * 5;
        $grand_vd_total =   $grand_vd_total * 5;

        $x_grand_total =  $grand_vs_total +  $grand_s_total + $grand_n_total +  $grand_d_total +   $grand_vd_total;

        //Percentage of Respondents/Customers who rated VS/S: 
        // = total no. of respondents / total no. respondets who rated vs/s * 100
        $percentage_vss_respondents  = 0;
        if($total_respondents != 0){
            $percentage_vss_respondents  = ($total_respondents/$total_vss_respondents) * 100;
        }
        $percentage_vss_respondents = number_format( $percentage_vss_respondents , 2);

        $customer_satisfaction_rating = 0;
        if($total_vss_respondents != 0){
            $customer_satisfaction_rating = ($total_vss_respondents/$total_vss_respondents) * 100;
        }
        $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);

        // Customer Satisfaction Index (CSI) = (ws grand total / 5) * 100
        $customer_satisfaction_index = 0;
        if($ws_grand_total != 0){
            $customer_satisfaction_index = ($ws_grand_total/5) * 100;
        }
        $customer_satisfaction_index = number_format( $customer_satisfaction_index , 2);

        return $customer_satisfaction_index;
    }   


    // all services and its units view page
    public function all_units()
    {
        //$user = Auth::user();
        $service_units = Services::all();

        //all Services and its units
        $data = ServiceResource::collection($service_units);
        return Inertia::render('CSI/AllServicesUnits/Index')
            ->with('service_units', $data);
    
    }


    public function generateAllUnitReports(Request $request)
    {
       
        if($request->csi_type == 'By Date'){
            return $this->generateCSIAllUnitByDate($request );
        }
        else if($request->csi_type == "By Month"){
            return $this->generateCSIAllUnitMonthly($request);
        }
        else if($request->csi_type == "By Quarter"){
            if($request->selected_quarter == "FIRST QUARTER"){
                return $this->generateCSIAllUnitFirstQuarter($request);
            }
            else if($request->selected_quarter == "SECOND QUARTER"){
                return $this->generateCSIAllUnitSecondQuarter($request);
            }
            else if($request->selected_quarter == "THIRD QUARTER"){
                return $this->generateCSIAllUnitThirdQuarter($request);
            }
            else if($request->selected_quarter == "FOURTH QUARTER"){
                return $this->generateCSIAllUnitFourthQuarter($request);
            }
          
        }
        else if($request->csi_type == "By Year/Annual"){
            return $this->generateCSIAllUnitYearly($request);  
        }
    
    }

    public function generateCSIAllUnitMonthly($request)
    {
        //get user
        $user = Auth::user();

        // search and check list of forms query  
        $customer_ids = $this->querySearchCSF( $region_id, $service_id, $unit_id ,$sub_unit_id , $psto_id, $client_type, $sub_unit_type );
        $numeric_month = Carbon::parse("1 {$request->selected_month}")->format('m');
        $date_range = CustomerAttributeRating::whereMonth('created_at', $numeric_month)
                                            ->whereYear('created_at', $request->selected_year)->get();

        $customer_recommendation_ratings = CustomerRecommendationRating::whereMonth('created_at', $numeric_month)
                                                                        ->whereYear('created_at', $request->selected_year)->get();

        // total number of respondents/customer
        $total_respondents = $date_range->groupBy('customer_id')->count();

        // total number of respondents/customer who rated VS/S
        $total_vss_respondents = $date_range->where('rate_score', '>','3')->groupBy('customer_id')->count();

        //Percentage of Respondents who rated VS and S
        $percentage_vss_respondents  = 0;
        if($total_respondents != 0){
            $percentage_vss_respondents  = ($total_respondents/$total_vss_respondents) * 100;
        }
        $percentage_vss_respondents = number_format( $percentage_vss_respondents , 2);

        //Customer Satisfaction Rating
        $customer_satisfaction_rating = 0;
        if($total_vss_respondents != 0){
            $customer_satisfaction_rating = ($total_vss_respondents/$total_vss_respondents) * 100;
        }
        $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);

        //Customer Satisfaction Index(CSI)
        $customer_satisfaction_index = $this->getAllUnitMonthlyCSI($request, $user->region_id,  $date_range);

        //Net Promoter Score
        // total number of promoters or respondents who rated 9-10 in recommendation rating
        $total_promoters = $customer_recommendation_ratings->where('recommend_rate_score', '>','8')->groupBy('customer_id')->count();

        // total number of detractors or respondents who rated 0-6 in recommendation rating
        $total_detractors = $customer_recommendation_ratings->where('recommend_rate_score', '<','7')->groupBy('customer_id')->count();

        //Percentage of Promoters = number of promoters / total respondents
        $percentage_promoters = 0;
        if($total_promoters != 0){
            $percentage_promoters = number_format((($total_promoters / $total_respondents) * 100), 2);
        }

        //Percentage of Promoters = number of promoters / total respondents
        $percentage_detractors = 0;
        if($total_detractors != 0){
            $percentage_detractors = number_format((($total_detractors / $total_respondents) * 100),2);
        }

        // Net Promotion Scores(NPS) = Percentage of Promoters−Percentage of Detractors
        $net_promotion_score =  number_format(($percentage_promoters - $percentage_detractors),2);

        //Likert Scale Rating(Attribute Average)

    }

    public function getAllUnitMonthlyCSI($request, $region_id,  $date_range)
    {        
        // Dimensions or attributes
        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();

        // total number of respondents/customer
        $total_respondents = $date_range->groupBy('customer_id')->count();

        // total number of respondents/customer who rated VS/S
        $total_vss_respondents = $date_range->where('rate_score', '>','3')->groupBy('customer_id')->count();

        $ilsr_grand_total =0;
        // loop for getting importance ls rating grand total for ws rating calculation
        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            $vi_total = $date_range->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->count();
            $i_total = $date_range->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->count();
            $mi_total = $date_range->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->count();
            $li_total = $date_range->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->count();
            $nai_total = $date_range->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->count();

            $x_vi_total = $vi_total * 5; 
            $x_i_total = $i_total * 4; 
            $x_mi_total = $mi_total * 3; 
            $x_li_total = $li_total * 2; 
            $x_nai_total = $nai_total * 1;
            $x_importance_total = $x_vi_total + $x_i_total + $x_mi_total + $x_li_total + $x_nai_total  ; 

            // Importance Likert Scale RAting 
            if($x_importance_total != 0){
                $ilsr_total = $x_importance_total / $total_respondents;
                $ilsr_grand_total =  $ilsr_grand_total + $ilsr_total;
            }

        }

        // PART I : CUSTOMER RATING OF SERVICE QUALITY 

        //set initial value of buttom side total scores
        $y_totals = [];
        $grand_vs_total = 0;
        $grand_s_total = 0;
        $grand_n_total = 0;
        $grand_vd_total = 0;
        $grand_d_total = 0;
        $grand_total = 0;
        
        //set initial value of right side total scores
        $x_vs_total = 0; 
        $x_s_total = 0; 
        $x_n_total = 0; 
        $x_d_total = 0; 
        $x_vd_total = 0; 
        $x_grand_total = 0 ; 

        $likert_scale_rating_totals = [];
        $lsr_total= 0;
        $lsr_grand_total= 0;

         // PART II : IMPORTANCE OF THIS ATTRIBUTE 

        //set importance rating score 
        $importance_rate_score_totals = [];
        $x_importance_totals = [];
        $x_importance_total=0; 

        $x_vi_total = 0; 
        $x_i_total =0; 
        $x_mi_total =0; 
        $x_li_total = 0; 
        $x_nai_total = 0;

        $importance_ilsr_totals = [];
        $ilsr_total = 0;

        $gap_totals = [];
        $gap_total = 0 ;
        $gap_grand_total=0;
        $ss_total= 0;
        $ss_totals = [];
        $wf_total= 0;
        $wf_totals = [];
        $ws_total= 0;
        $ws_totals = [];
        $ws_grand_total = 0;

        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            //PART I
            $vs_total = $date_range->where('rate_score', 5)->where('dimension_id', $dimensionId)->count();
            $s_total = $date_range->where('rate_score', 4)->where('dimension_id', $dimensionId)->count();
            $n_total = $date_range->where('rate_score', 3)->where('dimension_id', $dimensionId)->count();
            $d_total = $date_range->where('rate_score', 2)->where('dimension_id', $dimensionId)->count();
            $vd_total = $date_range->where('rate_score', 1)->where('dimension_id', $dimensionId)->count();          
       
            $x_vs_total = $vs_total * 5; 
            $x_s_total = $s_total * 4; 
            $x_n_total = $n_total * 3; 
            $x_d_total = $d_total * 2; 
            $x_vd_total = $vd_total * 1; 
            $x_grand_total = $x_vs_total + $x_s_total + $x_n_total + $x_d_total + $x_vd_total  ; 
         
            // right side total score divided by total repondents or customers
            if($x_grand_total != 0){
                $lsr_total = $x_grand_total / $total_respondents;
            }
           
            // SS = lsr with 3 decimals
            $ss_total = number_format($lsr_total, 3);
            $ss_totals[$dimensionId] = [
                'ss_total' => $ss_total,
            ];

            //likert sclae rating grandtotal

            $lsr_grand_total =  $lsr_grand_total + $lsr_total;
            $x_totals[$dimensionId] = [
                'x_total_score' => $x_grand_total,
            ];

            $lsr_total = number_format($lsr_total, 2);

            $likert_scale_rating_totals[$dimensionId] = [
                'lsr_total' => $lsr_total,
            ];

            $y_totals[$dimensionId] = [
                'vs_total' => $vs_total,
                's_total' => $s_total,
                'n_total' => $n_total,
                'd_total' => $d_total,
                'vd_total' => $vd_total,
            ];

            $grand_vs_total+=$vs_total;
            $grand_s_total+=$s_total;
            $grand_n_total+=$n_total;
            $grand_d_total+=$d_total;
            $grand_vd_total+=$vd_total;       
                     
            // PART II
            $vi_total = $date_range->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->count();
            $i_total = $date_range->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->count();
            $mi_total = $date_range->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->count();
            $li_total = $date_range->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->count();
            $nai_total = $date_range->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->count();
        
            $importance_rate_score_totals[$dimensionId] = [
                'vi_total' => $vi_total,
                'i_total' => $i_total,
                'mi_total' => $mi_total,
                'li_total' => $li_total,
                'nai_total' => $nai_total,
            ];

            $x_vi_total = $vi_total * 5; 
            $x_i_total = $i_total * 4; 
            $x_mi_total = $mi_total * 3; 
            $x_li_total = $li_total * 2; 
            $x_nai_total = $nai_total * 1;
            $x_importance_total = $x_vi_total + $x_i_total + $x_mi_total + $x_li_total + $x_nai_total  ; 
            
            //right side total importance rate scores 
            $x_importance_totals[$dimensionId] = [
                'x_importance_total_score' => $x_importance_total,
            ];
            
            // Likert Scale RAting 
            if($x_importance_total != 0){
                $ilsr_total = $x_importance_total / $total_respondents;
            }
            $ilsr_total = number_format($ilsr_total, 2);

            $importance_ilsr_totals[$dimensionId] = [
                'ilsr_total' => $ilsr_total,
            ];
 
            // GAP = attributes total score minus importance of attributes total score
            $gap_total=  $ilsr_total - $lsr_total;
            $gap_total = number_format($gap_total, 2);

            $gap_totals[$dimensionId] = [
                'gap_total' => $gap_total,
            ];

            $gap_grand_total += $gap_total;
            $gap_grand_total = number_format($gap_grand_total, 2);

            // WF = (importance LS Rating divided by importance grand total  of ls rating) * 100
            if($ilsr_total != 0){
                $wf_total =  ($ilsr_total / $ilsr_grand_total) * 100;
            }
            $wf_total = number_format($wf_total, 2);
            $wf_totals[$dimensionId] = [
                'wf_total' => $wf_total,
            ];

            // WS = (SS * WF) / 100  
            $ws_total = ($ss_total * $wf_total) / 100;   
            $ws_grand_total = $ws_grand_total + $ws_total;
            $ws_total = number_format($ws_total, 2);
            $ws_grand_total = number_format($ws_grand_total, 2);
            $ws_totals[$dimensionId] = [
                'ws_total' => $ws_total,
            ];
        }

        // Customer Satisfaction Index (CSI) = (ws grand total / 5) * 100
        $customer_satisfaction_index = 0;
        if($ws_grand_total != 0){
            $customer_satisfaction_index = ($ws_grand_total/5) * 100;
        }
        $customer_satisfaction_index = number_format( $customer_satisfaction_index , 2);

        return $customer_satisfaction_index;
    }  

    
    


    
 
      
     

   



}
