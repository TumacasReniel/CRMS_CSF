<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="vh-100 d-flex align-items-center justify-content-center position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 50%, #312e81 100%);">
        <!-- Animated Background Elements -->
        <div class="position-absolute w-100 h-100">
            <div class="position-absolute bg-primary rounded-circle opacity-25 animate-pulse" style="top: 10%; left: 10%; width: 8rem; height: 8rem;"></div>
            <div class="position-absolute bg-info rounded-circle opacity-25 animate-pulse" style="bottom: 10%; right: 10%; width: 6rem; height: 6rem; animation-delay: 1s;"></div>
            <div class="position-absolute bg-warning rounded-circle opacity-25 animate-pulse" style="top: 50%; left: 50%; width: 4rem; height: 4rem; animation-delay: 2s; transform: translate(-50%, -50%);"></div>
        </div>

        <!-- Tech Animation Elements -->
        <div class="position-absolute w-100 h-100 overflow-hidden">
            <!-- Moving Circuit Lines -->
            <div class="position-absolute w-100 h-100">
                <svg class="w-100 h-100 opacity-25" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="circuit-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#0d6efd;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#6610f2;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <!-- Horizontal Lines -->
                    <line x1="0" y1="200" x2="1000" y2="200" stroke="url(#circuit-gradient)" stroke-width="2" class="animate-pulse">
                        <animate attributeName="stroke-dasharray" values="0,1000;1000,0" dur="8s" repeatCount="indefinite"/>
                    </line>
                    <line x1="0" y1="400" x2="1000" y2="400" stroke="url(#circuit-gradient)" stroke-width="2" class="animate-pulse">
                        <animate attributeName="stroke-dasharray" values="0,1000;1000,0" dur="10s" repeatCount="indefinite"/>
                    </line>
                    <line x1="0" y1="600" x2="1000" y2="600" stroke="url(#circuit-gradient)" stroke-width="2" class="animate-pulse">
                        <animate attributeName="stroke-dasharray" values="0,1000;1000,0" dur="12s" repeatCount="indefinite"/>
                    </line>
                    <line x1="0" y1="800" x2="1000" y2="800" stroke="url(#circuit-gradient)" stroke-width="2" class="animate-pulse">
                        <animate attributeName="stroke-dasharray" values="0,1000;1000,0" dur="9s" repeatCount="indefinite"/>
                    </line>
                    <!-- Vertical Lines -->
                    <line x1="200" y1="0" x2="200" y2="1000" stroke="url(#circuit-gradient)" stroke-width="2" class="animate-pulse">
                        <animate attributeName="stroke-dasharray" values="0,1000;1000,0" dur="11s" repeatCount="indefinite"/>
                    </line>
                    <line x1="400" y1="0" x2="400" y2="1000" stroke="url(#circuit-gradient)" stroke-width="2" class="animate-pulse">
                        <animate attributeName="stroke-dasharray" values="0,1000;1000,0" dur="13s" repeatCount="indefinite"/>
                    </line>
                    <line x1="600" y1="0" x2="600" y2="1000" stroke="url(#circuit-gradient)" stroke-width="2" class="animate-pulse">
                        <animate attributeName="stroke-dasharray" values="0,1000;1000,0" dur="7s" repeatCount="indefinite"/>
                    </line>
                    <line x1="800" y1="0" x2="800" y2="1000" stroke="url(#circuit-gradient)" stroke-width="2" class="animate-pulse">
                        <animate attributeName="stroke-dasharray" values="0,1000;1000,0" dur="15s" repeatCount="indefinite"/>
                    </line>
                    <!-- Diagonal Lines -->
                    <line x1="0" y1="0" x2="1000" y2="1000" stroke="url(#circuit-gradient)" stroke-width="1" class="animate-pulse">
                        <animate attributeName="stroke-dasharray" values="0,1414;1414,0" dur="20s" repeatCount="indefinite"/>
                    </line>
                    <line x1="1000" y1="0" x2="0" y2="1000" stroke="url(#circuit-gradient)" stroke-width="1" class="animate-pulse">
                        <animate attributeName="stroke-dasharray" values="0,1414;1414,0" dur="18s" repeatCount="indefinite"/>
                    </line>
                </svg>
            </div>

            <!-- Floating Geometric Shapes -->
            <div class="position-absolute border border-info rotate-45 animate-bounce" style="top: 25%; left: 25%; width: 2rem; height: 2rem; animation-duration: 6s;"></div>
            <div class="position-absolute border border-primary rounded-circle animate-spin" style="top: 75%; right: 25%; width: 1.5rem; height: 1.5rem; animation-duration: 8s; animation-delay: 2s;"></div>
            <div class="position-absolute bg-warning opacity-50 animate-ping" style="top: 50%; left: 50%; width: 1rem; height: 1rem; animation-duration: 4s; animation-delay: 1s; transform: translate(-50%, -50%);"></div>
            <div class="position-absolute border border-light opacity-50 rotate-12 animate-pulse" style="bottom: 25%; right: 33%; width: 2.5rem; height: 2.5rem; animation-duration: 5s; animation-delay: 3s;"></div>
            <div class="position-absolute bg-light opacity-75 rounded-circle animate-bounce" style="top: 17%; right: 17%; width: 0.75rem; height: 0.75rem; animation-duration: 7s; animation-delay: 1.5s;"></div>
        </div>

        <!-- Login Card -->
        <div class="position-relative z-index-10 w-100" style="max-width: 28rem;">
            <div class="bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-20 rounded-3 p-5 shadow-lg">
                <!-- Logo -->
                <b-row>
                 
                <center class="mb-3"><h7 class="text-white text-center mb-3">Department of Science and Technology </h7> </center>
                 
                 </b-row>
                <div class="text-center mb-4">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 4rem; height: 4rem;">
                        <svg class="text-white" style="width: 2rem; height: 2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    
                    <h1 class="h3 font-weight-bold text-white animate-fade-in">Welcome Back</h1>
                    <p class="text-light animate-fade-in" style="animation-delay: 0.2s;">Sign in to your account</p>
                </div>

                <!-- Status Message -->
                <div v-if="status" class="mb-3 p-3 bg-success bg-opacity-25 border border-success border-opacity-50 rounded">
                    <p class="text-success mb-0 small font-weight-medium">{{ status }}</p>
                </div>

                <!-- Login Form -->
                <form @submit.prevent="submit" class="mb-0">
                    <!-- Email Field -->
                    <div class="mb-3 animate-fade-in" style="animation-delay: 0.3s;">
                        <label for="email" class="form-label text-light small font-weight-medium">Email Address</label>
                        <div class="position-relative">
                            <div class="position-absolute top-50 start-0 translate-middle-y ps-3">
                                <svg class="text-muted" style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="form-control bg-white text-dark  border-secondary"
                                placeholder="Enter your email"
                                required
                                autofocus
                                autocomplete="username"
                                style="padding-left: 2.5rem;"
                            />
                        </div>
                        <div v-if="form.errors.email" class="mt-2 text-danger small">
                            {{ form.errors.email }}
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3 animate-fade-in" style="animation-delay: 0.4s;">
                        <label for="password" class="form-label text-light small font-weight-medium">Password</label>
                        <div class="position-relative">
                            <div class="position-absolute top-50 start-0 translate-middle-y ps-3">
                                <svg class="text-muted" style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="form-control bg-white text-dark border-secondary"
                                placeholder="Enter your password"
                                required
                                autocomplete="current-password"
                                style="padding-left: 2.5rem;"
                            />
                        </div>
                        <div v-if="form.errors.password" class="mt-2 text-danger small">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check mb-3 animate-fade-in" style="animation-delay: 0.5s;">
                        <input
                            id="remember"
                            v-model="form.remember"
                            type="checkbox"
                            class="form-check-input"
                        />
                        <label for="remember" class="form-check-label text-light small">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="animate-fade-in" style="animation-delay: 0.6s;">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="btn btn-primary w-100 d-flex align-items-center justify-content-center"
                            :class="{ 'disabled': form.processing }"
                        >
                            <span v-if="form.processing" class="d-flex align-items-center">
                                <div class="spinner-border spinner-border-sm me-2" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                Signing in...
                            </span>
                            <span v-else>Sign In</span>
                        </button>
                    </div>
                </form>

                <!-- Additional Links -->
                <div class="mt-3 text-center animate-fade-in" style="animation-delay: 0.7s;">
                    <a v-if="canResetPassword" href="/forgot-password" class="text-primary text-decoration-none small">
                        Forgot your password?
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .animate-fade-in {
        animation: fadeIn 0.8s ease-out forwards;
        opacity: 0;
    }

    @keyframes fadeIn {
        to {
            opacity: 1;
        }
    }
</style>
