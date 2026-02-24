<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { reactive, ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { Printd } from "printd";
import Swal from 'sweetalert2';
import MonthlyContent from '@/Pages/CSI/AllServicesUnits/Monthly/Content.vue';
import VueMultiselect from "vue-multiselect";
import AOS from 'aos'
import 'aos/dist/aos.css'

AOS.init();
const props = defineProps({
  services_units: Object,
  cc_data: Object,
  all_units_data: Object,
  csi_total: Number,
  nps_total: Number,
  lsr_total: Number,
  total_respondents: Number,
  total_vss_respondents: Number,
  percentage_vss_respondents: Number,
  request: Object,
});


const form = reactive({
  date_from: null,
  date_to: null,
  csi_type: null,
  selected_month: null,
  selected_year: null,
  selected_quarter: null,
  comments_complaints: null,
  analysis: null,

  service: [],
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

const generated = ref(false); 
onMounted(() => {
    form.selected_month = currentMonth.value;
    form.selected_year = currentYear.value;
    generated.value = false;
});


const generateCSIReport = async () => {
   generated.value = true;

    if(form.csi_type == 'By Month'){
          form.selected_quarter = "";
          router.get('/csi/generate/all-units/monthly', form , { preserveState: true, preserveScroll: true})
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

</script>

<template>
    <AppLayout title="Customer Satisfaction Index">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Customer Satisfaction Index - All Services Units
            </h2>
        </template>

        <div class="container-fluid py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <!-- Filters Card -->
                    <div class="card shadow-lg border-0 mb-4" data-aos="fade-up">
                        <div class="card-header bg-primary text-white">
                            <h4 class="card-title mb-0">
                                <i class="ri-filter-3-line me-2"></i>
                                Generate Report
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Report Type</label>
                                    <select v-model="form.csi_type" class="form-select">
                                        <option value="">Select Report Type</option>
                                        <option value="By Month">By Month</option>
                                        <option value="By Quarter">By Quarter</option>
                                        <option value="By Year/Annual">By Year/Annual</option>
                                    </select>
                                </div>
                                <div class="col-md-4" v-if="form.csi_type == 'By Month'">
                                    <label class="form-label fw-bold">Month</label>
                                    <select v-model="form.selected_month" class="form-select">
                                        <option v-for="month in months" :key="month" :value="month">{{ month }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4" v-if="form.csi_type == 'By Quarter'">
                                    <label class="form-label fw-bold">Quarter</label>
                                    <select v-model="form.selected_quarter" class="form-select">
                                        <option value="">Select Quarter</option>
                                        <option value="FIRST QUARTER">First Quarter</option>
                                        <option value="SECOND QUARTER">Second Quarter</option>
                                        <option value="THIRD QUARTER">Third Quarter</option>
                                        <option value="FOURTH QUARTER">Fourth Quarter</option>
                                    </select>
                                </div>
                                <div class="col-md-4" v-if="form.csi_type">
                                    <label class="form-label fw-bold">Year</label>
                                    <select v-model="form.selected_year" class="form-select">
                                        <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button @click="generateCSIReport" class="btn btn-primary w-100">
                                        <i class="ri-file-chart-line me-2"></i>
                                        Generate Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Preview Card -->
                    <div v-if="form.csi_type == 'By Month' && generated == true" class="card mt-4 shadow" data-aos="fade-in">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-file-chart-line me-2"></i>
                                Report Preview - {{ form.selected_month }} {{ form.selected_year }}
                            </h5>
                            <button @click="printCSIReport" class="btn btn-light">
                                <i class="ri-printer-line me-2"></i>
                                Print
                            </button>
                        </div>
                        <div class="card-body print-id">
                            <MonthlyContent :form="form" :data="props" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
