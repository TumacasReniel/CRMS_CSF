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

    action: {
        type: String,
    }

});

// watch(
//     () => props.account,
//     (value) => {
//         console.log(props.action, 55);
//         if(value && value.action == "Update"){
//             form.id = value.id;
//             form.name = value.name;
//             form.designation = value.designation;
//             form.email = value.email;
//             form.account_type = value.account_type;
//             form.region = value.region.id ;
//             form.service = value.service.id;
//             form.unit = value.unit.id;
//         }
//         else{
//             form.id = null;
//             form.name = null;
//             form.designation = null;
//             form.email = null;
//             form.account_type = null;
//             form.region = null ;
//             form.service = null;
//             form.unit = null;
//         }
//     }
     
// );

// const form = reactive({
//     id: null,
//     name:null,
//     email: null,
//     password: null,
//     designation:'',
//     region: [],
//     account_type: null,
//     service: null,
//     unit: null,
//     psto:null,
// });


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
    () => props.account.service,
    (value) => {
        if(value){
            units.value = fetchUnits(value);
        }
    }
);


watch(
    () => props.account.unit,
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
            region_id: props.account.region,
        }
    })
    .then(response => {
        pstos.value = response.data;
    })
    .catch(err => console.log(err));
};


  // Reactive variable to control password visibility
  const showPassword = ref(false);


const saveAccount = async () => {
   
   if(action_clicked.value == 'Add'){
       router.post('/accounts/add', props.account );

   }
   else if(action_clicked.value == 'Update'){
       router.post('/accounts/update', props.account );
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
                            v-model="account.name"
                            variant="outlined"
                        ></v-text-field>
                    </v-col>
                </v-row>
                

                <v-row style="margin-bottom:-30px;">
                    <v-col cols="12" >
                        <v-text-field
                            prepend-icon="mdi-email"
                            label="Email*"
                            v-model="account.email"
                            variant="outlined"
                            type="email"
                            required
                        ></v-text-field>
                    </v-col>
                </v-row>
                <v-row style="margin-bottom:-30px;">
                    <v-col cols="9">
                        <v-text-field
                            v-if="props.action == 'Add'"
                            prepend-icon="mdi-lock"
                            label="Password*"
                            v-model="account.password"
                            variant="outlined"
                            :type="showPassword ? 'text' : 'password'"
                            required
                        ></v-text-field>
                    </v-col>
                    <v-col cols="3">
                        <v-checkbox
                            v-if="props.action == 'Add'"
                            v-model="showPassword"
                            label="Show"
                        ></v-checkbox>
                    </v-col>
                </v-row>

                <v-row style="margin-bottom:-30px;">
                   <v-col cols="12">
                        <v-select
                            prepend-icon="mdi-map-marker"
                            label="Region*"
                            v-model="account.region"
                            variant="outlined"
                            :items="data.regions"
                            item-title="name"
                            item-value="id"
                            required
                        ></v-select>
                    </v-col>
                </v-row>

                 <v-row style="margin-bottom:-30px;">
                   <v-col cols="12">
                        <v-select
                            prepend-icon="mdi-account-circle"
                            label="Account_type*"
                            v-model="account.account_type"
                            variant="outlined"
                            :items="['user','admin','planning','special-user']"
                            item-title="name"
                            clearable
                            required
                        ></v-select>
                    </v-col>
                </v-row>

                <v-row style="margin-bottom:-30px;">
                    <v-col cols="12" >
                        <v-text-field
                            v-if="account.account_type == 'user' || account.account_type == 'special-user'"
                            prepend-icon="mdi-account"
                            label="Designation*"
                            v-model="account.designation"
                            variant="outlined"
                        ></v-text-field>
                    </v-col>
                </v-row>
                

                  <v-row style="margin-bottom:-30px;">
                   <v-col cols="12">
                        <v-select
                            v-if="account.account_type == 'user' || account.account_type == 'special-user'"
                            prepend-icon="mdi-map-marker"
                            label="Service*"
                            v-model="account.service"
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
                            v-if="account.account_type == 'user' || account.account_type == 'special-user' || account.service  "
                            v-model="account.unit"
                            variant="outlined"
                            :items="units"
                            item-title="unit_name"
                            item-value="id"
                            clearable
                            required
                        ></v-select>
                    </v-col>
                </v-row>

                <!-- <v-row style="margin-bottom:-30px;">
                    <v-col cols="12">
                            <v-select
                                prepend-icon="mdi-map-marker"
                                label="PSTO"
                                v-if="form.account_type == 'user' || form.account_type == 'special-user' || form.unit && account.region"
                                v-model="form.psto.id"
                                variant="outlined"
                                :items="pstos"
                                item-title="psto_name"
                                item-value="id"
                                clearable
                                required
                            ></v-select>
                        </v-col>
                </v-row> -->

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
