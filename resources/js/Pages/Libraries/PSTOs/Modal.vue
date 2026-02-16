<script setup>
    import { reactive, watch, ref } from 'vue'
    import { router } from '@inertiajs/vue3'
    import Swal from 'sweetalert2'

    const emit = defineEmits(['reloadPSTOs', 'input'])

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
    })

    const show_form_modal = ref(false)

    const form = reactive({
        id: null,
        psto_name: '',
    })

    watch(
        () => props.value,
        (value) => {
            show_form_modal.value = value
        }
    )

    watch(
        () => props.psto,
        (value) => {
            if (value) {
                form.id = value.id
                form.psto_name = value.psto_name
            } else {
                resetForm()
            }
        }
    )

    watch(
        () => props.action,
        (value) => {
            if (value === 'Add') {
                resetForm()
            }
        }
    )

    const resetForm = () => {
        form.id = null
        form.psto_name = ''
    }

    const validateForm = () => {
        if (!form.psto_name) {
            Swal.fire({
                title: 'Validation Error',
                icon: 'error',
                text: 'Please enter a PSTO name',
            })
            return false
        }
        return true
    }

    const savePSTO = async () => {
        if (!validateForm()) {
            return
        }

        const url = props.action === 'Add' ? '/pstos/add' : '/pstos/update'

        router.post(url, form, {
            onSuccess: () => {
                Swal.fire({
                    title: 'Success',
                    icon: 'success',
                    text: props.action === 'Add' ? 'PSTO has been created successfully.' : 'PSTO has been updated successfully.',
                })
                closeDialog()
            },
            onError: (error) => {
                Swal.fire({
                    title: 'Failed',
                    icon: 'error',
                    text: error?.message || 'Something went wrong, please try again',
                })
            }
        })
    }

    const closeDialog = () => {
        emit('input', false)
        emit('reloadPSTOs')
        resetForm()
    }
</script>

<template>
    <div v-if="show_form_modal" class="modal fade show" tabindex="-1" style="display: block; background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-custom">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #fa7268 0%, #f5576c 50%, #f093fb 100%); color: white;">
                    <h5 class="modal-title">
                        <i class="ri-home-workspace-line me-2"></i>
                        {{ props.action }} PSTO
                    </h5>
                    <button type="button" class="btn-close btn-close-white" @click="closeDialog"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="savePSTO">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">PSTO Name <span class="text-danger">*</span></label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="ri-home-workspace-line"></i></span>
                                    <input
                                        type="text"
                                        class="form-control custom-input"
                                        v-model="form.psto_name"
                                        placeholder="Enter PSTO name"
                                        required
                                    />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-custom-secondary" @click="closeDialog">
                        <i class="ri-close-line me-1"></i>
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary btn-custom-primary" @click="savePSTO">
                        <i class="ri-save-line me-1"></i>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Modal Custom Styling */
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
}

.modal-footer {
    padding: 20px 25px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

/* Custom Input Group */
.custom-input-group {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.custom-input-group:focus-within {
    box-shadow: 0 4px 20px rgba(250, 114, 104, 0.3);
    transform: scale(1.01);
}

.input-group-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 15px;
}

.custom-input {
    border: 1px solid #e9ecef;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.custom-input:focus {
    border-color: #fa7268;
    box-shadow: 0 0 0 3px rgba(250, 114, 104, 0.1);
}

/* Form Label */
.form-label {
    color: #333;
    margin-bottom: 8px;
}

/* Button Styling */
.btn-custom-secondary {
    border-radius: 15px;
    padding: 10px 20px;
    transition: all 0.3s ease;
    background: #6c757d;
    border: none;
}

.btn-custom-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.btn-custom-primary {
    border-radius: 15px;
    padding: 10px 20px;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #fa7268 0%, #f5576c 100%);
    border: none;
}

.btn-custom-primary:hover {
    background: linear-gradient(135deg, #e04860 0%, #d5475c 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(250, 114, 104, 0.4);
}

/* Animation */
.modal {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.modal-dialog {
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>
