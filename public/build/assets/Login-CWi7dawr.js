import{T as w,o as i,e as d,b as t,u as s,w as n,F as b,Z as h,a,t as x,f as c,c as k,g as u,m as y,n as v,l as V}from"./app-By-Xubw5.js";import{A as N}from"./AuthenticationCard-BK-Lm3XJ.js";import{_ as $}from"./AuthenticationCardLogo-DgmNhis3.js";import{_ as B}from"./Checkbox-B9uSaLI_.js";import{_ as f,a as p}from"./TextInput-Dw8-RiA1.js";import{_}from"./InputLabel-DTxroHve.js";import{_ as C}from"./PrimaryButton-9KpBHVmr.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";import"./dost-logo-B4Huuyod.js";const F={class:"text-center"},S=a("h1",{class:"text-center"},"SIGNIN",-1),q={key:0,class:"mb-4 font-medium text-sm text-green-600"},A={class:"mt-4"},L={class:"block mt-4"},P={class:"flex items-center"},R=a("span",{class:"ms-2 text-sm text-gray-600"},"Remember me",-1),U={class:"flex items-center justify-end mt-4"},H={__name:"Login",props:{canResetPassword:Boolean,status:String},setup(m){const e=w({email:"",password:"",remember:!1}),g=()=>{e.transform(l=>({...l,remember:e.remember?"on":""})).post(route("login"),{onFinish:()=>e.reset("password")})};return(l,o)=>(i(),d(b,null,[t(s(h),{title:"Log in"}),t(N,{id:"AuthenticationCard"},{default:n(()=>[a("div",F,[t($)]),S,m.status?(i(),d("div",q,x(m.status),1)):c("",!0),a("form",{onSubmit:V(g,["prevent"])},[a("div",null,[t(_,{for:"email",value:"Email"}),t(f,{id:"email",modelValue:s(e).email,"onUpdate:modelValue":o[0]||(o[0]=r=>s(e).email=r),type:"email",class:"mt-1 block w-full",required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),t(p,{class:"mt-2",message:s(e).errors.email},null,8,["message"])]),a("div",A,[t(_,{for:"password",value:"Password"}),t(f,{id:"password",modelValue:s(e).password,"onUpdate:modelValue":o[1]||(o[1]=r=>s(e).password=r),type:"password",class:"mt-1 block w-full",required:"",autocomplete:"current-password"},null,8,["modelValue"]),t(p,{class:"mt-2",message:s(e).errors.password},null,8,["message"])]),a("div",L,[a("label",P,[t(B,{checked:s(e).remember,"onUpdate:checked":o[2]||(o[2]=r=>s(e).remember=r),name:"remember"},null,8,["checked"]),R])]),a("div",U,[m.canResetPassword?(i(),k(s(y),{key:0,href:l.route("password.request"),class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:n(()=>[u(" Forgot your password? ")]),_:1},8,["href"])):c("",!0),t(C,{class:v(["ms-4",{"opacity-25":s(e).processing}]),disabled:s(e).processing},{default:n(()=>[u(" Log in ")]),_:1},8,["class","disabled"])])],32)]),_:1})],64))}};export{H as default};
