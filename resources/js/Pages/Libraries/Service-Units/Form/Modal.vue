<script setup lang="ts">
import { reactive, watch, ref, onMounted } from "vue";
import { Head, Link, router } from '@inertiajs/vue3';
const emit = defineEmits(["input"]);
const props = defineProps({
    data: {
        type: Object,
        default: null,
    },
    value: {
        type: Boolean,
        default: false,
    },

    action_clicked: {
        type: String,
        default: null,
    },

    selected_service: {
        type: Object,
        default: null,
    },

});

watch(
    () => props.account,
    (value) => {
        if(value){
            form.id = value.id;
            form.name = value.name;
            form.email = value.email;
            form.selected_region = value.region;
        }
    }
     
);

const form = reactive({
    service_id: null,
    service_name:null,
    unit_name:null,
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

const closeDialog = (value) => {
    emit("input", value);
};

const saveService = () => {
    if(props.actioned_clicked == 'add_new_service'){
          router.post('/services/add', form );
    }
    else{
        form.service_id = props.selected_service.id;
        router.post('/services/unit/add', form );
    }
   
    emit("input", false);
};





</script>

<template>
    <v-dialog v-model="show_form_modal" width="600" scrollable persistent>
        <v-card>
            <v-card-title class="bg-indigo mb-5">
                <span class="text-h5" v-if="props.action_clicked == 'add_new_service'">Add New Service</span>
                 <span class="text-h5" v-if="props.action_clicked == 'add_new_unit'">Add New Unit</span>
            </v-card-title>
            <v-card-text>

                
            <v-row style="margin-bottom:-30px;" v-if="props.action_clicked=='add_new_service'">
                <v-col cols="12" >
                    <v-text-field
                        prepend-icon="mdi-account"
                        label="Service Name"
                        v-model="form.service_name"
                        variant="outlined"
                    ></v-text-field>
                </v-col>
            </v-row>

            <v-row style="margin-bottom:-30px;" v-if="props.action_clicked=='add_new_unit'">
                <v-col cols="12" >
                      <v-text-field
                        prepend-icon="mdi-account"
                        label="Service"
                        v-model="props.selected_service.services_name"
                        variant="outlined"
                    ></v-text-field>
                </v-col>
                 <v-col cols="12" >
                      <v-text-field
                        prepend-icon="mdi-account"
                        label="Unit Name"
                        v-model="form.unit_name"
                        variant="outlined"
                    ></v-text-field>
                </v-col>
            </v-row>



            </v-card-text>
            <v-spacer></v-spacer>
            <v-card-action >
                <v-divider></v-divider>
                <div style="text-align: center">
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
                        @click="saveService()"
                    >
                        Save
                        <v-icon end icon="mdi-check"></v-icon>
                    </v-btn>
                </div>
            </v-card-action>
        </v-card>
    </v-dialog>
</template>
