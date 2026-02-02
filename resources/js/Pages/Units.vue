<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { reactive, watch, ref, onMounted } from "vue";
import AOS from 'aos'
import 'aos/dist/aos.css'
import { router } from '@inertiajs/vue3'
import '../../css/card-animations.css'


AOS.init();

defineProps({
    service_units: Object,
    region_id: Number,
    region: Object,
    service_id: Number,
    service:Object,
    sub_units: Object,
});

const goNext = async (unit_id, region_id, service_id) => {
    getUnitSubUnits( region_id, service_id,unit_id);

}

const getUnitSubUnits = async (region_id, service_id,unit_id) => {
    router.get(`/services/csf/unit/sub-units?region_id=`+ region_id + `&service_id=`+ service_id + `&unit_id=`+ unit_id );                        
}

const goBack = async () => {
    window.history.back()
}

</script>

<template >
    <Head title="Service Units" />   
     <nav 
        data-aos="fade-down" 
        data-aos-duration="500" 
        data-aos-delay="500" 
         style="backdrop-filter: blur(2px);"
        class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="../../../public/images/dost-logo.jpg" class="h-8" alt="DOST Logo">
                <span class="self-center text-2xl font-semibold whitespace-nowrap">DOST <span v-if="region">{{ region.code }}</span> Customer Relation Management System</span>
            </a>

            </div>

        
    </nav>  
    <div class="container-fluid min-vh-100">
        <div class="row mx-15" style="margin-top: 100px;" >
            <div class="col">
                <div class="w-full bg-gradient-primary text-white p-4 rounded-3 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="text-center fw-bold fs-3 mb-0">{{ service.services_name }}</h5>
             </div>
            </div>
        </div>
        <div class="row mx-15 mt-5 align-items-center justify-content-center">
                <div v-for="(unit, index) in service_units" class="col-12 col-sm-4 col-md-4 col-lg-4">
                    <Link @click="goNext(unit.id, region_id, service_id)">
                        <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden card-amazing mx-5 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700" :style="{ animationDelay: `${index * 0.5}s` }">
                                <i class="bi bi-check-circle text-primary fs-1 p-3 icon-main"></i>
                                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white card-title">
                                    {{ unit.unit_name }}
                                </h5>
                        </div>
                    </Link>
                </div>
        </div>
        <div class="row">
            <Link @click="goBack()">
            <button class="btn btn-primary ms-5" style="margin-left: 120px"><i class="bi bi-arrow-left me-2"></i>Back</button>
            </Link>
        </div>



</div>
        

</template>
<style scoped>
</style>


