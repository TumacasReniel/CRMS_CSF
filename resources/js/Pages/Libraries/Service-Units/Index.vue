<script setup>
import VueMultiselect from "vue-multiselect";
import AppLayout from '@/Layouts/AppLayout.vue';
import ModalForm from '@/Pages/Libraries/Service-Units/Form/Modal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive ,ref, watch} from 'vue'

const props = defineProps({
    service_units: Object,
    sub_units: Object,
    user: Object,
});

const form = reactive({
  service_id: null,
  unit_id: null,
})

const rating = async (service_id, unit_id) => {

   form.service_id = service_id;
   form.unit_id = unit_id;
   router.get('/csi', form , { preserveState: true });
};

const all_service_unit_rating = async () => {
   form.form_type = "all units";
   router.get('/csi/all-units', form , { preserveState: true })
};

const show_modal = ref(false);
const action_clicked = ref('');
const selected_service = ref({});


const goViewPage = async (service_id, unit_id) => {
   form.service_id = service_id;
   form.unit_id = unit_id;
   router.get('/csi/view', form , { preserveState: true });

};

const showServiceModal = async (is_show, action , service) => {
    show_modal.value =is_show;
    action_clicked.value = action;
    if(service){
        selected_service.value = service;
        console.log(service, 77);
    }
};

const openPDF = () => {
    // Replace 'path/to/your/pdf/file.pdf' with the actual path to your PDF file
    const pdfPath = 'https://drive.google.com/file/d/1YHbyz0GnrXOTilE8Kpae1ZriNQQ2FVNh/view?usp=sharing';
    
    // Open the PDF in a new tab or window
    window.open(pdfPath, '_blank');
};
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                 <!-- <v-breadcrumbs :items="['Dashboard', 'Service Units']"></v-breadcrumbs> -->
                 Services Units
            </h2>
        </template>

        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <v-card>
                      
                        <v-row>
                            <v-col class="text-left m-5 mb-1" v-if="user.account_type == 'admin'">
                                <v-btn @click="showServiceModal(true, 'add_new_service', null)" prepend-icon="mdi-plus" color="primary"  size="small">
                                       Add New Service
                                </v-btn>
                            </v-col>
                            <v-col class="text-right m-5 mb-1">
                                <v-btn @click="all_service_unit_rating()" prepend-icon="mdi-file" color="yellow" style="margin-right:100px"  size="small">
                                        All Unit Ratings
                                </v-btn>

                                <v-btn 
                                    prepend-icon="mdi-printer" 
                                    class="mr-5"
                                    color="success"
                                    size="small"
                                    @click="openPDF()"
                                        >CSF Form(manual)
                                </v-btn>   
                                </v-col>
                            </v-row>
                        
                       
                        <table class="w-full">
                            
                            <thead class="font-bold text-center ">
                                <th class="pb-4 pt-6 px-6" colspan="2">Services Units</th>
                                <th class="pb-4 pt-6 px-6">Actions</th>
                            </thead>
                            
                            <template v-if="service_units" v-for="(service_unit, index) in service_units.data" :key="service_unit.id">
                                <tr class="border border-solid bg-blue-100">                
                                    <td class="m5 p-5  border border-solid font-black" colspan="2" >
                                         {{ service_unit.services_name }}
                                    </td>  
                                    <td class="m5 p-5  border border-solid font-black text-center" colspan="3" v-if="user.account_type == 'admin'">
                                         <v-btn @click="showServiceModal(true, 'add_new_unit', service_unit )" prepend-icon="mdi-plus" color="primary" size="small">
                                            Add New Unit
                                        </v-btn>
                                    </td>  
                                </tr>       

                                <tr  v-for="(unit, index) in service_unit.units" :key="unit.id"> 
                                    <td class="text-center p-2 border border-solid hover:bg-gray-100 focus-within:bg-gray-100">
                                        {{ unit.id }}
                                    </td>  
                                    <td  class="p-2 mr-2  border border-solid hover:bg-gray-100 focus-within:bg-gray-100">
                                        {{ unit.unit_name }}
                                    </td>  
                                     <td class="text-center px-4 py-2 p-2 mr-2 border border-solid" 
                                     >

                                       <div>
                                         <v-btn prepend-icon="mdi-eye" class="mr-3" size="small" @click="goViewPage(service_unit.id, unit.id)"
                                            :disabled="user.account_type == 'user' && user.unit_id != unit.id"
                                        >
                                            View 
                                        </v-btn>
          
                                        <v-btn
                                            @click="rating(service_unit.id, unit.id)" 
                                            prepend-icon="mdi-file" color="yellow" 
                                            size="small"
                                            :disabled="user.account_type == 'user' && user.unit_id != unit.id"
                                        >
                                            Rating
                                        </v-btn>
                                        </div>
                                    </td>  
                                </tr>          
                            </template>                          
                        </table>
                       <v-divider :thickness="1" class="border-opacity-100 mb-5"></v-divider>
                    </v-card>
                </div>
            </div>
        </div>

      <ModalForm 
          :value="show_modal"
          :action_clicked="action_clicked"
          :account="account"
          :selected_service="selected_service"
          :data="props"
          @input="showServiceModal"
          @reloadAccounts="reloadAccounts"
      ></ModalForm>
    </AppLayout>


</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<style scoped>
   table {
    border-collapse: collapse;
    width: 100%; /* Optional: Set a width for the table */
    border: none;
  }
  tr, th,td {
    border: 1px solid none; /* Optional: Add a border for better visibility */
    padding: 8px; /* Optional: Add padding for better spacing */
  }


  
</style>
