<script setup>
import VueMultiselect from "vue-multiselect";
import AppLayout from '@/Layouts/AppLayout.vue';
import AddServiceModal from '@/Components/AddServiceModal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive ,ref, watch} from 'vue'

const props = defineProps({
    service_units: Object,
    sub_units: Object,
    user: Object,
});

const form = reactive({
  service_id: null,
  unit_id: null,
})

const rating = async (service_id, unit_id) => {

   form.service_id = service_id;
   form.unit_id = unit_id;
   router.get('/csi', form , { preserveState: true });
};

const all_service_unit_rating = async () => {
   form.form_type = "all units";
   router.get('/csi/all-units', form , { preserveState: true })
};

const show_modal = ref(false);
const action_clicked = ref('');
const selected_service = ref({});


const goViewPage = async (service_id, unit_id) => {
   form.service_id = service_id;
   form.unit_id = unit_id;
   router.get('/csi/view', form , { preserveState: true });

};

const showServiceModal = async (is_show, action , service) => {
    show_modal.value =is_show;
    action_clicked.value = action;
    if(service){
        selected_service.value = service;
    }
};

const openPDF = () => {
    // Replace 'path/to/your/pdf/file.pdf' with the actual path to your PDF file
    const pdfPath = 'https://drive.google.com/file/d/1s7hgXu2_3znCrcKrXX0PWJUQfwb7SMWU/view?usp=sharing';

    // Open the PDF in a new tab or window
    window.open(pdfPath, '_blank');
};
</script>

<template>
    <AppLayout title="Service Units">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Service Units
            </h2>
        </template>

        <div class="container-fluid py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="card shadow-lg border-0" data-aos="fade-up">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">
                                <i class="ri-building-4-line me-2"></i>
                                Service Units Management
                            </h4>
                            <div class="d-flex gap-2">
                                <button v-if="user.account_type == 'admin'" @click="showServiceModal(true, 'add_new_service', null)"
                                        class="btn btn-light btn-sm">
                                    <i class="ri-add-line me-1"></i>Add New Service
                                </button>
                                <button @click="all_service_unit_rating()" class="btn btn-warning btn-sm">
                                    <i class="ri-file-chart-line me-1"></i>All Unit Ratings
                                </button>
                                <button @click="openPDF()" class="btn btn-success btn-sm">
                                    <i class="ri-printer-line me-1"></i>CSF Form (Manual)
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center" style="width: 80px;">#</th>
                                            <th>Unit Name</th>
                                            <th class="text-center" style="width: 200px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-if="service_units" v-for="(service_unit, serviceIndex) in service_units.data" :key="service_unit.id">
                                            <!-- Service Header Row -->
                                            <tr class="table-primary">
                                                <td colspan="3" class="fw-bold fs-5">
                                                    <i class="ri-service-line me-2"></i>
                                                    {{ service_unit.services_name }}
                                                    <button v-if="user.account_type == 'admin'"
                                                            @click="showServiceModal(true, 'add_new_unit', service_unit)"
                                                            class="btn btn-success btn-sm ms-3">
                                                        <i class="ri-add-line"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Unit Rows -->
                                            <tr v-for="(unit, unitIndex) in service_unit.units" :key="unit.id"
                                                class="align-middle">
                                                <td class="text-center fw-bold">{{ unitIndex + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="ri-building-line text-primary me-2"></i>
                                                        {{ unit.unit_name }}
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button @click="goViewPage(service_unit.id, unit.id)"
                                                                class="btn btn-primary me-2"
                                                                :disabled="user.account_type == 'user' && user.unit_id != unit.id">
                                                            <i class="ri-eye-line"></i> View
                                                        </button>
                                                        <button @click="rating(service_unit.id, unit.id)"
                                                                class="btn btn-warning"
                                                                :disabled="user.account_type == 'user' && user.unit_id != unit.id">
                                                            <i class="ri-star-line"></i> Rating
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <AddServiceModal
            :value="show_modal"
            :action-clicked="action_clicked"
            :selected-service="selected_service"
            @input="showServiceModal"
        />
    </AppLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<style scoped>
</style>

<style scoped>
   table {
    border-collapse: collapse;
    width: 100%; /* Optional: Set a width for the table */
    border: none;
  }
  tr, th,td {
    border: 1px solid none; /* Optional: Add a border for better visibility */
    padding: 8px; /* Optional: Add padding for better spacing */
  }


  
</style>
