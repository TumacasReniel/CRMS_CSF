<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Inertia\Inertia;
use App\Models\Services;
use App\Models\Dimension;
use Illuminate\Http\Request;
use App\Models\CustomerAttributeRating;
use App\Models\CustomerRecommendationRating;
use App\Http\Resources\CustomerAttributeRatings as CARResource;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dimensions = Dimension::all();
        $service = Services::findOrFail($request->service_id);
        $unit = Unit::findOrFail($request->unit_id);

        return Inertia::render('CSI/Index')
            ->with('dimensions', $dimensions)
            ->with('service', $service)
            ->with('unit', $unit);
    
    }

    public function generateCSI(Request $request)
    {
        // dd($request->all());

        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();

        $date_range = CustomerAttributeRating::whereBetween('created_at', [$request->date_from, $request->date_to])->get();
        $customer_recommendation_ratings = CustomerRecommendationRating::whereBetween('created_at', [$request->date_from, $request->date_to])->get();

        // total number of respondents/customer
        $total_respondents = $date_range->groupBy('customer.id')->count();

        // total number of respondents/customer who rated VS/S
        $total_vss_respondents = $date_range->where('rate_score', '>','3')->groupBy('customer.id')->count();
        
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
        $percentage_vss_respondents  = 0;
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

        // List of Respondents/Customers
        $respondents_list = CustomerAttributeRating::whereBetween('created_at', [$request->date_from, $request->date_to])->get();

        $data = CARResource::collection($respondents_list);

        //send response to front end
        return Inertia::render('CSI/Index')
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
            ->with('net_promotion_score', $net_promotion_score)
            ->with('percentage_promoters', $percentage_promoters)
            ->with('percentage_detractors', $percentage_detractors);    
    }   

}
