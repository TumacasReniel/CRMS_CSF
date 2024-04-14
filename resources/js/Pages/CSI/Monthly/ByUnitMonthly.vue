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
        <div class="print-id print">
            <div style="width: 100%;">
               <h5 style="text-transform:capitalize; text-align:center; margin-top: -10px">
                    CUSTOMER SATISFACTION FEEDBACK <br>SUMMARY REPORT FOR 
                    <u><span>{{ form.selected_month }}</span>  {{ form.selected_year }}</u>
                </h5><br>
                <div style="display: flex; justify-content: space-between;" v-if="data.service && data.unit">
                    <div style="font-size: 12px;">
                        Services : <u>{{ data.service.services_name }}</u> 
                       
                    </div>
                    <div style="font-size: 12px">  
                        Services Unit : <u v-if="data.unit.data.length > 0">{{ data.unit.data[0].unit_name }}</u><br>
                                        <u v-if="form.selected_unit_psto">{{ form.selected_unit_psto.psto_name }}</u><br v-if="form.selected_unit_psto.length > 0">
                                        <u v-if="data.sub_unit.length > 0" style="margin-left: 75px">{{ data.sub_unit[0].sub_unit_name }}</u>
                                         <u v-if="form.sub_unit_type" style="margin-left: 5px">{{ form.sub_unit_type.type_name }}</u>
                                        <u v-if="form.client_type" style="margin-left: 75px">{{ form.client_type }}</u> <br>
                                        <u v-if="form.selected_sub_unit_psto" style="margin-left: 75px">{{ form.selected_sub_unit_psto.psto_name }}</u>
                    </div>
                    
                </div>

                <div style="margin-top: 5px; margin-bottom: 20px">
                    <div style="font-size: 12px;margin-right:20px; margin-bottom:15px; font-weight: bold">PART I: CUSTOMER RATING OF SERVICE QUALITY  </div>
                    <table style="font-size: 13px;width:100%; border: 1px solid #333; border-collapse: collapse;  padding: 3px">
                        <tr style="border: 1px solid #333; text-align: center;">
                            <td style="border: 1px solid #333; padding:2px; " rowspan="2">Service Quality Attributes</td>
                            <td style="border: 1px solid #333;">5</td>
                            <td style="border: 1px solid #333;">4</td>
                            <td style="border: 1px solid #333;">3</td>
                            <td style="border: 1px solid #333;">2</td>
                            <td style="border: 1px solid #333;">1</td>
                            <td style="border: 1px solid #333; padding:2px" rowspan="2">TOTAL SCORE</td>
                            <td style="border: 1px solid #333; padding:2px" rowspan="2">Likert Scale Rating</td>
                            <td style="border: 1px solid #333; padding:2px" rowspan="2">GAP</td>
                        </tr>
                        <tr style="border: 1px solid #333; text-align: center;">               
                            <td style="border: 1px solid #333; padding:2px">Very Satisfied</td>
                            <td style="border: 1px solid #333;padding:2px">Satisfied </td>
                            <td style="border: 1px solid #333; padding:2px">Neither </td>
                            <td style="border: 1px solid #333; padding:2px" >Dissatisfied </td>
                            <td style="border: 1px solid #333; padding:2px">Very Dissatisfied </td>
                        </tr>

                        <tr v-for="(dimension, index) in data.dimensions" :key="dimension.id" style="border: 1px solid #333;" >              
                                <td style="border: 1px solid #333; padding: 3px; ">
                                    {{ dimension.name }}
                                </td>          
                                <td v-if="data.y_totals" style="border: 1px solid #333; padding: 2px; text-align: center" v-for="total in data.y_totals[index+1]">
                                    {{ total }}
                                </td>
                                <td v-if="data.x_totals" style="border: 1px solid #333; padding: 2px; text-align: center"  v-for="total in data.x_totals[index+1]">
                                    {{ total }}
                                </td>
                                <td v-if="data.likert_scale_rating_totals" style="border: 1px solid #333; padding: 2px; text-align: center"  v-for="total in data.likert_scale_rating_totals[index+1]">
                                    {{ total }}
                                </td>          
                                <td v-if="data.gap_totals" style="border: 1px solid #333; padding: 2px; text-align: center" v-for="total in data.gap_totals[index+1]">
                                    {{ total }}
                                </td>                   
                        </tr>

                        <tr style="border: 1px solid #333; text-align: center;">
                            <td style="border: 1px solid #333; ">TOTAL SCORE</td>
                            <td style="border: 1px solid #333;">{{ data.grand_vs_total }}</td>
                            <td style="border: 1px solid #333;">{{ data.grand_s_total }}</td>
                            <td style="border: 1px solid #333;">{{ data.grand_n_total }}</td>
                            <td style="border: 1px solid #333;">{{ data.grand_d_total }}</td>
                            <td style="border: 1px solid #333;">{{ data.grand_vd_total }}</td>
                            <td style="border: 1px solid #333;">{{ data.x_grand_total }}</td>
                            <td style="border: 1px solid #333;">{{ data.lsr_grand_total }}</td>
                            <td style="border: 1px solid #333;">{{ data.gap_grand_total }}</td>
                        </tr>  
                    </table>

                </div>

                <div style="margin-top: 20px">
                    <div style="font-size: 13px;margin-right:20px; margin-bottom:5px; font-weight: bold">PART II: IMPORTANCE OF THIS ATTRIBUTE    </div>
                    <table style="width:100%; border: 1px solid #333; border-collapse: collapse; font-size: 13px;">
                        <tr style="border: 1px solid #333; text-align: center;">
                            <td style="border: 1px solid #333;" rowspan="2">Importance Service Quality Attributes</td>
                            <td style="border: 1px solid #333;">5</td>
                            <td style="border: 1px solid #333;">4</td>
                            <td style="border: 1px solid #333;">3</td>
                            <td style="border: 1px solid #333;">2</td>
                            <td style="border: 1px solid #333;">1</td>
                            <td style="border: 1px solid #333; padding:2px" rowspan="2">TOTAL SCORE</td>
                            <td style="border: 1px solid #333; padding:2px" rowspan="2">Likert Scale Rating</td>
                           <td  style="border: 1px solid #333; padding:2px" rowspan="2">WF</td>
                            <td  style="border: 1px solid #333; padding:2px" rowspan="2">SS</td>
                            <td  style="border: 1px solid #333; padding:2px" rowspan="2">WS</td>
                        </tr>
                        <tr style="border: 1px solid #333; text-align: center;">               
                            <td style="border: 1px solid #333; margin: 5px; padding:2px">Very Important</td>
                            <td style="border: 1px solid #333;  margin: 5px; padding:2px">Important </td>
                            <td style="border: 1px solid #333;  margin: 5px; padding:2px">Moderately Important </td>
                            <td style="border: 1px solid #333;  margin: 5px; padding:px" >Slightly Important  </td>
                            <td style="border: 1px solid #333;  margin: 5px; padding:2px">Not All Important </td>
                        </tr>

                        <tr v-for="(dimension, index) in data.dimensions" :key="dimension.id" style="border: 1px solid #333;" >   
                                 <td style="border: 1px solid #333; padding: 2px;">
                                    {{ dimension.name }}
                                </td>                  
                                <td v-if="data.importance_rate_score_totals" style="border: 1px solid #333; padding: 2px; text-align: center" v-for="total in data.importance_rate_score_totals[index+1]">
                                    {{ total }}
                                </td>
                                <td v-if="data.x_importance_totals" style="border: 1px solid #333; padding: 2px; text-align: center" v-for="total in data.x_importance_totals[index+1]">
                                    {{ total }}
                                </td>
                                <td v-if="data.likert_scale_rating_totals" style="border: 1px solid #333; padding: 2px; text-align: center" v-for="total in data.importance_ilsr_totals[index+1]">
                                    {{ total }}
                                </td>  
                                <td v-if="data.wf_totals" style="border: 1px solid #333; padding: 2px; text-align: center" v-for="total in data.wf_totals[index+1]">
                                    {{ total }}
                                </td>       
                                <td v-if="data.ss_totals" style="border: 1px solid #333; padding: 2px; text-align: center" v-for="total in data.ss_totals[index+1]">
                                    {{ total }}
                                </td>  
                                <td v-if="data.ws_totals" style="border: 1px solid #333; padding: 2px; text-align: center"  v-for="total in data.ws_totals[index+1]">
                                    {{ total }}
                                </td>                                   
                        </tr>


                    </table>

                </div>

                <div style="font-size:13px;margin-top: 20px; text-align:center; text-align: center; font-size: 13px">
                    <table style="width: 100% ;border: 1px solid #333; border-collapse:collapse; margin: auto; ">
                        <tr style="border: 1px solid #333; border-collapse:collapse;">
                            <td style="border: 1px solid #333;">
                                <div style=" padding: 2px">
                                    Total No. of Respondents/Customers:
                                </div>
                            </td>
                            <td style="border: 1px solid #333;">
                                <div style=" padding: 2px">
                                    {{ data.total_respondents }}
                                </div>
                            </td>

                             <td style="border: 1px solid #333;">
                                <div style="padding: 2px ">
                                    Customer Satisfaction Index (CSI) :
                                </div>
                            </td>
                            <td style="border: 1px solid #333;">
                                <div style=" padding:2px">
                                    {{ data.customer_satisfaction_index }}
                                </div>
                            </td>
                        </tr>

                        <tr style="border: 1px solid #333; border-collapse:collapse;">
                            <td style="border: 1px solid #333;">
                                <div style=" padding: 2px">
                                    Total No. of Respondents/Customers who rated VS/S:
                                </div>
                            </td>
                            <td style="border: 1px solid #333;">
                                <div style=" padding: 2px">
                                    {{ data.total_vss_respondents }}
                                </div>
                            </td>

                              <td style="border: 1px solid #333;">
                                <div style=" padding: 2px">
                                   Net Promotion Score: 
                                </div>
                            </td>
                            <td style="border: 1px solid #333;">
                                <div style="padding: 2px">
                                    {{ data.net_promotion_score }}
                                </div>
                            </td>

                        </tr>

                        <tr style="border: 1px solid #333;">
                            <td style="border: 1px solid #333;">
                                <div style=" padding: 2px">
                                    Percentage of Respondents/Customers who rated VS/S: 
                                </div>
                            </td>
                            <td style="border: 1px solid #333;">
                                <div style="padding: 2px">
                                    {{ data.percentage_vss_respondents }}
                                </div>
                            </td>



                             <td style="border: 1px solid #333;">
                                <div style="padding: 2px">
                                Percentage of Promoters:  
                                </div>
                            </td>
                            <td style="border: 1px solid #333;">
                                <div style=" padding: 2px">
                                    {{ data.percentage_promoters }}
                                </div>
                            </td>
                        </tr>
                        <tr style="border: 1px solid #333;">         
                            <td style="padding: 3px"></td>  
                            <td style="padding: 3px"></td>     
                            <td style="border: 1px solid #333;">
                                <div style="padding: 3px">
                                Percentage of Detractors:  
                                </div>
                            </td>
                            <td style="border: 1px solid #333;">
                                <div style=" padding: 3px">
                                    {{ data.percentage_detractors }}
                                </div>
                            </td>
                        </tr>


                          <tr style="border: 1px solid #333;">
                            <td style="border-collapse:collapse;">
                                <div style=" padding: 3px">
                                Customer Satisfaction  Rating : 
                                </div>
                            </td>
                            <td style="border: 1px solid #333;">
                                <div style="padding: 3px">
                                    {{ data.customer_satisfaction_rating }}
                                </div>
                            </td>
                            <td style="border: 1px solid #333;">
                                <div style=" padding: 3px">
                                    Likert Scale Rating(Average):
                                </div>
                            </td>
                            <td style="border: 1px solid #333;">
                                <div style="padding: 3px">
                                    {{ data.customer_satisfaction_rating }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div style="margin-top: 20px;  font-size: 13px">
                    COMMENTS/COMPLAINTS : 
                    <span v-if="data.comments">       
                        <template v-for="(comment, index) in data.comments" class="m-5 mb-10">
                            <table>
                            <p style="margin-bottom:-10px">[{{ index +1 }}] {{ comment }}</p>
                            </table>
                        </template>
                    </span>
                    <span v-else>       
                        none
                    </span>
                    <p></p>
                </div>

                 <div style="margin-top: 20px ; font-size: 13px">
                    ANALYSIS : 
                    <div v-if="data.unit && data.service"  style="text-align: justify; margin: 5px">
                        The  <span>{{ data.unit.unit_name }}</span> unit had 15 respondents who rated the CSF, 
                        and <span>{{ data.total_vss_respondents }}</span> (or <span>{{ data.percentage_vss_respondents }}</span>%) of those respondents rated 
                        the unit with satisfied responses (VS & S) for all service quality attributes. The <span>{{ data.unit.unit_name }}</span> unit had a 
                        <span>{{ data.customer_satisfaction_index }}</span>% Customer Satisfaction Index as well as a Net Promoter Score of <span>{{ data.net_promotion_score }}</span>. 
                        The Customer Satisfaction Rating for the <span>{{ data.unit.unit_name }}</span> 
                        unit is <span>{{ data.customer_satisfaction_rating }}</span>%, 
                        which achieved its functional objective of 95% of customer surveyed are at least satisfied with the S&T services of DOST-IX
                    </div>
                </div>

                <div style="margin-top: 40px; display: flex;  font-size: 13px">
                   <div>
                     Prepared by : 
                   </div>
                   <div style="margin: auto; margin-left: 200px;  ">
                     Noted by : 
                   </div>
                </div>


                
                

               
            </div>  
        </div>
</template>
<style scoped>
    .print {
        display: none;
    }
    @media print {
        .print-id {
            display: block;
        }
    
    }

</style>
