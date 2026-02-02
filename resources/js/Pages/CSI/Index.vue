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
    users: Object,

    //Monthly
    y_totals: Object,
    grand_vs_total: Number,
    grand_s_total: Number,
    grand_n_total: Number,
    grand_d_total: Number,
    grand_vd_total: Number,
    x_totals: Object,
    x_grand_total: Object,
    likert_scale_rating_totals: Object,
    lsr_grand_total: Number,
    importance_rate_score_totals: Object,
    x_importance_totals: Object,
    importance_ilsr_totals: Object,
    gap_totals: Object,
    gap_grand_total: Number,
    wf_totals: Object,
    ss_totals: Object,
    ws_totals: Object,
    total_respondents: Number,
    total_vss_respondents: Number,
    percentage_vss_respondents: Number,
    customer_satisfaction_rating: Number,
    customer_satisfaction_index: Number,
    net_promoter_score: Number,
    percentage_promoters: Number,
    percentage_detractors: Number,
    total_comments: Number,
    total_complaints: Number,
    comments: Object,

    // Quarterly
    cc_data: Object,
    user: Object,
    assignatorees: Object,
    dimensions: Object,
    respondents_list: Object,
    trp_totals: Number,
    grand_total_raw_points: Number,
    vs_grand_total_raw_points: Number,
    s_grand_total_raw_points: Number,
    ndvd_grand_total_raw_points: Object,
    p1_total_scores: Object,
    vs_grand_total_score: Object,
    s_grand_total_score: Object,
    ndvd_grand_total_score: Object,
    grand_total_score: Number,
    lsr_totals: Object,
    lsr_grand_total: Number,
    lsr_average: Number,
    vs_totals: Object,
    s_totals: Object,
    n_totals: Object,
    d_totals: Object,
    vd_totals: Object,
    grand_totals: Object,
    first_month_total_vs_respondents: Object,
    second_month_total_vs_respondents: Object,
    third_month_total_vs_respondents: Object,
    first_month_total_s_respondents: Object,
    second_month_total_s_respondents: Object,
    third_month_total_s_respondents: Object,
    first_month_total_ndvd_respondents: Object,
    second_month_total_ndvd_respondents: Object,
    third_month_total_ndvd_respondents: Object,
    first_month_total_respondents: Object,
    second_month_total_respondents: Object,
    third_month_total_respondents: Object,
    total_promoters: Number,
    total_detractors: Number,
    vi_totals: Object,
    i_totals: Object,
    mi_totals: Object,
    si_totals: Object,
    nai_totals: Object,
    i_grand_totals: Object,
    i_trp_totals: Object,
    i_grand_total_raw_points: Object,
    vi_grand_total_raw_points: Object,
    misinai_grand_total_raw_points: Object,
    i_total_scores: Object,
    vi_grand_total_score: Number,
    i_grand_total_score: Number,
    misinai_grand_total_score: Number,
    first_month_percentage_promoters: Number,
    second_month_percentage_promoters: Number,
    third_month_percentage_promoters: Number,
    average_percentage_promoters: Number,
    first_month_percentage_detractors: Number,
    second_month_percentage_detractors: Number,
    third_month_percentage_detractors: Number,
    average_percentage_detractors: Number,
    first_month_net_promoter_score: Number,
    second_month_net_promoter_score: Number,
    third_month_net_promoter_score: Number,
    ave_net_promoter_score: Number,
    csi: Number,
    first_month_csi: Number,
    second_month_csi: Number,
    third_month_csi: Number,
    first_month_vs_grand_total: Number,
    second_month_vs_grand_total: Number,
    third_month_vs_grand_total: Number,
    first_month_s_grand_total: Number,
    second_month_s_grand_total: Number,
    third_month_s_grand_total: Number,
    first_month_ndvd_grand_total: Number,
    second_month_ndvd_grand_total: Number,
    third_month_ndvd_grand_total: Number,
    first_month_grand_total: Number,
    second_month_grand_total: Number,
    third_month_grand_total: Number,

    // Yearly
    q1_percentage_promoters: Number,
    q2_percentage_promoters: Number,
    q3_percentage_promoters: Number,
    q4_percentage_promoters: Number,
    q1_percentage_detractors: Number,
    q2_percentage_detractors: Number,
    q3_percentage_detractors: Number,
    q4_percentage_detractors: Number,
    q1_net_promoter_score: Number,
    q2_net_promoter_score: Number,
    q3_net_promoter_score: Number,
    q4_net_promoter_score: Number,
    q1_csi: Number,
    q2_csi: Number,
    q3_csi: Number,
    q4_csi: Number,
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

  // other properties
  sex:null , 
  age_group:null,

});


const r_form = reactive({
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
   
    if(form.csi_type == 'By Date'){
      if(form.date_from && form.date_to){
            router.get('/csi/generate', form , { preserveState: true, preserveScroll: true})
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
          router.get('/csi/generate', form , { preserveState: true, preserveScroll: true})
    }
    else if(form.csi_type == 'By Quarter'){
          form.selected_month = "";
          if(form.selected_quarter){
              router.get('/csi/generate', form , { preserveState: true, preserveScroll: true})
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
             router.get('/csi/generate', form , { preserveState: true, preserveScroll: true})
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

const sex_options = [
  'Male', 
  'Female' , 
  'Prefer not to say'
];


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
          .text-left{
            text-align: left;
          }
          .text-center{
            text-align: center;
          }
          .bg-blue{
            background: blue;
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
    <AppLayout title="Customer Satisfaction Index" class="overflow-visible">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <i class="ri-bar-chart-line text-primary me-2"></i>
                    Customer Satisfaction Index
                </h2>
                <div class="text-sm text-gray-600">
                    <i class="ri-information-line me-1"></i>
                    Generate and view CSI reports
                </div>
            </div>
        </template>


        <div class="py-8 overflow-visible" style="margin-left:60px; margin-right:60px">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-visible">
                <div class="bg-white shadow-xl sm:rounded-lg overflow-visible">
                   <div class="card mb-4 overflow-visible border-0 shadow-sm" v-if="service && unit">
                      <div class="card-header bg-gradient-primary text-white position-relative" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                          <div class="d-flex align-items-center">
                              <div class="me-4">
                                  <i class="ri-building-4-line fs-2"></i>
                              </div>
                              <div>
                                  <h5 class="mb-1 fw-bold">{{ service.services_name }}</h5>
                                  <h6 class="mb-0 opacity-75">{{ unit.data[0].unit_name }}</h6>
                              </div>
                          </div>
                          <div class="position-absolute top-0 end-0 p-3 opacity-25">
                              <i class="ri-star-line fs-1"></i>
                          </div>
                      </div>
                    </div>

                    <div class="card overflow-visible mb-5 border-0 shadow-lg">
                          <div class="card-header bg-light border-bottom-0">
                              <h5 class="mb-0 text-primary">
                                  <i class="ri-settings-5-line me-2"></i>
                                  Report Configuration
                              </h5>
                          </div>
                          <div class="card-body">
                              <div class="d-flex flex-wrap gap-3 align-items-end">
                                  <div class="flex-shrink-0">
                                      <label class="form-label fw-semibold">
                                          <i class="ri-calendar-line me-1 text-muted"></i>
                                          Report Type
                                      </label>
                                      <vue-multiselect
                                          v-model="form.csi_type"
                                          :options="['By Date','By Month', 'By Quarter', 'By Year/Annual']"
                                          :multiple="false"
                                          placeholder="Select Type*"
                                          :allow-empty="false"
                                          class="form-control p-0 border-0"
                                          style="min-width: 180px;"
                                      >
                                      </vue-multiselect>
                                  </div>

                                  <div class="flex-shrink-0" v-if="unit.data[0].id == 8 || user.account_type == 'planning'">
                                      <label class="form-label fw-semibold">
                                          <i class="ri-user-line me-1 text-muted"></i>
                                          Client Type
                                      </label>
                                      <vue-multiselect
                                          v-model="form.client_type"
                                          :options="['Internal', 'External']"
                                          :multiple="false"
                                          placeholder="Select Client Type"
                                          :allow-empty="true"
                                          class="form-control p-0 border-0"
                                          style="min-width: 150px;"
                                      >
                                      </vue-multiselect>
                                  </div>

                                  <div class="flex-shrink-0" v-if="unit.data[0].sub_units && unit.data[0].sub_units.length > 0">
                                      <label class="form-label fw-semibold">
                                          <i class="ri-building-line me-1 text-muted"></i>
                                          Sub Unit
                                      </label>
                                      <vue-multiselect
                                          v-model="form.selected_sub_unit"
                                          :options="unit.data[0].sub_units || []"
                                          :multiple="false"
                                          placeholder="Select Sub Unit*"
                                          label="sub_unit_name"
                                          track-by="sub_unit_name"
                                          :allow-empty="false"
                                          :disabled="generated"
                                          class="form-control p-0 border-0"
                                          style="min-width: 150px;"
                                      >
                                      </vue-multiselect>
                                  </div>

                                  <div class="flex-shrink-0" v-if="unit_pstos.length > 0">
                                      <label class="form-label fw-semibold">
                                          <i class="ri-map-pin-line me-1 text-muted"></i>
                                          Unit PSTO
                                      </label>
                                      <vue-multiselect
                                          v-model="form.selected_unit_psto"
                                          :options="unit_pstos"
                                          :multiple="false"
                                          placeholder="Select Unit PSTO"
                                          label="psto_name"
                                          track-by="psto_name"
                                          :allow-empty="false"
                                          class="form-control p-0 border-0"
                                          style="min-width: 150px;"
                                      >
                                      </vue-multiselect>
                                  </div>

                                  <div class="flex-shrink-0" v-if="sub_unit_pstos.length > 0">
                                      <label class="form-label fw-semibold">
                                          <i class="ri-map-pin-2-line me-1 text-muted"></i>
                                          Sub Unit PSTO
                                      </label>
                                      <vue-multiselect
                                          v-model="form.selected_sub_unit_psto"
                                          :options="sub_unit_pstos"
                                          :multiple="false"
                                          placeholder="Select Sub Unit PSTO"
                                          label="psto_name"
                                          track-by="psto_name"
                                          :allow-empty="false"
                                          class="form-control p-0 border-0"
                                          style="min-width: 150px;"
                                      >
                                      </vue-multiselect>
                                  </div>

                                  <div class="flex-shrink-0" v-if="sub_unit_types.length > 0 && form.selected_sub_unit">
                                      <label class="form-label fw-semibold">
                                          <i class="ri-car-line me-1 text-muted"></i>
                                          Driving Type
                                      </label>
                                      <vue-multiselect
                                          v-model="form.sub_unit_type"
                                          :options="sub_unit_types"
                                          :multiple="false"
                                          placeholder="Select Driving Type"
                                          label="type_name"
                                          track-by="type_name"
                                          :allow-empty="false"
                                          class="form-control p-0 border-0"
                                          style="min-width: 150px;"
                                      >
                                      </vue-multiselect>
                                  </div>
                              </div>
                          </div>
    
                          <hr class="border-opacity-100">

                          <div class="row p-3 overflow-visible" v-if="user.account_type == 'planning'">
                            <div class="col-md-6 my-auto">
                                <vue-multiselect
                                    v-model="form.sex"
                                    prepend-icon="mdi-account"
                                    :options="['Male','Female', 'Prefer not to say']"
                                    :multiple="false"
                                    placeholder="Select Sex"
                                    :allow-empty="true"
                                  >
                                </vue-multiselect>
                            </div>
                            <div class="col-md-6 my-auto">
                                <vue-multiselect
                                    v-model="form.age_group"
                                    prepend-icon="mdi-account"
                                    :options="['19 or lower','20-34','35-49','50-64','60+', 'Prefer not to say']"
                                    :multiple="false"
                                    placeholder="Select Age Group"
                                    :allow-empty="true"
                                  >
                                </vue-multiselect>
                              </div>
                          </div>
                          <hr class="border-opacity-100">
                          

                          <div class="card-footer bg-light border-top-0">
                              <div class="row g-3 align-items-end" v-if="form.csi_type == 'By Date'">
                                  <div class="col-md-3">
                                      <label class="form-label fw-semibold">
                                          <i class="ri-calendar-2-line me-1 text-muted"></i>
                                          From Date
                                      </label>
                                      <input
                                          class="form-control"
                                          type="date"
                                          v-model="form.date_from"
                                      />
                                  </div>
                                  <div class="col-md-3">
                                      <label class="form-label fw-semibold">
                                          <i class="ri-calendar-2-line me-1 text-muted"></i>
                                          To Date
                                      </label>
                                      <input
                                          class="form-control"
                                          type="date"
                                          v-model="form.date_to"
                                      />
                                  </div>
                                  <div class="col-md-6">
                                      <div class="d-flex gap-2 justify-content-end">
                                          <button @click="generateCSIReport(service, unit)" class="btn btn-primary">
                                              <i class="ri-play-circle-line me-1"></i>Generate Report
                                          </button>
                                          <button @click="refresh()" v-if="generated" class="btn btn-outline-secondary">
                                              <i class="ri-refresh-line me-1"></i>Refresh
                                          </button>
                                          <button :disabled="generated == false" @click="showPrintPreviewModal(true)" class="btn btn-success">
                                              <i class="ri-printer-line me-1"></i>Print Preview
                                          </button>
                                      </div>
                                  </div>
                              </div>

                              <div class="row g-3 align-items-end" v-if="form.csi_type == 'By Month'">
                                  <div class="col-md-3">
                                      <label class="form-label fw-semibold">
                                          <i class="ri-calendar-line me-1 text-muted"></i>
                                          Month
                                      </label>
                                      <select v-model="form.selected_month" class="form-select">
                                          <option disabled value="">Select Month</option>
                                          <option v-for="month in months" :key="month" :value="month">{{ month }}</option>
                                      </select>
                                  </div>
                                  <div class="col-md-3">
                                      <label class="form-label fw-semibold">
                                          <i class="ri-calendar-check-line me-1 text-muted"></i>
                                          Year
                                      </label>
                                      <select v-model="form.selected_year" class="form-select">
                                          <option disabled value="">Select Year</option>
                                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                      </select>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="d-flex gap-2 justify-content-end">
                                          <button @click="generateCSIReport(service, unit)" class="btn btn-primary">
                                              <i class="ri-play-circle-line me-1"></i>Generate Report
                                          </button>
                                          <button @click="refresh()" v-if="generated" class="btn btn-outline-secondary">
                                              <i class="ri-refresh-line me-1"></i>Refresh
                                          </button>
                                          <button :disabled="generated == false" @click="showPrintPreviewModal(true)" class="btn btn-success">
                                              <i class="ri-printer-line me-1"></i>Print Preview
                                          </button>
                                      </div>
                                  </div>
                              </div>

                              <div class="row g-3 align-items-end" v-if="form.csi_type == 'By Quarter'">
                                  <div class="col-md-3">
                                      <label class="form-label fw-semibold">
                                          <i class="ri-calendar-todo-line me-1 text-muted"></i>
                                          Quarter
                                      </label>
                                      <select v-model="form.selected_quarter" class="form-select">
                                          <option disabled value="">Select Quarter</option>
                                          <option value="FIRST QUARTER">Q1 - First Quarter</option>
                                          <option value="SECOND QUARTER">Q2 - Second Quarter</option>
                                          <option value="THIRD QUARTER">Q3 - Third Quarter</option>
                                          <option value="FOURTH QUARTER">Q4 - Fourth Quarter</option>
                                      </select>
                                  </div>
                                  <div class="col-md-3">
                                      <label class="form-label fw-semibold">
                                          <i class="ri-calendar-check-line me-1 text-muted"></i>
                                          Year
                                      </label>
                                      <select v-model="form.selected_year" class="form-select">
                                          <option disabled value="">Select Year</option>
                                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                      </select>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="d-flex gap-2 justify-content-end">
                                          <button @click="generateCSIReport(service, unit)" class="btn btn-primary">
                                              <i class="ri-play-circle-line me-1"></i>Generate Report
                                          </button>
                                          <button @click="refresh()" v-if="generated" class="btn btn-outline-secondary">
                                              <i class="ri-refresh-line me-1"></i>Refresh
                                          </button>
                                          <button :disabled="generated == false" @click="printCSIReport()" class="btn btn-success">
                                              <i class="ri-printer-line me-1"></i>Print Report
                                          </button>
                                      </div>
                                  </div>
                              </div>

                              <div class="row g-3 align-items-end" v-if="form.csi_type == 'By Year/Annual'">
                                  <div class="col-md-3">
                                      <label class="form-label fw-semibold">
                                          <i class="ri-calendar-check-line me-1 text-muted"></i>
                                          Year
                                      </label>
                                      <select v-model="form.selected_year" class="form-select">
                                          <option disabled value="">Select Year</option>
                                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                      </select>
                                  </div>
                                  <div class="col-md-9">
                                      <div class="d-flex gap-2 justify-content-end">
                                          <button @click="generateCSIReport(service, unit)" class="btn btn-primary">
                                              <i class="ri-play-circle-line me-1"></i>Generate Report
                                          </button>
                                          <button @click="refresh()" v-if="generated" class="btn btn-outline-secondary">
                                              <i class="ri-refresh-line me-1"></i>Refresh
                                          </button>
                                          <button :disabled="generated == false" @click="printCSIReport()" class="btn btn-success">
                                              <i class="ri-printer-line me-1"></i>Print Report
                                          </button>
                                      </div>
                                  </div>
                              </div>
                          </div>
                    </div>

                  <!-- Content Preview-->
                  <MonthlyContent v-if="form.csi_type == 'By Month' && generated == true  || form.csi_type == 'By Date' && generated == true" :form="form"  :data="props" />
                  <Q1Content v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'FIRST QUARTER' && generated == true "  :form="form"  :data="props" />
                  <Q2Content v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'SECOND QUARTER' && generated == true" :form="form"  :data="props" />
                  <Q3Content v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'THIRD QUARTER' && generated == true"  :form="form"  :data="props" />
                  <Q4Content v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'FOURTH QUARTER' && generated == true" :form="form"  :data="props" />
                  <YearlyContent v-if="form.csi_type == 'By Year/Annual' && generated == true"  :form="form"  :data="props"  />
                  
                    <!-- End Content Preview-->


                  <!-- QUARTER AND YEARLY PRINTOUTS Preview-->
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
                      :users="users"
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



