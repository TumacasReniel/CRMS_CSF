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
    sub_unit_id:Number,
    sub_unit:Object,
    types:Object,
});

const goNext = async (region_id, service_id, unit_id, sub_unit_id, type_name) => {
    router.get(`/services/csf?region_id=`+ region_id + 
                                `&service_id=`+ service_id + 
                                `&unit_id=`+ unit_id +
                                `&sub_unit_id=` + sub_unit_id +
                                `&sub_unit_type=` + type_name);   
};



const goBack = async (sub_unit_id) => {
    window.history.back();
};


</script>

<template>
    <Head title="Service Units" />
    <nav
        data-aos="fade-down"
        data-aos-duration="500"
        data-aos-delay="500"
        style="backdrop-filter: blur(2px); position: fixed; top: 0; left: 0; right: 0; z-index: 1000; background-color: white; border-bottom: 1px solid #e0e0e0;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
            <a href="/" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                <img src="../../../public/images/dost-logo.jpg" alt="DOST Logo" style="height: 2rem; margin-right: 1rem;">
                <span style="font-weight: bold; font-size: 1.5rem;">DOST <span v-if="region">{{ region.code }}</span> Customer Relation Management System</span>
            </a>
        </div>
    </nav>
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; flex-direction: column;">
        <div style="margin: 100px 3rem 0 3rem;">
            <div style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <h5 style="text-align: center; color: white; padding: 1rem; font-weight: bold; margin: 0; text-transform: uppercase;">{{ sub_unit.sub_unit_name }}</h5>
            </div>
        </div>
        <div style="display: flex; flex-wrap: wrap; justify-content: center; align-items: center; margin: 3rem 2rem; gap: 2rem;">
            <div v-for="(type, index) in types" :key="type.id" :data-aos="'zoom-in'" :data-aos-delay="index * 100" style="flex: 1 1 300px; max-width: 300px; display: flex; justify-content: center; align-items: center;">
                <Link @click="goNext(region_id, service_id, unit_id, sub_unit_id, type.type_name)" style="text-decoration: none;">
                    <div class="type-card">
                        <div class="card-bg"></div>
                        <i class="ri-check-circle-line type-icon"></i>
                        <p class="type-name">{{ type.type_name }}</p>
                        <div class="card-bottom"></div>
                    </div>
                </Link>
            </div>
        </div>
        <div style="margin-top: 3rem; text-align: center;">
            <button @click="goBack()" class="back-button">
                <i class="ri-arrow-left-line"></i> Back
            </button>
        </div>
    </div>
</template>

<style scoped>
.type-card {
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

.type-card:hover {
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

.type-icon {
  font-size: 3rem;
  color: #667eea;
  padding: 1rem;
  position: relative;
  z-index: 2;
}

.type-name {
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


