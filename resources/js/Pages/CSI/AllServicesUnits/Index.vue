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
  ord_total_respondents: Object,
  ord_total_vss_respondents: Object,
  ord_percentage_vss_respondents: Object,
  csi_total: Number, 
});


const form = reactive({
  date_from: null,
  date_to: null,
  csi_type: null,
  selected_month: null,
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
                Customer Satisfaction Index
            </h2>
        </template>

        <div class="container-fluid py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="card shadow-lg border-0" data-aos="fade-up">
                        <div class="card-header bg-primary text-white">
                            <h4 class="card-title mb-0">
                                <i class="ri-bar-chart-line me-2"></i>
                                All Services Units
                            </h4>
                        </div>
                        <!-- <div class="card-body">
                            <div class="alert alert-info border-0 shadow-sm" role="alert" data-aos="fade-in" data-aos-delay="200">
                                <div class="d-flex align-items-center">
                                    <i class="ri-information-line fs-4 me-3 text-info"></i>
                                    <div>
                                        <h6 class="alert-heading mb-2 fw-bold">Manual Excel Reporting Required</h6>
                                        <p class="mb-0">Please generate traditional manual reports on Excel for all units reports:</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4 mt-3">
                                <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                                    <div class="card h-100 border-primary border-2">
                                        <div class="card-body text-center">
                                            <i class="ri-calendar-line text-primary fs-1 mb-3"></i>
                                            <h5 class="card-title fw-bold">Monthly Reports</h5>
                                            <p class="card-text text-muted">Generate reports by specific months</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                                    <div class="card h-100 border-success border-2">
                                        <div class="card-body text-center">
                                            <i class="ri-time-line text-success fs-1 mb-3"></i>
                                            <h5 class="card-title fw-bold">Quarterly Reports</h5>
                                            <p class="card-text text-muted">First, Second, Third, and Fourth Quarter reports</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                                    <div class="card h-100 border-warning border-2">
                                        <div class="card-body text-center">
                                            <i class="ri-calendar-todo-line text-warning fs-1 mb-3"></i>
                                            <h5 class="card-title fw-bold">Annual Reports</h5>
                                            <p class="card-text text-muted">Complete yearly/annual reports</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 p-3 bg-light rounded" data-aos="fade-in" data-aos-delay="600">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="ri-file-excel-line me-2"></i>
                                    Report Types Available:
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-2"><i class="ri-check-line text-success me-2"></i>By Monthly</li>
                                            <li class="mb-2"><i class="ri-check-line text-success me-2"></i>By First Quarter</li>
                                            <li class="mb-2"><i class="ri-check-line text-success me-2"></i>By Second Quarter</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-2"><i class="ri-check-line text-success me-2"></i>By Third Quarter</li>
                                            <li class="mb-2"><i class="ri-check-line text-success me-2"></i>By Fourth Quarter</li>
                                            <li class="mb-2"><i class="ri-check-line text-success me-2"></i>By Yearly/Annual</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <span class="badge bg-primary fs-6 px-3 py-2">
                                        <i class="ri-heart-line me-1"></i>
                                        THANK YOU!
                                    </span>
                                </div>
                            </div>
                        </div> -->
                        <div class="card-body">

                        
                        </div>
                    </div>

                    <!-- Content Preview Card -->
                    <!-- <div v-if="form.csi_type == 'By Month' && generated == true" class="card mt-4 shadow" data-aos="fade-in">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title mb-0">
                                <i class="ri-file-chart-line me-2"></i>
                                Report Preview
                            </h5>
                        </div>
                        <div class="card-body print-id">
                            <MonthlyContent :form="form" :data="props" />
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>



