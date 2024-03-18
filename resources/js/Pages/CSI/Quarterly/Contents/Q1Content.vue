<script setup>
    const props = defineProps({
        data: {
            type: Object,
        },
        form: {
            type: Object,
        },
    });
</script>
<template>
    <v-card class="mb-3">
                <v-card-title class="bg-gray-500 text-white">
                    PART I: CUSTOMER RATING OF SERVICE QUALITY 
                </v-card-title>
                <table class="w-full border ">
                    <tr class="text-left font-bold text-center bg-blue-200">
                        <th colspan="3">Service Quality Attributes</th>
                        <th>JAN</th>
                        <th>FEB</th>
                        <th>MAR</th>
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
                                <td v-if="data.q1_vs_totals" class="text-center"  v-for="total in data.q1_vs_totals[index+1]">
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
                                <td v-if="data.q1_s_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in data.q1_s_totals[index+1]">
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
                                <td v-if="data.q1_n_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in data.q1_n_totals[index+1]">
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
                                <td v-if="data.q1_d_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in data.q1_d_totals[index+1]">
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
                                <td v-if="data.q1_vd_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in data.q1_vd_totals[index+1]">
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
                                <td v-if="data.q1_grand_totals" class="text-center bg-gray-300"  v-for="total in data.q1_grand_totals[index+1]">
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
                        <td class="text-center">{{ data.jan_total_vs_respondents }}</td>
                        <td class="text-center"> {{ data.feb_total_vs_respondents }} </td>
                        <td class="text-center">{{ data.mar_total_vs_respondents }} </td>
                        <td class="text-center">{{ data.vs_grand_total_raw_points }} </td>
                        <td class="text-center">{{ data.vs_grand_total_score }}</td>
                        <td class="text-center">{{ data.grand_total_score / data.grand_total_score * 100 }}</td>

                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Total No. of Satisfied (S) Responses:</td>
                        <td class="text-center">{{ data.jan_total_s_respondents }}</td>
                        <td class="text-center"> {{ data.feb_total_s_respondents }} </td>
                        <td class="text-center">{{ data.mar_total_s_respondents }} </td>
                        <td class="text-center">{{ data.s_grand_total_raw_points }}</td>
                        <td class="text-center">{{ data.s_grand_total_score }}</td>
                        <td class="text-center">{{ data.s_grand_total_score / data.grand_total_score * 100 }}</td>

 
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Total No. of Other (N, D, VD) Responses:</td>
                        <td class="text-center">{{ data.jan_total_ndvd_respondents }}</td>
                        <td class="text-center"> {{ data.feb_total_ndvd_respondents }} </td>
                        <td class="text-center">{{ data.mar_total_ndvd_respondents }} </td>
                        <td class="text-center">{{ data.ndvd_grand_total_raw_points }}</td>
                        <td class="text-center">{{ data.ndvd_grand_total_score }}</td>
                        <td class="text-center">{{ data.ndvd_grand_total_score / data.grand_total_score * 100 }}</td>


                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Total No. of All Responses::</td>
                        <td class="text-center">{{ data.jan_total_respondents }}</td>
                        <td class="text-center"> {{ data.feb_total_respondents }} </td>
                        <td class="text-center">{{ data.mar_total_respondents }} </td>
                        <td class="text-center">{{ data.grand_total_raw_points }} </td>
                        <td class="text-center">{{ data.grand_total_score }}</td>
                        <td class="text-center">{{ data.lsr_grand_total }}</td>


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
            </v-card> 
            
            <v-card class="mb-3">
                <v-card-title class="bg-gray-500 text-white">
                    PART II: IMPORTANCE OF THIS ATTRIBUTE   
                </v-card-title>
                 <table class="w-full border ">
                    <tr class="text-left font-bold text-center bg-blue-200">
                        <th  colspan="3">Importance Service Quality Attributes</th>
                         <th >JAN</th>
                        <th >FEB</th>
                        <th >MAR</th>
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
                                <td v-if="data.q1_vi_totals" class="text-center"  v-for="total in data.q1_vi_totals[index+1]">
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
                                <td v-if="data.q1_i_totals" class="text-center"  v-for="total in data.q1_i_totals[index+1]">
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
                                <td v-if="data.q1_mi_totals" class="text-center"  v-for="total in data.q1_mi_totals[index+1]">
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
                                <td v-if="data.q1_si_totals" class="text-center"  v-for="total in data.q1_si_totals[index+1]">
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
                                <td v-if="data.q1_nai_totals" class="text-center"  v-for="total in data.q1_nai_totals[index+1]">
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
                                 <td v-if="data.q1_grand_totals" class="text-center bg-gray-300"  v-for="total in data.q1_grand_totals[index+1]">
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
                        <td>JAN</td>
                        <td>FEB</td>
                        <td>MAR</td>
                        <td colspan="2">AVERAGE(%)</td>

                    </tr>

                    <tr>
                        <td colspan="3" class="text-right">% of Promoters:</td>
                         <td v-if="data.jan_percentage_promoters" class="text-center" >
                            {{ data.jan_percentage_promoters }} 
                        </td>  
                        <td v-if="data.feb_percentage_promoters" class="text-center " >
                            {{ data.feb_percentage_promoters }} 
                        </td>
                        <td v-if="data.jan_percentage_promoters" class="text-center" >
                            {{ data.mar_percentage_promoters }} 
                        </td>
                        <td v-if="data.average_percentage_promoters" colspan="2"  class="text-center">
                             {{ data.average_percentage_promoters }} 
                        </td>

                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">% of Detractors:</td>
                        <td v-if="data.jan_percentage_detractors" class="text-center" >
                            {{ data.jan_percentage_detractors }} 
                        </td>  
                        <td v-if="data.feb_percentage_detractors" class="text-center " >
                            {{ data.feb_percentage_detractors  }} 
                        </td>
                        <td v-if="data.jan_percentage_detractors" class="text-center" >
                            {{ data.mar_percentage_detractors  }} 
                        </td>
                        <td v-if="data.average_percentage_detractors" colspan="2"  class="text-center">
                             {{ data.average_percentage_detractors  }} 
                        </td>

                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Net Promoter Score:</td>
                        <td v-if="data.jan_net_promoter_score" class="text-center" >
                            {{ data.jan_net_promoter_score }} 
                        </td>  
                        <td v-if="data.feb_percentage_detractors" class="text-center " >
                            {{ data.feb_percentage_detractors  }} 
                        </td>
                        <td v-if="data.mar_net_promoter_score" class="text-center" >
                            {{ data.mar_net_promoter_score  }} 
                        </td>
                        <td v-if="data.ave_net_promoter_score" colspan="2"  class="text-center">
                             {{ data.ave_net_promoter_score  }} 
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Customer Satisfaction Index (CSI):</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td colspan="2"></td>
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
            </v-card> 
        



            <v-card class="mb-3">
                <v-card-title>
                    COMMENTS AND COMPLAINTS: 
                </v-card-title>
                <v-card-content class="m-5 mb-10">
                        <v-row>
                        <div class="ml-10">Comments
                        <v-chip color="primary">
                            0
                        </v-chip>
                        </div>
                        <div class="">Complaints
                            <v-chip color="red">
                                0
                            </v-chip>
                        </div>
                    </v-row>
                </v-card-content>
            </v-card>

            <v-card class="mb-3">
                <v-card-title>
                    ANALYSIS:
                </v-card-title>
                <v-card-content >
                    <div class="m-5" style="margin-top: -10px;text-align: justify;">
                            The  <span>{{ data.unit.unit_name }}</span> unit had 15 respondents who rated the CSF, 
                            and <span>{{ data.total_vss_respondents }}</span> (or <span>{{ data.percentage_vss_respondents }}</span>%) of those respondents rated 
                            the unit with satisfied responses (VS & S) for all service quality attributes. The <span>{{ data.unit.unit_name }}</span> unit had a 
                            <span>{{ data.customer_satisfaction_index }}</span>% Customer Satisfaction Index as well as a Net Promoter Score of <span>{{ data.net_promotion_score }}</span>. 
                            The Customer Satisfaction Rating for the <span>{{ data.unit.unit_name }}</span> 
                            unit is <span>{{ data.customer_satisfaction_rating }}</span>%, 
                            which achieved its functional objective of 95% of customer surveyed are at least satisfied with the S&T services of DOST-IX
                    </div>
                </v-card-content>
            </v-card>
   
</template>

<style scoped>
   table {
    border-collapse: collapse;
    width: 100%; /* Optional: Set a width for the table */
  }

  tr, th, td {
    border: 1px solid rgb(145, 139, 139); /* Optional: Add a border for better visibility */
    padding: 3px; /* Optional: Add padding for better spacing */
  }
</style>