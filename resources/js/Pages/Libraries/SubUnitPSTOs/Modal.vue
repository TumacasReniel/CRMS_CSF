<script setup lang="ts">
import { reactive, watch, ref, onMounted } from "vue";
import { Head, Link, router } from '@inertiajs/vue3';
import VueMultiselect from "vue-multiselect";
const emit = defineEmits(["reloadSubUnitPSTOs", "input"]);
const props = defineProps({
    sub_unit: {
        type: Object,
        default: null,
    },
    sub_unit_pstos: {
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
    () => props.sub_unit,
    (value) => {
        if(value){
            form.sub_unit_id = value.id;
            form.sub_unit_name = value.sub_unit_name;
            router.get('/sub-unit-pstos?page='+ props.page_number ,{ form },{preserveState:true} );
        }
    }
     
);

watch(
    () => props.sub_unit_pstos,
    (value) => {
        if(value){
            form.pstos = value.data;
        }
    }
     
);

const form = reactive({
    sub_unit_id: null,
    sub_unit_name:null,
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
        form.selected_sub_unit = null;
    }
);





const savePSTO = async () => {
   
    router.post('/sub-unit-pstos/assign', form );

    emit("input", false);
    emit("reloadSubUnitPSTOs");

    form.sub_unit_id= null;
    form.pstos = null;
};



const closeDialog = (value) => {
    emit("input", value);
    emit("reloadSubUnitPSTOs");

    form.sub_unit_id= null;
    form.pstos = null;
};




</script>

<template>
    <v-dialog v-model="show_form_modal" width="600" height="800" scrollable persistent>
        <v-card>
            <v-card-title class="bg-indigo mb-5">
                <span class="text-h5">{{ props.action }} SubUnit PSTO</span>
            </v-card-title>
            <v-card-body>

                <v-row>
                    <v-col cols="11" class="m-3 " style="margin-bottom:-20px">
                        <v-text-field
                            label="Name*"
                            v-model="form.sub_unit_name"
                            variant="outlined"
                        ></v-text-field>
                    </v-col>

                  <v-col class="my-auto mr-5 ml-5"  v-if="sub_unit_pstos.data">
                    <label>PSTOs</label>
                    <vue-multiselect
                        v-model="form.pstos"
                        :options="pstos.data"
                        :multiple="true"
                        placeholder="Select SubUnit PSTO"
                        label="psto_name"
                        track-by="psto_name"
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