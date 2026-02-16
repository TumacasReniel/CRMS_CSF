<script setup>
    import { reactive, watch, ref } from 'vue'
    import { router } from '@inertiajs/vue3'
    import Swal from 'sweetalert2'

    const emit = defineEmits(['reloadAssignatorees', 'input'])

    const props = defineProps({
        assignatoree: {
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
        name: '',
        designation: '',
    })

    watch(
        () => props.value,
        (value) => {
            show_form_modal.value = value
        }
    )

    watch(
        () => props.assignatoree,
        (value) => {
            if (value) {
                form.id = value.id
                form.name = value.name
                form.designation = value.designation
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
        form.name = ''
        form.designation = ''
    }

    const validateForm = () => {
        if (!form.name) {
            Swal.fire({
                title: 'Validation Error',
                icon: 'error',
                text: 'Please enter a name',
            })
            return false
        }
        if (!form.designation) {
            Swal.fire({
                title: 'Validation Error',
                icon: 'error',
                text: 'Please enter a designation',
            })
            return false
        }
        return true
    }

    const saveAssignatoree = async () => {
        if (!validateForm()) {
            return
        }

        const url = props.action === 'Add' ? '/assignatorees/add' : '/assignatorees/update'

        router.post(url, form, {
            onSuccess: () => {
                Swal.fire({
                    title: 'Success',
                    icon: 'success',
                    text: props.action === 'Add' ? 'Assignatoree has been created successfully.' : 'Assignatoree has been updated successfully.',
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
        emit('reloadAssignatorees')
        resetForm()
    }
</script>

<template>
    <div v-if="show_form_modal" class="modal fade show" tabindex="-1" style="display: block; background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-custom">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 50%, #4facfe 100%); color: white;">
                    <h5 class="modal-title">
                        <i class="ri-team-line me-2"></i>
                        {{ props.action }} Assignatoree
                    </h5>
                    <button type="button" class="btn-close btn-close-white" @click="closeDialog"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveAssignatoree">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="ri-user-line"></i></span>
                                    <input
                                        type="text"
                                        class="form-control custom-input"
                                        v-model="form.name"
                                        placeholder="Enter name"
                                        required
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">Designation <span class="text-danger">*</span></label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="ri-briefcase-line"></i></span>
                                    <input
                                        type="text"
                                        class="form-control custom-input"
                                        v-model="form.designation"
                                        placeholder="Enter designation"
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
                    <button type="button" class="btn btn-primary btn-custom-primary" @click="saveAssignatoree">
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
    box-shadow: 0 4px 20px rgba(67, 233, 123, 0.3);
    transform: scale(1.01);
}

.input-group-text {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
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
    border-color: #43e97b;
    box-shadow: 0 0 0 3px rgba(67, 233, 123, 0.1);
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
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    border: none;
}

.btn-custom-primary:hover {
    background: linear-gradient(135deg, #38d96b 0%, #2ee9c7 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(67, 233, 123, 0.4);
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
