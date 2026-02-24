<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\psto;
use App\Models\Unit;
use Inertia\Inertia;
use App\Models\Assignatorees;
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
use Illuminate\Support\Facades\DB;
use App\Models\CustomerAttributeRating;
use App\Models\CustomerComment;
use App\Models\CustomerCCRating;
use App\Models\User;
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
    private function getUnitData(Request $request, $withSubUnits = false)
    {
        $user = Auth::user();

        $query = Unit::where('id', $request->unit_id);
        if ($withSubUnits) {
            $query->with('sub_units');
        }
        $units = $query->get();
        $unit = UnitResource::collection($units);

        //get unit pstos
        $unit_pstos = UnitPSTO::where('unit_id', $request->unit_id)->get();
        $psto_ids = $unit_pstos->pluck('psto_id');
        $unit_pstos = psto::whereIn('id', $psto_ids)
                    ->where('region_id', $user->region_id)
                    ->get();

        //get sub unit pstos
        $sub_unit_pstos = SubUnitPSTO::where('sub_unit_id', $request->sub_unit_id)->get();
        $psto_ids = $sub_unit_pstos->pluck('psto_id');
        $sub_unit_pstos = psto::whereIn('id', $psto_ids)
                    ->where('region_id', $user->region_id)
                    ->get();

        $sub_unit_types = SubUnitType::where('sub_unit_id', $request->sub_unit_id)->get();

        $sub_unit = $request->sub_unit_id ? SubUnit::where('id', $request->sub_unit_id)->get() : collect();

        return [
            'unit' => $unit,
            'unit_pstos' => $unit_pstos,
            'sub_unit_pstos' => $sub_unit_pstos,
            'sub_unit_types' => $sub_unit_types,
            'sub_unit' => $sub_unit,
        ];
    }

    public function index(Request $request )
    {
        //get user
        $user = Auth::user();

        //get assignatoree list
        $assignatorees = Assignatorees::all();

        //get all users for prepared by dropdown
        $users = User::all();

        $dimensions = Dimension::all();
        $service = Services::findOrFail($request->service_id);

        $unitData = $this->getUnitData($request, true);

        return Inertia::render('CSI/Index')
            ->with('assignatorees', $assignatorees)
            ->with('dimensions', $dimensions)
            ->with('service', $service)
            ->with('unit', $unitData['unit'])
            ->with('unit_pstos', $unitData['unit_pstos'])
            ->with('sub_unit_pstos', $unitData['sub_unit_pstos'])
            ->with('sub_unit_types', $unitData['sub_unit_types'])
            ->with('user', $user)
            ->with('sub_unit', $unitData['sub_unit'])
            ->with('users', $users);
    }


    public function view(Request $request )
    {
        //get user
        $user = Auth::user();

        $dimensions = Dimension::all();
        $service = Services::findOrFail($request->service_id);

        $unitData = $this->getUnitData($request, true);

        return Inertia::render('Libraries/Service-Units/Views/View')
            ->with('dimensions', $dimensions)
            ->with('service', $service)
            ->with('unit', $unitData['unit'])
            ->with('unit_pstos', $unitData['unit_pstos'])
            ->with('sub_unit_pstos', $unitData['sub_unit_pstos'])
            ->with('sub_unit_types', $unitData['sub_unit_types'])
            ->with('user', $user);

    }


    public function generateReports(Request $request)
    {
        // Get PSTO ID from either unit or sub-unit selection
        $psto_id = $request->selected_unit_psto ?: $request->selected_sub_unit_psto;

        // Get authenticated user
        $user = Auth::user();

        // For GET requests, convert array data back to objects
        if ($request->isMethod('get')) {
            $request = $this->convertArraysToObjects($request);
        }

        // Route to appropriate generation method based on CSI type
        switch ($request->csi_type) {
            case 'By Date':
                return $this->generateCSIByUnitByDate($request, $user->region_id, $psto_id);
            case 'By Month':
                return $this->generateCSIByUnitMonthly($request, $user->region_id, $psto_id);
            case 'By Quarter':
                return $this->generateCSIByQuarter($request, $user->region_id, $psto_id);
            case 'By Year/Annual':
                return $this->generateCSIByUnitYearly($request, $user->region_id, $psto_id);
            default:
                abort(400, 'Invalid CSI type specified');
        }
    }

    /**
     * Convert array data to objects for GET requests and return modified request
     */
    private function convertArraysToObjects(Request $request): Request
    {
        $data = $request->all();
        $modifiedData = $data;

        if (isset($data['service']) && is_array($data['service'])) {
            $modifiedData['service'] = (object) $data['service'];
        }

        if (isset($data['unit']) && is_array($data['unit'])) {
            $modifiedData['unit'] = (object) $data['unit'];
        }

        return new Request($modifiedData, $request->query->all(), $request->attributes->all(),
                          $request->cookies->all(), $request->files->all(), $request->server->all());
    }








    public function generateCSIByUnitByDate($request, $region_id, $psto_id)
    {
        $unitData = $this->getUnitData($request, true);
        $sub_unit = $unitData['sub_unit'];
        $unit_pstos = $unitData['unit_pstos'];
        $sub_unit_pstos = $unitData['sub_unit_pstos'];
        $sub_unit_types = $unitData['sub_unit_types'];

        //get user
        $user = Auth::user();
        //get assignatoree list
        $assignatorees = Assignatorees::all();

        //get users lists
        $users = User::all();

      
        
        $service_id = $request->service['id'];
        $unit_id = $request->unit_id;
        $sub_unit_id = $request->selected_sub_unit;
        $client_type = $request->client_type;
        $sub_unit_type = $request->sub_unit_type;

       // search and check list of forms query
       $customer_ids = $this->querySearchCSF($region_id, $service_id, $unit_id ,$sub_unit_id , $psto_id, $client_type, $sub_unit_type );

       $cc_query = CustomerCCRating::whereBetween('created_at', [$request->date_from, $request->date_to])
            ->whereIn('customer_id',$customer_ids)
            ->when($request->sex, function ($query, $sex) {
                $query->whereHas('customer', function ($query) use ($sex) {
                    $query->where('sex', $sex);
                });
            })
            ->when($request->age_group, function ($query, $age_group) {
                $query->whereHas('customer', function ($query) use ($age_group) {
                    $query->where('age_group', $age_group);
                });
            });

        //calculate CC
        $cc_data = $this->calculateCC($cc_query);

        // $date_range = CustomerAttributeRating::whereIn('customer_id',$customer_ids)
        //                                      ->whereBetween('created_at', [$request->date_from, $request->date_to])->get(); 
        
        $date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
            ->whereBetween('created_at', [$request->date_from, $request->date_to])
            ->when($request->sex, function ($query, $sex) {
                $query->whereHas('customer', function ($query) use ($sex) {
                    $query->where('sex', $sex);
                });
            })
            ->when($request->age_group, function ($query, $age_group) {
                $query->whereHas('customer', function ($query) use ($age_group) {
                    $query->where('age_group', $age_group);
                });
            })
            ->get();


        $customer_recommendation_ratings = CustomerRecommendationRating::whereIn('customer_id',$customer_ids)
            ->whereBetween('created_at', [$request->date_from, $request->date_to])
            ->when($request->sex, function ($query, $sex) {
                $query->whereHas('customer', function ($query) use ($sex) {
                    $query->where('sex', $sex);
                });
            })
            ->when($request->age_group, function ($query, $age_group) {
                $query->whereHas('customer', function ($query) use ($age_group) {
                    $query->where('age_group', $age_group);
                });
            })
            ->get();   
                       
        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();

        // total number of respondents/customer
        $total_respondents = $date_range->groupBy('customer_id')->count();

        // total number of respondents/customer who rated VS/S
        $total_vss_respondents = $date_range->where('rate_score', '>','3')->groupBy('customer_id')->count();
        
        // total number of promoters or respondents who rated 7-10 in recommendation rating
        $total_promoters = $customer_recommendation_ratings->whereBetween('recommend_rate_score', [7, 10])->groupBy('customer_id')->count();
        
        // total number of detractors or respondents who rated 0-6 in recommendation rating
        $total_detractors = $customer_recommendation_ratings->whereBetween('recommend_rate_score', [0, 6])->groupBy('customer_id')->count();

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
        $ws_total = 0;
        $ws_totals = [];
        $ws_grand_total = 0;

        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            //PART II
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

             // sum of all repondent with rate_score 1-5
             $x_respondents_total =  $vs_total +   $x_s_total + $n_total +  $d_total +  $vd_total;
            $x_grand_total = $x_vs_total + $x_s_total + $x_n_total + $x_d_total + $x_vd_total  ; 
         
            // right side total score divided by total repondents or customers
            if($x_grand_total != 0){
                if($dimensionId == 6){
                    $lsr_total = $x_grand_total / $x_respondents_total;
                }
                else{
                    $lsr_total = $x_grand_total / $total_respondents;
                }
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
                     
            // PART III
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

            $ws_total = number_format($ws_total, 2);
            $ws_grand_total +=  $ws_total;
            $ws_grand_total = number_format($ws_grand_total, 2);
            $ws_totals[$dimensionId] = [
                'ws_total' => $ws_total,
            ];
        }

        // round off Likert Scale Rating grand total and control decimal to 2 
        $lsr_grand_total = ($lsr_grand_total/ $dimension_count);
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
        if($total_respondents != 0 || $total_vss_respondents != 0){
            $percentage_vss_respondents  = ($total_vss_respondents / $total_respondents) * 100;
        }
        $percentage_vss_respondents = number_format( $percentage_vss_respondents , 2);

        $customer_satisfaction_rating = 0;
        if($total_respondents != 0 || $total_vss_respondents != 0){
            $customer_satisfaction_rating = (($grand_vs_total+$grand_s_total)/$x_grand_total) * 100;
        }
        $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);

        // Customer Satisfaction Index (CSI) = (ws grand total / 5) * 100
        $customer_satisfaction_index = 0;
        if($ws_grand_total != 0){
            $customer_satisfaction_index = ($ws_grand_total/5) * 100;
        }
        $customer_satisfaction_index = number_format($customer_satisfaction_index, 2);

        if($customer_satisfaction_index > 100){
            $customer_satisfaction_index = number_format(100 , 2);
        }

        //Percentage of Promoters = number of promoters / total respondents
        $percentage_promoters = 0;
        if($total_respondents != 0){
            $percentage_promoters = number_format((($total_promoters / $total_respondents) * 100), 2);
        }

        //Percentage of Promoters = number of promoters / total respondents
        $percentage_detractors = 0;
        if($total_respondents != 0){
            $percentage_detractors = number_format((($total_detractors / $total_respondents) * 100),2);
        }

        // Net Promotion Scores(NPS) = Percentage of Promoters−Percentage of Detractors
        $net_promoter_score =  number_format(($percentage_promoters - $percentage_detractors),2);
  

        //send response to front end
        return Inertia::render('CSI/Index')    
            ->with('user', $user)
            ->with('assignatorees', $assignatorees)
            ->with('users', $users)
            ->with('cc_data', $cc_data) 
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
            ->with('net_promoter_score', $net_promoter_score)
            ->with('percentage_promoters', $percentage_promoters)
            ->with('percentage_detractors', $percentage_detractors)
            ->with('request', $request);    
    }   


    public function generateCSIByUnitMonthly($request, $region_id, $psto_id)
    {
        $unitData = $this->getUnitData($request, true);
        $sub_unit = $unitData['sub_unit'];
        $unit_pstos = $unitData['unit_pstos'];
        $sub_unit_pstos = $unitData['sub_unit_pstos'];
        $sub_unit_types = $unitData['sub_unit_types'];

        //get user
        $user = Auth::user();
         //get assignatoree list
         $assignatorees = Assignatorees::all();

         //get users lists
        $users = User::all();

        $date_range = null;
        $customer_recommendation_ratings = null;
        $respondents_list = null;

        $service_id = $request->service['id'];
        $unit_id = $request->unit_id;
        $sub_unit_id = $request->selected_sub_unit;
        $client_type = $request->client_type; 
        $sub_unit_type = $request->sub_unit_type; 

        // search and check list of forms query  
        $customer_ids = $this->querySearchCSF($region_id, $service_id, $unit_id ,$sub_unit_id , $psto_id, $client_type, $sub_unit_type );
      
        $numericMonth = Carbon::parse("1 {$request->selected_month}")->format('m');

        $cc_query = CustomerCCRating::whereMonth('created_at', $numericMonth)
                                    ->whereYear('created_at', $request->selected_year)
                                    ->whereIn('customer_id',$customer_ids)
                                    ->when($request->sex, function ($query, $sex) {
                                        $query->whereHas('customer', function ($query) use ($sex) {
                                            $query->where('sex', $sex);
                                        });
                                    })
                                    ->when($request->age_group, function ($query, $age_group) {
                                        $query->whereHas('customer', function ($query) use ($age_group) {
                                            $query->where('age_group', $age_group);
                                        });
                                    });

        //calculate Citizen's Charter
        $cc_data = $this->calculateCC($cc_query);


        //$date_range = CustomerAttributeRating::whereMonth('created_at', $numericMonth)->get();
        $date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                             ->whereMonth('created_at', $numericMonth)
                                             ->when($request->sex, function ($query, $sex) {
                                                $query->whereHas('customer', function ($query) use ($sex) {
                                                    $query->where('sex', $sex);
                                                });
                                            })
                                            ->when($request->age_group, function ($query, $age_group) {
                                                $query->whereHas('customer', function ($query) use ($age_group) {
                                                    $query->where('age_group', $age_group);
                                                });
                                            })
                                            ->get();

        $customer_recommendation_ratings = CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                            ->whereMonth('created_at', $numericMonth)
                                            ->when($request->sex, function ($query, $sex) {
                                                $query->whereHas('customer', function ($query) use ($sex) {
                                                    $query->where('sex', $sex);
                                                });
                                            })
                                            ->when($request->age_group, function ($query, $age_group) {
                                                $query->whereHas('customer', function ($query) use ($age_group) {
                                                    $query->where('age_group', $age_group);
                                                });
                                            })
                                            ->get();
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
        
        // total number of promoters or respondents who rated 7-10 in recommendation rating
        $total_promoters = $customer_recommendation_ratings->whereBetween('recommend_rate_score', [7, 10])->groupBy('customer_id')->count();
        
        // total number of detractors or respondents who rated 0-6 in recommendation rating
        $total_detractors = $customer_recommendation_ratings->whereBetween('recommend_rate_score', [0, 6])->groupBy('customer_id')->count();

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

        // PART II : CUSTOMER RATING OF SERVICE QUALITY 

        //set initial value of buttom side total scores
        $y_totals = [];
        $grand_na_total = 0;
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
            //PART I :

            $na_total = $date_range->where('rate_score', 6)->where('dimension_id', $dimensionId)->count(); 

            $vs_total = $date_range->where('rate_score', 5)->where('dimension_id', $dimensionId)->count();
            $s_total = $date_range->where('rate_score', 4)->where('dimension_id', $dimensionId)->count();
            $n_total = $date_range->where('rate_score', 3)->where('dimension_id', $dimensionId)->count();
            $d_total = $date_range->where('rate_score', 2)->where('dimension_id', $dimensionId)->count();
            $vd_total = $date_range->where('rate_score', 1)->where('dimension_id', $dimensionId)->count(); 

            //check if there are respondent who rate 3 or below and add it if theres

            // calculation for total score per dimension
            $x_vs_total = $vs_total * 5; 
            $x_s_total = $s_total * 4; 
            $x_n_total = $n_total * 3; 
            $x_d_total = $d_total * 2; 
            $x_vd_total = $vd_total * 1; 

            // sum of all repondent with rate_score 1-5
            $x_respondents_total =  $vs_total +   $x_s_total + $n_total +  $d_total +  $vd_total;
            $x_grand_total = $x_vs_total + $x_s_total + $x_n_total + $x_d_total + $x_vd_total; 
  
            // right side total score divided by total repondents or customers
            if($x_grand_total != 0){
                if($dimensionId == 6){
                    $lsr_total = $x_grand_total / $x_respondents_total;
                }
                else{
                    $lsr_total = $x_grand_total / $total_respondents;
                }
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
         
            $grand_na_total+=$na_total;  

            $grand_vs_total+=$vs_total;
            $grand_s_total+=$s_total;
            $grand_n_total+=$n_total;
            $grand_d_total+=$d_total;
            $grand_vd_total+=$vd_total;       
                     
            // PART II :
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

        //Calculate total number of respondents/customer who rated VS/S
        // Formula ----> get the sum of total respondents for each dimension who rated VS or S and divide it to dimension total count
        // here is 9 because I include the overall data in the dimensions

        $vss_total = $grand_vs_total +  $grand_s_total + $grand_na_total;
        $total_vss_respondents = $vss_total / $dimension_count;     
        $total_vss_respondents = round($total_vss_respondents);      

        // round off Likert Scale Rating grand total and control decimal to 2 
        $lsr_grand_total = ($lsr_grand_total/ $dimension_count);
        $lsr_grand_total = number_format($lsr_grand_total, 2);      
        

        // table below TOTAL SCORES
        $grand_vs_total =   $grand_vs_total * 5;
        $grand_s_total =   $grand_s_total * 4;
        $grand_n_total =   $grand_n_total * 3;
        $grand_d_total =   $grand_d_total * 2;
        $grand_vd_total =   $grand_vd_total * 1;


        $x_grand_total =  $grand_vs_total +  $grand_s_total + $grand_n_total +  $grand_d_total +   $grand_vd_total;

 
        //Percentage of Respondents/Customers who rated VS/S: 
        // = total no. of respondents / total no. respondets who rated vs/s * 100
        $percentage_vss_respondents  = 0;
        if($total_respondents != 0){
            $percentage_vss_respondents  = ($total_vss_respondents/$total_respondents) * 100;
        }
        $percentage_vss_respondents = number_format( $percentage_vss_respondents , 2);

        $customer_satisfaction_rating = 0;
        if($total_vss_respondents != 0){
            $customer_satisfaction_rating = (($grand_vs_total+$grand_s_total)/$x_grand_total) * 100;
        }
        $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);

        // Customer Satisfaction Index (CSI) = (ws grand total / 5) * 100
        $customer_satisfaction_index = 0;
        if($ws_grand_total != 0){
            $customer_satisfaction_index = ($ws_grand_total/5) * 100;
        }
        $customer_satisfaction_index = number_format($customer_satisfaction_index , 2);

        if($customer_satisfaction_index > 100){
            $customer_satisfaction_index = number_format(100 , 2);
        }

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
            ->with('user', $user)
            ->with('cc_data', $cc_data)
            ->with('assignatorees', $assignatorees)
            ->with('users', $users)
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
   
    // QUARTERLY || FIRST, SECOND , THIRD AND FOURTH QUARTER
    public function generateCSIByQuarter($request, $region_id, $psto_id)
    {
        $unitData = $this->getUnitData($request);
        $sub_unit = $unitData['sub_unit'];
        $unit_pstos = $unitData['unit_pstos'];
        $sub_unit_pstos = $unitData['sub_unit_pstos'];
        $sub_unit_types = $unitData['sub_unit_types'];

        //get user
        $user = Auth::user();
        //get assignatoree list
        $assignatorees = Assignatorees::all();

        //get users lists
        $users = User::all();
        
        $date_range = null;
        $customer_recommendation_ratings = null;
        $respondents_list = null; 
            
        $service_id = $request->service;
        $unit_id = $request->unit_id;
        $sub_unit_id = $request->selected_sub_unit;
        $client_type = $request->client_type; 
        $sub_unit_type = $request->sub_unit_type; 

       $startDate = null;
       $endDate = null;
       $numeric_first_month = 0;
       $numeric_second_month = 0;
       $numeric_third_month = 0;

        // Retrieve records for the specified quarter and year
        switch($request->selected_quarter){
            case 'FIRST QUARTER':
                $startDate = Carbon::create($request->selected_year, 1, 1)->startOfDay();
                $endDate = Carbon::create($request->selected_year, 3, 31)->endOfDay();

                $numeric_first_month = 1;
                $numeric_second_month = 2;
                $numeric_third_month = 3;

            break;
            case 'SECOND QUARTER':
                $startDate = Carbon::create($request->selected_year, 4, 1)->startOfDay();
                $endDate = Carbon::create($request->selected_year, 6, 31)->endOfDay();

                $numeric_first_month = 4;
                $numeric_second_month = 5;
                $numeric_third_month = 6;
            break;
            case 'THIRD QUARTER':
                $startDate = Carbon::create($request->selected_year, 7, 1)->startOfDay();
                $endDate = Carbon::create($request->selected_year, 9, 31)->endOfDay();

                $numeric_first_month = 7;
                $numeric_second_month = 8;
                $numeric_third_month = 9;
            break;
            case 'FOURTH QUARTER':
                $startDate = Carbon::create($request->selected_year, 10, 1)->startOfDay();
                $endDate = Carbon::create($request->selected_year, 12, 31)->endOfDay();

                $numeric_first_month = 10;
                $numeric_second_month = 11;
                $numeric_third_month = 12;
            break;

            default:
                dd('no quarter selected'); 
        }  
        
        // search and check list of forms query  and get customers list ids
        $customer_ids = $this->querySearchCSF($region_id, $service_id, $unit_id ,$sub_unit_id , $psto_id, $client_type, $sub_unit_type );

        // get CC Data
        $cc_query = $this->getCitizenCharterByQuarter($request, $customer_ids, $startDate ,$endDate);

        //calculate CC Data
        $cc_data = $this->calculateCC($cc_query);

        // get Customer Attribute Rating with specific quarter date range
        $date_range = $this->getCustomerAttributeRatingByQuarter($request,$customer_ids,$startDate ,$endDate);
        // get first month of Specific Quarter Selected 
        $first_month = $this->getCustomerAttributeRatingByQuarterWithMonth($request, $customer_ids, $numeric_first_month);
        // get second month of Specific Quarter Selected 
        $second_month = $this->getCustomerAttributeRatingByQuarterWithMonth($request, $customer_ids, $numeric_second_month);
        // get third month of Specific Quarter Selected 
        $third_month = $this->getCustomerAttributeRatingByQuarterWithMonth($request, $customer_ids, $numeric_third_month);

        // get Customer Recommendation Rating with specific quarter date range 
        $customer_recommendation_ratings = $this->getCustomerRecommendationRatingByQuarter($request, $customer_ids,$startDate ,$endDate);

        // get First Month Customer Recommendation Rating with specific quarter date range 
        $first_month_crr = $this->getCustomerRecommendationRatingByQuarterWithMonth($request, $customer_ids, $numeric_first_month);
        // get First Month Customer Recommendation Rating with specific quarter date range 
        $second_month_crr = $this->getCustomerRecommendationRatingByQuarterWithMonth($request, $customer_ids, $numeric_second_month);
        // get First Month Customer Recommendation Rating with specific quarter date range 
        $third_month_crr = $this->getCustomerRecommendationRatingByQuarterWithMonth($request, $customer_ids, $numeric_third_month);

        // get respondents list
        $respondents_list = $this->getRespondents($request, $customer_ids,$startDate ,$endDate);            

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
        $lsr_grand_total = 0;
        $lsr_average = 0;

        //Importance total raw points  
        $vi_grand_total_raw_points = 0;
        $i_grand_total_raw_points = 0;
        $misinai_grand_total_raw_points = 0;

        //Importance grand total score
        $vi_grand_total_score=0;
        $i_grand_total_score =0;
        $misinai_grand_total_score = 0;

        $first_month_vs_grand_total = 0;
        $second_month_vs_grand_total =  0;
        $third_month_vs_grand_total =  0;

        $first_month_s_grand_total = 0;
        $second_month_s_grand_total =  0;
        $third_month_s_grand_total =  0;

        $first_month_ndvd_grand_total = 0;
        $second_month_ndvd_grand_total =  0;
        $third_month_ndvd_grand_total =  0;

        $first_month_na_grand_total = 0;
        $second_month_na_grand_total =  0;
        $third_month_na_grand_total =  0;

        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            //PART I

            // First Month total responses with its dimensions and rate score
            $first_month_na_total = $first_month->where('rate_score', 6)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();

            $first_month_vs_total = $first_month->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $first_month_s_total = $first_month->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $first_month_n_total = $first_month->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $first_month_d_total = $first_month->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $first_month_vd_total = $first_month->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $first_month_grand_total =  $first_month_vs_total + $first_month_s_total + $first_month_n_total + $first_month_d_total + $first_month_vd_total ; 

            // Second Month total responses with its dimensions and rate score
            $second_month_na_total = $second_month->where('rate_score', 6)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();

            $second_month_vs_total = $second_month->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $second_month_s_total = $second_month->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $second_month_n_total = $second_month->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $second_month_d_total = $second_month->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $second_month_vd_total = $second_month->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $second_month_grand_total =  $second_month_vs_total + $second_month_s_total + $second_month_n_total + $second_month_d_total + $second_month_vd_total ; 

            // Third Month total responses with its dimensions and rate score
            $third_month_na_total = $third_month->where('rate_score', 6)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();

            $third_month_vs_total = $third_month->where('rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $third_month_s_total = $third_month->where('rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $third_month_n_total = $third_month->where('rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $third_month_d_total = $third_month->where('rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
            $third_month_vd_total = $third_month->where('rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

            $third_month_grand_total =  $third_month_vs_total + $third_month_s_total + $third_month_n_total + $third_month_d_total + $third_month_vd_total ; 

            // Quarterly Very Satisfied total with specific dimension or attribute
            $vs_totals[$dimensionId] = [
                'first_month_vs_total' => $first_month_vs_total,
                'second_month_vs_total' => $second_month_vs_total,
                'third_month_vs_total' => $third_month_vs_total,
            ];
            // Quarterly Satisfied total with specific dimension or attribute
            $s_totals[$dimensionId] = [
                'first_month_s_total' => $first_month_s_total,
                'second_month_s_total' => $second_month_s_total,
                'third_month_s_total' => $third_month_s_total,
            ];

             // Quarterly Neutral total with specific dimension or attribute
            $n_totals[$dimensionId] = [
                'first_month_n_total' => $first_month_n_total,
                'second_month_n_total' => $second_month_n_total,
                'third_month_n_total' => $third_month_n_total,
            ];

            // Quarterly Disatisfied total with specific dimension or attribute
            $d_totals[$dimensionId] = [
                'first_month_d_total' => $first_month_d_total,
                'second_month_d_total' => $second_month_d_total,
                'third_month_d_total' => $third_month_d_total,
            ];

             // Quarterly Very Disatisfied total with specific dimension or attribute
            $vd_totals[$dimensionId] = [
                'first_month_vd_total' => $first_month_vd_total,
                'second_month_vd_total' => $second_month_vd_total,
                'third_month_vd_total' => $third_month_vd_total,
            ];

            // Quarterly grand totals with specific dimension or attribute
            $grand_totals[$dimensionId] = [
                'first_month_grand_total' => $first_month_grand_total,
                'second_month_grand_total' => $second_month_grand_total,
                'third_month_grand_total' => $third_month_grand_total,
            ];

            //Total Raw Points totals
            $vs_total_raw_points = $first_month_vs_total + $second_month_vs_total + $third_month_vs_total;
            $s_total_raw_points = $first_month_s_total + $second_month_s_total + $third_month_s_total;
            $n_total_raw_points = $first_month_n_total + $second_month_n_total + $third_month_n_total;
            $d_total_raw_points =$first_month_d_total + $second_month_d_total + $third_month_d_total;
            $vd_total_raw_points =$first_month_vd_total + $second_month_vd_total + $third_month_vd_total;
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
                $s_lsr_total =   number_format(($x_s_total /  $total_raw_points),2);
                $n_lsr_total =   number_format(($x_n_total /  $total_raw_points),2);
                $d_lsr_total =   number_format(($x_d_total /  $total_raw_points),2);
                $vd_lsr_total =  number_format(($x_vd_total /  $total_raw_points),2);
                $lsr_total =  number_format(($vs_lsr_total +  $s_lsr_total  +  $n_lsr_total  +  $d_lsr_total  +  $vd_lsr_total),2);
                $lsr_grand_total +=  $lsr_total;
                $lsr_grand_total =number_format(($lsr_grand_total), 2);
                $lsr_average =  number_format(($lsr_grand_total / $dimensionId), 2);
            }
            else{
                $vs_lsr_total =  0;
                $s_lsr_total =  0;
                $n_lsr_total =  0;
                $d_lsr_total = 0;
                $vd_lsr_total =  0;
                $lsr_total = 0;
                $lsr_grand_total = 0;
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
              // first month total responses with its dimensions and rate score
              $first_month_vi_total = $first_month->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $first_month_i_total = $first_month->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $first_month_mi_total = $first_month->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $first_month_si_total = $first_month->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $first_month_nai_total = $first_month->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $first_month_i_grand_total =  $first_month_vi_total + $first_month_i_total + $first_month_mi_total + $first_month_si_total + $first_month_nai_total ; 
  
              //  second_month importance total responses with its dimensions and rate score
              $second_month_vi_total = $second_month->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $second_month_i_total = $second_month->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $second_month_mi_total = $second_month->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $second_month_si_total = $second_month->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $second_month_nai_total = $second_month->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $second_month_i_grand_total =  $second_month_vi_total + $second_month_i_total + $second_month_mi_total + $second_month_si_total + $second_month_nai_total ; 
  
              //  third month total responses with its dimensions and rate score
              $third_month_vi_total = $third_month->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $third_month_i_total = $third_month->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $third_month_mi_total = $third_month->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $third_month_si_total = $third_month->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $third_month_nai_total = $third_month->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          
  
              $third_month_i_grand_total =  $third_month_vi_total + $third_month_i_total + $third_month_mi_total + $third_month_si_total + $third_month_nai_total ; 

                // Very Important total with specific dimention or attribute
                $vi_totals[$dimensionId] = [
                    'first_month_vi_total' => $first_month_vi_total,
                    'second_month_vi_total' => $second_month_vi_total,
                    'third_month_vi_total' => $third_month_vi_total,
                ];
                //Important total with specific dimention or attribute
                $i_totals[$dimensionId] = [
                    'first_month_i_total' => $first_month_i_total,
                    'second_month_i_total' => $second_month_i_total,
                    'third_month_i_total' => $third_month_i_total,
                ];
                // Moderately Important total with specific dimention or attribute
                $mi_totals[$dimensionId] = [
                    'first_month_mi_total' => $first_month_mi_total,
                    'second_month_mi_total' => $second_month_mi_total,
                    'third_month_mi_total' => $third_month_mi_total,
                ];
                // slightly Important total with specific dimention or attribute
                $si_totals[$dimensionId] = [
                    'first_month_si_total' => $first_month_si_total,
                    'second_month_si_total' => $second_month_si_total,
                    'third_month_si_total' => $third_month_si_total,
                ];

                $nai_totals[$dimensionId] = [
                    'first_month_nai_total' => $first_month_nai_total,
                    'second_month_nai_total' => $second_month_nai_total,
                    'third_month_nai_total' => $third_month_nai_total,
                ];

                $i_grand_totals[$dimensionId] = [
                    'first_month_i_grand_total' => $first_month_i_grand_total,
                    'second_month_i_grand_total' => $second_month_i_grand_total,
                    'third_month_i_grand_total' => $third_month_i_grand_total,
                ];

                
            //Importance Total Raw Points totals
            $vi_total_raw_points = $first_month_vi_total + $second_month_vi_total + $third_month_vi_total;
            $i_total_raw_points = $first_month_i_total + $second_month_i_total + $third_month_i_total;
            $mi_total_raw_points = $first_month_mi_total + $second_month_mi_total + $third_month_mi_total;
            $si_total_raw_points = $first_month_si_total + $second_month_si_total + $third_month_si_total;
            $nai_total_raw_points = $first_month_nai_total + $second_month_nai_total + $third_month_nai_total;
            $total_raw_points = $vi_total_raw_points + $i_total_raw_points + $mi_total_raw_points +  $si_total_raw_points +  $nai_total_raw_points;           

            $vi_grand_total_raw_points += $vi_total_raw_points;
            $i_grand_total_raw_points +=  $i_total_raw_points;
            $misinai_grand_total_raw_points +=  $mi_total_raw_points + $si_total_raw_points + $nai_total_raw_points;
            $grand_total_raw_points+= $total_raw_points;

           
            $i_trp_totals[$dimensionId] = [
                'vi_total_raw_points' => $vi_total_raw_points,
                'i_total_raw_points' => $i_total_raw_points,
                'mi_total_raw_points' => $mi_total_raw_points,
                'si_total_raw_points' => $si_total_raw_points,
                'nai_total_raw_points' => $nai_total_raw_points,
                'total_raw_points' => $total_raw_points,
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

            // Calculate all respondents who rated VS 
            $first_month_vs_grand_total +=  $first_month_vs_total;
            $second_month_vs_grand_total +=  $second_month_vs_total;
            $third_month_vs_grand_total +=  $third_month_vs_total; 

            // Calculate all respondents who rated S
            $first_month_s_grand_total +=  $first_month_s_total;
            $second_month_s_grand_total +=  $second_month_s_total;
            $third_month_s_grand_total +=  $third_month_s_total; 

            // Calculate all respondents who rated NDVD
            $first_month_ndvd_total = $first_month_n_total + $first_month_d_total + $first_month_vd_total;
            $second_month_ndvd_total = $second_month_n_total + $second_month_d_total + $second_month_vd_total;
            $third_month_ndvd_total = $third_month_n_total + $third_month_d_total + $third_month_vd_total;

            $first_month_ndvd_grand_total +=  $first_month_ndvd_total;
            $second_month_ndvd_grand_total +=  $second_month_ndvd_total;
            $third_month_ndvd_grand_total +=  $third_month_ndvd_total; 

            // Calculate all respondents who rated n/a
            $first_month_na_grand_total +=  $first_month_na_total;
            $second_month_na_grand_total +=  $second_month_na_total;
            $third_month_na_grand_total +=  $third_month_na_total; 
   
        }

    
        // Calculate all respondents 
        $first_month_grand_total =  $first_month_vs_grand_total + $first_month_s_grand_total +   $first_month_ndvd_grand_total;     
        $second_month_grand_total =  $second_month_vs_grand_total + $second_month_s_grand_total +  $second_month_ndvd_grand_total;
        $third_month_grand_total =  $third_month_vs_grand_total + $third_month_s_grand_total +  $third_month_ndvd_grand_total;

        //Calculate total number of respondents/customer who rated VS/S include N/A
        // Formula ----> get the sum of total respondents for each dimension who rated VS or S and divide it to dimension total count
        // here is 9 because I include the overall data in the dimensions

        $first_month_vss_total = $first_month_vs_grand_total +  $first_month_s_grand_total + $first_month_na_grand_total;
        $first_month_total_vss_respondents = $first_month_vss_total / 9;     
        $first_month_total_vss_respondents = round($first_month_total_vss_respondents);  

        $second_month_vss_total = $second_month_vs_grand_total +  $second_month_s_grand_total + $second_month_na_grand_total;
        $second_month_total_vss_respondents = $second_month_vss_total / 9;     
        $second_month_total_vss_respondents = round($second_month_total_vss_respondents);  

        $third_month_vss_total = $third_month_vs_grand_total +  $third_month_s_grand_total + $third_month_na_grand_total;
        $third_month_total_vss_respondents = $third_month_vss_total / 9;     
        $third_month_total_vss_respondents = round($third_month_total_vss_respondents);  

        $total_vss_respondents =  $first_month_total_vss_respondents + $second_month_total_vss_respondents +   $third_month_total_vss_respondents;

        // Total No. of Very Satisfied (VS) Responses of First Quarte
        // -- 1st month
        $first_month_total_vs_respondents = $first_month->where('rate_score',5)->groupBy('customer_id')->count();
        // -- 2nd month
        $second_month_total_vs_respondents = $second_month->where('rate_score',5)->groupBy('customer_id')->count();
        // -- 3rd month
        $third_month_total_vs_respondents = $third_month->where('rate_score',5)->groupBy('customer_id')->count();

        // Total No. of Satisfied (S) Responses
       // -- octy
       $first_month_total_s_respondents = $first_month->where('rate_score',4)->groupBy('customer_id')->count();
       // -- novust
       $second_month_total_s_respondents = $second_month->where('rate_score',4)->groupBy('customer_id')->count();
       // -- dectember
       $third_month_total_s_respondents = $third_month->where('rate_score',4)->groupBy('customer_id')->count();

        // Total No. of Other (N, D, VD) Responses
        // -- octy
        $first_month_total_ndvd_respondents = $first_month->where('rate_score','<', 4)->groupBy('customer_id')->count();
        // -- novust
        $second_month_total_ndvd_respondents = $second_month->where('rate_score','<', 4)->groupBy('customer_id')->count();
        // -- dectember
        $third_month_total_ndvd_respondents = $third_month->where('rate_score','<', 4)->groupBy('customer_id')->count();
          
        // Total No. of All Responses
        // -- octy
        $first_month_total_respondents = $first_month->groupBy('customer_id')->count();
        // -- novust
        $second_month_total_respondents = $second_month->groupBy('customer_id')->count();
        // -- dectember
        $third_month_total_respondents = $third_month->groupBy('customer_id')->count();

        //Total respondents /Customers
        $total_respondents = $date_range->groupBy('customer_id')->count();
    
        // Frst quarter total number of promoters or respondents who rated 9-10 in recommendation rating
        $total_promoters = $customer_recommendation_ratings->where('recommend_rate_score', '>','6')->groupBy('customer_id')->count();
        // 1st month
        $first_month_total_promoters = $first_month_crr->whereBetween('recommend_rate_score', [7, 10])->groupBy('customer_id')->count();
        // 2nd Month
        $second_month_total_promoters = $second_month_crr->whereBetween('recommend_rate_score', [7, 10])->groupBy('customer_id')->count();
        // 3rd month
        $third_month_total_promoters = $third_month_crr->whereBetween('recommend_rate_score', [7, 10])->groupBy('customer_id')->count();
        
        // total number of detractors or respondents who rated 0-6 in recommendation rating
        $total_detractors = $customer_recommendation_ratings->whereBetween('recommend_rate_score', [0, 6])->groupBy('customer_id')->count();
       // 1st month
        $first_month_total_detractors = $first_month_crr->whereBetween('recommend_rate_score', [0, 6])->groupBy('customer_id')->count();
        // 2nd Month
        $second_month_total_detractors = $second_month_crr->whereBetween('recommend_rate_score', [0, 6])->groupBy('customer_id')->count();
        // 3rd month
        $third_month_total_detractors = $third_month_crr->whereBetween('recommend_rate_score', [0, 6])->groupBy('customer_id')->count();
  
        //Percentage of Respondents/Customers who rated VS/S = total no. of respondents / total no. respondets who rated vs/s * 100
        $percentage_vss_respondents  = 0;
        if($total_respondents != 0){
            $percentage_vss_respondents  = ($total_vss_respondents / $total_respondents) * 100;
        }
        $percentage_vss_respondents = number_format( $percentage_vss_respondents , 2);

        $customer_satisfaction_rating = 0;
        if($total_vss_respondents != 0){
            $customer_satisfaction_rating = (($vs_grand_total_score + $s_grand_total_score)/$grand_total_score) * 100;
        }
        $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);

        //Percentage of Promoters = number of promoters / total respondents
        $percentage_promoters = 0;
        //  Percentage promoters
        $first_month_percentage_promoters = 0.00;
        $second_month_percentage_promoters = 0.00;
        $third_month_percentage_promoters = 0.00;

        // Percentage of promoter
        $first_month_percentage_detractors = 0.00;
        $second_month_percentage_detractors = 0.00;
        $third_month_percentage_detractors = 0.00;

        //nps
        $first_month_net_promoter_score =  0.00;
        $second_month_net_promoter_score = 0.00;
        $third_month_net_promoter_score =  0.00;

        //average
        $ave_net_promoter_score = 0.00;
        $average_percentage_promoters =  0.00;
        $average_percentage_detractors =  0.00;


        if($total_respondents != 0 ){
            $percentage_promoters = number_format((($total_promoters / $total_respondents) * 100), 2);
            if($first_month_total_respondents !=0){
                $first_month_percentage_promoters = number_format((($first_month_total_promoters / $first_month_total_respondents) * 100), 2);
                $first_month_percentage_detractors = number_format((($first_month_total_detractors / $first_month_total_respondents) * 100),2);
            }

            if($second_month_total_respondents !=0){
                $second_month_percentage_promoters = number_format((($second_month_total_promoters / $second_month_total_respondents) * 100), 2);
                $second_month_percentage_detractors = number_format((($second_month_total_detractors / $second_month_total_respondents) * 100),2);
            }
           
            if($third_month_total_respondents != 0 ){
                $third_month_percentage_promoters = number_format((($third_month_total_promoters / $third_month_total_respondents) * 100), 2);
                $third_month_percentage_detractors = number_format((($third_month_total_detractors / $third_month_total_respondents) * 100),2);
            }
          
        
            //Percentage of Promoters = number of promoters / total respondents
            $percentage_detractors = number_format((($total_detractors / $total_respondents) * 100),2);

            // Net Promotion Scores(NPS) = Percentage of Promoters−Percentage of Detractors
            $first_month_net_promoter_score =  number_format(($first_month_percentage_promoters - $first_month_percentage_detractors),2);
            $second_month_net_promoter_score =  number_format(($second_month_percentage_promoters - $second_month_percentage_detractors),2);
            $third_month_net_promoter_score =  number_format(($third_month_percentage_promoters - $third_month_percentage_detractors),2);

            $ave_net_promoter_score =  number_format((($first_month_net_promoter_score + $second_month_net_promoter_score + $third_month_net_promoter_score)/ 3),2);
            $average_percentage_promoters =  number_format((($first_month_percentage_promoters + $second_month_percentage_promoters + $third_month_percentage_promoters)/ 3),2);
            $average_percentage_detractors =  number_format((($first_month_percentage_detractors + $second_month_percentage_detractors + $third_month_percentage_detractors)/ 3),2);

        }

        // get Monthly CSI 
        
        $first_month_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, $numeric_first_month);
        $second_month_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, $numeric_second_month);
        $third_month_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, $numeric_third_month);

        $customer_satisfaction_index = number_format((($first_month_csi + $second_month_csi +  $third_month_csi)/3), 2);
     

        if($customer_satisfaction_index > 100){
            $customer_satisfaction_index = number_format(100 , 2);
        }

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
            ->with('cc_data', $cc_data)
            ->with('user', $user)
            ->with('assignatorees', $assignatorees)
            ->with('users', $users)
            ->with('sub_unit', $sub_unit)
            ->with('unit_pstos', $unit_pstos)
            ->with('sub_unit_pstos', $sub_unit_pstos)
            ->with('sub_unit_types', $sub_unit_types)
            ->with('dimensions', $dimensions)
            ->with('service', $request->service)
            ->with('unit', $request->unit)
            ->with('respondents_list',$data)
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
            ->with('vs_totals', $vs_totals)
            ->with('s_totals', $s_totals)
            ->with('n_totals', $n_totals)
            ->with('d_totals', $d_totals)
            ->with('vd_totals', $vd_totals)
            ->with('grand_totals', $grand_totals)
            ->with('first_month_total_vs_respondents', $first_month_total_vs_respondents)
            ->with('second_month_total_vs_respondents', $second_month_total_vs_respondents)
            ->with('third_month_total_vs_respondents', $third_month_total_vs_respondents)
            ->with('first_month_total_s_respondents', $first_month_total_s_respondents)
            ->with('second_month_total_s_respondents', $second_month_total_s_respondents)
            ->with('third_month_total_s_respondents', $third_month_total_s_respondents)
            ->with('first_month_total_ndvd_respondents', $first_month_total_ndvd_respondents)
            ->with('second_month_total_ndvd_respondents', $second_month_total_ndvd_respondents)
            ->with('third_month_total_ndvd_respondents', $third_month_total_ndvd_respondents)
            ->with('first_month_total_respondents', $first_month_total_respondents)
            ->with('second_month_total_respondents', $second_month_total_respondents)
            ->with('third_month_total_respondents', $third_month_total_respondents)
            ->with('total_respondents', $total_respondents)
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
            ->with('first_month_percentage_promoters', $first_month_percentage_promoters)
            ->with('second_month_percentage_promoters', $second_month_percentage_promoters)
            ->with('third_month_percentage_promoters', $third_month_percentage_promoters)
            ->with('average_percentage_promoters', $average_percentage_promoters)
            ->with('first_month_percentage_detractors', $first_month_percentage_detractors)
            ->with('second_percentage_detractors', $second_month_percentage_detractors)
            ->with('third_month_percentage_detractors', $third_month_percentage_detractors) 
            ->with('average_percentage_detractors', $average_percentage_detractors)
            ->with('first_month_net_promoter_score', $first_month_net_promoter_score)
            ->with('second_month_net_promoter_score', $second_month_net_promoter_score)
            ->with('third_month_net_promoter_score', $third_month_net_promoter_score)
            ->with('ave_net_promoter_score', $ave_net_promoter_score)
            ->with('customer_satisfaction_rating', $customer_satisfaction_rating)
            ->with('csi', $customer_satisfaction_index)
            ->with('first_month_csi', $first_month_csi)
            ->with('second_month_csi', $second_month_csi)
            ->with('third_month_csi', $third_month_csi)
            ->with('first_month_vs_grand_total', $first_month_vs_grand_total)
            ->with('second_month_vs_grand_total', $second_month_vs_grand_total)
            ->with('third_month_vs_grand_total', $third_month_vs_grand_total)
            ->with('first_month_s_grand_total', $first_month_s_grand_total)
            ->with('second_month_s_grand_total', $second_month_s_grand_total)
            ->with('third_month_s_grand_total', $third_month_s_grand_total)
            ->with('first_month_ndvd_grand_total', $first_month_ndvd_grand_total)
            ->with('second_month_ndvd_grand_total', $second_month_ndvd_grand_total)
            ->with('third_month_ndvd_grand_total', $third_month_ndvd_grand_total)
            ->with('first_month_grand_total', $first_month_grand_total)
            ->with('second_month_grand_total', $second_month_grand_total)
            ->with('third_month_grand_total', $third_month_grand_total)
            ->with('total_comments', $total_comments)
            ->with('total_complaints', $total_complaints)
            ->with('comments', $comments);
    }

    public function getCitizenCharterByQuarter($request, $customer_ids, $startDate ,$endDate)
    {
         $cc_query = CustomerCCRating::whereBetween('created_at', [$startDate, $endDate])
            ->whereYear('created_at', $request->selected_year)
            ->whereIn('customer_id',$customer_ids)
            ->when($request->sex, function ($query, $sex) {
                $query->whereHas('customer', function ($query) use ($sex) {
                    $query->where('sex', $sex);
                });
            })
            ->when($request->age_group, function ($query, $age_group) {
                $query->whereHas('customer', function ($query) use ($age_group) {
                    $query->where('age_group', $age_group);
                });
            })->get();
        return  $cc_query;
    }

    public function getCustomerAttributeRatingByQuarter($request, $customer_ids , $startDate ,$endDate ){
        $query = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->whereYear('created_at', $request->selected_year)
        ->when($request->sex, function ($query, $sex) {
            $query->whereHas('customer', function ($query) use ($sex) {
                $query->where('sex', $sex);
            });
        })
        ->when($request->age_group, function ($query, $age_group) {
            $query->whereHas('customer', function ($query) use ($age_group) {
                $query->where('age_group', $age_group);
            });
        })->get(); 

        return  $query;
   }


   public function getCustomerAttributeRatingByQuarterWithMonth($request,$customer_ids, $numeric_month){
        $query = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
            ->whereMonth('created_at', $numeric_month)
            ->whereYear('created_at', $request->selected_year)
            ->when($request->sex, function ($query, $sex) {
                $query->whereHas('customer', function ($query) use ($sex) {
                    $query->where('sex', $sex);
                });
            })
            ->when($request->age_group, function ($query, $age_group) {
                $query->whereHas('customer', function ($query) use ($age_group) {
                    $query->where('age_group', $age_group);
                });
            })->get(); 

        return  $query;
    }


    public function getCustomerRecommendationRatingByQuarter($request, $customer_ids,$startDate, $endDate){
        $query = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->whereYear('created_at', $request->selected_year)
        ->when($request->sex, function ($query, $sex) {
            $query->whereHas('customer', function ($query) use ($sex) {
                $query->where('sex', $sex);
            });
        })
        ->when($request->age_group, function ($query, $age_group) {
            $query->whereHas('customer', function ($query) use ($age_group) {
                $query->where('age_group', $age_group);
            });
        })->get(); 

        return  $query;
   }

   
   public function getCustomerRecommendationRatingByQuarterWithMonth($request,$customer_ids, $numeric_month){
        $fisrt_month_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
        ->whereMonth('created_at', $numeric_month)
        ->whereYear('created_at', $request->selected_year)
        ->when($request->sex, function ($query, $sex) {
            $query->whereHas('customer', function ($query) use ($sex) {
                $query->where('sex', $sex);
            });
        })
        ->when($request->age_group, function ($query, $age_group) {
            $query->whereHas('customer', function ($query) use ($age_group) {
                $query->where('age_group', $age_group);
            });
        })->get();

        return  $fisrt_month_crr;
    }

    public function getRespondents($request, $customer_ids,$startDate, $endDate){
        $respondents_list = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->whereYear('created_at', $request->selected_year)
                    ->when($request->sex, function ($query, $sex) {
                        $query->whereHas('customer', function ($query) use ($sex) {
                            $query->where('sex', $sex);
                        });
                    })
                    ->when($request->age_group, function ($query, $age_group) {
                        $query->whereHas('customer', function ($query) use ($age_group) {
                            $query->where('age_group', $age_group);
                        });
                    })->get();

        return  $respondents_list;
   }


 
    // YEARLY || ANNUALLY PER UNIT
    public function generateCSIByUnitYearly($request, $region_id, $psto_id)
    {
        $unitData = $this->getUnitData($request, true);
        $sub_unit = $unitData['sub_unit'];
        $unit_pstos = $unitData['unit_pstos'];
        $sub_unit_pstos = $unitData['sub_unit_pstos'];
        $sub_unit_types = $unitData['sub_unit_types'];

        //get user
        $user = Auth::user();
         //get assignatoree list
         $assignatorees = Assignatorees::all();

         //get users lists
        $users = User::all();

        $date_range = [];
        $q1_date_range = [];
        $q2_date_range = [];
        $q3_date_range = [];
        $q4_date_range = [];
        $customer_recommendation_ratings = null;
        $respondents_list = null;

        $ws_grand_total = 0;
          
        $service_id = $request->service['id'];
        $unit_id = $request->unit_id;
        $sub_unit_id = $request->selected_sub_unit;
        $client_type = $request->client_type; 
        $sub_unit_type = $request->sub_unit_type; 

       // search and check list of forms query  
       $customer_ids = $this->querySearchCSF( $region_id, $service_id, $unit_id ,$sub_unit_id , $psto_id, $client_type, $sub_unit_type );
            
        // Citizen's Charter
        $cc_query = CustomerCCRating::whereYear('created_at', $request->selected_year)
                                    ->whereIn('customer_id',$customer_ids)
                                    ->when($request->sex, function ($query, $sex) {
                                        $query->whereHas('customer', function ($query) use ($sex) {
                                            $query->where('sex', $sex);
                                        });
                                    })
                                    ->when($request->age_group, function ($query, $age_group) {
                                        $query->whereHas('customer', function ($query) use ($age_group) {
                                            $query->where('age_group', $age_group);
                                        });
                                    });

       //calculate CC
        $cc_data = $this->calculateCC($cc_query);

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
                                                ->whereYear('created_at', $request->selected_year)
                                                ->when($request->sex, function ($query, $sex) {
                                                    $query->whereHas('customer', function ($query) use ($sex) {
                                                        $query->where('sex', $sex);
                                                    });
                                                })
                                                ->when($request->age_group, function ($query, $age_group) {
                                                    $query->whereHas('customer', function ($query) use ($age_group) {
                                                        $query->where('age_group', $age_group);
                                                    });
                                                })
                                                ->get(); 
        $q2_date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$q2_start_date, $q2_end_date])
                                                ->whereYear('created_at', $request->selected_year)
                                                ->when($request->sex, function ($query, $sex) {
                                                    $query->whereHas('customer', function ($query) use ($sex) {
                                                        $query->where('sex', $sex);
                                                    });
                                                })
                                                ->when($request->age_group, function ($query, $age_group) {
                                                    $query->whereHas('customer', function ($query) use ($age_group) {
                                                        $query->where('age_group', $age_group);
                                                    });
                                                })->get(); 
        $q3_date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$q3_start_date, $q3_end_date])
                                                ->whereYear('created_at', $request->selected_year)
                                                ->when($request->sex, function ($query, $sex) {
                                                    $query->whereHas('customer', function ($query) use ($sex) {
                                                        $query->where('sex', $sex);
                                                    });
                                                })
                                                ->when($request->age_group, function ($query, $age_group) {
                                                    $query->whereHas('customer', function ($query) use ($age_group) {
                                                        $query->where('age_group', $age_group);
                                                    });
                                                })->get();
        $q4_date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$q4_start_date, $q4_end_date])
                                                ->whereYear('created_at', $request->selected_year)
                                                ->when($request->sex, function ($query, $sex) {
                                                    $query->whereHas('customer', function ($query) use ($sex) {
                                                        $query->where('sex', $sex);
                                                    });
                                                })
                                                ->when($request->age_group, function ($query, $age_group) {
                                                    $query->whereHas('customer', function ($query) use ($age_group) {
                                                        $query->where('age_group', $age_group);
                                                    });
                                                })->get();
        
        $date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                                ->whereYear('created_at', $request->selected_year)
                                                ->when($request->sex, function ($query, $sex) {
                                                    $query->whereHas('customer', function ($query) use ($sex) {
                                                        $query->where('sex', $sex);
                                                    });
                                                })
                                                ->when($request->age_group, function ($query, $age_group) {
                                                    $query->whereHas('customer', function ($query) use ($age_group) {
                                                        $query->where('age_group', $age_group);
                                                    });
                                                })->get(); 

        $customer_recommendation_ratings = CustomerRecommendationRating::whereYear('created_at', $request->selected_year)
                                                ->whereIn('customer_id', $customer_ids)
                                                ->get();

        $q1_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$q1_start_date, $q1_end_date])
                                                ->whereYear('created_at', $request->selected_year)
                                                ->when($request->sex, function ($query, $sex) {
                                                    $query->whereHas('customer', function ($query) use ($sex) {
                                                        $query->where('sex', $sex);
                                                    });
                                                })
                                                ->when($request->age_group, function ($query, $age_group) {
                                                    $query->whereHas('customer', function ($query) use ($age_group) {
                                                        $query->where('age_group', $age_group);
                                                    });
                                                })->get();

        $q2_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$q2_start_date, $q2_end_date])
                                                ->whereYear('created_at', $request->selected_year)
                                                ->when($request->sex, function ($query, $sex) {
                                                    $query->whereHas('customer', function ($query) use ($sex) {
                                                        $query->where('sex', $sex);
                                                    });
                                                })
                                                ->when($request->age_group, function ($query, $age_group) {
                                                    $query->whereHas('customer', function ($query) use ($age_group) {
                                                        $query->where('age_group', $age_group);
                                                    });
                                                })->get();
                                                    
        $q3_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$q3_start_date, $q3_end_date])
                                                ->whereYear('created_at', $request->selected_year)
                                                ->when($request->sex, function ($query, $sex) {
                                                    $query->whereHas('customer', function ($query) use ($sex) {
                                                        $query->where('sex', $sex);
                                                    });
                                                })
                                                ->when($request->age_group, function ($query, $age_group) {
                                                    $query->whereHas('customer', function ($query) use ($age_group) {
                                                        $query->where('age_group', $age_group);
                                                    });
                                                })->get();

        $q4_crr =  CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                                                ->whereBetween('created_at', [$q4_start_date, $q4_end_date])
                                                ->whereYear('created_at', $request->selected_year)
                                                ->when($request->sex, function ($query, $sex) {
                                                    $query->whereHas('customer', function ($query) use ($sex) {
                                                        $query->where('sex', $sex);
                                                    });
                                                })
                                                ->when($request->age_group, function ($query, $age_group) {
                                                    $query->whereHas('customer', function ($query) use ($age_group) {
                                                        $query->where('age_group', $age_group);
                                                    });
                                                })->get();


        $respondents_list = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                                ->whereYear('created_at', $request->selected_year)
                                                ->when($request->sex, function ($query, $sex) {
                                                    $query->whereHas('customer', function ($query) use ($sex) {
                                                        $query->where('sex', $sex);
                                                    });
                                                })
                                                ->when($request->age_group, function ($query, $age_group) {
                                                    $query->whereHas('customer', function ($query) use ($age_group) {
                                                        $query->where('age_group', $age_group);
                                                    });
                                                })->get();     
          
        

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
              $q4_vi_total = $q4_date_range->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q4_i_total = $q4_date_range->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q4_mi_total = $q4_date_range->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q4_si_total = $q4_date_range->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();
              $q4_nai_total = $q4_date_range->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->groupBy('customer_id')->count();          

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
            $importance_total_raw_points = $vi_total_raw_points + $i_total_raw_points + $mi_total_raw_points +  $si_total_raw_points +  $nai_total_raw_points;           

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
                'importance_total_raw_points' => $importance_total_raw_points,
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
            $percentage_vss_respondents  = ($total_vss_respondents / $total_respondents) * 100;
        }
        $percentage_vss_respondents = number_format( $percentage_vss_respondents , 2);

         // CSAT = ((Total No. of Very Satisfied (VS) Responses + Total No. of Satisfied (S) Responses) / grand total respondents) * 100
        $customer_satisfaction_rating = 0;
        if($total_vss_respondents != 0){
            $customer_satisfaction_rating = (($vs_grand_total_score + $s_grand_total_score)/$grand_total_score) * 100;
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
            if($q1_total_respondents != 0){
                $q1_percentage_promoters = number_format((($q1_total_promoters / $q1_total_respondents) * 100), 2);
                $q1_percentage_detractors = number_format((($q1_total_detractors / $q1_total_respondents) * 100),2);
            }
            if($q2_total_respondents != 0){
                $q2_percentage_promoters = number_format((($q2_total_promoters / $q2_total_respondents) * 100), 2);
                $q2_percentage_detractors = number_format((($q2_total_detractors / $q2_total_respondents) * 100),2);
            }
            if($q3_total_respondents != 0){
                $q3_percentage_promoters = number_format((($q3_total_promoters / $q3_total_respondents) * 100), 2);
                $q3_percentage_detractors = number_format((($q3_total_detractors / $q3_total_respondents) * 100),2);
            }
            if($q4_total_respondents != 0){
                $q4_percentage_promoters = number_format((($q4_total_promoters / $q4_total_respondents) * 100), 2);
                $q4_percentage_detractors = number_format((($q4_total_detractors / $q4_total_respondents) * 100),2);
            }

            //Percentage of Promoters = number of promoters / total respondents
            $percentage_detractors = number_format((($total_detractors / $total_respondents) * 100),2);

            // Net Promotion Scores(NPS) = Percentage of Promoters−Percentage of Detractors
            $q1_net_promoter_score =  number_format(($q1_percentage_promoters - $q1_percentage_detractors),2);
            $q2_net_promoter_score =  number_format(($q2_percentage_promoters - $q2_percentage_detractors),2);
            $q3_net_promoter_score =  number_format(($q3_percentage_promoters - $q3_percentage_detractors),2);
            $q4_net_promoter_score =  number_format(($q4_percentage_promoters - $q4_percentage_detractors),2);

            $ave_net_promoter_score =  number_format((($q1_net_promoter_score + $q2_net_promoter_score + $q3_net_promoter_score + $q4_net_promoter_score)/ 4),2);
            $average_percentage_promoters =  number_format((($q1_percentage_promoters + $q2_percentage_promoters + $q3_percentage_promoters + $q4_percentage_promoters)/ 4),2);
            $average_percentage_detractors =  number_format((($q1_percentage_detractors + $q2_percentage_detractors + $q3_percentage_detractors + $q4_percentage_detractors)/ 4),2);


        }

        // Calculate yearly CSI
        $ws_grand_total = $ws_grand_total ?? 0;
      
        $customer_satisfaction_index = 0;
        if($ws_grand_total != 0){
            $customer_satisfaction_index = ($ws_grand_total / 5) * 100;
        }
        $customer_satisfaction_index = number_format($customer_satisfaction_index, 2);

        if($customer_satisfaction_index > 100){
            $customer_satisfaction_index = number_format(100 , 2);
        }

  
        // get Yearly CSI
        $jan_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 1);
        $feb_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 2);
        $mar_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 3);

        $q1_csi = 0;
        $q1_csi = number_format(($jan_csi +  $feb_csi + $mar_csi) / 3, 2);

        $apr_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 4);
        $may_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 5);
        $jun_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 6);

        $q2_csi = 0;
        $q2_csi = number_format(($apr_csi +  $may_csi + $jun_csi) / 3, 2);

        $jul_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 7);
        $aug_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 8);
        $sep_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 9);

        $q3_csi = 0;
        $q3_csi = number_format(($jul_csi +  $aug_csi + $sep_csi) / 3, 2);

        $oct_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 10);
        $nov_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 11);
        $dec_csi = $this->getMonthlyCSI($request, $region_id, $psto_id, 12);

        $q4_csi = 0;
        $q4_csi = number_format(($oct_csi +  $nov_csi + $dec_csi) / 3, 2);

      

        // Calculate average CSI for the year
        $average_csi = number_format(($q1_csi + $q2_csi + $q3_csi + $q4_csi) / 4, 2);


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
            ->with('user', $user)
            ->with('cc_data', $cc_data)
            ->with('assignatorees', $assignatorees)
            ->with('users', $users)
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
            ->with('csi', $average_csi)
            ->with('total_comments', $total_comments)
            ->with('total_complaints', $total_complaints)
            ->with('comments', $comments);
    }



    


    public function querySearchCSF($region_id, $service_id, $unit_id , $sub_unit_id , $psto_id, $client_type, $sub_unit_type )
    {

        $customer_ids = CSFForm::when($region_id, function ($query, $region_id) {
            $query->where('region_id', $region_id);
        })
        ->when($service_id, function ($query, $service_id) {
            $query->where('service_id', $service_id);
        })
        ->when($unit_id, function ($query, $unit_id) {
            $query->where('unit_id', $unit_id);
        })
        ->when($sub_unit_id, function ($query, $sub_unit_id) {
            $query->where('sub_unit_id', $sub_unit_id);
        })
        ->when($psto_id, function ($query, $psto_id) {
            $query->where('psto_id', $psto_id);
        })
        ->when($client_type, function ($query, $client_type) use ($region_id, $service_id, $unit_id) {
            // IF HR UNIT
            if($region_id == 10 && $service_id == 2 && $unit_id == 8){
                if($client_type == "Internal"){
                    $query->where('client_type', "Internal Employees");
                }
                else if($client_type == "External"){
                    $query->where(function ($q) {
                        $q->where('client_type', "General Public")
                          ->orWhere('client_type', "Government Employees")
                          ->orWhere('client_type', "Business/Organization");
                    });
                }
            }
        })
        ->when($sub_unit_type, function ($query, $sub_unit_type) {
            if($sub_unit_type['type_name']){
                $query->where('sub_unit_type', $sub_unit_type['type_name']);
            }
          
        })
        ->select('customer_id')
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

       // Citizen's Charter
       $cc_query = CustomerCCRating::whereMonth('created_at', $month)
                                    ->whereYear('created_at', $request->selected_year)
                                    ->whereIn('customer_id',$customer_ids)
                                    ->when($request->sex, function ($query, $sex) {
                                        $query->whereHas('customer', function ($query) use ($sex) {
                                            $query->where('sex', $sex);
                                        });
                                    })
                                    ->when($request->age_group, function ($query, $age_group) {
                                        $query->whereHas('customer', function ($query) use ($age_group) {
                                            $query->where('age_group', $age_group);
                                        });
                                    });

        //calculate CC
        $cc_data = $this->calculateCC($cc_query);

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

             // sum of all repondent with rate_score 1-5
             $x_respondents_total =  $vs_total +   $x_s_total + $n_total +  $d_total +  $vd_total;
            $x_grand_total = $x_vs_total + $x_s_total + $x_n_total + $x_d_total + $x_vd_total  ; 
         
            // right side total score divided by total repondents or customers
            if($x_grand_total != 0){
                if($dimensionId == 6){
                    $lsr_total = $x_grand_total / $x_respondents_total;
                }
                else{
                    $lsr_total = $x_grand_total / $total_respondents;
                }
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
            $customer_satisfaction_rating = ($total_respondents/$total_vss_respondents) * 100;
        }
        $customer_satisfaction_rating = number_format( $customer_satisfaction_rating , 2);

        // Customer Satisfaction Index (CSI) = (ws grand total / 5) * 100
        $customer_satisfaction_index = 0;
        if($ws_grand_total != 0){
            $customer_satisfaction_index = ($ws_grand_total/5) * 100;
        }
        $customer_satisfaction_index = number_format( $customer_satisfaction_index , 2);
        
        if($customer_satisfaction_index > 100){
            $customer_satisfaction_index = number_format(100 , 2);
        }

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
        //dd($request->all());
        if($request->csi_type == "By Month"){
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

        $numeric_month = Carbon::parse("1 {$request->selected_month}")->format('m');

        // Get customer IDs from CSFForm for the region filtered by month and year
        $csf_forms = CSFForm::where('region_id', $user->region_id)
            ->whereMonth('created_at', $numeric_month)
            ->whereYear('created_at', $request->selected_year)
            ->pluck('customer_id');

        //PART I: Citizens Charter - filter by customer IDs in the region and by month/year
        $cc_query = CustomerCCRating::whereMonth('created_at', $numeric_month)
                                    ->whereYear('created_at', $request->selected_year)
                                    ->whereIn('customer_id', $csf_forms)
                                    ->when($request->sex, function ($query, $sex) {
                                        $query->whereHas('customer', function ($query) use ($sex) {
                                            $query->where('sex', $sex);
                                        });
                                    })
                                    ->when($request->age_group, function ($query, $age_group) {
                                        $query->whereHas('customer', function ($query) use ($age_group) {
                                            $query->where('age_group', $age_group);
                                        });
                                    });
        $cc_data = $this->calculateCC($cc_query);

        // PART II:
        // --dimensions
        // --services and units
        // --totals

        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();

        $services = Services::with(['units.pstos' => function($query) {
            $query->where('region_id', 10);
        }, 'units.sub_units', 'units.sub_units.pstos' => function($query) {
            $query->where('region_id', 10);
        }, 'units.sub_units.sub_unit_types'])->get();
        //all Services and its units
        $services_units = ServiceResource::collection($services);

        // Get all units data for all services
        $all_units_data = $this->getAllUnitsData($request, $user->region_id, $numeric_month);

        //get monthly CSI
        $monthly_csi = $this->getAllUnitMonthlyCSI($request, $user->region_id, $numeric_month);
        
        // Get NPS and LSR data
        $nps_data = $this->getAllUnitNPS($request, $user->region_id, $numeric_month);
        $lsr_data = $this->getAllUnitLSR($request, $user->region_id, $numeric_month);

         //send response to front end
         return Inertia::render('CSI/AllServicesUnits/Index')
                    ->with('services_units', $services_units)
                    ->with('cc_data', $cc_data)
                    ->with('all_units_data', $all_units_data)
                    ->with('csi_total', $monthly_csi)
                    ->with('nps_total', $nps_data['nps'])
                    ->with('lsr_total', $lsr_data)
                    ->with('total_respondents', $all_units_data['grand_total_respondents'])
                    ->with('total_vss_respondents', $all_units_data['grand_total_vss_respondents'])
                    ->with('percentage_vss_respondents', $all_units_data['grand_percentage_vss_respondents'])
                    ->with('request', $request);
    }

    /**
     * Generate CSI All Unit First Quarter Report
     */
    public function generateCSIAllUnitFirstQuarter($request)
    {
        $user = Auth::user();

        // Define quarter date range (January - March)
        $startDate = Carbon::create($request->selected_year, 1, 1)->startOfDay();
        $endDate = Carbon::create($request->selected_year, 3, 31)->endOfDay();
        $numeric_months = [1, 2, 3];

        // Get customer IDs from CSFForm for the region filtered by quarter and year
        $csf_forms = CSFForm::where('region_id', $user->region_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereYear('created_at', $request->selected_year)
            ->pluck('customer_id');

        // CC Data with sex and age_group filtering
        $cc_query = CustomerCCRating::whereBetween('created_at', [$startDate, $endDate])
            ->whereYear('created_at', $request->selected_year)
            ->whereIn('customer_id', $csf_forms)
            ->when($request->sex, function ($query, $sex) {
                $query->whereHas('customer', function ($query) use ($sex) {
                    $query->where('sex', $sex);
                });
            })
            ->when($request->age_group, function ($query, $age_group) {
                $query->whereHas('customer', function ($query) use ($age_group) {
                    $query->where('age_group', $age_group);
                });
            });
        $cc_data = $this->calculateCC($cc_query);

        // Get all units data
        $all_units_data = $this->getAllUnitsDataByQuarter($request, $user->region_id, $numeric_months);

        $services = Services::with(['units.pstos' => function($query) {
            $query->where('region_id', 10);
        }, 'units.sub_units', 'units.sub_units.pstos' => function($query) {
            $query->where('region_id', 10);
        }, 'units.sub_units.sub_unit_types'])->get();
        $services_units = ServiceResource::collection($services);

        // Calculate quarterly CSI
        $quarterly_csi = $this->getAllUnitQuarterlyCSI($request, $user->region_id, $numeric_months);
        
        // Get NPS and LSR data
        $nps_data = $this->getAllUnitNPSByQuarter($request, $user->region_id, $numeric_months);
        $lsr_data = $this->getAllUnitLSRByQuarter($request, $user->region_id, $numeric_months);

        return Inertia::render('CSI/AllServicesUnits/Index')
            ->with('services_units', $services_units)
            ->with('cc_data', $cc_data)
            ->with('all_units_data', $all_units_data)
            ->with('csi_total', $quarterly_csi)
            ->with('nps_total', $nps_data['nps'])
            ->with('lsr_total', $lsr_data)
            ->with('total_respondents', $all_units_data['grand_total_respondents'])
            ->with('total_vss_respondents', $all_units_data['grand_total_vss_respondents'])
            ->with('percentage_vss_respondents', $all_units_data['grand_percentage_vss_respondents'])
            ->with('request', $request);
    }

    /**
     * Generate CSI All Unit Second Quarter Report
     */
    public function generateCSIAllUnitSecondQuarter($request)
    {
        $user = Auth::user();

        // Define quarter date range (April - June)
        $startDate = Carbon::create($request->selected_year, 4, 1)->startOfDay();
        $endDate = Carbon::create($request->selected_year, 6, 31)->endOfDay();
        $numeric_months = [4, 5, 6];

        // Get customer IDs from CSFForm for the region filtered by quarter and year
        $csf_forms = CSFForm::where('region_id', $user->region_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereYear('created_at', $request->selected_year)
            ->pluck('customer_id');

        // CC Data with sex and age_group filtering
        $cc_query = CustomerCCRating::whereBetween('created_at', [$startDate, $endDate])
            ->whereYear('created_at', $request->selected_year)
            ->whereIn('customer_id', $csf_forms)
            ->when($request->sex, function ($query, $sex) {
                $query->whereHas('customer', function ($query) use ($sex) {
                    $query->where('sex', $sex);
                });
            })
            ->when($request->age_group, function ($query, $age_group) {
                $query->whereHas('customer', function ($query) use ($age_group) {
                    $query->where('age_group', $age_group);
                });
            });
        $cc_data = $this->calculateCC($cc_query);

        // Get all units data
        $all_units_data = $this->getAllUnitsDataByQuarter($request, $user->region_id, $numeric_months);

        $services = Services::with(['units.pstos' => function($query) {
            $query->where('region_id', 10);
        }, 'units.sub_units', 'units.sub_units.pstos' => function($query) {
            $query->where('region_id', 10);
        }, 'units.sub_units.sub_unit_types'])->get();
        $services_units = ServiceResource::collection($services);

        // Calculate quarterly CSI
        $quarterly_csi = $this->getAllUnitQuarterlyCSI($request, $user->region_id, $numeric_months);
        
        // Get NPS and LSR data
        $nps_data = $this->getAllUnitNPSByQuarter($request, $user->region_id, $numeric_months);
        $lsr_data = $this->getAllUnitLSRByQuarter($request, $user->region_id, $numeric_months);

        return Inertia::render('CSI/AllServicesUnits/Index')
            ->with('services_units', $services_units)
            ->with('cc_data', $cc_data)
            ->with('all_units_data', $all_units_data)
            ->with('csi_total', $quarterly_csi)
            ->with('nps_total', $nps_data['nps'])
            ->with('lsr_total', $lsr_data)
            ->with('total_respondents', $all_units_data['grand_total_respondents'])
            ->with('total_vss_respondents', $all_units_data['grand_total_vss_respondents'])
            ->with('percentage_vss_respondents', $all_units_data['grand_percentage_vss_respondents'])
            ->with('request', $request);
    }

    /**
     * Generate CSI All Unit Third Quarter Report
     */
    public function generateCSIAllUnitThirdQuarter($request)
    {
        $user = Auth::user();

        // Define quarter date range (July - September)
        $startDate = Carbon::create($request->selected_year, 7, 1)->startOfDay();
        $endDate = Carbon::create($request->selected_year, 9, 30)->endOfDay();
        $numeric_months = [7, 8, 9];

        // Get customer IDs from CSFForm for the region filtered by quarter and year
        $csf_forms = CSFForm::where('region_id', $user->region_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereYear('created_at', $request->selected_year)
            ->pluck('customer_id');

        // CC Data with sex and age_group filtering
        $cc_query = CustomerCCRating::whereBetween('created_at', [$startDate, $endDate])
            ->whereYear('created_at', $request->selected_year)
            ->whereIn('customer_id', $csf_forms)
            ->when($request->sex, function ($query, $sex) {
                $query->whereHas('customer', function ($query) use ($sex) {
                    $query->where('sex', $sex);
                });
            })
            ->when($request->age_group, function ($query, $age_group) {
                $query->whereHas('customer', function ($query) use ($age_group) {
                    $query->where('age_group', $age_group);
                });
            });
        $cc_data = $this->calculateCC($cc_query);

        // Get all units data
        $all_units_data = $this->getAllUnitsDataByQuarter($request, $user->region_id, $numeric_months);

        $services = Services::with(['units.pstos' => function($query) {
            $query->where('region_id', 10);
        }, 'units.sub_units', 'units.sub_units.pstos' => function($query) {
            $query->where('region_id', 10);
        }, 'units.sub_units.sub_unit_types'])->get();
        $services_units = ServiceResource::collection($services);

        // Calculate quarterly CSI
        $quarterly_csi = $this->getAllUnitQuarterlyCSI($request, $user->region_id, $numeric_months);
        
        // Get NPS and LSR data
        $nps_data = $this->getAllUnitNPSByQuarter($request, $user->region_id, $numeric_months);
        $lsr_data = $this->getAllUnitLSRByQuarter($request, $user->region_id, $numeric_months);

        return Inertia::render('CSI/AllServicesUnits/Index')
            ->with('services_units', $services_units)
            ->with('cc_data', $cc_data)
            ->with('all_units_data', $all_units_data)
            ->with('csi_total', $quarterly_csi)
            ->with('nps_total', $nps_data['nps'])
            ->with('lsr_total', $lsr_data)
            ->with('total_respondents', $all_units_data['grand_total_respondents'])
            ->with('total_vss_respondents', $all_units_data['grand_total_vss_respondents'])
            ->with('percentage_vss_respondents', $all_units_data['grand_percentage_vss_respondents'])
            ->with('request', $request);
    }

    /**
     * Generate CSI All Unit Fourth Quarter Report
     */
    public function generateCSIAllUnitFourthQuarter($request)
    {
        $user = Auth::user();

        // Define quarter date range (October - December)
        $startDate = Carbon::create($request->selected_year, 10, 1)->startOfDay();
        $endDate = Carbon::create($request->selected_year, 12, 31)->endOfDay();
        $numeric_months = [10, 11, 12];

        // Get customer IDs from CSFForm for the region filtered by quarter and year
        $csf_forms = CSFForm::where('region_id', $user->region_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereYear('created_at', $request->selected_year)
            ->pluck('customer_id');

        // CC Data with sex and age_group filtering
        $cc_query = CustomerCCRating::whereBetween('created_at', [$startDate, $endDate])
            ->whereYear('created_at', $request->selected_year)
            ->whereIn('customer_id', $csf_forms)
            ->when($request->sex, function ($query, $sex) {
                $query->whereHas('customer', function ($query) use ($sex) {
                    $query->where('sex', $sex);
                });
            })
            ->when($request->age_group, function ($query, $age_group) {
                $query->whereHas('customer', function ($query) use ($age_group) {
                    $query->where('age_group', $age_group);
                });
            });
        $cc_data = $this->calculateCC($cc_query);

        // Get all units data
        $all_units_data = $this->getAllUnitsDataByQuarter($request, $user->region_id, $numeric_months);

        $services = Services::with(['units.pstos' => function($query) {
            $query->where('region_id', 10);
        }, 'units.sub_units', 'units.sub_units.pstos' => function($query) {
            $query->where('region_id', 10);
        }, 'units.sub_units.sub_unit_types'])->get();
        $services_units = ServiceResource::collection($services);

        // Calculate quarterly CSI
        $quarterly_csi = $this->getAllUnitQuarterlyCSI($request, $user->region_id, $numeric_months);
        
        // Get NPS and LSR data
        $nps_data = $this->getAllUnitNPSByQuarter($request, $user->region_id, $numeric_months);
        $lsr_data = $this->getAllUnitLSRByQuarter($request, $user->region_id, $numeric_months);

        return Inertia::render('CSI/AllServicesUnits/Index')
            ->with('services_units', $services_units)
            ->with('cc_data', $cc_data)
            ->with('all_units_data', $all_units_data)
            ->with('csi_total', $quarterly_csi)
            ->with('nps_total', $nps_data['nps'])
            ->with('lsr_total', $lsr_data)
            ->with('total_respondents', $all_units_data['grand_total_respondents'])
            ->with('total_vss_respondents', $all_units_data['grand_total_vss_respondents'])
            ->with('percentage_vss_respondents', $all_units_data['grand_percentage_vss_respondents'])
            ->with('request', $request);
    }

    /**
     * Generate CSI All Unit Yearly Report
     */
    public function generateCSIAllUnitYearly($request)
    {
        $user = Auth::user();

        // Define year date range
        $startDate = Carbon::create($request->selected_year, 1, 1)->startOfDay();
        $endDate = Carbon::create($request->selected_year, 12, 31)->endOfDay();
        $numeric_months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

        // Get customer IDs from CSFForm for the region filtered by year
        $csf_forms = CSFForm::where('region_id', $user->region_id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereYear('created_at', $request->selected_year)
            ->pluck('customer_id');

        // CC Data with sex and age_group filtering
        $cc_query = CustomerCCRating::whereBetween('created_at', [$startDate, $endDate])
            ->whereYear('created_at', $request->selected_year)
            ->whereIn('customer_id', $csf_forms)
            ->when($request->sex, function ($query, $sex) {
                $query->whereHas('customer', function ($query) use ($sex) {
                    $query->where('sex', $sex);
                });
            })
            ->when($request->age_group, function ($query, $age_group) {
                $query->whereHas('customer', function ($query) use ($age_group) {
                    $query->where('age_group', $age_group);
                });
            });
        $cc_data = $this->calculateCC($cc_query);

        // Get all units data
        $all_units_data = $this->getAllUnitsDataByQuarter($request, $user->region_id, $numeric_months);

        $services = Services::with(['units.pstos' => function($query) {
            $query->where('region_id', 10);
        }, 'units.sub_units', 'units.sub_units.pstos' => function($query) {
            $query->where('region_id', 10);
        }, 'units.sub_units.sub_unit_types'])->get();
        $services_units = ServiceResource::collection($services);

        // Calculate yearly CSI (average of all months)
        $yearly_csi = $this->getAllUnitQuarterlyCSI($request, $user->region_id, $numeric_months);
        
        // Get NPS and LSR data
        $nps_data = $this->getAllUnitNPSByQuarter($request, $user->region_id, $numeric_months);
        $lsr_data = $this->getAllUnitLSRByQuarter($request, $user->region_id, $numeric_months);

        return Inertia::render('CSI/AllServicesUnits/Index')
            ->with('services_units', $services_units)
            ->with('cc_data', $cc_data)
            ->with('all_units_data', $all_units_data)
            ->with('csi_total', $yearly_csi)
            ->with('nps_total', $nps_data['nps'])
            ->with('lsr_total', $lsr_data)
            ->with('total_respondents', $all_units_data['grand_total_respondents'])
            ->with('total_vss_respondents', $all_units_data['grand_total_vss_respondents'])
            ->with('percentage_vss_respondents', $all_units_data['grand_percentage_vss_respondents'])
            ->with('request', $request);
    }

    /**
     * Get all units data by quarter (for quarterly and yearly reports)
     */
    private function getAllUnitsDataByQuarter($request, $region_id, $numeric_months)
    {
        $services = Services::all();
        $units_data = [];
        $grand_total_respondents = 0;
        $grand_total_vss_respondents = 0;
        $grand_total_promoters = 0;
        $grand_total_detractors = 0;
        
        // Grand totals for rating percentages
        $grand_strongly_agree_count = 0;
        $grand_agree_count = 0;
        $grand_neither_count = 0;
        $grand_disagree_count = 0;
        $grand_strongly_disagree_count = 0;
        $grand_na_count = 0;
        
        // Grand CC totals
        $grand_cc1_ans1 = 0;
        $grand_cc1_ans2 = 0;
        $grand_cc1_ans3 = 0;
        $grand_cc1_ans4 = 0;
        $grand_cc2_ans1 = 0;
        $grand_cc2_ans2 = 0;
        $grand_cc2_ans3 = 0;
        $grand_cc2_ans4 = 0;
        $grand_cc2_ans5 = 0;
        $grand_cc3_ans1 = 0;
        $grand_cc3_ans2 = 0;
        $grand_cc3_ans3 = 0;
        $grand_cc3_ans4 = 0;
        
        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();

        foreach ($services as $service) {
            $service_units = Unit::where('services_id', $service->id)->get();
            
            foreach ($service_units as $unit) {
                // Get customer IDs for this unit filtered by months and year
                $customer_ids = CsfForm::where('region_id', $region_id)
                    ->where('service_id', $service->id)
                    ->where('unit_id', $unit->id)
                    ->whereIn(DB::raw('MONTH(created_at)'), $numeric_months)
                    ->whereYear('created_at', $request->selected_year)
                    ->pluck('customer_id');

                // Total number of respondents (unique customers)
                $total_respo = $customer_ids->count();

                // Get attribute ratings for this unit
                $attribute_ratings = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                    ->whereIn(DB::raw('MONTH(created_at)'), $numeric_months)
                    ->whereYear('created_at', $request->selected_year)
                    ->get();

                // Total number of respondents who rated 5 or 4 (Very Satisfied or Satisfied)
                $total_vss_respo = $attribute_ratings->whereIn('rate_score', [4, 5])->groupBy('customer_id')->count();

                // Percentage of respondents who rated 5 or 4 (Very Satisfied or Satisfied)
                $percentage_vss_respo = 0;
                if ($total_respo > 0 && $total_vss_respo > 0) {
                    $percentage_vss_respo = ($total_vss_respo / $total_respo) * 100;
                }

                // Get recommendation ratings for NPS calculation
                $recommendation_ratings = CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                    ->whereIn(DB::raw('MONTH(created_at)'), $numeric_months)
                    ->whereYear('created_at', $request->selected_year)
                    ->get();

                // Calculate NPS per unit
                $promoters = $recommendation_ratings->whereBetween('recommend_rate_score', [7, 10])->groupBy('customer_id')->count();
                $detractors = $recommendation_ratings->whereBetween('recommend_rate_score', [0, 6])->groupBy('customer_id')->count();
                
                $nps = 0;
                if ($total_respo > 0) {
                    $percentage_promoters = ($promoters / $total_respo) * 100;
                    $percentage_detractors = ($detractors / $total_respo) * 100;
                    $nps = $percentage_promoters - $percentage_detractors;
                }

                // Calculate LSR per unit (Likert Scale Rating)
                $lsr = 0;
                if ($attribute_ratings->count() > 0) {
                    $total_score = $attribute_ratings->sum('rate_score');
                    $total_responses = $attribute_ratings->count();
                    $lsr = $total_score / $total_responses;
                }

                // Calculate CSI per unit using Weighted Sum method
                $unit_csi = $this->calculateUnitCSI($attribute_ratings, $total_respo, $dimension_count);

                // Calculate percentage breakdown for each rating
                $strongly_agree_count = $attribute_ratings->where('rate_score', 5)->count();
                $agree_count = $attribute_ratings->where('rate_score', 4)->count();
                $neither_count = $attribute_ratings->where('rate_score', 3)->count();
                $disagree_count = $attribute_ratings->where('rate_score', 2)->count();
                $strongly_disagree_count = $attribute_ratings->where('rate_score', 1)->count();
                $na_count = $attribute_ratings->where('rate_score', 6)->count();
                
                // Total count for percentage calculation
                $total_ratings = $strongly_agree_count + $agree_count + $neither_count + $disagree_count + $strongly_disagree_count + $na_count;

                // Calculate percentages
                $pct_strongly_agree = $total_ratings > 0 ? ($strongly_agree_count / $total_ratings) * 100 : 0;
                $pct_agree = $total_ratings > 0 ? ($agree_count / $total_ratings) * 100 : 0;
                $pct_neither = $total_ratings > 0 ? ($neither_count / $total_ratings) * 100 : 0;
                $pct_disagree = $total_ratings > 0 ? ($disagree_count / $total_ratings) * 100 : 0;
                $pct_strongly_disagree = $total_ratings > 0 ? ($strongly_disagree_count / $total_ratings) * 100 : 0;
                $pct_na = $total_ratings > 0 ? ($na_count / $total_ratings) * 100 : 0;

                // Get CC data for this unit with sex and age_group filtering
                $cc_ratings = CustomerCCRating::whereIn('customer_id', $customer_ids)
                    ->whereIn(DB::raw('MONTH(created_at)'), $numeric_months)
                    ->whereYear('created_at', $request->selected_year)
                    ->when($request->sex, function ($query, $sex) {
                        $query->whereHas('customer', function ($query) use ($sex) {
                            $query->where('sex', $sex);
                        });
                    })
                    ->when($request->age_group, function ($query, $age_group) {
                        $query->whereHas('customer', function ($query) use ($age_group) {
                            $query->where('age_group', $age_group);
                        });
                    })
                    ->get();

                $cc1_ans1 = $cc_ratings->where('cc_id', 1)->where('answer', 1)->count();
                $cc1_ans2 = $cc_ratings->where('cc_id', 1)->where('answer', 2)->count();
                $cc1_ans3 = $cc_ratings->where('cc_id', 1)->where('answer', 3)->count();
                $cc1_ans4 = $cc_ratings->where('cc_id', 1)->where('answer', 4)->count();
                $cc2_ans1 = $cc_ratings->where('cc_id', 2)->where('answer', 1)->count();
                $cc2_ans2 = $cc_ratings->where('cc_id', 2)->where('answer', 2)->count();
                $cc2_ans3 = $cc_ratings->where('cc_id', 2)->where('answer', 3)->count();
                $cc2_ans4 = $cc_ratings->where('cc_id', 2)->where('answer', 4)->count();
                $cc2_ans5 = $cc_ratings->where('cc_id', 2)->where('answer', 5)->count();
                $cc3_ans1 = $cc_ratings->where('cc_id', 3)->where('answer', 1)->count();
                $cc3_ans2 = $cc_ratings->where('cc_id', 3)->where('answer', 2)->count();
                $cc3_ans3 = $cc_ratings->where('cc_id', 3)->where('answer', 3)->count();
                $cc3_ans4 = $cc_ratings->where('cc_id', 3)->where('answer', 4)->count();

                // Get sub-units for this unit (simplified for quarterly/yearly - no sub-unit data)
                $sub_units_data = [];

                $units_data[$service->id][$unit->id] = [
                    'unit_name' => $unit->unit_name,
                    'total_respo' => $total_respo,
                    'sub_units_data' => $sub_units_data,
                    'unit_pstos_data' => [],
                    'total_vss_respo' => $total_vss_respo,
                    'percentage_vss_respo' => number_format($percentage_vss_respo, 2),
                    'csi' => number_format($unit_csi, 2),
                    'nps' => number_format($nps, 2),
                    'lsr' => number_format($lsr, 2),
                    'strongly_agree_count' => $strongly_agree_count,
                    'agree_count' => $agree_count,
                    'neither_count' => $neither_count,
                    'disagree_count' => $disagree_count,
                    'strongly_disagree_count' => $strongly_disagree_count,
                    'na_count' => $na_count,
                    'pct_strongly_agree' => number_format($pct_strongly_agree, 2),
                    'pct_agree' => number_format($pct_agree, 2),
                    'pct_neither' => number_format($pct_neither, 2),
                    'pct_disagree' => number_format($pct_disagree, 2),
                    'pct_strongly_disagree' => number_format($pct_strongly_disagree, 2),
                    'pct_na' => number_format($pct_na, 2),
                    'cc_data' => [
                        'cc1_ans1' => $cc1_ans1,
                        'cc1_ans2' => $cc1_ans2,
                        'cc1_ans3' => $cc1_ans3,
                        'cc1_ans4' => $cc1_ans4,
                        'cc2_ans1' => $cc2_ans1,
                        'cc2_ans2' => $cc2_ans2,
                        'cc2_ans3' => $cc2_ans3,
                        'cc2_ans4' => $cc2_ans4,
                        'cc2_ans5' => $cc2_ans5,
                        'cc3_ans1' => $cc3_ans1,
                        'cc3_ans2' => $cc3_ans2,
                        'cc3_ans3' => $cc3_ans3,
                        'cc3_ans4' => $cc3_ans4,
                    ],
                ];

                $grand_cc1_ans1 += $cc1_ans1;
                $grand_cc1_ans2 += $cc1_ans2;
                $grand_cc1_ans3 += $cc1_ans3;
                $grand_cc1_ans4 += $cc1_ans4;
                $grand_cc2_ans1 += $cc2_ans1;
                $grand_cc2_ans2 += $cc2_ans2;
                $grand_cc2_ans3 += $cc2_ans3;
                $grand_cc2_ans4 += $cc2_ans4;
                $grand_cc2_ans5 += $cc2_ans5;
                $grand_cc3_ans1 += $cc3_ans1;
                $grand_cc3_ans2 += $cc3_ans2;
                $grand_cc3_ans3 += $cc3_ans3;
                $grand_cc3_ans4 += $cc3_ans4;
                
                $grand_total_respondents += $total_respo;
                $grand_total_vss_respondents += $total_vss_respo;
                $grand_total_promoters += $promoters;
                $grand_total_detractors += $detractors;
                
                $grand_strongly_agree_count += $strongly_agree_count;
                $grand_agree_count += $agree_count;
                $grand_neither_count += $neither_count;
                $grand_disagree_count += $disagree_count;
                $grand_strongly_disagree_count += $strongly_disagree_count;
                $grand_na_count += $na_count;
            }
        }

        // Grand percentage for VSS
        $grand_percentage_vss_respondents = 0;
        if ($grand_total_respondents > 0 && $grand_total_vss_respondents > 0) {
            $grand_percentage_vss_respondents = ($grand_total_vss_respondents / $grand_total_respondents) * 100;
        }

        // Grand NPS
        $grand_nps = 0;
        if ($grand_total_respondents > 0) {
            $grand_percentage_promoters = ($grand_total_promoters / $grand_total_respondents) * 100;
            $grand_percentage_detractors = ($grand_total_detractors / $grand_total_respondents) * 100;
            $grand_nps = $grand_percentage_promoters - $grand_percentage_detractors;
        }

        // Grand CC totals
        $grand_cc1_total = $grand_cc1_ans1 + $grand_cc1_ans2 + $grand_cc1_ans3 + $grand_cc1_ans4;
        $grand_cc2_total = $grand_cc2_ans1 + $grand_cc2_ans2 + $grand_cc2_ans3 + $grand_cc2_ans4 + $grand_cc2_ans5;
        $grand_cc3_total = $grand_cc3_ans1 + $grand_cc3_ans2 + $grand_cc3_ans3 + $grand_cc3_ans4;

        // Grand total ratings for percentage calculation
        $grand_total_ratings = $grand_strongly_agree_count + $grand_agree_count + $grand_neither_count + $grand_disagree_count + $grand_strongly_disagree_count + $grand_na_count;
        
        // Grand percentages for rating categories
        $grand_pct_strongly_agree = $grand_total_ratings > 0 ? ($grand_strongly_agree_count / $grand_total_ratings) * 100 : 0;
        $grand_pct_agree = $grand_total_ratings > 0 ? ($grand_agree_count / $grand_total_ratings) * 100 : 0;
        $grand_pct_neither = $grand_total_ratings > 0 ? ($grand_neither_count / $grand_total_ratings) * 100 : 0;
        $grand_pct_disagree = $grand_total_ratings > 0 ? ($grand_disagree_count / $grand_total_ratings) * 100 : 0;
        $grand_pct_strongly_disagree = $grand_total_ratings > 0 ? ($grand_strongly_disagree_count / $grand_total_ratings) * 100 : 0;
        $grand_pct_na = $grand_total_ratings > 0 ? ($grand_na_count / $grand_total_ratings) * 100 : 0;

        return [
            'units_data' => $units_data,
            'grand_total_respondents' => $grand_total_respondents,
            'grand_total_vss_respondents' => $grand_total_vss_respondents,
            'grand_percentage_vss_respondents' => number_format($grand_percentage_vss_respondents, 2),
            'grand_nps' => number_format($grand_nps, 2),
            'grand_pct_strongly_agree' => number_format($grand_pct_strongly_agree, 2),
            'grand_pct_agree' => number_format($grand_pct_agree, 2),
            'grand_pct_neither' => number_format($grand_pct_neither, 2),
            'grand_pct_disagree' => number_format($grand_pct_disagree, 2),
            'grand_pct_strongly_disagree' => number_format($grand_pct_strongly_disagree, 2),
            'grand_pct_na' => number_format($grand_pct_na, 2),
            'grand_cc_data' => [
                'cc1_ans1' => $grand_cc1_ans1,
                'cc1_ans2' => $grand_cc1_ans2,
                'cc1_ans3' => $grand_cc1_ans3,
                'cc1_ans4' => $grand_cc1_ans4,
                'cc1_total' => $grand_cc1_total,
                'cc2_ans1' => $grand_cc2_ans1,
                'cc2_ans2' => $grand_cc2_ans2,
                'cc2_ans3' => $grand_cc2_ans3,
                'cc2_ans4' => $grand_cc2_ans4,
                'cc2_ans5' => $grand_cc2_ans5,
                'cc2_total' => $grand_cc2_total,
                'cc3_ans1' => $grand_cc3_ans1,
                'cc3_ans2' => $grand_cc3_ans2,
                'cc3_ans3' => $grand_cc3_ans3,
                'cc3_ans4' => $grand_cc3_ans4,
                'cc3_total' => $grand_cc3_total,
            ],
        ];
    }

    /**
     * Get quarterly/yearly CSI for all units
     */
    private function getAllUnitQuarterlyCSI($request, $region_id, $numeric_months)
    {
        $total_csi = 0;
        $count = 0;
        
        foreach ($numeric_months as $month) {
            $csi = $this->getAllUnitMonthlyCSI($request, $region_id, $month);
            if ($csi > 0) {
                $total_csi += $csi;
                $count++;
            }
        }
        
        return $count > 0 ? $total_csi / $count : 0;
    }

    /**
     * Get quarterly/yearly NPS for all units
     */
    private function getAllUnitNPSByQuarter($request, $region_id, $numeric_months)
    {
        $total_promoters = 0;
        $total_detractors = 0;
        $total_respondents = 0;
        
        foreach ($numeric_months as $month) {
            $customer_ids = CsfForm::where('region_id', $region_id)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $request->selected_year)
                ->pluck('customer_id');

            $recommendation_ratings = CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $request->selected_year)
                ->get();

            $month_respondents = $recommendation_ratings->groupBy('customer_id')->count();
            $month_promoters = $recommendation_ratings->whereBetween('recommend_rate_score', [7, 10])->groupBy('customer_id')->count();
            $month_detractors = $recommendation_ratings->whereBetween('recommend_rate_score', [0, 6])->groupBy('customer_id')->count();
            
            $total_respondents += $month_respondents;
            $total_promoters += $month_promoters;
            $total_detractors += $month_detractors;
        }
        
        $percentage_promoters = $total_respondents > 0 ? ($total_promoters / $total_respondents) * 100 : 0;
        $percentage_detractors = $total_respondents > 0 ? ($total_detractors / $total_respondents) * 100 : 0;
        $nps = $percentage_promoters - $percentage_detractors;

        return [
            'nps' => number_format($nps, 2),
            'percentage_promoters' => number_format($percentage_promoters, 2),
            'percentage_detractors' => number_format($percentage_detractors, 2),
            'total_respondents' => $total_respondents,
            'promoters' => $total_promoters,
            'detractors' => $total_detractors,
        ];
    }

    /**
     * Get quarterly/yearly LSR for all units
     */
    private function getAllUnitLSRByQuarter($request, $region_id, $numeric_months)
    {
        $grand_total_score = 0;
        $grand_total_responses = 0;

        foreach ($numeric_months as $month) {
            $customer_ids = CsfForm::where('region_id', $region_id)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $request->selected_year)
                ->pluck('customer_id');

            $attribute_ratings = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $request->selected_year)
                ->get();

            $grand_total_score += $attribute_ratings->sum('rate_score');
            $grand_total_responses += $attribute_ratings->count();
        }

        $lsr = $grand_total_responses > 0 ? $grand_total_score / $grand_total_responses : 0;
        return number_format($lsr, 2);
    }

    /**
     * Get all units data for all services with per-unit calculations
     */
    private function getAllUnitsData($request, $region_id, $numeric_month)
    {
        $services = Services::all();
        $units_data = [];
        $grand_total_respondents = 0;
        $grand_total_vss_respondents = 0;
        $grand_total_promoters = 0;
        $grand_total_detractors = 0;
        
        // Grand totals for rating percentages
        $grand_strongly_agree_count = 0;
        $grand_agree_count = 0;
        $grand_neither_count = 0;
        $grand_disagree_count = 0;
        $grand_strongly_disagree_count = 0;
        $grand_na_count = 0;
        
        // Grand CC totals
        $grand_cc1_ans1 = 0;
        $grand_cc1_ans2 = 0;
        $grand_cc1_ans3 = 0;
        $grand_cc1_ans4 = 0;
        $grand_cc2_ans1 = 0;
        $grand_cc2_ans2 = 0;
        $grand_cc2_ans3 = 0;
        $grand_cc2_ans4 = 0;
        $grand_cc2_ans5 = 0;
        $grand_cc3_ans1 = 0;
        $grand_cc3_ans2 = 0;
        $grand_cc3_ans3 = 0;
        $grand_cc3_ans4 = 0;
        
        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();

        foreach ($services as $service) {
            $service_units = Unit::where('services_id', $service->id)->get();
            
            foreach ($service_units as $unit) {
                // Get customer IDs for this unit filtered by month and year
                $customer_ids = CsfForm::where('region_id', $region_id)
                    ->where('service_id', $service->id)
                    ->where('unit_id', $unit->id)
                    ->whereMonth('created_at', $numeric_month)
                    ->whereYear('created_at', $request->selected_year)
                    ->pluck('customer_id');

                // Total number of respondents (unique customers)
                $total_respo = $customer_ids->count();

                // Get attribute ratings for this unit
                $attribute_ratings = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                    ->whereMonth('created_at', $numeric_month)
                    ->whereYear('created_at', $request->selected_year)
                    ->get();

                // Total number of respondents who rated 5 or 4 (Very Satisfied or Satisfied)
                $total_vss_respo = $attribute_ratings->whereIn('rate_score', [4, 5])->groupBy('customer_id')->count();

                // Percentage of respondents who rated 5 or 4 (Very Satisfied or Satisfied)
                $percentage_vss_respo = 0;
                if ($total_respo > 0 && $total_vss_respo > 0) {
                    $percentage_vss_respo = ($total_vss_respo / $total_respo) * 100;
                }

                // Get recommendation ratings for NPS calculation
                $recommendation_ratings = CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
                    ->whereMonth('created_at', $numeric_month)
                    ->whereYear('created_at', $request->selected_year)
                    ->get();

                // Calculate NPS per unit
                $promoters = $recommendation_ratings->whereBetween('recommend_rate_score', [7, 10])->groupBy('customer_id')->count();
                $detractors = $recommendation_ratings->whereBetween('recommend_rate_score', [0, 6])->groupBy('customer_id')->count();
                
                $nps = 0;
                if ($total_respo > 0) {
                    $percentage_promoters = ($promoters / $total_respo) * 100;
                    $percentage_detractors = ($detractors / $total_respo) * 100;
                    $nps = $percentage_promoters - $percentage_detractors;
                }

                // Calculate LSR per unit (Likert Scale Rating)
                $lsr = 0;
                if ($attribute_ratings->count() > 0) {
                    $total_score = $attribute_ratings->sum('rate_score');
                    $total_responses = $attribute_ratings->count();
                    $lsr = $total_score / $total_responses;
                }

                // Calculate CSI per unit using Weighted Sum method
                $unit_csi = $this->calculateUnitCSI($attribute_ratings, $total_respo, $dimension_count);

                // Calculate percentage breakdown for each rating (based on total responses)
                // Count all ratings (not unique customers) so percentages add up to 100%
                $strongly_agree_count = $attribute_ratings->where('rate_score', 5)->count();
                $agree_count = $attribute_ratings->where('rate_score', 4)->count();
                $neither_count = $attribute_ratings->where('rate_score', 3)->count();
                $disagree_count = $attribute_ratings->where('rate_score', 2)->count();
                $strongly_disagree_count = $attribute_ratings->where('rate_score', 1)->count();
                $na_count = $attribute_ratings->where('rate_score', 6)->count();
                
                // Total count for percentage calculation
                $total_ratings = $strongly_agree_count + $agree_count + $neither_count + $disagree_count + $strongly_disagree_count + $na_count;

                // Calculate percentages based on total ratings (so they add up to 100%)
                $pct_strongly_agree = $total_ratings > 0 ? ($strongly_agree_count / $total_ratings) * 100 : 0;
                $pct_agree = $total_ratings > 0 ? ($agree_count / $total_ratings) * 100 : 0;
                $pct_neither = $total_ratings > 0 ? ($neither_count / $total_ratings) * 100 : 0;
                $pct_disagree = $total_ratings > 0 ? ($disagree_count / $total_ratings) * 100 : 0;
                $pct_strongly_disagree = $total_ratings > 0 ? ($strongly_disagree_count / $total_ratings) * 100 : 0;
                $pct_na = $total_ratings > 0 ? ($na_count / $total_ratings) * 100 : 0;

                // Get CC data for this unit
                $cc_ratings = CustomerCCRating::whereIn('customer_id', $customer_ids)
                    ->whereMonth('created_at', $numeric_month)
                    ->whereYear('created_at', $request->selected_year)
                    ->when($request->sex, function ($query, $sex) {
                        $query->whereHas('customer', function ($query) use ($sex) {
                            $query->where('sex', $sex);
                        });
                    })
                    ->when($request->age_group, function ($query, $age_group) {
                        $query->whereHas('customer', function ($query) use ($age_group) {
                            $query->where('age_group', $age_group);
                        });
                    })
                    ->get();

                $cc1_ans1 = $cc_ratings->where('cc_id', 1)->where('answer', 1)->count();
                $cc1_ans2 = $cc_ratings->where('cc_id', 1)->where('answer', 2)->count();
                $cc1_ans3 = $cc_ratings->where('cc_id', 1)->where('answer', 3)->count();
                $cc1_ans4 = $cc_ratings->where('cc_id', 1)->where('answer', 4)->count();
                $cc2_ans1 = $cc_ratings->where('cc_id', 2)->where('answer', 1)->count();
                $cc2_ans2 = $cc_ratings->where('cc_id', 2)->where('answer', 2)->count();
                $cc2_ans3 = $cc_ratings->where('cc_id', 2)->where('answer', 3)->count();
                $cc2_ans4 = $cc_ratings->where('cc_id', 2)->where('answer', 4)->count();
                $cc2_ans5 = $cc_ratings->where('cc_id', 2)->where('answer', 5)->count();
                $cc3_ans1 = $cc_ratings->where('cc_id', 3)->where('answer', 1)->count();
                $cc3_ans2 = $cc_ratings->where('cc_id', 3)->where('answer', 2)->count();
                $cc3_ans3 = $cc_ratings->where('cc_id', 3)->where('answer', 3)->count();
                $cc3_ans4 = $cc_ratings->where('cc_id', 3)->where('answer', 4)->count();

                // Get sub-units for this unit
                $sub_units = SubUnit::where('unit_id', $unit->id)->get();
                $sub_units_data = [];
                
                foreach ($sub_units as $sub_unit) {
                    // Get customer IDs for this sub-unit filtered by month and year
                    $sub_customer_ids = CsfForm::where('region_id', $region_id)
                        ->where('service_id', $service->id)
                        ->where('unit_id', $unit->id)
                        ->where('sub_unit_id', $sub_unit->id)
                        ->whereMonth('created_at', $numeric_month)
                        ->whereYear('created_at', $request->selected_year)
                        ->pluck('customer_id');

                    // Total number of respondents for sub-unit
                    $sub_total_respo = $sub_customer_ids->count();

                    // Get attribute ratings for this sub-unit
                    $sub_attribute_ratings = CustomerAttributeRating::whereIn('customer_id', $sub_customer_ids)
                        ->whereMonth('created_at', $numeric_month)
                        ->whereYear('created_at', $request->selected_year)
                        ->get();

                    // Total number of respondents who rated 5 or 4 (Very Satisfied or Satisfied)
                    $sub_total_vss_respo = $sub_attribute_ratings->whereIn('rate_score', [4, 5])->groupBy('customer_id')->count();

                    // Percentage of respondents who rated 5 or 4
                    $sub_percentage_vss_respo = 0;
                    if ($sub_total_respo > 0 && $sub_total_vss_respo > 0) {
                        $sub_percentage_vss_respo = ($sub_total_vss_respo / $sub_total_respo) * 100;
                    }

                    // Calculate percentage breakdown for each rating
                    $sub_strongly_agree_count = $sub_attribute_ratings->where('rate_score', 5)->count();
                    $sub_agree_count = $sub_attribute_ratings->where('rate_score', 4)->count();
                    $sub_neither_count = $sub_attribute_ratings->where('rate_score', 3)->count();
                    $sub_disagree_count = $sub_attribute_ratings->where('rate_score', 2)->count();
                    $sub_strongly_disagree_count = $sub_attribute_ratings->where('rate_score', 1)->count();
                    $sub_na_count = $sub_attribute_ratings->where('rate_score', 6)->count();
                    
                    // Total count for percentage calculation
                    $sub_total_ratings = $sub_strongly_agree_count + $sub_agree_count + $sub_neither_count + $sub_disagree_count + $sub_strongly_disagree_count + $sub_na_count;

                    // Calculate percentages
                    $sub_pct_strongly_agree = $sub_total_ratings > 0 ? ($sub_strongly_agree_count / $sub_total_ratings) * 100 : 0;
                    $sub_pct_agree = $sub_total_ratings > 0 ? ($sub_agree_count / $sub_total_ratings) * 100 : 0;
                    $sub_pct_neither = $sub_total_ratings > 0 ? ($sub_neither_count / $sub_total_ratings) * 100 : 0;
                    $sub_pct_disagree = $sub_total_ratings > 0 ? ($sub_disagree_count / $sub_total_ratings) * 100 : 0;
                    $sub_pct_strongly_disagree = $sub_total_ratings > 0 ? ($sub_strongly_disagree_count / $sub_total_ratings) * 100 : 0;
                    $sub_pct_na = $sub_total_ratings > 0 ? ($sub_na_count / $sub_total_ratings) * 100 : 0;

                    // Get sub-unit types for this sub-unit
                    $sub_unit_types = SubUnitType::where('sub_unit_id', $sub_unit->id)->get();
                    $sub_unit_types_data = [];
                    
                    foreach ($sub_unit_types as $sub_unit_type) {
                        // Get customer IDs for this sub-unit type filtered by month and year
                        $sub_type_customer_ids = CsfForm::where('region_id', $region_id)
                            ->where('service_id', $service->id)
                            ->where('unit_id', $unit->id)
                            ->where('sub_unit_id', $sub_unit->id)
                            ->where('sub_unit_type', $sub_unit_type->type_name)
                            ->whereMonth('created_at', $numeric_month)
                            ->whereYear('created_at', $request->selected_year)
                            ->pluck('customer_id');

                        // Total number of respondents for sub-unit type
                        $sub_type_total_respo = $sub_type_customer_ids->count();

                        // Get attribute ratings for this sub-unit type
                        $sub_type_attribute_ratings = CustomerAttributeRating::whereIn('customer_id', $sub_type_customer_ids)
                            ->whereMonth('created_at', $numeric_month)
                            ->whereYear('created_at', $request->selected_year)
                            ->get();

                        // Total number of respondents who rated 5 or 4
                        $sub_type_total_vss_respo = $sub_type_attribute_ratings->whereIn('rate_score', [4, 5])->groupBy('customer_id')->count();

                        // Percentage of respondents who rated 5 or 4
                        $sub_type_percentage_vss_respo = 0;
                        if ($sub_type_total_respo > 0 && $sub_type_total_vss_respo > 0) {
                            $sub_type_percentage_vss_respo = ($sub_type_total_vss_respo / $sub_type_total_respo) * 100;
                        }

                        // Calculate percentage breakdown for each rating
                        $sub_type_strongly_agree_count = $sub_type_attribute_ratings->where('rate_score', 5)->count();
                        $sub_type_agree_count = $sub_type_attribute_ratings->where('rate_score', 4)->count();
                        $sub_type_neither_count = $sub_type_attribute_ratings->where('rate_score', 3)->count();
                        $sub_type_disagree_count = $sub_type_attribute_ratings->where('rate_score', 2)->count();
                        $sub_type_strongly_disagree_count = $sub_type_attribute_ratings->where('rate_score', 1)->count();
                        $sub_type_na_count = $sub_type_attribute_ratings->where('rate_score', 6)->count();
                        
                        // Total count for percentage calculation
                        $sub_type_total_ratings = $sub_type_strongly_agree_count + $sub_type_agree_count + $sub_type_neither_count + $sub_type_disagree_count + $sub_type_strongly_disagree_count + $sub_type_na_count;

                        // Calculate percentages
                        $sub_type_pct_strongly_agree = $sub_type_total_ratings > 0 ? ($sub_type_strongly_agree_count / $sub_type_total_ratings) * 100 : 0;
                        $sub_type_pct_agree = $sub_type_total_ratings > 0 ? ($sub_type_agree_count / $sub_type_total_ratings) * 100 : 0;
                        $sub_type_pct_neither = $sub_type_total_ratings > 0 ? ($sub_type_neither_count / $sub_type_total_ratings) * 100 : 0;
                        $sub_type_pct_disagree = $sub_type_total_ratings > 0 ? ($sub_type_disagree_count / $sub_type_total_ratings) * 100 : 0;
                        $sub_type_pct_strongly_disagree = $sub_type_total_ratings > 0 ? ($sub_type_strongly_disagree_count / $sub_type_total_ratings) * 100 : 0;
                        $sub_type_pct_na = $sub_type_total_ratings > 0 ? ($sub_type_na_count / $sub_type_total_ratings) * 100 : 0;

                        $sub_unit_types_data[$sub_unit_type->id] = [
                            'type_name' => $sub_unit_type->type_name,
                            'total_respo' => $sub_type_total_respo,
                            'total_vss_respo' => $sub_type_total_vss_respo,
                            'percentage_vss_respo' => number_format($sub_type_percentage_vss_respo, 2),
                            // Rating percentages
                            'pct_strongly_agree' => number_format($sub_type_pct_strongly_agree, 2),
                            'pct_agree' => number_format($sub_type_pct_agree, 2),
                            'pct_neither' => number_format($sub_type_pct_neither, 2),
                            'pct_disagree' => number_format($sub_type_pct_disagree, 2),
                            'pct_strongly_disagree' => number_format($sub_type_pct_strongly_disagree, 2),
                            'pct_na' => number_format($sub_type_pct_na, 2),
                        ];
                    }

                    $sub_units_data[$sub_unit->id] = [
                        'sub_unit_name' => $sub_unit->sub_unit_name,
                        'total_respo' => $sub_total_respo,
                        'total_vss_respo' => $sub_total_vss_respo,
                        'percentage_vss_respo' => number_format($sub_percentage_vss_respo, 2),
                        // Rating percentages
                        'pct_strongly_agree' => number_format($sub_pct_strongly_agree, 2),
                        'pct_agree' => number_format($sub_pct_agree, 2),
                        'pct_neither' => number_format($sub_pct_neither, 2),
                        'pct_disagree' => number_format($sub_pct_disagree, 2),
                        'pct_strongly_disagree' => number_format($sub_pct_strongly_disagree, 2),
                        'pct_na' => number_format($sub_pct_na, 2),
                        // Include sub-unit types data
                        'sub_unit_types_data' => $sub_unit_types_data,
                    ];

                    // Add PSTO-specific calculations for sub_units filtered by region_id = 10
                    $pstos_data = [];
                    if ($sub_unit->sub_unit_pstos) {
                        foreach ($sub_unit->sub_unit_pstos as $sub_psto) {
                            // Get PSTO-specific CSF forms filtered by region_id = 10
                            $psto_forms = CsfForm::where('region_id', 10)
                                ->where('service_id', $service->id)
                                ->where('unit_id', $unit->id)
                                ->where('sub_unit_id', $sub_unit->id)
                                ->where('psto_id', $sub_psto->id)
                                ->whereMonth('created_at', $numeric_month)
                                ->whereYear('created_at', $request->selected_year)
                                ->get();

                            $psto_customer_ids = $psto_forms->pluck('customer_id');
                            $psto_total_respo = $psto_customer_ids->count();

                            // Get attribute ratings for PSTO using customer_id
                            $psto_attribute_ratings = CustomerAttributeRating::whereIn('customer_id', $psto_customer_ids)
                                ->whereMonth('created_at', $numeric_month)
                                ->whereYear('created_at', $request->selected_year)
                                ->get();

                            // Calculate VSS (Very Satisfied + Satisfied) for PSTO using rate_score
                            $psto_vss_respondents = $psto_attribute_ratings->whereIn('rate_score', [4, 5])->groupBy('customer_id')->count();

                            $psto_percentage_vss_respo = $psto_total_respo > 0 ? ($psto_vss_respondents / $psto_total_respo) * 100 : 0;

                            // Calculate rating percentages for PSTO using rate_score
                            $psto_strongly_agree_count = $psto_attribute_ratings->where('rate_score', 5)->count();
                            $psto_agree_count = $psto_attribute_ratings->where('rate_score', 4)->count();
                            $psto_neither_count = $psto_attribute_ratings->where('rate_score', 3)->count();
                            $psto_disagree_count = $psto_attribute_ratings->where('rate_score', 2)->count();
                            $psto_strongly_disagree_count = $psto_attribute_ratings->where('rate_score', 1)->count();
                            $psto_na_count = $psto_attribute_ratings->where('rate_score', 6)->count();

                            $psto_total_ratings = $psto_strongly_agree_count + $psto_agree_count + $psto_neither_count + $psto_disagree_count + $psto_strongly_disagree_count + $psto_na_count;

                            $psto_pct_strongly_agree = $psto_total_ratings > 0 ? ($psto_strongly_agree_count / $psto_total_ratings) * 100 : 0;
                            $psto_pct_agree = $psto_total_ratings > 0 ? ($psto_agree_count / $psto_total_ratings) * 100 : 0;
                            $psto_pct_neither = $psto_total_ratings > 0 ? ($psto_neither_count / $psto_total_ratings) * 100 : 0;
                            $psto_pct_disagree = $psto_total_ratings > 0 ? ($psto_disagree_count / $psto_total_ratings) * 100 : 0;
                            $psto_pct_strongly_disagree = $psto_total_ratings > 0 ? ($psto_strongly_disagree_count / $psto_total_ratings) * 100 : 0;
                            $psto_pct_na = $psto_total_ratings > 0 ? ($psto_na_count / $psto_total_ratings) * 100 : 0;

                            $pstos_data[$sub_psto->id] = [
                                'psto_name' => $sub_psto->psto_name,
                                'total_respo' => $psto_total_respo,
                                'total_vss_respo' => $psto_vss_respondents,
                                'percentage_vss_respo' => number_format($psto_percentage_vss_respo, 2),
                                'pct_strongly_agree' => number_format($psto_pct_strongly_agree, 2),
                                'pct_agree' => number_format($psto_pct_agree, 2),
                                'pct_neither' => number_format($psto_pct_neither, 2),
                                'pct_disagree' => number_format($psto_pct_disagree, 2),
                                'pct_strongly_disagree' => number_format($psto_pct_strongly_disagree, 2),
                                'pct_na' => number_format($psto_pct_na, 2),
                            ];
                        }
                    }
                    $sub_units_data[$sub_unit->id]['pstos_data'] = $pstos_data;
                }

                $units_data[$service->id][$unit->id] = [
                    'unit_name' => $unit->unit_name,
                    'total_respo' => $total_respo,
                    'sub_units_data' => $sub_units_data,
                    // Add unit-level PSTO data (region_id = 10)
                    'unit_pstos_data' => [],
                    'total_vss_respo' => $total_vss_respo,
                    'percentage_vss_respo' => number_format($percentage_vss_respo, 2),
                    'csi' => number_format($unit_csi, 2),
                    'nps' => number_format($nps, 2),
                    'lsr' => number_format($lsr, 2),
                    // Rating percentages
                    'strongly_agree_count' => $strongly_agree_count,
                    'agree_count' => $agree_count,
                    'neither_count' => $neither_count,
                    'disagree_count' => $disagree_count,
                    'strongly_disagree_count' => $strongly_disagree_count,
                    'na_count' => $na_count,
                    'pct_strongly_agree' => number_format($pct_strongly_agree, 2),
                    'pct_agree' => number_format($pct_agree, 2),
                    'pct_neither' => number_format($pct_neither, 2),
                    'pct_disagree' => number_format($pct_disagree, 2),
                    'pct_strongly_disagree' => number_format($pct_strongly_disagree, 2),
                    'pct_na' => number_format($pct_na, 2),
                    'cc_data' => [
                        'cc1_ans1' => $cc1_ans1,
                        'cc1_ans2' => $cc1_ans2,
                        'cc1_ans3' => $cc1_ans3,
                        'cc1_ans4' => $cc1_ans4,
                        'cc2_ans1' => $cc2_ans1,
                        'cc2_ans2' => $cc2_ans2,
                        'cc2_ans3' => $cc2_ans3,
                        'cc2_ans4' => $cc2_ans4,
                        'cc2_ans5' => $cc2_ans5,
                        'cc3_ans1' => $cc3_ans1,
                        'cc3_ans2' => $cc3_ans2,
                        'cc3_ans3' => $cc3_ans3,
                        'cc3_ans4' => $cc3_ans4,
                    ],
                ];

                $grand_cc1_ans1 += $cc1_ans1;
                $grand_cc1_ans2 += $cc1_ans2;
                $grand_cc1_ans3 += $cc1_ans3;
                $grand_cc1_ans4 += $cc1_ans4;
                $grand_cc2_ans1 += $cc2_ans1;
                $grand_cc2_ans2 += $cc2_ans2;
                $grand_cc2_ans3 += $cc2_ans3;
                $grand_cc2_ans4 += $cc2_ans4;
                $grand_cc2_ans5 += $cc2_ans5;
                $grand_cc3_ans1 += $cc3_ans1;
                $grand_cc3_ans2 += $cc3_ans2;
                $grand_cc3_ans3 += $cc3_ans3;
                $grand_cc3_ans4 += $cc3_ans4;
                
                // Calculate unit-level PSTO data (region_id = 10)
                $unit_pstos_data = [];
                if ($unit->pstos && $unit->pstos->count() > 0) {
                    foreach ($unit->pstos as $unit_psto) {
                        // Get PSTO-specific CSF forms filtered by region_id = 10
                        $unit_psto_forms = CsfForm::where('region_id', 10)
                            ->where('service_id', $service->id)
                            ->where('unit_id', $unit->id)
                            ->where('psto_id', $unit_psto->id)
                            ->whereMonth('created_at', $numeric_month)
                            ->whereYear('created_at', $request->selected_year)
                            ->get();

                        $unit_psto_customer_ids = $unit_psto_forms->pluck('customer_id');
                        $unit_psto_total_respo = $unit_psto_customer_ids->count();

                        // Get attribute ratings for unit PSTO using customer_id
                        $unit_psto_attribute_ratings = CustomerAttributeRating::whereIn('customer_id', $unit_psto_customer_ids)
                            ->whereMonth('created_at', $numeric_month)
                            ->whereYear('created_at', $request->selected_year)
                            ->get();

                        // Calculate VSS (Very Satisfied + Satisfied) for unit PSTO using rate_score
                        $unit_psto_vss_respondents = $unit_psto_attribute_ratings->whereIn('rate_score', [4, 5])->groupBy('customer_id')->count();

                        $unit_psto_percentage_vss_respo = $unit_psto_total_respo > 0 ? ($unit_psto_vss_respondents / $unit_psto_total_respo) * 100 : 0;

                        // Calculate rating percentages for unit PSTO using rate_score
                        $unit_psto_strongly_agree_count = $unit_psto_attribute_ratings->where('rate_score', 5)->count();
                        $unit_psto_agree_count = $unit_psto_attribute_ratings->where('rate_score', 4)->count();
                        $unit_psto_neither_count = $unit_psto_attribute_ratings->where('rate_score', 3)->count();
                        $unit_psto_disagree_count = $unit_psto_attribute_ratings->where('rate_score', 2)->count();
                        $unit_psto_strongly_disagree_count = $unit_psto_attribute_ratings->where('rate_score', 1)->count();
                        $unit_psto_na_count = $unit_psto_attribute_ratings->where('rate_score', 6)->count();

                        $unit_psto_total_ratings = $unit_psto_strongly_agree_count + $unit_psto_agree_count + $unit_psto_neither_count + $unit_psto_disagree_count + $unit_psto_strongly_disagree_count + $unit_psto_na_count;

                        $unit_psto_pct_strongly_agree = $unit_psto_total_ratings > 0 ? ($unit_psto_strongly_agree_count / $unit_psto_total_ratings) * 100 : 0;
                        $unit_psto_pct_agree = $unit_psto_total_ratings > 0 ? ($unit_psto_agree_count / $unit_psto_total_ratings) * 100 : 0;
                        $unit_psto_pct_neither = $unit_psto_total_ratings > 0 ? ($unit_psto_neither_count / $unit_psto_total_ratings) * 100 : 0;
                        $unit_psto_pct_disagree = $unit_psto_total_ratings > 0 ? ($unit_psto_disagree_count / $unit_psto_total_ratings) * 100 : 0;
                        $unit_psto_pct_strongly_disagree = $unit_psto_total_ratings > 0 ? ($unit_psto_strongly_disagree_count / $unit_psto_total_ratings) * 100 : 0;
                        $unit_psto_pct_na = $unit_psto_total_ratings > 0 ? ($unit_psto_na_count / $unit_psto_total_ratings) * 100 : 0;

                        $unit_pstos_data[$unit_psto->id] = [
                            'psto_name' => $unit_psto->psto_name,
                            'total_respo' => $unit_psto_total_respo,
                            'total_vss_respo' => $unit_psto_vss_respondents,
                            'percentage_vss_respo' => number_format($unit_psto_percentage_vss_respo, 2),
                            'pct_strongly_agree' => number_format($unit_psto_pct_strongly_agree, 2),
                            'pct_agree' => number_format($unit_psto_pct_agree, 2),
                            'pct_neither' => number_format($unit_psto_pct_neither, 2),
                            'pct_disagree' => number_format($unit_psto_pct_disagree, 2),
                            'pct_strongly_disagree' => number_format($unit_psto_pct_strongly_disagree, 2),
                            'pct_na' => number_format($unit_psto_pct_na, 2),
                        ];
                    }
                }
                
                // Update units_data with unit PSTO data
                $units_data[$service->id][$unit->id]['unit_pstos_data'] = $unit_pstos_data;

                $grand_total_respondents += $total_respo;
                $grand_total_vss_respondents += $total_vss_respo;
                $grand_total_promoters += $promoters;
                $grand_total_detractors += $detractors;
                
                // Accumulate grand totals for rating percentages
                $grand_strongly_agree_count += $strongly_agree_count;
                $grand_agree_count += $agree_count;
                $grand_neither_count += $neither_count;
                $grand_disagree_count += $disagree_count;
                $grand_strongly_disagree_count += $strongly_disagree_count;
                $grand_na_count += $na_count;
            }
        }

        // Grand percentage for VSS
        $grand_percentage_vss_respondents = 0;
        if ($grand_total_respondents > 0 && $grand_total_vss_respondents > 0) {
            $grand_percentage_vss_respondents = ($grand_total_vss_respondents / $grand_total_respondents) * 100;
        }

        // Grand NPS
        $grand_nps = 0;
        if ($grand_total_respondents > 0) {
            $grand_percentage_promoters = ($grand_total_promoters / $grand_total_respondents) * 100;
            $grand_percentage_detractors = ($grand_total_detractors / $grand_total_respondents) * 100;
            $grand_nps = $grand_percentage_promoters - $grand_percentage_detractors;
        }

        // Grand CC totals
        $grand_cc1_total = $grand_cc1_ans1 + $grand_cc1_ans2 + $grand_cc1_ans3 + $grand_cc1_ans4;
        $grand_cc2_total = $grand_cc2_ans1 + $grand_cc2_ans2 + $grand_cc2_ans3 + $grand_cc2_ans4 + $grand_cc2_ans5;
        $grand_cc3_total = $grand_cc3_ans1 + $grand_cc3_ans2 + $grand_cc3_ans3 + $grand_cc3_ans4;

        // Calculate grand total ratings for percentage calculation
        $grand_total_ratings = $grand_strongly_agree_count + $grand_agree_count + $grand_neither_count + $grand_disagree_count + $grand_strongly_disagree_count + $grand_na_count;
        
        // Calculate grand percentages for rating categories (based on total ratings so they add up to 100%)
        $grand_pct_strongly_agree = $grand_total_ratings > 0 ? ($grand_strongly_agree_count / $grand_total_ratings) * 100 : 0;
        $grand_pct_agree = $grand_total_ratings > 0 ? ($grand_agree_count / $grand_total_ratings) * 100 : 0;
        $grand_pct_neither = $grand_total_ratings > 0 ? ($grand_neither_count / $grand_total_ratings) * 100 : 0;
        $grand_pct_disagree = $grand_total_ratings > 0 ? ($grand_disagree_count / $grand_total_ratings) * 100 : 0;
        $grand_pct_strongly_disagree = $grand_total_ratings > 0 ? ($grand_strongly_disagree_count / $grand_total_ratings) * 100 : 0;
        $grand_pct_na = $grand_total_ratings > 0 ? ($grand_na_count / $grand_total_ratings) * 100 : 0;

        return [
            'units_data' => $units_data,
            'grand_total_respondents' => $grand_total_respondents,
            'grand_total_vss_respondents' => $grand_total_vss_respondents,
            'grand_percentage_vss_respondents' => number_format($grand_percentage_vss_respondents, 2),
            'grand_nps' => number_format($grand_nps, 2),
            // Grand rating percentages for summary table
            'grand_pct_strongly_agree' => number_format($grand_pct_strongly_agree, 2),
            'grand_pct_agree' => number_format($grand_pct_agree, 2),
            'grand_pct_neither' => number_format($grand_pct_neither, 2),
            'grand_pct_disagree' => number_format($grand_pct_disagree, 2),
            'grand_pct_strongly_disagree' => number_format($grand_pct_strongly_disagree, 2),
            'grand_pct_na' => number_format($grand_pct_na, 2),
            'grand_cc_data' => [
                'cc1_ans1' => $grand_cc1_ans1,
                'cc1_ans2' => $grand_cc1_ans2,
                'cc1_ans3' => $grand_cc1_ans3,
                'cc1_ans4' => $grand_cc1_ans4,
                'cc1_total' => $grand_cc1_total,
                'cc2_ans1' => $grand_cc2_ans1,
                'cc2_ans2' => $grand_cc2_ans2,
                'cc2_ans3' => $grand_cc2_ans3,
                'cc2_ans4' => $grand_cc2_ans4,
                'cc2_ans5' => $grand_cc2_ans5,
                'cc2_total' => $grand_cc2_total,
                'cc3_ans1' => $grand_cc3_ans1,
                'cc3_ans2' => $grand_cc3_ans2,
                'cc3_ans3' => $grand_cc3_ans3,
                'cc3_ans4' => $grand_cc3_ans4,
                'cc3_total' => $grand_cc3_total,
            ],
        ];
    }

    /**
     * Calculate CSI for a single unit using Weighted Sum method
     */
    private function calculateUnitCSI($attribute_ratings, $total_respondents, $dimension_count)
    {
        if ($total_respondents == 0 || $attribute_ratings->count() == 0) {
            return 0;
        }

        $ilsr_grand_total = 0;
        $ws_grand_total = 0;

        // Calculate importance and service quality ratings for each dimension
        for ($dimensionId = 1; $dimensionId <= $dimension_count; $dimensionId++) {
            // Importance ratings
            $vi_total = $attribute_ratings->where('importance_rate_score', 5)->where('dimension_id', $dimensionId)->count();
            $i_total = $attribute_ratings->where('importance_rate_score', 4)->where('dimension_id', $dimensionId)->count();
            $mi_total = $attribute_ratings->where('importance_rate_score', 3)->where('dimension_id', $dimensionId)->count();
            $li_total = $attribute_ratings->where('importance_rate_score', 2)->where('dimension_id', $dimensionId)->count();
            $nai_total = $attribute_ratings->where('importance_rate_score', 1)->where('dimension_id', $dimensionId)->count();

            $x_vi_total = $vi_total * 5; 
            $x_i_total = $i_total * 4; 
            $x_mi_total = $mi_total * 3; 
            $x_li_total = $li_total * 2; 
            $x_nai_total = $nai_total * 1;
            $x_importance_total = $x_vi_total + $x_i_total + $x_mi_total + $x_li_total + $x_nai_total;

            // Importance Likert Scale Rating 
            $ilsr = 0;
            if ($x_importance_total != 0) {
                $ilsr = $x_importance_total / $total_respondents;
                $ilsr_grand_total += $ilsr;
            }

            // Service quality ratings
            $vs_total = $attribute_ratings->where('rate_score', 5)->where('dimension_id', $dimensionId)->count();
            $s_total = $attribute_ratings->where('rate_score', 4)->where('dimension_id', $dimensionId)->count();
            $n_total = $attribute_ratings->where('rate_score', 3)->where('dimension_id', $dimensionId)->count();
            $d_total = $attribute_ratings->where('rate_score', 2)->where('dimension_id', $dimensionId)->count();
            $vd_total = $attribute_ratings->where('rate_score', 1)->where('dimension_id', $dimensionId)->count();

            $x_vs_total = $vs_total * 5; 
            $x_s_total = $s_total * 4; 
            $x_n_total = $n_total * 3; 
            $x_d_total = $d_total * 2; 
            $x_vd_total = $vd_total * 1; 
            $x_respondents_total = $vs_total + $s_total + $n_total + $d_total + $vd_total;
            $x_grand_total = $x_vs_total + $x_s_total + $x_n_total + $x_d_total + $x_vd_total;

            // Likert Scale Rating (Service Satisfaction)
            $lsr = 0;
            if ($x_grand_total != 0) {
                if ($dimensionId == 6) {
                    $lsr = $x_grand_total / $x_respondents_total;
                } else {
                    $lsr = $x_grand_total / $total_respondents;
                }
            }

            // Weighted Factor
            $wf = 0;
            if ($ilsr_grand_total > 0) {
                $wf = ($ilsr / $ilsr_grand_total) * 100;
            }

            // Weighted Score
            $ws = ($lsr * $wf) / 100;
            $ws_grand_total += $ws;
        }

        // CSI = (WS grand total / 5) * 100
        $csi = 0;
        if ($ws_grand_total > 0) {
            $csi = ($ws_grand_total / 5) * 100;
        }

        if ($csi > 100) {
            $csi = 100;
        }

        return $csi;
    }

    /**
     * Get NPS for all units
     */
    private function getAllUnitNPS($request, $region_id, $numeric_month)
    {
        // Get all customer IDs for the region filtered by month and year
        $customer_ids = CsfForm::where('region_id', $region_id)
            ->whereMonth('created_at', $numeric_month)
            ->whereYear('created_at', $request->selected_year)
            ->pluck('customer_id');

        // Get recommendation ratings for the month filtered by month and year
        $recommendation_ratings = CustomerRecommendationRating::whereIn('customer_id', $customer_ids)
            ->whereMonth('created_at', $numeric_month)
            ->whereYear('created_at', $request->selected_year)
            ->get();

        $total_respondents = $recommendation_ratings->groupBy('customer_id')->count();
        
        // Promoters: 7-10, Detractors: 0-6
        $promoters = $recommendation_ratings->whereBetween('recommend_rate_score', [7, 10])->groupBy('customer_id')->count();
        $detractors = $recommendation_ratings->whereBetween('recommend_rate_score', [0, 6])->groupBy('customer_id')->count();

        $percentage_promoters = 0;
        $percentage_detractors = 0;
        $nps = 0;

        if ($total_respondents > 0) {
            $percentage_promoters = ($promoters / $total_respondents) * 100;
            $percentage_detractors = ($detractors / $total_respondents) * 100;
            $nps = $percentage_promoters - $percentage_detractors;
        }

        return [
            'nps' => number_format($nps, 2),
            'percentage_promoters' => number_format($percentage_promoters, 2),
            'percentage_detractors' => number_format($percentage_detractors, 2),
            'total_respondents' => $total_respondents,
            'promoters' => $promoters,
            'detractors' => $detractors,
        ];
    }

    /**
     * Get Likert Scale Rating for all units
     */
    private function getAllUnitLSR($request, $region_id, $numeric_month)
    {
        // Get all customer IDs for the region
        $customer_ids = CsfForm::where('region_id', $region_id)
            ->pluck('customer_id');

        // Get attribute ratings for the month
        $attribute_ratings = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
            ->whereMonth('created_at', $numeric_month)
            ->whereYear('created_at', $request->selected_year)
            ->get();

        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();

        $grand_total_score = 0;
        $grand_total_responses = 0;

        foreach ($dimensions as $dimension) {
            $dimension_ratings = $attribute_ratings->where('dimension_id', $dimension->id);
            
            foreach ($dimension_ratings as $rating) {
                $grand_total_score += $rating->rate_score;
                $grand_total_responses++;
            }
        }

        $lsr = 0;
        if ($grand_total_responses > 0) {
            $lsr = $grand_total_score / $grand_total_responses;
        }

        return number_format($lsr, 2);
    }

    public function getAllUnitMonthlyCSI($request, $region_id, $numeric_month)
    {        
        // Get all customer IDs for the region filtered by month and year
        $customer_ids = CsfForm::where('region_id', $region_id)
            ->whereMonth('created_at', $numeric_month)
            ->whereYear('created_at', $request->selected_year)
            ->pluck('customer_id');

        // Dimensions or attributes
        $dimensions = Dimension::all();
        $dimension_count = $dimensions->count();

        $date_range = CustomerAttributeRating::whereIn('customer_id', $customer_ids)
                                             ->whereMonth('created_at', $numeric_month)
                                             ->whereYear('created_at', $request->selected_year)
                                             ->get();

        // total number of respondents/customer (unique customers)
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

            // sum of all repondent with rate_score 1-5
            $x_respondents_total =  $vs_total +   $x_s_total + $n_total +  $d_total +  $vd_total;
            $x_grand_total = $x_vs_total + $x_s_total + $x_n_total + $x_d_total + $x_vd_total  ; 
         
            // right side total score divided by total repondents or customers
            if($x_grand_total != 0){
                if($dimensionId == 6){
                    $lsr_total = $x_grand_total / $x_respondents_total;
                }
                else{
                    $lsr_total = $x_grand_total / $total_respondents;
                }
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
        
        if($customer_satisfaction_index > 100){
            $customer_satisfaction_index = number_format(100 , 2);
        }

        return $customer_satisfaction_index;
    }  

    public function calculateCC($cc_query)
    {  
           // Clone the original query builder instance
        $cc_query_clone = clone $cc_query;

        // CC 1 
        $cc_query = clone $cc_query_clone;
        $cc1_ans4 = $cc_query->where('cc_id', 1)->where('answer', 4)->count();
        $cc_query = clone $cc_query_clone;
        $cc1_ans3 = $cc_query->where('cc_id', 1)->where('answer', 3)->count();
        $cc_query = clone $cc_query_clone;
        $cc1_ans2 = $cc_query->where('cc_id', 1)->where('answer', 2)->count();
        $cc_query = clone $cc_query_clone;
        $cc1_ans1 = $cc_query->where('cc_id', 1)->where('answer', 1)->count();

        // CC 2 
        $cc_query = clone $cc_query_clone;
        $cc2_ans5 = $cc_query->where('cc_id', 2)->where('answer', 5)->count();
        $cc_query = clone $cc_query_clone;
        $cc2_ans4 = $cc_query->where('cc_id', 2)->where('answer', 4)->count();
        $cc_query = clone $cc_query_clone;
        $cc2_ans3 = $cc_query->where('cc_id', 2)->where('answer', 3)->count();
        $cc_query = clone $cc_query_clone;
        $cc2_ans2 = $cc_query->where('cc_id', 2)->where('answer', 2)->count();
        $cc_query = clone $cc_query_clone;
        $cc2_ans1 = $cc_query->where('cc_id', 2)->where('answer', 1)->count();

        // CC 3
        $cc_query = clone $cc_query_clone;
        $cc3_ans4 = $cc_query->where('cc_id', 3)->where('answer', 4)->count();
        $cc_query = clone $cc_query_clone;
        $cc3_ans3 = $cc_query->where('cc_id', 3)->where('answer', 3)->count();
        $cc_query = clone $cc_query_clone;
        $cc3_ans2 = $cc_query->where('cc_id', 3)->where('answer', 2)->count();
        $cc_query = clone $cc_query_clone;
        $cc3_ans1 = $cc_query->where('cc_id', 3)->where('answer', 1)->count();

        // Calculate total counts for percentage calculation
        $cc1_total = $cc1_ans1 + $cc1_ans2 + $cc1_ans3 + $cc1_ans4;
        $cc2_total = $cc2_ans1 + $cc2_ans2 + $cc2_ans3 + $cc2_ans4 + $cc2_ans5;
        $cc3_total = $cc3_ans1 + $cc3_ans2 + $cc3_ans3 + $cc3_ans4;

        // Calculate percentages
        $cc1_ans1_pct = $cc1_total > 0 ? number_format(($cc1_ans1 / $cc1_total) * 100, 2) : 0;
        $cc1_ans2_pct = $cc1_total > 0 ? number_format(($cc1_ans2 / $cc1_total) * 100, 2) : 0;
        $cc1_ans3_pct = $cc1_total > 0 ? number_format(($cc1_ans3 / $cc1_total) * 100, 2) : 0;
        $cc1_ans4_pct = $cc1_total > 0 ? number_format(($cc1_ans4 / $cc1_total) * 100, 2) : 0;

        $cc2_ans1_pct = $cc2_total > 0 ? number_format(($cc2_ans1 / $cc2_total) * 100, 2) : 0;
        $cc2_ans2_pct = $cc2_total > 0 ? number_format(($cc2_ans2 / $cc2_total) * 100, 2) : 0;
        $cc2_ans3_pct = $cc2_total > 0 ? number_format(($cc2_ans3 / $cc2_total) * 100, 2) : 0;
        $cc2_ans4_pct = $cc2_total > 0 ? number_format(($cc2_ans4 / $cc2_total) * 100, 2) : 0;
        $cc2_ans5_pct = $cc2_total > 0 ? number_format(($cc2_ans5 / $cc2_total) * 100, 2) : 0; // N/A

        $cc3_ans1_pct = $cc3_total > 0 ? number_format(($cc3_ans1 / $cc3_total) * 100, 2) : 0;
        $cc3_ans2_pct = $cc3_total > 0 ? number_format(($cc3_ans2 / $cc3_total) * 100, 2) : 0;
        $cc3_ans3_pct = $cc3_total > 0 ? number_format(($cc3_ans3 / $cc3_total) * 100, 2) : 0;
        $cc3_ans4_pct = $cc3_total > 0 ? number_format(($cc3_ans4 / $cc3_total) * 100, 2) : 0;

        // cc 1-3 data with counts and percentages
        $cc1_data = [
            'cc1_ans4' => $cc1_ans4,
            'cc1_ans3' => $cc1_ans3,
            'cc1_ans2' => $cc1_ans2,
            'cc1_ans1' => $cc1_ans1,
            'cc1_ans4_pct' => $cc1_ans4_pct,
            'cc1_ans3_pct' => $cc1_ans3_pct,
            'cc1_ans2_pct' => $cc1_ans2_pct,
            'cc1_ans1_pct' => $cc1_ans1_pct,
            'cc1_total' => $cc1_total,
        ];

        $cc2_data = [
            'cc2_ans5' => $cc2_ans5,
            'cc2_ans4' => $cc2_ans4,
            'cc2_ans3' => $cc2_ans3,
            'cc2_ans2' => $cc2_ans2,
            'cc2_ans1' => $cc2_ans1,
            'cc2_ans5_pct' => $cc2_ans5_pct,
            'cc2_ans4_pct' => $cc2_ans4_pct,
            'cc2_ans3_pct' => $cc2_ans3_pct,
            'cc2_ans2_pct' => $cc2_ans2_pct,
            'cc2_ans1_pct' => $cc2_ans1_pct,
            'cc2_total' => $cc2_total,
        ];

        $cc3_data = [
            'cc3_ans4' => $cc3_ans4,
            'cc3_ans3' => $cc3_ans3,
            'cc3_ans2' => $cc3_ans2,
            'cc3_ans1' => $cc3_ans1,
            'cc3_ans4_pct' => $cc3_ans4_pct,
            'cc3_ans3_pct' => $cc3_ans3_pct,
            'cc3_ans2_pct' => $cc3_ans2_pct,
            'cc3_ans1_pct' => $cc3_ans1_pct,
            'cc3_total' => $cc3_total,
        ];

        //cc data all in one

        $cc_data =[
            'cc1_data' => $cc1_data,
            'cc2_data' => $cc2_data,
            'cc3_data' => $cc3_data,
        ];

        return $cc_data;
    }

}
