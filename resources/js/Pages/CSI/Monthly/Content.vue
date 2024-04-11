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
                <table style="width:100%; border: 1px solid #333; border-collapse: collapse;  padding: 3px" >
                   <tr class="text-center">
                        <td rowspan="2">Service Quality Attributes</td>
                        <td >5</td>
                        <td >4</td>
                        <td >3</td>
                        <td >2</td>
                        <td >1</td>
                        <td  rowspan="2">TOTAL SCORE</td>
                        <td  rowspan="2">Likert Scale Rating</td>
                        <td  rowspan="2">GAP</td>
                    </tr>
                    <tr class="text-center">               
                        <td >Very Satisfied</td>
                        <td style="border: 1px solid #333;padding:2px">Satisfied </td>
                        <td >Neither </td>
                        <td  >Dissatisfied </td>
                        <td >Very Dissatisfied </td>
                    </tr>

                    <tr v-for="(dimension, index) in data.dimensions" :key="dimension.id" class="border border-solid hover:bg-gray-100 focus-within:bg-gray-100">                     
                            <td class="border-t p-5 pl-10">
                                {{ index + 1 }}.{{ dimension.name }}
                            </td>
                            <td v-if="data.y_totals" class="text-center"  v-for="total in data.y_totals[index+1]">
                                {{ total }}
                            </td>
                            <td v-if="data.x_totals" class="text-center"  v-for="total in data.x_totals[index+1]">
                                {{ total }}
                            </td>
                                <td v-if="data.likert_scale_rating_totals" class=" text-center"  v-for="total in data.likert_scale_rating_totals[index+1]">
                                {{ total }}
                            </td>          
                            <td v-if="data.likert_scale_rating_totals" class="text-center"  v-for="total in data.gap_totals[index+1]">
                                {{ total }}
                            </td>                   
                    </tr>
                    <tr class="text-center font-black p-5 m-5 border border-solid hover:bg-gray-100 focus-within:bg-gray-100" >
                        <td class="m-5 p-3">TOTAL SCORE</td>
                        <td >{{ data.grand_vs_total }}</td>
                        <td >{{ data.grand_s_total }}</td>
                        <td >{{ data.grand_n_total }}</td>
                        <td >{{ data.grand_d_total }}</td>
                        <td >{{ data.grand_vd_total }}</td>
                        <td >{{ data.x_grand_total }}</td>
                        <td >{{ data.lsr_grand_total }}</td>
                        <td >{{ data.gap_grand_total }}</td>
                    </tr>                                               
                </table>

        
            </v-card> 
            
            <v-card class="mb-3">
                <v-card-title class="bg-gray-500 text-white">
                    PART II: IMPORTANCE OF THIS ATTRIBUTE   
                </v-card-title>
                <v-card-body>
                    <table style="width:100%; border: 1px solid #333; border-collapse: collapse;  padding: 3px">
                       <tr class="text-center">
                            <td  rowspan="2">Importance Service Quality Attributes</td>
                            <td >5</td>
                            <td >4</td>
                            <td >3</td>
                            <td >2</td>
                            <td >1</td>
                            <td  rowspan="2">TOTAL SCORE</td>
                            <td  rowspan="2">Likert Scale Rating</td>
                           <td   rowspan="2">WF</td>
                            <td   rowspan="2">SS</td>
                            <td   rowspan="2">WS</td>
                        </tr>
                        <tr class="text-center">               
                            <td >Very Important</td>
                            <td >Important </td>
                            <td >Moderately Important </td>
                            <td  >Slightly Important  </td>
                            <td >Not All Important </td>
                        </tr>


                        <tr v-for="(dimension, index) in data.dimensions" :key="dimension.id" class="border border-solid hover:bg-gray-100 focus-within:bg-gray-100">
                            
                                <td class="border-t p-3 pl-10 w-1/8 ">
                                    {{ index + 1 }}.{{ dimension.name }}
                                </td>
                                <td v-if="data.importance_rate_score_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in data.importance_rate_score_totals[index+1]">
                                    {{ total }}
                                </td>
                                <td v-if="data.x_importance_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in data.x_importance_totals[index+1]">
                                    {{ total }}
                                </td>
                                <td v-if="data.likert_scale_rating_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in data.importance_ilsr_totals[index+1]">
                                    {{ total }}
                                </td>  
                                <td v-if="data.wf_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in data.wf_totals[index+1]">
                                    {{ total }}
                                </td>       
                                <td v-if="data.ss_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in data.ss_totals[index+1]">
                                    {{ total }}
                                </td>  
                                <td v-if="data.ws_totals" class="border-t p-5 w-1/8 text-center mr-10"  v-for="total in data.ws_totals[index+1]">
                                    {{ total }}
                                </td>                   
                        </tr>                                           
                    </table>
                </v-card-body>     
            </v-card> 
        
            <v-card class="mb-3 bg-none  font-black">
                <v-row class="text-center">
                    <v-col cols="6" >
                        <v-card class="mb-2">
                            <v-card-title class="bg-secondary text-white">
                                    Total No. of Respondents/Customers:   
                            </v-card-title>
                            <v-card-content class="p-5 m-5 text-lg">
                                {{ data.total_respondents }}
                            </v-card-content>
                        </v-card>

                            <v-card class="mb-2">
                            <v-card-title class="bg-secondary text-white">
                                    Total No. of Respondents/Customers who rated VS/S: 
                            </v-card-title>
                            <v-card-content class="p-5 m-5 text-lg">
                                {{ data.total_vss_respondents }}
                            </v-card-content>
                        </v-card>
                            <v-card class="mb-2">
                            <v-card-title class="bg-secondary text-white">
                                    Percentage of Respondents/Customers who rated VS/S:       
                            </v-card-title>
                            <v-card-content class="p-5 m-5 text-lg">
                                {{ data.percentage_vss_respondents }} %
                            </v-card-content>
                        </v-card>

                            <v-card class="mb-2">
                            <v-card-title class="bg-secondary text-white">
                                Customer Satisfaction Score Rating(CSAT) 
                            </v-card-title>
                            <v-card-content class="p-5 m-5 text-lg">
                                {{ data.customer_satisfaction_rating }} %
                            </v-card-content>
                        </v-card>
                    </v-col>
                    <v-col cols="6" >
                        <v-card class="mb-2">
                                <v-card-title class="bg-gray-500 text-white">
                                    Customer Satifaction Index(CSI)
                            </v-card-title>
                            <v-card-content class="p-10 m-5 text-lg " >
                                {{ data.customer_satisfaction_index }} %
                            </v-card-content>
                        </v-card>

                        <v-card class="mb-2">
                            <v-card-title class="bg-gray-500 text-white">
                                    Net Promotion Score(NPS)
                            </v-card-title>
                            <v-card-content class="p-5 m-5 text-lg">
                                {{ data.net_promoter_score }} %
                            </v-card-content>
                        </v-card>
            
                        <v-row>
                            <v-col cols="6">
                                <v-card class="mb-2">
                                        <v-card-title class="bg-gray-500 text-white">
                                        Percentage of Promoters
                                    </v-card-title>
                                    <v-card-content class="p-5 m-5 text-lg">
                                        {{ data.percentage_promoters }} %
                                    </v-card-content>
                                    
                                </v-card>
                            </v-col>
                                <v-col>
                                <v-card class="mb-2">
                                        <v-card-title class="bg-gray-500 text-white">
                                    Percentage of Detractors
                                    </v-card-title>
                                    <v-card-content class="p-5 m-5 text-lg">
                                        {{ data.percentage_detractors }} %
                                    </v-card-content>                                        
                                </v-card>
                            </v-col>
                        </v-row>
                            <v-card class="mb-2">
                            <v-card-title class="bg-gray-500 text-white">
                                Likert Scale Rating(Average)
                            </v-card-title>
                            <v-card-content class="p-5 m-5 text-lg">
                                {{ data.lsr_grand_total }}
                            </v-card-content>
                        </v-card>

                        
                    </v-col>
                        
                </v-row>           
            </v-card> 
            <v-card class="mb-3">
                <v-card-title>
                    COMMENTS AND COMPLAINTS: 
                </v-card-title>
                <v-card-content class="m-5 mb-10">
                        <v-row>
                        <div class="ml-10">Comments
                        <v-chip color="primary">
                            {{ data.total_comments }}
                        </v-chip>
                        </div>
                        <div class="">Complaints
                            <v-chip color="red">
                            {{ data.total_complaints }}
                            </v-chip>
                        </div>

                        <template v-for="(comment, index) in data.comments" class="m-5 mb-10">
                            <table style="margin-left: 40px" >
                            <p>[{{ index +1 }}] {{ comment }}</p>
                            </table>
                        </template>
                    </v-row>
                </v-card-content>
            </v-card>

         

            <v-card class="mb-3">
                <v-card-title>
                    ANALYSIS:
                </v-card-title>
                <v-card-content >
                    <div v-if="data.unit && data.service" class="m-5" style="margin-top: -10px;text-align: justify;">
                            The  <span>{{ data.unit.unit_name }}</span> unit had 15 respondents who rated the CSF, 
                            and <span>{{ data.total_vss_respondents }}</span> (or <span>{{ data.percentage_vss_respondents }}</span>%) of those respondents rated 
                            the unit with satisfied responses (VS & S) for all service quality attributes. The <span>{{ data.unit.unit_name }}</span> unit had a 
                            <span>{{ data.customer_satisfaction_index }}</span>% Customer Satisfaction Index as well as a Net Promoter Score of <span>{{ data.net_promoter_score }}</span>. 
                            The Customer Satisfaction Rating for the <span>{{ data.unit.unit_name }}</span> 
                            unit is <span>{{ data.customer_satisfaction_rating }}</span>%, 
                            which achieved its functional objective of 95% of customer surveyed are at least satisfied with the S&T services of DOST-IX
                    </div>
                </v-card-content>
            </v-card>

            <!-- <v-card class="mb-3">
                <v-card-title class="bg-gray-500 text-white">
                    RESPONDENTS/CUSTOMERS LIST
                </v-card-title>
                <v-card-content>
                        <table class="w-full border">
                        <tr class="text-left font-bold text-center">
                            <th >#</th>
                            <th >Name</th>
                            <th >Email</th>
                            <th >Sex</th>
                            <th >is PWD?</th>
                            <th >is Pregnant?</th>
                            <th >is Senior Citizen?</th>
                            <th >Attribute Rate Score</th>
                            <th >Importance</th>
                        </tr>

                        <tr v-if="respondents_list" v-for="(respondent, index) in respondents_list.data" :key="respondent.id" class="border border-solid hover:bg-gray-100 focus-within:bg-gray-100"> 
                                <td  class="border-t p-5 w-1/8 text-center mr-10">
                                    {{ index + 1 }}
                                </td>  
                                <td  class="border-t p-5 w-1/8 text-center mr-10">
                                    {{ respondent.customer.name }}
                                </td>           
                                    <td  class="border-t p-5 w-1/8 text-center mr-10">
                                    {{ respondent.customer.email }}
                                </td>         
                                <td  class="border-t p-5 w-1/8 text-center mr-10">
                                    {{ respondent.customer.sex }}
                                </td>   
                                    <td  class="border-t p-5 w-1/8 text-center mr-10">
                                    {{ respondent.customer.pwd }}
                                </td>       
                                <td  class="border-t p-5 w-1/8 text-center mr-10">
                                    {{ respondent.customer.pregnant }}
                                </td>  
                                <td  class="border-t p-5 w-1/8 text-center mr-10">
                                    {{ respondent.customer.senior_citizen }}
                                </td>  
                                    <td  class="border-t p-5 w-1/8 text-center mr-10">
                                    {{ respondent.rate_score }}
                                </td>  
                                <td  class="border-t p-5 w-1/8 text-center mr-10">
                                    {{ respondent.importance_rate_score }}
                                </td>
                        </tr>                                           
                    </table>
                </v-card-content>

            </v-card>               -->
</template>

<style scoped>
   table {
    border-collapse: collapse;
    width: 100%; /* Optional: Set a width for the table */
  }

  tr, th, td {
    border: 1px solid rgb(145, 139, 139); /* Optional: Add a border for better visibility */
    padding: 8px; /* Optional: Add padding for better spacing */
  }
</style>