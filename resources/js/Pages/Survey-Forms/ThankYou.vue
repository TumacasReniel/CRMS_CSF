<script setup>
import { Head, Link } from '@inertiajs/vue3';

import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';

defineProps({
    message: String,
});


</script>

<template>
    <Head title="Thank you" />
     <nav 
        class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600 mb-10">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="../../../../public/images/dost-logo.jpg" class="h-8" alt="DOST Logo">
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Department of Science and Technology</span>
            </a>
        </div>
    </nav>
<v-row>
    <v-sheet
         class="pa-4 text-center mx-auto"
        elevation="12"
        max-width="600"
        rounded="lg"
        width="100%"
        style="margin-top: 200px"
    >
        <v-icon
        class="mb-5"
        color="success"
        icon="mdi-check-circle"
        size="112"
        ></v-icon>

        <h2 class="text-h5 mb-6">{{ messsage ? message: "Submitted Successfully, Thank you." }}</h2>

        <p class="mb-4 text-medium-emphasis text-body-2">
        Thank you for your feedback.
        <br>

        Have a good day!
        </p>

        <v-divider class="mb-4"></v-divider>

        <div class="text-end">
            <Link :href="route('csf_form')">
                <v-btn
                    class="text-none"
                    color="success"
                    variant="flat"
                    width="90"
                    rounded
                >
                    Done
                </v-btn>
            </Link>
        </div>
    </v-sheet>
   
  </v-row>
</template>
