<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import ModalForm from '@/Pages/Libraries/Regions/Modal.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { reactive ,ref, watch, onMounted} from 'vue';
    import Swal from 'sweetalert2';
    
    const props = defineProps({
        regions: Object, 
    });


    const show_modal = ref(false);
    const action_clicked = ref(null);

    const form = ref({});
    const region = ref({});
    const search = ref('');

    watch(
    () => search.value,
        (search) => {
            router.get('/regions', { search },{ preserveState: true})
        }
        
    );
    
    const showRegionModal = async (is_show, action,region_data) => {
        show_modal.value = is_show;
        action_clicked.value = action;
        region.value = region_data;
    };

    const reloadREgions = async () => {
        regions.value = {};
    };
</script>


<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Regions
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
                                <v-btn @click="showRegionModal(true, 'Add', null)" size="small" prepend-icon="mdi-plus" color="green">     
                                    Region                      
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
                                <th class="text-left">
                                    Code
                                </th>
                                    <th class="text-left">
                                    Short Name
                                </th>
                                <th class="text-left">
                                    Order
                                </th>

                                
                                  <th class="text-center">
                                    Actions
                                </th>
                                
                            </tr>
                            </thead>
                            <tbody>
                            <tr 
                                v-for="(region,index) in regions.data"
                                :key="region.id"
                                class="hover:bg-gray-200"
                            >
                                <template v-if="region">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ region.name }}</td>
                                    <td>{{ region.code }}</td>
                                    <td>{{ region.short_name }}</td>
                                    <td>{{ region.order }}</td>

                                    <td class="text-center">
                                        <!-- <v-btn size="small" @click="resetPassword(region.id)" prepend-icon="mdi-delete" color="red" class="mr-5">  
                                            Delete                            
                                        </v-btn> -->
                                        <v-btn @click="showRegionModal(true, 'Update' , region)" size="small" prepend-icon="mdi-update" color="primary">     
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
                                        Showing {{ regions.from }} to {{ regions.to }} out of
                                        <b>{{ regions.total }} records</b>
                                    </span>
                                    <div class="text-center">
                                        <v-pagination
                                            :length="regions.last_page"
                                            circle
                                            @click="getRegions"
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
            :region="region"
            :action="action_clicked"
            @input="showRegionModal"
            @reloadRegions="reloadRegions"
        ></ModalForm>
    </AppLayout>
</template>
