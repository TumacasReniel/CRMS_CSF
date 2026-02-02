<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import AOS from 'aos'
import 'aos/dist/aos.css'

AOS.init();

</script>

<template>
    <AppLayout title="Regional Offices">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Regional Offices
            </h2>
        </template>

        <div class="container-fluid mt-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="ri-building-4-line text-primary fs-1 mb-3"></i>
                            <h5 class="card-title fw-bold">Regional Office I</h5>
                            <p class="card-text text-muted">Ilocos Region</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="ri-question-line text-success fs-1 mb-3"></i>
                            <h5 class="card-title fw-bold">Help & Support</h5>
                            <p class="card-text text-muted">Get assistance with claims</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="ri-information-line text-info fs-1 mb-3"></i>
                            <h5 class="card-title fw-bold">Information Center</h5>
                            <p class="card-text text-muted">Find important updates</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="ri-phone-line text-warning fs-1 mb-3"></i>
                            <h5 class="card-title fw-bold">Contact Us</h5>
                            <p class="card-text text-muted">Reach out for inquiries</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="ri-map-pin-line text-danger fs-1 mb-3"></i>
                            <h5 class="card-title fw-bold">Locations</h5>
                            <p class="card-text text-muted">Find our offices near you</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AppLayout>
</template>
