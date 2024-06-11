import{x as Z,d as J,y as Q,i as X,j as m,o as n,e as u,b as a,u as ee,w as s,F as y,M as te,Z as ae,l as le,a as t,t as c,g as h,f as b,h as w,c as I,O as oe}from"./app-C7FqRKsX.js";import{_ as F}from"./dost-logo-B4Huuyod.js";import{A as se}from"./aos-CJ46uAfX.js";import{S as V}from"./sweetalert2.all-DKAoRO1l.js";const ne=te('<nav data-aos="fade-down" data-aos-duration="500" data-aos-delay="500" class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600"><div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4"><a href="/" class="flex items-center space-x-3 rtl:space-x-reverse"><img src="'+F+'" class="h-8" alt="DOST Logo"><span class="self-center lg:text-2xl md:text-base sm:text-sm font-semibold whitespace-nowrap dark:text-white text-black">Department of Science and Technology </span></a></div></nav>',1),re={class:"py-20 bg-gray-200"},ie=t("div",null,[t("img",{"data-aos":"zoom-in","data-aos-duration":"500","data-aos-delay":"500",class:"mx-auto sm:mb-0",style:{width:"200px",height:"200px"},src:F,alt:".."})],-1),ue=t("span",{class:"font-black text-base lg:text-2xl md:text-base sm:text-sm","data-aos":"fade-down","data-aos-duration":"500","data-aos-delay":"500"},"CUSTOMER SATISFACTION FEEDBACK",-1),de=t("br",null,null,-1),ce=t("a",{href:"#"},[t("img",{class:"rounded-t-lg",src:"/docs/images/blog/image-1.jpg",alt:""})],-1),me={class:"p-5"},_e={href:"#"},pe={class:"mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"},be=t("br",null,null,-1),ve={key:0},fe={key:1},he={key:2,class:"ml-3"},ye={key:3,class:"ml-3"},ge=t("p",{class:"mb-3 font-normal text-gray-700 dark:text-gray-400"},"This questionaire aims to solicit your honest assessment of our services. Please take a minute in filling out this form and help us serve you better.",-1),xe=t("div",{class:"m-5 text-gray-600"},[t("h2",null," Select your answer to the Citzen's Charter(CC) questions. The Citizen's Charter is an official document that reflects the services of a government agency/office's including its requirements, fees and processing times among others. ")],-1),we={class:"m-5 font-black mb-10"},Ce={key:3,class:"text-red-800 m-5",style:{"margin-left":"80px"}},ke={class:"text-white bg-blue_grey p-3"},Ve={style:{"text-transform":"uppercase"}},Se={class:"mt-5 mb-3 text-left mx-5 bg-gray-200 p-3"},Te={style:{"font-size":"18px"}},Oe=["value"],Ue=t("br",null,null,-1),Ae={key:0,class:"text-red-800"},Ie={class:"overflow-hidden mb-3"},Ee=t("div",null,"How important is this attribute?",-1),ze={class:"ml-2 mb-3"},De={key:0,class:"text-red-800"},Fe=t("div",{class:"p-3 font-bold text-lg"},[h("Considering your complete experience with our agency, how likely would you recommend our services to others? "),t("span",{class:"text-red-800"},"*")],-1),Ne={class:"ml-2 mb-3 mx-auto my-auto mb-5 d-flex justify-center text-center",style:{"margin-right":"50px","margin-left":"50px"}},je={key:0,class:"text-red-800"},qe={class:"p-3 mt-0 font-bold text-lg"},Be={key:0,class:"text-red-800"},Me={key:1,class:"text-blue-400"},Pe={key:0,class:"text-red-800 p-5"},$e=t("br",null,null,-1),Le={href:"/",class:"btn bg-secondary"},Ke={__name:"Index",props:{cc_questions:Object,dimensions:Object,unit:Object,sub_unit:Object,unit_psto:Object,sub_unit_psto:Object,status:String,errors:Object,captcha_img:String},setup(_){const N=_,j=[{label:"1. I know what a CC is and I saw this office's CC.",value:"1"},{label:"2. I know what a CC is but I did NOT see this office's CC. ",value:"2"},{label:"3. I learned the CC when I saw this office's CC.",value:"3"},{label:"4. I do not know what a CC is  and I did not see one in this office.(Answer 'N/A' on CC2 and CC3)",value:"4"}],q=[{label:"1. Easy to see",value:"1"},{label:"2. Somewhat easy to see",value:"2"},{label:"3. Difficult to see",value:"3"},{label:"4. not Visible at all",value:"4"},{label:"5. N/A",value:"5"}],B=[{label:"1. Helped Very Much",value:"1"},{label:"2. Somewhat helped",value:"2"},{label:"3. Did not help",value:"3"},{label:"4. N/A",value:"4"}],M=[{label:"Strongly Agree",value:"5",icon:"mdi-emoticon-cool",color:"#FFEB3B"},{label:"Agree",value:"4",icon:"mdi-emoticon-happy",color:"#FFC107"},{label:"Neither",value:"3",icon:"mdi-emoticon-neutral",color:"#263238"},{label:"Dissagree",value:"2",icon:"mdi-emoticon-sad",color:"#F44336"},{label:"Very Dissagree",value:"1",icon:"mdi-emoticon-devil",color:"#6200EA"},{label:"N/A",value:"",icon:"mdi-close-circle-outline",color:"red"}],P=[{label:"5",value:"5"},{label:"4",value:"4"},{label:"3",value:"3"},{label:"2",value:"2"},{label:"1",value:"1"}],$=[{label:"10",value:"10"},{label:"9",value:"9"},{label:"8",value:"8"},{label:"7",value:"7"},{label:"6",value:"6"},{label:"5",value:"5"},{label:"4",value:"4"},{label:"3",value:"3"},{label:"2",value:"2"},{label:"1",value:"1"}],e=Z({region_id:null,service_id:null,unit_id:null,sub_unit_id:null,psto_id:null,date:(()=>{const d=new Date,l=d.getFullYear(),p=String(d.getMonth()+1).padStart(2,"0"),v=String(d.getDate()).padStart(2,"0");return`${l}-${p}-${v}`})(),client_type:null,sub_unit_type:null,email:null,name:null,sex:null,age_group:null,pwd:0,pregnant:0,senior_citizen:0,cc1:null,cc2:null,cc3:null,recommend_rate_score:null,comment:null,is_complaint:!1,indication:null,dimension_form:{id:[],rate_score:[],importance_rate_score:[]},cc_form:{id:[],answer:[]},captcha:null,current_url:null,complaint_scanner:{value:[]}}),g=J(!1),S=(d,l,p)=>{e.cc_form.id[d]=l,e.cc_form.answer[d]=p},L=(d,l)=>{e.dimension_form.id[d]=l};Q(()=>{se.init();const d=window.location.href,l=new URLSearchParams(d.split("?")[1]);e.region_id=l.get("region_id"),e.service_id=l.get("service_id"),e.unit_id=l.get("unit_id"),e.sub_unit_id=l.get("sub_unit_id"),e.psto_id=l.get("psto_id"),e.sub_unit_type=l.get("sub_unit_type"),e.current_url=d,V.fire({title:"Disclaimer",icon:"warning",text:"The DOST is committed to protect and respect your personal data privacy. All information collected will only be used for documentation purposes and will not be published in any platform."})});const R=async()=>{g.value=!0;let d=Math.random();const l=()=>'<img src="'+("/captcha/flat?rand="+d)+'" alt="CAPTCHA" style="display: block; margin: 0 auto; ">';try{V.fire({title:l(),html:'<div style="font-weight: bold; font-size:25px">Enter Captcha Code</div> <input id="captcha-input" class="swal2-input text-center"><p id="invalid-captcha-text" style="color: red; font-size: 14px; margin-top: 5px; display: none;">Invalid CAPTCHA code</p>',inputAttributes:{autocapitalize:"off"},showCancelButton:!0,confirmButtonText:"Submit",showLoaderOnConfirm:!0,preConfirm:()=>{const p=document.getElementById("captcha-input").value;return e.captcha=p,!0}}).then(p=>{p.isConfirmed&&oe.post("/csf_submission",e)})}catch{V.fire({title:"Failed",icon:"error",text:"Something went wrong pease check"})}},H=(d,l)=>{e.dimension_form.rate_score[d]<4?e.complaint_scanner.value[d]=!0:e.complaint_scanner.value[d]=!1,e.is_complaint=!1,e.complaint_scanner.value.forEach(p=>{if(p===!0){e.is_complaint=!0;return}})};return X(()=>N.errors.captcha,d=>{d&&V.fire({title:"Error Captcha",text:"Wrong captcha code!",icon:"error"})}),(d,l)=>{const p=m("v-card-title"),v=m("v-card"),E=m("v-text-field"),T=m("v-select"),C=m("v-col"),O=m("v-row"),U=m("v-checkbox"),G=m("v-icon"),k=m("v-btn"),A=m("v-btn-toggle"),z=m("v-chip"),W=m("v-divider"),D=m("v-textarea"),Y=m("v-container"),K=m("v-form");return n(),u(y,null,[a(ee(ae),{title:"Survey Form"}),ne,a(v,{class:"w-full","data-aos":"fade-up","data-aos-duration":"2000","data-aos-delay":"500"},{default:s(()=>[a(O,{justify:"center",class:"py-3 bg-gray-200 w-full"},{default:s(()=>[a(C,{cols:"12",md:"8",sm:"6"},{default:s(()=>[a(K,{class:"max-w",onSubmit:le(R,["prevent"])},{default:s(()=>[t("div",re,[a(v,{class:"mb-3 md:mb-0 sm:mb-0 text-center"},{default:s(()=>[a(p,{class:"m-5 font-black flex flex-col items-center"},{default:s(()=>[ie,ue,de]),_:1})]),_:1}),a(v,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto text-base md:text-base sm:text-sm"},{default:s(()=>[ce,t("div",me,[t("a",_e,[t("h5",pe,[t("span",null,c(_.unit.data[0].unit_name),1),h(),be,_.sub_unit.data.length>0?(n(),u("span",ve,c(_.sub_unit.data[0].sub_unit_name),1)):b("",!0),_.unit_psto.data.length>0?(n(),u("span",fe,c(_.unit_psto.data[0].psto.psto_name),1)):b("",!0),_.sub_unit_psto.data.length>0?(n(),u("span",he,c(_.sub_unit_psto.data[0].psto.psto_name),1)):b("",!0),e.sub_unit_type?(n(),u("span",ye,c(e.sub_unit_type),1)):b("",!0)])]),ge,t("div",null,[a(E,{modelValue:e.email,"onUpdate:modelValue":l[0]||(l[0]=o=>e.email=o),type:"email",placeholder:"email@gmail.com",label:"Email(Optional)",variant:"outlined"},null,8,["modelValue"]),a(E,{modelValue:e.name,"onUpdate:modelValue":l[1]||(l[1]=o=>e.name=o),placeholder:"Enter your full name",label:"Name(Optional)",variant:"outlined"},null,8,["modelValue"]),a(O,{class:"mb-5"},{default:s(()=>[a(C,{cols:"12",md:"",sm:"4",style:{"margin-bottom":"-23px"}},{default:s(()=>[a(T,{label:"Client_type*",variant:"outlined",modelValue:e.client_type,"onUpdate:modelValue":l[2]||(l[2]=o=>e.client_type=o),items:["General Public","Internal Employees","Business/Organization","Government Employees"],rules:[o=>!!o||_.errors.client_type||"This field is required"]},null,8,["modelValue","rules"])]),_:1}),a(C,{cols:"12",md:"",sm:"4",style:{"margin-bottom":"-23px"}},{default:s(()=>[a(T,{label:"Sex*",variant:"outlined",modelValue:e.sex,"onUpdate:modelValue":l[3]||(l[3]=o=>e.sex=o),items:["Male","Female"],rules:[o=>!!o||_.errors.sex||"This field is required"]},null,8,["modelValue","rules"])]),_:1}),a(C,{cols:"12",md:"",sm:"4",style:{"margin-bottom":"-23px"}},{default:s(()=>[a(T,{label:"Age Group*",variant:"outlined",modelValue:e.age_group,"onUpdate:modelValue":l[4]||(l[4]=o=>e.age_group=o),items:["15-19","20-29","30-39","40-49","50-59","60-69","70-79","80+"],rules:[o=>!!o||_.errors.sex||"This field is required"]},null,8,["modelValue","rules"])]),_:1})]),_:1})])])]),_:1}),a(v,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto text-base sm:text-sm"},{default:s(()=>[xe,(n(!0),u(y,null,w(_.cc_questions,(o,r)=>(n(),u("div",{key:r,class:"mb-10"},[t("div",we,[t("h2",null,c(o.title)+". "+c(o.question),1)]),r==0?(n(),u(y,{key:0},w(j,(i,f)=>t("div",{key:f},[t("h5",null,[a(U,{onClick:x=>S(r,o.id,i.value),modelValue:e.cc1,"onUpdate:modelValue":l[5]||(l[5]=x=>e.cc1=x),color:"primary",style:{"margin-top":"-50px","margin-left":"7%","margin-bottom":"-5%"},label:i.label,value:i.label},null,8,["onClick","modelValue","label","value"])])])),64)):b("",!0),r==1?(n(),u(y,{key:1},w(q,(i,f)=>t("div",{key:f},[a(U,{onClick:x=>S(r,o.id,i.value),modelValue:e.cc2,"onUpdate:modelValue":l[6]||(l[6]=x=>e.cc2=x),color:"primary",style:{"margin-top":"-50px","margin-left":"7%","margin-bottom":"-5%"},label:i.label,value:i.label},null,8,["onClick","modelValue","label","value"])])),64)):b("",!0),r==2?(n(),u(y,{key:2},w(B,(i,f)=>t("div",{key:f},[a(U,{onClick:x=>S(r,o.id,i.value),modelValue:e.cc3,"onUpdate:modelValue":l[7]||(l[7]=x=>e.cc3=x),color:"primary",style:{"margin-top":"-50px","margin-left":"7%","margin-bottom":"-5%"},label:i.label,value:i.value},null,8,["onClick","modelValue","label","value"])])),64)):b("",!0),g.value&&!e.cc_form.answer[r]?(n(),u("div",Ce,c("This selection is required"))):b("",!0)]))),128))]),_:1}),a(v,{"data-aos":"fade-left","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:s(()=>[t("div",ke,[t("h3",null,[h("HOW WOULD YOU RATE OUR "),t("span",Ve,c(_.unit.data[0].unit_name),1),h(" SERVICES?")])]),t("div",null,[(n(!0),u(y,null,w(_.dimensions,(o,r)=>(n(),I(v,{"data-aos":"fade-left","data-aos-duration":"1000","data-aos-delay":"500",class:"text-center over-flowhidden scroll-none mb-1",border:"1",key:o.id},{default:s(()=>[t("h5",Se,[t("span",Te,c(o.id)+". "+c(o.description)+"("+c(o.name)+")",1)]),t("input",{type:"hidden",value:L(r,o.id)},null,8,Oe),t("div",null,[(n(),u(y,null,w(M,i=>a(A,{class:"mb-5",modelValue:e.dimension_form.rate_score[r],"onUpdate:modelValue":f=>e.dimension_form.rate_score[r]=f,key:i.value,rules:[()=>g.value?!!e.dimension_form.rate_score[r]||"This selection is required":!0]},{default:s(()=>[a(k,{onClick:f=>H(r,e.dimension_form.rate_score[r]),rounded:"",class:"mr-2 bg-gray-200",value:i.value,color:"secondary"},{default:s(()=>[a(G,{color:e.dimension_form.rate_score[r]===i.value?i.color:"gray",size:"40"},{default:s(()=>[h(c(i.icon),1)]),_:2},1032,["color"]),Ue,t("label",null,c(i.label),1)]),_:2},1032,["onClick","value"])]),_:2},1032,["modelValue","onUpdate:modelValue","rules"])),64)),g.value&&!e.dimension_form.rate_score[r]?(n(),u("div",Ae,c("This selection is required"))):b("",!0)]),t("div",Ie,[Ee,t("div",null,[t("div",ze,[(n(),u(y,null,w(P,i=>a(A,{modelValue:e.dimension_form.importance_rate_score[r],"onUpdate:modelValue":f=>e.dimension_form.importance_rate_score[r]=f,key:i.value,mandatory:""},{default:s(()=>[a(k,{class:"mr-2",value:i.value,color:"secondary",style:{"border-radius":"40%"}},{default:s(()=>[a(z,null,{default:s(()=>[t("label",null,c(i.label),1)]),_:2},1024)]),_:2},1032,["value"])]),_:2},1032,["modelValue","onUpdate:modelValue"])),64)),g.value&&!e.dimension_form.importance_rate_score[r]?(n(),u("div",De,c("This selection is required"))):b("",!0)])])])]),_:2},1024))),128)),a(W)])]),_:1}),a(v,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:s(()=>[Fe,t("div",Ne,[(n(),u(y,null,w($,o=>a(A,{modelValue:e.recommend_rate_score,"onUpdate:modelValue":l[8]||(l[8]=r=>e.recommend_rate_score=r),mandatory:"",key:o.value},{default:s(()=>[a(k,{value:o.value,class:"mr-2",color:(e.recommend_rate_score===o.color,"secondary"),style:{"border-radius":"40%"}},{default:s(()=>[a(z,null,{default:s(()=>[t("label",null,c(o.label),1)]),_:2},1024)]),_:2},1032,["value","color"])]),_:2},1032,["modelValue"])),64)),g.value&&!e.recommend_rate_score?(n(),u("div",je,c("This selection is required"))):b("",!0)])]),_:1}),a(v,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:s(()=>[t("div",qe,[h("Please write your comment/suggestions below. "),e.is_complaint==!0?(n(),u("span",Be,"*")):(n(),u("span",Me,"(Optional)"))]),a(Y,{fluid:""},{default:s(()=>[e.is_complaint==!0?(n(),I(D,{key:0,modelValue:e.comment,"onUpdate:modelValue":l[9]||(l[9]=o=>e.comment=o),placeholder:"Input here!",rules:[o=>!!o||g.value&&!e.comment||"This field is required"]},null,8,["modelValue","rules"])):e.is_complaint==!1?(n(),I(D,{key:1,modelValue:e.comment,"onUpdate:modelValue":l[10]||(l[10]=o=>e.comment=o),placeholder:"Input here"},null,8,["modelValue"])):b("",!0)]),_:1}),g.value&&e.is_complaint==!0?(n(),u("div",Pe,[h(c("This selection is required because you rate low to our services with the options above."),1),$e,h(" Please input the reason/s why you have rated low.")])):b("",!0)]),_:1}),a(v,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:s(()=>[a(O,{class:"mt-5 mb-5 text-center"},{default:s(()=>[a(C,{cols:"6",class:"text-right"},{default:s(()=>[t("a",Le,[a(k,{class:"bg-secondary"},{default:s(()=>[h("Back")]),_:1})])]),_:1}),a(C,{cols:"6",class:"text-left"},{default:s(()=>[a(k,{color:"success",type:"submit",class:"mr-2","prepend-icon":"mdi-send",disabled:e.processing||e.is_complaint&&!e.comment},{default:s(()=>[h("Submit")]),_:1},8,["disabled"])]),_:1})]),_:1})]),_:1})])]),_:1})]),_:1})]),_:1})]),_:1})],64)}}};export{Ke as default};
