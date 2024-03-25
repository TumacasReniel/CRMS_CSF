<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ViewForm from '@/Pages/Libraries/Service-Units/Views/Modal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive ,ref, watch} from 'vue'

const props = defineProps({
    service_units: Object,
    user: Object,
});

const form = reactive({
  service: null,
  unit: null,
  service_id: null,
  unit_id: null,
})

const rating = async (service_id, unit_id) => {
   form.service_id = service_id;
   form.unit_id = unit_id;
   router.get('/csi', form , { preserveState: true })
};

const all_service_unit_rating = async () => {
   form.form_type = "all units";
   router.get('/csi/all-units', form , { preserveState: true })
};

const show_modal = ref(false);

const showViewModal = async (service, unit) => {
  form.service = service;
  form.unit = unit;
  show_modal.value = true;
};

const viewModal = async (is_show) => {
  show_modal.value = is_show;
};

const viewMore = async (service, unit) => {
    form.service = service;
    form.unit = unit;
    console.log(form, 88);

    if(unit.id == 9 || unit.id == 11 || unit.id == 14 || unit.id == 15){
        router.get('/service_unit/unit-psto', form , { preserveState: true })
    }
    else{
        router.get('/service_unit/unit', form , { preserveState: true })
    }

       
};

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                 <v-breadcrumbs :items="['Dashboard', 'Service Units']"></v-breadcrumbs>
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <v-card>
                        <v-row>
                            <v-col class="text-right m-5 mb-1">
                                <v-btn @click="all_service_unit_rating()" prepend-icon="mdi-file" color="yellow" style="margin-right:120px">
                                        All Unit Ratings
                                </v-btn>
                            </v-col>
                        </v-row>
                       <div class="m-5 text-end">
                       </div>
                        <table class="w-full border">
                            <tr class="text-left font-bold text-center">
                                <th class="pb-4 pt-6 px-6" colspan="2"></th>
                                <th class="pb-4 pt-6 px-6">Actions</th>
                            </tr>

                            <template v-if="service_units" v-for="(service_unit, index) in service_units.data" :key="service_unit.id">
                                <tr class="border border-solid bg-blue-100">                
                                    <td class="m5 p-5  border border-solid font-black" colspan="3" >
                                         {{ service_unit.services_name }}
                                    </td>  
                                </tr>       

                                <tr  v-for="(unit, index) in service_unit.units" :key="unit.id"> 
                                    <td class="text-center p-2 border border-solid hover:bg-gray-100 focus-within:bg-gray-100">
                                        {{ unit.id }}
                                    </td>  
                                    <td  class="p-2 mr-2  border border-solid hover:bg-gray-100 focus-within:bg-gray-100">
                                        {{ unit.unit_name }}
                                    </td>  
                                     <td class="text-center px-4 py-2 p-2 mr-2 border border-solid">
                                        <v-btn prepend-icon="mdi-eye" class="mr-3" size="small" @click="showViewModal(service_unit, unit)"
                                             v-if="unit.id == 1 || unit.id == 2 || unit.id == 3 || unit.id == 4 || unit.id == 5 || unit.id == 6 || 
                                                    unit.id == 8 || unit.id == 12 || unit.id == 13 || unit.id == 16 || unit.id == 17 || unit.id == 18 || 
                                                    unit.id == 19 || unit.id == 21"
                                        >
                                            View
                                        </v-btn>
                                        <v-btn v-else prepend-icon="mdi-eye" class="mr-3" size="small" @click="viewMore(service_unit,unit)">View</v-btn>
                                        <v-btn
                                            @click="rating(service_unit.id, unit.id)" 
                                            prepend-icon="mdi-file" color="yellow" 
                                            size="small"
                                              v-if="unit.id == 1 || unit.id == 2 || unit.id == 3 || unit.id == 4 || unit.id == 5 || unit.id == 6 || 
                                                    unit.id == 8  || unit.id == 12 || unit.id == 13 || unit.id == 16 || unit.id == 17 || unit.id == 18 || 
                                                    unit.id == 19 || unit.id == 21"
                                        >
                                            Rating
                                        </v-btn>
                                    </td>  
                                </tr>          
                            </template>                          
                        </table>
                       <v-divider :thickness="1" class="border-opacity-100 mb-5"></v-divider>
                    </v-card>
                </div>
            </div>
        </div>

        <ViewForm :show_modal="show_modal" :data="props" :form="form"  @input="viewModal()"/>
    </AppLayout>
</template>
