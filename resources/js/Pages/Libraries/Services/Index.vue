<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import { Link } from '@inertiajs/vue3';
</script>


<template>
    <AppLayout title="Libraries">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Libraries
            </h2>
        </template>

        <div class="container-fluid py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-11">
                    <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                        <div class="card-header text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%); padding: 25px 30px;">
                            <div class="position-absolute top-0 end-0 p-3 opacity-25">
                                <i class="ri-library-line" style="font-size: 100px;"></i>
                            </div>
                            <h3 class="card-title mb-0 position-relative">
                                <i class="ri-library-line me-2"></i>
                                Libraries Management
                            </h3>
                            <p class="mb-0 mt-2 opacity-75 position-relative">Manage your system libraries and configurations</p>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Accounts -->
                                <div class="col-md-4 mb-4">
                                    <Link href="/accounts" class="text-decoration-none">
                                        <div class="card card-amazing h-100 border-0 library-card" style="border-radius: 16px;">
                                            <div class="card-body text-center py-4 position-relative">
                                                <div class="icon-container position-relative d-inline-block mb-3">
                                                    <div class="icon-wrapper" style="display: inline-block; padding: 20px; border-radius: 50%; background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0.15) 100%);">
                                                        <i class="ri-account-circle-line ri-3x icon-main" style="color: #667eea;"></i>
                                                    </div>
                                                </div>
                                                <h5 class="card-title fw-bold mb-2" style="color: #333;">
                                                    Accounts
                                                </h5>
                                                <p class="card-description text-muted small mb-0">
                                                    Manage user accounts and permissions
                                                </p>
                                                <div class="arrow-icon position-absolute bottom-0 end-0 p-3">
                                                    <i class="ri-arrow-right-line text-muted"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>

                                <!-- Assignatorees -->
                                <div class="col-md-4 mb-4">
                                    <Link href="/assignatorees" class="text-decoration-none">
                                        <div class="card card-amazing h-100 border-0 library-card" style="border-radius: 16px;">
                                            <div class="card-body text-center py-4 position-relative">
                                                <div class="icon-container position-relative d-inline-block mb-3">
                                                    <div class="icon-wrapper" style="display: inline-block; padding: 20px; border-radius: 50%; background: linear-gradient(135deg, rgba(67, 233, 123, 0.15) 0%, rgba(56, 249, 215, 0.15) 100%);">
                                                        <i class="ri-team-line ri-3x icon-main" style="color: #43e97b;"></i>
                                                    </div>
                                                </div>
                                                <h5 class="card-title fw-bold mb-2" style="color: #333;">
                                                    Assignatorees
                                                </h5>
                                                <p class="card-description text-muted small mb-0">
                                                    Manage assignees and their assignments
                                                </p>
                                                <div class="arrow-icon position-absolute bottom-0 end-0 p-3">
                                                    <i class="ri-arrow-right-line text-muted"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>

                                <!-- Service Units -->
                                <div class="col-md-4 mb-4">
                                    <Link href="/service_units" class="text-decoration-none">
                                        <div class="card card-amazing h-100 border-0 library-card" style="border-radius: 16px;">
                                            <div class="card-body text-center py-4 position-relative">
                                                <div class="icon-container position-relative d-inline-block mb-3">
                                                    <div class="icon-wrapper" style="display: inline-block; padding: 20px; border-radius: 50%; background: linear-gradient(135deg, rgba(79, 172, 254, 0.15) 0%, rgba(0, 242, 254, 0.15) 100%);">
                                                        <i class="ri-building-4-line ri-3x icon-main" style="color: #4facfe;"></i>
                                                    </div>
                                                </div>
                                                <h5 class="card-title fw-bold mb-2" style="color: #333;">
                                                    Service Units
                                                </h5>
                                                <p class="card-description text-muted small mb-0">
                                                    Configure and manage service units
                                                </p>
                                                <div class="arrow-icon position-absolute bottom-0 end-0 p-3">
                                                    <i class="ri-arrow-right-line text-muted"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>

                                <!-- Regions -->
                                <div class="col-md-4 mb-4">
                                    <Link href="/regions" class="text-decoration-none">
                                        <div class="card card-amazing h-100 border-0 library-card" style="border-radius: 16px;">
                                            <div class="card-body text-center py-4 position-relative">
                                                <div class="icon-container position-relative d-inline-block mb-3">
                                                    <div class="icon-wrapper" style="display: inline-block; padding: 20px; border-radius: 50%; background: linear-gradient(135deg, rgba(240, 147, 251, 0.15) 0%, rgba(245, 87, 108, 0.15) 100%);">
                                                        <i class="ri-map-pin-line ri-3x icon-main" style="color: #f093fb;"></i>
                                                    </div>
                                                </div>
                                                <h5 class="card-title fw-bold mb-2" style="color: #333;">
                                                    Regions
                                                </h5>
                                                <p class="card-description text-muted small mb-0">
                                                    Manage regional configurations
                                                </p>
                                                <div class="arrow-icon position-absolute bottom-0 end-0 p-3">
                                                    <i class="ri-arrow-right-line text-muted"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>

                                <!-- PSTOs -->
                                <div class="col-md-4 mb-4">
                                    <Link href="/pstos" class="text-decoration-none">
                                        <div class="card card-amazing h-100 border-0 library-card" style="border-radius: 16px;">
                                            <div class="card-body text-center py-4 position-relative">
                                                <div class="icon-container position-relative d-inline-block mb-3">
                                                    <div class="icon-wrapper" style="display: inline-block; padding: 20px; border-radius: 50%; background: linear-gradient(135deg, rgba(250, 114, 104, 0.15) 0%, rgba(253, 121, 168, 0.15) 100%);">
                                                        <i class="ri-building-line ri-3x icon-main" style="color: #fa7268;"></i>
                                                    </div>
                                                </div>
                                                <h5 class="card-title fw-bold mb-2" style="color: #333;">
                                                    PSTOs
                                                </h5>
                                                <p class="card-description text-muted small mb-0">
                                                    Manage PSTO configurations
                                                </p>
                                                <div class="arrow-icon position-absolute bottom-0 end-0 p-3">
                                                    <i class="ri-arrow-right-line text-muted"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>

                                <!-- Unit PSTOs -->
                                <div class="col-md-4 mb-4">
                                    <Link href="/unit-pstos" class="text-decoration-none">
                                        <div class="card card-amazing h-100 border-0 library-card" style="border-radius: 16px;">
                                            <div class="card-body text-center py-4 position-relative">
                                                <div class="icon-container position-relative d-inline-block mb-3">
                                                    <div class="icon-wrapper" style="display: inline-block; padding: 20px; border-radius: 50%; background: linear-gradient(135deg, rgba(255, 193, 7, 0.15) 0%, rgba(255, 152, 0, 0.15) 100%);">
                                                        <i class="ri-map-pin-range-line ri-3x icon-main" style="color: #ffc107;"></i>
                                                    </div>
                                                </div>
                                                <h5 class="card-title fw-bold mb-2" style="color: #333;">
                                                    Unit PSTOs
                                                </h5>
                                                <p class="card-description text-muted small mb-0">
                                                    Configure unit-specific PSTOs
                                                </p>
                                                <div class="arrow-icon position-absolute bottom-0 end-0 p-3">
                                                    <i class="ri-arrow-right-line text-muted"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>

                                <!-- Sub Unit PSTOs -->
                                <div class="col-md-4 mb-4">
                                    <Link href="/sub-unit-pstos" class="text-decoration-none">
                                        <div class="card card-amazing h-100 border-0 library-card" style="border-radius: 16px;">
                                            <div class="card-body text-center py-4 position-relative">
                                                <div class="icon-container position-relative d-inline-block mb-3">
                                                    <div class="icon-wrapper" style="display: inline-block; padding: 20px; border-radius: 50%; background: linear-gradient(135deg, rgba(0, 188, 212, 0.15) 0%, rgba(0, 150, 136, 0.15) 100%);">
                                                        <i class="ri-map-pin-2-line ri-3x icon-main" style="color: #00bcd4;"></i>
                                                    </div>
                                                </div>
                                                <h5 class="card-title fw-bold mb-2" style="color: #333;">
                                                    Sub Unit PSTOs
                                                </h5>
                                                <p class="card-description text-muted small mb-0">
                                                    Manage sub-unit PSTO mappings
                                                </p>
                                                <div class="arrow-icon position-absolute bottom-0 end-0 p-3">
                                                    <i class="ri-arrow-right-line text-muted"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>

                                <!-- CSF Date Display -->
                                <div class="col-md-4 mb-4">
                                    <Link href="/show-date-csf-form" class="text-decoration-none">
                                        <div class="card card-amazing h-100 border-0 library-card" style="border-radius: 16px;">
                                            <div class="card-body text-center py-4 position-relative">
                                                <div class="icon-container position-relative d-inline-block mb-3">
                                                    <div class="icon-wrapper" style="display: inline-block; padding: 20px; border-radius: 50%; background: linear-gradient(135deg, rgba(156, 39, 176, 0.15) 0%, rgba(103, 58, 183, 0.15) 100%);">
                                                        <i class="ri-calendar-line ri-3x icon-main" style="color: #9c27b0;"></i>
                                                    </div>
                                                </div>
                                                <h5 class="card-title fw-bold mb-2" style="color: #333;">
                                                    CSF Date Display
                                                </h5>
                                                <p class="card-description text-muted small mb-0">
                                                    View and manage CSF form dates
                                                </p>
                                                <div class="arrow-icon position-absolute bottom-0 end-0 p-3">
                                                    <i class="ri-arrow-right-line text-muted"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div>

                                <!-- Additional Card - Reports (if needed) -->
                                <!-- <div class="col-md-4 mb-4">
                                    <Link href="/reports" class="text-decoration-none">
                                        <div class="card card-amazing h-100 border-0 library-card" style="border-radius: 16px;">
                                            <div class="card-body text-center py-4 position-relative">
                                                <div class="icon-container position-relative d-inline-block mb-3">
                                                    <div class="icon-wrapper" style="display: inline-block; padding: 20px; border-radius: 50%; background: linear-gradient(135deg, rgba(233, 30, 99, 0.15) 0%, rgba(233, 30, 99, 0.15) 100%);">
                                                        <i class="ri-bar-chart-line ri-3x icon-main" style="color: #e91e63;"></i>
                                                    </div>
                                                </div>
                                                <h5 class="card-title fw-bold mb-2" style="color: #333;">
                                                    Reports
                                                </h5>
                                                <p class="card-description text-muted small mb-0">
                                                    Generate and view reports
                                                </p>
                                                <div class="arrow-icon position-absolute bottom-0 end-0 p-3">
                                                    <i class="ri-arrow-right-line text-muted"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </Link>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Enhanced card styles */
.library-card {
    transition: all 0.3s ease;
    cursor: pointer;
    background: #fff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.library-card:hover {
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}

.library-card .icon-wrapper {
    transition: all 0.3s ease;
}

.library-card:hover .icon-wrapper {
    transform: scale(1.1);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.library-card .card-title {
    color: #333;
    transition: all 0.3s ease;
}

.library-card:hover .card-title {
    transform: translateY(-2px);
}

.library-card .card-description {
    transition: all 0.3s ease;
}

.library-card:hover .card-description {
    color: #6c757d !important;
}

.library-card .arrow-icon {
    opacity: 0;
    transition: all 0.3s ease;
}

.library-card:hover .arrow-icon {
    opacity: 1;
    transform: translateX(-5px);
}

/* Header decorative elements */
.card-header::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .library-card {
        margin-bottom: 1rem;
    }
    
    .card-header h3 {
        font-size: 1.25rem;
    }
    
    .card-header p {
        font-size: 0.875rem;
    }
}
</style>
