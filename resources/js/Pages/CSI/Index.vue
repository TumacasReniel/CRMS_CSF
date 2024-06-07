<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import MonthlyContent from '@/Pages/CSI/Monthly/Content.vue';
import Q1Content from '@/Pages/CSI/Quarterly/Contents/Q1Content.vue';
import Q2Content from '@/Pages/CSI/Quarterly/Contents/Q2Content.vue';
import Q3Content from '@/Pages/CSI/Quarterly/Contents/Q3Content.vue';
import Q4Content from '@/Pages/CSI/Quarterly/Contents/Q4Content.vue';
import YearlyContent from '@/Pages/CSI/Yearly/Content.vue';
import ByUnitQ1Report from '@/Pages/CSI/Quarterly/Printouts/ByUnitQuarter1.vue';
import ByUnitQ2Report from '@/Pages/CSI/Quarterly/Printouts/ByUnitQuarter2.vue';
import ByUnitQ3Report from '@/Pages/CSI/Quarterly/Printouts/ByUnitQuarter3.vue';
import ByUnitQ4Report from '@/Pages/CSI/Quarterly/Printouts/ByUnitQuarter4.vue';
import ByUnitYearlyReport from '@/Pages/CSI/Yearly/ByUnitYearly.vue';
import ModalForm from '@/Pages/CSI/Modal.vue';
import VueMultiselect from "vue-multiselect";
import { reactive, ref, computed, onMounted, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2';
import { Printd } from "printd";


const props = defineProps({
    service: Object, 
    unit: Object,
    sub_unit: Object,
    unit_pstos: Object,
    sub_unit_pstos: Object,
    sub_unit_types: Object,

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

    //comment and complaints
    comments: Object,
    total_comments: Number,
    total_complaints: Number,

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
    customer_satisfaction_rating: Number,

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
    customer_satisfaction_rating: Number,


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
    customer_satisfaction_rating: Number,

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
    customer_satisfaction_rating: Number,

    // --- Yearly ---


  // all quarter totals
    vs_totals: Object,
    s_totals: Object,
    n_totals: Object,
    d_totals: Object,
    vd_totals: Object,

    grand_totals: Object,
    trp_totals: Object,
    grand_total_raw_points: Object,
    vs_grand_total_raw_points: Object, 
    s_grand_total_raw_points: Object,
    ndvd_grand_total_raw_points: Object,
    p1_total_scores: Object,
    vs_grand_total_score: Number,
    s_grand_total_score: Number,
    ndvd_grand_total_score: Number,
    grand_total_score: Number,
    lsr_totals: Object,
    lsr_grand_total: Number,
    lsr_average: Number,

    //all quarter total respondents/customer who rated Very satisfied in attributes rating
    q1_total_vs_respondents: Number,
    q2_total_vs_respondents: Number, 
    q3_total_vs_respondents: Number,
    q4_total_vs_respondents: Number,

     //by quarter total respondents/customer who rated satisfied in attributes rating
    q1_total_s_respondents: Number,
    q2_total_s_respondents: Number,
    q3_total_s_respondents: Number,
    q4_total_s_respondents: Number,

     //by quarter total respondents/customer who rated Neither, Dissatisfied and  very Dissatisfied in attributes rating
    q1_total_ndvd_respondents: Number,
    q2_total_ndvd_respondents: Number,
    q3_total_ndvd_respondents: Number,
    q4_total_ndvd_respondents: Number,

    // By quarter total respondents
    q1_total_respondents: Number,
    q2_total_respondents: Number,
    q3_total_respondents: Number,
    q4_total_respondents: Number,
    
    // grandtotal of total respondents 
    total_respondents: Number,

    // by quarter respondents who rated VS/S
    q1_total_vss_respondents: Number,
    q2_total_vss_respondents: Number,
    q3_total_vss_respondents: Number,
    q4_total_vss_respondents: Number,

    //grand total of respondents who rated VS/S
    total_vss_respondents: Number,
    //percentage of respondents who rated VS/S
    percentage_vss_respondents: Number,

    // total number of promoters and detractors
    total_promoters: Number,
    total_detractors: Number,

    // all quarters importance attributes rating totals
    vi_totals: Object,
    i_totals: Object,
    mi_totals: Object,
    si_totals: Object,
    nai_totals: Object,

    // imporatnce grand total
    i_grand_totals: Object,

    // importance attributes total raw score
    i_trp_totals: Object,

    // importance raw points totals
    i_grand_total_raw_point: Number,
    vi_grand_total_raw_points: Number,
    s_grand_total_raw_points: Number,
    misinai_grand_total_raw_points: Number,
    i_total_scores: Number,

    vi_grand_total_score: Number,
    i_grand_total_score: Number,
    misinai_grand_total_score: Number,

    // total promoters by quarter and percentage,  average
    percentage_promoters: Number,
    q1_percentage_promoters: Number,
    q2_percentage_promoters: Number,
    q3_percentage_promoters: Number,
    q4_percentage_promoters: Number,
    average_percentage_promoters: Number,

    // total detractors by quarter and percentage
    q1_percentage_detractors: Number,
    q2_percentage_detractors: Number,
    q3_percentage_detractors: Number,
    q4_percentage_detractors: Number,
    average_percentage_detractors: Number,

    // total nps by quarter and average
    q1_net_promoter_score: Number,
    q2_net_promoter_score: Number,
    q3_net_promoter_score: Number,
    q4_net_promoter_score: Number,
    ave_net_promoter_score: Number,

    // CSAT
    customer_satisfaction_rating: Number,

    // CSI by quarter
    csi: Number,
    first_month_csi: Number, 
    second_month_csi: Number, 
    third_month_csi: Number, 

    //CSI By year
    q1_csi: Number,
    q2_csi: Number,
    q3_csi: Number,
    q4_csi: Number,

    //get current user
    user: Object,

    //get assignatorees
    assignatorees:Object,
    

});


const form = reactive({
  service: null,
  unit:  null,
  unit_id: null,

  selected_sub_unit: [],
  selected_unit_psto: [],
  selected_sub_unit_psto: [],

  // form type if all or per unit
  form_type: null,

  //by date
  date_from: null,
  date_to: null,

  // by date , monthly ,querterly or yearly
  csi_type: null,

  // by month and year
  selected_month: null,
  selected_year: null,

  //by quarter
  selected_quarter: null,

  // for HR case only
  client_type: null,
});


const view_form = reactive({
  generated_url: null,
});


const generated = ref(false);

//get year
const years = computed(() => {
    const currentYear = new Date().getFullYear();
    const last9Years = Array.from({ length: 9 }, (_, index) => (currentYear - index).toString());
    return last9Years;
});

const months = [
    'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL',
    'MAY', 'JUNE', 'JULY', 'AUGUST',
    'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'
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
      generated.value == false;
  });


  const generateCSIReport = async (service, unit) => {
   generated.value = true;
   form.service = service;
   form.unit = unit;
   form.unit_id = unit.data[0].id;
  //  console.log(form,990);
    if(form.csi_type == 'By Date'){
      if(form.date_from && form.date_to){
            router.post('/csi/generate', form , { preserveState: true, preserveScroll: true})
      }
      else{ 
        Swal.fire({
              title: "Error",
              icon: "error",
              text: "Please fill up Date From and Date To field."           
          });
      }
    }
    else if(form.csi_type == 'By Month'){
          form.selected_quarter = "";
          router.post('/csi/generate', form , { preserveState: true, preserveScroll: true})
    }
    else if(form.csi_type == 'By Quarter'){
          form.selected_month = "";
          if(form.selected_quarter){
              router.post('/csi/generate', form , { preserveState: true, preserveScroll: true})
          }
          else{ 
            Swal.fire({
                  title: "Error",
                  icon: "error",
                  text: "Please select a quarter first!"           
              });
          }
    }
      else if(form.csi_type == 'By Year/Annual'){
          form.selected_quarter = "";
          if(form.selected_year ){
             router.post('/csi/generate', form , { preserveState: true, preserveScroll: true})
          }
          else{         
              Swal.fire({
                  title: "Error",
                  icon: "error",
                  text: "Please select year first!"           
              });
          }     
      }

    
  };

  function refresh() {
      window.history.back()
  }





  watch(
    () => form.selected_sub_unit,
    (value) => {
        generated.value = false;;
        getSubUnitPSTO(value.id);
    }
);

  watch(
    () => form.csi_type,
    (value) => {
          if(value == ''){
            form.selected_sub_unit = null;
          }
    }
);



  const getSubUnitPSTO = async (sub_unit_id) => {
    // Get the current query parameters
    const currentQueryParams = new URLSearchParams(window.location.search);

    // Add or update the 'sub_unit_id' parameter
    currentQueryParams.set('sub_unit_id', sub_unit_id);

    // Construct the new URL with updated query parameters
    const newUrl = `/csi?${currentQueryParams.toString()}`;

    // Navigate to the new URL
    await router.visit(
      newUrl,
      {
        //preserveQuery: true, 
        preserveState: true,
        preserveScroll: true,
      }
  );


};

  const is_printing = ref(false);
  const printCSIReport = async () => {
      is_printing.value = true;
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
            padding: 1px; /* Optional: Add padding for better spacing */
          }
          .page-break {
            page-break-before: always; /* or page-break-after: always; */
          }

        `;

       d.print(document.querySelector(".print-id"), [css]);

};

 const show_modal = ref(false);
//For Modal Print Preview
 const showPrintPreviewModal = async(is_show) => {
      show_modal.value = is_show;
  };
  
</script>

<template>
    <AppLayout title="Dashboard" class="overflow-visible">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Customer Satisfaction Index 

            </h2>
        </template>

        
        <div class="py-10 overflow-visible"  style="margin-left:80px; margin-right:80px" >
            <div class="max-w-7x1 mx-auto sm:px-6 lg:px-8  overflow-visibl">
                <div class="bg-white shadow-xl sm:rounded-lg overflow-visible">
                   <v-card class="mb-5 overflow-visible" v-if="service && unit">
                      <v-card-title class="m-3" >
                              <div v-if="service">
                                  SERVICES :   {{ service.services_name }}
                              </div>
                              <v-divider class="border-opacity-100"></v-divider>
                              <div v-if="unit">
                                  SERVICE UNIT :    {{ unit.data[0].unit_name }}
                              </div>
                          </v-card-title>

                    </v-card>

                    <v-card class="overflow-visible mb-5" >   
                          <v-divider class="border-opacity-100"></v-divider>
                          <v-row class="p-3 overflow-visible" >
                              <v-col class="my-auto overflow-visible">
                                <div class="my-auto overflow-visible"> 
                                   <vue-multiselect
                                      v-model="form.csi_type"
                                      prepend-icon="mdi-account"
                                      :options="['By Date','By Month', 'By Quarter', 'By Year/Annual']"
                                      :multiple="false"
                                      placeholder="Select Type*"
                                      :allow-empty="false"
                                    >         
                                    </vue-multiselect>        

                                  </div>
                              </v-col>
                              
                              <v-col class="my-auto"  v-if="unit.data[0].id == 8" >
                                   <vue-multiselect
                                      v-model="form.client_type"
                                      prepend-icon="mdi-account"
                                      :options="['Internal', 'External']"
                                      :multiple="false"
                                      placeholder="Select Client Type"
                                      :allow-empty="false"
                                    >         
                                    </vue-multiselect>       
                              </v-col>


                              <v-col class="my-auto ml-5" v-if="unit.data[0].sub_units.length > 0" >
                                    <vue-multiselect
                                      v-model="form.selected_sub_unit"
                                      prepend-icon="mdi-account"
                                      :options="unit.data[0].sub_units"
                                      :multiple="false"
                                      placeholder="Select Sub Unit*"
                                      label="sub_unit_name"
                                      track-by="sub_unit_name"
                                      :allow-empty="false"
                                      :disabled="generated"
                                    >         
                                    </vue-multiselect>           
                              </v-col>

                                <v-col class="my-auto mr-5 ml-5" v-if="unit_pstos.length > 0" >
                                    <vue-multiselect
                                      v-model="form.selected_unit_psto"
                                      :options="unit_pstos"
                                      :multiple="false"
                                      placeholder="Select Unit PSTO"
                                      label="psto_name"
                                      track-by="psto_name"
                                      :allow-empty="false"
                                    >
                                    </vue-multiselect>          
                              </v-col>

                                <v-col class="my-auto mr-5" v-if="sub_unit_pstos.length > 0" >
                                    <vue-multiselect
                                      v-model="form.selected_sub_unit_psto"
                                      :options="sub_unit_pstos"
                                      :multiple="false"
                                      placeholder="Select Sub Unit PSTO"
                                      label="psto_name"
                                      track-by="psto_name"
                                      :allow-empty="false"
                                    >
                                    </vue-multiselect>          
                              </v-col>      

                                <v-col class="my-auto" v-if="sub_unit_types.length > 0 && form.selected_sub_unit" >
                                    <vue-multiselect
                                      v-model="form.sub_unit_type"
                                      :options="sub_unit_types"
                                      :multiple="false"
                                      placeholder="Select Driving Type"
                                      label="type_name"
                                      track-by="type_name"
                                      :allow-empty="false"
                                    >
                                    </vue-multiselect>          
                              </v-col>
                                
                          </v-row>
    
                          <v-divider class="border-opacity-100"></v-divider>

                          <v-card-body class="overflow-visible mb-5" >
                            
                              <v-row class="p-3" v-if="form.csi_type == 'By Date'" >
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
                                    <v-btn @click="refresh()" icon="mdi-refresh" v-if="generated" variant="text"></v-btn>
                                  </v-col>

                              </v-row>

                              <v-row class="p-3" v-if="form.csi_type == 'By Month'">
                                  <v-col class="my-auto">
                                        <v-select v-model="form.selected_month" 
                                              class="m-3" label="Select Month" 
                                              variant="outlined" 
                                              :items="months" 
                                              outlined="none"> 
                                        </v-select>
                                  </v-col> 
                                  <v-col class="my-auto">
                                      <v-select v-model="form.selected_year" 
                                              class="m-3" label="Select Year" 
                                              variant="outlined" 
                                              :items="years" 
                                              outlined="none"> 
                                        </v-select>
                                  </v-col>   

                                  <v-col class="ml-5 mt-3">
                                    <v-btn @click="generateCSIReport(service, unit)" >Generate</v-btn>
                                    <v-btn @click="refresh()" icon="mdi-refresh" v-if="generated" variant="text"></v-btn>
                                  </v-col>
                                <v-col class="text-end mr-5 m-3">
                                  <v-btn  :disabled="generated == false" prepend-icon="mdi-printer" @click="showPrintPreviewModal(true)">Print</v-btn>
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
                                    <v-btn @click="refresh()" icon="mdi-refresh" v-if="generated" variant="text"></v-btn>
                                  </v-col>
                                <v-col class="text-end mr-5 m-3">
                                  <v-btn :disabled="generated == false" prepend-icon="mdi-printer" @click="printCSIReport()">Print</v-btn>
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
                                    <v-btn @click="refresh()" icon="mdi-refresh" v-if="generated" variant="text"></v-btn>
                                  </v-col>
                                <v-col class="text-end mr-5 m-3">
                                  <v-btn  :disabled="generated == false" prepend-icon="mdi-printer" @click="printCSIReport()">Print</v-btn>
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
                                      <v-btn @click="refresh()" icon="mdi-refresh" v-if="generated" variant="text"></v-btn>
                                  </v-col>
                                <v-col class="text-end mr-5 m-3">
                                  <v-btn  :disabled="generated == false" prepend-icon="mdi-printer" @click="printCSIReport()">Print</v-btn>
                                </v-col>
                              </v-row>
                              </v-card-body>
                    </v-card>

                  <!-- Content Preview-->
                  <MonthlyContent v-if="form.csi_type == 'By Month' && generated == true  || form.csi_type == 'By Date' && generated == true" :form="form"  :data="props" />
                  <Q1Content v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'FIRST QUARTER' && generated == true "  :form="form"  :data="props" />
                  <Q2Content v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'SECOND QUARTER' && generated == true" :form="form"  :data="props" />
                  <Q3Content v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'THIRD QUARTER' && generated == true"  :form="form"  :data="props" />
                  <Q4Content v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'FOURTH QUARTER' && generated == true" :form="form"  :data="props" />
                  <YearlyContent v-if="form.csi_type == 'By Year/Annual' && generated == true"  :form="form"  :data="props"  />
                  
                    <!-- End Content Preview-->


                  <!-- QUATER AND YEARLY PRINTOUTS Preview-->
                  <ByUnitQ1Report v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'FIRST QUARTER'" :form="form"  :data="props" />
                  <ByUnitQ2Report v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'SECOND QUARTER'"  :form="form"  :data="props" />
                  <ByUnitQ3Report v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'THIRD QUARTER'"  :form="form"  :data="props" />
                  <ByUnitQ4Report v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'FOURTH QUARTER'"  :form="form"  :data="props" />
                  <ByUnitYearlyReport v-if="form.csi_type == 'By Year/Annual'"  :form="form"  :data="props"/>
                 
                  <!-- Modal for Print Preview -->
                  <ModalForm 
                      v-if="generated" 
                      :value="show_modal"
                      :form="form"  
                      :assignatorees="assignatorees"
                      :user="user"
                      @input="showPrintPreviewModal"  
                      :data="props"
                     />
                  
                 
                </div>
            </div>
        </div>

    </AppLayout>

   
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>



