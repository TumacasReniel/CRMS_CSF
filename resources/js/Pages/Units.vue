<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { reactive, watch, ref, onMounted } from "vue";
 import AOS from 'aos'
import 'aos/dist/aos.css'
import { router } from '@inertiajs/vue3'


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
    <v-container fill-height>
        <v-row class="mx-15" style="margin-top: 100px;" >
            <v-col>
                <div class="w-full border bg-blue">
                <v-card-title class="text-center">{{ service.services_name }}</v-card-title>
             </div>
            </v-col>
        </v-row>
        <v-row   class=" mx-15 mt-5" align="center" justify="center">        
                <v-col v-for="unit in service_units" cols="12"sm="4" md="4" lg="4">
                    <Link @click="goNext(unit.id, region_id, service_id)">
                        <div style="height:150px"  class="card mx-5 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                <v-icon color="blue" size="x-large" class="p-3" >mdi-check-circle</v-icon>
                                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                    {{ unit.unit_name }}
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


