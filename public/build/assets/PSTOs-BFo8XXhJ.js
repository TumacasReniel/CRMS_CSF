import{_ as y}from"./dost-logo-B4Huuyod.js";import{j as a,o as d,e as f,b as e,u as _,w as t,F as g,M as k,Z as N,a as u,g as m,h as S,c as B,m as b,t as O,O as T}from"./app-GUstiQiu.js";import{A as C}from"./aos-B46zqYBm.js";const j=k('<nav data-aos="fade-down" data-aos-duration="500" data-aos-delay="500" style="backdrop-filter:blur(2px);" class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600"><div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4"><a href="/" class="flex items-center space-x-3 rtl:space-x-reverse"><img src="'+y+'" class="h-8" alt="DOST Logo"><span class="self-center text-2xl font-semibold whitespace-nowrap">Department of Science and Technology</span></a></div></nav>',1),V={class:"w-full border bg-blue"},D={style:{height:"150px"},class:"mx-5 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"},U={class:"mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white"},P={__name:"PSTOs",props:{region_id:Number,service_id:Number,unit_id:Number,sub_unit_id:Number,pstos:Object},setup(s){C.init();const p=async(i,r,c,n,o)=>{T.get("/services/csf?region_id="+i+"&service_id="+r+"&unit_id="+c+"&sub_unit_id="+n+"&psto_id="+o)},x=async i=>{window.history.back()};return(i,r)=>{const c=a("v-card-title"),n=a("v-col"),o=a("v-row"),v=a("v-icon"),h=a("v-btn"),w=a("v-container");return d(),f(g,null,[e(_(N),{title:"Service Units"}),j,e(w,{"fill-height":""},{default:t(()=>[e(o,{class:"mx-15",style:{"margin-top":"100px"}},{default:t(()=>[e(n,null,{default:t(()=>[u("div",V,[e(c,{class:"text-center"},{default:t(()=>[m("SUB-UNIT PSTOs")]),_:1})])]),_:1})]),_:1}),e(o,{class:"mx-15 mt-5",align:"center",justify:"center"},{default:t(()=>[(d(!0),f(g,null,S(s.pstos,l=>(d(),B(n,{cols:"12",sm:"4",md:"4",lg:"4"},{default:t(()=>[e(_(b),{onClick:z=>p(s.region_id,s.service_id,s.unit_id,s.sub_unit_id,l.id)},{default:t(()=>[u("div",D,[e(v,{color:"red",size:"x-large",class:"p-3"},{default:t(()=>[m("mdi-map-marker-outline")]),_:1}),u("h5",U,O(l.psto_name),1)])]),_:2},1032,["onClick"])]),_:2},1024))),256))]),_:1}),e(o,null,{default:t(()=>[e(_(b),{onClick:r[0]||(r[0]=l=>x())},{default:t(()=>[e(h,{"prepend-icon":"mdi-arrow-left",style:{"margin-left":"120px"}},{default:t(()=>[m("Back")]),_:1})]),_:1})]),_:1})]),_:1})],64)}}};export{P as default};
