<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import ModalForm from '@/Pages/Libraries/Assignatorees/Modal.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { reactive ,ref, watch, onMounted} from 'vue';
    import Swal from 'sweetalert2';
    
    const props = defineProps({
        assignatorees: Object, 
    });


    const show_modal = ref(false);
    const action_clicked = ref(null);

    const form = ref({});
    const assignatoree = ref({});
    const search = ref('');

    watch(
    () => search.value,
        (search) => {
            router.get('/assignatorees', { search },{ preserveState: true})
        }
        
    );
    
    const showAssignatoreeModal = async (is_show, action,assignatoree_data) => {
        show_modal.value = is_show;
        action_clicked.value = action;
        assignatoree.value = assignatoree_data;
    };

    const deleteRecord = async (id) => {

        Swal.fire({
            html: '<div style="font-weight: bold; font-size:25px">Are you sure you want to delete this record?</div> ',
            icon:'warning',
            
            showCancelButton: true,
            confirmButtonText: "Yes, I'm sure",
            showLoaderOnConfirm: true,
        }).then((result) => {
            if (result.isConfirmed) {            
                router.post('/assignatorees/delete', { id } );
            }
        });

    };

    const reloadAssignatorees = async () => {
        assignatoree.value = {};
    };

    const changePage = async (page) => {
        router.get('/assignatorees', { page, search: search.value }, { preserveState: true });
    };
</script>


<template>
    <AppLayout title="Assignatorees">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Assignatorees
            </h2>
        </template>

        <div class="container-fluid py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-11">
                    <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                        <div class="card-header text-white position-relative overflow-hidden d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 50%, #4facfe 100%); padding: 20px 25px;">
                            <div class="position-absolute top-0 end-0 p-3 opacity-25">
                                <i class="ri-team-line" style="font-size: 80px;"></i>
                            </div>
                            <div class="position-relative">
                                <h3 class="card-title mb-0">
                                    <i class="ri-team-line me-2"></i>
                                    Assignatorees Management
                                </h3>
                                <p class="mb-0 mt-1 opacity-75" style="font-size: 0.9rem;">Manage assignees and their assignments</p>
                            </div>
                            <div class="d-flex gap-3 align-items-center">
                                <div class="search-box">
                                    <div class="input-group input-group-sm" style="border-radius: 25px; overflow: hidden;">
                                        <span class="input-group-text bg-white border-0"><i class="ri-search-line text-muted"></i></span>
                                        <input type="text" class="form-control border-0 shadow-none" placeholder="Search..." v-model="search" style="border-radius: 0 25px 25px 0;">
                                    </div>
                                </div>
                                <button @click="showAssignatoreeModal(true, 'Add', null)" class="btn btn-light btn-sm fw-semibold" style="border-radius: 20px;">
                                    <i class="ri-add-line me-1"></i> Add Assignatoree
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="min-width: 600px;">
                                    <thead class="table-dark" style="background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);">
                                        <tr>
                                            <th class="text-start" style="width: 60px; border-radius: 0;">#</th>
                                            <th class="text-start">Name</th>
                                            <th class="text-start">Designation</th>
                                            <th class="text-center" style="width: 200px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr 
                                            v-for="(assignatoree,index) in assignatorees.data"
                                            :key="assignatoree.id"
                                            class="align-middle table-row-animated"
                                        >
                                            <template v-if="assignatoree">
                                                <td class="fw-bold text-muted">{{ index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="icon-circle me-2" style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, rgba(67, 233, 123, 0.2) 0%, rgba(56, 249, 215, 0.2) 100%); display: flex; align-items: center; justify-content: center;">
                                                            <i class="ri-user-line text-success"></i>
                                                        </div>
                                                        <span class="fw-semibold">{{ assignatoree.name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark" style="border-radius: 15px; padding: 5px 12px;">
                                                        {{ assignatoree.designation }}
                                                    </span>
                                                </td>

                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button @click="showAssignatoreeModal(true, 'Update' , assignatoree)" class="btn btn-primary" style="border-radius: 15px 0 0 15px;">
                                                            <i class="ri-edit-line me-1"></i> Update
                                                        </button>
                                                        <button @click="deleteRecord(assignatoree.id)" class="btn btn-danger" style="border-radius: 0 15px 15px 0;">
                                                            <i class="ri-delete-bin-line me-1"></i> Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </template>
                                            <template v-else>
                                                <td colspan="4"> No data at the moment</td>
                                            </template>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer bg-light" style="border-radius: 0 0 20px 20px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">
                                        Showing <span class="fw-semibold">{{ assignatorees.from }}</span> to <span class="fw-semibold">{{ assignatorees.to }}</span> out of
                                        <span class="fw-bold text-primary">{{ assignatorees.total }}</span> records
                                    </span>
                                    <nav>
                                        <ul class="pagination pagination-sm mb-0">
                                            <li v-for="page in assignatorees.last_page" :key="page" class="page-item" :class="{ active: page === assignatorees.current_page }">
                                                <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
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
            :assignatoree="assignatoree"
            :action="action_clicked"
            @input="showAssignatoreeModal"
            @reloadAssignatorees="reloadAssignatorees"
        ></ModalForm>
    </AppLayout>
</template>

<style scoped>
/* Enhanced Card Styles */
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
    box-shadow: 0 4px 20px rgba(67, 233, 123, 0.3);
    transform: scale(1.02);
}

/* Table Row Animations */
.table-row-animated {
    transition: all 0.3s ease;
}

.table-hover tbody tr:hover {
    background-color: rgba(67, 233, 123, 0.08) !important;
    transform: translateX(5px);
}

.table-hover tbody tr:hover td:first-child {
    border-left: 3px solid #43e97b;
}

/* Button Enhancements */
.btn-group .btn {
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
}

.btn-danger {
    background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
    border: none;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #e04860 0%, #d883fb 100%);
}

.btn-light {
    color: #43e97b;
    background: white;
    border: none;
}

.btn-light:hover {
    background: #f8f9fa;
    color: #38f9d7;
}

/* Pagination Enhancements */
.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    border-color: #43e97b;
    font-weight: bold;
}

.pagination .page-link {
    color: #4a5568;
    border-radius: 10px;
    margin: 0 3px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: rgba(67, 233, 123, 0.2);
    color: #43e97b;
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

/* Icon Circle */
.icon-circle {
    transition: all 0.3s ease;
}

.table-row-animated:hover .icon-circle {
    transform: scale(1.1);
    box-shadow: 0 0 15px rgba(67, 233, 123, 0.4);
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
