<script setup lang="ts">
import { reactive, watch, ref, onMounted } from "vue";
import { Head, Link, router } from '@inertiajs/vue3';
import VueMultiselect from "vue-multiselect";
const emit = defineEmits(["reloadUnitPSTOs", "input"]);
const props = defineProps({
    unit: {
        type: Object,
        default: null,
    },
    unit_pstos: {
        type: Object,
        default: null,
    },
    pstos: {
        type: Object,
        default: null,
    },
    value: {
        type: Boolean,
        default: false,
    },
    action: {
        type: String,
    },
    page_number: {
        type:Number,
    }


});

watch(
    () => props.unit,
    (value) => {
        if(value){
            form.unit_id = value.id;
            form.unit_name = value.unit_name;
            router.get('/unit-pstos?page='+ props.page_number ,{ form },{preserveState:true} );
        }
    }
     
);

watch(
    () => props.unit_pstos,
    (value) => {
        if(value){
            form.pstos = value.data;
        }
    }
     
);

const form = reactive({
    unit_id: null,
    unit_name:null,
    pstos:null,
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
   
    router.post('/unit-pstos/assign', form );

    emit("input", false);
    emit("reloadUnitPSTOs");

    form.id= '';
    form.pstos = null;
};



const closeDialog = (value) => {
    emit("input", value);
    emit("reloadUnitPSTOs");

    form.id= '';
    form.pstos = null;
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
                            label="Name*"
                            v-model="form.unit_name"
                            variant="outlined"
                        ></v-text-field>
                    </v-col>

                  <v-col class="my-auto mr-5 ml-5"  v-if="unit_pstos.data">
                    <label>PSTOs</label>
                    <vue-multiselect
                        v-model="form.pstos"
                        :options="pstos.data"
                        :multiple="true"
                        placeholder="Select Unit PSTO"
                        label="psto_name"
                        track-by="psto_name"
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