<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import ModalForm from '@/Pages/Libraries/Regions/Modal.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { reactive ,ref, watch, onMounted} from 'vue';
    import Swal from 'sweetalert2';
    
    const props = defineProps({
        regions: Object, 
    });


    const show_modal = ref(false);
    const action_clicked = ref(null);

    const form = ref({});
    const region = ref({});
    const search = ref('');

    watch(
    () => search.value,
        (search) => {
            router.get('/regions', { search },{ preserveState: true})
        }
        
    );
    
    const showRegionModal = async (is_show, action,region_data) => {
        show_modal.value = is_show;
        action_clicked.value = action;
        region.value = region_data;
    };

    const reloadRegions = async () => {
        region.value = {};
    };

    const getRegions = async (page) => {
        router.visit('/regions?page=' + page, { preserveState: true });
    };

    const handleModalInput = (value) => {
        showRegionModal(value, action_clicked.value, region.value);
    };
</script>


<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Regions
            </h2>
        </template>
        
        <div class="container-fluid py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-11">
                    <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                        <div class="card-header text-white position-relative overflow-hidden d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 50%, #fa7268 100%); padding: 20px 25px;">
                            <div class="position-absolute top-0 end-0 p-3 opacity-25">
                                <i class="ri-map-pin-line" style="font-size: 80px;"></i>
                            </div>
                            <div class="position-relative">
                                <h3 class="card-title mb-0">
                                    <i class="ri-map-pin-line me-2"></i>
                                    Regions Management
                                </h3>
                                <p class="mb-0 mt-1 opacity-75" style="font-size: 0.9rem;">Manage regional configurations</p>
                            </div>
                            <div class="d-flex gap-3 align-items-center">
                                <div class="search-box">
                                    <div class="input-group input-group-sm" style="border-radius: 25px; overflow: hidden;">
                                        <span class="input-group-text bg-white border-0"><i class="ri-search-line text-muted"></i></span>
                                        <input type="text" class="form-control border-0 shadow-none" placeholder="Search..." v-model="search" style="border-radius: 0 25px 25px 0;">
                                    </div>
                                </div>
                                <button @click="showRegionModal(true, 'Add', null)" class="btn btn-light btn-sm fw-semibold" style="border-radius: 20px;">
                                    <i class="ri-add-line me-1"></i> Region
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="min-width: 700px;">
                                    <thead class="table-dark" style="background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);">
                                        <tr>
                                            <th class="text-start" style="width: 60px;">#</th>
                                            <th class="text-start">Name</th>
                                            <th class="text-start">Code</th>
                                            <th class="text-start">Short Name</th>
                                            <th class="text-start">Order</th>
                                            <th class="text-center" style="width: 120px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr 
                                            v-for="(region,index) in regions.data"
                                            :key="region.id"
                                            class="align-middle table-row-animated"
                                        >
                                            <template v-if="region">
                                                <td class="fw-bold text-muted">{{ index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="icon-circle me-2" style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, rgba(240, 147, 251, 0.2) 0%, rgba(245, 87, 108, 0.2) 100%); display: flex; align-items: center; justify-content: center;">
                                                            <i class="ri-map-pin-line text-pink"></i>
                                                        </div>
                                                        <span class="fw-semibold">{{ region.name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-gradient-primary text-white" style="border-radius: 15px; padding: 5px 12px;">
                                                        {{ region.code }}
                                                    </span>
                                                </td>
                                                <td>{{ region.short_name }}</td>
                                                <td>
                                                    <span class="badge bg-light text-dark" style="border-radius: 15px; padding: 5px 12px;">
                                                        {{ region.order }}
                                                    </span>
                                                </td>

                                                <td class="text-center">
                                                    <button @click="showRegionModal(true, 'Update' , region)" class="btn btn-primary btn-sm" style="border-radius: 20px;">     
                                                        <i class="ri-edit-line me-1"></i> Update                         
                                                    </button>
                                                </td>
                                            </template>
                                            <template v-else>
                                                <td colspan="6"> No data at the moment</td>
                                            </template>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer bg-light" style="border-radius: 0 0 20px 20px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">
                                        Showing <span class="fw-semibold">{{ regions.from }}</span> to <span class="fw-semibold">{{ regions.to }}</span> out of
                                        <span class="fw-bold" style="color: #f093fb;">{{ regions.total }}</span> records
                                    </span>
                                    <nav>
                                        <ul class="pagination pagination-sm mb-0">
                                            <li v-for="page in regions.last_page" :key="page" class="page-item" :class="{ active: page === regions.current_page }">
                                                <a class="page-link" href="#" @click.prevent="getRegions(page)">{{ page }}</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ModalForm 
            :value="show_modal"
            :region="region"
            :action="action_clicked"
            @input="showRegionModal"
            @reloadRegions="reloadRegions"
        ></ModalForm>
    </AppLayout>
</template>

<style scoped>
/* Card Enhancements */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15) !important;
}

/* Search Box Enhancements */
.search-box .input-group {
    background: white;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.search-box .input-group:focus-within {
    box-shadow: 0 4px 20px rgba(240, 147, 251, 0.3);
    transform: scale(1.02);
}

/* Table Row Animations */
.table-row-animated {
    transition: all 0.3s ease;
}

.table-hover tbody tr:hover {
    background-color: rgba(240, 147, 251, 0.08) !important;
    transform: translateX(5px);
}

.table-hover tbody tr:hover td:first-child {
    border-left: 3px solid #f093fb;
}

/* Button Enhancements */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-light {
    color: #f5576c;
    background: white;
    border: none;
}

.btn-light:hover {
    background: #f8f9fa;
    color: #f093fb;
}

/* Pagination Enhancements */
.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-color: #f093fb;
    font-weight: bold;
}

.pagination .page-link {
    color: #4a5568;
    border-radius: 10px;
    margin: 0 3px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: rgba(240, 147, 251, 0.2);
    color: #f093fb;
    transform: scale(1.1);
}

/* Badge Enhancements */
.badge {
    font-weight: 500;
    transition: all 0.3s ease;
}

.table-row-animated:hover .badge {
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Icon Circle */
.icon-circle {
    transition: all 0.3s ease;
}

.table-row-animated:hover .icon-circle {
    transform: scale(1.1);
    box-shadow: 0 0 15px rgba(240, 147, 251, 0.4);
}

.text-pink {
    color: #f093fb !important;
}

/* Responsive */
@media (max-width: 768px) {
    .card-header {
        flex-direction: column !important;
        gap: 15px !important;
    }
    
    .card-header > div:last-child {
        width: 100%;
        flex-direction: column;
    }
    
    .search-box {
        width: 100%;
    }
}
</style>
