import{_ as N}from"./dost-logo-B4Huuyod.js";import{j as n,o,e as b,b as s,u as f,a,g as i,t as u,f as g,w as e,F as v,Z as j,c as x,h as C,m as h,O as S}from"./app-ijq8Ikri.js";import{A as B}from"./aos-BQwzxYKl.js";const T={"data-aos":"fade-down","data-aos-duration":"500","data-aos-delay":"500",style:{"backdrop-filter":"blur(2px)"},class:"bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600"},V={class:"max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4"},D={href:"/",class:"flex items-center space-x-3 rtl:space-x-reverse"},z=a("img",{src:N,class:"h-8",alt:"DOST Logo"},null,-1),A={class:"self-center text-2xl font-semibold whitespace-nowrap"},F={key:0},L={class:"w-full border bg-blue"},$={style:{height:"150px"},class:"mx-5 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"},E={class:"mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white"},Z={__name:"PSTOs",props:{region_id:Number,region:Object,service_id:Number,unit_id:Number,unit:Object,sub_unit_id:Number,sub_unit:Object,pstos:Object},setup(t){B.init();const p=async(_,c,l,d,r)=>{S.get("/services/csf?region_id="+_+"&service_id="+c+"&unit_id="+l+"&sub_unit_id="+d+"&psto_id="+r)},y=async _=>{window.history.back()};return(_,c)=>{const l=n("v-card-title"),d=n("v-col"),r=n("v-row"),k=n("v-icon"),w=n("v-btn"),O=n("v-container");return o(),b(v,null,[s(f(j),{title:"Service Units"}),a("nav",T,[a("div",V,[a("a",D,[z,a("span",A,[i("DOST "),t.region?(o(),b("span",F,u(t.region.code),1)):g("",!0),i(" Customer Relation Management System")])])])]),s(O,{"fill-height":""},{default:e(()=>[s(r,{class:"mx-15",style:{"margin-top":"100px"}},{default:e(()=>[s(d,null,{default:e(()=>[a("div",L,[t.sub_unit?(o(),x(l,{key:0,class:"text-center text-uppercase"},{default:e(()=>[i(u(t.sub_unit.sub_unit_name),1)]),_:1})):g("",!0),t.unit?(o(),x(l,{key:1,class:"text-center text-uppercase"},{default:e(()=>[i(u(t.unit.unit_name),1)]),_:1})):g("",!0)])]),_:1})]),_:1}),s(r,{class:"mx-15 mt-5",align:"center",justify:"center"},{default:e(()=>[(o(!0),b(v,null,C(t.pstos,m=>(o(),x(d,{cols:"12",sm:"4",md:"4",lg:"4"},{default:e(()=>[s(f(h),{onClick:M=>p(t.region_id,t.service_id,t.unit_id,t.sub_unit_id,m.id)},{default:e(()=>[a("div",$,[s(k,{color:"red",size:"x-large",class:"p-3"},{default:e(()=>[i("mdi-map-marker-outline")]),_:1}),a("h5",E,u(m.psto_name),1)])]),_:2},1032,["onClick"])]),_:2},1024))),256))]),_:1}),s(r,null,{default:e(()=>[s(f(h),{onClick:c[0]||(c[0]=m=>y())},{default:e(()=>[s(w,{"prepend-icon":"mdi-arrow-left",style:{"margin-left":"120px"}},{default:e(()=>[i("Back")]),_:1})]),_:1})]),_:1})]),_:1})],64)}}};export{Z as default};