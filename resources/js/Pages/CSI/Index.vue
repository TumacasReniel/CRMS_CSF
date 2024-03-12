<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PrintReport from '@/Pages/CSI/PrintReport.vue';
import { reactive, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { Printd } from "printd";

const props = defineProps({
    service: Object, 
    unit: Object,

    dimensions: Object,
    respondents_list: Object,
    y_totals: Object,
    likert_scale_rating_totals: Object,
    lsr_grand_total: Number,

    grand_vs_total: Number,
    grand_s_total: Number,
    grand_n_total: Number,
    grand_d_total: Number,
    grand_vd_total: Number,
    grand_total: Number,

    x_totals: Object,
    x_grand_total: Object,

    //Importance Attribute Ratings
    importance_rate_score_totals: Object,
    x_importance_totals: Object,
    importance_ilsr_totals:Object,

    //GAP
    gap_totals: Object,
    gap_grand_total: Number,
    
    //WF
    wf_totals: Object,

    //SS
    ss_totals: Object,

    //WS
    ws_totals: Object,

    //Total Respondents or Customers
    total_respondents: Number,

    // Total REspondents or Cutomers who rated VS/S
    total_vss_respondents: Number,

    // Total Percentage Percentage of Respondents/Customers who rated VS/S
    percentage_vss_respondents: Number,

    // Customer Satisfaction Rating(CSAT)
    customer_satisfaction_rating: Number,

    //Customer SAtisfaction Index(CSI)
    customer_satisfaction_index: Number,

    //Net Promotion Score(NPS)
    net_promotion_score: Number,

    //Percentage of Promoters
    percentage_promoters: Number,

    //Percentage of Detractors
    percentage_detractors: Number,
  
});

const enabled = false;

const form = reactive({
  date_from: null,
  date_to: null,
  service: null,
  unit:  null,
})

const generateCSIReport = async (service, unit) => {
   form.service = service;
   form.unit = unit;
   console.log(service,990);
    router.get('/csi/generate', form , { preserveState: true})
};

const printCSIReport = async () => {
    // Create an instance of Printd
      let d = new Printd();
      let css = ` 
    @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;600;800&family=Roboto:wght@100;300;400;500;700;900&display=swap');
    * {
        font-family: 'Raleway'
        }`;

       d.print(document.querySelector(".print-id"), [css]);
};

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Customer Satisfaction Index
            </h2>
        </template>

        

        <div class="py-10"  style="margin-left:80px; margin-right:80px">
            <div class="max-w-7x1 mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <v-card class="mb-3">
                        <v-card-title class="m-3">
                            <div>
                                SERVICES :   {{ service.services_name }}
                            </div>
                            <v-divider class="border-opacity-100"></v-divider>
                            <div>
                                SERVICE UNIT :    {{ unit.unit_name }}
                            </div>
                        </v-card-title>
                    </v-card>
                     <v-divider class="border-opacity-100"></v-divider>
                     <v-card class="mb-3 my-auto">

                         <v-row class="p-3">
                            <v-col class="my-auto">
                                 <v-text-field
                                    label="Select Date From"
                                    placeholder="Date From"
                                    variant="outlined"
                                    size="x-small"
                                    type="date"
                                    v-model="form.date_from"
                                ></v-text-field>
                            </v-col>
                            <v-col>
                                 <v-text-field
                                    label="Select Date To"
                                    placeholder="Date To"
                                    variant="outlined"
                                    size="x-small"
                                    type="date"
                                     v-model="form.date_to"
                                ></v-text-field>
                            </v-col>
                            <v-col class="ml-5">
                              <v-btn @click="generateCSIReport(service, unit)" >Generate</v-btn>
                            </v-col>
                           <v-col class="text-end mr-5">
                             <v-btn prepend-icon="mdi-printer" @click="printCSIReport()">Print</v-btn>
                           </v-col>
                        </v-row>
                     </v-card>
                     <v-card class="mb-3">
                        <v-card-title class="bg-gray-500 text-white">
                           PART I: CUSTOMER RATING OF SERVICE QUALITY     
                        </v-card-title>
                       <table class="w-full border">
                            <tr class="text-left font-bold text-center">
                                <th class="pb-4 pt-6 px-6">Service Quality Attributes</th>
                                <th class="pb-4 pt-6 px-6">Very Satisfied (5)</th>
                                <th class="pb-4 pt-6 px-6">Satisfied (4)</th>
                                <th class="pb-4 pt-6 px-6">Neither (3)</th>
                                <th class="pb-4 pt-6 px-6" >Dissatisfied (2)</th>
                                <th class="pb-4 pt-6 px-6">Very Dissatisfied (1)</th>
                                <th class="pb-4 pt-6 px-6">TOTAL SCORE</th>
                                 <th class="pb-4 pt-6 px-6">Likert Scale Rating</th>
                                <th class="pb-4 pt-6 px-6">GAP</th>
                            </tr>

                            <tr v-for="(dimension, index) in dimensions" :key="dimension.id" class="border border-solid hover:bg-gray-100 focus-within:bg-gray-100">                     
                                    <td class="border-t p-5 pl-10">
                                        {{ index + 1 }}.{{ dimension.name }}
                                    </td>
                                    <td v-if="y_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in y_totals[index+1]">
                                        {{ total }}
                                    </td>
                                     <td v-if="x_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in x_totals[index+1]">
                                        {{ total }}
                                    </td>
                                     <td v-if="likert_scale_rating_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in likert_scale_rating_totals[index+1]">
                                        {{ total }}
                                    </td>          
                                    <td v-if="likert_scale_rating_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in gap_totals[index+1]">
                                        {{ total }}
                                    </td>                   
                            </tr>
                            <tr class="text-center font-black p-5 m-5 border border-solid hover:bg-gray-100 focus-within:bg-gray-100">
                                <td class="m-5 p-3">TOTAL SCORE</td>
                                <td class="border-t ">{{ grand_vs_total }}</td>
                                <td class="border-t ">{{ grand_s_total }}</td>
                                <td class="border-t ">{{ grand_n_total }}</td>
                                <td class="border-t ">{{ grand_d_total }}</td>
                                <td class="border-t">{{ grand_vd_total }}</td>
                                <td class="border-t">{{ x_grand_total }}</td>
                                <td class="border-t">{{ lsr_grand_total }}</td>
                                <td class="border-t">{{ gap_grand_total }}</td>
                            </tr>                                               
                        </table>

               
                    </v-card> 
                    <v-card class="mb-3">
                        <v-card-title class="bg-gray-500 text-white">
                           PART II: IMPORTANCE OF THIS ATTRIBUTE   
                        </v-card-title>
                        <v-card-body>
                            <table class="w-full border">
                                <tr class="text-left font-bold text-center">
                                    <th class="pb-4 pt-6 px-6">Importance Service Quality Attributes</th>
                                    <th class="pb-4 pt-6 px-6">Very Important(5)</th>
                                    <th class="pb-4 pt-6 px-6">Important (4)</th>
                                    <th class="pb-4 pt-6 px-6">Moderately Important (3)</th>
                                    <th class="pb-4 pt-6 px-6" >Slightly Important (2)</th>
                                    <th class="pb-4 pt-6 px-6">Not All Important (1)</th>
                                    <th class="pb-4 pt-6 px-6">TOTAL SCORE</th>
                                    <th class="pb-4 pt-6 px-6">LS Rating</th>
                                    <th class="pb-4 pt-6 px-6">WF</th>
                                    <th class="pb-4 pt-6 px-6">SS</th>
                                    <th class="pb-4 pt-6 px-6">WS</th>
                                </tr>

                                <tr v-for="(dimension, index) in dimensions" :key="dimension.id" class="border border-solid hover:bg-gray-100 focus-within:bg-gray-100">
                                    
                                        <td class="border-t p-3 pl-10 w-1/8 ">
                                            {{ index + 1 }}.{{ dimension.name }}
                                        </td>
                                        <td v-if="y_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in importance_rate_score_totals[index+1]">
                                            {{ total }}
                                        </td>
                                        <td v-if="x_importance_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in x_importance_totals[index+1]">
                                            {{ total }}
                                        </td>
                                        <td v-if="likert_scale_rating_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in importance_ilsr_totals[index+1]">
                                            {{ total }}
                                        </td>  
                                        <td v-if="wf_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in wf_totals[index+1]">
                                            {{ total }}
                                        </td>       
                                        <td v-if="ss_totals" class="border-t p-5 w-1/8 text-center"  v-for="total in ss_totals[index+1]">
                                            {{ total }}
                                        </td>  
                                        <td v-if="ws_totals" class="border-t p-5 w-1/8 text-center mr-10"  v-for="total in ws_totals[index+1]">
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
                                        {{ total_respondents }}
                                    </v-card-content>
                                </v-card>

                                  <v-card class="mb-2">
                                    <v-card-title class="bg-secondary text-white">
                                            Total No. of Respondents/Customers who rated VS/S: 
                                    </v-card-title>
                                    <v-card-content class="p-5 m-5 text-lg">
                                        {{ total_vss_respondents }}
                                    </v-card-content>
                                </v-card>
                                  <v-card class="mb-2">
                                    <v-card-title class="bg-secondary text-white">
                                          Percentage of Respondents/Customers who rated VS/S:       
                                    </v-card-title>
                                    <v-card-content class="p-5 m-5 text-lg">
                                        {{ percentage_vss_respondents }} %
                                    </v-card-content>
                                </v-card>

                                 <v-card class="mb-2">
                                    <v-card-title class="bg-secondary text-white">
                                        Customer Satisfaction Score Rating(CSAT) 
                                    </v-card-title>
                                    <v-card-content class="p-5 m-5 text-lg">
                                        {{ customer_satisfaction_rating }} %
                                    </v-card-content>
                                </v-card>
                            </v-col>
                           <v-col cols="6" >
                                <v-card class="mb-2">
                                      <v-card-title class="bg-gray-500 text-white">
                                            Customer Satifaction Index(CSI)
                                    </v-card-title>
                                    <v-card-content class="p-10 m-5 text-lg " >
                                        {{ customer_satisfaction_index }} %
                                    </v-card-content>
                                </v-card>

                                <v-card class="mb-2">
                                    <v-card-title class="bg-gray-500 text-white">
                                            Net Promotion Score(NPS)
                                    </v-card-title>
                                    <v-card-content class="p-5 m-5 text-lg">
                                        {{ net_promotion_score }} %
                                    </v-card-content>
                                </v-card>
                  
                               <v-row>
                                    <v-col cols="6">
                                        <v-card class="mb-2">
                                             <v-card-title class="bg-gray-500 text-white">
                                                Percentage of Promoters
                                            </v-card-title>
                                            <v-card-content class="p-5 m-5 text-lg">
                                                {{ percentage_promoters }} %
                                            </v-card-content>
                                            
                                        </v-card>
                                    </v-col>
                                     <v-col>
                                        <v-card class="mb-2">
                                             <v-card-title class="bg-gray-500 text-white">
                                            Percentage of Detractors
                                            </v-card-title>
                                            <v-card-content class="p-5 m-5 text-lg">
                                                {{ percentage_detractors }} %
                                            </v-card-content>                                        
                                        </v-card>
                                    </v-col>
                               </v-row>
                                 <v-card class="mb-2">
                                    <v-card-title class="bg-gray-500 text-white">
                                        Likert Scale Rating(Average)
                                    </v-card-title>
                                    <v-card-content class="p-5 m-5 text-lg">
                                        {{ lsr_grand_total }}
                                    </v-card-content>
                                </v-card>

                              
                            </v-col>
                                
                        </v-row>           
                    </v-card> 
 

                      <v-card class="mb-3">
                        <v-card-title class="bg-gray-500 text-white">
                          RESPONDENTS/CUSTOMERS LIST
                        </v-card-title>
                        <v-card-content>
                             <table class="w-full border">
                                <tr class="text-left font-bold text-center">
                                    <th class="pb-4 pt-6 px-6">#</th>
                                    <th class="pb-4 pt-6 px-6">Name</th>
                                    <th class="pb-4 pt-6 px-6">Email</th>
                                    <th class="pb-4 pt-6 px-6">Sex</th>
                                    <th class="pb-4 pt-6 px-6">is PWD?</th>
                                    <th class="pb-4 pt-6 px-6">is Pregnant?</th>
                                    <th class="pb-4 pt-6 px-6">is Senior Citizen?</th>
                                    <th class="pb-4 pt-6 px-6">Attribute Rate Score</th>
                                    <th class="pb-4 pt-6 px-6">Importance</th>
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
       
                    </v-card>               
                </div>
            </div>
        </div>
        <PrintReport :data="props" />
      
    </AppLayout>
</template>
