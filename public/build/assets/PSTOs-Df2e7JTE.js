import{_ as O}from"./dost-logo-B4Huuyod.js";import{j as s,o as r,e as g,b as t,u as _,w as e,F as x,M as S,Z as j,a as m,c as b,g as l,t as f,f as p,h as B,m as v,O as C}from"./app-DeDXB03R.js";import{A as V}from"./aos-Dg8pv-a9.js";const T=S('<nav data-aos="fade-down" data-aos-duration="500" data-aos-delay="500" style="backdrop-filter:blur(2px);" class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600"><div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4"><a href="/" class="flex items-center space-x-3 rtl:space-x-reverse"><img src="'+O+'" class="h-8" alt="DOST Logo"><span class="self-center text-2xl font-semibold whitespace-nowrap">Department of Science and Technology</span></a></div></nav>',1),D={class:"w-full border bg-blue"},z={style:{height:"150px"},class:"mx-5 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"},A={class:"mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white"},M={__name:"PSTOs",props:{region_id:Number,service_id:Number,unit_id:Number,unit:Object,sub_unit_id:Number,sub_unit:Object,pstos:Object},setup(a){V.init();const y=async(d,i,o,c,n)=>{C.get("/services/csf?region_id="+d+"&service_id="+i+"&unit_id="+o+"&sub_unit_id="+c+"&psto_id="+n)},h=async d=>{window.history.back()};return(d,i)=>{const o=s("v-card-title"),c=s("v-col"),n=s("v-row"),w=s("v-icon"),k=s("v-btn"),N=s("v-container");return r(),g(x,null,[t(_(j),{title:"Service Units"}),T,t(N,{"fill-height":""},{default:e(()=>[t(n,{class:"mx-15",style:{"margin-top":"100px"}},{default:e(()=>[t(c,null,{default:e(()=>[m("div",D,[a.sub_unit?(r(),b(o,{key:0,class:"text-center text-uppercase"},{default:e(()=>[l(f(a.sub_unit.sub_unit_name),1)]),_:1})):p("",!0),a.unit?(r(),b(o,{key:1,class:"text-center text-uppercase"},{default:e(()=>[l(f(a.unit.unit_name),1)]),_:1})):p("",!0)])]),_:1})]),_:1}),t(n,{class:"mx-15 mt-5",align:"center",justify:"center"},{default:e(()=>[(r(!0),g(x,null,B(a.pstos,u=>(r(),b(c,{cols:"12",sm:"4",md:"4",lg:"4"},{default:e(()=>[t(_(v),{onClick:F=>y(a.region_id,a.service_id,a.unit_id,a.sub_unit_id,u.id)},{default:e(()=>[m("div",z,[t(w,{color:"red",size:"x-large",class:"p-3"},{default:e(()=>[l("mdi-map-marker-outline")]),_:1}),m("h5",A,f(u.psto_name),1)])]),_:2},1032,["onClick"])]),_:2},1024))),256))]),_:1}),t(n,null,{default:e(()=>[t(_(v),{onClick:i[0]||(i[0]=u=>h())},{default:e(()=>[t(k,{"prepend-icon":"mdi-arrow-left",style:{"margin-left":"120px"}},{default:e(()=>[l("Back")]),_:1})]),_:1})]),_:1})]),_:1})],64)}}};export{M as default};