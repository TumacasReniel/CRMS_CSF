<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import MonthlyContent from '@/Pages/CSI/Monthly/Content.vue';
import Q1Content from '@/Pages/CSI/Quarterly/Contents/Q1Content.vue';
import Q2Content from '@/Pages/CSI/Quarterly/Contents/Q2Content.vue';
import Q3Content from '@/Pages/CSI/Quarterly/Contents/Q3Content.vue';
import Q4Content from '@/Pages/CSI/Quarterly/Contents/Q4Content.vue';
import YearlyContent from '@/Pages/CSI/Yearly/Content.vue';
import AllUnitMonthlyContent from '@/Pages/CSI/AllServicesUnits/Monthly/Content.vue';
import ByUnitMonthlyReport from '@/Pages/CSI/Monthly/ByUnitMonthly.vue';
import ByUnitQ1Report from '@/Pages/CSI/Quarterly/Printouts/ByUnitQuarter1.vue';
import ByUnitQ2Report from '@/Pages/CSI/Quarterly/Printouts/ByUnitQuarter2.vue';
import ByUnitQ3Report from '@/Pages/CSI/Quarterly/Printouts/ByUnitQuarter3.vue';
import ByUnitQ4Report from '@/Pages/CSI/Quarterly/Printouts/ByUnitQuarter4.vue';
import ByUnitYearlyReport from '@/Pages/CSI/Yearly/ByUnitYearly.vue';
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


const generateCSIReport = async () => {
   form.generated = true;
   form.service = service;
   form.unit = unit;
  //  console.log(form,990);

     if(form.csi_type == 'By Month'){
            form.selected_quarter = "";
            router.get('/csi/generate/ByUnit/Monthly', form , { preserveState: true, preserveScroll: true})
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
                    <v-card class="p-5 mb-3">
                   
                         <v-row class="p-3">
                             <v-col class="my-auto">
                                <v-combobox v-model="form.csi_type" class="m-3" label="Select Type" variant="outlined" 
                                :items="['By Month', 'By Quarter', 'By Year/Annual', 'By Employee']" border="none"> </v-combobox>
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
                              <v-btn @click="generateCSIReport()" >Generate</v-btn>
                            </v-col>
                           <!-- <v-col class="text-end mr-5">
                             <v-btn  :disabled="form.generated == false" prepend-icon="mdi-printer" @click="printCSIReport()">Print</v-btn>
                           </v-col> -->
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
                              <v-btn @click="generateCSIReport()" >Generate</v-btn>
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
                              <v-btn  @click="generateCSIReport()" >Generate</v-btn>
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
                              <v-btn @click="generateCSIReport()" >Generate</v-btn>
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
                              <v-btn @click="generateCSIReport()" >Generate</v-btn>
                            </v-col>
                           <v-col class="text-end mr-5 m-3">
                             <v-btn  :disabled="form.generated == false" prepend-icon="mdi-printer" @click="printCSIReport()">Print</v-btn>
                           </v-col>
                        </v-row>
                     </v-card>


                    <!-- Content Preview-->
                    <AllUnitMonthlyContent v-if="form.csi_type == 'By Month'"  :form="form"  :data="props" />
                    
                      <!-- End Content Preview-->
                </div>
            </div>
        </div>

         <!-- Printouts-->
        <ByUnitMonthlyReport v-if="form.csi_type == 'By Month'" :form="form"  :data="props" />
        <ByUnitQ1Report v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'FIRST QUARTER' && form.generated == true" :is_printing="is_printing"  :form="form"  :data="props" />
        <ByUnitQ2Report v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'SECOND QUARTER' && form.generated == true" :is_printing="is_printing"  :form="form"  :data="props" />
        <ByUnitQ3Report v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'THIRD QUARTER' && form.generated == true" :is_printing="is_printing"  :form="form"  :data="props" />
        <ByUnitQ4Report v-if="form.csi_type == 'By Quarter' && form.selected_quarter == 'FOURTH QUARTER' && form.generated == true" :is_printing="is_printing"  :form="form"  :data="props" />
        <ByUnitYearlyReport v-if="form.csi_type == 'By Year/Annual' && form.generated == true" :is_printing="is_printing"  :form="form"  :data="props" />
        
    </AppLayout>
</template>


