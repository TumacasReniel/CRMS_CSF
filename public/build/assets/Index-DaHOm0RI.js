import{A as V}from"./AppLayout-CtRSdcwP.js";import{_ as S}from"./Modal.vue_vue_type_script_setup_true_lang-CD72k3FJ.js";import{d as c,i as A,j as s,o as r,c as B,w as o,a as e,b as n,g,t as a,e as u,h as I,F as b,O}from"./app-D8es4Nph.js";import"./sweetalert2.all-CapNuMTQ.js";import"./dost-logo-B4Huuyod.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const U=e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," Regions ",-1),$={class:"py-12"},j={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},z={class:"bg-white overflow-hidden shadow-xl sm:rounded-lg"},D={class:"w-full m-5 text-right"},F={class:"text-right m-5"},L=e("thead",null,[e("tr",null,[e("th",{class:"text-left"}," # "),e("th",{class:"text-left"}," Name "),e("th",{class:"text-left"}," Code "),e("th",{class:"text-left"}," Short Name "),e("th",{class:"text-left"}," Order "),e("th",{class:"text-center"}," Actions ")])],-1),E={class:"text-center"},M={key:1,span:""},T={class:"m-2"},q={style:{color:"gray"}},G={class:"text-center"},Y={__name:"Index",props:{regions:Object},setup(d){const h=c(!1),v=c(null);c({});const f=c({}),m=c("");A(()=>m.value,l=>{O.get("/regions",{search:l},{preserveState:!0})});const _=async(l,i,p)=>{h.value=l,v.value=i,f.value=p};return(l,i)=>{const p=s("v-text-field"),x=s("v-col"),y=s("v-btn"),k=s("v-row"),w=s("v-pagination"),C=s("v-table"),R=s("v-card");return r(),B(V,{title:"Dashboard"},{header:o(()=>[U]),default:o(()=>[e("div",$,[e("div",j,[e("div",z,[n(k,{style:{"margin-bottom":"-30px"}},{default:o(()=>[n(x,null,{default:o(()=>[e("div",D,[n(p,{loading:l.loading,"append-inner-icon":"mdi-magnify",density:"compact",label:"Search",variant:"solo","hide-details":"","single-line":"",modelValue:m.value,"onUpdate:modelValue":i[0]||(i[0]=t=>m.value=t),"onClick:appendInner":l.onClick},null,8,["loading","modelValue","onClick:appendInner"])])]),_:1}),n(x,null,{default:o(()=>[e("div",F,[n(y,{onClick:i[1]||(i[1]=t=>_(!0,"Add",null)),size:"small","prepend-icon":"mdi-plus",color:"green"},{default:o(()=>[g(" Region ")]),_:1})])]),_:1})]),_:1}),n(R,{class:"m-3"},{default:o(()=>[n(C,null,{bottom:o(()=>[e("div",T,[e("span",q,[g(" Showing "+a(d.regions.from)+" to "+a(d.regions.to)+" out of ",1),e("b",null,a(d.regions.total)+" records",1)]),e("div",G,[n(w,{length:d.regions.last_page,circle:"",onClick:l.getRegions},null,8,["length","onClick"])])])]),default:o(()=>[L,e("tbody",null,[(r(!0),u(b,null,I(d.regions.data,(t,N)=>(r(),u("tr",{key:t.id,class:"hover:bg-gray-200"},[t?(r(),u(b,{key:0},[e("td",null,a(N+1),1),e("td",null,a(t.name),1),e("td",null,a(t.code),1),e("td",null,a(t.short_name),1),e("td",null,a(t.order),1),e("td",E,[n(y,{onClick:H=>_(!0,"Update",t),size:"small","prepend-icon":"mdi-update",color:"primary"},{default:o(()=>[g(" Update ")]),_:2},1032,["onClick"])])],64)):(r(),u("td",M," No data at the moment"))]))),128))])]),_:1})]),_:1})])])]),n(S,{value:h.value,region:f.value,action:v.value,onInput:_,onReloadRegions:l.reloadRegions},null,8,["value","region","action","onReloadRegions"])]),_:1})}}};export{Y as default};
