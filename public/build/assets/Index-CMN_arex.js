import{x as ot,d as Q,y as nt,i as it,j as E,o as f,e as g,b as d,u as lt,w as h,F as M,M as rt,Z as dt,l as ct,a as o,t as k,g as w,f as P,q as H,v as Y,h as L,c as X,O as ut}from"./app-DBdEy3oc.js";import{_ as tt}from"./dost-logo-B4Huuyod.js";import{A as ht}from"./aos-C-NhQzWs.js";import{S as N}from"./sweetalert2.all-BGvuOgZV.js";/*!
 * Signature Pad v4.1.7 | https://github.com/szimek/signature_pad
 * (c) 2023 Szymon Nowak | Released under the MIT license
 */class B{constructor(t,s,a,i){if(isNaN(t)||isNaN(s))throw new Error(`Point is invalid: (${t}, ${s})`);this.x=+t,this.y=+s,this.pressure=a||0,this.time=i||Date.now()}distanceTo(t){return Math.sqrt(Math.pow(this.x-t.x,2)+Math.pow(this.y-t.y,2))}equals(t){return this.x===t.x&&this.y===t.y&&this.pressure===t.pressure&&this.time===t.time}velocityFrom(t){return this.time!==t.time?this.distanceTo(t)/(this.time-t.time):0}}class K{static fromPoints(t,s){const a=this.calculateControlPoints(t[0],t[1],t[2]).c2,i=this.calculateControlPoints(t[1],t[2],t[3]).c1;return new K(t[1],a,i,t[2],s.start,s.end)}static calculateControlPoints(t,s,a){const i=t.x-s.x,l=t.y-s.y,u=s.x-a.x,m=s.y-a.y,e={x:(t.x+s.x)/2,y:(t.y+s.y)/2},n={x:(s.x+a.x)/2,y:(s.y+a.y)/2},_=Math.sqrt(i*i+l*l),p=Math.sqrt(u*u+m*m),S=e.x-n.x,T=e.y-n.y,C=p/(_+p),U={x:n.x+S*C,y:n.y+T*C},y=s.x-U.x,r=s.y-U.y;return{c1:new B(e.x+y,e.y+r),c2:new B(n.x+y,n.y+r)}}constructor(t,s,a,i,l,u){this.startPoint=t,this.control2=s,this.control1=a,this.endPoint=i,this.startWidth=l,this.endWidth=u}length(){let s=0,a,i;for(let l=0;l<=10;l+=1){const u=l/10,m=this.point(u,this.startPoint.x,this.control1.x,this.control2.x,this.endPoint.x),e=this.point(u,this.startPoint.y,this.control1.y,this.control2.y,this.endPoint.y);if(l>0){const n=m-a,_=e-i;s+=Math.sqrt(n*n+_*_)}a=m,i=e}return s}point(t,s,a,i,l){return s*(1-t)*(1-t)*(1-t)+3*a*(1-t)*(1-t)*t+3*i*(1-t)*t*t+l*t*t*t}}class mt{constructor(){try{this._et=new EventTarget}catch{this._et=document}}addEventListener(t,s,a){this._et.addEventListener(t,s,a)}dispatchEvent(t){return this._et.dispatchEvent(t)}removeEventListener(t,s,a){this._et.removeEventListener(t,s,a)}}function _t(x,t=250){let s=0,a=null,i,l,u;const m=()=>{s=Date.now(),a=null,i=x.apply(l,u),a||(l=null,u=[])};return function(...n){const _=Date.now(),p=t-(_-s);return l=this,u=n,p<=0||p>t?(a&&(clearTimeout(a),a=null),s=_,i=x.apply(l,u),a||(l=null,u=[])):a||(a=window.setTimeout(m,p)),i}}class I extends mt{constructor(t,s={}){super(),this.canvas=t,this._drawingStroke=!1,this._isEmpty=!0,this._lastPoints=[],this._data=[],this._lastVelocity=0,this._lastWidth=0,this._handleMouseDown=a=>{a.buttons===1&&this._strokeBegin(a)},this._handleMouseMove=a=>{this._strokeMoveUpdate(a)},this._handleMouseUp=a=>{a.buttons===1&&this._strokeEnd(a)},this._handleTouchStart=a=>{if(a.cancelable&&a.preventDefault(),a.targetTouches.length===1){const i=a.changedTouches[0];this._strokeBegin(i)}},this._handleTouchMove=a=>{a.cancelable&&a.preventDefault();const i=a.targetTouches[0];this._strokeMoveUpdate(i)},this._handleTouchEnd=a=>{if(a.target===this.canvas){a.cancelable&&a.preventDefault();const l=a.changedTouches[0];this._strokeEnd(l)}},this._handlePointerStart=a=>{a.preventDefault(),this._strokeBegin(a)},this._handlePointerMove=a=>{this._strokeMoveUpdate(a)},this._handlePointerEnd=a=>{this._drawingStroke&&(a.preventDefault(),this._strokeEnd(a))},this.velocityFilterWeight=s.velocityFilterWeight||.7,this.minWidth=s.minWidth||.5,this.maxWidth=s.maxWidth||2.5,this.throttle="throttle"in s?s.throttle:16,this.minDistance="minDistance"in s?s.minDistance:5,this.dotSize=s.dotSize||0,this.penColor=s.penColor||"black",this.backgroundColor=s.backgroundColor||"rgba(0,0,0,0)",this.compositeOperation=s.compositeOperation||"source-over",this._strokeMoveUpdate=this.throttle?_t(I.prototype._strokeUpdate,this.throttle):I.prototype._strokeUpdate,this._ctx=t.getContext("2d"),this.clear(),this.on()}clear(){const{_ctx:t,canvas:s}=this;t.fillStyle=this.backgroundColor,t.clearRect(0,0,s.width,s.height),t.fillRect(0,0,s.width,s.height),this._data=[],this._reset(this._getPointGroupOptions()),this._isEmpty=!0}fromDataURL(t,s={}){return new Promise((a,i)=>{const l=new Image,u=s.ratio||window.devicePixelRatio||1,m=s.width||this.canvas.width/u,e=s.height||this.canvas.height/u,n=s.xOffset||0,_=s.yOffset||0;this._reset(this._getPointGroupOptions()),l.onload=()=>{this._ctx.drawImage(l,n,_,m,e),a()},l.onerror=p=>{i(p)},l.crossOrigin="anonymous",l.src=t,this._isEmpty=!1})}toDataURL(t="image/png",s){switch(t){case"image/svg+xml":return typeof s!="object"&&(s=void 0),`data:image/svg+xml;base64,${btoa(this.toSVG(s))}`;default:return typeof s!="number"&&(s=void 0),this.canvas.toDataURL(t,s)}}on(){this.canvas.style.touchAction="none",this.canvas.style.msTouchAction="none",this.canvas.style.userSelect="none";const t=/Macintosh/.test(navigator.userAgent)&&"ontouchstart"in document;window.PointerEvent&&!t?this._handlePointerEvents():(this._handleMouseEvents(),"ontouchstart"in window&&this._handleTouchEvents())}off(){this.canvas.style.touchAction="auto",this.canvas.style.msTouchAction="auto",this.canvas.style.userSelect="auto",this.canvas.removeEventListener("pointerdown",this._handlePointerStart),this.canvas.removeEventListener("pointermove",this._handlePointerMove),this.canvas.ownerDocument.removeEventListener("pointerup",this._handlePointerEnd),this.canvas.removeEventListener("mousedown",this._handleMouseDown),this.canvas.removeEventListener("mousemove",this._handleMouseMove),this.canvas.ownerDocument.removeEventListener("mouseup",this._handleMouseUp),this.canvas.removeEventListener("touchstart",this._handleTouchStart),this.canvas.removeEventListener("touchmove",this._handleTouchMove),this.canvas.removeEventListener("touchend",this._handleTouchEnd)}isEmpty(){return this._isEmpty}fromData(t,{clear:s=!0}={}){s&&this.clear(),this._fromData(t,this._drawCurve.bind(this),this._drawDot.bind(this)),this._data=this._data.concat(t)}toData(){return this._data}_getPointGroupOptions(t){return{penColor:t&&"penColor"in t?t.penColor:this.penColor,dotSize:t&&"dotSize"in t?t.dotSize:this.dotSize,minWidth:t&&"minWidth"in t?t.minWidth:this.minWidth,maxWidth:t&&"maxWidth"in t?t.maxWidth:this.maxWidth,velocityFilterWeight:t&&"velocityFilterWeight"in t?t.velocityFilterWeight:this.velocityFilterWeight,compositeOperation:t&&"compositeOperation"in t?t.compositeOperation:this.compositeOperation}}_strokeBegin(t){if(!this.dispatchEvent(new CustomEvent("beginStroke",{detail:t,cancelable:!0})))return;this._drawingStroke=!0;const a=this._getPointGroupOptions(),i=Object.assign(Object.assign({},a),{points:[]});this._data.push(i),this._reset(a),this._strokeUpdate(t)}_strokeUpdate(t){if(!this._drawingStroke)return;if(this._data.length===0){this._strokeBegin(t);return}this.dispatchEvent(new CustomEvent("beforeUpdateStroke",{detail:t}));const s=t.clientX,a=t.clientY,i=t.pressure!==void 0?t.pressure:t.force!==void 0?t.force:0,l=this._createPoint(s,a,i),u=this._data[this._data.length-1],m=u.points,e=m.length>0&&m[m.length-1],n=e?l.distanceTo(e)<=this.minDistance:!1,_=this._getPointGroupOptions(u);if(!e||!(e&&n)){const p=this._addPoint(l,_);e?p&&this._drawCurve(p,_):this._drawDot(l,_),m.push({time:l.time,x:l.x,y:l.y,pressure:l.pressure})}this.dispatchEvent(new CustomEvent("afterUpdateStroke",{detail:t}))}_strokeEnd(t){this._drawingStroke&&(this._strokeUpdate(t),this._drawingStroke=!1,this.dispatchEvent(new CustomEvent("endStroke",{detail:t})))}_handlePointerEvents(){this._drawingStroke=!1,this.canvas.addEventListener("pointerdown",this._handlePointerStart),this.canvas.addEventListener("pointermove",this._handlePointerMove),this.canvas.ownerDocument.addEventListener("pointerup",this._handlePointerEnd)}_handleMouseEvents(){this._drawingStroke=!1,this.canvas.addEventListener("mousedown",this._handleMouseDown),this.canvas.addEventListener("mousemove",this._handleMouseMove),this.canvas.ownerDocument.addEventListener("mouseup",this._handleMouseUp)}_handleTouchEvents(){this.canvas.addEventListener("touchstart",this._handleTouchStart),this.canvas.addEventListener("touchmove",this._handleTouchMove),this.canvas.addEventListener("touchend",this._handleTouchEnd)}_reset(t){this._lastPoints=[],this._lastVelocity=0,this._lastWidth=(t.minWidth+t.maxWidth)/2,this._ctx.fillStyle=t.penColor,this._ctx.globalCompositeOperation=t.compositeOperation}_createPoint(t,s,a){const i=this.canvas.getBoundingClientRect();return new B(t-i.left,s-i.top,a,new Date().getTime())}_addPoint(t,s){const{_lastPoints:a}=this;if(a.push(t),a.length>2){a.length===3&&a.unshift(a[0]);const i=this._calculateCurveWidths(a[1],a[2],s),l=K.fromPoints(a,i);return a.shift(),l}return null}_calculateCurveWidths(t,s,a){const i=a.velocityFilterWeight*s.velocityFrom(t)+(1-a.velocityFilterWeight)*this._lastVelocity,l=this._strokeWidth(i,a),u={end:l,start:this._lastWidth};return this._lastVelocity=i,this._lastWidth=l,u}_strokeWidth(t,s){return Math.max(s.maxWidth/(t+1),s.minWidth)}_drawCurveSegment(t,s,a){const i=this._ctx;i.moveTo(t,s),i.arc(t,s,a,0,2*Math.PI,!1),this._isEmpty=!1}_drawCurve(t,s){const a=this._ctx,i=t.endWidth-t.startWidth,l=Math.ceil(t.length())*2;a.beginPath(),a.fillStyle=s.penColor;for(let u=0;u<l;u+=1){const m=u/l,e=m*m,n=e*m,_=1-m,p=_*_,S=p*_;let T=S*t.startPoint.x;T+=3*p*m*t.control1.x,T+=3*_*e*t.control2.x,T+=n*t.endPoint.x;let C=S*t.startPoint.y;C+=3*p*m*t.control1.y,C+=3*_*e*t.control2.y,C+=n*t.endPoint.y;const U=Math.min(t.startWidth+n*i,s.maxWidth);this._drawCurveSegment(T,C,U)}a.closePath(),a.fill()}_drawDot(t,s){const a=this._ctx,i=s.dotSize>0?s.dotSize:(s.minWidth+s.maxWidth)/2;a.beginPath(),this._drawCurveSegment(t.x,t.y,i),a.closePath(),a.fillStyle=s.penColor,a.fill()}_fromData(t,s,a){for(const i of t){const{points:l}=i,u=this._getPointGroupOptions(i);if(l.length>1)for(let m=0;m<l.length;m+=1){const e=l[m],n=new B(e.x,e.y,e.pressure,e.time);m===0&&this._reset(u);const _=this._addPoint(n,u);_&&s(_,u)}else this._reset(u),a(l[0],u)}}toSVG({includeBackgroundColor:t=!1}={}){const s=this._data,a=Math.max(window.devicePixelRatio||1,1),i=0,l=0,u=this.canvas.width/a,m=this.canvas.height/a,e=document.createElementNS("http://www.w3.org/2000/svg","svg");if(e.setAttribute("xmlns","http://www.w3.org/2000/svg"),e.setAttribute("xmlns:xlink","http://www.w3.org/1999/xlink"),e.setAttribute("viewBox",`${i} ${l} ${u} ${m}`),e.setAttribute("width",u.toString()),e.setAttribute("height",m.toString()),t&&this.backgroundColor){const n=document.createElement("rect");n.setAttribute("width","100%"),n.setAttribute("height","100%"),n.setAttribute("fill",this.backgroundColor),e.appendChild(n)}return this._fromData(s,(n,{penColor:_})=>{const p=document.createElement("path");if(!isNaN(n.control1.x)&&!isNaN(n.control1.y)&&!isNaN(n.control2.x)&&!isNaN(n.control2.y)){const S=`M ${n.startPoint.x.toFixed(3)},${n.startPoint.y.toFixed(3)} C ${n.control1.x.toFixed(3)},${n.control1.y.toFixed(3)} ${n.control2.x.toFixed(3)},${n.control2.y.toFixed(3)} ${n.endPoint.x.toFixed(3)},${n.endPoint.y.toFixed(3)}`;p.setAttribute("d",S),p.setAttribute("stroke-width",(n.endWidth*2.25).toFixed(3)),p.setAttribute("stroke",_),p.setAttribute("fill","none"),p.setAttribute("stroke-linecap","round"),e.appendChild(p)}},(n,{penColor:_,dotSize:p,minWidth:S,maxWidth:T})=>{const C=document.createElement("circle"),U=p>0?p:(S+T)/2;C.setAttribute("r",U.toString()),C.setAttribute("cx",n.x.toString()),C.setAttribute("cy",n.y.toString()),C.setAttribute("fill",_),e.appendChild(C)}),e.outerHTML}}const pt=rt('<nav data-aos="fade-down" data-aos-duration="500" data-aos-delay="500" class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600"><div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4"><a href="/" class="flex items-center space-x-3 rtl:space-x-reverse"><img src="'+tt+'" class="h-8" alt="DOST Logo"><span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white text-black">Department of Science and Technology </span></a></div></nav>',1),ft={class:"py-20 bg-gray-200"},vt=o("div",null,[o("img",{"data-aos":"zoom-in","data-aos-duration":"500","data-aos-delay":"500",class:"mx-auto mb-5",style:{width:"200px",height:"200px"},src:tt,alt:".."})],-1),bt=o("span",{class:"font-black lg:text-4xl","data-aos":"fade-down","data-aos-duration":"500","data-aos-delay":"500"},"CUSTOMER SATISFACTION FEEDBACK",-1),gt=o("br",null,null,-1),yt=o("a",{href:"#"},[o("img",{class:"rounded-t-lg",src:"/docs/images/blog/image-1.jpg",alt:""})],-1),xt={class:"p-5"},wt={href:"#"},kt={class:"mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"},Ct=o("br",null,null,-1),Et={key:0},St={key:1},Pt={key:2,class:"ml-3"},Vt={key:3,class:"ml-3"},Tt=o("p",{class:"mb-3 font-normal text-gray-700 dark:text-gray-400"},"This questionaire aims to solicit your honest assessment of our services. Please take a minute in filling out this form and help us serve you better.",-1),Dt={class:"mb-5 grid grid-cols-3 gap-4"},Ot={class:"col-span-1"},Mt={class:"col-span-1"},Ut={class:"col-span-1"},Wt={class:"border border-w-2 p-3 mb-5"},At=o("div",null,[w(" Other Informations ("),o("span",{class:"text-blue-500"},"Optional"),w(") ")],-1),Lt={class:"grid grid-cols-4 gap-4"},Ft={class:"flex items-center ps-4 rounded"},zt=o("label",{for:"bordered-checkbox-2",class:"w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"},"Person with Disability",-1),It={class:"flex items-center ps-4 rounded"},Nt=o("label",{for:"bordered-checkbox-3",class:"w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"},"Pregnant Woman",-1),Bt={class:"flex items-center ps-4 rounded"},$t=o("label",{for:"bordered-checkbox-4",class:"w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"},"Senior Citizen",-1),qt={key:3,class:"text-red-800 m-5",style:{"margin-left":"80px"}},jt={style:{"text-transform":"uppercase"}},Gt=["value"],Rt={class:"ml-2"},Ht=o("br",null,null,-1),Yt={key:0,class:"text-red-800"},Xt={class:"overflow-hidden mb-3"},Kt=o("div",null,"How important is this attribute?",-1),Zt={class:"ml-2 mb-3"},Jt={key:0,class:"text-red-800"},Qt=o("div",{class:"p-3 font-bold text-lg"},[w("Considering your complete experience with our agency, how likely would you recommend our services to others? "),o("span",{class:"text-red-800"},"*")],-1),te={class:"ml-2 mb-3 mx-auto my-auto mb-5 d-flex justify-center text-center",style:{"margin-right":"50px","margin-left":"50px"}},ee={key:0,class:"text-red-800"},ae={class:"p-3 mt-0 font-bold text-lg"},se={key:0,class:"text-red-800"},oe={key:1,class:"text-blue-400"},ne={key:0,class:"text-red-800 p-5"},ie=o("br",null,null,-1),le=o("div",{class:"p-3 mt-0 font-bold text-lg"},[w("Please indicate other important attribute/s which you think is important to your needs. ( "),o("span",{class:"text-blue-400"},"Optional"),w(" )")],-1),re=o("div",{class:"p-3 mt-0 font-bold text-lg"},[w("Please write your signature on the box. ( "),o("span",{class:"text-blue-400"},"Optional"),w(" )")],-1),de={style:{"margin-left":"280px","margin-right":"280px"},class:"mt-5 mb-5 text-center"},ce={href:"/",class:"btn bg-secondary"},pe={__name:"Index",props:{cc_questions:Object,dimensions:Object,unit:Object,sub_unit:Object,unit_psto:Object,sub_unit_psto:Object,status:String,errors:Object,captcha_img:String},setup(x){const t=x,s=[{label:"1. I know what a CC is and I saw this office's CC.",value:"1"},{label:"2. I know what a CC is but I did NOT see this office's CC. ",value:"2"},{label:"3. I learned the CC when I saw this office's CC.",value:"3"},{label:"4. I do not know what a CC is  and I did not see one in this office.(Answer 'N/A' on CC2 and CC3)",value:"4"}],a=[{label:"1. Easy to see",value:"1"},{label:"2. Somewhat easy to see",value:"2"},{label:"3. Difficult to see",value:"3"},{label:"4. not Visible at all",value:"4"},{label:"5. N/A",value:"5"}],i=[{label:"1. Helped Very Much",value:"1"},{label:"2. Somewhat helped",value:"2"},{label:"3. Did not help",value:"3"},{label:"4. N/A",value:"4"}],l=[{label:"Very Satisfied",value:"5",icon:"mdi-emoticon-cool",color:"#FFEB3B"},{label:"Satisfied",value:"4",icon:"mdi-emoticon-happy",color:"#FFC107"},{label:"Neither",value:"3",icon:"mdi-emoticon-neutral",color:"#263238"},{label:"Dissatisfied",value:"2",icon:"mdi-emoticon-sad",color:"#F44336"},{label:"Very Dissatisfied",value:"1",icon:"mdi-emoticon-devil",color:"#6200EA"}],u=[{label:"5",value:"5"},{label:"4",value:"4"},{label:"3",value:"3"},{label:"2",value:"2"},{label:"1",value:"1"}],m=[{label:"10",value:"10"},{label:"9",value:"9"},{label:"8",value:"8"},{label:"7",value:"7"},{label:"6",value:"6"},{label:"5",value:"5"},{label:"4",value:"4"},{label:"3",value:"3"},{label:"2",value:"2"},{label:"1",value:"1"}],e=ot({region_id:null,service_id:null,unit_id:null,sub_unit_id:null,psto_id:null,client_type:null,sub_unit_type:null,email:null,name:null,sex:null,age_group:null,pwd:0,pregnant:0,senior_citizen:0,cc1:null,cc2:null,cc3:null,recommend_rate_score:null,comment:null,is_complaint:!1,indication:null,signature:null,dimension_form:{id:[],rate_score:[],importance_rate_score:[]},cc_form:{id:[],answer:[]},captcha:null,current_url:null,complaint_scanner:{value:[]}}),n=Q(!1),_=(y,r,W)=>{e.cc_form.id[y]=r,e.cc_form.answer[y]=W},p=(y,r)=>{e.dimension_form.id[y]=r},S=Q(null);document.querySelector(".signature-pad canvas"),nt(()=>{ht.init(),S.value=new I(S.value);const y=window.location.href,r=new URLSearchParams(y.split("?")[1]);e.region_id=r.get("region_id"),e.service_id=r.get("service_id"),e.unit_id=r.get("unit_id"),e.sub_unit_id=r.get("sub_unit_id"),e.psto_id=r.get("psto_id"),e.sub_unit_type=r.get("sub_unit_type"),e.current_url=y,N.fire({title:"Disclaimer",icon:"warning",text:"The DOST is committed to protect and respect your personal data privacy. All information collected will only be used for documentation purposes and will not be published in any platform."})});const T=async()=>{n.value=!0;const y=document.querySelector(".signature-pad");y.getContext("2d");const r=y.toDataURL();e.signature=r;let W=Math.random();const z=()=>'<img src="'+("/captcha/flat?rand="+W)+'" alt="CAPTCHA" style="display: block; margin: 0 auto; ">';try{N.fire({title:z(),html:'<div style="font-weight: bold; font-size:25px">Enter Captcha Code</div> <input id="captcha-input" class="swal2-input text-center"><p id="invalid-captcha-text" style="color: red; font-size: 14px; margin-top: 5px; display: none;">Invalid CAPTCHA code</p>',inputAttributes:{autocapitalize:"off"},showCancelButton:!0,confirmButtonText:"Submit",showLoaderOnConfirm:!0,preConfirm:()=>{const D=document.getElementById("captcha-input").value;return e.captcha=D,!0}}).then(D=>{D.isConfirmed&&ut.post("/csf_submission",e)})}catch{N.fire({title:"Failed",icon:"error",text:"Something went wrong pease check"})}},C=(y,r)=>{e.dimension_form.rate_score[y]<4?e.complaint_scanner.value[y]=!0:e.complaint_scanner.value[y]=!1,e.is_complaint=!1,e.complaint_scanner.value.forEach(W=>{if(W===!0){e.is_complaint=!0;return}})},U=()=>{new I(S.value)};return it(()=>t.errors.captcha,y=>{y&&N.fire({title:"Error Captcha",text:"Wrong captcha code!",icon:"error"})}),(y,r)=>{const W=E("v-col"),z=E("v-row"),D=E("v-card-title"),V=E("v-card"),Z=E("v-text-field"),$=E("v-select"),q=E("v-checkbox"),et=E("v-icon"),F=E("v-btn"),j=E("v-btn-toggle"),J=E("v-chip"),at=E("v-divider"),G=E("v-textarea"),R=E("v-container"),st=E("v-form");return f(),g(M,null,[d(lt(dt),{title:"Survey Form"}),pt,d(V,{class:"container","data-aos":"fade-up","data-aos-duration":"2000","data-aos-delay":"500"},{default:h(()=>[d(z,{justify:"center",class:"py-3 bg-gray-200"},{default:h(()=>[d(st,{class:"max-w",onSubmit:ct(T,["prevent"])},{default:h(()=>[o("div",ft,[d(V,{class:"mb-5",width:"1000"},{default:h(()=>[d(D,{class:"m-5 font-black flex flex-col items-center"},{default:h(()=>[d(z,null,{default:h(()=>[d(W,{class:"text-center"},{default:h(()=>[vt,bt,gt]),_:1})]),_:1})]),_:1})]),_:1}),d(V,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto",width:"1000"},{default:h(()=>[yt,o("div",xt,[o("a",wt,[o("h5",kt,[o("span",null,k(x.unit.data[0].unit_name),1),w(),Ct,x.sub_unit.data.length>0?(f(),g("span",Et,k(x.sub_unit.data[0].sub_unit_name),1)):P("",!0),x.unit_psto.data.length>0?(f(),g("span",St,k(x.unit_psto.data[0].psto.psto_name),1)):P("",!0),x.sub_unit_psto.data.length>0?(f(),g("span",Pt,k(x.sub_unit_psto.data[0].psto.psto_name),1)):P("",!0),e.sub_unit_type?(f(),g("span",Vt,k(e.sub_unit_type),1)):P("",!0)])]),Tt,o("div",null,[d(Z,{modelValue:e.email,"onUpdate:modelValue":r[0]||(r[0]=c=>e.email=c),type:"email",placeholder:"email@gmail.com",label:"Email(Optional)",variant:"outlined"},null,8,["modelValue"]),d(Z,{modelValue:e.name,"onUpdate:modelValue":r[1]||(r[1]=c=>e.name=c),placeholder:"Enter your full name",label:"Name(Optional)",variant:"outlined"},null,8,["modelValue"]),o("div",Dt,[o("div",Ot,[d($,{label:"Client_type*",variant:"outlined",modelValue:e.client_type,"onUpdate:modelValue":r[2]||(r[2]=c=>e.client_type=c),items:["Internal Employees","General Public","Government Employees","Business/Organization"],rules:[c=>!!c||x.errors.client_type||"This field is required"]},null,8,["modelValue","rules"])]),o("div",Mt,[d($,{label:"Sex*",variant:"outlined",modelValue:e.sex,"onUpdate:modelValue":r[3]||(r[3]=c=>e.sex=c),items:["Male","Female"],rules:[c=>!!c||x.errors.sex||"This field is required"]},null,8,["modelValue","rules"])]),o("div",Ut,[d($,{label:"Age Group*",variant:"outlined",modelValue:e.age_group,"onUpdate:modelValue":r[4]||(r[4]=c=>e.age_group=c),items:["15-19","20-29","30-39","40-49","50-59","60-69","70-79","80+"],rules:[c=>!!c||x.errors.sex||"This field is required"]},null,8,["modelValue","rules"])])]),o("div",Wt,[At,o("div",Lt,[o("div",Ft,[H(o("input",{"onUpdate:modelValue":r[5]||(r[5]=c=>e.pwd=c),id:"bordered-checkbox-2",type:"checkbox",value:"",name:"bordered-checkbox",class:"w-4 h-4 text-blue-600 bg-gray-100 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"},null,512),[[Y,e.pwd]]),zt]),o("div",It,[H(o("input",{"onUpdate:modelValue":r[6]||(r[6]=c=>e.pregnant=c),id:"bordered-checkbox-3",type:"checkbox",value:"",name:"bordered-checkbox",class:"w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"},null,512),[[Y,e.pregnant]]),Nt]),o("div",Bt,[H(o("input",{"onUpdate:modelValue":r[7]||(r[7]=c=>e.senior_citizen=c),id:"bordered-checkbox-4",type:"checkbox",value:"",name:"bordered-checkbox",class:"w-4 h-4 text-blue-600 bg-gray-100 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"},null,512),[[Y,e.senior_citizen]]),$t])])])])])]),_:1}),d(V,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto",width:"1000"},{default:h(()=>[(f(!0),g(M,null,L(x.cc_questions,(c,v)=>(f(),g("div",{key:v,class:"mb-10"},[d(D,{class:"m-5 font-black mb-10"},{default:h(()=>[w(k(c.title)+". "+k(c.question),1)]),_:2},1024),v==0?(f(),g(M,{key:0},L(s,(b,O)=>o("div",{key:O},[d(q,{onClick:A=>_(v,c.id,b.value),modelValue:e.cc1,"onUpdate:modelValue":r[8]||(r[8]=A=>e.cc1=A),color:"primary",style:{"margin-top":"-50px","margin-left":"7%","margin-bottom":"-5%"},label:b.label,value:b.label},null,8,["onClick","modelValue","label","value"])])),64)):P("",!0),v==1?(f(),g(M,{key:1},L(a,(b,O)=>o("div",{key:O},[d(q,{onClick:A=>_(v,c.id,b.value),modelValue:e.cc2,"onUpdate:modelValue":r[9]||(r[9]=A=>e.cc2=A),color:"primary",style:{"margin-top":"-50px","margin-left":"7%","margin-bottom":"-5%"},label:b.label,value:b.label},null,8,["onClick","modelValue","label","value"])])),64)):P("",!0),v==2?(f(),g(M,{key:2},L(i,(b,O)=>o("div",{key:O},[d(q,{onClick:A=>_(v,c.id,b.value),modelValue:e.cc3,"onUpdate:modelValue":r[10]||(r[10]=A=>e.cc3=A),color:"primary",style:{"margin-top":"-50px","margin-left":"7%","margin-bottom":"-5%"},label:b.label,value:b.value},null,8,["onClick","modelValue","label","value"])])),64)):P("",!0),n.value&&!e.cc_form.answer[v]?(f(),g("div",qt,k("This selection is required"))):P("",!0)]))),128))]),_:1}),d(V,{"data-aos":"fade-left","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto",width:"1000"},{default:h(()=>[d(D,{class:"text-lg text-white bg-blue_grey p-3"},{default:h(()=>[w(" HOW WOULD YOU RATE OUR "),o("span",jt,k(x.unit.data[0].unit_name),1),w(" SERVICES? ")]),_:1}),o("div",null,[(f(!0),g(M,null,L(x.dimensions,(c,v)=>(f(),X(V,{"data-aos":"fade-left","data-aos-duration":"1000","data-aos-delay":"500",class:"text-center over-flowhidden scroll-none mb-1",border:"1",key:c.id},{default:h(()=>[d(D,{class:"text-4xl mt-5 mb-3 text-uppercase"},{default:h(()=>[w(k(c.name),1)]),_:2},1024),o("input",{type:"hidden",value:p(v,c.id)},null,8,Gt),o("div",Rt,[(f(),g(M,null,L(l,b=>d(j,{class:"mb-5",modelValue:e.dimension_form.rate_score[v],"onUpdate:modelValue":O=>e.dimension_form.rate_score[v]=O,key:b.value,rules:[()=>n.value?!!e.dimension_form.rate_score[v]||"This selection is required":!0]},{default:h(()=>[d(F,{onClick:O=>C(v,e.dimension_form.rate_score[v]),rounded:"",class:"mr-2 bg-gray-200",value:b.value,color:"secondary"},{default:h(()=>[d(et,{color:e.dimension_form.rate_score[v]===b.value?b.color:"gray",size:"40"},{default:h(()=>[w(k(b.icon),1)]),_:2},1032,["color"]),Ht,o("label",null,k(b.label),1)]),_:2},1032,["onClick","value"])]),_:2},1032,["modelValue","onUpdate:modelValue","rules"])),64)),n.value&&!e.dimension_form.rate_score[v]?(f(),g("div",Yt,k("This selection is required"))):P("",!0)]),o("div",Xt,[Kt,o("div",null,[o("div",Zt,[(f(),g(M,null,L(u,b=>d(j,{modelValue:e.dimension_form.importance_rate_score[v],"onUpdate:modelValue":O=>e.dimension_form.importance_rate_score[v]=O,key:b.value,mandatory:""},{default:h(()=>[d(F,{class:"mr-2",value:b.value,color:"secondary",style:{"border-radius":"40%"}},{default:h(()=>[d(J,null,{default:h(()=>[o("label",null,k(b.label),1)]),_:2},1024)]),_:2},1032,["value"])]),_:2},1032,["modelValue","onUpdate:modelValue"])),64)),n.value&&!e.dimension_form.importance_rate_score[v]?(f(),g("div",Jt,k("This selection is required"))):P("",!0)])])])]),_:2},1024))),128)),d(at)])]),_:1}),d(V,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto",width:"1000"},{default:h(()=>[Qt,o("div",te,[(f(),g(M,null,L(m,c=>d(j,{modelValue:e.recommend_rate_score,"onUpdate:modelValue":r[11]||(r[11]=v=>e.recommend_rate_score=v),mandatory:"",key:c.value},{default:h(()=>[d(F,{value:c.value,class:"mr-2",color:(e.recommend_rate_score===c.color,"secondary"),style:{"border-radius":"40%"}},{default:h(()=>[d(J,null,{default:h(()=>[o("label",null,k(c.label),1)]),_:2},1024)]),_:2},1032,["value","color"])]),_:2},1032,["modelValue"])),64)),n.value&&!e.recommend_rate_score?(f(),g("div",ee,k("This selection is required"))):P("",!0)])]),_:1}),d(V,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto",width:"1000"},{default:h(()=>[o("div",ae,[w("Please write your comment/suggestions below. "),e.is_complaint==!0?(f(),g("span",se,"*")):(f(),g("span",oe,"(Optional)"))]),d(R,{fluid:""},{default:h(()=>[e.is_complaint==!0?(f(),X(G,{key:0,modelValue:e.comment,"onUpdate:modelValue":r[12]||(r[12]=c=>e.comment=c),placeholder:"Input here!",rules:[c=>!!c||n.value&&!e.comment||"This field is required"]},null,8,["modelValue","rules"])):e.is_complaint==!1?(f(),X(G,{key:1,modelValue:e.comment,"onUpdate:modelValue":r[13]||(r[13]=c=>e.comment=c),placeholder:"Input here"},null,8,["modelValue"])):P("",!0)]),_:1}),n.value&&e.is_complaint==!0?(f(),g("div",ne,[w(k("This selection is required because you rate low to our services with the options above."),1),ie,w(" Please input the reason/s why you have rated low.")])):P("",!0)]),_:1}),d(V,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto",width:"1000"},{default:h(()=>[le,d(R,{fluid:""},{default:h(()=>[d(G,{modelValue:e.indication,"onUpdate:modelValue":r[14]||(r[14]=c=>e.indication=c),placeholder:"Input here"},null,8,["modelValue"])]),_:1})]),_:1}),d(V,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto",width:"1000"},{default:h(()=>[re,d(R,{class:"text-center"},{default:h(()=>[d(z,null,{default:h(()=>[d(W,null,{default:h(()=>[o("div",null,[o("canvas",{class:"signature-pad mb-3 mx-auto",ref_key:"signaturePad",ref:S},null,512)]),d(F,{onClick:U,class:""},{default:h(()=>[w("Clear")]),_:1})]),_:1})]),_:1})]),_:1})]),_:1}),d(V,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto",width:"1000"},{default:h(()=>[o("div",de,[d(F,{color:"success",type:"submit",class:"mr-2","prepend-icon":"mdi-send",disabled:e.processing||e.is_complaint&&!e.comment},{default:h(()=>[w("Submit")]),_:1},8,["disabled"]),o("a",ce,[d(F,{class:"bg-secondary"},{default:h(()=>[w("Back")]),_:1})])])]),_:1})])]),_:1})]),_:1})]),_:1})],64)}}};export{pe as default};
