<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue'
    import ModalForm from '@/Pages/Account/Partials/Modal.vue'
    import { Head, Link, router } from '@inertiajs/vue3'
    import { reactive, ref, watch, onMounted } from 'vue'
    import Swal from 'sweetalert2'

    const props = defineProps({
        accounts: Object,
        regions: Object,
        services: Object,
    })

    const show_modal = ref(false)
    const action_clicked = ref(null)
    const form = ref({})
    const account = ref({})
    const search = ref('')
    const page_number = ref(1)
    const loading = ref(false)

    const resetPassword = async (id) => {
        const result = await Swal.fire({
            title: 'Reset Password',
            html: '<div style="font-weight: bold; font-size:18px">Are you sure you want to reset password for this account?</div>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Yes, I'm sure",
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        })

        if (result.isConfirmed) {
            router.post(
                '/accounts/reset-password',
                { id },
                {
                    onSuccess: () => {
                        Swal.fire({
                            title: 'Success',
                            icon: 'success',
                            text: 'Password has been successfully reset.',
                        })
                    },
                    onError: (error) => {
                        Swal.fire({
                            title: 'Failed',
                            icon: 'error',
                            text: error?.message || 'Something went wrong, please check',
                        })
                    },
                }
            )
        }
    }

    watch(
        () => search.value,
        (search) => {
            loading.value = true;
            router.get('/accounts', { search }, { preserveState: true, onFinish: () => { loading.value = false; } })
        }
    )

    const showAccountModal = async (is_show, action, user_data) => {

        console.log(is_show, 888);
        show_modal.value = is_show;
        action_clicked.value = action;
        if(user_data){
            account.value = user_data;
        }
   
    }

    const reloadAccounts = async () => {
        account.value = {}
    }

    const getAccounts = async (page) => {
        router.visit('/accounts?page=' + page, { preserveState: true });
        page_number.value = page;
    }

    const handleModalInput = (value) => {
        show_modal.value = value;
    };
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Accounts
            </h2>
        </template>

        <div class="container-fluid py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-11">
                    <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                        <div class="card-header text-white position-relative overflow-hidden d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%); padding: 20px 25px;">

                            <div class="position-relative">
                                <h3 class="card-title mb-0">
                                    <i class="ri-account-circle-line me-2"></i>
                                    Accounts Management
                                </h3>
                                <p class="mb-0 mt-1 opacity-75" style="font-size: 0.9rem;">Manage user accounts and permissions</p>
                            </div>
                            <div class="d-flex gap-3 align-items-center">
                            
                                <div class="search-box">
                                    <div class="input-group input-group-sm" style="border-radius: 25px; overflow: hidden;">
                                        <span class="input-group-text bg-white border-0"><i class="ri-search-line text-muted"></i></span>
                                        <input type="text" class="form-control border-0 shadow-none" placeholder="Search accounts..." v-model="search" style="border-radius: 0 25px 25px 0;">
                                    </div>
                                </div>

                                <div>
                                 <b-button @click="showAccountModal(true,'Add', null)" class="btn btn-success btn-sm fw-semibold" style="border-radius: 20px;">
                                    <i class="ri-add-line me-1"></i> Account
                                </b-button>
                                </div>
                            
                            </div>
                               
                        </div>

                       

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="min-width: 800px;">
                                    <thead class="table-dark" style="background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);">
                                        <tr>
                                            <th class="text-start" style="width: 60px;">#</th>
                                            <th class="text-start">Name</th>
                                            <th class="text-start">Designation</th>
                                            <th class="text-start">Email</th>
                                            <th class="text-start">Region</th>
                                            <th class="text-center" style="width: 150px;">Role/Account Type</th>
                                            <th class="text-center" style="width: 200px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(user, index) in accounts.data"
                                            :key="user.id"
                                            class="align-middle table-row-animated"
                                        >
                                            <template v-if="user">
                                                <td class="fw-bold text-muted">{{ index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="icon-circle me-2" style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%); display: flex; align-items: center; justify-content: center;">
                                                            <i class="ri-user-line text-purple"></i>
                                                        </div>
                                                        <span class="fw-semibold text-dark">{{ user.name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark" style="border-radius: 15px; padding: 5px 12px;">
                                                        {{ user.designation }}
                                                    </span>
                                                </td>
                                                <td class="text-muted">{{ user.email }}</td>
                                                <td>{{ user.region?.order }}</td>
                                                <td class="text-center">
                                                    <span
                                                        v-if="user.account_type == 'admin'"
                                                        class="badge"
                                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; padding: 5px 12px;"
                                                    >
                                                        {{ user.account_type }}
                                                    </span>
                                                    <span
                                                        v-else
                                                        class="badge"
                                                        style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); border-radius: 15px; padding: 5px 12px;"
                                                    >
                                                        {{ user.account_type }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button @click="resetPassword(user.id)" class="btn btn-secondary" style="border-radius: 15px 0 0 15px;">
                                                            <i class="ri-key-line me-1"></i> Reset
                                                        </button>
                                                        <button @click="showAccountModal(true, 'Update', user)" class="btn btn-primary" style="border-radius: 0 15px 15px 0;">
                                                            <i class="ri-edit-line me-1"></i> Update
                                                        </button>
                                                    </div>
                                                </td>
                                            </template>
                                            <template v-else>
                                                <td colspan="7"> 
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
                                        Showing <span class="fw-semibold">{{ accounts.meta?.from }}</span> to <span class="fw-semibold">{{ accounts.meta?.to }}</span> out of
                                        <span class="fw-bold" style="color: #667eea;">{{ accounts.meta?.total }}</span> records
                                    </span>
                                    <nav>
                                        <ul class="pagination pagination-sm mb-0">
                                            <li v-for="page in accounts.meta?.last_page" :key="page" class="page-item" :class="{ active: page === page_number }">
                                                <a class="page-link" href="#" @click.prevent="getAccounts(page)">{{ page }}</a>
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
            v-model="show_modal"
            :account="account"
            :data="props"
            :action="action_clicked"
            @reloadAccounts="reloadAccounts"
        />
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
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
    transform: scale(1.02);
}

/* Table Row Animations */
.table-row-animated {
    transition: all 0.3s ease;
}

.table-hover tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.08) !important;
    transform: translateX(5px);
}

.table-hover tbody tr:hover td:first-child {
    border-left: 3px solid #667eea;
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

.btn-secondary {
    background: #6c757d;
    border: none;
}

.btn-secondary:hover {
    background: #5a6268;
}

.btn-light {
    color: #667eea;
    background: white;
    border: none;
}

.btn-light:hover {
    background: #f8f9fa;
    color: #764ba2;
}

/* Pagination Enhancements */
.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    font-weight: bold;
}

.pagination .page-link {
    color: #4a5568;
    border-radius: 10px;
    margin: 0 3px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: rgba(102, 126, 234, 0.2);
    color: #667eea;
    transform: scale(1.1);
}

/* Icon Circle */
.icon-circle {
    transition: all 0.3s ease;
}

.table-row-animated:hover .icon-circle {
    transform: scale(1.1);
    box-shadow: 0 0 15px rgba(102, 126, 234, 0.4);
}

.text-purple {
    color: #667eea !important;
}

/* Badge Enhancements */
.badge {
    font-weight: 500;
    transition: all 0.3s ease;
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
