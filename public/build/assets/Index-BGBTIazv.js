import"./vue-multiselect.css_vue_type_style_index_0_src_true_lang-BpRgDKxS.js";import{A as j}from"./AppLayout-B0iAQFW7.js";import{_ as B}from"./Modal.vue_vue_type_script_setup_true_lang-D8dFyxM3.js";import{x as R,d as v,j as _,o as r,c as C,w as o,a as e,b as n,g as u,f as b,e as p,h as S,F as f,t as h,O as g,z as D,A as X}from"./app-BL0d-hXa.js";import{_ as L}from"./_plugin-vue_export-helper-DlAUqK2U.js";import"./dost-logo-B4Huuyod.js";const A=t=>(D("data-v-5fe8a75e"),t=t(),X(),t),M=A(()=>e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," Services Units ",-1)),W={class:"py-5"},E={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},J={class:"bg-white overflow-hidden shadow-xl sm:rounded-lg"},K={class:"w-full"},Q=A(()=>e("thead",{class:"font-bold text-center"},[e("th",{class:"pb-4 pt-6 px-6",colspan:"2"},"Services Units"),e("th",{class:"pb-4 pt-6 px-6"},"Actions")],-1)),T={class:"border border-solid bg-blue-100"},q={class:"m5 p-5 border border-solid font-black",colspan:"2"},G={key:0,class:"m5 p-5 border border-solid font-black text-center",colspan:"3"},H={class:"text-center p-2 border border-solid hover:bg-gray-100 focus-within:bg-gray-100"},Y={class:"p-2 mr-2 border border-solid hover:bg-gray-100 focus-within:bg-gray-100"},Z={class:"text-center px-4 py-2 p-2 mr-2 border border-solid"},ee={__name:"Index",props:{service_units:Object,sub_units:Object,user:Object},setup(t){const z=t,l=R({service_id:null,unit_id:null}),I=async(a,s)=>{l.service_id=a,l.unit_id=s,g.get("/csi",l,{preserveState:!0})},$=async()=>{l.form_type="all units",g.get("/csi/all-units",l,{preserveState:!0})},y=v(!1),w=v(""),x=v({}),N=async(a,s)=>{l.service_id=a,l.unit_id=s,g.get("/csi/view",l,{preserveState:!0})},m=async(a,s,c)=>{y.value=a,w.value=s,c&&(x.value=c)},U=()=>{window.open("https://drive.google.com/file/d/1s7hgXu2_3znCrcKrXX0PWJUQfwb7SMWU/view?usp=sharing","_blank")};return(a,s)=>{const c=_("v-btn"),k=_("v-col"),V=_("v-row"),F=_("v-divider"),O=_("v-card");return r(),C(j,{title:"Dashboard"},{header:o(()=>[M]),default:o(()=>[e("div",W,[e("div",E,[e("div",J,[n(O,null,{default:o(()=>[n(V,null,{default:o(()=>[t.user.account_type=="admin"?(r(),C(k,{key:0,class:"text-left m-5 mb-1"},{default:o(()=>[n(c,{onClick:s[0]||(s[0]=i=>m(!0,"add_new_service",null)),"prepend-icon":"mdi-plus",color:"primary",size:"small"},{default:o(()=>[u(" Add New Service ")]),_:1})]),_:1})):b("",!0),n(k,{class:"text-right m-5 mb-1"},{default:o(()=>[n(c,{onClick:s[1]||(s[1]=i=>$()),"prepend-icon":"mdi-file",color:"yellow",style:{"margin-right":"100px"},size:"small"},{default:o(()=>[u(" All Unit Ratings ")]),_:1}),n(c,{"prepend-icon":"mdi-printer",class:"mr-5",color:"success",size:"small",onClick:s[2]||(s[2]=i=>U())},{default:o(()=>[u("CSF Form(manual) ")]),_:1})]),_:1})]),_:1}),e("table",K,[Q,t.service_units?(r(!0),p(f,{key:0},S(t.service_units.data,(i,te)=>(r(),p(f,{key:i.id},[e("tr",T,[e("td",q,h(i.services_name),1),t.user.account_type=="admin"?(r(),p("td",G,[n(c,{onClick:d=>m(!0,"add_new_unit",i),"prepend-icon":"mdi-plus",color:"primary",size:"small"},{default:o(()=>[u(" Add New Unit ")]),_:2},1032,["onClick"])])):b("",!0)]),(r(!0),p(f,null,S(i.units,(d,se)=>(r(),p("tr",{key:d.id},[e("td",H,h(d.id),1),e("td",Y,h(d.unit_name),1),e("td",Z,[e("div",null,[n(c,{"prepend-icon":"mdi-eye",class:"mr-3",size:"small",onClick:P=>N(i.id,d.id),disabled:t.user.account_type=="user"&&t.user.unit_id!=d.id},{default:o(()=>[u(" View ")]),_:2},1032,["onClick","disabled"]),n(c,{onClick:P=>I(i.id,d.id),"prepend-icon":"mdi-file",color:"yellow",size:"small",disabled:t.user.account_type=="user"&&t.user.unit_id!=d.id},{default:o(()=>[u(" Rating ")]),_:2},1032,["onClick","disabled"])])])]))),128))],64))),128)):b("",!0)]),n(F,{thickness:1,class:"border-opacity-100 mb-5"})]),_:1})])])]),n(B,{value:y.value,action_clicked:w.value,account:a.account,selected_service:x.value,data:z,onInput:m,onReloadAccounts:a.reloadAccounts},null,8,["value","action_clicked","account","selected_service","data","onReloadAccounts"])]),_:1})}}},de=L(ee,[["__scopeId","data-v-5fe8a75e"]]);export{de as default};
