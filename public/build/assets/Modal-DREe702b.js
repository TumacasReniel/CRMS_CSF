import{E as V,d as u,i as O,z as N,x as _,o as d,e as b,a as e,b as h,u as w,g as i,n as R,f as p,c as g,l as $,F as I,B as M,C as j}from"./app-CqWeIVpP.js";import{s as k}from"./vue-multiselect.css_vue_type_style_index_0_src_true_lang-Cf-n-MLR.js";import C from"./ByUnitMonthly-BHxLzOvG.js";import{P as A}from"./index-DajnbktX.js";import{_ as F}from"./_plugin-vue_export-helper-DlAUqK2U.js";import"./dost-logo-B4Huuyod.js";const l=o=>(M("data-v-d9121387"),o=o(),j(),o),U={class:"modal-dialog modal-lg",role:"document"},z={class:"modal-content"},E={class:"modal-header bg-primary text-white"},T=l(()=>e("h5",{class:"modal-title"},[e("i",{class:"ri-user-line me-2"}),i(" Select Assignatoree ")],-1)),q={class:"modal-body"},D={class:"row mb-3"},G={class:"col-12"},H=l(()=>e("label",{class:"form-label"},"Prepared By:",-1)),J={class:"row"},K={class:"col-12"},L=l(()=>e("label",{class:"form-label"},"Noted By:",-1)),Q={class:"modal-footer"},W=l(()=>e("i",{class:"ri-close-line me-1"},null,-1)),X=l(()=>e("i",{class:"ri-printer-line me-1"},null,-1)),Y={key:0,class:"modal-backdrop fade show"},Z={class:"modal-header"},ee=l(()=>e("h5",{class:"modal-title"},[e("i",{class:"ri-file-text-line me-2"}),i(" Print Preview - CSI Report ")],-1)),te={class:"modal-body"},oe={class:"preview-container",style:{"max-height":"60vh","overflow-y":"auto"}},se={class:"modal-footer"},ae=l(()=>e("i",{class:"ri-close-line me-1"},null,-1)),le=l(()=>e("i",{class:"ri-printer-line me-1"},null,-1)),ne=V({__name:"Modal",props:{form:{type:Object,default:null},assignatorees:{type:Object,default:null},user:{type:Object,default:null},users:{type:Object,default:null},value:{type:Boolean,default:!1},data:{type:Object,default:null},generated:{type:Boolean}},emits:["input"],setup(o,{emit:x}){const f=x,c=o,m=u(!1),r=u(!1);O(()=>c.value,a=>{m.value=a});const n=N({prepared_by:c.user,noted_by:{}});_(()=>{var a;return((a=c.data)==null?void 0:a.user_list)||[]});const B=_(()=>c.assignatorees||[]),P=()=>{r.value=!0},y=a=>{f("input",a)},v=u(!1),S=async()=>{v.value=!0,await(await new A).print(document.querySelector(".print-id"),[`
          @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;600;800&family=Roboto:wght@100;300;400;500;700;900&display=swap');
          * {
              font-family: 'Time New Roman'
          }
          .new-page {
              page-break-before: always;
          }
          .th-color{
              background-color: #8fd1e8;
          }
          .text-center{
            text-align: center;
          }
          .text-right{
            text-align:end
          }
          table {
            border-collapse: collapse;
            width: 100%; /* Optional: Set a width for the table */
          }

          tr, th, td {
            border: 1px solid rgb(145, 139, 139); /* Optional: Add a border for better visibility */
            padding: 3px; /* Optional: Add padding for better spacing */
          }
          .page-break {
            page-break-before: always; /* or page-break-after: always; */
          }

        `]),f("input",!1)};return(a,t)=>(d(),b(I,null,[e("div",{class:R(["modal fade",{show:m.value,"d-block":m.value}]),tabindex:"-1",role:"dialog"},[e("div",U,[e("div",z,[e("div",E,[T,e("button",{type:"button",class:"btn-close btn-close-white",onClick:t[0]||(t[0]=s=>y(!1)),"aria-label":"Close"})]),e("div",q,[e("div",D,[e("div",G,[H,h(w(k),{modelValue:n.prepared_by,"onUpdate:modelValue":t[1]||(t[1]=s=>n.prepared_by=s),options:o.users,multiple:!1,placeholder:"Select Prepared By",label:"name","track-by":"id","allow-empty":!1,class:"form-control p-0 border-0",style:{"min-width":"200px"}},null,8,["modelValue","options"])])]),e("div",J,[e("div",K,[L,h(w(k),{modelValue:n.noted_by,"onUpdate:modelValue":t[2]||(t[2]=s=>n.noted_by=s),options:B.value,multiple:!1,placeholder:"Select Noted By",label:"name","track-by":"name","allow-empty":!1},null,8,["modelValue","options"])])])]),e("div",Q,[e("button",{type:"button",class:"btn btn-secondary",onClick:t[3]||(t[3]=s=>y(!1))},[W,i(" Cancel ")]),e("button",{type:"button",class:"btn btn-success",onClick:t[4]||(t[4]=s=>P())},[X,i(" Print Preview ")])])])])],2),m.value?(d(),b("div",Y)):p("",!0),r.value?(d(),b("div",{key:1,class:"modal-overlay",onClick:t[9]||(t[9]=s=>r.value=!1)},[e("div",{class:"modal-content",onClick:t[8]||(t[8]=$(()=>{},["stop"]))},[e("div",Z,[ee,e("button",{type:"button",class:"modal-close",onClick:t[5]||(t[5]=s=>r.value=!1)},"×")]),e("div",te,[e("div",oe,[o.form.csi_type=="By Month"?(d(),g(C,{key:0,form:o.form,data:o.data,form_assignatorees:n},null,8,["form","data","form_assignatorees"])):p("",!0)])]),e("div",se,[e("button",{type:"button",class:"btn btn-secondary",onClick:t[6]||(t[6]=s=>r.value=!1)},[ae,i("Close ")]),e("button",{type:"button",class:"btn btn-primary",onClick:t[7]||(t[7]=s=>S())},[le,i("Print Report ")])])])])):p("",!0),o.form.csi_type=="By Month"&&v.value&&o.data?(d(),g(C,{key:2,form:o.form,data:o.data,form_assignatorees:n},null,8,["form","data","form_assignatorees"])):p("",!0)],64))}}),ue=F(ne,[["__scopeId","data-v-d9121387"]]);export{ue as default};
