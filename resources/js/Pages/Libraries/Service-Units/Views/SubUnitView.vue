<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import ViewForm from '@/Pages/Libraries/Service-Units/Views/Modal.vue';
    import { router } from '@inertiajs/vue3'
    import { reactive , ref} from 'vue'
   
    const props = defineProps({
        user: Object,
        service: Object,
        unit: Object,
        sub_units: Object,
    });

    const form = reactive({
        form_type: null, 
        service: null,
        unit:  null,
        sub_unit: null,
        has_sub_unit: false,
        has_psto: false,
    })

    const show_modal = ref(false);
    const showViewModal = async (service, unit,sub_unit) => {
        form.service = service;
        form.unit = unit;
        form.sub_unit = sub_unit;
        form.has_sub_unit = true;
        form.has_psto = false;

     
        if(unit.id == 10){
            router.get('/service_unit/sub-unit-psto', form , { preserveState: true })
        }
        else if(unit.id == 15 || unit.id == 16){
            router.get('/service_unit/unit-psto', form , { preserveState: true })
        }
        else{
            show_modal.value = true;
        }
       
    };

    const viewModal = async (is_show) => {
        show_modal.value = is_show;
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
                    <v-card class="m-5" >           
                           <table>
                                <tr>
                                    <th>#</th>
                                    <th class="text-left">Sub Units</th>
                                    <th>Actions</th>
                                </tr>
                                <template v-if="sub_units.length > 0" v-for="(sub_unit, index) in sub_units" :key="sub_unit.id" >
                                    <tr >
                                        <td class="text-center">{{ index + 1 }}</td>
                                       <td class="text-left"> {{  sub_unit.sub_unit_name }}</td>
                                        <td class="text-center">
                                            <v-btn prepend-icon="mdi-eye" class="mr-5" size="small" @click="showViewModal(service, unit, sub_unit)">View</v-btn>
                                            <a :href="`/csi?service_id=${service.id}&unit_id=${unit.id}&sub_unit_id=${sub_unit.id}`">
                                                 <v-btn prepend-icon="mdi-file" color="yellow"  size="small">Rating</v-btn>
                                            </a>
                                           
                                        </td>
                                    </tr>
                               </template>
                           </table>

                    </v-card>



                </div>
            </div>
        </div>

          <ViewForm :show_modal="show_modal" :data="props" :form="form"  @input="viewModal()"/>
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