import{z as j,i as _,x as $,d as f,j as n,o as q,c as z,w as a,b as e,a as x,t as M,g as b,O as V}from"./app-D8es4Nph.js";const T={class:"text-h5"},h={style:{"text-align":"end"}},F=j({__name:"Modal",props:{assignatoree:{type:Object,default:null},value:{type:Boolean,default:!1},action:{type:String}},emits:["reloadAssignatorees","input"],setup(y,{emit:k}){const i=k,d=y;_(()=>d.assignatoree,o=>{o&&(t.id=o.id,t.name=o.name,t.designation=o.designation)});const t=$({id:null,name:null,designation:null}),c=f(!1);_(()=>d.value,o=>{c.value=o});const r=f("");_(()=>d.action,o=>{r.value=o});const w=async()=>{r.value=="Add"?V.post("/assignatorees/add",t):r.value=="Update"&&V.post("/assignatorees/update",t),i("input",!1),i("reloadAssignatorees"),t.id="",t.name="",t.designation=""},A=o=>{i("input",o),i("reloadAssignatorees"),t.id="",t.name="",t.designation=""};return(o,s)=>{const C=n("v-card-title"),m=n("v-text-field"),u=n("v-col"),p=n("v-row"),B=n("v-card-text"),N=n("v-spacer"),U=n("v-divider"),v=n("v-icon"),g=n("v-btn"),D=n("v-card-action"),O=n("v-card"),S=n("v-dialog");return q(),z(S,{modelValue:c.value,"onUpdate:modelValue":s[4]||(s[4]=l=>c.value=l),width:"600",scrollable:"",persistent:""},{default:a(()=>[e(O,null,{default:a(()=>[e(C,{class:"bg-indigo"},{default:a(()=>[x("span",T,M(d.action)+" Assignatoree",1)]),_:1}),e(B,null,{default:a(()=>[e(p,{style:{"margin-bottom":"-30px"}},{default:a(()=>[e(u,{cols:"12"},{default:a(()=>[e(m,{"prepend-icon":"mdi-account",label:"Name*",modelValue:t.name,"onUpdate:modelValue":s[0]||(s[0]=l=>t.name=l),variant:"outlined"},null,8,["modelValue"])]),_:1})]),_:1}),e(p,{style:{"margin-bottom":"-30px"}},{default:a(()=>[e(u,{cols:"12"},{default:a(()=>[e(m,{"prepend-icon":"mdi-account",label:"Designation*",modelValue:t.designation,"onUpdate:modelValue":s[1]||(s[1]=l=>t.designation=l),variant:"outlined",required:""},null,8,["modelValue"])]),_:1})]),_:1})]),_:1}),e(N),e(D,null,{default:a(()=>[e(U),x("div",h,[e(g,{class:"ma-2",color:"blue-grey-lighten-2",onClick:s[2]||(s[2]=l=>A(!1))},{default:a(()=>[e(v,{start:"",icon:"mdi-cancel"}),b(" Cancel ")]),_:1}),e(g,{class:"ma-2",color:"green-darken-1",type:"button",onClick:s[3]||(s[3]=l=>w())},{default:a(()=>[b(" Save "),e(v,{end:"",icon:"mdi-check"})]),_:1})])]),_:1})]),_:1})]),_:1},8,["modelValue"])}}});export{F as _};
