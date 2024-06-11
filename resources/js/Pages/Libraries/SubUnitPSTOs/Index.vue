<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Pagination from '@/Shared/Pagination.vue';
    import ModalForm from '@/Pages/Libraries/SubUnitPSTOs/Modal.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { reactive ,ref, watch, onMounted} from 'vue';
    import Swal from 'sweetalert2';
    
    
    const props = defineProps({
        sub_units: Object, 
        sub_unit_pstos: Object, 
        pstos: Object,
    });


    const show_modal = ref(false);
    const action_clicked = ref(null);

    const form = ref({});
    const sub_unit = ref({});
    const search = ref('');

    watch(
    () => search.value,
        (search) => {
            router.get('/unit-pstos', { search },{ preserveState: true})
        }
        
    );
    
    const showSubUnitPSTOModal = async (is_show, action,data) => {
        show_modal.value = is_show;
        action_clicked.value = action;
        sub_unit.value = data;
        
    };




    const deleteRecord = async (data) => {

        Swal.fire({
            html: '<div style="font-weight: bold; font-size:25px">Are you sure you want to delete this record?</div> ',
            icon:'warning',
            
            showCancelButton: true,
            confirmButtonText: "Yes, I'm sure",
            showLoaderOnConfirm: true,
        }).then((result) => {
            if (result.isConfirmed) {            
                router.post('/sub-unit-pstos/delete', data );
            }
        });

    };

 

    const reloadSubUnits = async () => {
        sub_unit.value = {};
    };


    let page_number = 1;
    const getSubUnits = async (page) => {
       router.visit('/sub-unit-pstos?page=' + page , { preserveState: true});
       page_number = page;
    };

  


</script>


<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                SubUnit PSTOs
            </h2>

        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <v-row style="margin-bottom:-30px">
                       <v-col cols="6">
                         <div class="w-full m-5 text-right">
                            <v-text-field
                                :loading="loading"
                                append-inner-icon="mdi-magnify"
                                density="compact"
                                label="Search"
                                variant="solo"
                                hide-details
                                single-line
                                v-model="search"
                                @click:append-inner="onClick"
                            ></v-text-field>   
                         </div>
                        </v-col>

                    </v-row>

                    <v-card class="m-3">
                       
                        <v-table>
                            <thead>
                            <tr >
                                <th>ID</th>
                                <th class="text-left">
                                    SubUnit
                                </th>

                                
                                  <th class="text-center">
                                    Actions
                                </th>
                                
                            </tr>
                            </thead>
                            <tbody>
                            <tr 
                                v-for="(sub_unit,index) in sub_units.data"
                                :key="sub_unit.id"
                                class="hover:bg-gray-200"
                            >
                                <template v-if="sub_unit">
                                     <td>{{ sub_unit.id }}</td>
                                    <td>{{ sub_unit.sub_unit_name }}</td>
                                    <td class="text-center">
                                        <v-btn @click="showSubUnitPSTOModal(true, 'Assign' , sub_unit)" size="small" prepend-icon="mdi-update" color="primary">     
                                            Assign                         
                                        </v-btn>
                                    </td>
                                </template>
                                 <template v-else>
                                    <td span=""> No data at the moment</td>
                                </template>
                            </tr>
                            </tbody>

                            <template v-slot:bottom>
                 
                                 <div class="m-2">
                                    <span style="color: gray">
                                        Showing {{ sub_units.from }} to {{ sub_units.to }} out of
                                        <b>{{ sub_units.total }} records</b>
                                                       {{ page_number }}
                                    </span>
                                    <div class="text-center">
                                        <v-pagination
                                            v-model="page_number"
                                            :length="sub_units.last_page"
                                            circle
                                            @click="getSubUnits(page_number)"
                                        ></v-pagination>
                                    </div>   
                                </div>  
                            </template> 
                        </v-table>
                    </v-card>
                </div>
            </div>
        </div>

        <ModalForm 
            :value="show_modal"
            :sub_unit="sub_unit"
            :sub_unit_pstos="sub_unit_pstos"
            :pstos="pstos"
            :action="action_clicked"
            :page_number="page_number"
            @input="showSubUnitPSTOModal"
            @reloadSubUnits="reloadSubUnits"
        ></ModalForm>
    </AppLayout>
</template>

