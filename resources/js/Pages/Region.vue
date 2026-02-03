<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { reactive, watch, ref, onMounted } from "vue";
 import AOS from 'aos'
import 'aos/dist/aos.css'
import { router } from '@inertiajs/vue3'

AOS.init();

defineProps({
    regions: Object,
});

const goServices = async (region_id) => {
    router.get('/services/csf/services',{region_id} )
}

const goBack = async () => {
    window.history.back()
}

</script>

<template>
    <Head title="Regions" />
    <nav
        class="navbar navbar-expand-lg navbar-light bg-white shadow-sm"
        data-aos="fade-down"
        data-aos-duration="500"
        data-aos-delay="500"
        style="position: fixed; top: 0; left: 0; right: 0; z-index: 1000; backdrop-filter: blur(2px);">
        <div class="container-fluid">
            <a href="/" class="navbar-brand d-flex align-items-center text-decoration-none">
                <img src="../../../public/images/dost-logo.jpg" alt="DOST Logo" class="me-2" style="height: 2rem;">
                <span class="fw-bold fs-4">DOST Customer Relation Management System</span>
            </a>
        </div>
    </nav>
    <div class="min-vh-100 d-flex flex-column" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="mt-5 mx-3">
            <div class="w-100 py-3 text-center text-white fw-bold rounded shadow" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0">REGIONAL OFFICES</h5>
            </div>
        </div>
        <div class="container-fluid mt-4">
            <div class="row justify-content-center">
                <div v-for="(region, index) in regions" :key="region.id" class="col-lg-4 col-md-4 col-sm-6 m-4" :data-aos="'zoom-in'" :data-aos-delay="index * 10">
                    <Link @click="goServices(region.id)" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-lg hover-lift">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center text-center position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);">
                                <i class="ri-map-pin-line display-4 text-primary mb-3"></i>
                                <h6 class="card-title fw-bold text-dark mb-0">{{ region.short_name }}</h6>
                                <div class="position-absolute bottom-0 start-0 w-100" style="height: 4px; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);"></div>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
        <div class="mt-auto text-center mb-4">
            <button @click="goBack()" class="btn btn-light btn-lg rounded-pill shadow hover-lift">
                <i class="ri-arrow-left-line me-2"></i> Back
            </button>
        </div>
    </div>
</template>
<style scoped>
.region-card {
  position: relative;
  width: 100%;
  height: 220px;
  background-color: white;
  border-radius: 15px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  transition: all 0.3s ease;
  overflow: hidden;
}

.region-card:hover {
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
  transform: translateY(-5px);
}

.card-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
  z-index: 1;
}

.region-icon {
  font-size: 3rem;
  color: #667eea;
  padding: 1rem;
  position: relative;
  z-index: 2;
}

.region-name {
  margin-bottom: 0.5rem;
  font-size: 1.25rem;
  font-weight: 600;
  color: #333;
  text-align: center;
  position: relative;
  z-index: 2;
}

.card-bottom {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 4px;
  background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.back-button {
  background-color: white;
  border: none;
  padding: 1rem 2rem;
  font-size: 1.125rem;
  border-radius: 50px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  transition: all 0.3s ease;
}

.back-button:hover {
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
  transform: translateY(-2px);
}
</style>


