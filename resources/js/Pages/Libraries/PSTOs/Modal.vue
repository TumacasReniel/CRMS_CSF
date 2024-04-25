<script setup lang="ts">
import { reactive, watch, ref, onMounted } from "vue";
import { Head, Link, router } from '@inertiajs/vue3';
const emit = defineEmits(["reloadPSTOs", "input"]);
const props = defineProps({
    psto: {
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
    () => props.psto,
    (value) => {
        if(value){
            form.id = value.id;
            form.psto_name = value.psto_name;
        }
    }
     
);

const form = reactive({
    id: null,
    name:null,
    code:null,
    short_name:null,
    order:null,
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
    <v-dialog v-model="show_form_modal" width="600" scrollable persistent>
        <v-card>
            <v-card-title class="bg-indigo">
                <span class="text-h5">{{ props.action }} PSTO</span>
            </v-card-title>
            <v-card-text>

                
                <v-row style="margin-bottom:-30px;">
                    <v-col cols="12" >
                        <v-text-field
                            prepend-icon="mdi-account"
                            label="Name*"
                            v-model="form.psto_name"
                            variant="outlined"
                        ></v-text-field>
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
