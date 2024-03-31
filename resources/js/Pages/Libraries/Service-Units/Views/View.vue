<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import { router } from '@inertiajs/vue3';
    import { reactive , ref, watch} from 'vue';
    import QrcodeVue from "qrcode.vue";
   
    const props = defineProps({
         service: Object, 
        unit: Object,
        sub_units: Object,
        unit_pstos: Object,
        sub_unit_pstos: Object,
        user: Object,

    });

    const form = reactive({
        generated_url: null,
        selected_sub_unit: '',
        selected_unit_psto: '',
        selected_sub_unit_psto: '',

    });

     const getSubUnitPSTO = async (sub_unit_id) => {
        // Get the current query parameters
        const currentQueryParams = new URLSearchParams(window.location.search);

        // Add or update the 'sub_unit_id' parameter
        currentQueryParams.set('sub_unit_id', sub_unit_id);

        // Construct the new URL with updated query parameters
        const newUrl = `/csi/view?${currentQueryParams.toString()}`;

        // Navigate to the new URL
        await router.visit(
            newUrl,
            {
                //preserveQuery: true, 
                preserveState: true,
                preserveScroll: true,
            }
        );
    };

     watch(
        () => form.selected_sub_unit,
        (value) => {
                if(value){
                    getSubUnitPSTO(value);
                }
        });


    const qr_link_type = ref(null);
    const generateURL = async (sub_unit, unit_psto , sub_unit_psto) =>{
      if(unit_psto){
        qr_link_type.value = 3;
        form.generated_url = baseURL + '/services/csf?' +
                                'region_id=' + props.user.region_id + 
                                '&service_id=' + props.service.id + 
                                '&unit_id=' +  props.unit.id +
                                '&psto_id=' + unit_psto;
      }
      else if(sub_unit_psto && sub_unit_psto){
            qr_link_type.value = 2;
            form.generated_url = baseURL + '/services/csf?' +
                                'region_id=' + props.user.region_id + 
                                '&service_id=' + props.service.id + 
                                '&unit_id=' +  props.unit.id +
                                '&sub_unit_id=' + sub_unit + 
                                '&psto_id=' + sub_unit_psto;

      }

      else if(sub_unit){
            qr_link_type.value = 1;
            form.generated_url = baseURL + '/services/csf?' +
                                'region_id=' + props.user.region_id + 
                                '&service_id=' + props.service.id + 
                                '&unit_id=' +  props.unit.id +
                                '&sub_unit_id=' + sub_unit;

      }

      else{
         qr_link_type.value = 0;
        form.generated_url = baseURL + '/services/csf?' +
                                'region_id=' + props.user.region_id + 
                                '&service_id=' + props.service.id + 
                                '&unit_id=' +  props.unit.id;
      }
      
  
  }

  
    const baseURL = window.location.origin;
    const copied = ref(false);
    // Function to copy text to clipboard
  const copyToClipboard = () => {
    // Create a temporary textarea element
     const textarea = document.createElement('textarea');
    textarea.value = form.generated_url;

    // Append the textarea to the document
    document.body.appendChild(textarea);

    // Select and copy the text
    textarea.select();
    document.execCommand('copy');

    // Remove the textarea from the document
    document.body.removeChild(textarea);
    copied.value = true;

     setTimeout(() => {
         copied.value = false;
      }, 2000);

  };
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               <!-- <v-breadcrumbs :items="['Dashboard', 'Service Units', unit.unit_name]"></v-breadcrumbs> -->
               View
            </h2>
        </template>
        <div class="py-10"  style="margin-left:80px; margin-right:80px">
            <div class="max-w-7x1 mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <v-card class="mb-3">
                        <v-card-title class="m-3" >
                            <div v-if="service">
                                SERVICES :   {{ service.services_name }}
                            </div>
                            <v-divider class="border-opacity-100"></v-divider>
                            <div v-if="unit">
                                SERVICE UNIT :    {{ unit.unit_name }}
                            </div>
                        </v-card-title>
                    </v-card>

                      <v-card>
                        <v-row class="p-5">
                        <v-col class="my-auto" v-if="sub_units.length > 0" >
                            <v-select
                            variant="outlined"
                            v-model="form.selected_sub_unit"
                            :items="sub_units"
                            item-title="sub_unit_name"
                            item-value="id"
                            label="Select Sub Unit"
                            :readonly="generated"
                            ></v-select>
                        </v-col>
                                        
                        <v-col class="my-auto"  v-if="unit_pstos.length > 0" >
                        <v-select
                            variant="outlined"
                            v-model="form.selected_unit_psto"
                            :items="unit_pstos"
                            item-title="psto_name"
                            item-value="id"
                            label="Select Unit PSTO"
                            ></v-select>
    
                        </v-col>
                        <v-col class="my-auto"  v-if="sub_unit_pstos.length > 0" >
                            <v-select
                                v-if="form.selected_sub_unit"
                                variant="outlined"
                                v-model="form.selected_sub_unit_psto"
                                :items="sub_unit_pstos"
                                item-title="psto_name"
                                item-value="id"
                                label="Select Sub Unit PSTO"
                            ></v-select>                          
                        </v-col>

                        <v-col class="my-auto text-right" >
                            <v-btn :disabled="sub_units.length > 0  && form.selected_sub_unit == '' || sub_unit_pstos.length > 0 && form.selected_sub_unit_psto == '' || unit_pstos.length > 0 && form.selected_unit_psto == ''  " prepend-icon="mdi-plus" @click="generateURL(form.selected_sub_unit, form.selected_unit_psto , form.selected_sub_unit_psto)" >Generate URL </v-btn>           
                        </v-col>
                        </v-row>
                    </v-card>

                    <v-card class="mt-5">
                        
                        <div class="p-5 m-5" label="URL">
                            <v-row>
                                <v-col cols="10" md="11">
                                <v-text-field v-model="form.generated_url" variant="outlined" label="URL" readonly></v-text-field>                                       
                                </v-col>
                                <v-col>
                                <v-btn color="none" icon="mdi-content-copy" @click="copyToClipboard()" >
                                    
                                </v-btn>
                                <span v-if="copied">copied</span>
                                </v-col>
                            </v-row>
                        </div>

                        <div style="  display: flex;justify-content: center;align-items: center;" class="mb-10">
                             <QrcodeVue
                                v-if="qr_link_type == 0"
                                :render-as="'svg'"
                                :value="`${baseURL}/services/csf?region_id=${user.region_id}&service_id=${props.service.id}&unit_id=${props.unit.id}`"
                                :size="145"
                                :foreground="'#000'"
                                level="L"
                                style="
                                border: 3px #ffffff solid;
                                width: 300px;
                                height: 300px;
                      
                                "
                            />
                            <QrcodeVue
                                v-if="qr_link_type == 1"
                                :render-as="'svg'"
                                :value="`${baseURL}/services/csf?region_id=${user.region_id}&service_id=${props.service.id}&unit_id=${props.unit.id}&sub_unit_id=${form.selected_sub_unit.id}`"
                                :size="145"
                                :foreground="'#000'"
                                level="L"
                                style="
                                border: 3px #ffffff solid;
                                width: 300px;
                                height: 300px;
                      
                                "
                            />

                              <QrcodeVue
                                v-if="qr_link_type == 2"
                                :render-as="'svg'"
                                :value="`${baseURL}/services/csf?region_id=${user.region_id}&service_id=${props.service.id}&unit_id=${props.unit.id}&sub_unit_id=${form.selected_sub_unit.id}&psto_id=${form.selected_sub_unit_psto.id}`"
                                :size="145"
                                :foreground="'#000'"
                                level="L"
                                style="
                                border: 3px #ffffff solid;
                                width: 300px;
                                height: 300px;
                      
                                "
                            />

                              <QrcodeVue
                                v-if="qr_link_type == 3"
                                :render-as="'svg'"
                                :value="`${baseURL}/services/csf?region_id=${user.region_id}&service_id=${props.service.id}&unit_id=${props.unit.id}psto_id=${form.selected_unit_psto.id}`"
                                :size="145"
                                :foreground="'#000'"
                                level="L"
                                style="
                                border: 3px #ffffff solid;
                                width: 300px;
                                height: 300px;
                      
                                "
                            />
                        </div>
                        
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