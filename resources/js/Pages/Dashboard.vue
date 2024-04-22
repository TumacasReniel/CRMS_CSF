<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';

import { Head, Link, router } from '@inertiajs/vue3';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

       <v-row class="mx-15 mt-5">
                <Link :href="route('accounts')">
                    <div class="py-5 ml-5 mr-5 " style="width:250px">
                        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <v-icon size="x-large" class="p-3" >mdi-account</v-icon>
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                    Accounts
                                </h5>
                            </a>  

                        </div>
                    </div>     
                 </Link>
            

                <Link :href="route('services_units')">
                    <div class="py-5 ml-5 mr-5 " style="width:250px">
                        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <v-icon size="x-large" class="p-3" >mdi-domain</v-icon>
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                    Services Units
                                </h5>
                            </a>  

                        </div>
                    </div>     
                </Link>

                    <Link :href="route('regions')">
                    <div class="py-5 ml-5 mr-5 " style="width:250px">
                        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <v-icon size="x-large" class="p-3" >mdi-domain</v-icon>
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                    Regions
                                </h5>
                            </a>  

                        </div>
                    </div>     
                </Link>

                   <Link :href="route('regions')">
                    <div class="py-5 ml-5 mr-5 " style="width:250px">
                        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <v-icon size="x-large" class="p-3" >mdi-domain</v-icon>
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                    PSTOs
                                </h5>
                            </a>  

                        </div>
                    </div>     
                </Link>
         
        </v-row>
<!-- 
          <v-row class="mx-15 mt-5">
                <Link :href="route('accounts')">
                    <div class="py-5 ml-5 mr-5 " style="width:250px">
                        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <v-icon size="x-large" class="p-3" >mdi-account</v-icon>
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                    Unit PSTOs
                                </h5>
                            </a>  

                        </div>
                    </div>     
                 </Link>
            

                <Link :href="route('services_units')">
                    <div class="py-5 ml-5 mr-5 " style="width:250px">
                        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <v-icon size="x-large" class="p-3" >mdi-domain</v-icon>
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                    Sub Unit PSTOs
                                </h5>
                            </a>  

                        </div>
                    </div>     
                </Link>

         
        </v-row> -->


 
    </AppLayout>
</template>
