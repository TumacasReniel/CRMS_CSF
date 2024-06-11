<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { reactive, ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { Printd } from "printd";
import Swal from 'sweetalert2'

const props = defineProps({
    service_units: Object,
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
            router.get('/csi/generate/all-units', form , { preserveState: true, preserveScroll: true});
      }
      else if(form.csi_type == 'By Quarter'){
            form.selected_month = "";
      
      }
      else if(form.csi_type == 'By Year/Annual'){
            form.selected_quarter = "";
            if(form.selected_year ){
              router.get('/csi/generate/ByUnit/Yearly', form , { preserveState: true, preserveScroll: true});
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
                        <v-card-title class="m-3" >
                            <div>
                                All SERVICES UNITS
                            </div>
                           
                        </v-card-title>
                    </v-card>
                    
                     <v-divider class="border-opacity-100"></v-divider>
                   
                     <v-card class="mb-3 my-auto">
                       <v-card-text class="ml-5">
                         <span class="font-black">PLEASE DO THE TRADITIONAL MANUAL REPORT ON EXCEL FOR ALL UNITS REPORT </span><br>
                          <span class="ml-10">
                            <p>-By Monthly</p>
                            <p>-By First Quarter</p>
                            <p>-By Second Quarter</p>
                            <p>-By Third Quarter</p>
                            <p>-By Fourth Quarter</p>
                            <p>-By Yearly/Annual</p>
        
                            <b>THANK YOU!</b>
                          </span>
                         </v-card-text>
                          <v-divider class="border-opacity-100"></v-divider>
                     </v-card>

                     

                </div>
            </div>
        </div>

              
    </AppLayout>
</template>


