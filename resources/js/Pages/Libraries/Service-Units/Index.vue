<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Account from '@/Components/Account.vue';
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'

defineProps({
    service_units: Object,
});

const form = reactive({
  service_id: null,
  unit_id:  null,
})

const rating = async (service_id, unit_id) => {
   form.service_id = service_id;
   form.unit_id = unit_id;
   router.get('/csi', form , { preserveState: true })
};

const all_service_unit_rating = async () => {
   router.get('/csi/all-units', form , { preserveState: true })
};


</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Services
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
                                        {{ index + 1 }}
                                    </td>  
                                    <td  class="p-2 mr-2  border border-solid hover:bg-gray-100 focus-within:bg-gray-100">
                                        {{ unit.unit_name }}
                                    </td>  
                                     <td class="text-center px-4 py-2 p-2 mr-2 border border-solid">
                                        <v-btn prepend-icon="mdi-eye" class="mr-3" size="small">View</v-btn>
                                        <v-btn @click="rating(service_unit.id, unit.id)" prepend-icon="mdi-file" color="yellow" size="small">Rating</v-btn>
                                    </td>  
                                </tr>          
                            </template>                          
                        </table>
                       <v-divider :thickness="1" class="border-opacity-100 mb-5"></v-divider>
                    </v-card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
