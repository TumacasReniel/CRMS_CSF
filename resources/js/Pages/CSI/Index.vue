<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
// import Account from '@/Components/Account.vue';
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'


defineProps({
    dimensions: Object,
    customer_attribute_ratings: Object,
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
  
});

const form = reactive({
  date_from: null,
  date_to: null,
})

const generateCSIReport = async () => {
   console.log('hello', 9000);
   router.post('/csi', form , { preserveState: true, preserveScroll: true })
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
                        <v-card-title class="m-5">
                            <div>
                                Services :
                            </div>
                            <v-divider class="border-opacity-100"></v-divider>
                            <div>
                                Service Unit :
                            </div>
                        </v-card-title>
                    </v-card>
                     <v-divider class="border-opacity-100"></v-divider>
                     <v-card class="mb-3 my-auto">
                         <v-row class="p-5">
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
                              <v-btn @click="generateCSIReport" >Generate</v-btn>
                            </v-col>
                           <v-col class="text-end mr-5">
                             <v-btn prepend-icon="mdi-printer" >Print</v-btn>
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
                                <!-- <tbody>

                                </tbody> -->
                                <tr v-for="(dimension, index) in dimensions" :key="dimension.id" class="border border-solid hover:bg-gray-100 focus-within:bg-gray-100">
                                    
                                        <td class="border-t p-3 w-1/8 ">
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

                    <v-card class="mb-3">
                        <v-card-title class="bg-gray-500 text-white">
                           PART III: TOTALS
                        </v-card-title>
       
                    </v-card> 

                      <v-card class="mb-3">
                        <v-card-title class="bg-gray-500 text-white">
                           PART IV: CUSTOMER LIST
                        </v-card-title>
       
                    </v-card> 

                 
                  
                </div>
            </div>
        </div>
    </AppLayout>
</template>
