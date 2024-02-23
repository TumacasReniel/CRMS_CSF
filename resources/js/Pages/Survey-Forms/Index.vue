<script>
import { ref, onMounted } from 'vue';
import SignaturePad from 'signature_pad';
import AOS from 'aos';
import 'aos/dist/aos.css';

export default {
  data() {
    return {
      cc1_options: [
        { label: "1. I know what a CC is and I saw this office's CC.", value: '1' },
        { label: "2. I know what a CC is but I did NOT see this office's CC. ", value: '2' },
        { label: "3. I learned the CC when I saw this office's CC.", value: '3' },
        { label: "4. I do not know what a CC is  and I did not see one in this office.(Answer 'N/A' on CC2 and CC3)", value: '4' },
      ],
      cc2_options: [
        { label: "1. Easy to see", value: '1' },
        { label: "2. Somewhat easy to see", value: '2' },
        { label: "3. Difficult to see", value: '3' },
        { label: "4. not Visible at all", value: '4' },
        { label: "5. N/A", value: '5' },
      ],
      cc3_options: [
        { label: "1. Helped Very Much", value: '1' },
        { label: "2. Somewhat helped", value: '2' },
        { label: "3. Did not help", value: '3' },
        { label: "4. N/A", value: '4' },
      ],
      options: [
        { label: 'Very Satisfied', value: 'very-satisfied', icon: 'mdi-emoticon-cool', color: '#FFEB3B' },
        { label: 'Satisfied', value: 'satisfied', icon: 'mdi-emoticon-happy', color: '#FFC107' },
        { label: 'Neither', value: 'neither', icon: 'mdi-emoticon-neutral', color: '#263238' },
        { label: 'Dissatisfied', value: 'dissatisfied', icon: 'mdi-emoticon-sad', color: '#F44336' },
        { label: 'Very Dissatisfied', value: 'very-dissatisfied', icon: 'mdi-emoticon-devil', color: '#6200EA' },
      ],
      attribute_numbers: [
        { label: '5', value: '5' },
        { label: '4', value: '4' },
        { label: '3', value: '3' },
        { label: '2', value: '2' },
        { label: '1', value: '1' },
      ],
      recommendation_numbers: [
        { label: '10', value: '10' },
        { label: '9', value: '9' },
        { label: '8', value: '8' },
        { label: '7', value: '7' },
        { label: '6', value: '6' },
        { label: '5', value: '5' },
        { label: '4', value: '4' },
        { label: '3', value: '3' },
        { label: '2', value: '2' },
        { label: '1', value: '1' },
      ],
    
      form: this.$inertia.form({
        email: null,
        name: null,
        client_type: null,
        sex: null,
        age_group: null,
        cc1: null,
        cc2: null,
        cc3: null,
        responsiveness: null,
        responsiveness_attr_number: null,
        reliability: null,
        reliability_attr_number: null,
        access_and_facilities: null,
        access_and_facilities_attr_number: null,
        communication: null,
        communication_attr_number: null,
        integrity: null,
        integrity_attr_number: null,
        assurance: null,
        assurance_attr_number: null,
        outcome: null,
        outcome_attr_number: null,
        recommend_rate: null,
        comment:null,
        other_attr_indication: null,
        signature: null,
      }),
       signaturePad: null,
       canvas: null,
    };
  },
  methods: {
    saveCSF: async function () {
      this.form.signature = this.signaturePad;  
      await this.form.post('/csf_submission',);
    },
    clearSignature: function () {
        new SignaturePad(this.signaturePad);
    }

  },
  setup() {
    const signaturePad = ref(null);
        onMounted(() => {
            AOS.init();
            signaturePad.value = new SignaturePad(signaturePad.value);
            // Set canvas dimensions
            const canvas = signaturePad.value;
            canvas.width = 400;
            canvas.height = 200;
        });

    return {
      signaturePad,
    };
  },

};

</script>
<template>
    <Head title="Survey Form" />

     <nav 
         data-aos="fade-down" 
        data-aos-duration="500" 
        data-aos-delay="500" 
        class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="../../../../public/images/dost-logo.jpg" class="h-8" alt="DOST Logo">
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Department of Science and Technology</span>
            </a>
        </div>
    </nav>

    <v-card
        data-aos="fade-up" 
        data-aos-duration="2000" 
        data-aos-delay="500" 
    >
        <v-row justify="center" class="py-3 bg-gray-200">
            <v-form class="max-w" @submit.prevent="saveCSF">
                <div class="py-20 bg-gray-200 mx-auto">
                    <v-card class="mb-5 mx-auto" width="1000">
                    <v-card-title class="text-center m-5 font-black">
                            <img
                                data-aos="zoom-in" 
                                data-aos-duration="500" 
                                data-aos-delay="500"
                                class="mx-auto mb-5" style="width:200px; height:200px" src="../../../../public/images/dost-logo.jpg" alt="..">
                            <span class="font-black lg:text-4xl"  
                                data-aos="fade-down" 
                                data-aos-duration="500" 
                                data-aos-delay="500"
                                >CUSTOMER SATISFACTION FEEDBACK</span>
                    </v-card-title>
                    
                    </v-card>
            
                    <v-card 
                        data-aos="zoom-out-up" 
                        data-aos-duration="1000" 
                        data-aos-delay="500" 
                        class="mb-5 mx-auto"
                        width="1000"
                        >
                        <a href="#">
                            <img class="rounded-t-lg" src="/docs/images/blog/image-1.jpg" alt="" />
                        </a>
                        <div class="p-5">
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Information Communication Technology</h5>
                            </a>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">This questionaire aims to solicit your honest assessment of our services. Please take a minute in filling out this form and help us serve you better.</p>
                            <div>
                                <div class="mb-5">
                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Email
                                        <span class="text-red-800">*</span>
                                    </label>
                                    <input v-model="form.email" type="email" id="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="name@gmail.com" required />
                                </div>

                                <div class="mb-5">
                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Name
                                        (<span class="text-blue-500">Optional</span>)
                                    </label>
                                    <input v-model="form.name" type="text" id="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="e.g. Juan Dela Cruz" required />
                                </div>

                                <div class="mb-5 grid grid-cols-3 gap-4">
                                    <div class="col-span-1">
                                        <label for="client_types" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                Client Types
                                            <span class="text-red-800">*</span>
                                            </label>
                                            <select v-model="form.client_type" id="client_types" placeholder="Select Client Types" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option>Select Client Types..</option>
                                                <option>Internal Employees</option>
                                                <option>General Public</option>
                                                <option>Government Employees</option>
                                                <option>Business/Organization</option>
                                            </select>
                                    </div>

                                    <div class="col-span-1">
                                        <label for="sex" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Sex
                                        <span class="text-red-800">*</span>
                                        </label>
                                        <select v-model="form.sex" id="sex" placeholder="Select Client Types" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option>Select Sex.. </option>
                                            <option>Male</option>
                                            <option>Female</option>
                                        </select>
                                    </div>

                                    <div class="col-span-1">
                                        <label for="age_group" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                Age Group
                                            <span class="text-red-800">*</span>
                                            </label>
                                            <select v-model="form.age_group" id="age_group" placeholder="Select Client Types" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option>Select Age Group..</option>
                                                <option>15-19</option>
                                                <option>20-29</option>
                                                <option>30-39</option>
                                                <option>40-49</option>
                                                <option>50-59</option>
                                                <option>60-69</option>
                                                <option>70-79</option>
                                                <option>80+</option>
                                            </select>
                                    </div>

                                </div>

                                <div class="border border-w-2 p-3 mb-5">
                                    <div>
                                        Other Informations
                                        (<span class="text-blue-500">Optional</span>)
                                    </div>
                                    <div class="grid grid-cols-4 gap-4">

                                        <div class="flex items-center ps-4   rounded ">
                                            <input v-model="form.digital_literacy" id="bordered-checkbox-1" type="checkbox" value="" name="bordered-checkbox" class="w-4 h-4 text-blue-600 bg-gray-100  rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-checkbox-1" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Digital Literacy</label>
                                        </div>
                                        <div class="flex items-center ps-4  rounded">
                                            <input v-model="form.pwd" id="bordered-checkbox-2" type="checkbox" value="" name="bordered-checkbox" class="w-4 h-4 text-blue-600 bg-gray-100  rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-checkbox-2" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Person with Disability</label>
                                        </div>
                                        <div class="flex items-center ps-4  rounded ">
                                            <input  v-model="form.pregnant" id="bordered-checkbox-3" type="checkbox" value="" name="bordered-checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-checkbox-3" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Pregnant Woman</label>
                                        </div>
                                        <div class="flex items-center ps-4   rounded ">
                                            <input  v-model="form.senior_citizen" id="bordered-checkbox-4" type="checkbox" value="" name="bordered-checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="bordered-checkbox-4" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Senior Citizen</label>
                                        </div>

                                    </div>
                                </div>


                            </div>
                        </div>
                    </v-card >
                
                    <v-card 
                        data-aos="zoom-out-up" 
                        data-aos-duration="1000" 
                        data-aos-delay="500" 
                        class="mb-5 mx-auto" width="1000"
                    >
                        <!-- CC1 -->
                        <div>
                            <v-card-title class=" m-5 font-black mb-10">
                                CC1. Which of the following best describes your awareness of a CC?
                            </v-card-title>
                            <div v-for="(option, index) in cc1_options" :key="index">
                                <v-checkbox color="primary" style="margin-top:-50px; margin-left:7%; margin-bottom: -5%" v-model="form.cc1" :label="option.label" :value="option.value"></v-checkbox>
                            </div>
        
                        </div>

                        <!-- CC2 -->
                        <div >
                            <v-card-title class=" m-5 mb-10">
                                    CC2. If aware of CC (anwered 1-3 in CC1), would say that the CC of this was...?
                            </v-card-title>
                    
                            <div v-for="(option, index) in cc2_options" :key="index">
                                <v-checkbox color="primary" style="margin-top:-50px; margin-left:7%; margin-bottom: -5%" v-model="form.cc2" :label="option.label" :value="option.value"></v-checkbox>
                            </div>
                        </div>
            

                    <!-- CC3 -->
                        <div>
                            <v-card-title class=" m-5 mb-10">
                                CC3. If aware of CC (anwered 1-3 in CC1), how much did the CC help you in your transaction?
                            </v-card-title>
                            <div v-for="(option, index) in cc3_options" :key="index" style="color:!unimportant">
                                <v-checkbox color="primary" style="margin-top:-50px; margin-left:7%" v-model="form.cc3" :label="option.label" :value="option.value"></v-checkbox>
                            </div>     
                        </div> 
                    </v-card>
                
                    <v-card 
                        data-aos="zoom-out-up" 
                        data-aos-duration="1000" 
                        data-aos-delay="500" 
                        class="mb-5 mx-auto" width="1000">
                        <v-card-title class="text-lg text-white bg-blue_grey p-3">
                            HOW WOULD YOU RATE OUR INFORMATION COMMUNICATION TECHNOLOGY SERVICES?
                        </v-card-title>
                        <div>
                            <v-card
                                data-aos="fade-left" 
                                data-aos-duration="1000" 
                                data-aos-delay="500" 
                                class="text-center"
                                border="1"
                            >
                                <v-card-title class="text-4xl mt-5 mb-3">
                                    Responsiveness
                                </v-card-title>
                                <v-card-content>
                                    <div class="ml-2 mb-3">
                                        <v-btn-toggle class="mb-5"  v-model="form.responsiveness"  v-for="option in options" :key="option.value" >
                                                <v-btn class="mr-2 bg-secondary " :value="option.value" >
                                                    <v-icon :color="form.responsiveness === option.value ? option.color : 'blue'" size="40">{{ option.icon }}</v-icon><br>
                                                    <label >{{ option.label }}</label>
                                                </v-btn>
                                        </v-btn-toggle>
                                    </div> 

                                    <v-card>
                                        <v-card-title>How important is this attibute?</v-card-title>
                                        <v-card-content>
                                            <div class="ml-2 mb-3">
                                                <v-btn-toggle  v-model="form.responsiveness_attr_number"  v-for="option in attribute_numbers "  :key="option.value"  mandatory>
                                                    <v-btn                      
                                                        class=" mr-2"
                                                        :value="option.value"                        
                                                        color="secondary"
                                                        style="border-radius:40%;"                         
                                                    >
                                                    <v-chip >
                                                        <label >{{ option.label }}</label>
                                                    </v-chip>
                                                    </v-btn>

                                                </v-btn-toggle>
                                            </div>
                                        </v-card-content>

                                    </v-card>
                                </v-card-content>
                            </v-card>
                            <v-divider :thickness="5" color="success" class="border-opacity-100"></v-divider> 
                            <v-card
                                class="text-center bg-none"
                                data-aos="fade-right" 
                                data-aos-duration="1000" 
                                data-aos-delay="500" 
                            >
                                <v-card-title justify="center" class="mt-10 mb-2">Reliability</v-card-title>

                                <div class="ml-2 mb-3">
                                    <v-btn-toggle class="mb-5" v-model="form.reliability"  v-for="option in options" :key="option.value" >
                                        <v-btn class="bg-secondary mr-2" :value="option.value" >
                                            <v-icon :color="form.reliability === option.value ? option.color : 'blue'" size="40">{{ option.icon }}</v-icon><br>
                                            <label >{{ option.label }}</label>
                                        </v-btn>
                                    </v-btn-toggle>
                                </div> 

                                <v-card>
                                    <v-card-title>How important is this attibute?</v-card-title>
                                    <v-card-content>
                                        <div class="ml-2 mb-3">
                                        <v-btn-toggle 
                                            class="mb-5"
                                            v-model="form.reliability_attr_number"
                                            mandatory
                                            v-for="option in attribute_numbers "
                                            :key="option.value"
                                        >
                                            <v-btn      
                                                :value="option.value"                               
                                                class=" mr-2"
                                                color="secondary"
                                                style="border-radius:40%"
                                        
                                            >
                                                <v-chip>
                                                    <label >{{ option.label }}</label>
                                                </v-chip>
                                            </v-btn>

                                        </v-btn-toggle>
                                        </div>
                                    </v-card-content>

                                </v-card>
                            </v-card>
                            <v-divider :thickness="5" color="success" class="border-opacity-100"></v-divider> 
                            <v-card
                                class="text-center bg-none"
                                data-aos="fade-left" 
                                data-aos-duration="1000" 
                                data-aos-delay="500" 
                            >
                                <v-card-title justify="center" class="mt-10 mb-2">Access && Facilities</v-card-title>

                                <div class="ml-2 mb-3">
                                    <v-btn-toggle 
                                        class="mb-5"
                                        v-model="form.access_and_facilities"  v-for="option in options" :key="option.value" >
                                        <v-btn class="bg-secondary mr-2" :value="option.value" >
                                            <v-icon :color="form.access_and_facilities === option.value ? option.color : 'blue'" size="40">{{ option.icon }}</v-icon><br>
                                            <label >{{ option.label }}</label>
                                        </v-btn>
                                    </v-btn-toggle>
                                </div> 

                                <v-card>
                                    <v-card-title>How important is this attibute?</v-card-title>
                                    <v-card-content>
                                        <div class="ml-2 mb-3">
                                        <v-btn-toggle 
                                            v-model="form.access_and_facilities_attr_number" 
                                            mandatory
                                            v-for="option in attribute_numbers "
                                            :key="option.value"
                                        >
                                            <v-btn
                                                :value="option.value"
                                                class=" mr-2"
                                                color="secondary"
                                                style="border-radius:40%"
                                        
                                            >
                                                <v-chip>
                                                    <label >{{ option.label }}</label>
                                                </v-chip>
                                            </v-btn>

                                        </v-btn-toggle>
                                        </div>
                                    </v-card-content>

                                </v-card>

                                
                            </v-card>
                            <v-divider :thickness="5" color="success" class="border-opacity-100"></v-divider> 
                            <v-card
                                class="text-center bg-none"
                                data-aos="fade-right" 
                                data-aos-duration="1000" 
                                data-aos-delay="500" 
                            >
                                <v-card-title justify="center" class="mt-10 mb-2">Communication</v-card-title>

                                <div class="ml-2 mb-3">
                                    <v-btn-toggle
                                    class="mb-5"
                                        v-model="form.communication"  v-for="option in options" :key="option.value" >
                                        <v-btn class="bg-secondary mr-2" :value="option.value" >
                                            <v-icon :color="form.communication === option.value ? option.color : 'blue'" size="40">{{ option.icon }}</v-icon><br>
                                            <label >{{ option.label }}</label>
                                        </v-btn>
                                    </v-btn-toggle>
                                </div> 

                                <v-card>
                                    <v-card-title>How important is this attibute?</v-card-title>
                                    <v-card-content>
                                        <div class="ml-2 mb-3">
                                        <v-btn-toggle 
                                            v-model="form.communication_attr_number" 
                                            mandatory
                                            v-for="option in attribute_numbers "
                                            :key="option.value"
                                        >
                                            <v-btn
                                                :value="option.value"
                                                class=" mr-2"
                                                color="secondary"
                                                style="border-radius:40%"
                                        
                                            >
                                                <v-chip>
                                                    <label >{{ option.label }}</label>
                                                </v-chip>
                                            </v-btn>

                                        </v-btn-toggle>
                                        </div>
                                    </v-card-content>

                                </v-card>

                                
                            </v-card>
                            <v-divider :thickness="5" color="success" class="border-opacity-100"></v-divider> 
                            <v-card
                                class="text-center bg-none"
                                data-aos="fade-left" 
                                data-aos-duration="1000" 
                                data-aos-delay="500" 
                            >
                                <v-card-title justify="center" class="mt-10 mb-2">Integrity</v-card-title>

                                <div class="ml-2 mb-3">
                                    <v-btn-toggle 
                                        class="mb-5"
                                        v-model="form.integrity"  v-for="option in options" :key="option.value" >
                                        <v-btn class="bg-secondary mr-2" :value="option.value" >
                                            <v-icon :color="form.integrity === option.value ? option.color : 'blue'" size="40">{{ option.icon }}</v-icon><br>
                                            <label >{{ option.label }}</label>
                                        </v-btn>
                                    </v-btn-toggle>
                                </div> 

                                <v-card>
                                    <v-card-title>How important is this attibute?</v-card-title>
                                    <v-card-content>
                                        <div class="ml-2 mb-3">
                                        <v-btn-toggle 
                                            v-model="form.integrity_attr_number" 
                                            mandatory
                                            v-for="option in attribute_numbers "
                                            :key="option.value"
                                        >
                                            <v-btn
                                                :value="option.value"
                                                class=" mr-2"
                                                color="secondary"
                                                style="border-radius:40%"
                                        
                                            >
                                            <v-chip>
                                                <label >{{ option.label }}</label>
                                            </v-chip>
                                            </v-btn>

                                        </v-btn-toggle>
                                        </div>
                                    </v-card-content>

                                </v-card>

                                
                            </v-card>
                            <v-divider :thickness="5" color="success" class="border-opacity-100"></v-divider> 
                            <v-card
                                class="text-center bg-none"
                                data-aos="fade-right" 
                                data-aos-duration="1000" 
                                data-aos-delay="500" 
                            >
                                <v-card-title justify="center" class="mt-10 mb-2">Assurance</v-card-title>

                                <div class="ml-2 mb-3">
                                    <v-btn-toggle 
                                        class="mb-5"
                                        v-model="form.assurance"  v-for="option in options" :key="option.value" >
                                        <v-btn class="bg-secondary mr-2" :value="option.value" >
                                            <v-icon :color="form.assurance === option.value ? option.color : 'blue'" size="40">{{ option.icon }}</v-icon><br>
                                            <label >{{ option.label }}</label>
                                        </v-btn>
                                    </v-btn-toggle>
                                </div> 

                                <v-card>
                                    <v-card-title>How important is this attibute?</v-card-title>
                                    <v-card-content>
                                        <div class="ml-2 mb-3">
                                        <v-btn-toggle 
                                            v-model="form.assurance_attr_number" 
                                            mandatory
                                            v-for="option in attribute_numbers "
                                            :key="option.value"
                                        >
                                            <v-btn
                                                :value="option.value"
                                                class=" mr-2"
                                                color="secondary"
                                                style="border-radius:40%"
                                        
                                            >
                                        <v-chip>
                                                <label >{{ option.label }}</label>
                                            </v-chip>
                                            </v-btn>

                                        </v-btn-toggle>
                                        </div>
                                    </v-card-content>

                                </v-card>

                                
                            </v-card>
                            <v-divider :thickness="5" color="success" class="border-opacity-100"></v-divider> 
                            <v-card
                                class="text-center bg-none"
                                data-aos="fade-left" 
                                data-aos-duration="1000" 
                                data-aos-delay="500" 
                            >
                                <v-card-title justify="center" class="mt-10 mb-2">Outcome</v-card-title>

                                <div class="ml-2 mb-3">
                                    <v-btn-toggle 
                                        class="mb-5"
                                        v-model="form.outcome"  v-for="option in options" :key="option.value" >
                                        <v-btn class="bg-secondary mr-2" :value="option.value" >
                                            <v-icon :color="form.outcome === option.value ? option.color : 'blue'" size="40">{{ option.icon }}</v-icon><br>
                                            <label >{{ option.label }}</label>
                                        </v-btn>
                                    </v-btn-toggle>
                                </div> 

                                <v-card>
                                    <v-card-title>How important is this attibute?</v-card-title>
                                    <v-card-content>
                                        <div class="ml-2 mb-3">
                                        <v-btn-toggle 
                                            v-model="form.outcome_attr_number" 
                                            mandatory
                                            v-for="option in attribute_numbers "
                                            :key="option.value"
                                            >
                                            <v-btn
                                                :value="option.value"
                                                class=" mr-2"
                                                color="secondary"
                                                style="border-radius:40%"
                                        
                                            >
                                            <v-chip>
                                                <label >{{ option.label }}</label>
                                            </v-chip>
                                            </v-btn>

                                        </v-btn-toggle>
                                        </div>
                                    </v-card-content>

                                </v-card>

                                
                            </v-card>
                            <v-divider :thickness="5" color="success" class="border-opacity-100"></v-divider> 
                        
                        </div>
                    </v-card>
                    <v-card 
                        data-aos="zoom-out-up" 
                        data-aos-duration="1000" 
                        data-aos-delay="500" 
                        class="mb-5 mx-auto" width="1000"
                    >
                        <div class="p-3 font-bold text-lg">Considering your complete experience with our agency, how likely would you recommend our services to others?</div>
                        <v-card class="text-center">
                            <div class="ml-2 mb-3 mx-auto my-auto mb-5 d-flex justify-center " style="margin-right: 50px ; margin-left: 50px">
                                <v-btn-toggle 
                                    v-model="form.recommend_rate" 
                                    mandatory 
                                    v-for="option in recommendation_numbers "
                                    :key="option.value"
                                >
                                    <v-btn
                                        :value="option.value"
                                        class=" mr-2"
                                        :color="form.recommennd_rate === option.value ? option.color : 'secondary'"
                                        style="border-radius:40%"
                                
                                    >
                                        <v-chip>
                                            <label >{{ option.label }}</label>
                                        </v-chip>
                                    </v-btn>

                                </v-btn-toggle>
                            </div>
                    </v-card>
                    </v-card>

                    <v-card 
                        data-aos="zoom-out-up" 
                        data-aos-duration="1000" 
                        data-aos-delay="500" 
                        class="mb-5 mx-auto" width="1000"
                    >
                        <div class="p-3 mt-0 font-bold text-lg">Please write your comment/suggestions below. (
                            <span class="text-blue-400">Optional</span>
                            )</div>
                            <v-container fluid>
                                <v-textarea
                                    color="secondary"
                                    v-model="form.comment"
                                    placeholder="Input here"
                                ></v-textarea>
                                
                            </v-container>
                    </v-card>

                    <v-card 
                        data-aos="zoom-out-up" 
                        data-aos-duration="1000" 
                        data-aos-delay="500" 
                        class="mb-5 mx-auto" width="1000"
                    >
                        <div class="p-3 mt-0 font-bold text-lg">Please indicate other important attribute/s which you think is important to your needs. (
                            <span class="text-blue-400">Optional</span>
                            )</div>
                            <v-container fluid>
                                <v-textarea
                                    v-model="form.other_attr_indication"
                                    placeholder="Input here"
                                ></v-textarea>
                                
                            </v-container>
                    </v-card>

                    <v-card 
                        data-aos="zoom-out-up" 
                        data-aos-duration="1000" 
                        data-aos-delay="500" 
                        class="mb-5 mx-auto" width="1000"
                        >
                        <div class="p-3 mt-0 font-bold text-lg" >Please write your signature on the box. (
                            <span class="text-blue-400">Optional</span>
                            )</div>
                            <v-container class="text-center">
                                <v-row>
                                <v-col>
                                    <canvas ref="signaturePad" class="mb-3 mx-auto">
                                    </canvas>
                                    <v-btn @click="clearSignature" class="">Clear</v-btn>
                                </v-col>
                                </v-row>
                            </v-container>
                    </v-card>
            
                    <v-card
                        data-aos="zoom-out-up" 
                        data-aos-duration="1000" 
                        data-aos-delay="500" 
                        class="mb-5 mx-auto" width="1000" 
                    >
                        <div
                        
                            style="margin-left: 280px; margin-right: 280px;" class="mt-5 mb-5 text-center">
                            <v-btn color="success" type="submit" class="mr-2" append-icon="mdi-send">Submit</v-btn>
                            <a href="/" class="btn bg-secondary">
                                <v-btn class="bg-secondary">Back</v-btn>
                            </a>
                    </div>
                    </v-card>
                </div>


            </v-form>
        </v-row>
    </v-card>
</template>
<style scoped>
canvas {
  border: 1px solid #000;
}
</style>