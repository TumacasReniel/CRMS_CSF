<script setup>
import { reactive, watch, ref, nextTick } from "vue";
import { router } from '@inertiajs/vue3';

const emit = defineEmits(["input"]);

const props = defineProps({
    data: {
        type: Object,
        default: null,
    },
    value: {
        type: Boolean,
        default: false,
    },
    action_clicked: {
        type: String,
        default: null,
    },
    selected_service: {
        type: Object,
        default: null,
    },
});

const form = reactive({
    service_id: null,
    service_name: null,
    unit_name: null,
});

const show_form_modal = ref(false);

watch(
    () => props.value,
    async (value) => {
        show_form_modal.value = value;
        if (value) {
            // Focus the first input when modal opens
            await nextTick();
            const firstInput = document.querySelector(props.action_clicked === 'add_new_service' ? '#service_name' : '#unit_name');
            if (firstInput) {
                firstInput.focus();
            }
        }
    }
);

const closeDialog = (value) => {
    emit("input", value);
};

const saveService = () => {
    if (props.action_clicked == 'add_new_service') {
        router.post('/services/add', form);
    } else {
        form.service_id = props.selected_service.id;
        router.post('/services/unit/add', form);
    }

    emit("input", false);
};
</script>

<template>
    <div class="modal fade" :class="{ 'show d-block': show_form_modal }" tabindex="-1" role="dialog" style="z-index: 1055; position: fixed; top: 0; left: 0; width: 100%; height: 100%;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="ri-add-circle-line me-2"></i>
                        {{ props.action_clicked == 'add_new_service' ? 'Add New Service' : 'Add New Unit' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" @click="closeDialog(false)"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveService()">
                        <div v-if="props.action_clicked == 'add_new_service'" class="mb-3">
                            <label for="service_name" class="form-label">Service Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-service-line"></i></span>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="service_name"
                                    v-model="form.service_name"
                                    placeholder="Enter service name"
                                    required
                                />
                            </div>
                        </div>

                        <div v-if="props.action_clicked == 'add_new_unit'">
                            <div class="mb-3">
                                <label for="service" class="form-label">Service</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-building-line"></i></span>
                                    <input
                                        type="text"
                                        class="form-control-plaintext"
                                        id="service"
                                        :value="props.selected_service?.services_name"
                                        readonly
                                    />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="unit_name" class="form-label">Unit Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-home-line"></i></span>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="unit_name"
                                        v-model="form.unit_name"
                                        placeholder="Enter unit name"
                                        required
                                    />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeDialog(false)">
                        <i class="ri-close-line me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" @click="saveService()">
                        <i class="ri-save-line me-1"></i>Save
                    </button>
                </div>
            </div>
        </div>
        <div v-if="show_form_modal" class="modal-backdrop fade show" style="z-index: 1050; opacity: 0.3;"></div>
    </div>
</template>
