import{A as z}from"./AppLayout-B5VLNE5p.js";import{_ as U}from"./Modal.vue_vue_type_script_setup_true_lang-pIVyAEZo.js";import{d as c,i as $,j as a,o as r,c as D,w as l,a as e,b as n,g as m,t as u,u as k,k as L,e as p,h as R,F as C,O as g}from"./app-BzQVbPtk.js";import{S as j}from"./sweetalert2.all-CoPeW7XL.js";import"./dost-logo-B4Huuyod.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const F=e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," PSTOs ",-1),E={class:"py-12"},M={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},Y={class:"bg-white overflow-hidden shadow-xl sm:rounded-lg"},q={class:"w-full m-5 text-right"},G={class:"text-right m-5"},H=e("thead",null,[e("tr",null,[e("th",{class:"text-left"}," # "),e("th",{class:"text-left"}," Name "),e("th",{class:"text-center"}," Actions ")])],-1),J={class:"text-center"},K={key:1,span:""},Q={class:"m-2"},W={style:{color:"gray"}},X={class:"text-center"},ne={__name:"Index",props:{pstos:Object},setup(i){const x=c(!1),y=c(null);c({});const w=c({}),_=c("");$(()=>_.value,s=>{g.get("/pstos",{search:s},{preserveState:!0})});const v=async(s,t,f)=>{x.value=s,y.value=t,w.value=f},S=async s=>{j.fire({html:'<div style="font-weight: bold; font-size:25px">Are you sure you want to delete this record?</div> ',icon:"warning",showCancelButton:!0,confirmButtonText:"Yes, I'm sure",showLoaderOnConfirm:!0}).then(t=>{t.isConfirmed&&g.post("/pstos/delete",{id:s})})},O=async()=>{pstos.value={}};let d=1;const V=async s=>{g.visit("/pstos?page="+s,{preserveState:!0}),d=s};return(s,t)=>{const f=a("v-text-field"),b=a("v-col"),h=a("v-btn"),T=a("v-row"),B=a("v-pagination"),P=a("v-table"),A=a("v-card");return r(),D(z,{title:"Dashboard"},{header:l(()=>[F]),default:l(()=>[e("div",E,[e("div",M,[e("div",Y,[n(T,{style:{"margin-bottom":"-30px"}},{default:l(()=>[n(b,null,{default:l(()=>[e("div",q,[n(f,{loading:s.loading,"append-inner-icon":"mdi-magnify",density:"compact",label:"Search",variant:"solo","hide-details":"","single-line":"",modelValue:_.value,"onUpdate:modelValue":t[0]||(t[0]=o=>_.value=o),"onClick:appendInner":s.onClick},null,8,["loading","modelValue","onClick:appendInner"])])]),_:1}),n(b,null,{default:l(()=>[e("div",G,[n(h,{onClick:t[1]||(t[1]=o=>v(!0,"Add",null)),size:"small","prepend-icon":"mdi-plus",color:"green"},{default:l(()=>[m(" PSTO ")]),_:1})])]),_:1})]),_:1}),n(A,{class:"m-3"},{default:l(()=>[n(P,null,{bottom:l(()=>[e("div",Q,[e("span",W,[m(" Showing "+u(i.pstos.from)+" to "+u(i.pstos.to)+" out of ",1),e("b",null,u(i.pstos.total)+" records",1)]),e("div",X,[n(B,{modelValue:k(d),"onUpdate:modelValue":t[2]||(t[2]=o=>L(d)?d.value=o:d=o),length:i.pstos.last_page,circle:"",onClick:t[3]||(t[3]=o=>V(k(d)))},null,8,["modelValue","length"])])])]),default:l(()=>[H,e("tbody",null,[(r(!0),p(C,null,R(i.pstos.data,(o,I)=>(r(),p("tr",{key:o.id,class:"hover:bg-gray-200"},[o?(r(),p(C,{key:0},[e("td",null,u(I+1),1),e("td",null,u(o.psto_name),1),e("td",J,[n(h,{class:"mr-3",onClick:N=>S(o.id),size:"small","prepend-icon":"mdi-delete",color:"red"},{default:l(()=>[m(" Delete ")]),_:2},1032,["onClick"]),n(h,{onClick:N=>v(!0,"Update",o),size:"small","prepend-icon":"mdi-update",color:"primary"},{default:l(()=>[m(" Update ")]),_:2},1032,["onClick"])])],64)):(r(),p("td",K," No data at the moment"))]))),128))])]),_:1})]),_:1})])])]),n(U,{value:x.value,psto:w.value,action:y.value,onInput:v,onReloadPSTOs:O},null,8,["value","psto","action"])]),_:1})}}};export{ne as default};
