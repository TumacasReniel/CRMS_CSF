<script setup>
    const props = defineProps({
        data: {
            type: Object,
        },
        form: {
            type: Object,
        },
        is_printing: {
            type:Boolean,
        },
    });
        const calculate = (ndvd_grand_total_score ,grand_total_score) => {
            const result = (ndvd_grand_total_score / grand_total_score) * 100;
            return result.toFixed(2);
        };
</script>
<template>
    <div class="mb-3 print-id print" v-if="is_printing">
        <h5 style="text-transform:capitalize; text-align:center; margin-top: -10px">
            CUSTOMER SATISFACTION FEEDBACK <br>SUMMARY REPORT FOR 
            <u><span>{{ form.selected_quarter }}</span>  {{ form.selected_year }}</u>
        </h5><br>
        <div style="display: flex; justify-content: space-between; margin-top:-20px">
            <div style="font-size: 12px;">
                Services : <u>{{ data.service.services_name }}</u> 
                
            </div>
            <div style="font-size: 12px">  
                Services Unit : <u v-if="data.unit.data.length > 0">{{ data.unit.data[0].unit_name }}</u><br>
                                <u v-if="data.sub_unit.length > 0" style="margin-left: 75px">{{ data.sub_unit[0].sub_unit_name }}</u>
                                    <u v-if="form.sub_unit_type" style="margin-left: 5px">{{ form.sub_unit_type.type_name }}</u>
                                <u v-if="form.client_type" style="margin-left: 75px">{{ form.client_type }}</u>
                                <u v-if="form.selected_sub_unit_psto" style="margin-left: 75px">{{ form.selected_sub_unit_psto.id }}</u>
            </div>
        </div>
         <div style="font-size: 12px;margin-right:20px; margin-bottom:5px;margin-top:10px; font-weight: bold">PART I: CUSTOMER RATING OF SERVICE QUALITY  </div>
                <table style="font-size: 13px;width:100%; border: 1px solid #333; border-collapse: collapse;  padding: 3px">
                    <tr class="text-left font-bold text-center bg-blue-200">
                        <th colspan="3">Service Quality Attributes</th>
                        <th>JUL</th>
                        <th>AUG</th>
                        <th>SEP</th>
                        <th>Total Raw Points</th>
                        <th>Total Score</th>
                        <th >Likert Scale Rating</th>
                    </tr>

                    <template v-for="(dimension, index) in data.dimensions" :key="dimension.id" class="border border-solid hover:bg-gray-100 focus-within:bg-gray-100">                     
                            <tr>
                               <td class="text-center" rowspan="7">
                                 [{{ index + 1 }}] {{ dimension.name }}    
                                </td>             
                            </tr> 
                            <tr>
                                <td class="text-center">5</td>
                                <td>Very Satisfied</td>
                                <td v-if="data.q3_vs_totals" class="text-center"  v-for="total in data.q3_vs_totals[index+1]">
                                    {{ total }}
                                </td>
                                <td v-if="data.trp_totals" class="text-center" >
                                    {{ data.trp_totals[index+1].vs_total_raw_points }}
                                </td>
                                <td v-if="data.p1_total_scores" class="text-center" >
                                    {{ data.p1_total_scores[index+1].x_vs_total }}
                                </td>
                                <td v-if="data.lsr_totals" class="text-center" >
                                    {{ data.lsr_totals[index+1].vs_lsr_total }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">4</td>
                                <td>Satisfied</td>
                                <td v-if="data.q3_s_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in data.q3_s_totals[index+1]">
                                    {{ total }}
                                </td>
                                  <td v-if="data.trp_totals"  class="text-center" >
                                    {{ data.trp_totals[index+1].s_total_raw_points }}
                                </td>
                                <td v-if="data.p1_total_scores" class="text-center" >
                                    {{ data.p1_total_scores[index+1].x_s_total }}
                                </td>
                                <td v-if="data.lsr_totals" class="text-center" >
                                    {{ data.lsr_totals[index+1].s_lsr_total }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">3</td>
                                <td>Neither</td>
                                <td v-if="data.q3_n_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in data.q3_n_totals[index+1]">
                                    {{ total }}
                                </td>
                                <td v-if="data.trp_totals" class="text-center" >
                                    {{ data.trp_totals[index+1].n_total_raw_points }}
                                </td>
                                <td v-if="data.p1_total_scores" class="text-center" >
                                    {{ data.p1_total_scores[index+1].x_n_total }}
                                </td>
                                <td v-if="data.lsr_totals" class="text-center" >
                                    {{ data.lsr_totals[index+1].n_lsr_total }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                 <td>Dissatisfied</td>
                                <td v-if="data.q3_d_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in data.q3_d_totals[index+1]">
                                    {{ total }}
                                </td>
                                <td v-if="data.trp_totals" class="text-center" >
                                    {{ data.trp_totals[index+1].d_total_raw_points }}
                                </td>
                                <td v-if="data.p1_total_scores" class="text-center" >
                                    {{ data.p1_total_scores[index+1].x_d_total }}
                                </td>
                                   <td v-if="data.lsr_totals" class="text-center" >
                                    {{ data.lsr_totals[index+1].d_lsr_total }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">1</td>
                                 <td>Very Dissatisfied</td>
                                <td v-if="data.q3_vd_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in data.q3_vd_totals[index+1]">
                                    {{ total }}
                                </td>
                                <td v-if="data.trp_totals" class="text-center" >
                                    {{ data.trp_totals[index+1].vd_total_raw_points }}
                                </td>
                                <td v-if="data.p1_total_scores" class="text-center" >
                                    {{ data.p1_total_scores[index+1].x_vd_total }}
                                </td>
                                <td v-if="data.lsr_totals" class="text-center" >
                                    {{ data.lsr_totals[index+1].vd_lsr_total }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="2"></td>
                                <td v-if="data.q3_grand_totals" class="text-center bg-gray-300"  v-for="total in data.q3_grand_totals[index+1]">
                                    {{ total }}
                                </td>        
                                <td v-if="data.trp_totals" class="text-center bg-gray-200" >
                                    {{ data.trp_totals[index+1].total_raw_points }}
                                </td>  
                                <td v-if="data.p1_total_scores" class="text-center bg-gray-200" >
                                    {{ data.p1_total_scores[index+1].x_total_score }} 
                                </td>   
                                <td v-if="data.lsr_totals" class="text-center bg-gray-200" >
                                    {{ data.lsr_totals[index+1].lsr_total }}
                                </td>             
                            </tr>

                    </template>   

                    <!-- totals   -->
                    <tr>
                        <td></td>
                    </tr>
           <tr>
                        <td colspan="3" class="text-right">Total No. of Very Satisfied (VS) Responses:</td>
                        <td class="text-center">{{ data.apr_total_vs_respondents }}</td>
                        <td class="text-center"> {{ data.may_total_vs_respondents }} </td>
                        <td class="text-center">{{ data.jun_total_vs_respondents }} </td>
                        <td class="text-center">{{ data.vs_grand_total_raw_points }} </td>
                        <td class="text-center">{{ data.vs_grand_total_score }}</td>
                        <td class="text-center">{{ calculate(data.vs_grand_total_score, data.grand_total_score) }}</td>

                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Total No. of Satisfied (S) Responses:</td>
                        <td class="text-center">{{ data.apr_total_s_respondents }}</td>
                        <td class="text-center"> {{ data.may_total_s_respondents }} </td>
                        <td class="text-center">{{ data.jun_total_s_respondents }} </td>
                        <td class="text-center">{{ data.s_grand_total_raw_points }}</td>
                        <td class="text-center">{{ data.s_grand_total_score }}</td>
                        <td class="text-center">{{ calculate(data.s_grand_total_score, data.grand_total_score) }}</td>

 
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Total No. of Other (N, D, VD) Responses:</td>
                        <td class="text-center">{{ data.apr_total_ndvd_respondents }}</td>
                        <td class="text-center"> {{ data.may_total_ndvd_respondents }} </td>
                        <td class="text-center">{{ data.jun_total_ndvd_respondents }} </td>
                        <td class="text-center">{{ data.ndvd_grand_total_raw_points }}</td>
                        <td class="text-center">{{ data.ndvd_grand_total_score }}</td>
                        <td class="text-center">{{ calculate(data.ndvd_grand_total_score, data.grand_total_score) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Total No. of All Responses::</td>
                        <td class="text-center">{{ data.apr_total_respondents }}</td>
                        <td class="text-center"> {{ data.may_total_respondents }} </td>
                        <td class="text-center">{{ data.jun_total_respondents }} </td>
                        <td class="text-center">{{ data.grand_total_raw_points }} </td>
                        <td class="text-center">{{ data.grand_total_score }}</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td colspan="8" class="text-right">Total No. of Respondents/Customers:</td>
                        <td class="text-center">{{ data.total_respondents }}</td>
                    </tr>

                    <tr>
                        <td colspan="8" class="text-right">Total No. of Respondents/Customers who rated VS or S:</td>
                        <td class="text-center">{{ data.total_vss_respondents }}</td>
                    </tr>
                      <tr>
                        <td colspan="8" class="text-right">Percentage No. of Respondents/Customers who rated VS or S:</td>
                        <td class="text-center">{{ data.percentage_vss_respondents }}</td>
                    </tr>
                    <tr>
                        <td colspan="8" class="text-right"> Likert Scale Rating (Average):</td>      
                        <td class="text-center">{{ data.lsr_average }}</td>
                    </tr>              
                </table> 
            
           <div style="margin-top: 20px; page-break-before:always">
                <div style="font-size: 13px;margin-right:20px; margin-bottom:5px; font-weight: bold">PART II: IMPORTANCE OF THIS ATTRIBUTE    </div>
                 <table style="font-size: 13px;width:100%; border: 1px solid #333; border-collapse: collapse;  padding: 3px">
                    <tr class="text-left font-bold text-center bg-blue-200">
                        <th  colspan="3">Importance Service Quality Attributes</th>
                         <th >JUL</th>
                        <th >AUG</th>
                        <th >SEP</th>
                        <th >Total Raw Points</th>
                        <th  colspan="2">Total Score</th>
                    </tr>

                    <template v-for="(dimension, index) in data.dimensions" :key="dimension.id" class="border border-solid hover:bg-gray-100 focus-within:bg-gray-100">                     
                            <tr>
                               <td class="text-center" rowspan="6">
                                 [{{ index + 1 }}] {{ dimension.name }}    
                                </td>             
                            </tr> 
                            <tr>
                                <td class="text-center">5</td>
                                <td>Very Important</td>
                                <td v-if="data.q3_vi_totals" class="text-center"  v-for="total in data.q3_vi_totals[index+1]">
                                    {{ total }}
                                </td>
                                <td v-if="data.i_trp_totals" class="text-center" >
                                    {{ data.i_trp_totals[index+1].vi_total_raw_points }}
                                </td>
                                <td v-if="data.i_total_scores" class="text-center" >
                                    {{ data.i_total_scores[index+1].x_vi_total }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">4</td>
                                 <td>Important</td>
                                <td v-if="data.q3_i_totals" class="text-center"  v-for="total in data.q3_i_totals[index+1]">
                                    {{ total }}
                                </td>
                                <td v-if="data.i_trp_totals" class="text-center" >
                                    {{ data.i_trp_totals[index+1].i_total_raw_points }}
                                </td>
                                <td v-if="data.i_total_scores" class="text-center" >
                                    {{ data.i_total_scores[index+1].x_i_total }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">3</td>
                                <td>Moderately Important</td>
                                <td v-if="data.q3_mi_totals" class="text-center"  v-for="total in data.q3_mi_totals[index+1]">
                                    {{ total }}
                                </td>
                                <td v-if="data.i_trp_totals" class="text-center" >
                                    {{ data.i_trp_totals[index+1].mi_total_raw_points }}
                                </td>
                                <td v-if="data.i_total_scores" class="text-center" >
                                    {{ data.i_total_scores[index+1].x_mi_total }}
                                </td>
                                
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                 <td>Slightly Important</td>
                                <td v-if="data.q3_si_totals" class="text-center"  v-for="total in data.q3_si_totals[index+1]">
                                    {{ total }}
                                </td>
                                <td v-if="data.i_trp_totals" class="text-center" >
                                    {{ data.i_trp_totals[index+1].si_total_raw_points }}
                                </td>
                                <td v-if="data.i_total_scores" class="text-center" >
                                    {{ data.i_total_scores[index+1].x_si_total }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">1</td>
                                 <td>Not all Important</td>
                                <td v-if="data.q3_nai_totals" class="text-center"  v-for="total in data.q3_nai_totals[index+1]">
                                    {{ total }}
                                </td>
                                <td v-if="data.i_trp_totals" class="text-center" >
                                    {{ data.i_trp_totals[index+1].nai_total_raw_points }}
                                </td>
                                <td v-if="data.i_total_scores" class="text-center" >
                                    {{ data.i_total_scores[index+1].x_nai_total }}
                                </td>
                            </tr>
                            <tr class="text-center">
                                <td colspan="3"></td>
                                 <td v-if="data.q3_grand_totals" class="text-center bg-gray-300"  v-for="total in data.q3_grand_totals[index+1]">
                                    {{ total }}
                                </td>        
                                <td v-if="data.i_trp_totals" class="text-center bg-gray-200" >
                                    {{ data.i_trp_totals[index+1].i_total_raw_points }}
                                </td>  
                                <td v-if="data.i_total_scores" class="text-center bg-gray-200" >
                                    {{ data.i_total_scores[index+1].x_i_total_score }} 
                                </td>             
                            </tr>
                    </template>                 

                    <!-- totals -->
                    <tr>
                        <td></td>
                    </tr>

                     <tr class="text-center bg-blue-200">
                        <td colspan="3"></td>
                        <td>JUL</td>
                        <td>AUG</td>
                        <td>SEP</td>
                        <td colspan="2">AVERAGE(%)</td>

                    </tr>

                    <tr>
                        <td colspan="3" class="text-right">% of Promoters:</td>
                         <td v-if="data.julpercentage_promoters" class="text-center" >
                            {{ data.julpercentage_promoters }} 
                        </td>  
                        <td v-if="data.aug_percentage_promoters" class="text-center " >
                            {{ data.aug_percentage_promoters }} 
                        </td>
                        <td v-if="data.sep_percentage_promoters" class="text-center" >
                            {{ data.sep_percentage_promoters }} 
                        </td>
                        <td v-if="data.average_percentage_promoters" colspan="2"  class="text-center">
                             {{ data.average_percentage_promoters }} 
                        </td>

                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">% of Detractors:</td>
                        <td v-if="data.jul_percentage_detractors" class="text-center" >
                            {{ data.jul_percentage_detractors }} 
                        </td>  
                        <td v-if="data.aug_percentage_detractors" class="text-center " >
                            {{ data.aug_percentage_detractors  }} 
                        </td>
                        <td v-if="data.sep_percentage_detractors" class="text-center" >
                            {{ data.sep_percentage_detractors  }} 
                        </td>
                        <td v-if="data.average_percentage_detractors" colspan="2"  class="text-center">
                             {{ data.average_percentage_detractors  }} 
                        </td>

                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Net Promoter Score:</td>
                        <td v-if="data.jul_net_promoter_score" class="text-center" >
                            {{ data.jul_net_promoter_score }} 
                        </td>  
                        <td v-if="data.aug_percentage_detractors" class="text-center " >
                            {{ data.aug_percentage_detractors  }} 
                        </td>
                        <td v-if="data.sep_net_promoter_score" class="text-center" >
                            {{ data.sep_net_promoter_score  }} 
                        </td>
                        <td v-if="data.ave_net_promoter_score" colspan="2"  class="text-center">
                             {{ data.ave_net_promoter_score  }} 
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Customer Satisfaction Index (CSI):</td>
                        <td class="text-center" v-if="data.first_month_csi">{{ data.first_month_csi }}</td>
                        <td class="text-center" v-if="data.second_month_csi">{{ data.second_month_csi }}</td>
                        <td class="text-center" v-if="data.third_month_csi">{{ data.third_month_csi }}</td>
                        <td class="text-center" colspan="2" v-if="data.csi">{{ data.csi }} </td>
                    </tr> 
                    <tr>
                        <td colspan="8"></td>
                        
                    </tr>
                   <tr>
                        <td colspan="3" class="text-right">Customer Satisfaction Rating  :</td>
                        <td colspan="5" v-if="data.customer_satisfaction_rating">
                            {{ data.customer_satisfaction_rating }}
                        </td>
                    </tr>                                   
                </table>   
            </div> 
        

            <div style="margin-top: 20px;  font-size: 13px">
                COMMENTS/COMPLAINTS : 
                <span v-if="data.comments">       
                    <template v-for="(comment, index) in data.comments" class="m-5 mb-10">
                        <table>
                        <p>[{{ index +1 }}] {{ comment }}</p>
                        </table>
                    </template>
                </span>

            </div>

                <div style="margin-top: 5px ; font-size: 13px">
                    ANALYSIS : 
                    <div  style="text-align: justify; margin: 5px">
                        The  <span>{{ data.unit.unit_name }}</span> unit had <span>{{ data.total_respondents }}</span> respondents who rated the CSF, 
                        and <span>{{ data.total_vss_respondents }}</span> (or <span>{{ data.percentage_vss_respondents }}</span>%) of those respondents rated 
                        the unit with satisfied responses (VS & S) for all service quality attributes. The <span>{{ data.unit.unit_name }}</span> unit had a 
                        <span>{{ data.customer_satisfaction_index }}</span>% Customer Satisfaction Index as well as a Net Promoter Score of <span>{{ data.ave_net_promoter_score }}%</span>. 
                        The Customer Satisfaction Rating for the <span>{{ data.unit.unit_name }}</span> 
                        unit is <span>{{ data.customer_satisfaction_rating }}</span>%, 
                        which achieved its functional objective of 95% of customer surveyed are at least satisfied with the S&T services of DOST-IX
                    </div>
                </div>
        </div> 
   
</template>
