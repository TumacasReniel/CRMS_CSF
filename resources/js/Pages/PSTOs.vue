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
    service_id: Number,
    unit_id: Number,
    unit:Object,
    sub_unit_id: Number,
    sub_unit: Object,
    pstos:Object,
});

const goNext = async (region_id, service_id, unit_id, sub_unit_id, psto_id) => {
    router.get(`/services/csf?region_id=`+ region_id + 
                            `&service_id=`+ service_id + 
                            `&unit_id=`+ unit_id +
                            `&sub_unit_id=` + sub_unit_id +
                            `&psto_id=` + psto_id );   
}




const goBack = async (sub_unit_id) => {
    window.history.back()
}





</script>

<template>
    <Head title="Service Units" />
    <nav
        data-aos="fade-down"
        data-aos-duration="500"
        data-aos-delay="500"
        class="navbar navbar-expand-lg navbar-light bg-white shadow-sm position-fixed w-100"
        style="z-index: 1000; backdrop-filter: blur(2px);">
        <div class="container-fluid">
            <a href="/" class="navbar-brand d-flex align-items-center text-decoration-none">
                <img src="../../../public/images/dost-logo.jpg" alt="DOST Logo" class="me-2" style="height: 2rem;">
                <span class="fw-bold fs-5">DOST <span v-if="region">{{ region.code }}</span> Customer Relation Management System</span>
            </a>
        </div>
    </nav>
    <div class="bg-primary min-vh-100 d-flex flex-column" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;">
        <div class="container-fluid mt-5 pt-5">
            <div class="row justify-content-center mb-4">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
                        <div class="card-body text-center py-4">
                            <h5 class="card-title text-white fw-bold text-uppercase mb-0">
                                <span v-if="sub_unit">{{ sub_unit.sub_unit_name }}</span>
                                <span v-if="unit && !sub_unit">{{ unit.unit_name }}</span>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                <div v-for="(psto, index) in pstos" :key="psto.id" :data-aos="'zoom-in'" :data-aos-delay="index * 100" class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <Link @click="goNext(region_id, service_id, unit_id, sub_unit_id, psto.id)" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm text-center" style="border-radius: 15px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                                <i class="ri-map-pin-line display-4 text-primary mb-3"></i>
                                <h6 class="card-title fw-bold text-dark mb-0">{{ psto.psto_name }}</h6>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 text-center">
                    <button @click="goBack()" class="btn btn-outline-light btn-lg px-4 py-2">
                        <i class="ri-arrow-left-line me-2"></i> Back
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}
</style>



