<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3'
import { reactive } from 'vue'

defineProps({
    unit: Object,
});

const form = reactive({
  form_type: null, 
  service: null,
  unit:  null,
})

const rating = async (service, unit) => {
   form.service = service;
   form.unit =unit ;
   router.get('/csi', form , { preserveState: unit_idtrue })
};


</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               <v-breadcrumbs :items="['Dashboard', 'Service Units', unit.unit_name]"></v-breadcrumbs>
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <v-card class="m-5" v-if="unit.sub_units">           
                           <table>
                                <tr>
                                    <th>#</th>
                                    <th class="text-left">Sub Units</th>
                                    <th>Actions</th>
                                </tr>
                                <template v-if="unit" v-for="(sub_unit, index) in unit.sub_units" :key="sub_unit.id" >
                                    <tr >
                                        <td class="text-center">{{ index + 1 }}</td>
                                       <td class="text-left"> {{  sub_unit.sub_unit_name }}</td>
                                        <td class="text-center">
                                            <v-btn prepend-icon="mdi-eye" class="mr-5" size="small">View</v-btn>
                                            <v-btn @click="rating(unit)" prepend-icon="mdi-file" color="yellow"  size="small">Rating</v-btn>
                                        </td>
                                    </tr>
                               </template>
                           </table>
                     
                    </v-card>

                    
               
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
   table {
    border-collapse: collapse;
    width: 100%; /* Optional: Set a width for the table */
  }

  tr, th, td {
    border: 1px solid rgb(145, 139, 139); /* Optional: Add a border for better visibility */
    padding: 8px; /* Optional: Add padding for better spacing */
  }
</style>
