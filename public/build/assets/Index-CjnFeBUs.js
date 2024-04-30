import{A as b}from"./AppLayout-CtRSdcwP.js";import{_ as p}from"./Modal.vue_vue_type_script_setup_true_lang-DkMqlD1o.js";import{d as l,i as f,j as n,o as x,c as y,w as t,b as s,u as o,m as d,a as e,g as i,O as w}from"./app-D8es4Nph.js";import"./sweetalert2.all-CapNuMTQ.js";import"./dost-logo-B4Huuyod.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const k=e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," Libraries/Settings ",-1),v={class:"py-5 ml-5 mr-5",style:{width:"250px"}},z={class:"max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"},A=e("a",{href:"#"},[e("h5",{class:"mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white"}," Accounts ")],-1),S={class:"py-5 ml-5 mr-5",style:{width:"250px"}},O={class:"max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"},P=e("a",{href:"#"},[e("h5",{class:"mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white"}," Assignatorees ")],-1),T={class:"py-5 ml-5 mr-5",style:{width:"250px"}},B={class:"max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"},I=e("a",{href:"#"},[e("h5",{class:"mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white"}," Services Units ")],-1),N={class:"py-5 ml-5 mr-5",style:{width:"250px"}},U={class:"max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"},V=e("a",{href:"#"},[e("h5",{class:"mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white"}," Regions ")],-1),j={class:"py-5 ml-5 mr-5",style:{width:"250px"}},C={class:"max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"},L=e("a",{href:"#"},[e("h5",{class:"mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white"}," PSTOs ")],-1),R={class:"py-5 ml-5 mr-5",style:{width:"250px"}},D={class:"max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"},M=e("a",{href:"#"},[e("h5",{class:"mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white"}," Unit PSTOs ")],-1),$={class:"py-5 ml-5 mr-5",style:{width:"250px"}},q={class:"max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"},E=e("a",{href:"#"},[e("h5",{class:"mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white"}," Sub Unit PSTOs ")],-1),Y={__name:"Index",props:{services:Object},setup(F){const h=l(!1),g=l(null);l({});const c=l({}),m=l("");f(()=>m.value,a=>{w.get("/accounts",{search:a},{preserveState:!0})});const _=async()=>{c.value={}};return(a,G)=>{const r=n("v-icon"),u=n("v-row");return x(),y(b,{title:"Dashboard"},{header:t(()=>[k]),default:t(()=>[s(u,{class:"mx-15 mt-5"},{default:t(()=>[s(o(d),{href:a.route("accounts")},{default:t(()=>[e("div",v,[e("div",z,[s(r,{size:"x-large",class:"p-3"},{default:t(()=>[i("mdi-account")]),_:1}),A])])]),_:1},8,["href"]),s(o(d),{href:a.route("assignatorees")},{default:t(()=>[e("div",S,[e("div",O,[s(r,{size:"x-large",class:"p-3"},{default:t(()=>[i("mdi-account-multiple")]),_:1}),P])])]),_:1},8,["href"]),s(o(d),{href:a.route("services_units")},{default:t(()=>[e("div",T,[e("div",B,[s(r,{size:"x-large",class:"p-3"},{default:t(()=>[i("mdi-domain")]),_:1}),I])])]),_:1},8,["href"]),s(o(d),{href:a.route("regions")},{default:t(()=>[e("div",N,[e("div",U,[s(r,{size:"x-large",class:"p-3"},{default:t(()=>[i("mdi-map-marker")]),_:1}),V])])]),_:1},8,["href"]),s(o(d),{href:a.route("pstos")},{default:t(()=>[e("div",j,[e("div",C,[s(r,{size:"x-large",class:"p-3"},{default:t(()=>[i("mdi-home-map-marker")]),_:1}),L])])]),_:1},8,["href"]),s(o(d),{href:a.route("unitPstos")},{default:t(()=>[e("div",R,[e("div",D,[s(r,{size:"x-large",class:"p-3"},{default:t(()=>[i("mdi-map-marker-outline")]),_:1}),M])])]),_:1},8,["href"]),s(o(d),{href:a.route("subunitPstos")},{default:t(()=>[e("div",$,[e("div",q,[s(r,{size:"x-large",class:"p-3"},{default:t(()=>[i("mdi-map-marker-radius")]),_:1}),E])])]),_:1},8,["href"])]),_:1}),s(p,{value:h.value,account:c.value,regions:a.regions,action:g.value,onInput:a.showAccountModal,onReloadAccounts:_},null,8,["value","account","regions","action","onInput"])]),_:1})}}};export{Y as default};
