import{x as J,d as Q,y as X,i as ee,j as m,o as n,e as d,b as o,u as te,w as s,F as y,N as ae,Z as le,l as oe,a as l,t as c,g as w,f as _,c as g,h as x,O as se}from"./app-5aWLEA4K.js";import{_ as N}from"./dost-logo-B4Huuyod.js";import{A as ne}from"./aos-YQ-z66t_.js";import{S}from"./sweetalert2.all-BTVYNQqp.js";const re=ae('<nav data-aos="fade-down" data-aos-duration="500" data-aos-delay="500" class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600"><div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4"><a href="/" class="flex items-center space-x-3 rtl:space-x-reverse"><img src="'+N+'" class="h-8" alt="DOST Logo"><span class="self-center lg:text-2xl md:text-base sm:text-sm font-semibold whitespace-nowrap dark:text-white text-black">Department of Science and Technology </span></a></div></nav>',1),ie={class:"py-20 bg-gray-200"},ue=l("div",null,[l("img",{"data-aos":"zoom-in","data-aos-duration":"500","data-aos-delay":"500",class:"mx-auto sm:mb-0",style:{width:"200px",height:"200px"},src:N,alt:".."})],-1),de=l("span",{class:"font-black text-base lg:text-2xl md:text-base sm:text-sm","data-aos":"fade-down","data-aos-duration":"500","data-aos-delay":"500"},"CUSTOMER SATISFACTION FEEDBACK ",-1),ce=l("br",null,null,-1),me=l("a",{href:"#"},[l("img",{class:"rounded-t-lg",src:"/docs/images/blog/image-1.jpg",alt:""})],-1),_e={class:"p-5"},pe={href:"#"},fe={class:"mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"},be=l("br",null,null,-1),ve={key:0},he={key:1},ye={key:2,class:"ml-3"},ge={key:3,class:"ml-3"},xe=l("p",{class:"mb-3 font-normal text-gray-700 dark:text-gray-400"},"This questionaire aims to solicit your honest assessment of our services. Please take a minute in filling out this form and help us serve you better.",-1),we=l("div",{class:"m-5 text-gray-600"},[l("h2",null," Select your answer to the Citizen's Charter(CC) questions. The Citizen's Charter is an official document that reflects the services of a government agency/office including its requirements, fees and processing times among others. ")],-1),Ce={class:"mx-5"},ke={class:"font-black"},Ve={key:0,class:"text-red-800 m-5",style:{"margin-left":"80px"}},Se=l("div",{class:"text-white bg-blue_grey p-3"},[l("h3",null,"HOW WOULD YOU RATE OUR SERVICES?")],-1),Te={class:"mt-5 mb-3 text-left mx-5 bg-gray-200 p-3"},Ue={style:{"font-size":"18px"}},Oe=["value"],Ae=l("br",null,null,-1),Ie={key:0,class:"text-red-800"},Ee={key:0,class:"overflow-hidden mb-3"},De=l("div",null,"How important is this attribute?",-1),ze={class:"ml-2 mb-3"},Fe={key:0,class:"text-red-800"},Ne=l("div",{class:"p-3 font-bold text-lg"},[w("Considering your complete experience with our agency, how likely would you recommend our services to others? "),l("span",{class:"text-red-800"},"*")],-1),qe={class:"ml-2 mb-3 mx-auto my-auto mb-5 d-flex justify-center text-center",style:{"margin-right":"50px","margin-left":"50px"}},je={key:0,class:"text-red-800"},Be={class:"p-3 mt-0 font-bold text-lg"},Pe={key:0,class:"text-red-800"},Me={key:1,class:"text-blue-400"},$e={key:0,class:"text-red-800 p-5"},Le=l("br",null,null,-1),Re={href:"/",class:"btn bg-secondary"},Ze={__name:"Index",props:{cc_questions:Object,dimensions:Object,unit:Object,sub_unit:Object,unit_psto:Object,sub_unit_psto:Object,status:String,errors:Object,captcha_img:String,date_display:String},setup(p){const q=p,j=[{label:"1. I know what a CC is and I saw this office's CC.",value:"1"},{label:"2. I know what a CC is but I did NOT see this office's CC. ",value:"2"},{label:"3. I learned the CC when I saw this office's CC.",value:"3"},{label:"4. I do not know what a CC is  and I did not see one in this office.(Answer 'N/A' on CC2 and CC3)",value:"4"}],B=[{label:"1. Easy to see",value:"1"},{label:"2. Somewhat easy to see",value:"2"},{label:"3. Difficult to see",value:"3"},{label:"4. not Visible at all",value:"4"},{label:"5. N/A",value:"5"}],P=[{label:"1. Helped Very Much",value:"1"},{label:"2. Somewhat helped",value:"2"},{label:"3. Did not help",value:"3"},{label:"4. N/A",value:"4"}],M=[{label:"Strongly Agree",value:"5",icon:"mdi-emoticon-excited",color:"#FFEB3B"},{label:"Agree",value:"4",icon:"mdi-emoticon-happy",color:"#FFC107"},{label:"Neither",value:"3",icon:"mdi-emoticon-neutral",color:"#263238"},{label:"Disagree",value:"2",icon:"mdi-emoticon-sad",color:"#F44336"},{label:"Very Disagree",value:"1",icon:"mdi-emoticon-frown",color:"#6200EA"},{label:"N/A",value:"6",icon:"mdi-close-circle-outline",color:"red"}],$=[{label:"5",value:"5"},{label:"4",value:"4"},{label:"3",value:"3"},{label:"2",value:"2"},{label:"1",value:"1"}],L=[{label:"10",value:"10"},{label:"9",value:"9"},{label:"8",value:"8"},{label:"7",value:"7"},{label:"6",value:"6"},{label:"5",value:"5"},{label:"4",value:"4"},{label:"3",value:"3"},{label:"2",value:"2"},{label:"1",value:"1"}],e=J({region_id:null,service_id:null,unit_id:null,sub_unit_id:null,psto_id:null,date:(()=>{const u=new Date,a=u.getFullYear(),f=String(u.getMonth()+1).padStart(2,"0"),b=String(u.getDate()).padStart(2,"0");return`${a}-${f}-${b}`})(),client_type:null,sub_unit_type:null,email:null,name:null,sex:null,age_group:null,pwd:0,pregnant:0,senior_citizen:0,cc1:null,cc2:null,cc3:null,recommend_rate_score:null,comment:null,is_complaint:!1,indication:null,dimension_form:{id:[],rate_score:[],importance_rate_score:[]},cc_form:{id:[],answer:[]},captcha:null,current_url:null,complaint_scanner:{value:[]}}),h=Q(!1),T=(u,a,f)=>{e.cc_form.id[u]=a,e.cc_form.answer[u]=f},R=(u,a)=>{e.dimension_form.id[u]=a};X(()=>{ne.init();const u=window.location.href,a=new URLSearchParams(u.split("?")[1]);e.region_id=a.get("region_id"),e.service_id=a.get("service_id"),e.unit_id=a.get("unit_id"),e.sub_unit_id=a.get("sub_unit_id"),e.psto_id=a.get("psto_id"),e.sub_unit_type=a.get("sub_unit_type"),e.current_url=u,S.fire({title:"Disclaimer",icon:"warning",text:"The DOST is committed to protect and respect your personal data privacy. All information collected will only be used for documentation purposes and will not be published in any platform."})});const H=async()=>{console.log(e,99),h.value=!0;let u=Math.random();const a=()=>'<img src="'+("/captcha/flat?rand="+u)+'" alt="CAPTCHA" style="display: block; margin: 0 auto; ">';try{S.fire({title:a(),html:'<div style="font-weight: bold; font-size:25px">Enter Captcha Code</div> <input id="captcha-input" class="swal2-input text-center"><p id="invalid-captcha-text" style="color: red; font-size: 14px; margin-top: 5px; display: none;">Invalid CAPTCHA code</p>',inputAttributes:{autocapitalize:"off"},showCancelButton:!0,confirmButtonText:"Submit",showLoaderOnConfirm:!0,preConfirm:()=>{const f=document.getElementById("captcha-input").value;return e.captcha=f,!0}}).then(f=>{f.isConfirmed&&se.post("/csf_submission",e)})}catch{S.fire({title:"Failed",icon:"error",text:"Something went wrong please check"})}},G=(u,a)=>{e.dimension_form.rate_score[u]>0&&e.dimension_form.rate_score[u]<4?e.complaint_scanner.value[u]=!0:e.complaint_scanner.value[u]=!1,e.dimension_form.rate_score[u]==6&&(e.dimension_form.importance_rate_score[u]=5),e.is_complaint=!1,e.complaint_scanner.value.forEach(f=>{if(f===!0){e.is_complaint=!0;return}})};return ee(()=>q.errors,u=>{u&&S.fire({title:"Failed",icon:"error",text:"Something went wrong. Please check the fields and make sure the captcha is correctly entered. If you continue to get errors, please contact the administrator."})}),(u,a)=>{const f=m("v-card-title"),b=m("v-card"),V=m("v-text-field"),U=m("v-select"),C=m("v-col"),O=m("v-row"),A=m("v-radio"),I=m("v-radio-group"),W=m("v-icon"),k=m("v-btn"),E=m("v-btn-toggle"),D=m("v-chip"),Y=m("v-divider"),z=m("v-textarea"),K=m("v-container"),Z=m("v-form");return n(),d(y,null,[o(te(le),{title:"CSF Form"}),re,o(b,{class:"w-full","data-aos":"fade-up","data-aos-duration":"2000","data-aos-delay":"500"},{default:s(()=>[o(O,{justify:"center",class:"py-3 bg-gray-200 w-full"},{default:s(()=>[o(C,{cols:"12",md:"8",sm:"6"},{default:s(()=>[o(Z,{class:"max-w",onSubmit:oe(H,["prevent"])},{default:s(()=>[l("div",ie,[o(b,{class:"mb-3 md:mb-0 sm:mb-0 text-center"},{default:s(()=>[o(f,{class:"m-5 font-black flex flex-col items-center"},{default:s(()=>[ue,de,ce]),_:1})]),_:1}),o(b,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto text-base md:text-base sm:text-sm"},{default:s(()=>[me,l("div",_e,[l("a",pe,[l("h5",fe,[l("span",null,c(p.unit.data[0].unit_name),1),w(),be,p.sub_unit.data.length>0?(n(),d("span",ve,c(p.sub_unit.data[0].sub_unit_name),1)):_("",!0),p.unit_psto.data.length>0?(n(),d("span",he,c(p.unit_psto.data[0].psto.psto_name),1)):_("",!0),p.sub_unit_psto.data.length>0?(n(),d("span",ye,c(p.sub_unit_psto.data[0].psto.psto_name),1)):_("",!0),e.sub_unit_type?(n(),d("span",ge,c(e.sub_unit_type),1)):_("",!0)])]),xe,l("div",null,[p.date_display[0].is_displayed==1?(n(),g(V,{key:0,modelValue:e.date,"onUpdate:modelValue":a[0]||(a[0]=t=>e.date=t),type:"date",label:"Date",variant:"outlined"},null,8,["modelValue"])):_("",!0),e.is_complaint==!0?(n(),g(V,{key:1,modelValue:e.email,"onUpdate:modelValue":a[1]||(a[1]=t=>e.email=t),type:"email",placeholder:"email@gmail.com",label:"Email*",variant:"outlined",rules:[t=>!!t||h.value&&!e.email||"This field is required"]},null,8,["modelValue","rules"])):e.is_complaint==!1?(n(),g(V,{key:2,modelValue:e.email,"onUpdate:modelValue":a[2]||(a[2]=t=>e.email=t),type:"email",placeholder:"email@gmail.com",label:"Email(Optional)",variant:"outlined"},null,8,["modelValue"])):_("",!0),o(V,{modelValue:e.name,"onUpdate:modelValue":a[3]||(a[3]=t=>e.name=t),placeholder:"Enter your full name",label:"Name(Optional)",variant:"outlined"},null,8,["modelValue"]),o(O,{class:"mb-5"},{default:s(()=>[o(C,{cols:"12",md:"",sm:"4",style:{"margin-bottom":"-23px"}},{default:s(()=>[o(U,{label:"Client_type*",variant:"outlined",modelValue:e.client_type,"onUpdate:modelValue":a[4]||(a[4]=t=>e.client_type=t),items:["General Public","Internal Employees","Business/Organization","Government Employees"],rules:[t=>!!t||p.errors.client_type||"This field is required"]},null,8,["modelValue","rules"])]),_:1}),o(C,{cols:"12",md:"",sm:"4",style:{"margin-bottom":"-23px"}},{default:s(()=>[o(U,{label:"Sex*",variant:"outlined",modelValue:e.sex,"onUpdate:modelValue":a[5]||(a[5]=t=>e.sex=t),items:["Male","Female","Prefer not to say"],rules:[t=>!!t||p.errors.sex||"This field is required"]},null,8,["modelValue","rules"])]),_:1}),o(C,{cols:"12",md:"",sm:"4",style:{"margin-bottom":"-23px"}},{default:s(()=>[o(U,{label:"Age Group*",variant:"outlined",modelValue:e.age_group,"onUpdate:modelValue":a[6]||(a[6]=t=>e.age_group=t),items:["19 or lower","20-34","35-49","50-64","60+","Prefer not to say"],rules:[t=>!!t||p.errors.sex||"This field is required"]},null,8,["modelValue","rules"])]),_:1})]),_:1})])])]),_:1}),o(b,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto text-base sm:text-sm"},{default:s(()=>[we,(n(!0),d(y,null,x(p.cc_questions,(t,i)=>(n(),d("div",{key:i,class:"mb-0"},[l("div",Ce,[l("div",ke,[l("h2",null,c(t.title)+". "+c(t.question),1)]),i==0?(n(),g(I,{key:0,modelValue:e.cc1,"onUpdate:modelValue":a[7]||(a[7]=r=>e.cc1=r),color:"primary",class:"mx-2"},{default:s(()=>[(n(),d(y,null,x(j,(r,v)=>l("h5",{key:v},[o(A,{onClick:F=>T(i,t.id,r.value),label:r.label,value:r.value},null,8,["onClick","label","value"])])),64))]),_:2},1032,["modelValue"])):_("",!0),i==1?(n(),g(I,{key:1,modelValue:e.cc2,"onUpdate:modelValue":a[8]||(a[8]=r=>e.cc2=r),color:"primary",class:"mx-2"},{default:s(()=>[(n(),d(y,null,x(B,(r,v)=>l("h5",{key:v},[o(A,{onClick:F=>T(i,t.id,r.value),label:r.label,value:r.value},null,8,["onClick","label","value"])])),64))]),_:2},1032,["modelValue"])):_("",!0),i==2?(n(),g(I,{key:2,modelValue:e.cc3,"onUpdate:modelValue":a[9]||(a[9]=r=>e.cc3=r),color:"primary",class:"mx-2"},{default:s(()=>[(n(),d(y,null,x(P,(r,v)=>l("h5",{key:v},[o(A,{onClick:F=>T(i,t.id,r.value),label:r.label,value:r.value},null,8,["onClick","label","value"])])),64))]),_:2},1032,["modelValue"])):_("",!0)]),h.value&&!e.cc_form.answer[i]?(n(),d("div",Ve,c("This selection is required"))):_("",!0)]))),128))]),_:1}),o(b,{"data-aos":"fade-left","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:s(()=>[Se,l("div",null,[(n(!0),d(y,null,x(p.dimensions,(t,i)=>(n(),g(b,{"data-aos":"fade-left","data-aos-duration":"1000","data-aos-delay":"500",class:"text-center over-flowhidden scroll-none mb-1",border:"1",key:t.id},{default:s(()=>[l("h5",Te,[l("span",Ue,c(t.id)+". "+c(t.description)+"("+c(t.name)+")",1)]),l("input",{type:"hidden",value:R(i,t.id)},null,8,Oe),l("div",null,[(n(),d(y,null,x(M,r=>o(E,{class:"mb-5",modelValue:e.dimension_form.rate_score[i],"onUpdate:modelValue":v=>e.dimension_form.rate_score[i]=v,key:r.value,rules:[()=>h.value?!!e.dimension_form.rate_score[i]||"This selection is required":!0]},{default:s(()=>[o(k,{onClick:v=>G(i,e.dimension_form.rate_score[i]),rounded:"",class:"mr-2 bg-gray-200",value:r.value,color:"secondary"},{default:s(()=>[o(W,{color:e.dimension_form.rate_score[i]===r.value?r.color:"gray",size:"40"},{default:s(()=>[w(c(r.icon),1)]),_:2},1032,["color"]),Ae,l("label",null,c(r.label),1)]),_:2},1032,["onClick","value"])]),_:2},1032,["modelValue","onUpdate:modelValue","rules"])),64)),h.value&&!e.dimension_form.rate_score[i]?(n(),d("div",Ie,c("This selection is required"))):_("",!0)]),e.dimension_form.rate_score[i]&&e.dimension_form.rate_score[i]!=6?(n(),d("div",Ee,[De,l("div",null,[l("div",ze,[(n(),d(y,null,x($,r=>o(E,{modelValue:e.dimension_form.importance_rate_score[i],"onUpdate:modelValue":v=>e.dimension_form.importance_rate_score[i]=v,key:r.value,mandatory:""},{default:s(()=>[o(k,{class:"mr-2",value:r.value,color:"secondary",style:{"border-radius":"40%"}},{default:s(()=>[o(D,null,{default:s(()=>[l("label",null,c(r.label),1)]),_:2},1024)]),_:2},1032,["value"])]),_:2},1032,["modelValue","onUpdate:modelValue"])),64)),h.value&&!e.dimension_form.importance_rate_score[i]?(n(),d("div",Fe,c("This selection is required"))):_("",!0)])])])):_("",!0)]),_:2},1024))),128)),o(Y)])]),_:1}),o(b,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:s(()=>[Ne,l("div",qe,[(n(),d(y,null,x(L,t=>o(E,{modelValue:e.recommend_rate_score,"onUpdate:modelValue":a[10]||(a[10]=i=>e.recommend_rate_score=i),mandatory:"",key:t.value},{default:s(()=>[o(k,{value:t.value,class:"mr-2",color:(e.recommend_rate_score===t.color,"secondary"),style:{"border-radius":"40%"}},{default:s(()=>[o(D,null,{default:s(()=>[l("label",null,c(t.label),1)]),_:2},1024)]),_:2},1032,["value","color"])]),_:2},1032,["modelValue"])),64)),h.value&&!e.recommend_rate_score?(n(),d("div",je,c("This selection is required"))):_("",!0)])]),_:1}),o(b,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:s(()=>[l("div",Be,[w("Please write your comment/suggestions below. "),e.is_complaint==!0?(n(),d("span",Pe,"*")):(n(),d("span",Me,"(Optional)"))]),o(K,{fluid:""},{default:s(()=>[e.is_complaint==!0?(n(),g(z,{key:0,modelValue:e.comment,"onUpdate:modelValue":a[11]||(a[11]=t=>e.comment=t),placeholder:"Input here!",rules:[t=>!!t||h.value&&!e.comment||"This field is required"]},null,8,["modelValue","rules"])):e.is_complaint==!1?(n(),g(z,{key:1,modelValue:e.comment,"onUpdate:modelValue":a[12]||(a[12]=t=>e.comment=t),placeholder:"Input here"},null,8,["modelValue"])):_("",!0)]),_:1}),h.value&&e.is_complaint==!0?(n(),d("div",$e,[w(c("This selection is required because you rate low to our services with the options above."),1),Le,w(" Please input the reason/s why you have rated low.")])):_("",!0)]),_:1}),o(b,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:s(()=>[o(O,{class:"mt-5 mb-5 text-center"},{default:s(()=>[o(C,{cols:"6",class:"text-right"},{default:s(()=>[l("a",Re,[o(k,{class:"bg-secondary"},{default:s(()=>[w("Back")]),_:1})])]),_:1}),o(C,{cols:"6",class:"text-left"},{default:s(()=>[o(k,{color:"success",type:"submit",class:"mr-2","prepend-icon":"mdi-send",disabled:e.processing||e.is_complaint&&!e.comment},{default:s(()=>[w("Submit")]),_:1},8,["disabled"])]),_:1})]),_:1})]),_:1})])]),_:1})]),_:1})]),_:1})]),_:1})],64)}}};export{Ze as default};
