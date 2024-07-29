import{x as J,d as Q,y as X,i as ee,j as m,o as n,e as d,b as o,u as te,w as s,F as g,M as ae,Z as le,l as oe,a as t,t as c,g as h,f as p,c as x,h as w,O as se}from"./app-CX1n-mBn.js";import{_ as q}from"./dost-logo-B4Huuyod.js";import{A as ne}from"./aos-BADtXjhE.js";import{S as V}from"./sweetalert2.all-4noOKs9_.js";const re=ae('<nav data-aos="fade-down" data-aos-duration="500" data-aos-delay="500" class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600"><div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4"><a href="/" class="flex items-center space-x-3 rtl:space-x-reverse"><img src="'+q+'" class="h-8" alt="DOST Logo"><span class="self-center lg:text-2xl md:text-base sm:text-sm font-semibold whitespace-nowrap dark:text-white text-black">Department of Science and Technology </span></a></div></nav>',1),ie={class:"py-20 bg-gray-200"},ue=t("div",null,[t("img",{"data-aos":"zoom-in","data-aos-duration":"500","data-aos-delay":"500",class:"mx-auto sm:mb-0",style:{width:"200px",height:"200px"},src:q,alt:".."})],-1),de=t("span",{class:"font-black text-base lg:text-2xl md:text-base sm:text-sm","data-aos":"fade-down","data-aos-duration":"500","data-aos-delay":"500"},"CUSTOMER SATISFACTION FEEDBACK ",-1),ce=t("br",null,null,-1),me=t("a",{href:"#"},[t("img",{class:"rounded-t-lg",src:"/docs/images/blog/image-1.jpg",alt:""})],-1),_e={class:"p-5"},pe={href:"#"},fe={class:"mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"},be=t("br",null,null,-1),ve={key:0},he={key:1},ye={key:2,class:"ml-3"},ge={key:3,class:"ml-3"},xe=t("p",{class:"mb-3 font-normal text-gray-700 dark:text-gray-400"},"This questionaire aims to solicit your honest assessment of our services. Please take a minute in filling out this form and help us serve you better.",-1),we=t("div",{class:"m-5 text-gray-600"},[t("h2",null," Select your answer to the Citizen's Charter(CC) questions. The Citizen's Charter is an official document that reflects the services of a government agency/office including its requirements, fees and processing times among others. ")],-1),Ce={class:"mx-5"},ke={class:"font-black"},Ve={key:0,class:"text-red-800 m-5",style:{"margin-left":"80px"}},Se={class:"text-white bg-blue_grey p-3"},Te={style:{"text-transform":"uppercase"}},Oe={class:"mt-5 mb-3 text-left mx-5 bg-gray-200 p-3"},Ue={style:{"font-size":"18px"}},Ae=["value"],Ie=t("br",null,null,-1),Ee={key:0,class:"text-red-800"},ze={class:"overflow-hidden mb-3"},De=t("div",null,"How important is this attribute?",-1),Fe={class:"ml-2 mb-3"},qe={key:0,class:"text-red-800"},Ne=t("div",{class:"p-3 font-bold text-lg"},[h("Considering your complete experience with our agency, how likely would you recommend our services to others? "),t("span",{class:"text-red-800"},"*")],-1),je={class:"ml-2 mb-3 mx-auto my-auto mb-5 d-flex justify-center text-center",style:{"margin-right":"50px","margin-left":"50px"}},Be={key:0,class:"text-red-800"},Pe={class:"p-3 mt-0 font-bold text-lg"},Me={key:0,class:"text-red-800"},$e={key:1,class:"text-blue-400"},Le={key:0,class:"text-red-800 p-5"},Re=t("br",null,null,-1),He={href:"/",class:"btn bg-secondary"},Je={__name:"Index",props:{cc_questions:Object,dimensions:Object,unit:Object,sub_unit:Object,unit_psto:Object,sub_unit_psto:Object,status:String,errors:Object,captcha_img:String},setup(_){const N=_,j=[{label:"1. I know what a CC is and I saw this office's CC.",value:"1"},{label:"2. I know what a CC is but I did NOT see this office's CC. ",value:"2"},{label:"3. I learned the CC when I saw this office's CC.",value:"3"},{label:"4. I do not know what a CC is  and I did not see one in this office.(Answer 'N/A' on CC2 and CC3)",value:"4"}],B=[{label:"1. Easy to see",value:"1"},{label:"2. Somewhat easy to see",value:"2"},{label:"3. Difficult to see",value:"3"},{label:"4. not Visible at all",value:"4"},{label:"5. N/A",value:"5"}],P=[{label:"1. Helped Very Much",value:"1"},{label:"2. Somewhat helped",value:"2"},{label:"3. Did not help",value:"3"},{label:"4. N/A",value:"4"}],M=[{label:"Strongly Agree",value:"5",icon:"mdi-emoticon-excited",color:"#FFEB3B"},{label:"Agree",value:"4",icon:"mdi-emoticon-happy",color:"#FFC107"},{label:"Neither",value:"3",icon:"mdi-emoticon-neutral",color:"#263238"},{label:"Dissagree",value:"2",icon:"mdi-emoticon-sad",color:"#F44336"},{label:"Very Dissagree",value:"1",icon:"mdi-emoticon-frown",color:"#6200EA"},{label:"N/A",value:"6",icon:"mdi-close-circle-outline",color:"red"}],$=[{label:"5",value:"5"},{label:"4",value:"4"},{label:"3",value:"3"},{label:"2",value:"2"},{label:"1",value:"1"}],L=[{label:"10",value:"10"},{label:"9",value:"9"},{label:"8",value:"8"},{label:"7",value:"7"},{label:"6",value:"6"},{label:"5",value:"5"},{label:"4",value:"4"},{label:"3",value:"3"},{label:"2",value:"2"},{label:"1",value:"1"}],e=J({region_id:null,service_id:null,unit_id:null,sub_unit_id:null,psto_id:null,date:(()=>{const u=new Date,l=u.getFullYear(),f=String(u.getMonth()+1).padStart(2,"0"),b=String(u.getDate()).padStart(2,"0");return`${l}-${f}-${b}`})(),client_type:null,sub_unit_type:null,email:null,name:null,sex:null,age_group:null,pwd:0,pregnant:0,senior_citizen:0,cc1:null,cc2:null,cc3:null,recommend_rate_score:null,comment:null,is_complaint:!1,indication:null,dimension_form:{id:[],rate_score:[],importance_rate_score:[]},cc_form:{id:[],answer:[]},captcha:null,current_url:null,complaint_scanner:{value:[]}}),y=Q(!1),S=(u,l,f)=>{e.cc_form.id[u]=l,e.cc_form.answer[u]=f},R=(u,l)=>{e.dimension_form.id[u]=l};X(()=>{ne.init();const u=window.location.href,l=new URLSearchParams(u.split("?")[1]);e.region_id=l.get("region_id"),e.service_id=l.get("service_id"),e.unit_id=l.get("unit_id"),e.sub_unit_id=l.get("sub_unit_id"),e.psto_id=l.get("psto_id"),e.sub_unit_type=l.get("sub_unit_type"),e.current_url=u,V.fire({title:"Disclaimer",icon:"warning",text:"The DOST is committed to protect and respect your personal data privacy. All information collected will only be used for documentation purposes and will not be published in any platform."})});const H=async()=>{y.value=!0;let u=Math.random();const l=()=>'<img src="'+("/captcha/flat?rand="+u)+'" alt="CAPTCHA" style="display: block; margin: 0 auto; ">';try{V.fire({title:l(),html:'<div style="font-weight: bold; font-size:25px">Enter Captcha Code</div> <input id="captcha-input" class="swal2-input text-center"><p id="invalid-captcha-text" style="color: red; font-size: 14px; margin-top: 5px; display: none;">Invalid CAPTCHA code</p>',inputAttributes:{autocapitalize:"off"},showCancelButton:!0,confirmButtonText:"Submit",showLoaderOnConfirm:!0,preConfirm:()=>{const f=document.getElementById("captcha-input").value;return e.captcha=f,!0}}).then(f=>{f.isConfirmed&&se.post("/csf_submission",e)})}catch{V.fire({title:"Failed",icon:"error",text:"Something went wrong please check"})}},G=(u,l)=>{e.dimension_form.rate_score[u]>0&&e.dimension_form.rate_score[u]<4?e.complaint_scanner.value[u]=!0:e.complaint_scanner.value[u]=!1,e.is_complaint=!1,e.complaint_scanner.value.forEach(f=>{if(f===!0){e.is_complaint=!0;return}})};return ee(()=>N.errors,u=>{u&&V.fire({title:"Failed",icon:"error",text:"Something went wrong. Please check the fields and make sure the captcha is correctly entered. If you continue to get errors, please contact the administrator."})}),(u,l)=>{const f=m("v-card-title"),b=m("v-card"),T=m("v-text-field"),O=m("v-select"),C=m("v-col"),U=m("v-row"),A=m("v-radio"),I=m("v-radio-group"),W=m("v-icon"),k=m("v-btn"),E=m("v-btn-toggle"),z=m("v-chip"),Y=m("v-divider"),D=m("v-textarea"),K=m("v-container"),Z=m("v-form");return n(),d(g,null,[o(te(le),{title:"CSF Form"}),re,o(b,{class:"w-full","data-aos":"fade-up","data-aos-duration":"2000","data-aos-delay":"500"},{default:s(()=>[o(U,{justify:"center",class:"py-3 bg-gray-200 w-full"},{default:s(()=>[o(C,{cols:"12",md:"8",sm:"6"},{default:s(()=>[o(Z,{class:"max-w",onSubmit:oe(H,["prevent"])},{default:s(()=>[t("div",ie,[o(b,{class:"mb-3 md:mb-0 sm:mb-0 text-center"},{default:s(()=>[o(f,{class:"m-5 font-black flex flex-col items-center"},{default:s(()=>[ue,de,ce]),_:1})]),_:1}),o(b,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto text-base md:text-base sm:text-sm"},{default:s(()=>[me,t("div",_e,[t("a",pe,[t("h5",fe,[t("span",null,c(_.unit.data[0].unit_name),1),h(),be,_.sub_unit.data.length>0?(n(),d("span",ve,c(_.sub_unit.data[0].sub_unit_name),1)):p("",!0),_.unit_psto.data.length>0?(n(),d("span",he,c(_.unit_psto.data[0].psto.psto_name),1)):p("",!0),_.sub_unit_psto.data.length>0?(n(),d("span",ye,c(_.sub_unit_psto.data[0].psto.psto_name),1)):p("",!0),e.sub_unit_type?(n(),d("span",ge,c(e.sub_unit_type),1)):p("",!0)])]),xe,t("div",null,[e.is_complaint==!0?(n(),x(T,{key:0,modelValue:e.email,"onUpdate:modelValue":l[0]||(l[0]=a=>e.email=a),type:"email",placeholder:"email@gmail.com",label:"Email*",variant:"outlined",rules:[a=>!!a||y.value&&!e.email||"This field is required"]},null,8,["modelValue","rules"])):e.is_complaint==!1?(n(),x(T,{key:1,modelValue:e.email,"onUpdate:modelValue":l[1]||(l[1]=a=>e.email=a),type:"email",placeholder:"email@gmail.com",label:"Email(Optional)",variant:"outlined"},null,8,["modelValue"])):p("",!0),o(T,{modelValue:e.name,"onUpdate:modelValue":l[2]||(l[2]=a=>e.name=a),placeholder:"Enter your full name",label:"Name(Optional)",variant:"outlined"},null,8,["modelValue"]),o(U,{class:"mb-5"},{default:s(()=>[o(C,{cols:"12",md:"",sm:"4",style:{"margin-bottom":"-23px"}},{default:s(()=>[o(O,{label:"Client_type*",variant:"outlined",modelValue:e.client_type,"onUpdate:modelValue":l[3]||(l[3]=a=>e.client_type=a),items:["General Public","Internal Employees","Business/Organization","Government Employees"],rules:[a=>!!a||_.errors.client_type||"This field is required"]},null,8,["modelValue","rules"])]),_:1}),o(C,{cols:"12",md:"",sm:"4",style:{"margin-bottom":"-23px"}},{default:s(()=>[o(O,{label:"Sex*",variant:"outlined",modelValue:e.sex,"onUpdate:modelValue":l[4]||(l[4]=a=>e.sex=a),items:["Male","Female","Prefer not to say"],rules:[a=>!!a||_.errors.sex||"This field is required"]},null,8,["modelValue","rules"])]),_:1}),o(C,{cols:"12",md:"",sm:"4",style:{"margin-bottom":"-23px"}},{default:s(()=>[o(O,{label:"Age Group*",variant:"outlined",modelValue:e.age_group,"onUpdate:modelValue":l[5]||(l[5]=a=>e.age_group=a),items:["19 or lower","20-34","35-49","50-64","60+","Prefer not to say"],rules:[a=>!!a||_.errors.sex||"This field is required"]},null,8,["modelValue","rules"])]),_:1})]),_:1})])])]),_:1}),o(b,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto text-base sm:text-sm"},{default:s(()=>[we,(n(!0),d(g,null,w(_.cc_questions,(a,i)=>(n(),d("div",{key:i,class:"mb-0"},[t("div",Ce,[t("div",ke,[t("h2",null,c(a.title)+". "+c(a.question),1)]),i==0?(n(),x(I,{key:0,modelValue:e.cc1,"onUpdate:modelValue":l[6]||(l[6]=r=>e.cc1=r),color:"primary",class:"mx-2"},{default:s(()=>[(n(),d(g,null,w(j,(r,v)=>t("h5",{key:v},[o(A,{onClick:F=>S(i,a.id,r.value),label:r.label,value:r.value},null,8,["onClick","label","value"])])),64))]),_:2},1032,["modelValue"])):p("",!0),i==1?(n(),x(I,{key:1,modelValue:e.cc2,"onUpdate:modelValue":l[7]||(l[7]=r=>e.cc2=r),color:"primary",class:"mx-2"},{default:s(()=>[(n(),d(g,null,w(B,(r,v)=>t("h5",{key:v},[o(A,{onClick:F=>S(i,a.id,r.value),label:r.label,value:r.value},null,8,["onClick","label","value"])])),64))]),_:2},1032,["modelValue"])):p("",!0),i==2?(n(),x(I,{key:2,modelValue:e.cc3,"onUpdate:modelValue":l[8]||(l[8]=r=>e.cc3=r),color:"primary",class:"mx-2"},{default:s(()=>[(n(),d(g,null,w(P,(r,v)=>t("h5",{key:v},[o(A,{onClick:F=>S(i,a.id,r.value),label:r.label,value:r.value},null,8,["onClick","label","value"])])),64))]),_:2},1032,["modelValue"])):p("",!0)]),y.value&&!e.cc_form.answer[i]?(n(),d("div",Ve,c("This selection is required"))):p("",!0)]))),128))]),_:1}),o(b,{"data-aos":"fade-left","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:s(()=>[t("div",Se,[t("h3",null,[h("HOW WOULD YOU RATE OUR "),t("span",Te,c(_.unit.data[0].unit_name),1),h(" SERVICES?")])]),t("div",null,[(n(!0),d(g,null,w(_.dimensions,(a,i)=>(n(),x(b,{"data-aos":"fade-left","data-aos-duration":"1000","data-aos-delay":"500",class:"text-center over-flowhidden scroll-none mb-1",border:"1",key:a.id},{default:s(()=>[t("h5",Oe,[t("span",Ue,c(a.id)+". "+c(a.description)+"("+c(a.name)+")",1)]),t("input",{type:"hidden",value:R(i,a.id)},null,8,Ae),t("div",null,[(n(),d(g,null,w(M,r=>o(E,{class:"mb-5",modelValue:e.dimension_form.rate_score[i],"onUpdate:modelValue":v=>e.dimension_form.rate_score[i]=v,key:r.value,rules:[()=>y.value?!!e.dimension_form.rate_score[i]||"This selection is required":!0]},{default:s(()=>[o(k,{onClick:v=>G(i,e.dimension_form.rate_score[i]),rounded:"",class:"mr-2 bg-gray-200",value:r.value,color:"secondary"},{default:s(()=>[o(W,{color:e.dimension_form.rate_score[i]===r.value?r.color:"gray",size:"40"},{default:s(()=>[h(c(r.icon),1)]),_:2},1032,["color"]),Ie,t("label",null,c(r.label),1)]),_:2},1032,["onClick","value"])]),_:2},1032,["modelValue","onUpdate:modelValue","rules"])),64)),y.value&&!e.dimension_form.rate_score[i]?(n(),d("div",Ee,c("This selection is required"))):p("",!0)]),t("div",ze,[De,t("div",null,[t("div",Fe,[(n(),d(g,null,w($,r=>o(E,{modelValue:e.dimension_form.importance_rate_score[i],"onUpdate:modelValue":v=>e.dimension_form.importance_rate_score[i]=v,key:r.value,mandatory:""},{default:s(()=>[o(k,{class:"mr-2",value:r.value,color:"secondary",style:{"border-radius":"40%"}},{default:s(()=>[o(z,null,{default:s(()=>[t("label",null,c(r.label),1)]),_:2},1024)]),_:2},1032,["value"])]),_:2},1032,["modelValue","onUpdate:modelValue"])),64)),y.value&&!e.dimension_form.importance_rate_score[i]?(n(),d("div",qe,c("This selection is required"))):p("",!0)])])])]),_:2},1024))),128)),o(Y)])]),_:1}),o(b,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:s(()=>[Ne,t("div",je,[(n(),d(g,null,w(L,a=>o(E,{modelValue:e.recommend_rate_score,"onUpdate:modelValue":l[9]||(l[9]=i=>e.recommend_rate_score=i),mandatory:"",key:a.value},{default:s(()=>[o(k,{value:a.value,class:"mr-2",color:(e.recommend_rate_score===a.color,"secondary"),style:{"border-radius":"40%"}},{default:s(()=>[o(z,null,{default:s(()=>[t("label",null,c(a.label),1)]),_:2},1024)]),_:2},1032,["value","color"])]),_:2},1032,["modelValue"])),64)),y.value&&!e.recommend_rate_score?(n(),d("div",Be,c("This selection is required"))):p("",!0)])]),_:1}),o(b,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:s(()=>[t("div",Pe,[h("Please write your comment/suggestions below. "),e.is_complaint==!0?(n(),d("span",Me,"*")):(n(),d("span",$e,"(Optional)"))]),o(K,{fluid:""},{default:s(()=>[e.is_complaint==!0?(n(),x(D,{key:0,modelValue:e.comment,"onUpdate:modelValue":l[10]||(l[10]=a=>e.comment=a),placeholder:"Input here!",rules:[a=>!!a||y.value&&!e.comment||"This field is required"]},null,8,["modelValue","rules"])):e.is_complaint==!1?(n(),x(D,{key:1,modelValue:e.comment,"onUpdate:modelValue":l[11]||(l[11]=a=>e.comment=a),placeholder:"Input here"},null,8,["modelValue"])):p("",!0)]),_:1}),y.value&&e.is_complaint==!0?(n(),d("div",Le,[h(c("This selection is required because you rate low to our services with the options above."),1),Re,h(" Please input the reason/s why you have rated low.")])):p("",!0)]),_:1}),o(b,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:s(()=>[o(U,{class:"mt-5 mb-5 text-center"},{default:s(()=>[o(C,{cols:"6",class:"text-right"},{default:s(()=>[t("a",He,[o(k,{class:"bg-secondary"},{default:s(()=>[h("Back")]),_:1})])]),_:1}),o(C,{cols:"6",class:"text-left"},{default:s(()=>[o(k,{color:"success",type:"submit",class:"mr-2","prepend-icon":"mdi-send",disabled:e.processing||e.is_complaint&&!e.comment},{default:s(()=>[h("Submit")]),_:1},8,["disabled"])]),_:1})]),_:1})]),_:1})])]),_:1})]),_:1})]),_:1})]),_:1})],64)}}};export{Je as default};
