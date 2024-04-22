<script setup lang="ts">
import { reactive, watch, ref, onMounted } from "vue";
import { Head, Link, router } from '@inertiajs/vue3';
import VueMultiselect from "vue-multiselect";
const emit = defineEmits(["reloadPSTOs", "input"]);
const props = defineProps({
    unit: {
        type: Object,
        default: null,
    },
    unit_pstos: {
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

watch(
    () => props.unit,
    (value) => {
        if(value){
            form.id = value.id;
            form.unit_name = value.unit_name;
            form.selected_unit = value.selected_unit;
            router.visit('/unit-pstos?' ,{preserveState:true} );
        }
    }
     
);

const form = reactive({
    id: null,
    unit_name:null,
    selected_unit:null,
});


const show_form_modal = ref(false);
watch(
    () => props.value,
    (value) => {
        show_form_modal.value = value;
    }
);


const action_clicked = ref('');
watch(
    () => props.action,
    (value) => {
        action_clicked.value = value;
    }
);

watch(
    () => form.selected_service,
    (value) => {
        form.selected_unit = null;
    }
);





const savePSTO = async () => {
   
    if(action_clicked.value == 'Add'){
        router.post('/pstos/add', form );
 
    }
    else if(action_clicked.value == 'Update'){
        router.post('/pstos/update', form );
    }
    else if(action_clicked.value == 'Delete'){
            Swal.fire({
                html: '<div style="font-weight: bold; font-size:25px">Are you sure you want to delete this record?</div> ',
                icon:'warning',
                
                showCancelButton: true,
                confirmButtonText: "Yes, I'm sure",
                showLoaderOnConfirm: true,
            }).then((result) => {
                if (result.isConfirmed) {            
                    router.post('/pstos/delete', form );
                }
            });

    }
    emit("input", false);
    emit("reloadAccounts");

    form.id= '';
    form.psto_name='';
};



const closeDialog = (value) => {
    emit("input", value);
    emit("reloadPSTOs");

    form.id= '';
    form.psto_name='';
};




</script>

<template>
    <v-dialog v-model="show_form_modal" width="600" height="800" scrollable persistent>
        <v-card>
            <v-card-title class="bg-indigo mb-5">
                <span class="text-h5">{{ props.action }} Unit PSTO</span>
            </v-card-title>
            <v-card-body>

                
                <v-row>
                    <v-col cols="11" class="m-3 " style="margin-bottom:-20px">
                        <v-text-field
                            prepend-icon="mdi-account"
                            label="Name*"
                            v-model="form.unit_name"
                            variant="outlined"
                        ></v-text-field>
                    </v-col>

                     <v-col cols="12" >
                        <label class="m-10">PSTOs</label>
                        <vue-multiselect
                            v-model="form.csi_type"
                            prepend-icon="mdi-account"
                            :options="['By Date','By Month', 'By Quarter', 'By Year/Annual']"
                            :multiple="true"
                            placeholder="Select *"
                            :allow-empty="false"
                        >         
                        </vue-multiselect>        
                    </v-col>

                   
                </v-row>

            </v-card-body>

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
                        @click="savePSTO()"
                    >
                        Save
                        <v-icon end icon="mdi-check"></v-icon>
                    </v-btn>
                </div>
            </v-card-action>
        </v-card>
    </v-dialog>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>