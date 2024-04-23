<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { reactive, watch, ref, onMounted } from "vue";
 import AOS from 'aos'
import 'aos/dist/aos.css'

import * as THREE from 'three';

AOS.init();

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});


</script>

<template >
    <Head title="Welcome" />   
     <nav 
        data-aos="fade-down" 
        data-aos-duration="500" 
        data-aos-delay="500" 
         style="backdrop-filter: blur(2px);"
        class="bg-transparent dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="../../../public/images/dost-logo.jpg" class="h-8" alt="DOST Logo">
                <span class="self-center text-2xl font-semibold whitespace-nowrap">Department of Science and Technology</span>
            </a>
            <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                    <a :href="route('login')">
                        <button type="button" class="mr-3 ml-5 text-blue-800 bg-gray-200 hover:bg-blue-800 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Login
                        </button>
                    </a>
                
                <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-sticky" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
            </div>
        <!-- <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            <ul class="bg-transparent text-white flex flex-col p-4 md:p-0 pb-3 mt-4 font-medium  rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <li>
                        <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100  md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">About</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100  md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Contact</a>
                    </li>
                </ul>
        </div> -->
    </div>


    </nav> 



    <div style="display: flex;
                justify-content: center;
                align-items: center;
                color: white;
                width: 100%;     
                "
                 data-aos="zoom-out" 
                data-aos-duration="500" 
                data-aos-delay="500" >
        <h1 style="font-size: 70px; font-weight: bold; text-shadow: -2px -2px 4px black, 5px 5px 10px black;text-align:center; margin-left: 10%;margin-right: 10%; ">Customer Relation Management System</h1>
    </div>


</template>


<style>
    #app {
        background: url(https://cdn.dribbble.com/users/6117646/screenshots/14975149/media/8f26446e227baeb76f1ae01e8dc1c558.gif);
         width:100vw;
         height: 100vh;
         z-index:1;
         margin: 0;
        padding: 0;
        background-color: white;
        display: flex;
        justify-content: center;
        align-items: center;
    }

</style>
