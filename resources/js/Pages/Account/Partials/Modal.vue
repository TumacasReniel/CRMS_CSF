<script setup>
import axios from 'axios';
import { reactive, watch, ref, onMounted } from "vue";
import { Head, Link, router } from '@inertiajs/vue3';

const emit = defineEmits(["reloadAccounts", "update:modelValue"]);
const props = defineProps({
    data: {
        type: Object,
        default: null,
    },
    account: {
        type: Object,
        default: null,
    },
    modelValue: {
        type: Boolean,
        default: false,
    },
    action: {
        type: String,
    }
});

const form = reactive({
    id: null,
    name: null,
    email: null,
    password: null,
    designation: null,
    region: null,
    account_type: null,
    service: null,
    unit: null,
    psto: null,
});

watch(
    () => props.account,
    (value) => {
        if (value && props.action == "Update") {
            form.id = value.id;
            form.name = value.name;
            form.designation = value.designation;
            form.email = value.email;
            form.account_type = value.account_type;
            form.region = value.region ? value.region.id : null;
            form.service = value.service ? value.service.id : null;
            form.unit = value.unit ? value.unit.id : null;
            form.psto = value.psto ? value.psto.id : null;
        } else {
            // Reset form for new account
            form.id = null;
            form.name = null;
            form.designation = null;
            form.email = null;
            form.region = null;
            form.account_type = null;
            form.service = null;
            form.unit = null;
            form.psto = null;
            form.password = null;
        }
    },
    { immediate: true }
);

const show_form_modal = ref(false);
watch(
    () => props.modelValue,
    (value) => {
        show_form_modal.value = value;
    },
    { immediate: true }
);

const action_clicked = ref('');
const units = ref([]);
const pstos = ref([]);

watch(
    () => props.action,
    (value) => {
        action_clicked.value = value;
    },
    { immediate: true }
);

watch(
    () => form.service,
    (newVal, oldVal) => {
        if (newVal && newVal !== oldVal) {
            fetchUnits(newVal);
        }
    }
);

watch(
    () => form.unit,
    (newVal, oldVal) => {
        if (newVal && newVal !== oldVal) {
            fetchPstos(newVal);
        }
    }
);

const fetchUnits = (serviceId) => {
    if (!serviceId) return;
    axios.get('/service/units', {
        params: {
            option: 'units',
            code: serviceId,
        }
    })
    .then(response => {
        units.value = response.data;
    })
    .catch(err => console.log(err));
};

const fetchPstos = (unitId) => {
    if (!unitId) return;
    axios.get('/service/pstos', {
        params: {
            option: 'pstos',
            code: unitId,
            region_id: form.region,
        }
    })
    .then(response => {
        pstos.value = response.data;
    })
    .catch(err => console.log(err));
};

const showPassword = ref(false);
const isLoading = ref(false);

const saveAccount = async () => {
    // Validation
    if (!form.name || !form.email || !form.region || !form.account_type) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Fields',
            text: 'Please fill in all required fields',
        });
        return;
    }

    if (props.action === 'Add' && !form.password) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Password',
            text: 'Please enter a password',
        });
        return;
    }

    isLoading.value = true;
    
    if (action_clicked.value === 'Add') {
        router.post('/accounts/add', form);
    } else if (action_clicked.value === 'Update') {
        router.post('/accounts/update', form);
    }
    
    emit("update:modelValue", false);
    emit("reloadAccounts");
    
    setTimeout(() => { 
        isLoading.value = false; 
    }, 500);
};

const closeDialog = (value) => {
    emit("update:modelValue", value);
    emit("reloadAccounts");
};

const accountTypes = [
    { value: 'user', text: 'User', icon: 'ri-user-line', color: '#43e97b' },
    { value: 'admin', text: 'Admin', icon: 'ri-shield-star-line', color: '#667eea' },
    { value: 'planning', text: 'Planning', icon: 'ri-calendar-check-line', color: '#fa7268' },
    { value: 'special-user', text: 'Special User', icon: 'ri-vip-diamond-line', color: '#f093fb' }
];

const getAccountTypeColor = (type) => {
    const accountType = accountTypes.find(t => t.value === type);
    return accountType ? accountType.color : '#667eea';
};

const isAdminAccount = () => {
    return props.account && props.account.account_type === 'admin';
};
</script>

<template>
    <div v-if="show_form_modal" class="modal fade show" tabindex="-1" style="display: block; background-color: rgba(0,0,0,0.6);" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-custom">
            <div class="modal-content">
                <div class="modal-header-bg"></div>
                
                <div class="modal-header" style="background: transparent; color: white; position: relative; z-index: 1;">
                    <div class="d-flex align-items-center">
                        <div class="header-icon-container">
                            <i class="ri-account-circle-line header-icon"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0">
                                {{ props.action }} Account
                            </h5>
                            <small class="opacity-75" style="font-size: 0.8rem;">Fill in the details below</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" @click="closeDialog(false)" aria-label="Close"></button>
                </div>
                
                <div class="modal-body" v-if="!isAdminAccount()" style="position: relative; z-index: 1;">
                    <div class="decorative-circle circle-1"></div>
                    <div class="decorative-circle circle-2"></div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group-custom">
                                    <span class="input-icon"><i class="ri-user-line"></i></span>
                                    <input type="text" class="form-control custom-input-field" v-model="form.name" placeholder="Enter full name">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group-custom">
                                    <span class="input-icon"><i class="ri-mail-line"></i></span>
                                    <input type="email" class="form-control custom-input-field" v-model="form.email" placeholder="Enter email address" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6" v-if="props.action === 'Add'">
                            <div class="form-group-custom">
                                <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                <div class="input-group-custom">
                                    <span class="input-icon"><i class="ri-lock-line"></i></span>
                                    <input 
                                        :type="showPassword ? 'text' : 'password'" 
                                        class="form-control custom-input-field" 
                                        v-model="form.password" 
                                        placeholder="Enter password" 
                                        required
                                    >
                                    <button class="password-toggle" type="button" @click="showPassword = !showPassword">
                                        <i :class="showPassword ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label fw-semibold">Region <span class="text-danger">*</span></label>
                                <div class="select-wrapper">
                                    <select class="form-select custom-select-field" v-model="form.region" required>
                                        <option value="">Select Region</option>
                                        <option v-for="region in data?.regions || []" :key="region.id" :value="region.id">
                                            {{ region.name }}
                                        </option>
                                    </select>
                                    <i class="ri-map-pin-line select-icon"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-custom">
                                <label class="form-label fw-semibold">Account Type <span class="text-danger">*</span></label>
                                <div class="account-type-selector">
                                    <div 
                                        v-for="type in accountTypes" 
                                        :key="type.value"
                                        class="account-type-option"
                                        :class="{ active: form.account_type === type.value }"
                                        :style="{ '--type-color': type.color }"
                                        @click="form.account_type = type.value"
                                    >
                                        <i :class="type.icon"></i>
                                        <span>{{ type.text }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6" v-if="form.account_type === 'user' || form.account_type === 'special-user'">
                            <div class="form-group-custom">
                                <label class="form-label fw-semibold">Designation <span class="text-danger">*</span></label>
                                <div class="input-group-custom">
                                    <span class="input-icon"><i class="ri-briefcase-line"></i></span>
                                    <input type="text" class="form-control custom-input-field" v-model="form.designation" placeholder="Enter designation">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6" v-if="form.account_type === 'user' || form.account_type === 'special-user'">
                            <div class="form-group-custom">
                                <label class="form-label fw-semibold">Service <span class="text-danger">*</span></label>
                                <div class="select-wrapper">
                                    <select class="form-select custom-select-field" v-model="form.service">
                                        <option value="">Select Service</option>
                                        <option v-for="service in data?.services || []" :key="service.id" :value="service.id">
                                            {{ service.services_name }}
                                        </option>
                                    </select>
                                    <i class="ri-service-line select-icon"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6" v-if="form.account_type === 'user' || form.account_type === 'special-user'">
                            <div class="form-group-custom">
                                <label class="form-label fw-semibold">Unit <span class="text-danger">*</span></label>
                                <div class="select-wrapper">
                                    <select class="form-select custom-select-field" v-model="form.unit">
                                        <option value="">Select Unit</option>
                                        <option v-for="unit in units" :key="unit.id" :value="unit.id">
                                            {{ unit.unit_name }}
                                        </option>
                                    </select>
                                    <i class="ri-building-line select-icon"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6" v-if="form.account_type === 'user' || form.account_type === 'special-user'">
                            <div class="form-group-custom">
                                <label class="form-label fw-semibold">PSTO</label>
                                <div class="select-wrapper">
                                    <select class="form-select custom-select-field" v-model="form.psto">
                                        <option value="">Select PSTO</option>
                                        <option v-for="psto in pstos" :key="psto.id" :value="psto.id">
                                            {{ psto.psto_name }}
                                        </option>
                                    </select>
                                    <i class="ri-map-pin-2-line select-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-body" v-else style="position: relative; z-index: 1;">
                    <div class="alert alert-warning d-flex align-items-center" style="border-radius: 15px; background: linear-gradient(135deg, rgba(255, 193, 7, 0.15) 0%, rgba(255, 152, 0, 0.15) 100%); border: none;">
                        <i class="ri-alert-line me-2 fs-4"></i> 
                        <span class="fw-semibold">This account is an Administrator and cannot be edited for security reasons.</span>
                    </div>
                </div>

                <div class="modal-footer" style="position: relative; z-index: 1;">
                    <button type="button" class="btn btn-secondary btn-custom-secondary" @click="closeDialog(false)">
                        <i class="ri-close-line me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary btn-custom-primary" @click="saveAccount()" :disabled="isLoading">
                        <span v-if="isLoading">
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            Saving...
                        </span>
                        <span v-else>
                            <i class="ri-check-line me-1"></i> Save
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div v-if="show_form_modal" class="modal-backdrop fade show"></div>
</template>

<style scoped>
.modal-custom {
    border-radius: 20px;
    overflow: hidden;
}

.modal-content {
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.3);
    overflow: hidden;
}

.modal-header {
    padding: 20px 25px;
    border-bottom: none;
}

.modal-body {
    padding: 30px;
    max-height: 70vh;
    overflow-y: auto;
}

.modal-footer {
    padding: 20px 25px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.modal-header-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 120px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    z-index: 0;
}

.header-icon-container {
    width: 50px;
    height: 50px;
    border-radius: 15px;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.header-icon {
    font-size: 28px;
}

.form-group-custom {
    margin-bottom: 5px;
}

.form-group-custom .form-label {
    color: #333;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.input-group-custom {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    background: white;
    border: 1px solid #e9ecef;
}

.input-group-custom:focus-within {
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.25);
    transform: scale(1.01);
    border-color: #667eea;
}

.input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
    z-index: 10;
    font-size: 18px;
}

.custom-input-field {
    border: none;
    padding: 14px 15px 14px 45px;
    background: transparent;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.custom-input-field:focus {
    outline: none;
    box-shadow: none;
}

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6c757d;
    cursor: pointer;
    padding: 0;
    z-index: 10;
}

.password-toggle:hover {
    color: #667eea;
}

.select-wrapper {
    position: relative;
}

.select-wrapper .form-select {
    border-radius: 15px;
    border: 1px solid #e9ecef;
    padding: 14px 40px 14px 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    font-size: 0.95rem;
    appearance: none;
    background: white;
}

.select-wrapper .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.25);
    outline: none;
}

.select-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
    pointer-events: none;
}

.account-type-selector {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.account-type-option {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 15px;
    border-radius: 12px;
    border: 2px solid #e9ecef;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
    font-size: 0.9rem;
}

.account-type-option:hover {
    border-color: var(--type-color);
    background: rgba(255,255,255,0.8);
}

.account-type-option.active {
    border-color: var(--type-color);
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
}

.account-type-option i {
    font-size: 18px;
    color: var(--type-color);
}

.alert-warning {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 152, 0, 0.1) 100%);
    border: none;
    color: #856404;
}

.btn-custom-secondary {
    border-radius: 15px;
    padding: 12px 25px;
    transition: all 0.3s ease;
    background: #6c757d;
    border: none;
    font-weight: 500;
}

.btn-custom-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.btn-custom-primary {
    border-radius: 15px;
    padding: 12px 25px;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    font-weight: 500;
}

.btn-custom-primary:hover:not(:disabled) {
    background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-custom-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.decorative-circle {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
    pointer-events: none;
}

.circle-1 {
    width: 200px;
    height: 200px;
    background: #667eea;
    top: -50px;
    right: -50px;
}

.circle-2 {
    width: 150px;
    height: 150px;
    background: #f093fb;
    bottom: -30px;
    left: -30px;
}

.modal {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-dialog {
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        transform: translateY(-30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #667eea;
}
</style>
