<script setup>
import { Head, Link } from '@inertiajs/vue3';
 import AOS from 'aos'
import 'aos/dist/aos.css'
AOS.init();

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

</script>

<template>
    <Head title="Welcome" />    

    <nav 
        data-aos="fade-down" 
        data-aos-duration="500" 
        data-aos-delay="500" 
        class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="../../../public/images/dost-logo.jpg" class="h-8" alt="DOST Logo">
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white text-black">Department of Science and Technology</span>
    </a>
    <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            <Link :href="route('login')">
                <button type="button" class="mr-3 ml-5 text-blue-800 bg-gray-200 hover:bg-blue-800 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Login
                </button>
            </Link>
        
        <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-sticky" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
            </svg>
        </button>
    </div>
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                    <li>
                        <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">About</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Contact</a>
                    </li>
                    <li>
                        <button id="mega-menu-full-dropdown-button" data-collapse-toggle="mega-menu-full-dropdown" class="flex items-center justify-between w-full py-2 px-3 text-gray-900 rounded md:w-auto hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-600 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700">
                            Regional Offices <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                    </li>
                </ul>
        </div>
    </div>
    <div id="mega-menu-full-dropdown" class="mt-1 border-gray-200 shadow-sm bg-gray-50 md:bg-white border-y dark:bg-gray-800 dark:border-gray-600">
        <div class="grid max-w-screen-xl px-4 py-5 mx-auto text-gray-900 dark:text-white sm:grid-cols-4 md:px-6">
            <ul>
                <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-I</div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                        Click for more Details
                        <v-icon>mdi-arrow-forward</v-icon>
                    </span>
                    </a>
                </li>
                <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-II</div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Click for more Details
                            <v-icon>mdi-arrow-forward</v-icon>
                        </span>
                    </a>
                </li>
                 <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-III</div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                        Click for more Details
                        <v-icon>mdi-arrow-forward</v-icon>
                    </span>
                    </a>
                </li>
                <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-IVA</div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Click for more Details
                            <v-icon>mdi-arrow-forward</v-icon>
                        </span>
                    </a>
                </li>
                 <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-IVB</div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Click for more Details
                            <v-icon>mdi-arrow-forward</v-icon>
                        </span>
                    </a>
                </li>
             
            </ul>
            <ul>
                   <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-V</div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                        Click for more Details
                        <v-icon>mdi-arrow-forward</v-icon>
                    </span>
                    </a>
                </li>
                <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-VI</div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Click for more Details
                            <v-icon>mdi-arrow-forward</v-icon>
                        </span>
                    </a>
                </li>
                 <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-VII</div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                        Click for more Details
                        <v-icon>mdi-arrow-forward</v-icon>
                    </span>
                    </a>
                </li>
                <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-VIII</div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Click for more Details
                            <v-icon>mdi-arrow-forward</v-icon>
                        </span>
                    </a>
                </li>
            </ul>
            <ul>
                    <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-IX</div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                        Click for more Details
                        <v-icon>mdi-arrow-forward</v-icon>
                    </span>
                    </a>
                </li>
                <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-X</div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Click for more Details
                            <v-icon>mdi-arrow-forward</v-icon>
                        </span>
                    </a>
                </li>
                 <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-XI</div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                        Click for more Details
                        <v-icon>mdi-arrow-forward</v-icon>
                    </span>
                    </a>
                </li>
                <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-XII</div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Click for more Details
                            <v-icon>mdi-arrow-forward</v-icon>
                        </span>
                    </a>
                </li>
            </ul>
            
                  <ul>
                   <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-ARMM</div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                        Click for more Details
                        <v-icon>mdi-arrow-forward</v-icon>
                    </span>
                    </a>
                </li>
                <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-CAR</div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Click for more Details
                            <v-icon>mdi-arrow-forward</v-icon>
                        </span>
                    </a>
                </li>
                 <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-CARAGA</div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                        Click for more Details
                        <v-icon>mdi-arrow-forward</v-icon>
                    </span>
                    </a>
                </li>
                <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-NCR</div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Click for more Details
                            <v-icon>mdi-arrow-forward</v-icon>
                        </span>
                    </a>
                </li>
                   <li>
                    <a href="#" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="font-semibold">DOST-ROS</div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Click for more Details
                            <v-icon>mdi-arrow-forward</v-icon>
                        </span>
                    </a>
                </li>
            </ul>
         </div>
    </div>
    </nav> 
    


</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
