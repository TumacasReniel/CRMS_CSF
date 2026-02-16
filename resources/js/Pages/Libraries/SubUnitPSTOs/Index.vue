<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Pagination from '@/Shared/Pagination.vue';
    import ModalForm from '@/Pages/Libraries/SubUnitPSTOs/Modal.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { reactive ,ref, watch, onMounted} from 'vue';
    import Swal from 'sweetalert2';
    
    
    const props = defineProps({
        sub_units: Object, 
        sub_unit_pstos: Object, 
        pstos: Object,
    });


    const show_modal = ref(false);
    const action_clicked = ref(null);

    const form = ref({});
    const sub_unit = ref({});
    const search = ref('');
    const loading = ref(false);

    watch(
    () => search.value,
        (search) => {
            loading.value = true;
            router.get('/sub-unit-pstos', { search },{ preserveState: true, onFinish: () => { loading.value = false; } })
        }
        
    );
    
    const showSubUnitPSTOModal = async (is_show, action,data) => {
        show_modal.value = is_show;
        action_clicked.value = action;
        sub_unit.value = data;
        
    };




    const deleteRecord = async (data) => {

        Swal.fire({
            html: '<div style="font-weight: bold; font-size:25px">Are you sure you want to delete this record?</div> ',
            icon:'warning',
            
            showCancelButton: true,
            confirmButtonText: "Yes, I'm sure",
            showLoaderOnConfirm: true,
        }).then((result) => {
            if (result.isConfirmed) {            
                router.post('/sub-unit-pstos/delete', data );
            }
        });

    };

 

    const reloadSubUnits = async () => {
        sub_unit.value = {};
    };


    let page_number = 1;
    const getSubUnits = async (page) => {
       router.visit('/sub-unit-pstos?page=' + page , { preserveState: true});
       page_number = page;
    };

    const handleModalInput = (value) => {
        showSubUnitPSTOModal(value, action_clicked.value, sub_unit.value);
    };
</script>


<template>
    <AppLayout title="SubUnit PSTOs">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                SubUnit PSTOs
            </h2>
        </template>

        <div class="container-fluid py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-11">
                    <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                        <div class="card-header text-white position-relative overflow-hidden d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #00bcd4 0%, #00acc1 50%, #00838f 100%); padding: 20px 25px;">
                            <div class="position-absolute top-0 end-0 p-3 opacity-25">
                                <i class="ri-map-pin-2-line" style="font-size: 80px;"></i>
                            </div>
                            <div class="position-relative">
                                <h3 class="card-title mb-0">
                                    <i class="ri-map-pin-2-line me-2"></i>
                                    SubUnit PSTOs Management
                                </h3>
                                <p class="mb-0 mt-1 opacity-75" style="font-size: 0.9rem;">Manage sub-unit PSTO mappings</p>
                            </div>
                            <div class="d-flex gap-3 align-items-center">
                                <div class="search-box">
                                    <div class="input-group input-group-sm" style="border-radius: 25px; overflow: hidden;">
                                        <span class="input-group-text bg-white border-0"><i class="ri-search-line text-muted"></i></span>
                                        <input type="text" class="form-control border-0 shadow-none" placeholder="Search sub-units..." v-model="search" style="border-radius: 0 25px 25px 0;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="min-width: 500px;">
                                    <thead class="table-dark" style="background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);">
                                        <tr>
                                            <th class="text-start" style="width: 60px;">#</th>
                                            <th class="text-left">SubUnit Name</th>
                                            <th class="text-center" style="width: 150px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr 
                                            v-for="(sub_unit,index) in sub_units.data"
                                            :key="sub_unit.id"
                                            class="align-middle table-row-animated"
                                        >
                                            <template v-if="sub_unit">
                                                <td class="fw-bold text-muted">{{ index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="icon-circle me-2" style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, rgba(0, 188, 212, 0.2) 0%, rgba(0, 172, 193, 0.2) 100%); display: flex; align-items: center; justify-content: center;">
                                                            <i class="ri-building-2-line text-cyan"></i>
                                                        </div>
                                                        <span class="fw-semibold text-dark">{{ sub_unit.sub_unit_name }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <button @click="showSubUnitPSTOModal(true, 'Assign' , sub_unit)" class="btn btn-primary btn-sm" style="border-radius: 20px;">
                                                        <i class="ri-add-circle-line me-1"></i> Assign
                                                    </button>
                                                </td>
                                            </template>
                                            <template v-else>
                                                <td colspan="3"> 
                                                    <div class="text-center py-4">
                                                        <i class="ri-inbox-line text-muted" style="font-size: 40px;"></i>
                                                        <p class="text-muted mb-0 mt-2">No data at the moment</p>
                                                    </div>
                                                </td>
                                            </template>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer bg-light" style="border-radius: 0 0 20px 20px;">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <span class="text-muted">
                                        Showing <span class="fw-semibold">{{ sub_units.from }}</span> to <span class="fw-semibold">{{ sub_units.to }}</span> out of
                                        <span class="fw-bold" style="color: #00bcd4;">{{ sub_units.total }}</span> records
                                    </span>
                                    <nav>
                                        <ul class="pagination pagination-sm mb-0">
                                            <li v-for="page in sub_units.last_page" :key="page" class="page-item" :class="{ active: page === page_number }">
                                                <a class="page-link" href="#" @click.prevent="getSubUnits(page)">{{ page }}</a>
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
            :sub_unit="sub_unit"
            :sub_unit_pstos="sub_unit_pstos"
            :pstos="pstos"
            :action="action_clicked"
            :page_number="page_number"
            @input="showSubUnitPSTOModal"
            @reloadSubUnits="reloadSubUnits"
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
    min-width: 250px;
}

.search-box .input-group:focus-within {
    box-shadow: 0 4px 20px rgba(0, 188, 212, 0.3);
    transform: scale(1.02);
}

/* Table Row Animations */
.table-row-animated {
    transition: all 0.3s ease;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 188, 212, 0.08) !important;
    transform: translateX(5px);
}

.table-hover tbody tr:hover td:first-child {
    border-left: 3px solid #00bcd4;
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

/* Pagination Enhancements */
.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #00bcd4 0%, #00acc1 100%);
    border-color: #00bcd4;
    font-weight: bold;
}

.pagination .page-link {
    color: #4a5568;
    border-radius: 10px;
    margin: 0 3px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: rgba(0, 188, 212, 0.2);
    color: #00bcd4;
    transform: scale(1.1);
}

/* Icon Circle */
.icon-circle {
    transition: all 0.3s ease;
}

.table-row-animated:hover .icon-circle {
    transform: scale(1.1);
    box-shadow: 0 0 15px rgba(0, 188, 212, 0.4);
}

.text-cyan {
    color: #00bcd4 !important;
}

/* Empty State */
.text-muted {
    color: #6c757d !important;
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
    
    .search-box .input-group {
        min-width: 100%;
    }
}
</style>
