import{A as j}from"./AppLayout-Dj2j_gOz.js";import"./index-Beng-4A6.js";import{_ as B}from"./Modal.vue_vue_type_script_setup_true_lang-C_QP4hmf.js";import"./vue-multiselect.css_vue_type_style_index_0_src_true_lang-CAVZg4_u.js";import{d as r,i as N,j as c,o as u,c as D,w as a,a as t,b as l,g as h,t as d,u as p,k as T,e as _,h as F,F as y,O as k}from"./app-Conszyh8.js";import"./sweetalert2.all-BSn2qh2e.js";import"./dost-logo-B4Huuyod.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const L=t("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," Unit PSTOs ",-1),P={class:"py-12"},R={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},$={class:"bg-white overflow-hidden shadow-xl sm:rounded-lg"},z={class:"w-full m-5 text-right"},E=t("thead",null,[t("tr",null,[t("th",null,"ID"),t("th",{class:"text-left"}," Unit "),t("th",{class:"text-center"}," Actions ")])],-1),M={class:"text-center"},q={key:1,span:""},G={class:"m-2"},H={style:{color:"gray"}},J={class:"text-center"},st={__name:"Index",props:{units:Object,unit_pstos:Object,pstos:Object},setup(i){const f=r(!1),x=r(null);r({});const v=r({}),g=r("");N(()=>g.value,n=>{k.get("/unit-pstos",{search:n},{preserveState:!0})});const b=async(n,s,m)=>{console.log(m,333),f.value=n,x.value=s,v.value=m},w=async()=>{v.value={}};let o=1;const V=async n=>{k.visit("/unit-pstos?page="+n,{preserveState:!0}),o=n};return(n,s)=>{const m=c("v-text-field"),C=c("v-col"),U=c("v-row"),O=c("v-btn"),S=c("v-pagination"),A=c("v-table"),I=c("v-card");return u(),D(j,{title:"Dashboard"},{header:a(()=>[L]),default:a(()=>[t("div",P,[t("div",R,[t("div",$,[l(U,{style:{"margin-bottom":"-30px"}},{default:a(()=>[l(C,{cols:"6"},{default:a(()=>[t("div",z,[l(m,{loading:n.loading,"append-inner-icon":"mdi-magnify",density:"compact",label:"Search",variant:"solo","hide-details":"","single-line":"",modelValue:g.value,"onUpdate:modelValue":s[0]||(s[0]=e=>g.value=e),"onClick:appendInner":n.onClick},null,8,["loading","modelValue","onClick:appendInner"])])]),_:1})]),_:1}),l(I,{class:"m-3"},{default:a(()=>[l(A,null,{bottom:a(()=>[t("div",G,[t("span",H,[h(" Showing "+d(i.units.from)+" to "+d(i.units.to)+" out of ",1),t("b",null,d(i.units.total)+" records",1),h(" "+d(p(o)),1)]),t("div",J,[l(S,{modelValue:p(o),"onUpdate:modelValue":s[1]||(s[1]=e=>T(o)?o.value=e:o=e),length:i.units.last_page,circle:"",onClick:s[2]||(s[2]=e=>V(p(o)))},null,8,["modelValue","length"])])])]),default:a(()=>[E,t("tbody",null,[(u(!0),_(y,null,F(i.units.data,(e,K)=>(u(),_("tr",{key:e.id,class:"hover:bg-gray-200"},[e?(u(),_(y,{key:0},[t("td",null,d(e.id),1),t("td",null,d(e.unit_name),1),t("td",M,[l(O,{onClick:Q=>b(!0,"Assign",e),size:"small","prepend-icon":"mdi-update",color:"primary"},{default:a(()=>[h(" Assign ")]),_:2},1032,["onClick"])])],64)):(u(),_("td",q," No data at the moment"))]))),128))])]),_:1})]),_:1})])])]),l(B,{value:f.value,unit:v.value,unit_pstos:i.unit_pstos,pstos:i.pstos,action:x.value,page_number:p(o),onInput:b,onReloadUnits:w},null,8,["value","unit","unit_pstos","pstos","action","page_number"])]),_:1})}}};export{st as default};