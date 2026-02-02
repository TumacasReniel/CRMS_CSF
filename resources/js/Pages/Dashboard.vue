<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';

import { Head, Link, router } from '@inertiajs/vue3';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="fw-semibold fs-4 text-dark p-3">
                Dashboard
            </h2>
        </template>

        <!-- Welcome Section -->
        <div class="bg-primary text-white p-4 rounded-3 shadow-sm mx-4 mt-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="h2 fw-bold mb-2">Welcome back, {{ $page.props.auth.user.name }}!</h1>
                    <p class="mb-0 opacity-90">Here's what's happening with your CRMS CSF system today.</p>
                </div>
                <div class="d-none d-md-block">
                    <i class="ri-dashboard-line fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

        <!-- Quick Stats Row -->
        <div class="row mx-4 mt-4">
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="bg-primary text-white p-4 rounded-3 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-primary-emphasis small mb-2">Total Surveys</p>
                            <p class="h4 mb-0 fw-bold">1,234</p>
                        </div>
                        <i class="ri-bar-chart-line fs-1 text-primary-emphasis"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="bg-info text-white p-4 rounded-3 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-info-emphasis small mb-2">Active Users</p>
                            <p class="h4 mb-0 fw-bold">567</p>
                        </div>
                        <i class="ri-group-line fs-1 text-info-emphasis"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="bg-success text-white p-4 rounded-3 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-success-emphasis small mb-2">Satisfaction Rate</p>
                            <p class="h4 mb-0 fw-bold">94%</p>
                        </div>
                        <i class="bi bi-star fs-1 text-success-emphasis"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 mb-3">
                <div class="bg-warning text-white p-4 rounded-3 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-warning-emphasis small mb-2">Pending Reviews</p>
                            <p class="h4 mb-0 fw-bold">23</p>
                        </div>
                        <i class="ri-time-line fs-1 text-warning-emphasis"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Cards Row -->
        <div class="row mx-4 mt-4">
            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <Link href="/accounts" class="text-decoration-none">
                    <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden card-amazing" style="border-radius: 24px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transform: translateY(0);">
                        <div class="card-body p-4 text-center position-relative" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(248,249,250,0.9) 100%);">
                            <div class="position-absolute top-0 end-0 opacity-20" style="transform: scale(0.8);">
                                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="40" cy="40" r="40" fill="url(#userGradient)"/>
                                    <defs>
                                        <linearGradient id="userGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:#667eea"/>
                                            <stop offset="100%" style="stop-color:#764ba2"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <div class="icon-container mx-auto mb-4 position-relative" style="width: 90px; height: 90px;">
                                <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center position-absolute w-100 h-100 icon-main" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4), 0 6px 15px rgba(102, 126, 234, 0.2); transition: all 0.4s ease; transform: scale(1);">
                                    <i class="ri-user-line fs-1 text-white"></i>
                                </div>
                                <div class="pulse-ring position-absolute rounded-circle" style="width: 90px; height: 90px; border: 2px solid rgba(102, 126, 234, 0.3); animation: pulse 2s infinite;"></div>
                            </div>
                            <h3 class="fs-5 fw-bold text-dark mb-3 card-title" style="font-family: 'Inter', sans-serif; letter-spacing: -0.02em;">User Accounts</h3>
                            <p class="text-muted small mb-3 card-description" style="line-height: 1.5;">Manage user accounts and permissions with advanced controls</p>
                            <div class="mt-3">
                                <span class="badge bg-primary bg-opacity-15 text-primary px-3 py-2 fw-medium badge-amazing" style="border-radius: 20px; font-size: 0.75rem; border: 1px solid rgba(102, 126, 234, 0.1);">
                                    <i class="ri-group-line me-1"></i>567 Users
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 p-3 card-footer-amazing" style="background: linear-gradient(135deg, rgba(255,255,255,0.8) 0%, rgba(248,249,250,0.8) 100%);">
                            <div class="d-flex align-items-center justify-content-center text-primary fw-medium footer-link" style="transition: all 0.3s ease;">
                                <span class="small me-2">View Details</span>
                                <i class="ri-arrow-right-circle-line fs-5 arrow-icon" style="transition: transform 0.3s ease;"></i>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>

            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <Link href="/service_units" class="text-decoration-none">
                    <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden card-amazing" style="border-radius: 24px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transform: translateY(0);">
                        <div class="card-body p-4 text-center position-relative" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(248,249,250,0.9) 100%);">
                            <div class="position-absolute top-0 end-0 opacity-20" style="transform: scale(0.8);">
                                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="40" cy="40" r="40" fill="url(#serviceGradient)"/>
                                    <defs>
                                        <linearGradient id="serviceGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:#43e97b"/>
                                            <stop offset="100%" style="stop-color:#38f9d7"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <div class="icon-container mx-auto mb-4 position-relative" style="width: 90px; height: 90px;">
                                <div class="bg-gradient-success rounded-circle d-flex align-items-center justify-content-center position-absolute w-100 h-100 icon-main" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); box-shadow: 0 12px 35px rgba(67, 233, 123, 0.4), 0 6px 15px rgba(67, 233, 123, 0.2); transition: all 0.4s ease; transform: scale(1);">
                                    <i class="ri-building-line fs-1 text-white"></i>
                                </div>
                                <div class="pulse-ring position-absolute rounded-circle" style="width: 90px; height: 90px; border: 2px solid rgba(67, 233, 123, 0.3); animation: pulse 2.5s infinite;"></div>
                            </div>
                            <h3 class="fs-5 fw-bold text-dark mb-3 card-title" style="font-family: 'Inter', sans-serif; letter-spacing: -0.02em;">Service Units</h3>
                            <p class="text-muted small mb-3 card-description" style="line-height: 1.5;">Configure service units and departments with ease</p>
                            <div class="mt-3">
                                <span class="badge bg-success bg-opacity-15 text-success px-3 py-2 fw-medium badge-amazing" style="border-radius: 20px; font-size: 0.75rem; border: 1px solid rgba(67, 233, 123, 0.1);">
                                    <i class="ri-building-4-line me-1"></i>24 Units
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 p-3 card-footer-amazing" style="background: linear-gradient(135deg, rgba(255,255,255,0.8) 0%, rgba(248,249,250,0.8) 100%);">
                            <div class="d-flex align-items-center justify-content-center text-success fw-medium footer-link" style="transition: all 0.3s ease;">
                                <span class="small me-2">View Details</span>
                                <i class="ri-arrow-right-circle-line fs-5 arrow-icon" style="transition: transform 0.3s ease;"></i>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>

            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <Link href="/regions" class="text-decoration-none">
                    <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden card-amazing" style="border-radius: 24px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transform: translateY(0);">
                        <div class="card-body p-4 text-center position-relative" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(248,249,250,0.9) 100%);">
                            <div class="position-absolute top-0 end-0 opacity-20" style="transform: scale(0.8);">
                                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="40" cy="40" r="40" fill="url(#regionGradient)"/>
                                    <defs>
                                        <linearGradient id="regionGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:#4facfe"/>
                                            <stop offset="100%" style="stop-color:#00f2fe"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <div class="icon-container mx-auto mb-4 position-relative" style="width: 90px; height: 90px;">
                                <div class="bg-gradient-info rounded-circle d-flex align-items-center justify-content-center position-absolute w-100 h-100 icon-main" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); box-shadow: 0 12px 35px rgba(79, 172, 254, 0.4), 0 6px 15px rgba(79, 172, 254, 0.2); transition: all 0.4s ease; transform: scale(1);">
                                    <i class="ri-map-pin-line fs-1 text-white"></i>
                                </div>
                                <div class="pulse-ring position-absolute rounded-circle" style="width: 90px; height: 90px; border: 2px solid rgba(79, 172, 254, 0.3); animation: pulse 3s infinite;"></div>
                            </div>
                            <h3 class="fs-5 fw-bold text-dark mb-3 card-title" style="font-family: 'Inter', sans-serif; letter-spacing: -0.02em;">Regions</h3>
                            <p class="text-muted small mb-3 card-description" style="line-height: 1.5;">Manage regional offices and locations efficiently</p>
                            <div class="mt-3">
                                <span class="badge bg-info bg-opacity-15 text-info px-3 py-2 fw-medium badge-amazing" style="border-radius: 20px; font-size: 0.75rem; border: 1px solid rgba(79, 172, 254, 0.1);">
                                    <i class="ri-earth-line me-1"></i>17 Regions
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 p-3 card-footer-amazing" style="background: linear-gradient(135deg, rgba(255,255,255,0.8) 0%, rgba(248,249,250,0.8) 100%);">
                            <div class="d-flex align-items-center justify-content-center text-info fw-medium footer-link" style="transition: all 0.3s ease;">
                                <span class="small me-2">View Details</span>
                                <i class="ri-arrow-right-circle-line fs-5 arrow-icon" style="transition: transform 0.3s ease;"></i>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>

            <div class="col-12 col-sm-6 col-md-3 mb-4">
                <Link href="/pstos" class="text-decoration-none">
                    <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden card-amazing" style="border-radius: 24px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); transform: translateY(0);">
                        <div class="card-body p-4 text-center position-relative" style="background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(248,249,250,0.9) 100%);">
                            <div class="position-absolute top-0 end-0 opacity-20" style="transform: scale(0.8);">
                                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="40" cy="40" r="40" fill="url(#pstoGradient)"/>
                                    <defs>
                                        <linearGradient id="pstoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:#f093fb"/>
                                            <stop offset="100%" style="stop-color:#f5576c"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <div class="icon-container mx-auto mb-4 position-relative" style="width: 90px; height: 90px;">
                                <div class="bg-gradient-warning rounded-circle d-flex align-items-center justify-content-center position-absolute w-100 h-100 icon-main" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); box-shadow: 0 12px 35px rgba(240, 147, 251, 0.4), 0 6px 15px rgba(240, 147, 251, 0.2); transition: all 0.4s ease; transform: scale(1);">
                                    <i class="ri-store-line fs-1 text-white"></i>
                                </div>
                                <div class="pulse-ring position-absolute rounded-circle" style="width: 90px; height: 90px; border: 2px solid rgba(240, 147, 251, 0.3); animation: pulse 3.5s infinite;"></div>
                            </div>
                            <h3 class="fs-5 fw-bold text-dark mb-3 card-title" style="font-family: 'Inter', sans-serif; letter-spacing: -0.02em;">PSTOs</h3>
                            <p class="text-muted small mb-3 card-description" style="line-height: 1.5;">Handle PSTO configurations and operations</p>
                            <div class="mt-3">
                                <span class="badge bg-warning bg-opacity-15 text-warning px-3 py-2 fw-medium badge-amazing" style="border-radius: 20px; font-size: 0.75rem; border: 1px solid rgba(240, 147, 251, 0.1);">
                                    <i class="ri-store-2-line me-1"></i>8 PSTOs
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 p-3 card-footer-amazing" style="background: linear-gradient(135deg, rgba(255,255,255,0.8) 0%, rgba(248,249,250,0.8) 100%);">
                            <div class="d-flex align-items-center justify-content-center text-warning fw-medium footer-link" style="transition: all 0.3s ease;">
                                <span class="small me-2">View Details</span>
                                <i class="ri-arrow-right-circle-line fs-5 arrow-icon" style="transition: transform 0.3s ease;"></i>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>
        </div>

        <!-- Charts and Analytics Section -->
        <div class="row mx-4 mt-4">
            <div class="col-12 col-md-6 mb-4">
                <div class="bg-white text-dark p-4 rounded-3 shadow-sm">
                    <h3 class="h5 fw-semibold mb-4 d-flex align-items-center">
                        <i class="ri-bar-chart-line me-2 text-primary"></i>
                        Satisfaction Trends
                    </h3>
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small">Very Satisfied</span>
                            <span class="small fw-medium">85%</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: 85%;"></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small">Satisfied</span>
                            <span class="small fw-medium">12%</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: 12%;"></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small">Neutral</span>
                            <span class="small fw-medium">2%</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: 2%;"></div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small">Dissatisfied</span>
                            <span class="small fw-medium">1%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-danger" style="width: 1%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-4">
                <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden" style="border-radius: 20px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);">
                    <div class="card-body p-4 position-relative">
                        <div class="position-absolute top-0 end-0 opacity-25">
                            <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="50" cy="50" r="50" fill="#4facfe"/>
                            </svg>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-gradient-info rounded-3 p-3 me-3" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <i class="bi bi-pie-chart-fill fs-4 text-white"></i>
                            </div>
                            <div>
                                <h3 class="h5 font-weight-bold text-dark mb-1">Survey Distribution</h3>
                                <p class="small text-muted mb-0">Response breakdown by category</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center mb-4">
                            <div class="position-relative">
                                <!-- Enhanced SVG Pie Chart -->
                                <svg viewBox="0 0 200 200" class="w-100 h-100" style="width: 160px; height: 160px;">
                                    <!-- Very Satisfied - Green (85%) -->
                                    <path d="M100 100 L100 20 A80 80 0 0 1 180 100 Z" fill="url(#verySatisfied)" stroke="#fff" stroke-width="2"/>
                                    <!-- Satisfied - Blue (12%) -->
                                    <path d="M100 100 L180 100 A80 80 0 0 1 170 140 Z" fill="url(#satisfied)" stroke="#fff" stroke-width="2"/>
                                    <!-- Neutral - Yellow (2%) -->
                                    <path d="M100 100 L170 140 A80 80 0 0 1 166 150 Z" fill="url(#neutral)" stroke="#fff" stroke-width="2"/>
                                    <!-- Dissatisfied - Orange (1%) -->
                                    <path d="M100 100 L166 150 A80 80 0 0 1 100 20 Z" fill="url(#dissatisfied)" stroke="#fff" stroke-width="2"/>
                                    <defs>
                                        <linearGradient id="verySatisfied" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:#28a745"/>
                                            <stop offset="100%" style="stop-color:#20c997"/>
                                        </linearGradient>
                                        <linearGradient id="satisfied" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:#007bff"/>
                                            <stop offset="100%" style="stop-color:#6610f2"/>
                                        </linearGradient>
                                        <linearGradient id="neutral" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:#ffc107"/>
                                            <stop offset="100%" style="stop-color:#fd7e14"/>
                                        </linearGradient>
                                        <linearGradient id="dissatisfied" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" style="stop-color:#dc3545"/>
                                            <stop offset="100%" style="stop-color:#e83e8c"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle text-center">
                                    <div class="h3 font-weight-bold text-dark mb-0">1,234</div>
                                    <div class="small text-muted fw-medium">Total Surveys</div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="d-flex align-items-center p-2 bg-light rounded-3">
                                    <div class="bg-success rounded-circle me-2 flex-shrink-0" style="width: 14px; height: 14px;"></div>
                                    <div>
                                        <div class="small fw-bold text-dark">85%</div>
                                        <div class="small text-muted">Very Satisfied</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center p-2 bg-light rounded-3">
                                    <div class="bg-primary rounded-circle me-2 flex-shrink-0" style="width: 14px; height: 14px;"></div>
                                    <div>
                                        <div class="small fw-bold text-dark">12%</div>
                                        <div class="small text-muted">Satisfied</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center p-2 bg-light rounded-3">
                                    <div class="bg-warning rounded-circle me-2 flex-shrink-0" style="width: 14px; height: 14px;"></div>
                                    <div>
                                        <div class="small fw-bold text-dark">2%</div>
                                        <div class="small text-muted">Neutral</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center p-2 bg-light rounded-3">
                                    <div class="bg-danger rounded-circle me-2 flex-shrink-0" style="width: 14px; height: 14px;"></div>
                                    <div>
                                        <div class="small fw-bold text-dark">1%</div>
                                        <div class="small text-muted">Dissatisfied</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

     

 
    </AppLayout>
</template>
