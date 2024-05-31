<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { reactive, watch, ref, onMounted } from "vue";
 import AOS from 'aos'
import 'aos/dist/aos.css'
import { router } from '@inertiajs/vue3'


AOS.init();

defineProps({
    region_id: Number,
    service_id: Number,
    unit_id: Number,
    sub_units:Object,
});

const goNext = async (region_id, service_id, unit_id, sub_unit_id) => {
    getSubUnitPSTO(region_id, service_id, unit_id, sub_unit_id);
}


const getSubUnitPSTO = async (region_id, service_id, unit_id, sub_unit_id) => {
    router.get(`/services/csf/sub-unit/pstos?region_id=`+ region_id + 
                                `&service_id=`+ service_id + 
                                `&unit_id=`+ unit_id +
                                `&sub_unit_id=` + sub_unit_id );             
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
        class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="../../../public/images/dost-logo.jpg" class="h-8" alt="DOST Logo">
                <span class="self-center text-2xl font-semibold whitespace-nowrap">Department of Science and Technology</span>
            </a>

            </div>

        
    </nav>  
    <v-container fill-height>
        <v-row class="mx-15" style="margin-top: 100px;" >
            <v-col>
                <div class="w-full border bg-blue">
                <v-card-title class="text-center">UNIT SUB-UNITS</v-card-title>
             </div>
            </v-col>
        </v-row>
        <v-row   class=" mx-15 mt-5" align="center" justify="center">        
                <v-col v-for="sub_unit in sub_units" cols="12"sm="4" md="4" lg="4">
                    <Link @click="goNext(region_id, service_id, unit_id, sub_unit.id)">
                        <div style="height:150px"  class="mx-5 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                <v-icon color="blue" size="x-large" class="p-3" >mdi-check-circle</v-icon>
                                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                    {{ sub_unit.sub_unit_name }}
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


