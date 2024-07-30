<script setup lang="ts">
import axios from 'axios';
import { reactive, watch, ref, onMounted } from "vue";
import { Head, Link, router } from '@inertiajs/vue3';
const emit = defineEmits(["reloadAccounts", "input"]);
const props = defineProps({
    data: {
        type: Object,
        default: null,
    },
    account: {
        type: Object,
        default: null,
    },
    value: {
        type: Boolean,
        default: false,
    },

    services: {
        type: Object,
        default: null,
    },

    action: {
        type: String,
    }

});

watch(
    () => props.account,
    (value) => {
        if(value && value.region && value.account_type && value.region && value.service && value.unit && value.psto && props.action == 'Update'){
            form.id = value.id;
            form.name = value.name;
            form.designation = value.designation;
            form.email = value.email;
            form.selected_account_type = value.account_type;
            form.selected_region = value.region.id;
            form.selected_service = value.service.id;
            form.selected_unit = value.unit.id;
            form.selected_psto = value.psto.id;    
        }
    }
     
);

const form = reactive({
    id: null,
    name:null,
    email: null,
    designation:'',
    selected_region: [],
    selected_account_type: null,
    selected_service: null,
    selected_unit: null,
    selected_psto:null,
});


const show_form_modal = ref(false);
watch(
    () => props.value,
    (value) => {
        show_form_modal.value = value;
    }
);


const action_clicked = ref('');
const units = ref();
const pstos = ref();

watch(
    () => props.action,
    (value) => {
        action_clicked.value = value;
    }
);


watch(
    () => form.selected_service,
    (value) => {
        if(value){
            units.value = fetchUnits(value);
        }
    }
);


watch(
    () => form.selected_unit,
    (value) => {
        if(value){    
            pstos.value = fetchPstos(value);
        }
    }
);


const fetchUnits = (code) => {
    axios.get('/service/units', {
        params: {
            option: 'units',
            code: code,
        }
    })
    .then(response => {
        units.value = response.data;
    })
    .catch(err => console.log(err));
};

const fetchPstos = (code) => {
    axios.get('/service/pstos', {
        params: {
            option: 'pstos',
            code: code,
            region_id: form.selected_region,
        }
    })
    .then(response => {
        pstos.value = response.data;
    })
    .catch(err => console.log(err));
};






const saveAccount = async () => {
   
   if(action_clicked.value == 'Add'){
       router.post('/accounts/add', form );

   }
   else if(action_clicked.value == 'Update'){
       router.post('/accounts/update', form );
   }
  
   emit("input", false);
   emit("reloadAccounts");
};

const closeDialog = (value) => {
    emit("input", value);
    emit("reloadAccounts");
};

</script>

<template>
    <v-dialog v-model="show_form_modal" width="600" scrollable persistent>
        <v-card>
            <v-card-title class="bg-indigo">
                <span class="text-h5">{{ props.action }} Account</span>
            </v-card-title>

            <v-card-text>                
                <v-row style="margin-bottom:-30px;">
                    <v-col cols="12" >
                        <v-text-field
                            prepend-icon="mdi-account"
                            label="Name*"
                            v-model="form.name"
                            variant="outlined"
                        ></v-text-field>
                    </v-col>
                </v-row>
                

                <v-row style="margin-bottom:-30px;">
                    <v-col cols="12" >
                        <v-text-field
                            prepend-icon="mdi-email"
                            label="Email*"
                            v-model="form.email"
                            variant="outlined"
                            type="email"
                            required
                        ></v-text-field>
                    </v-col>
                </v-row>

                <v-row style="margin-bottom:-30px;">
                   <v-col cols="12">
                        <v-select
                            prepend-icon="mdi-map-marker"
                            label="Region*"
                            v-model="form.selected_region"
                            variant="outlined"
                            :items="data.regions"
                            item-title="name"
                            item-value="id"
                            @change="setData()"
                            required
                        ></v-select>
                    </v-col>
                </v-row>

                 <v-row style="margin-bottom:-30px;">
                   <v-col cols="12">
                        <v-select
                            prepend-icon="mdi-account-circle"
                            label="Account_type*"
                            v-model="form.selected_account_type"
                            variant="outlined"
                            :items="['user','admin','planning']"
                            item-title="name"
                            clearable
                            required
                        ></v-select>
                    </v-col>
                </v-row>

                <v-row style="margin-bottom:-30px;">
                    <v-col cols="12" >
                        <v-text-field
                            v-if="form.selected_account_type == 'user'"
                            prepend-icon="mdi-account"
                            label="Designation*"
                            v-model="form.designation"
                            variant="outlined"
                        ></v-text-field>
                    </v-col>
                </v-row>
                

                  <v-row style="margin-bottom:-30px;">
                   <v-col cols="12">
                        <v-select
                            v-if="form.selected_account_type == 'user'"
                            prepend-icon="mdi-map-marker"
                            label="Service*"
                            v-model="form.selected_service"
                            variant="outlined"
                            :items="data.services"
                            item-title="services_name"
                            item-value="id"
                            clearable
                            required
                        ></v-select>
                        
                    </v-col>
                </v-row>

                <v-row style="margin-bottom:-30px;">
                    <v-col cols="12">
                        <v-select
                            prepend-icon="mdi-map-marker"
                            label="Unit*"
                            v-if="form.selected_account_type == 'user' || form.selected_service  "
                            v-model="form.selected_unit"
                            variant="outlined"
                            :items="units"
                            item-title="unit_name"
                            item-value="id"
                            clearable
                            required
                        ></v-select>
                    </v-col>
                </v-row>

                <v-row style="margin-bottom:-30px;">
                    <v-col cols="12">
                            <v-select
                                prepend-icon="mdi-map-marker"
                                label="PSTO*"
                                v-if="form.selected_account_type == 'user' || form.selected_unit && form.selected_region"
                                v-model="form.selected_psto"
                                variant="outlined"
                                :items="pstos"
                                item-title="psto_name"
                                item-value="id"
                                clearable
                                required
                            ></v-select>
                        </v-col>
                </v-row>

            </v-card-text>
            <v-spacer></v-spacer>
            <v-card-action>
                <v-divider></v-divider>
                <div style="text-align: end">
                    <v-btn
                        class="ma-2"
                        color="blue-grey-lighten-2"
                        @click="closeDialog(false)"
                    >
                        <v-icon start icon="mdi-cancel"></v-icon>
                        Cancel
                    </v-btn>
                    <v-btn
                        class="ma-2"
                        color="green-darken-1"
                        type="button"
                        @click="saveAccount()"
                    >
                        Save
                        <v-icon end icon="mdi-check"></v-icon>
                    </v-btn>
                </div>
            </v-card-action>
        </v-card>
    </v-dialog>
</template>
