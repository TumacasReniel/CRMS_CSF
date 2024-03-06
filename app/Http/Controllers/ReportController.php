<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Dimension;
use App\Models\CcQuestion;
use App\Models\CustomerAttributeRating;
use Illuminate\Http\Request;
use App\Http\Resources\CustomerAttributeRatings as CARResource;

class ReportController extends Controller
{
    public function index()
    {
        $cc_questions = CcQuestion::all();
        $dimensions = Dimension::all();
        
        return Inertia::render('CSI/Index')
            ->with('cc_questions', $cc_questions)
            ->with('dimensions', $dimensions);
    }

    public function generateCSI(Request $request)
    {
        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();

        $customer_attribute_ratings = CustomerAttributeRating::whereBetween('created_at', [$request->date_from, $request->date_to])
                                        ->get();

        $date_range = CustomerAttributeRating::whereBetween('created_at', [$request->date_from, $request->date_to])->get();
        
        // PART I
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

        $total_respondents = $date_range->groupBy('customer.id')->count();

        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            $vs_rate = $date_range->where('rate_score', 5)->where('dimension_id', $dimensionId);
            $s_rate = $date_range->where('rate_score', 4)->where('dimension_id', $dimensionId);
            $n_rate = $date_range->where('rate_score', 3)->where('dimension_id', $dimensionId);
            $d_rate = $date_range->where('rate_score', 2)->where('dimension_id', $dimensionId);
            $vd_rate = $date_range->where('rate_score', 1)->where('dimension_id', $dimensionId);
            
            $vs_total = $vs_rate->count();
            $s_total = $s_rate ->count();
            $n_total = $n_rate->count();
            $d_total = $d_rate->count();
            $vd_total = $vd_rate->count();
       
            $x_vs_total = $vs_total * 5; 
            $x_s_total = $s_total * 4; 
            $x_n_total = $n_total * 3; 
            $x_d_total = $d_total * 2; 
            $x_vd_total = $vd_total * 1; 
            $x_grand_total = $x_vs_total + $x_s_total + $x_n_total + $x_d_total + $x_vd_total  ; 
         
            // right side total score divided by total repondents or customers
            $lsr_total = $x_grand_total / $total_respondents;
            $lsr_grand_total =  $lsr_grand_total + $lsr_total;

            $x_totals[$dimensionId] = [
                'x_total_score' => $x_grand_total,
            ];

            $lsr_total = round($lsr_total, 2);

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
        }

        // round off Likert Scale Rating grand total and control decimal to 2 
        $lsr_grand_total = round($lsr_grand_total, 2);      

        // table below total score
        $grand_vs_total =   $grand_vs_total * 5;
        $grand_s_total =   $grand_s_total * 5;
        $grand_n_total =   $grand_n_total * 5;
        $grand_d_total =   $grand_d_total * 5;
        $grand_vd_total =   $grand_vd_total * 5;

        $x_grand_total =  $grand_vs_total +  $grand_s_total + $grand_n_total +  $grand_d_total +   $grand_vd_total;

        // PART II

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
        $ilsr_grand_total = 0;
        $ilsr__total = 0;

        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
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
            $ilsr_total = $x_importance_total / $total_respondents;
            $ilsr_grand_total =  $ilsr_grand_total + $ilsr_total;
            $ilsr_total = round($lsr_total, 2);

            $importance_ilsr_totals[$dimensionId] = [
                'ilsr_total' => $ilsr_total,
            ];

        }

        //send response to front end
        $data = CARResource::collection($customer_attribute_ratings);
    
        return Inertia::render('CSI/Index')
            ->with('dimensions', $dimensions)
            ->with('customer_attribute_ratings',$data)
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
            ->with('importance_ilsr_totals', $importance_ilsr_totals);
    }   

}
