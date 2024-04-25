<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import ModalForm from '@/Pages/Libraries/PSTOs/Modal.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { reactive ,ref, watch, onMounted} from 'vue';
    import Swal from 'sweetalert2';
    
    const props = defineProps({
        pstos: Object, 
    });


    const show_modal = ref(false);
    const action_clicked = ref(null);

    const form = ref({});
    const psto = ref({});
    const search = ref('');

    watch(
    () => search.value,
        (search) => {
            router.get('/pstos', { search },{ preserveState: true})
        }
        
    );
    
    const showPSTOModal = async (is_show, action,psto_data) => {
        show_modal.value = is_show;
        action_clicked.value = action;
        psto.value = psto_data;
    };

    const deleteRecord = async (id) => {

        Swal.fire({
            html: '<div style="font-weight: bold; font-size:25px">Are you sure you want to delete this record?</div> ',
            icon:'warning',
            
            showCancelButton: true,
            confirmButtonText: "Yes, I'm sure",
            showLoaderOnConfirm: true,
        }).then((result) => {
            if (result.isConfirmed) {            
                router.post('/pstos/delete', { id } );
            }
        });

    };


    const reloadPSTOs = async () => {
        pstos.value = {};
    };

     let page_number = 1;
    const getPSTOs = async (page) => {
       router.visit('/pstos?page=' + page , { preserveState: true});
       page_number = page;
    };
</script>


<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                PSTOs
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <v-row style="margin-bottom:-30px">
                       <v-col>
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
                        <v-col>
                            <div class="text-right m-5">
                                <v-btn @click="showPSTOModal(true, 'Add', null)" size="small" prepend-icon="mdi-plus" color="green">     
                                    PSTO                      
                                </v-btn>
                            </div>
                        </v-col>
                    </v-row>
                         
                    <v-card class="m-3">
                       
                        <v-table>
                            <thead>
                            <tr >
                                <th class="text-left">
                                    #
                                </th>
                                <th class="text-left">
                                    Name
                                </th>

                                
                                  <th class="text-center">
                                    Actions
                                </th>
                                
                            </tr>
                            </thead>
                            <tbody>
                            <tr 
                                v-for="(psto,index) in pstos.data"
                                :key="psto.id"
                                class="hover:bg-gray-200"
                            >
                                <template v-if="psto">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ psto.psto_name }}</td>
                                    <td class="text-center">
                                         <v-btn class="mr-3" @click="deleteRecord(psto.id)" size="small" prepend-icon="mdi-delete" color="red">     
                                            Delete                         
                                        </v-btn>
                                        <v-btn @click="showPSTOModal(true, 'Update' , psto)" size="small" prepend-icon="mdi-update" color="primary">     
                                            Update                         
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
                                        Showing {{ pstos.from }} to {{ pstos.to }} out of
                                        <b>{{ pstos.total }} records</b>
                                    </span>
                                    <div class="text-center">
                                        <v-pagination
                                            v-model="page_number"
                                            :length="pstos.last_page"
                                            circle
                                            @click="getPSTOs(page_number)"
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
            :psto="psto"
            :action="action_clicked"
            @input="showPSTOModal"
            @reloadPSTOs="reloadPSTOs"
        ></ModalForm>
    </AppLayout>
</template>
