<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import VueMultiselect from "vue-multiselect";
    import { router } from '@inertiajs/vue3';
    import { reactive , ref, watch} from 'vue';
    import QrcodeVue from "qrcode.vue";
    import { Printd } from "printd";
    import CSFPrint from '@/Pages/Libraries/Service-Units/Form/PrintCSF.vue';
   
    const props = defineProps({
        service: Object, 
        unit: Object,
        unit_pstos: Object,
        sub_unit_pstos: Object,
        sub_unit_types: Object,
        user: Object,

    });

    const form = reactive({
        generated_url: null,
        selected_sub_unit: '',
        selected_unit_psto: '',
        selected_sub_unit_psto: '',
        sub_unit_type: '',
        client_type: ''

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
            getSubUnitPSTO(value.id);
        });


    const qr_link_type = ref(null);
    const generated = ref(false);
    const generateURL = async (sub_unit, unit_psto , sub_unit_psto, sub_unit_type) =>{

        generated.value=true;

        if(props.unit.data[0].id == 8){
            qr_link_type.value = 4;
            form.generated_url = baseURL + '/services/csf?' +
                            'region_id=' + props.user.region_id + 
                            '&service_id=' + props.service.id + 
                            '&unit_id=' +  props.unit.data[0].id +
                            '&client_type=' + form.client_type;

        }
        
      else if(unit_psto){
        qr_link_type.value = 3;
        form.generated_url = baseURL + '/services/csf?' +
                                'region_id=' + props.user.region_id + 
                                '&service_id=' + props.service.id + 
                                '&unit_id=' +  props.unit.data[0].id +
                                '&psto_id=' + unit_psto.id;
      }
      else if(sub_unit_psto && sub_unit_psto){
            qr_link_type.value = 2;
            form.generated_url = baseURL + '/services/csf?' +
                                'region_id=' + props.user.region_id + 
                                '&service_id=' + props.service.id + 
                                '&unit_id=' +  props.unit.data[0].id +
                                '&sub_unit_id=' + sub_unit.id + 
                                '&psto_id=' + sub_unit_psto.id;

      }

      else if(sub_unit){
            if(sub_unit_type){
                qr_link_type.value = 1.1;
                form.generated_url = baseURL + '/services/csf?' +
                                'region_id=' + props.user.region_id + 
                                '&service_id=' + props.service.id + 
                                '&unit_id=' +  props.unit.data[0].id +
                                '&sub_unit_id=' + sub_unit.id +
                                 '&sub_unit_type=' + form.sub_unit_type.type_name;

            }
            else{
                qr_link_type.value = 1.2;
                form.generated_url = baseURL + '/services/csf?' +
                                'region_id=' + props.user.region_id + 
                                '&service_id=' + props.service.id + 
                                '&unit_id=' +  props.unit.data[0].id +
                                '&sub_unit_id=' + sub_unit.id;
            }
          
           
      }

      else{
         qr_link_type.value = 0;
        form.generated_url = baseURL + '/services/csf?' +
                                'region_id=' + props.user.region_id + 
                                '&service_id=' + props.service.id + 
                                '&unit_id=' +  props.unit.data[0].id;
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


  const is_printing = ref(false);
  const printCSFForm = async () => {
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

          /* Styles for v-text-field */
            .v-text-field {
            display: inline-block;
            position: relative;
            width: 100%;
            max-width: 700px;
            padding: 0.625rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: rgba(0, 0, 0, 0.87);
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }

           

        `;

       d.print(document.querySelector(".print-id"), [css]);
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
                                SERVICE UNIT :    {{ props.unit.data[0].unit_name }}
                            </div>
                        </v-card-title>
                    </v-card>
                    <v-card class="mb-3" height="600px" >
                      <v-card-body  class="overflow-visible">
                        <v-row class="p-5 " key="">
                        <v-col class="my-auto ml-5" v-if="unit.data[0].sub_units.length > 0" >
                            <vue-multiselect
                                v-model="form.selected_sub_unit"
                                prepend-icon="mdi-account"
                                :options="unit.data[0].sub_units"
                                :multiple="false"
                                placeholder="Select Sub Unit*"
                                label="sub_unit_name"
                                track-by="sub_unit_name"
                                :allow-empty="false"
                            >         
                            </vue-multiselect>           
                        </v-col>

                        <v-col class="my-auto mr-5 ml-5" v-if="unit_pstos.length > 0" >
                            <vue-multiselect
                                v-model="form.selected_unit_psto"
                                :options="unit_pstos"
                                :multiple="false"
                                placeholder="Select Unit PSTO"
                                label="psto_name"
                                track-by="psto_name"
                                :allow-empty="false"
                            >
                            </vue-multiselect>          
                        </v-col>

                        <v-col class="my-auto mr-5" v-if="sub_unit_pstos.length > 0" >
                            <vue-multiselect
                                v-model="form.selected_sub_unit_psto"
                                :options="sub_unit_pstos"
                                :multiple="false"
                                placeholder="Select Sub Unit PSTO"
                                label="psto_name"
                                track-by="psto_name"
                                :allow-empty="false"
                            >
                            </vue-multiselect>          
                        </v-col>      

                        <v-col class="my-auto" v-if="sub_unit_types.length > 0 && form.selected_sub_unit" >
                            <vue-multiselect
                                v-model="form.sub_unit_type"
                                :options="sub_unit_types"
                                :multiple="false"
                                placeholder="Select Sub Unit Type"
                                label="type_name"
                                track-by="type_name"
                                :allow-empty="false"
                            >
                            </vue-multiselect>          
                        </v-col>


                        <v-col class="my-auto text-right" >                            
                            <v-btn 
                            :disabled="unit.data[0].sub_units.length > 0  && form.selected_sub_unit == '' || 
                            sub_unit_pstos.length > 0 && form.selected_sub_unit_psto == '' || 
                            unit_pstos.length > 0 && form.selected_unit_psto == ''  || 
                            form.selected_sub_unit == 3 && form.sub_unit_type == '' " 
                            prepend-icon="mdi-plus"
                            @click="generateURL(form.selected_sub_unit, form.selected_unit_psto , form.selected_sub_unit_psto, form.sub_unit_type)" >Generate URL </v-btn>           
                        </v-col>
                        </v-row>
                        
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
                                :value="`${baseURL}/services/csf?region_id=${user.region_id}&service_id=${props.service.id}&unit_id=${props.unit.data[0].id}`"
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
                                v-if="qr_link_type == 1.1"
                                :render-as="'svg'"
                                :value="`${baseURL}/services/csf?region_id=${user.region_id}&service_id=${props.service.id}&unit_idk=${unit.data[0].id }&sub_unit_id=${form.selected_sub_unit.id}`"
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
                                v-if="qr_link_type == 1.2"
                                :render-as="'svg'"
                                :value="`${baseURL}/services/csf?region_id=${user.region_id}&service_id=${props.service.id}&unit_id=${unit.data[0].id }&sub_unit_id=${form.selected_sub_unit.id}&sub_unit_type=${form.sub_unit_type.id}`"
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
                                :value="`${baseURL}/services/csf?region_id=${user.region_id}&service_id=${props.service.id}&unit_id=${unit.data[0].id }&sub_unit_id=${form.selected_sub_unit.id}&psto_id=${form.selected_sub_unit_psto.id}`"
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
                                :value="`${baseURL}/services/csf?region_id=${user.region_id}&service_id=${props.service.id}&unit_id=${unit.data[0].id}&psto_id=${form.selected_unit_psto.id}`"
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
                        
                    </v-card-body>
                       </v-card>

            
                
                </div>
            </div>
        </div>
        <CSFPrint v-if="generated == true" :is_printing="is_printing"  :form="form"  :data="props" />
    </AppLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
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