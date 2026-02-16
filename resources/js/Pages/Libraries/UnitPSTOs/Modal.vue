<script setup>
    import { reactive, watch, ref } from 'vue'
    import { router } from '@inertiajs/vue3'
    import VueMultiselect from "vue-multiselect"
    import Swal from 'sweetalert2'

    const emit = defineEmits(['reloadUnits', 'input'])

    const props = defineProps({
        unit: {
            type: Object,
            default: null,
        },
        unit_pstos: {
            type: Object,
            default: null,
        },
        pstos: {
            type: Object,
            default: null,
        },
        value: {
            type: Boolean,
            default: false,
        },
        action: {
            type: String,
        },
        page_number: {
            type: Number,
        }
    })

    const show_form_modal = ref(false)

    const form = reactive({
        unit_id: null,
        unit_name: null,
        pstos: null,
    })

    watch(
        () => props.value,
        (value) => {
            show_form_modal.value = value
        }
    )

    watch(
        () => props.unit,
        (value) => {
            if (value) {
                form.unit_id = value.id
                form.unit_name = value.unit_name
            } else {
                resetForm()
            }
        }
    )

    watch(
        () => props.unit_pstos,
        (value) => {
            if (value && value.data) {
                form.pstos = value.data
            }
        }
    )

    const resetForm = () => {
        form.unit_id = null
        form.unit_name = null
        form.pstos = null
    }

    const validateForm = () => {
        if (!form.unit_name) {
            Swal.fire({
                title: 'Validation Error',
                icon: 'error',
                text: 'Please select a unit',
            })
            return false
        }
        if (!form.pstos || form.pstos.length === 0) {
            Swal.fire({
                title: 'Validation Error',
                icon: 'error',
                text: 'Please select at least one PSTO',
            })
            return false
        }
        return true
    }

    const savePSTO = async () => {
        if (!validateForm()) {
            return
        }

        router.post('/unit-pstos/assign', form, {
            onSuccess: () => {
                Swal.fire({
                    title: 'Success',
                    icon: 'success',
                    text: 'PSTO has been assigned successfully.',
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
        emit('reloadUnits')
        resetForm()
    }
</script>

<template>
    <div v-if="show_form_modal" class="modal fade show" tabindex="-1" style="display: block; background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-custom">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #6B8DD6 100%); color: white;">
                    <h5 class="modal-title">
                        <i class="ri-map-pin-range-line me-2"></i>
                        {{ props.action }} Unit PSTO
                    </h5>
                    <button type="button" class="btn-close btn-close-white" @click="closeDialog"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="savePSTO">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">Unit Name <span class="text-danger">*</span></label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="ri-building-4-line"></i></span>
                                    <input
                                        type="text"
                                        class="form-control custom-input"
                                        v-model="form.unit_name"
                                        placeholder="Unit name"
                                        readonly
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">PSTOs <span class="text-danger">*</span></label>
                                <vue-multiselect
                                    v-model="form.pstos"
                                    :options="pstos.data"
                                    :multiple="true"
                                    placeholder="Select Unit PSTO"
                                    label="psto_name"
                                    track-by="psto_name"
                                    :options-limit="10"
                                    :limit="3"
                                    :max-height="200"
                                    class="custom-multiselect"
                                >
                                </vue-multiselect>
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

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

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
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
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
    background-color: #f8f9fa;
}

.custom-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    background-color: white;
}

/* Form Label */
.form-label {
    color: #333;
    margin-bottom: 8px;
}

/* Multiselect Custom Styling */
.custom-multiselect :deep(.multiselect) {
    border-radius: 15px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.custom-multiselect :deep(.multiselect:hover) {
    border-color: #667eea;
}

.custom-multiselect :deep(.multiselect.is-open) {
    border-color: #667eea;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
}

.custom-multiselect :deep(.multiselect__tags) {
    border-radius: 15px;
    padding: 8px 40px 0 8px;
}

.custom-multiselect :deep(.multiselect__input) {
    border-radius: 15px;
}

.custom-multiselect :deep(.multiselect__single) {
    padding: 8px 0;
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-custom-primary:hover {
    background: linear-gradient(135deg, #5a6fd6 0%, #6a4190 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
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
