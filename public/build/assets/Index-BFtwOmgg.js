import{A as L}from"./AppLayout-Dsk-exo9.js";import J from"./Content-D9i3w4Ca.js";import z from"./Q1Content-DrLgKATV.js";import K from"./Q2Content-Df1mxYu2.js";import W from"./Q3Content-DI3gyZ8j.js";import X from"./Q4Content-CY-EQkZQ.js";import Z from"./Content-B2hXb86L.js";import ee from"./ByUnitQuarter1-CTBdwAuz.js";import te from"./ByUnitQuarter2-cBsuCm9J.js";import re from"./ByUnitQuarter3-C0h2_l_P.js";import ae from"./ByUnitQuarter4-DTfGR38G.js";import oe from"./ByUnitYearly-Cgrr2991.js";import{_ as le}from"./Modal.vue_vue_type_script_setup_true_lang-C32IW-Lw.js";import{s as b}from"./vue-multiselect.css_vue_type_style_index_0_src_true_lang-Ca8NHSEv.js";import{x as q,d as N,p as se,y as ne,i as A,j as p,o as l,c as u,w as a,a as h,b as r,e as D,t as Y,f as s,u as f,g as v,O}from"./app-B-5dCsTv.js";import{S as x}from"./sweetalert2.all-B1nFWsCJ.js";import{P as ue}from"./index-DajnbktX.js";import"./dost-logo-B4Huuyod.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";import"./ByUnitMonthly-D6mGnXHr.js";const _e=h("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," Customer Satisfaction Index ",-1),ie={class:"py-10 overflow-visible",style:{"margin-left":"80px","margin-right":"80px"}},de={class:"max-w-7x1 mx-auto sm:px-6 lg:px-8 overflow-visibl"},me={class:"bg-white shadow-xl sm:rounded-lg overflow-visible"},ce={key:0},pe={key:1},be={class:"my-auto overflow-visible"},qe={__name:"Index",props:{service:Object,unit:Object,sub_unit:Object,unit_pstos:Object,sub_unit_pstos:Object,sub_unit_types:Object,y_totals:Object,grand_vs_total:Number,grand_s_total:Number,grand_n_total:Number,grand_d_total:Number,grand_vd_total:Number,x_totals:Object,x_grand_total:Object,likert_scale_rating_totals:Object,lsr_grand_total:Number,importance_rate_score_totals:Object,x_importance_totals:Object,importance_ilsr_totals:Object,gap_totals:Object,gap_grand_total:Number,wf_totals:Object,gap_grand_total:Number,ss_totals:Object,ws_totals:Object,total_respondents:Number,total_vss_respondents:Number,percentage_vss_respondents:Number,customer_satisfaction_rating:Number,customer_satisfaction_index:Number,net_promoter_score:Number,percentage_promoters:Number,percentage_detractors:Number,total_comments:Number,total_complaints:Number,comments:Object,cc_data:Object,user:Object,assignatorees:Object,dimensions:Object,service:Object,unit:String,respondents_list:Object,trp_totals:Number,grand_total_raw_points:Number,vs_grand_total_raw_points:Number,vs_grand_total_raw_points:Number,s_grand_total_raw_points:Number,ndvd_grand_total_raw_points:Object,p1_total_scores:Object,vs_grand_total_score:Object,s_grand_total_score:Object,ndvd_grand_total_score:Object,grand_total_score:Number,lsr_totals:Object,lsr_grand_total:Number,lsr_average:Number,vs_totals:Object,s_totals:Object,n_totals:Object,d_totals:Object,vd_totals:Object,grand_totals:Object,first_month_total_vs_respondents:Object,second_month_total_vs_respondents:Object,third_month_total_vs_respondents:Object,first_month_total_s_respondents:Object,second_month_total_s_respondents:Object,third_month_total_s_respondents:Object,first_month_total_ndvd_respondents:Object,second_month_total_ndvd_respondents:Object,third_month_total_ndvd_respondents:Object,first_month_total_respondents:Object,second_month_total_respondents:Object,third_month_total_respondents:Object,total_respondents:Number,total_vss_respondents:Number,percentage_vss_respondents:Number,total_promoters:Number,total_detractors:Number,vi_totals:Object,i_totals:Object,mi_totals:Object,si_totals:Object,nai_totals:Object,i_grand_totals:Object,i_trp_totals:Object,i_grand_total_raw_points:Object,vi_grand_total_raw_points:Object,s_grand_total_raw_points:Object,misinai_grand_total_raw_points:Object,i_total_scores:Object,vi_grand_total_score:Number,i_grand_total_score:Number,misinai_grand_total_score:Number,percentage_promoters:Number,first_month_percentage_promoters:Number,second_month_percentage_promoters:Number,third_month_percentage_promoters:Number,average_percentage_promoters:Number,first_month_percentage_detractors:Number,second_percentage_detractors:Number,third_month_percentage_detractors:Number,average_percentage_detractors:Number,first_month_net_promoter_score:Number,second_month_net_promoter_score:Number,third_month_net_promoter_score:Number,average_percentage_detractors:Number,first_month_net_promoter_score:Number,second_month_net_promoter_score:Number,third_month_net_promoter_score:Number,third_month_net_promoter_score:Number,ave_net_promoter_score:Number,customer_satisfaction_rating:Number,csi:Number,first_month_csi:Number,second_month_csi:Number,third_month_csi:Number,first_month_vs_grand_total:Number,second_month_vs_grand_total:Number,third_month_vs_grand_total:Number,first_month_s_grand_total:Number,second_month_s_grand_total:Number,third_month_s_grand_total:Number,first_month_ndvd_grand_total:Number,second_month_ndvd_grand_total:Number,third_month_ndvd_grand_total:Number,first_month_grand_total:Number,second_month_grand_total:Number,third_month_grand_total:Number,total_comments:Number,total_complaints:Number,comments:Object,customer_satisfaction_index:Number,net_promoter_score:Number,percentage_promoters:Number,q1_percentage_promoters:Number,q2_percentage_promoters:Number,q3_percentage_promoters:Number,q4_percentage_promoters:Number,average_percentage_promoters:Number,q1_percentage_detractors:Number,q2_percentage_detractors:Number,q3_percentage_detractors:Number,q4_percentage_detractors:Number,average_percentage_detractors:Number,q1_net_promoter_score:Number,q2_net_promoter_score:Number,q3_net_promoter_score:Number,q4_net_promoter_score:Number,ave_net_promoter_score:Number,customer_satisfaction_rating:Number,q1_csi:Number,q2_csi:Number,q3_csi:Number,q4_csi:Number,csi:Number},setup(n){const m=n,e=q({service:null,unit:null,unit_id:null,selected_sub_unit:[],selected_unit_psto:[],selected_sub_unit_psto:[],form_type:null,date_from:null,date_to:null,csi_type:null,selected_month:null,selected_year:null,selected_quarter:null,client_type:null,sex:null,age_group:null});q({generated_url:null});const i=N(!1),S=se(()=>{const d=new Date().getFullYear();return Array.from({length:9},(y,k)=>(d-k).toString())}),V=["JANUARY","FEBRUARY","MARCH","APRIL","MAY","JUNE","JULY","AUGUST","SEPTEMBER","OCTOBER","NOVEMBER","DECEMBER"],P=N(M());function M(){return new Date().getFullYear().toString()}const I=N(F());function F(){return V[new Date().getMonth()]}ne(()=>{e.selected_month=I.value,e.selected_year=P.value,i.value==!1});const w=async(d,t)=>{i.value=!0,e.service=d,e.unit=t,e.unit_id=t.data[0].id,e.csi_type=="By Date"?e.date_from&&e.date_to?O.post("/csi/generate",e,{preserveState:!0,preserveScroll:!0}):x.fire({title:"Error",icon:"error",text:"Please fill up Date From and Date To field."}):e.csi_type=="By Month"?(e.selected_quarter="",O.post("/csi/generate",e,{preserveState:!0,preserveScroll:!0})):e.csi_type=="By Quarter"?(e.selected_month="",e.selected_quarter?O.post("/csi/generate",e,{preserveState:!0,preserveScroll:!0}):x.fire({title:"Error",icon:"error",text:"Please select a quarter first!"})):e.csi_type=="By Year/Annual"&&(e.selected_quarter="",e.selected_year?O.post("/csi/generate",e,{preserveState:!0,preserveScroll:!0}):x.fire({title:"Error",icon:"error",text:"Please select year first!"}))};function j(){window.history.back()}A(()=>e.selected_sub_unit,d=>{i.value=!1,$(d.id)}),A(()=>e.csi_type,d=>{d==""&&(e.selected_sub_unit=null)});const $=async d=>{const t=new URLSearchParams(window.location.search);t.set("sub_unit_id",d);const y=`/csi?${t.toString()}`;await O.visit(y,{preserveState:!0,preserveScroll:!0})},H=N(!1),U=async()=>{H.value=!0,(await new ue).print(document.querySelector(".print-id"),[` 
          @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;600;800&family=Roboto:wght@100;300;400;500;700;900&display=swap');
          * {
              font-family: 'Time New Roman'
          }
          .new-page {
              page-break-before: always;
          }
          .th-color{
              background-color: #8fd1e8;
          }
          .text-center{
            text-align: center;
          }
          .text-right{
            text-align:end
          }
          table {
            border-collapse: collapse;
            width: 100%; /* Optional: Set a width for the table */
          }

          tr, th, td {
            border: 1px solid rgb(145, 139, 139); /* Optional: Add a border for better visibility */
            padding: 1px; /* Optional: Add padding for better spacing */
          }
          .page-break {
            page-break-before: always; /* or page-break-after: always; */
          }
          .text-left{
            text-align: left;
          }
          .text-center{
            text-align: center;
          }
          .bg-blue{
            background: blue;
          }

        `])},B=N(!1),T=async d=>{B.value=d};return(d,t)=>{const y=p("v-divider"),k=p("v-card-title"),E=p("v-card"),_=p("v-col"),g=p("v-row"),C=p("v-text-field"),c=p("v-btn"),Q=p("v-select"),R=p("v-combobox"),G=p("v-card-body");return l(),u(L,{title:"Dashboard",class:"overflow-visible"},{header:a(()=>[_e]),default:a(()=>[h("div",ie,[h("div",de,[h("div",me,[n.service&&n.unit?(l(),u(E,{key:0,class:"mb-5 overflow-visible"},{default:a(()=>[r(k,{class:"m-3"},{default:a(()=>[n.service?(l(),D("div",ce," SERVICES : "+Y(n.service.services_name),1)):s("",!0),r(y,{class:"border-opacity-100"}),n.unit?(l(),D("div",pe," SERVICE UNIT : "+Y(n.unit.data[0].unit_name),1)):s("",!0)]),_:1})]),_:1})):s("",!0),r(E,{class:"overflow-visible mb-5"},{default:a(()=>[r(y,{class:"border-opacity-100"}),r(g,{class:"p-3 overflow-visible"},{default:a(()=>[r(_,{class:"my-auto overflow-visible"},{default:a(()=>[h("div",be,[r(f(b),{modelValue:e.csi_type,"onUpdate:modelValue":t[0]||(t[0]=o=>e.csi_type=o),"prepend-icon":"mdi-account",options:["By Date","By Month","By Quarter","By Year/Annual"],multiple:!1,placeholder:"Select Type*","allow-empty":!1},null,8,["modelValue"])])]),_:1}),n.unit.data[0].id==8||n.user.account_type=="planning"?(l(),u(_,{key:0,class:"my-auto"},{default:a(()=>[r(f(b),{modelValue:e.client_type,"onUpdate:modelValue":t[1]||(t[1]=o=>e.client_type=o),"prepend-icon":"mdi-account",options:["Internal","External"],multiple:!1,placeholder:"Select Client Type","allow-empty":!0},null,8,["modelValue"])]),_:1})):s("",!0),n.unit.data[0].sub_units.length>0?(l(),u(_,{key:1,class:"my-auto"},{default:a(()=>[r(f(b),{modelValue:e.selected_sub_unit,"onUpdate:modelValue":t[2]||(t[2]=o=>e.selected_sub_unit=o),"prepend-icon":"mdi-account",options:n.unit.data[0].sub_units,multiple:!1,placeholder:"Select Sub Unit*",label:"sub_unit_name","track-by":"sub_unit_name","allow-empty":!1,disabled:i.value},null,8,["modelValue","options","disabled"])]),_:1})):s("",!0),n.unit_pstos.length>0?(l(),u(_,{key:2,class:"my-auto mr-5 ml-5"},{default:a(()=>[r(f(b),{modelValue:e.selected_unit_psto,"onUpdate:modelValue":t[3]||(t[3]=o=>e.selected_unit_psto=o),options:n.unit_pstos,multiple:!1,placeholder:"Select Unit PSTO",label:"psto_name","track-by":"psto_name","allow-empty":!1},null,8,["modelValue","options"])]),_:1})):s("",!0),n.sub_unit_pstos.length>0?(l(),u(_,{key:3,class:"my-auto mr-5"},{default:a(()=>[r(f(b),{modelValue:e.selected_sub_unit_psto,"onUpdate:modelValue":t[4]||(t[4]=o=>e.selected_sub_unit_psto=o),options:n.sub_unit_pstos,multiple:!1,placeholder:"Select Sub Unit PSTO",label:"psto_name","track-by":"psto_name","allow-empty":!1},null,8,["modelValue","options"])]),_:1})):s("",!0),n.sub_unit_types.length>0&&e.selected_sub_unit?(l(),u(_,{key:4,class:"my-auto"},{default:a(()=>[r(f(b),{modelValue:e.sub_unit_type,"onUpdate:modelValue":t[5]||(t[5]=o=>e.sub_unit_type=o),options:n.sub_unit_types,multiple:!1,placeholder:"Select Driving Type",label:"type_name","track-by":"type_name","allow-empty":!1},null,8,["modelValue","options"])]),_:1})):s("",!0)]),_:1}),r(y,{class:"border-opacity-100"}),n.user.account_type=="planning"?(l(),u(g,{key:0,class:"p-3 overflow-visible"},{default:a(()=>[r(_,{class:"my-auto"},{default:a(()=>[r(f(b),{modelValue:e.sex,"onUpdate:modelValue":t[6]||(t[6]=o=>e.sex=o),"prepend-icon":"mdi-account",options:["Male","Female","Prefer not to say"],multiple:!1,placeholder:"Select Sex","allow-empty":!0},null,8,["modelValue"])]),_:1}),r(_,{class:"my-auto"},{default:a(()=>[r(f(b),{modelValue:e.age_group,"onUpdate:modelValue":t[7]||(t[7]=o=>e.age_group=o),"prepend-icon":"mdi-account",options:["19 or lower","20-34","35-49","50-64","60+","Prefer not to say"],multiple:!1,placeholder:"Select Age Group","allow-empty":!0},null,8,["modelValue"])]),_:1})]),_:1})):s("",!0),r(y,{class:"border-opacity-100"}),r(G,{class:"overflow-visible mb-2"},{default:a(()=>[e.csi_type=="By Date"?(l(),u(g,{key:0,class:"p-3"},{default:a(()=>[r(_,{class:"my-auto"},{default:a(()=>[r(C,{label:"Select Date From",placeholder:"Date From",variant:"outlined",size:"x-small",type:"date",modelValue:e.date_from,"onUpdate:modelValue":t[8]||(t[8]=o=>e.date_from=o)},null,8,["modelValue"])]),_:1}),r(_,null,{default:a(()=>[r(C,{label:"Select Date To",placeholder:"Date To",variant:"outlined",size:"x-small",type:"date",modelValue:e.date_to,"onUpdate:modelValue":t[9]||(t[9]=o=>e.date_to=o)},null,8,["modelValue"])]),_:1}),r(_,{class:"ml-5"},{default:a(()=>[r(c,{onClick:t[10]||(t[10]=o=>w(n.service,n.unit))},{default:a(()=>[v("Generate")]),_:1}),i.value?(l(),u(c,{key:0,onClick:t[11]||(t[11]=o=>j()),icon:"mdi-refresh",variant:"text"})):s("",!0)]),_:1})]),_:1})):s("",!0),e.csi_type=="By Month"?(l(),u(g,{key:1,class:"p-3"},{default:a(()=>[r(_,{class:"my-auto"},{default:a(()=>[r(Q,{modelValue:e.selected_month,"onUpdate:modelValue":t[12]||(t[12]=o=>e.selected_month=o),class:"m-3",label:"Select Month",variant:"outlined",items:V,outlined:"none"},null,8,["modelValue"])]),_:1}),r(_,{class:"my-auto"},{default:a(()=>[r(Q,{modelValue:e.selected_year,"onUpdate:modelValue":t[13]||(t[13]=o=>e.selected_year=o),class:"m-3",label:"Select Year",variant:"outlined",items:S.value,outlined:"none"},null,8,["modelValue","items"])]),_:1}),r(_,{class:"ml-5 mt-3"},{default:a(()=>[r(c,{onClick:t[14]||(t[14]=o=>w(n.service,n.unit))},{default:a(()=>[v("Generate")]),_:1}),i.value?(l(),u(c,{key:0,onClick:t[15]||(t[15]=o=>j()),icon:"mdi-refresh",variant:"text"})):s("",!0)]),_:1}),r(_,{class:"text-end mr-5 m-3"},{default:a(()=>[r(c,{disabled:i.value==!1,"prepend-icon":"mdi-printer",onClick:t[16]||(t[16]=o=>T(!0))},{default:a(()=>[v("Print")]),_:1},8,["disabled"])]),_:1})]),_:1})):s("",!0),e.csi_type=="By Quarter"?(l(),u(g,{key:2,class:"p-3"},{default:a(()=>[r(_,{class:"my-auto"},{default:a(()=>[r(R,{modelValue:e.selected_quarter,"onUpdate:modelValue":t[17]||(t[17]=o=>e.selected_quarter=o),class:"m-3",label:"Select Quarter",variant:"outlined",items:["FIRST QUARTER","SECOND QUARTER","THIRD QUARTER","FOURTH QUARTER"],outlined:"none"},null,8,["modelValue"])]),_:1}),r(_,{class:"my-auto"},{default:a(()=>[r(R,{modelValue:e.selected_year,"onUpdate:modelValue":t[18]||(t[18]=o=>e.selected_year=o),class:"m-3",label:"Select Year",variant:"outlined",items:S.value,outlined:"none"},null,8,["modelValue","items"])]),_:1}),r(_,{class:"ml-5 mt-3"},{default:a(()=>[r(c,{onClick:t[19]||(t[19]=o=>w(n.service,n.unit))},{default:a(()=>[v("Generate")]),_:1}),i.value?(l(),u(c,{key:0,onClick:t[20]||(t[20]=o=>j()),icon:"mdi-refresh",variant:"text"})):s("",!0)]),_:1}),r(_,{class:"text-end mr-5 m-3"},{default:a(()=>[r(c,{disabled:i.value==!1,"prepend-icon":"mdi-printer",onClick:t[21]||(t[21]=o=>U())},{default:a(()=>[v("Print")]),_:1},8,["disabled"])]),_:1})]),_:1})):s("",!0),e.csi_type=="By Year/Annual"?(l(),u(g,{key:3,class:"p-3"},{default:a(()=>[r(_,{class:"my-auto"},{default:a(()=>[r(R,{modelValue:e.selected_year,"onUpdate:modelValue":t[22]||(t[22]=o=>e.selected_year=o),class:"m-3",label:"Select Year",variant:"outlined",items:S.value,outlined:"none"},null,8,["modelValue","items"])]),_:1}),r(_,{class:"ml-5 mt-3"},{default:a(()=>[r(c,{onClick:t[23]||(t[23]=o=>w(n.service,n.unit))},{default:a(()=>[v("Generate")]),_:1}),i.value?(l(),u(c,{key:0,onClick:t[24]||(t[24]=o=>j()),icon:"mdi-refresh",variant:"text"})):s("",!0)]),_:1}),r(_,{class:"text-end mr-5 m-3"},{default:a(()=>[r(c,{disabled:i.value==!1,"prepend-icon":"mdi-printer",onClick:t[25]||(t[25]=o=>U())},{default:a(()=>[v("Print")]),_:1},8,["disabled"])]),_:1})]),_:1})):s("",!0)]),_:1})]),_:1}),e.csi_type=="By Month"&&i.value==!0||e.csi_type=="By Date"&&i.value==!0?(l(),u(J,{key:1,form:e,data:m},null,8,["form","data"])):s("",!0),e.csi_type=="By Quarter"&&e.selected_quarter=="FIRST QUARTER"&&i.value==!0?(l(),u(z,{key:2,form:e,data:m},null,8,["form","data"])):s("",!0),e.csi_type=="By Quarter"&&e.selected_quarter=="SECOND QUARTER"&&i.value==!0?(l(),u(K,{key:3,form:e,data:m},null,8,["form","data"])):s("",!0),e.csi_type=="By Quarter"&&e.selected_quarter=="THIRD QUARTER"&&i.value==!0?(l(),u(W,{key:4,form:e,data:m},null,8,["form","data"])):s("",!0),e.csi_type=="By Quarter"&&e.selected_quarter=="FOURTH QUARTER"&&i.value==!0?(l(),u(X,{key:5,form:e,data:m},null,8,["form","data"])):s("",!0),e.csi_type=="By Year/Annual"&&i.value==!0?(l(),u(Z,{key:6,form:e,data:m},null,8,["form","data"])):s("",!0),e.csi_type=="By Quarter"&&e.selected_quarter=="FIRST QUARTER"?(l(),u(ee,{key:7,form:e,data:m},null,8,["form","data"])):s("",!0),e.csi_type=="By Quarter"&&e.selected_quarter=="SECOND QUARTER"?(l(),u(te,{key:8,form:e,data:m},null,8,["form","data"])):s("",!0),e.csi_type=="By Quarter"&&e.selected_quarter=="THIRD QUARTER"?(l(),u(re,{key:9,form:e,data:m},null,8,["form","data"])):s("",!0),e.csi_type=="By Quarter"&&e.selected_quarter=="FOURTH QUARTER"?(l(),u(ae,{key:10,form:e,data:m},null,8,["form","data"])):s("",!0),e.csi_type=="By Year/Annual"?(l(),u(oe,{key:11,form:e,data:m},null,8,["form","data"])):s("",!0),i.value?(l(),u(le,{key:12,value:B.value,form:e,assignatorees:n.assignatorees,user:n.user,onInput:T,data:m},null,8,["value","form","assignatorees","user","data"])):s("",!0)])])])]),_:1})}}};export{qe as default};
