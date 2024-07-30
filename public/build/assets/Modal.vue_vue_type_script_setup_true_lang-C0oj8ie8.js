import{B as G,i as _,x as H,d as v,j as d,o as u,c as r,w as n,b as o,a as q,t as I,f as m,g as C,J as O,O as S}from"./app-GrTQDDTX.js";const K={class:"text-h5"},L={style:{"text-align":"end"}},W=G({__name:"Modal",props:{data:{type:Object,default:null},account:{type:Object,default:null},value:{type:Boolean,default:!1},services:{type:Object,default:null},action:{type:String}},emits:["reloadAccounts","input"],setup(y,{emit:B}){const f=B,c=y;_(()=>c.account,l=>{l&&l.region&&l.account_type&&l.region&&l.service&&l.unit&&l.psto&&c.action=="Update"&&(e.id=l.id,e.name=l.name,e.designation=l.designation,e.email=l.email,e.selected_account_type=l.account_type,e.selected_region=l.region.id,e.selected_service=l.service.id,e.selected_unit=l.unit.id,e.selected_psto=l.psto.id)});const e=H({id:null,name:null,email:null,password:null,designation:"",selected_region:[],selected_account_type:null,selected_service:null,selected_unit:null,selected_psto:null}),V=v(!1);_(()=>c.value,l=>{V.value=l});const b=v(""),x=v(),k=v();_(()=>c.action,l=>{b.value=l}),_(()=>e.selected_service,l=>{l&&(x.value=N(l))}),_(()=>e.selected_unit,l=>{l&&(k.value=j(l))});const N=l=>{O.get("/service/units",{params:{option:"units",code:l}}).then(t=>{x.value=t.data}).catch(t=>console.log(t))},j=l=>{O.get("/service/pstos",{params:{option:"pstos",code:l,region_id:e.selected_region}}).then(t=>{k.value=t.data}).catch(t=>console.log(t))},w=v(!1),P=async()=>{b.value=="Add"?S.post("/accounts/add",e):b.value=="Update"&&S.post("/accounts/update",e),f("input",!1),f("reloadAccounts")},D=l=>{f("input",l),f("reloadAccounts")};return(l,t)=>{const T=d("v-card-title"),g=d("v-text-field"),i=d("v-col"),s=d("v-row"),$=d("v-checkbox"),p=d("v-select"),E=d("v-card-text"),J=d("v-spacer"),M=d("v-divider"),U=d("v-icon"),A=d("v-btn"),R=d("v-card-action"),z=d("v-card"),F=d("v-dialog");return u(),r(F,{modelValue:V.value,"onUpdate:modelValue":t[12]||(t[12]=a=>V.value=a),width:"600",scrollable:"",persistent:""},{default:n(()=>[o(z,null,{default:n(()=>[o(T,{class:"bg-indigo"},{default:n(()=>[q("span",K,I(c.action)+" Account",1)]),_:1}),o(E,null,{default:n(()=>[o(s,{style:{"margin-bottom":"-30px"}},{default:n(()=>[o(i,{cols:"12"},{default:n(()=>[o(g,{"prepend-icon":"mdi-account",label:"Name*",modelValue:e.name,"onUpdate:modelValue":t[0]||(t[0]=a=>e.name=a),variant:"outlined"},null,8,["modelValue"])]),_:1})]),_:1}),o(s,{style:{"margin-bottom":"-30px"}},{default:n(()=>[o(i,{cols:"12"},{default:n(()=>[o(g,{"prepend-icon":"mdi-email",label:"Email*",modelValue:e.email,"onUpdate:modelValue":t[1]||(t[1]=a=>e.email=a),variant:"outlined",type:"email",required:""},null,8,["modelValue"])]),_:1})]),_:1}),o(s,{style:{"margin-bottom":"-30px"}},{default:n(()=>[o(i,{cols:"9"},{default:n(()=>[c.action=="Add"?(u(),r(g,{key:0,"prepend-icon":"mdi-lock",label:"Password*",modelValue:e.password,"onUpdate:modelValue":t[2]||(t[2]=a=>e.password=a),variant:"outlined",type:w.value?"text":"password",required:""},null,8,["modelValue","type"])):m("",!0)]),_:1}),o(i,{cols:"3"},{default:n(()=>[c.action=="Add"?(u(),r($,{key:0,modelValue:w.value,"onUpdate:modelValue":t[3]||(t[3]=a=>w.value=a),label:"Show"},null,8,["modelValue"])):m("",!0)]),_:1})]),_:1}),o(s,{style:{"margin-bottom":"-30px"}},{default:n(()=>[o(i,{cols:"12"},{default:n(()=>[o(p,{"prepend-icon":"mdi-map-marker",label:"Region*",modelValue:e.selected_region,"onUpdate:modelValue":t[4]||(t[4]=a=>e.selected_region=a),variant:"outlined",items:y.data.regions,"item-title":"name","item-value":"id",required:""},null,8,["modelValue","items"])]),_:1})]),_:1}),o(s,{style:{"margin-bottom":"-30px"}},{default:n(()=>[o(i,{cols:"12"},{default:n(()=>[o(p,{"prepend-icon":"mdi-account-circle",label:"Account_type*",modelValue:e.selected_account_type,"onUpdate:modelValue":t[5]||(t[5]=a=>e.selected_account_type=a),variant:"outlined",items:["user","admin","planning"],"item-title":"name",clearable:"",required:""},null,8,["modelValue"])]),_:1})]),_:1}),o(s,{style:{"margin-bottom":"-30px"}},{default:n(()=>[o(i,{cols:"12"},{default:n(()=>[e.selected_account_type=="user"?(u(),r(g,{key:0,"prepend-icon":"mdi-account",label:"Designation*",modelValue:e.designation,"onUpdate:modelValue":t[6]||(t[6]=a=>e.designation=a),variant:"outlined"},null,8,["modelValue"])):m("",!0)]),_:1})]),_:1}),o(s,{style:{"margin-bottom":"-30px"}},{default:n(()=>[o(i,{cols:"12"},{default:n(()=>[e.selected_account_type=="user"?(u(),r(p,{key:0,"prepend-icon":"mdi-map-marker",label:"Service*",modelValue:e.selected_service,"onUpdate:modelValue":t[7]||(t[7]=a=>e.selected_service=a),variant:"outlined",items:y.data.services,"item-title":"services_name","item-value":"id",clearable:"",required:""},null,8,["modelValue","items"])):m("",!0)]),_:1})]),_:1}),o(s,{style:{"margin-bottom":"-30px"}},{default:n(()=>[o(i,{cols:"12"},{default:n(()=>[e.selected_account_type=="user"||e.selected_service?(u(),r(p,{key:0,"prepend-icon":"mdi-map-marker",label:"Unit*",modelValue:e.selected_unit,"onUpdate:modelValue":t[8]||(t[8]=a=>e.selected_unit=a),variant:"outlined",items:x.value,"item-title":"unit_name","item-value":"id",clearable:"",required:""},null,8,["modelValue","items"])):m("",!0)]),_:1})]),_:1}),o(s,{style:{"margin-bottom":"-30px"}},{default:n(()=>[o(i,{cols:"12"},{default:n(()=>[e.selected_account_type=="user"||e.selected_unit&&e.selected_region?(u(),r(p,{key:0,"prepend-icon":"mdi-map-marker",label:"PSTO",modelValue:e.selected_psto,"onUpdate:modelValue":t[9]||(t[9]=a=>e.selected_psto=a),variant:"outlined",items:k.value,"item-title":"psto_name","item-value":"id",clearable:"",required:""},null,8,["modelValue","items"])):m("",!0)]),_:1})]),_:1})]),_:1}),o(J),o(R,null,{default:n(()=>[o(M),q("div",L,[o(A,{class:"ma-2",color:"blue-grey-lighten-2",onClick:t[10]||(t[10]=a=>D(!1))},{default:n(()=>[o(U,{start:"",icon:"mdi-cancel"}),C(" Cancel ")]),_:1}),o(A,{class:"ma-2",color:"green-darken-1",type:"button",onClick:t[11]||(t[11]=a=>P())},{default:n(()=>[C(" Save "),o(U,{end:"",icon:"mdi-check"})]),_:1})])]),_:1})]),_:1})]),_:1},8,["modelValue"])}}});export{W as _};
