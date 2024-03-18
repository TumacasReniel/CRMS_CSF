<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import MonthlyContent from '@/Pages/CSI/Monthly/Content.vue';
import Q1Content from '@/Pages/CSI/Quarterly/Contents/Q1Content.vue';
import Q2Content from '@/Pages/CSI/Quarterly/Contents/Q2Content.vue';
import Q3Content from '@/Pages/CSI/Quarterly/Contents/Q3Content.vue';
import Q4Content from '@/Pages/CSI/Quarterly/Contents/Q4Content.vue';
import ByUnitMonthlyReport from '@/Pages/CSI/Monthly/ByUnitMonthly.vue';
import ByUnitQ1Report from '@/Pages/CSI/Quarterly/Printouts/ByUnitQuarter1.vue';
import ByUnitQ2Report from '@/Pages/CSI/Quarterly/Printouts/ByUnitQuarter2.vue';
import ByUnitQ3Report from '@/Pages/CSI/Quarterly/Printouts/ByUnitQuarter3.vue';
import ByUnitQ4Report from '@/Pages/CSI/Quarterly/Printouts/ByUnitQuarter4.vue';
import { reactive, ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { Printd } from "printd";
import Swal from 'sweetalert2'

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
    net_promoter_score: Number,

    //Percentage of Promoters
    percentage_promoters: Number,

    //Percentage of Detractors
    percentage_detractors: Number,

  // --- QUARTER 1 ----

    //First Quarter  quality attributes totals
    q1_vs_totals: Object,
    q1_s_totals: Object,
    q1_n_totals: Object,
    q1_d_totals: Object,
    q1_vd_totals: Object,
    q1_grand_totals: Object,

    // First Quarter raw totals
    vs_grand_total_raw_points: Number,
    s_grand_total_raw_points: Number,
    ndvd_grand_total_raw_points: Number,
    grand_total_raw_points: Number,
    trp_totals: Object,  
    grand_total_raw_points: Number,

    //Part 1 Q1 Total scores
    p1_total_scores: Object,
    vs_grand_total_score: Number,
    s_grand_total_score: Number,
    ndvd_grand_total_score: Number,
    grand_total_score: Number,

    // Likert Scale Rating Quarterly totals
    lsr_totals: Object,
    lsr_grand_total: Number,
    lsr_average: Number,

    // First Quarter very satified respondent totals
    jan_total_vs_respondents: Number, 
    feb_total_vs_respondents: Number, 
    mar_total_vs_respondents: Number, 

    // First Quarter satified respondent totals
    jan_total_s_respondents: Number, 
    feb_total_s_respondents: Number, 
    mar_total_s_respondents: Number, 

    // First Quarter neither, dissasfied, very dissatisfied respondent totals
    jan_total_ndvd_respondents: Number, 
    feb_total_ndvd_respondents: Number, 
    mar_total_ndvd_respondents: Number, 

     // First Quarter  respondent totals
    jan_total_respondents: Number, 
    feb_total_respondents: Number, 
    mar_total_respondents: Number, 


      //First Quarter importance quality attributes totals
    q1_vi_totals: Object,
    q1_i_totals: Object,
    q1_mi_totals: Object,
    q1_si_totals: Object,
    q1_nai_totals: Object,
    q1_i_grand_totals: Object,

    // Importance total raw points 
    vi_grand_total_raw_points: Number,
    i_grand_total_raw_points: Number,
    misinai_grand_total_raw_points: Number,
    i_grand_total_raw_points: Number,
    i_trp_totals: Object,  
    i_grand_total_raw_points: Number,

    //Imporatance total scores
    i_total_scores: Object,
    vi_grand_total_score: Number,
    i_grand_total_score: Number,
    misinai_grand_total_score: Number,

    // % promoters
    jan_percentage_promoters: Number,
    feb_percentage_promoters: Number,
    mar_percentage_promoters: Number,
    average_percentage_promoters: Number,

    // % detractors
    jan_percentage_detractors: Number,
    feb_percentage_detractors: Number,
    mar_percentage_detractors: Number,
    average_percentage_detractors: Number,

    // % net promoter score
    jan_net_promoter_score: Number,
    feb_net_promoter_score: Number,
    mar_net_promoter_score: Number,
    ave_net_promoter_score: Number,

    //customer_satisfaction_rating
    $customer_satisfaction_rating: Number,

     // --- QUARTER 2 ----

    //quality attributes totals
    q2_vs_totals: Object,
    q2_s_totals: Object,
    q2_n_totals: Object,
    q2_d_totals: Object,
    q2_vd_totals: Object,
    q2_grand_totals: Object,

    // First Quarter raw totals
    vs_grand_total_raw_points: Number,
    s_grand_total_raw_points: Number,
    ndvd_grand_total_raw_points: Number,
    grand_total_raw_points: Number,
    trp_totals: Object,  
    grand_total_raw_points: Number,

    //Part 1 Q1 Total scores
    p1_total_scores: Object,
    vs_grand_total_score: Number,
    s_grand_total_score: Number,
    ndvd_grand_total_score: Number,
    grand_total_score: Number,

    // Likert Scale Rating Quarterly totals
    lsr_totals: Object,
    lsr_grand_total: Number,
    lsr_average: Number,

    // very satified respondent totals
    apr_total_vs_respondents: Number, 
    may_total_vs_respondents: Number, 
    jun_total_vs_respondents: Number, 

    // satified respondent totals
    apr_total_s_respondents: Number, 
    may_total_s_respondents: Number, 
    jun_total_s_respondents: Number, 

    // neither, dissasfied, very dissatisfied respondent totals
    apr_total_ndvd_respondents: Number, 
    may_total_ndvd_respondents: Number, 
    jun_total_ndvd_respondents: Number, 

    // respondent totals
    apr_total_respondents: Number, 
    may_total_respondents: Number, 
    jun_total_respondents: Number, 


    //importance quality attributes totals
    q2_vi_totals: Object,
    q2_i_totals: Object,
    q2_mi_totals: Object,
    q2_si_totals: Object,
    q2_nai_totals: Object,
    q2_i_grand_totals: Object,

    // Importance total raw points 
    vi_grand_total_raw_points: Number,
    i_grand_total_raw_points: Number,
    misinai_grand_total_raw_points: Number,
    i_grand_total_raw_points: Number,
    i_trp_totals: Object,  
    i_grand_total_raw_points: Number,

    //Imporatance total scores
    i_total_scores: Object,
    vi_grand_total_score: Number,
    i_grand_total_score: Number,
    misinai_grand_total_score: Number,

    // % promoters
    apr_percentage_promoters: Number,
    may_percentage_promoters: Number,
    jun_percentage_promoters: Number,
    average_percentage_promoters: Number,

    // % detractors
    apr_percentage_detractors: Number,
    may_percentage_detractors: Number,
    jun_percentage_detractors: Number,
    average_percentage_detractors: Number,

    // % net promoter score
    apr_net_promoter_score: Number,
    may_net_promoter_score: Number,
    jun_net_promoter_score: Number,
    ave_net_promoter_score: Number,

    //customer_satisfaction_rating
    $customer_satisfaction_rating: Number,


     // --- QUARTER 3 ----

    //quality attributes totals
    q3_vs_totals: Object,
    q3_s_totals: Object,
    q3_n_totals: Object,
    q3_d_totals: Object,
    q3_vd_totals: Object,
    q3_grand_totals: Object,

    // First Quarter raw totals
    vs_grand_total_raw_points: Number,
    s_grand_total_raw_points: Number,
    ndvd_grand_total_raw_points: Number,
    grand_total_raw_points: Number,
    trp_totals: Object,  
    grand_total_raw_points: Number,

    //Part 1 Q1 Total scores
    p1_total_scores: Object,
    vs_grand_total_score: Number,
    s_grand_total_score: Number,
    ndvd_grand_total_score: Number,
    grand_total_score: Number,

    // Likert Scale Rating Quarterly totals
    lsr_totals: Object,
    lsr_grand_total: Number,
    lsr_average: Number,

    // very satified respondent totals
    jul_total_vs_respondents: Number, 
    aug_total_vs_respondents: Number, 
    sep_total_vs_respondents: Number, 

    // satified respondent totals
    jul_total_s_respondents: Number, 
    aug_total_s_respondents: Number, 
    sep_total_s_respondents: Number, 

    // neither, dissasfied, very dissatisfied respondent totals
    jul_total_ndvd_respondents: Number, 
    aug_total_ndvd_respondents: Number, 
    sep_total_ndvd_respondents: Number, 

    // respondent totals
    jul_total_respondents: Number, 
    aug_total_respondents: Number, 
    sep_total_respondents: Number, 


    //importance quality attributes totals
    q3_vi_totals: Object,
    q3_i_totals: Object,
    q3_mi_totals: Object,
    q3_si_totals: Object,
    q3_nai_totals: Object,
    q3_i_grand_totals: Object,

    // Importance total raw points 
    vi_grand_total_raw_points: Number,
    i_grand_total_raw_points: Number,
    misinai_grand_total_raw_points: Number,
    i_grand_total_raw_points: Number,
    i_trp_totals: Object,  
    i_grand_total_raw_points: Number,

    //Imporatance total scores
    i_total_scores: Object,
    vi_grand_total_score: Number,
    i_grand_total_score: Number,
    misinai_grand_total_score: Number,

    // % promoters
    jul_percentage_promoters: Number,
    aug_percentage_promoters: Number,
    sep_percentage_promoters: Number,
    average_percentage_promoters: Number,

    // % detractors
    jul_percentage_detractors: Number,
    aug_percentage_detractors: Number,
    sep_percentage_detractors: Number,
    average_percentage_detractors: Number,

    // % net promoter score
    jul_net_promoter_score: Number,
    aug_net_promoter_score: Number,
    sep_net_promoter_score: Number,
    sep_net_promoter_score: Number,

    //customer_satisfaction_rating
    $customer_satisfaction_rating: Number,

      // --- QUARTER 4 ----

    //quality attributes totals
    q4_vs_totals: Object,
    q4_s_totals: Object,
    q4_n_totals: Object,
    q4_d_totals: Object,
    q4_vd_totals: Object,
    q4_grand_totals: Object,

    // First Quarter raw totals
    vs_grand_total_raw_points: Number,
    s_grand_total_raw_points: Number,
    ndvd_grand_total_raw_points: Number,
    grand_total_raw_points: Number,
    trp_totals: Object,  
    grand_total_raw_points: Number,

    //Part 1 Q1 Total scores
    p1_total_scores: Object,
    vs_grand_total_score: Number,
    s_grand_total_score: Number,
    ndvd_grand_total_score: Number,
    grand_total_score: Number,

    // Likert Scale Rating Quarterly totals
    lsr_totals: Object,
    lsr_grand_total: Number,
    lsr_average: Number,

    // very satified respondent totals
    oct_total_vs_respondents: Number, 
    nov_total_vs_respondents: Number, 
    dec_total_vs_respondents: Number, 

    // satified respondent totals
    oct_total_s_respondents: Number, 
    nov_total_s_respondents: Number, 
    dec_total_s_respondents: Number, 

    // neither, dissasfied, very dissatisfied respondent totals
    oct_total_ndvd_respondents: Number, 
    nov_total_ndvd_respondents: Number, 
    dec_total_ndvd_respondents: Number, 

    // respondent totals
    oct_total_respondents: Number, 
    nov_total_respondents: Number, 
    dec_total_respondents: Number, 


    //importance quality attributes totals
    q4_vi_totals: Object,
    q4_i_totals: Object,
    q4_mi_totals: Object,
    q4_si_totals: Object,
    q4_nai_totals: Object,
    q4_i_grand_totals: Object,

    // Importance total raw points 
    vi_grand_total_raw_points: Number,
    i_grand_total_raw_points: Number,
    misinai_grand_total_raw_points: Number,
    i_grand_total_raw_points: Number,
    i_trp_totals: Object,  
    i_grand_total_raw_points: Number,

    //Imporatance total scores
    i_total_scores: Object,
    vi_grand_total_score: Number,
    i_grand_total_score: Number,
    misinai_grand_total_score: Number,

    // % promoters
    oct_percentage_promoters: Number,
    nov_percentage_promoters: Number,
    dec_percentage_promoters: Number,
    average_percentage_promoters: Number,

    // % detractors
    oct_percentage_detractors: Number,
    nov_percentage_detractors: Number,
    dec_percentage_detractors: Number,
    average_percentage_detractors: Number,

    // % net promoter score
    oct_net_promoter_score: Number,
    nov_net_promoter_score: Number,
    dec_net_promoter_score: Number,
    sep_net_promoter_score: Number,

    //customer_satisfaction_rating
    $customer_satisfaction_rating: Number,

});


const form = reactive({
  date_from: null,
  date_to: null,
  service: null,
  unit:  null,
  csi_type: null,
  selected_month: null,
  selected_quarter: null,
  comments_complaints: null,
  analysis: null,
  generated: false,
})

//get year
const years = computed(() => {
    const currentYear = new Date().getFullYear();
    const last9Years = Array.from({ length: 9 }, (_, index) => (currentYear - index).toString());
    return last9Years;
});

const months = [
    'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL',
    'MAY', 'JUNE', 'JULY', 'AUGUST',
    'SEPEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'
];

const currentYear = ref(getCurrentYear());

function getCurrentYear() {
    return new Date().getFullYear().toString();
}

//get month
const currentMonth = ref(getCurrentMonth());

function getCurrentMonth() {
    const currentDate = new Date();
    return months[currentDate.getMonth()];
}

onMounted(() => {
    form.selected_month = currentMonth.value;
    form.selected_year = currentYear.value;
    form.generated == false;
});


const generateCSIReport = async (service, unit) => {
   form.generated = true;
   form.service = service;
   form.unit = unit;
  //  console.log(form,990);
   if(form.csi_type == 'By Date'){
      form.selected_month = "";
   }
   else if(form.csi_type == 'By Month'){
        form.selected_quarter = "";
        form.selected_month = currentMonth.value;
        form.selected_year = currentYear.value;
        router.get('/csi/generate/ByUnit/Monthly', form , { preserveState: true, preserveScroll: true})
   }
   else if(form.csi_type == 'By Quarter'){
        form.selected_month = "";
        if(form.selected_quarter == 'FIRST QUARTER'){
          router.get('/csi/generate/ByUnit/FirstQuarter', form , { preserveState: true, preserveScroll: true})
        }
        else if(form.selected_quarter == 'SECOND QUARTER'){
          router.get('/csi/generate/ByUnit/SecondQuarter', form , { preserveState: true, preserveScroll: true})
        }
        else if(form.selected_quarter == 'THIRD QUARTER'){
          router.get('/csi/generate/ByUnit/ThirdQuarter', form , { preserveState: true, preserveScroll: true})
        }
        else if(form.selected_quarter == 'FOURTH QUARTER'){
          router.get('/csi/generate/ByUnit/FourthQuarter', form , { preserveState: true, preserveScroll: true})
        }   
        else{ 
           Swal.fire({
                title: "Error",
                icon: "error",
                text: "Please select a quarter first!"           
            });
        }
   }

  
};

const printCSIReport = async () => {
    //  router.get('/generate-pdf', form , { preserveState: true, preserveScroll: true})
    //Create an instance of Printd
      let d = await new Printd();
      let css = ` 
        @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;600;800&family=Roboto:wght@100;300;400;500;700;900&display=swap');
        * {
            font-family: 'Time New Roman'
        }
        .new-page {
            page-break-before: always;
        }
        .th-color{
            background-color: #8fd1e8;
        }
        .text-center{
          text-align: center;
        }
        .text-right{
          text-align:end
        }
        table {
          border-collapse: collapse;
          width: 100%; /* Optional: Set a width for the table */
        }

        tr, th, td {
          border: 1px solid rgb(145, 139, 139); /* Optional: Add a border for better visibility */
          padding: 3px; /* Optional: Add padding for better spacing */
        }
         .page-break {
          page-break-before: always; /* or page-break-after: always; */
        }

        `;

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
                    <v-card class="p-5 mb-3">
                   
                         <v-row class="p-3">
                             <v-col class="my-auto">
                                <v-combobox v-model="form.csi_type" class="m-3" label="Select Type" variant="outlined" 
                                :items="['By Date','By Month', 'By Quarter', 'By Year/Annual', 'By Employee']" border="none"> </v-combobox>
                            </v-col>
                        </v-row>
                    </v-card>
                   
                     <v-card class="mb-3 my-auto">
                        
                        <v-row class="p-3" v-if="form.csi_type == 'By Date'">
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
                             <v-btn  :disabled="form.generated == false" prepend-icon="mdi-printer" @click="printCSIReport()">Print</v-btn>
                           </v-col>
                        </v-row>

                         <v-row class="p-3" v-if="form.csi_type == 'By Month'">
                            <v-col class="my-auto">
                                  <v-combobox v-model="form.selected_month" 
                                        class="m-3" label="Select Month" 
                                        variant="outlined" 
                                        :items="months" 
                                        outlined="none"> 
                                  </v-combobox>
                            </v-col> 
                            <v-col class="my-auto">
                                <v-combobox v-model="form.selected_year" 
                                        class="m-3" label="Select Year" 
                                        variant="outlined" 
                                        :items="years" 
                                        outlined="none"> 
                                  </v-combobox>
                            </v-col>   

                            <v-col class="ml-5 mt-3">
                              <v-btn @click="generateCSIReport(service, unit)" >Generate</v-btn>
                            </v-col>
                           <v-col class="text-end mr-5 m-3">
                             <v-btn  :disabled="form.generated == false" prepend-icon="mdi-printer" @click="printCSIReport()">Print</v-btn>
                           </v-col>
                        </v-row>

                          <v-row class="p-3" v-if="form.csi_type == 'By Quarter'">
                            <v-col class="my-auto">
                                  <v-combobox v-model="form.selected_quarter" 
                                        class="m-3" label="Select Quarter" 
                                        variant="outlined" 
                                        :items="['FIRST QUARTER', 'SECOND QUARTER', 'THIRD QUARTER', 'FOURTH QUARTER']" 
                                        outlined="none"> 
                                  </v-combobox>
                            </v-col> 
                            <v-col class="my-auto">
                                <v-combobox v-model="form.selected_year" 
                                        class="m-3" label="Select Year" 
                                        variant="outlined" 
                                        :items="years" 
                                        outlined="none"> 
                                  </v-combobox>
                            </v-col>   

                            <v-col class="ml-5 mt-3">
                              <v-btn  @click="generateCSIReport(service, unit)" >Generate</v-btn>
                            </v-col>
                           <v-col class="text-end mr-5 m-3">
                             <v-btn :disabled="form.generated == false" prepend-icon="mdi-printer" @click="printCSIReport()">Print</v-btn>
                           </v-col>
                        </v-row>
                          <v-row class="p-3" v-if="form.csi_type == 'By Year/Annual'">
                            <v-col class="my-auto">
                                <v-combobox v-model="form.selected_year" 
                                        class="m-3" label="Select Year" 
                                        variant="outlined" 
                                        :items="years" 
                                        outlined="none"> 
                                  </v-combobox>
                            </v-col>   

                            <v-col class="ml-5 mt-3">
                              <v-btn @click="generateCSIReport(service, unit)" >Generate</v-btn>
                            </v-col>
                           <v-col class="text-end mr-5 m-3">
                             <v-btn  :disabled="form.generated == false" prepend-icon="mdi-printer" @click="printCSIReport()">Print</v-btn>
                           </v-col>
                        </v-row>

                          <v-row class="p-3" v-if="form.csi_type == 'By Employee'">
                            <v-col class="my-auto">
                                <v-combobox v-model="form.selected_employee" 
                                        class="m-3" label="Select Employee" 
                                        variant="outlined" 
                                        :items="['']" 
                                        outlined="none"> 
                                  </v-combobox>
                            </v-col>   

                            <v-col class="ml-5 mt-3">
                              <v-btn @click="generateCSIReport(service, unit)" >Generate</v-btn>
                            </v-col>
                           <v-col class="text-end mr-5 m-3">
                             <v-btn  :disabled="form.generated == false" prepend-icon="mdi-printer" @click="printCSIReport()">Print</v-btn>
                           </v-col>
                        </v-row>
                     </v-card>


                    <!-- Content Preview-->
                    <MonthlyContent v-if="form.csi_type == 'By Month'" :form="form"  :data="props" />
                    <Q1Content v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'FIRST QUARTER' && form.generated == true "  :form="form"  :data="props" />
                    <Q2Content v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'SECOND QUARTER'" :form="form"  :data="props" />
                    <Q3Content v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'THIRD QUARTER'"  :form="form"  :data="props" />
                    <Q4Content v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'FOURTH QUARTER'" :form="form"  :data="props" />
                      <!-- End Content Preview-->
                </div>
            </div>
        </div>

        <ByUnitMonthlyReport v-if="form.csi_type == 'By Month'" :form="form"  :data="props" />
        <ByUnitQ1Report v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'FIRST QUARTER' && form.generated == true " :form="form"  :data="props" />
        <ByUnitQ2Report v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'SECOND QUARTER' && form.generated == true " :form="form"  :data="props" />
        <ByUnitQ3Report v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'THIRD QUARTER' && form.generated == true " :form="form"  :data="props" />
        <ByUnitQ4Report v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'FOURTH QUARTER' && form.generated == true " :form="form"  :data="props" />
      
    </AppLayout>
</template>


