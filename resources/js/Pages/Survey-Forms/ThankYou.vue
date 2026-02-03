<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    message: String,
    status: String,
    current_url: String,
});
</script>

<template>
    <Head title="CSF Submission" />
    <div class="container-fluid bg-light min-vh-100 d-flex flex-column">
        <nav class="navbar navbar-light bg-white border-bottom">
            <div class="container-fluid">
                <a href="/" class="navbar-brand d-flex align-items-center">
                    <img src="../../../../public/images/dost-logo.jpg" class="me-3" style="height: 32px;" alt="DOST Logo">
                    <span class="fw-bold fs-4">Department of Science and Technology</span>
                </a>
            </div>
        </nav>

        <div class="flex-grow-1 d-flex align-items-center justify-content-center">
            <div class="card shadow-lg" style="max-width: 600px; width: 100%;">
                <div class="card-body text-center p-5">
                    <div v-if="status == 'success'">
                        <i class="bi bi-check-circle-fill text-success mb-4" style="font-size: 4rem;"></i>
                        <h2 class="card-title mb-4">{{ message }}</h2>
                        <p class="card-text text-muted">
                            Thank you for your feedback.<br>
                            Have a good day!
                        </p>
                    </div>
                    <div v-else-if="status == 'error'">
                        <i class="bi bi-exclamation-triangle-fill text-danger mb-4" style="font-size: 4rem;"></i>
                        <h2 class="card-title mb-4">{{ message }}</h2>
                        <p class="card-text text-muted">
                            Something went wrong, please check. Thank you.
                        </p>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex justify-content-end">
                        <Link :href="props.current_url" class="btn btn-success px-4">
                            Okay
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
