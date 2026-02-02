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
    <div class="container-fluid d-flex flex-column min-vh-100">
        <div class="row mx-5" style="margin-top: 100px;" >
            <div class="col">
                <div class="w-full border bg-primary">
                <h5 class="card-title text-center text-white">SERVICES</h5>
             </div>
            </div>
        </div>
        <div class="row mx-4 mt-5 justify-content-center align-items-center">
            <div v-for="service in services" :key="service.id" class="col-12 col-sm-6 col-md-6 col-lg-6 d-flex justify-content-center align-items-center">
            <Link @click="goServiceUnits(region_id, service.id)" class="card-link">
                <div class="card mx-5 p-4 bg-white border shadow d-flex flex-column align-items-center justify-content-center" style="height:180px; text-align: center;">
                <i class="ri-check-circle-line text-primary fs-1 p-3"></i>
                <p class="mb-2 fs-5 fw-semibold text-dark text-start">
                    {{ service.services_name }}
                </p>
                </div>
            </Link>
            </div>
        </div>
        <div class="row">
            <button @click="goBack()" class="btn btn-outline-primary ms-5">
                <i class="ri-arrow-left-line"></i> Back
            </button>
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


