import{_ as S}from"./dost-logo-B4Huuyod.js";import{j as n,o as l,e as m,b as e,u,a,g as r,t as f,f as O,w as t,F as b,Z as j,h as C,c as N,m as p,z as B,A as U,O as I}from"./app-DLGPIspb.js";import{A as V}from"./aos-CWF5IHpI.js";import{_ as z}from"./_plugin-vue_export-helper-DlAUqK2U.js";const A=s=>(B("data-v-ea88acb8"),s=s(),U(),s),D={"data-aos":"fade-down","data-aos-duration":"500","data-aos-delay":"500",style:{"backdrop-filter":"blur(2px)"},class:"bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600"},T={class:"max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4"},F={href:"/",class:"flex items-center space-x-3 rtl:space-x-reverse"},L=A(()=>a("img",{src:S,class:"h-8",alt:"DOST Logo"},null,-1)),$={class:"self-center text-2xl font-semibold whitespace-nowrap"},E={key:0},M={class:"w-full border bg-blue"},R={style:{height:"150px"},class:"card mx-5 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"},Z={class:"mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white"},q={__name:"Units",props:{service_units:Object,region_id:Number,region:Object,service_id:Number,service:Object,sub_units:Object},setup(s){V.init();const v=async(i,o,c)=>{x(o,c,i)},x=async(i,o,c)=>{I.get("/services/csf/unit/sub-units?region_id="+i+"&service_id="+o+"&unit_id="+c)},h=async()=>{window.history.back()};return(i,o)=>{const c=n("v-card-title"),g=n("v-col"),d=n("v-row"),w=n("v-icon"),y=n("v-btn"),k=n("v-container");return l(),m(b,null,[e(u(j),{title:"Service Units"}),a("nav",D,[a("div",T,[a("a",F,[L,a("span",$,[r("DOST "),s.region?(l(),m("span",E,f(s.region.code),1)):O("",!0),r(" Customer Relation Management System")])])])]),e(k,{"fill-height":""},{default:t(()=>[e(d,{class:"mx-15",style:{"margin-top":"100px"}},{default:t(()=>[e(g,null,{default:t(()=>[a("div",M,[e(c,{class:"text-center"},{default:t(()=>[r(f(s.service.services_name),1)]),_:1})])]),_:1})]),_:1}),e(d,{class:"mx-15 mt-5",align:"center",justify:"center"},{default:t(()=>[(l(!0),m(b,null,C(s.service_units,_=>(l(),N(g,{cols:"12",sm:"4",md:"4",lg:"4"},{default:t(()=>[e(u(p),{onClick:G=>v(_.id,s.region_id,s.service_id)},{default:t(()=>[a("div",R,[e(w,{color:"blue",size:"x-large",class:"p-3"},{default:t(()=>[r("mdi-check-circle")]),_:1}),a("h5",Z,f(_.unit_name),1)])]),_:2},1032,["onClick"])]),_:2},1024))),256))]),_:1}),e(d,null,{default:t(()=>[e(u(p),{onClick:o[0]||(o[0]=_=>h())},{default:t(()=>[e(y,{"prepend-icon":"mdi-arrow-left",style:{"margin-left":"120px"}},{default:t(()=>[r("Back")]),_:1})]),_:1})]),_:1})]),_:1})],64)}}},Q=z(q,[["__scopeId","data-v-ea88acb8"]]);export{Q as default};