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

<template >
    <Head title="Service Units" />   
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
    <v-container fill-height>
        <v-row class="mx-15" style="margin-top: 100px;" >
            <v-col>
                <div class="w-full border bg-blue">
                <v-card-title class="text-center text-uppercase" v-if="sub_unit">{{ sub_unit.sub_unit_name }}</v-card-title>
                <v-card-title class="text-center text-uppercase" v-if="unit">{{ unit.unit_name }}</v-card-title>
             </div>
            </v-col>
        </v-row>
        <v-row   class=" mx-15 mt-5" align="center" justify="center">        
                <v-col v-for="psto in pstos" cols="12"sm="4" md="4" lg="4">
                    <Link @click="goNext(region_id, service_id, unit_id, sub_unit_id, psto.id)">
                        <div style="height:150px"  class="card mx-5 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                <v-icon color="red" size="x-large" class="p-3" >mdi-map-marker-outline</v-icon>
                                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                    {{ psto.psto_name }}
                                </h5>      
                        </div>     
                    </Link>
                </v-col>
        </v-row>
        <v-row>
            <Link @click="goBack()">
            <v-btn prepend-icon="mdi-arrow-left" style="margin-left: 120px">Back</v-btn>
            </Link>
        </v-row>
        
     
  
</v-container>
        

</template>

<style scoped>
.card {
  transition: box-shadow 0.3s ease, background-color 0.3s ease, color 0.3s ease;
}

.card:hover {
  box-shadow:  10px 10px 15px rgba(0, 0, 0, 0.2);
}
</style>



