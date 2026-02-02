<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    service_units: Object,
    user: Object,
});

const rating = async (service_id, unit_id) => {
   const form = { service_id, unit_id };
   router.get('/csi', form , { preserveState: true });
};

const all_service_unit_rating = async () => {
   const form = { form_type: "all units" };
   router.get('/csi/all-units', form , { preserveState: true });
};

const goViewPage = async (service_id, unit_id) => {
   const form = { service_id, unit_id };
   router.get('/csi/view', form , { preserveState: true });
};
</script>

<template>
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
                    <tr class="table-primary" :data-aos="'fade-right'" :data-aos-delay="serviceIndex * 100">
                        <td colspan="3" class="fw-bold fs-5">
                            <i class="ri-service-line me-2"></i>
                            {{ service_unit.services_name }}
                            <button v-if="user.account_type == 'admin'"
                                    @click="$emit('showModal', 'add_new_unit', service_unit)"
                                    class="btn btn-success btn-sm ms-3">
                                <i class="ri-add-line"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Unit Rows -->
                    <tr v-for="(unit, unitIndex) in service_unit.units" :key="unit.id"
                        :data-aos="'fade-up'" :data-aos-delay="(serviceIndex * 100) + (unitIndex * 50) + 200"
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
</template>

<style scoped>
table {
    border-collapse: collapse;
    width: 100%;
    border: none;
}
tr, th, td {
    border: 1px solid none;
    padding: 8px;
}
</style>
