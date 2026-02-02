<script setup>
import { reactive, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const emit = defineEmits(['input'])

const props = defineProps({
  value: {
    type: Boolean,
    default: false
  },
  actionClicked: {
    type: String,
    default: null
  },
  selectedService: {
    type: Object,
    default: null
  }
})

const form = reactive({
  service_name: '',
  unit_name: ''
})

const showModal = computed({
  get: () => props.value,
  set: (value) => emit('input', value)
})

watch(() => props.value, (newValue) => {
  if (newValue) {
    form.service_name = ''
    form.unit_name = ''
  }
})

const closeModal = () => {
  emit('input', false)
}

const save = () => {
  if (props.actionClicked === 'add_new_service') {
    router.post('/services/add', { service_name: form.service_name })
    closeModal()
  } else if (props.actionClicked === 'add_new_unit') {
    router.post('/services/unit/add', {
      service_id: props.selectedService.id,
      unit_name: form.unit_name
    })
    closeModal()
  }
}
</script>

<template>
  <div v-if="showModal" class="modal-overlay" @click="closeModal">
    <div class="modal-content" @click.stop>
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ri-add-circle-line me-2"></i>
          {{ actionClicked === 'add_new_service' ? 'Add New Service' : 'Add New Unit' }}
        </h5>
        <button type="button" class="modal-close" @click="closeModal">&times;</button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="save">
          <div v-if="actionClicked === 'add_new_service'" class="mb-3">
            <label for="service_name" class="form-label">Service Name</label>
            <input
              type="text"
              class="form-control"
              id="service_name"
              v-model="form.service_name"
              placeholder="Enter service name"
              required
            />
          </div>
          <div v-else-if="actionClicked === 'add_new_unit'">
            <div class="mb-3">
              <label class="form-label">Service</label>
              <input
                type="text"
                class="form-control-plaintext"
                :value="selectedService?.services_name"
                readonly
              />
            </div>
            <div class="mb-3">
              <label for="unit_name" class="form-label">Unit Name</label>
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
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="closeModal">
          <i class="ri-close-line me-1"></i>Cancel
        </button>
        <button type="button" class="btn btn-primary" @click="save">
          <i class="ri-save-line me-1"></i>Save
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1055;
}

.modal-content {
  background-color: white;
  border-radius: 0.375rem;
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
  max-width: 500px;
  width: 90%;
  max-height: 90%;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid #dee2e6;
  background-color: #007bff;
  color: white;
  border-radius: 0.375rem 0.375rem 0 0;
}

.modal-title {
  margin: 0;
  font-size: 1.25rem;
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  color: white;
  cursor: pointer;
}

.modal-body {
  padding: 1rem;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  padding: 1rem;
  border-top: 1px solid #dee2e6;
  border-radius: 0 0 0.375rem 0.375rem;
}
</style>
