<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { reactive, watch, ref, onMounted } from "vue";
 import AOS from 'aos'
import 'aos/dist/aos.css'
import { router } from '@inertiajs/vue3'


AOS.init();

defineProps({
    region_id: Number,
    region: Object,
    services: Object,
});

const goServiceUnits = async (region_id,service_id) => {
    router.get(`/services/csf/service_units?region_id=`+region_id + `&service_id=`+service_id)
}

const goBack = async () => {
    window.history.back()
}



</script>

<template>
    <Head title="Services" />
     <nav
        data-aos="fade-down"
        data-aos-duration="500"
        data-aos-delay="500"
         style="backdrop-filter: blur(2px);"
        class="navbar navbar-light bg-white fixed-top border-bottom">
            <div class="container-fluid">
            <a href="/" class="navbar-brand d-flex align-items-center">
                <img src="../../../public/images/dost-logo.jpg" class="me-3" alt="DOST Logo" style="height: 2rem;">
                <span class="fw-bold fs-4">DOST <span v-if="region">{{ region.code }}</span> Customer Relation Management System</span>
            </a>

            </div>


    </nav>
    <div class="container-fluid d-flex flex-column min-vh-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
        <div class="row mx-5" style="margin-top: 100px;" >
            <div class="col">
                <div class="w-full border-0 shadow-lg rounded" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="card-title text-center text-white py-3 fw-bold">SERVICES</h5>
             </div>
            </div>
        </div>
        <div class="row mx-4 mt-5 justify-content-center align-items-center">
            <div v-for="(service, index) in services" :key="service.id" class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex justify-content-center align-items-center mb-4" :data-aos="'zoom-in'" :data-aos-delay="index * 10">
            <Link @click="goServiceUnits(region_id, service.id)" class="card-link">
                <div class="card mx-3 p-4 bg-white border-0 shadow-lg d-flex flex-column align-items-center justify-content-center position-relative overflow-hidden" style="height:220px; text-align: center; border-radius: 15px; transition: all 0.3s ease;">
                    <div class="card-bg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); z-index: 1;"></div>
                    <i class="ri-service-line text-primary fs-1 p-3 position-relative z-2" style="color: #667eea !important;"></i>
                    <p class="mb-2 fs-5 fw-semibold text-dark text-center position-relative z-2">
                        {{ service.services_name }}
                    </p>
                    <div class="position-absolute bottom-0 start-0 w-100 bg-primary" style="height: 4px; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);"></div>
                </div>
            </Link>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col text-center">
                <button @click="goBack()" class="btn btn-light btn-lg shadow-lg px-5 py-3 rounded-pill">
                    <i class="ri-arrow-left-line me-2"></i> Back to Regions
                </button>
            </div>
        </div>



</div>

</template>

<style scoped>
.card {
  transition: box-shadow 0.3s ease, background-color 0.3s ease, color 0.3s ease;
}

.card:hover {
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
}

</style>


