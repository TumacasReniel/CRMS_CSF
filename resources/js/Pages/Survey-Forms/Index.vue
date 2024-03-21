<script>
import { ref, onMounted, watch } from 'vue';
import SignaturePad from 'signature_pad';
import AOS from 'aos';
import 'aos/dist/aos.css';
import Swal from 'sweetalert2';

export default {
  props: [ 'dimensions', 'cc_questions', 'message', 'status'  , 'captcha'],

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
        { label: 'Very Satisfied', value: '5', icon: 'mdi-emoticon-cool', color: '#FFEB3B' },
        { label: 'Satisfied', value: '4', icon: 'mdi-emoticon-happy', color: '#FFC107' },
        { label: 'Neither', value: '3', icon: 'mdi-emoticon-neutral', color: '#263238' },
        { label: 'Dissatisfied', value: '2', icon: 'mdi-emoticon-sad', color: '#F44336' },
        { label: 'Very Dissatisfied', value: '1', icon: 'mdi-emoticon-devil', color: '#6200EA' },
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
        pwd: 0,
        pregnant: 0,
        senior_citizen: 0,
        cc1: null,
        cc2: null,
        cc3: null,
        recommend_rate_score: null,
        comment:null,
        is_complaint: false,
        indication: null,
        signature: null,
        dimension_form : {
            id: [],
            name: [],
            rate_score: [],
            importance_rate_score: [],
        },
        cc_form: {
            id: [],
            title: [],
            answer: [],
        },
        captcha: null,

      }),
      signaturePad:null,
      canvas: null,
      errors: {},
      formSubmitted: false,
      captchaSrc: '/captcha/flat',

    };

    
  
  },

//   mounted(){
//       AOS.init();

//         // this.signaturePad = new SignaturePad(this.signaturePad);
//         // // Set canvas dimensions
//         // this.canvas = this.signaturePad;
//         // canvas.width = 400;
//         // canvas.height = 200;

//         Swal.fire({
//             title: "Disclaimer",
//             icon: "warning",
//             text: "The DOST is committed to protect and respect your personal data privacy. All information collected will only be used for documentation purposes and will not be published in any platform.",
//         });
//   },

  

  methods: {
    getDimension(index, id , name, rate_score) {
        this.form.dimension_form.id[index] = id;
        this.form.dimension_form.name[index] = name;
        this.form.dimension_form.rate_score[index] = rate_score;

          if(rate_score <= 3){
            this.form.is_complaint = true;        
        }
        else{
             this.form.is_complaint = false; 
        }
    },
    getCC(index, id , title ) {
        this.form.cc_form.id[index] = id;
        this.form.cc_form.title[index] = title;
        if(id == 1){
            this.form.cc_form.answer[index] = this.form.cc1;        
        }
        else if(id == 2){
            this.form.cc_form.answer[index] = this.form.cc2;
        }
        else if(id == 3){
            this.form.cc_form.answer[index] = this.form.cc3;
        }
        return null;
    },
  
    saveCSF: async function () {
        this.formSubmitted = true;
        try {    
            Swal.fire({
                title: '<img src="/captcha/flat" alt="CAPTCHA" style="display: block; margin: 0 auto; ">',
                html: '<div style="font-weight: bold; font-size:25px">Enter Captcha Code</div> ' +
                      '<input id="captcha-input" class="swal2-input" autofocus>',

                inputAttributes: {
                    autocapitalize: "off"
                },
                
                showCancelButton: true,
                confirmButtonText: "Submit",
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return this.form.captcha = document.getElementById('captcha-input').value;
                }
            }).then((result) => {
                if (result.isConfirmed) {   
                    console.log('hello',999);          
                    // this.form.signature = this.signaturePad;  
                    this.form.post('/csf_submission',{
                        // onSuccess: () => {
                        //     this.form.reset();
                        //     this.form.recommend_rate_score = null;
                        //     // this.form.signaturePad = new SignaturePad(this.signaturePad.value);
                        // },

                        // onError: () => {
                        //     Swal.fire({
                        //         title: 'Failed',
                        //         icon: 'error',
                        //         text: this.error ? this.error: "Something went wrong please check",
                        //     })

                        // }

                    })
    
                }
            });
                            
        } catch (error) {
            Swal.fire({
                title: 'Failed',
                icon: 'error',
                text: this.error ? this.error: "Something went wrong please check",
            })
        }
        
    },

    clearSignature: function () {
        new SignaturePad(this.signaturePad);
    },
    reset() {
        this.form = mapValues(this.form, () => null)
    },
    
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

           Swal.fire({
                title: "Disclaimer",
                icon: "warning",
                text: "The DOST is committed to protect and respect your personal data privacy. All information collected will only be used for documentation purposes and will not be published in any platform.",
            });
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
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Department of Science and Technology </span>
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
                <div class="py-20 bg-gray-200 ">
                    <v-card class="mb-5" width="1000">
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
                                    <input  v-model="form.email" type="email" id="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="name@gmail.com" required />
                                    <div class="text-red-800" v-if="!form.email">{{ form.errors.email }} </div>
                                </div>

                                <div class="mb-5">
                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        Name
                                        (<span class="text-blue-500">Optional</span>)
                                    </label>
                                    <input v-model="form.name" type="text" id="name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="e.g. Juan Dela Cruz" required />
                                    <div v-if="errors.name">{{ errors.name }}</div>
                                </div>

                                <div class="mb-5 grid grid-cols-3 gap-4">
                                    <div class="col-span-1">
                                        <label for="client_types" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                Client Types
                                            <span class="text-red-800">*</span>
                                            </label>
                                            <select v-model="form.client_type" id="client_types"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option disabled selected value="">Select Client Types..</option>
                                                <option>Internal Employees</option>
                                                <option>General Public</option>
                                                <option>Government Employees</option>
                                                <option>Business/Organization</option>
                                            </select>
                                            <div class="text-red-800" v-if="form.errors.client_type && !form.client_type">{{ form.errors.client_type }}</div>
                                    </div>

                                    <div class="col-span-1">
                                        <label for="sex" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                            Sex
                                        <span class="text-red-800">*</span>
                                        </label>
                                        <select  v-model="form.sex" id="sex" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option disabled selected value="">Select Sex.. </option>
                                            <option>Male</option>
                                            <option>Female</option>
                                        </select>
                                         <div  class="text-red-800" v-if="form.errors.sex && !form.sex">{{ form.errors.sex }}</div>
                                    </div>

                                    <div class="col-span-1">
                                        <label for="age_group" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                Age Group
                                            <span class="text-red-800">*</span>
                                            </label>
                                            <select v-model="form.age_group" id="age_group" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option disabled selected value="">Select Age Group..</option>
                                                <option>15-19</option>
                                                <option>20-29</option>
                                                <option>30-39</option>
                                                <option>40-49</option>
                                                <option>50-59</option>
                                                <option>60-69</option>
                                                <option>70-79</option>
                                                <option>80+</option>
                                            </select>                                   
                                           <div  class="text-red-800" v-if="form.errors.age_group && !form.age_group">{{ form.errors.age_group }}</div>
                                    </div>

                                </div>

                                <div class="border border-w-2 p-3 mb-5">
                                    <div>
                                        Other Informations
                                        (<span class="text-blue-500">Optional</span>)
                                    </div>
                                    <div class="grid grid-cols-4 gap-4">
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

                        <div v-for="(cc_question, index) in cc_questions" :key="index" class="mb-10" >
                            <v-card-title class=" m-5 font-black mb-10">
                               {{ cc_question.title }}. {{ cc_question.question }}
                            </v-card-title>

                            <div v-if="index == 0" v-for="(option, index) in cc1_options" :key="index">
                                <v-checkbox color="primary" style="margin-top:-50px; margin-left:7%; margin-bottom: -5%" v-model="form.cc1" :label="option.label" :value="option.value"></v-checkbox>                      
                            </div>

                            <div v-if="index == 1" v-for="(option, index) in cc2_options" :key="index">
                                <v-checkbox color="primary" style="margin-top:-50px; margin-left:7%; margin-bottom: -5%" v-model="form.cc2" :label="option.label" :value="option.value"></v-checkbox>                        
                            </div>

                            <div v-if="index == 2" v-for="(option, index) in cc3_options" :key="index">
                                <v-checkbox color="primary" style="margin-top:-50px; margin-left:7%; margin-bottom: -5%" v-model="form.cc3" :label="option.label" :value="option.value"></v-checkbox>                        
                            </div>

                            <input v-if="form.cc1" type="hidden" :value="getCC(index, cc_question.id ,cc_question.title)"> 
                            <input v-else-if="form.cc2" type="hidden" :value="getCC(index, cc_question.id ,cc_question.title )"> 
                            <input v-else-if="form.cc3" type="hidden" :value="getCC(index, cc_question.id ,cc_question.title )"> 
                            
                            <div class="text-red-800 m-5" style="margin-left:80px" v-if="formSubmitted && !form.cc_form.answer[index]  ">{{ 'This selection is required' }}</div>
                        </div>

                    </v-card>
                  
                    <v-card 
                        data-aos="fade-left" 
                        data-aos-duration="1000" 
                        data-aos-delay="500" 
                        class="mb-5 mx-auto" width="1000">
                        <v-card-title class="text-lg text-white bg-blue_grey p-3 ">
                            HOW WOULD YOU RATE OUR INFORMATION COMMUNICATION TECHNOLOGY SERVICES?
                        </v-card-title>
                        <div>
    
                                <v-card
                                    data-aos="fade-left" 
                                    data-aos-duration="1000" 
                                    data-aos-delay="500" 
                                    class="text-center over-flowhidden scroll-none mb-1"
                                    border="1"
                                    v-for="(dimension, index) in dimensions" :key="dimension.id"
                                >
                                    <v-card-title class="text-4xl mt-5 mb-3 text-uppercase">
                                        {{ dimension.name }}
                                    </v-card-title>

                                       <div class="ml-2">
                                            <v-btn-toggle class="mb-5" v-model="form.dimension_form.rate_score[index]" v-for="option in options" :key="option.value"
                                            :rules="[() => formSubmitted ? !!form.dimension_form.rate_score[index] || 'This selection is required' : true]"
                                            >     
                                            <v-btn rounded class="mr-2 bg-gray-200" :value="option.value" color="secondary" >
                                                <v-icon :color="form.dimension_form.rate_score[index] === option.value ? option.color : 'gray'" size="40">{{ option.icon }}</v-icon><br>
                                                <label>{{ option.label }}</label>
                                            </v-btn>      
                                            <input type="hidden" :value="getDimension(index, dimension.id, dimension.name, form.dimension_form.rate_score[index] )">   
                                            </v-btn-toggle> 
                                            <div class="text-red-800" v-if="formSubmitted && !form.dimension_form.rate_score[index]">{{ 'This selection is required' }}</div>
                                        </div>
                                        <div class="overflow-hidden mb-3">
                                            <div>How important is this attibute?</div>
                                            <div>
                                                <div class="ml-2 mb-3">
                                                    <v-btn-toggle  v-model="form.dimension_form.importance_rate_score[index]"  v-for="option in attribute_numbers "  :key="option.value"  mandatory>
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
                                                    <div class="text-red-800" v-if="form.errors && formSubmitted && !form.dimension_form.importance_rate_score[index]">{{ 'This selection is required' }}</div>
                                                </div>
                                            </div>

                                        </div>

                                </v-card>                     

                            <v-divider></v-divider> 
                        
                        </div>
                    </v-card>
                    <v-card 
                        data-aos="zoom-out-up" 
                        data-aos-duration="1000" 
                        data-aos-delay="500" 
                        class="mb-5 mx-auto" width="1000"
                    >
                        <div class="p-3 font-bold text-lg">Considering your complete experience with our agency, how likely would you recommend our services to others? <span class="text-red-800">*</span></div>

                            <div class="ml-2 mb-3 mx-auto my-auto mb-5 d-flex justify-center text-center" style="margin-right: 50px ; margin-left: 50px">
                                <v-btn-toggle 
                                    v-model="form.recommend_rate_score" 
                                    mandatory 
                                    v-for="option in recommendation_numbers "
                                    :key="option.value"
                                >
                                    <v-btn
                                        :value="option.value"
                                        class=" mr-2 "
                                        :color="form.recommend_rate_score === option.color ? 'secondary' : 'secondary'"
                                        style="border-radius:40%"
                                
                                    >
                                        <v-chip>
                                            <label >{{ option.label }}</label>
                                        </v-chip>
                                    </v-btn>

                                </v-btn-toggle>            
                                </br>
                                <div class="text-red-800" v-if="form.errors.recommend_rate_score && !form.recommend_rate_score">{{ 'This selection is required' }}</div>
                            </div>
                         

                    </v-card>

                    <v-card 
                        data-aos="zoom-out-up" 
                        data-aos-duration="1000" 
                        data-aos-delay="500" 
                        class="mb-5 mx-auto" width="1000"
                    >
                        <div class="p-3 mt-0 font-bold text-lg">Please write your comment/suggestions below. 
                           <span  v-if="form.is_complaint == false" class="text-blue-400">(Optional)</span>
                           <span  v-if="form.is_complaint == true" class="text-red-800">*</span>
                        </div>
                        <v-container fluid>
                            <v-textarea
                                v-if="form.is_complaint == true"
                                v-model="form.comment"
                                placeholder="Input here!"
                            ></v-textarea>     
                            <v-textarea
                                v-else-if="form.is_complaint == false"
                                v-model="form.comment"
                                placeholder="Input here"
                            ></v-textarea>                         
                        </v-container>

                        <div class="text-red-800 p-5" v-if="formSubmitted && form.is_complaint == true">{{ 'This selection is required because you rate low to our services with the options above.' }}<br>
                        Please input the reason/s why you have rated low.</div>
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
                                    v-model="form.indication"
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
                            <v-btn color="success" type="submit" class="mr-2" prepend-icon="mdi-send" :disabled="form.processing">Submit</v-btn>
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