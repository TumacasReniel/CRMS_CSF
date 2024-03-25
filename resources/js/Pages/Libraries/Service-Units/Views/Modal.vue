
<script setup>
    import { Link } from '@inertiajs/vue3';
    import { reactive ,ref, watch} from 'vue'
    import QrcodeVue from "qrcode.vue";

    const emit = defineEmits(["input"]);
    const props = defineProps({
        show_modal: {
            type: Boolean,
        },
        data: {
            type: Object,
        },
        form: {
            type: Object,
            default: null,
        },
    });

    

    const show_form_modal = ref(false);
    watch(
    () => props.show_modal,
        (value) => {
            show_form_modal.value = value;
        }
    );

    const closeDialog = (value) => {
        emit("input", value);
    };

    const baseURL = window.location.origin;

</script>

<template>
     <v-dialog v-model="show_form_modal"  width="900" scrollable persistent v-if="show_modal">
    <v-card  >
      <v-card-title class="bg-primary">
        <div style="display:flex;justify-content: space-between">
            <span class="text-h5">
                {{ form.service.services_name }}         
            </span>
            <span class="text-h5">
              <v-btn color="primary" @click="closeDialog(false)">x</v-btn>   
            </span>

        </div>
      </v-card-title>

      <v-card-text>
        <form class="space-y-6" @submit.prevent="">
          <table class="w-100">
            <tr class="bg-gray-300">
                <th >Services Unit Name</th>
                <td>{{ form.unit.unit_name}}</td>
          </tr>
          <tr>
            <th>Url</th>

            <td class="text-primary">
                <a v-if="form.has_sub_unit && form.has_unit_psto == false && form.has_sub_unit_psto == false" class="hover:underline" :href="`${baseURL}/services/csf?region_id=${data.user.region_id}service_id=${form.service.id}&unit_id=${form.unit.id}&sub_unit_id=${form.sub_unit.id}`">
                   {{ baseURL }}/services/csf?region_id={{ data.user.region_id }}&service_id={{ form.service.id }}&unit_id={{ form.unit.id }}&sub_unit_id={{ form.sub_unit.id }}
                </a>
                <a v-else-if="form.has_sub_unit && form.has_unit_psto == true && form.psto" class="hover:underline" :href="`${baseURL}/services/csf?region_id=${data.user.region_id}service_id=${form.service.id}&unit_id=${form.unit.id}&psto_id=${form.psto.id}`">
                   {{ baseURL }}/services/csf?region_id={{ data.user.region_id }}&service_id={{ form.service.id }}&unit_id={{ form.unit.id }}&psto_id={{ form.psto.id }}
                </a>

                <a v-else-if="form.has_sub_unit == true && form.has_psto == true && form.psto" class="hover:underline" :href="`${baseURL}/services/csf?region_id=${data.user.region_id}service_id=${form.service.id}&unit_id=${form.unit.id}&sub_unit_id=${form.sub_unit.id}&psto_id=${form.psto.id}`">
                   {{ baseURL }}/services/csf?region_id={{ data.user.region_id }}&service_id={{ form.service.id }}&unit_id={{ form.unit.id }}&sub_unit_id={{ form.sub_unit.id }}&psto_id={{ form.psto.id }}
                </a>
                
                <a v-else class="hover:underline" :href="`${baseURL}/services/csf?region_id=${data.user.region_id}service_id=${form.service.id}&unit_id=${form.unit.id}`">
                   {{ baseURL }}/services/csf?region_id={{ data.user.region_id }}&service_id={{ form.service.id }}&unit_id={{ form.unit.id }}
                </a>
            </td>
          </tr>
          </table>

          <div  style="  display: flex;justify-content: center;align-items: center;">
            <QrcodeVue
                v-if="form.has_sub_unit == true && form.has_psto == false"
                :render-as="'svg'"
                :value="`${baseURL}/services/csf?region_id=${data.user.region_id}&service_id=${form.service.id}&unit_id=${form.unit.id}&sub_unit_id=${form.sub_unit.id}`"
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
                v-else-if="form.has_sub_unit == true && form.has_psto == true && form.psto"
                :render-as="'svg'"
                :value="`${baseURL}/services/csf?region_id=${data.user.region_id}&service_id=${form.service.id}&unit_id=${form.unit.id}&sub_unit_id=${form.sub_unit.id}&psto_id=${form.psto.id}`"
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
                v-else
                :render-as="'svg'"
                :value="`${baseURL}/services/csf?region_id=${data.user.region_id}&service_id=${form.service.id}&unit_id=${form.unit.id}`"
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
          <v-spacer></v-spacer>

        </form>
      </v-card-text>
    </v-card>
  </v-dialog>
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