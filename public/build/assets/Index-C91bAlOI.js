import{x as ot,d as Q,y as nt,i as it,j as E,o as f,e as y,b as o,u as lt,w as c,F as U,M as rt,Z as dt,l as ct,a as r,t as k,g as w,f as V,q as Y,v as X,h as F,c as K,O as ut}from"./app-D8es4Nph.js";import{_ as tt}from"./dost-logo-B4Huuyod.js";import{A as ht}from"./aos-C7Eh-XEC.js";import{S as B}from"./sweetalert2.all-CapNuMTQ.js";/*!
 * Signature Pad v4.1.7 | https://github.com/szimek/signature_pad
 * (c) 2023 Szymon Nowak | Released under the MIT license
 */class ${constructor(t,s,a,i){if(isNaN(t)||isNaN(s))throw new Error(`Point is invalid: (${t}, ${s})`);this.x=+t,this.y=+s,this.pressure=a||0,this.time=i||Date.now()}distanceTo(t){return Math.sqrt(Math.pow(this.x-t.x,2)+Math.pow(this.y-t.y,2))}equals(t){return this.x===t.x&&this.y===t.y&&this.pressure===t.pressure&&this.time===t.time}velocityFrom(t){return this.time!==t.time?this.distanceTo(t)/(this.time-t.time):0}}class Z{static fromPoints(t,s){const a=this.calculateControlPoints(t[0],t[1],t[2]).c2,i=this.calculateControlPoints(t[1],t[2],t[3]).c1;return new Z(t[1],a,i,t[2],s.start,s.end)}static calculateControlPoints(t,s,a){const i=t.x-s.x,l=t.y-s.y,h=s.x-a.x,m=s.y-a.y,e={x:(t.x+s.x)/2,y:(t.y+s.y)/2},n={x:(s.x+a.x)/2,y:(s.y+a.y)/2},_=Math.sqrt(i*i+l*l),p=Math.sqrt(h*h+m*m),S=e.x-n.x,T=e.y-n.y,C=p/(_+p),W={x:n.x+S*C,y:n.y+T*C},g=s.x-W.x,d=s.y-W.y;return{c1:new $(e.x+g,e.y+d),c2:new $(n.x+g,n.y+d)}}constructor(t,s,a,i,l,h){this.startPoint=t,this.control2=s,this.control1=a,this.endPoint=i,this.startWidth=l,this.endWidth=h}length(){let s=0,a,i;for(let l=0;l<=10;l+=1){const h=l/10,m=this.point(h,this.startPoint.x,this.control1.x,this.control2.x,this.endPoint.x),e=this.point(h,this.startPoint.y,this.control1.y,this.control2.y,this.endPoint.y);if(l>0){const n=m-a,_=e-i;s+=Math.sqrt(n*n+_*_)}a=m,i=e}return s}point(t,s,a,i,l){return s*(1-t)*(1-t)*(1-t)+3*a*(1-t)*(1-t)*t+3*i*(1-t)*t*t+l*t*t*t}}class mt{constructor(){try{this._et=new EventTarget}catch{this._et=document}}addEventListener(t,s,a){this._et.addEventListener(t,s,a)}dispatchEvent(t){return this._et.dispatchEvent(t)}removeEventListener(t,s,a){this._et.removeEventListener(t,s,a)}}function _t(x,t=250){let s=0,a=null,i,l,h;const m=()=>{s=Date.now(),a=null,i=x.apply(l,h),a||(l=null,h=[])};return function(...n){const _=Date.now(),p=t-(_-s);return l=this,h=n,p<=0||p>t?(a&&(clearTimeout(a),a=null),s=_,i=x.apply(l,h),a||(l=null,h=[])):a||(a=window.setTimeout(m,p)),i}}class N extends mt{constructor(t,s={}){super(),this.canvas=t,this._drawingStroke=!1,this._isEmpty=!0,this._lastPoints=[],this._data=[],this._lastVelocity=0,this._lastWidth=0,this._handleMouseDown=a=>{a.buttons===1&&this._strokeBegin(a)},this._handleMouseMove=a=>{this._strokeMoveUpdate(a)},this._handleMouseUp=a=>{a.buttons===1&&this._strokeEnd(a)},this._handleTouchStart=a=>{if(a.cancelable&&a.preventDefault(),a.targetTouches.length===1){const i=a.changedTouches[0];this._strokeBegin(i)}},this._handleTouchMove=a=>{a.cancelable&&a.preventDefault();const i=a.targetTouches[0];this._strokeMoveUpdate(i)},this._handleTouchEnd=a=>{if(a.target===this.canvas){a.cancelable&&a.preventDefault();const l=a.changedTouches[0];this._strokeEnd(l)}},this._handlePointerStart=a=>{a.preventDefault(),this._strokeBegin(a)},this._handlePointerMove=a=>{this._strokeMoveUpdate(a)},this._handlePointerEnd=a=>{this._drawingStroke&&(a.preventDefault(),this._strokeEnd(a))},this.velocityFilterWeight=s.velocityFilterWeight||.7,this.minWidth=s.minWidth||.5,this.maxWidth=s.maxWidth||2.5,this.throttle="throttle"in s?s.throttle:16,this.minDistance="minDistance"in s?s.minDistance:5,this.dotSize=s.dotSize||0,this.penColor=s.penColor||"black",this.backgroundColor=s.backgroundColor||"rgba(0,0,0,0)",this.compositeOperation=s.compositeOperation||"source-over",this._strokeMoveUpdate=this.throttle?_t(N.prototype._strokeUpdate,this.throttle):N.prototype._strokeUpdate,this._ctx=t.getContext("2d"),this.clear(),this.on()}clear(){const{_ctx:t,canvas:s}=this;t.fillStyle=this.backgroundColor,t.clearRect(0,0,s.width,s.height),t.fillRect(0,0,s.width,s.height),this._data=[],this._reset(this._getPointGroupOptions()),this._isEmpty=!0}fromDataURL(t,s={}){return new Promise((a,i)=>{const l=new Image,h=s.ratio||window.devicePixelRatio||1,m=s.width||this.canvas.width/h,e=s.height||this.canvas.height/h,n=s.xOffset||0,_=s.yOffset||0;this._reset(this._getPointGroupOptions()),l.onload=()=>{this._ctx.drawImage(l,n,_,m,e),a()},l.onerror=p=>{i(p)},l.crossOrigin="anonymous",l.src=t,this._isEmpty=!1})}toDataURL(t="image/png",s){switch(t){case"image/svg+xml":return typeof s!="object"&&(s=void 0),`data:image/svg+xml;base64,${btoa(this.toSVG(s))}`;default:return typeof s!="number"&&(s=void 0),this.canvas.toDataURL(t,s)}}on(){this.canvas.style.touchAction="none",this.canvas.style.msTouchAction="none",this.canvas.style.userSelect="none";const t=/Macintosh/.test(navigator.userAgent)&&"ontouchstart"in document;window.PointerEvent&&!t?this._handlePointerEvents():(this._handleMouseEvents(),"ontouchstart"in window&&this._handleTouchEvents())}off(){this.canvas.style.touchAction="auto",this.canvas.style.msTouchAction="auto",this.canvas.style.userSelect="auto",this.canvas.removeEventListener("pointerdown",this._handlePointerStart),this.canvas.removeEventListener("pointermove",this._handlePointerMove),this.canvas.ownerDocument.removeEventListener("pointerup",this._handlePointerEnd),this.canvas.removeEventListener("mousedown",this._handleMouseDown),this.canvas.removeEventListener("mousemove",this._handleMouseMove),this.canvas.ownerDocument.removeEventListener("mouseup",this._handleMouseUp),this.canvas.removeEventListener("touchstart",this._handleTouchStart),this.canvas.removeEventListener("touchmove",this._handleTouchMove),this.canvas.removeEventListener("touchend",this._handleTouchEnd)}isEmpty(){return this._isEmpty}fromData(t,{clear:s=!0}={}){s&&this.clear(),this._fromData(t,this._drawCurve.bind(this),this._drawDot.bind(this)),this._data=this._data.concat(t)}toData(){return this._data}_getPointGroupOptions(t){return{penColor:t&&"penColor"in t?t.penColor:this.penColor,dotSize:t&&"dotSize"in t?t.dotSize:this.dotSize,minWidth:t&&"minWidth"in t?t.minWidth:this.minWidth,maxWidth:t&&"maxWidth"in t?t.maxWidth:this.maxWidth,velocityFilterWeight:t&&"velocityFilterWeight"in t?t.velocityFilterWeight:this.velocityFilterWeight,compositeOperation:t&&"compositeOperation"in t?t.compositeOperation:this.compositeOperation}}_strokeBegin(t){if(!this.dispatchEvent(new CustomEvent("beginStroke",{detail:t,cancelable:!0})))return;this._drawingStroke=!0;const a=this._getPointGroupOptions(),i=Object.assign(Object.assign({},a),{points:[]});this._data.push(i),this._reset(a),this._strokeUpdate(t)}_strokeUpdate(t){if(!this._drawingStroke)return;if(this._data.length===0){this._strokeBegin(t);return}this.dispatchEvent(new CustomEvent("beforeUpdateStroke",{detail:t}));const s=t.clientX,a=t.clientY,i=t.pressure!==void 0?t.pressure:t.force!==void 0?t.force:0,l=this._createPoint(s,a,i),h=this._data[this._data.length-1],m=h.points,e=m.length>0&&m[m.length-1],n=e?l.distanceTo(e)<=this.minDistance:!1,_=this._getPointGroupOptions(h);if(!e||!(e&&n)){const p=this._addPoint(l,_);e?p&&this._drawCurve(p,_):this._drawDot(l,_),m.push({time:l.time,x:l.x,y:l.y,pressure:l.pressure})}this.dispatchEvent(new CustomEvent("afterUpdateStroke",{detail:t}))}_strokeEnd(t){this._drawingStroke&&(this._strokeUpdate(t),this._drawingStroke=!1,this.dispatchEvent(new CustomEvent("endStroke",{detail:t})))}_handlePointerEvents(){this._drawingStroke=!1,this.canvas.addEventListener("pointerdown",this._handlePointerStart),this.canvas.addEventListener("pointermove",this._handlePointerMove),this.canvas.ownerDocument.addEventListener("pointerup",this._handlePointerEnd)}_handleMouseEvents(){this._drawingStroke=!1,this.canvas.addEventListener("mousedown",this._handleMouseDown),this.canvas.addEventListener("mousemove",this._handleMouseMove),this.canvas.ownerDocument.addEventListener("mouseup",this._handleMouseUp)}_handleTouchEvents(){this.canvas.addEventListener("touchstart",this._handleTouchStart),this.canvas.addEventListener("touchmove",this._handleTouchMove),this.canvas.addEventListener("touchend",this._handleTouchEnd)}_reset(t){this._lastPoints=[],this._lastVelocity=0,this._lastWidth=(t.minWidth+t.maxWidth)/2,this._ctx.fillStyle=t.penColor,this._ctx.globalCompositeOperation=t.compositeOperation}_createPoint(t,s,a){const i=this.canvas.getBoundingClientRect();return new $(t-i.left,s-i.top,a,new Date().getTime())}_addPoint(t,s){const{_lastPoints:a}=this;if(a.push(t),a.length>2){a.length===3&&a.unshift(a[0]);const i=this._calculateCurveWidths(a[1],a[2],s),l=Z.fromPoints(a,i);return a.shift(),l}return null}_calculateCurveWidths(t,s,a){const i=a.velocityFilterWeight*s.velocityFrom(t)+(1-a.velocityFilterWeight)*this._lastVelocity,l=this._strokeWidth(i,a),h={end:l,start:this._lastWidth};return this._lastVelocity=i,this._lastWidth=l,h}_strokeWidth(t,s){return Math.max(s.maxWidth/(t+1),s.minWidth)}_drawCurveSegment(t,s,a){const i=this._ctx;i.moveTo(t,s),i.arc(t,s,a,0,2*Math.PI,!1),this._isEmpty=!1}_drawCurve(t,s){const a=this._ctx,i=t.endWidth-t.startWidth,l=Math.ceil(t.length())*2;a.beginPath(),a.fillStyle=s.penColor;for(let h=0;h<l;h+=1){const m=h/l,e=m*m,n=e*m,_=1-m,p=_*_,S=p*_;let T=S*t.startPoint.x;T+=3*p*m*t.control1.x,T+=3*_*e*t.control2.x,T+=n*t.endPoint.x;let C=S*t.startPoint.y;C+=3*p*m*t.control1.y,C+=3*_*e*t.control2.y,C+=n*t.endPoint.y;const W=Math.min(t.startWidth+n*i,s.maxWidth);this._drawCurveSegment(T,C,W)}a.closePath(),a.fill()}_drawDot(t,s){const a=this._ctx,i=s.dotSize>0?s.dotSize:(s.minWidth+s.maxWidth)/2;a.beginPath(),this._drawCurveSegment(t.x,t.y,i),a.closePath(),a.fillStyle=s.penColor,a.fill()}_fromData(t,s,a){for(const i of t){const{points:l}=i,h=this._getPointGroupOptions(i);if(l.length>1)for(let m=0;m<l.length;m+=1){const e=l[m],n=new $(e.x,e.y,e.pressure,e.time);m===0&&this._reset(h);const _=this._addPoint(n,h);_&&s(_,h)}else this._reset(h),a(l[0],h)}}toSVG({includeBackgroundColor:t=!1}={}){const s=this._data,a=Math.max(window.devicePixelRatio||1,1),i=0,l=0,h=this.canvas.width/a,m=this.canvas.height/a,e=document.createElementNS("http://www.w3.org/2000/svg","svg");if(e.setAttribute("xmlns","http://www.w3.org/2000/svg"),e.setAttribute("xmlns:xlink","http://www.w3.org/1999/xlink"),e.setAttribute("viewBox",`${i} ${l} ${h} ${m}`),e.setAttribute("width",h.toString()),e.setAttribute("height",m.toString()),t&&this.backgroundColor){const n=document.createElement("rect");n.setAttribute("width","100%"),n.setAttribute("height","100%"),n.setAttribute("fill",this.backgroundColor),e.appendChild(n)}return this._fromData(s,(n,{penColor:_})=>{const p=document.createElement("path");if(!isNaN(n.control1.x)&&!isNaN(n.control1.y)&&!isNaN(n.control2.x)&&!isNaN(n.control2.y)){const S=`M ${n.startPoint.x.toFixed(3)},${n.startPoint.y.toFixed(3)} C ${n.control1.x.toFixed(3)},${n.control1.y.toFixed(3)} ${n.control2.x.toFixed(3)},${n.control2.y.toFixed(3)} ${n.endPoint.x.toFixed(3)},${n.endPoint.y.toFixed(3)}`;p.setAttribute("d",S),p.setAttribute("stroke-width",(n.endWidth*2.25).toFixed(3)),p.setAttribute("stroke",_),p.setAttribute("fill","none"),p.setAttribute("stroke-linecap","round"),e.appendChild(p)}},(n,{penColor:_,dotSize:p,minWidth:S,maxWidth:T})=>{const C=document.createElement("circle"),W=p>0?p:(S+T)/2;C.setAttribute("r",W.toString()),C.setAttribute("cx",n.x.toString()),C.setAttribute("cy",n.y.toString()),C.setAttribute("fill",_),e.appendChild(C)}),e.outerHTML}}const pt=rt('<nav data-aos="fade-down" data-aos-duration="500" data-aos-delay="500" class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600"><div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4"><a href="/" class="flex items-center space-x-3 rtl:space-x-reverse"><img src="'+tt+'" class="h-8" alt="DOST Logo"><span class="self-center lg:text-2xl md:text-base sm:text-sm font-semibold whitespace-nowrap dark:text-white text-black">Department of Science and Technology </span></a></div></nav>',1),ft={class:"py-20 bg-gray-200"},bt=r("div",null,[r("img",{"data-aos":"zoom-in","data-aos-duration":"500","data-aos-delay":"500",class:"mx-auto lg:mb-5 sm:mb-0",style:{width:"30%",height:"30%"},src:tt,alt:".."})],-1),vt=r("span",{class:"font-black text-base lg:text-2xl md:text-base sm:text-sm","data-aos":"fade-down","data-aos-duration":"500","data-aos-delay":"500"},"CUSTOMER SATISFACTION FEEDBACK",-1),yt=r("br",null,null,-1),gt=r("a",{href:"#"},[r("img",{class:"rounded-t-lg",src:"/docs/images/blog/image-1.jpg",alt:""})],-1),xt={class:"p-5"},wt={href:"#"},kt={class:"mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"},Ct=r("br",null,null,-1),Et={key:0},St={key:1},Pt={key:2,class:"ml-3"},Vt={key:3,class:"ml-3"},Tt=r("p",{class:"mb-3 font-normal text-gray-700 dark:text-gray-400"},"This questionaire aims to solicit your honest assessment of our services. Please take a minute in filling out this form and help us serve you better.",-1),Dt={class:"border border-w-2 p-3 mb-5"},Ot=r("div",null,[w(" Other Informations ("),r("span",{class:"text-blue-500"},"Optional"),w(") ")],-1),Mt=r("label",{for:"bordered-checkbox-2",class:"w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"},"Person with Disability",-1),Ut=r("label",{for:"bordered-checkbox-3",class:"w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"},"Pregnant Woman",-1),Wt=r("label",{for:"bordered-checkbox-4",class:"w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"},"Senior Citizen",-1),At={key:3,class:"text-red-800 m-5",style:{"margin-left":"80px"}},Lt={style:{"text-transform":"uppercase"}},Ft=["value"],zt={class:"ml-2"},It=r("br",null,null,-1),Nt={key:0,class:"text-red-800"},Bt={class:"overflow-hidden mb-3"},$t=r("div",null,"How important is this attribute?",-1),qt={class:"ml-2 mb-3"},jt={key:0,class:"text-red-800"},Gt=r("div",{class:"p-3 font-bold text-lg"},[w("Considering your complete experience with our agency, how likely would you recommend our services to others? "),r("span",{class:"text-red-800"},"*")],-1),Rt={class:"ml-2 mb-3 mx-auto my-auto mb-5 d-flex justify-center text-center",style:{"margin-right":"50px","margin-left":"50px"}},Ht={key:0,class:"text-red-800"},Yt={class:"p-3 mt-0 font-bold text-lg"},Xt={key:0,class:"text-red-800"},Kt={key:1,class:"text-blue-400"},Zt={key:0,class:"text-red-800 p-5"},Jt=r("br",null,null,-1),Qt=r("div",{class:"p-3 mt-0 font-bold text-lg"},[w("Please indicate other important attribute/s which you think is important to your needs. ( "),r("span",{class:"text-blue-400"},"Optional"),w(" )")],-1),te=r("div",{class:"p-3 mt-0 font-bold text-lg"},[w("Please write your signature on the box. ( "),r("span",{class:"text-blue-400"},"Optional"),w(" )")],-1),ee={href:"/",class:"btn bg-secondary"},ie={__name:"Index",props:{cc_questions:Object,dimensions:Object,unit:Object,sub_unit:Object,unit_psto:Object,sub_unit_psto:Object,status:String,errors:Object,captcha_img:String},setup(x){const t=x,s=[{label:"1. I know what a CC is and I saw this office's CC.",value:"1"},{label:"2. I know what a CC is but I did NOT see this office's CC. ",value:"2"},{label:"3. I learned the CC when I saw this office's CC.",value:"3"},{label:"4. I do not know what a CC is  and I did not see one in this office.(Answer 'N/A' on CC2 and CC3)",value:"4"}],a=[{label:"1. Easy to see",value:"1"},{label:"2. Somewhat easy to see",value:"2"},{label:"3. Difficult to see",value:"3"},{label:"4. not Visible at all",value:"4"},{label:"5. N/A",value:"5"}],i=[{label:"1. Helped Very Much",value:"1"},{label:"2. Somewhat helped",value:"2"},{label:"3. Did not help",value:"3"},{label:"4. N/A",value:"4"}],l=[{label:"Very Satisfied",value:"5",icon:"mdi-emoticon-cool",color:"#FFEB3B"},{label:"Satisfied",value:"4",icon:"mdi-emoticon-happy",color:"#FFC107"},{label:"Neither",value:"3",icon:"mdi-emoticon-neutral",color:"#263238"},{label:"Dissatisfied",value:"2",icon:"mdi-emoticon-sad",color:"#F44336"},{label:"Very Dissatisfied",value:"1",icon:"mdi-emoticon-devil",color:"#6200EA"}],h=[{label:"5",value:"5"},{label:"4",value:"4"},{label:"3",value:"3"},{label:"2",value:"2"},{label:"1",value:"1"}],m=[{label:"10",value:"10"},{label:"9",value:"9"},{label:"8",value:"8"},{label:"7",value:"7"},{label:"6",value:"6"},{label:"5",value:"5"},{label:"4",value:"4"},{label:"3",value:"3"},{label:"2",value:"2"},{label:"1",value:"1"}],e=ot({region_id:null,service_id:null,unit_id:null,sub_unit_id:null,psto_id:null,client_type:null,sub_unit_type:null,email:null,name:null,sex:null,age_group:null,pwd:0,pregnant:0,senior_citizen:0,cc1:null,cc2:null,cc3:null,recommend_rate_score:null,comment:null,is_complaint:!1,indication:null,signature:null,dimension_form:{id:[],rate_score:[],importance_rate_score:[]},cc_form:{id:[],answer:[]},captcha:null,current_url:null,complaint_scanner:{value:[]}}),n=Q(!1),_=(g,d,D)=>{e.cc_form.id[g]=d,e.cc_form.answer[g]=D},p=(g,d)=>{e.dimension_form.id[g]=d},S=Q(null);document.querySelector(".signature-pad canvas"),nt(()=>{ht.init(),S.value=new N(S.value);const g=window.location.href,d=new URLSearchParams(g.split("?")[1]);e.region_id=d.get("region_id"),e.service_id=d.get("service_id"),e.unit_id=d.get("unit_id"),e.sub_unit_id=d.get("sub_unit_id"),e.psto_id=d.get("psto_id"),e.sub_unit_type=d.get("sub_unit_type"),e.current_url=g,B.fire({title:"Disclaimer",icon:"warning",text:"The DOST is committed to protect and respect your personal data privacy. All information collected will only be used for documentation purposes and will not be published in any platform."})});const T=async()=>{n.value=!0;const g=document.querySelector(".signature-pad");g.getContext("2d");const d=g.toDataURL();e.signature=d;let D=Math.random();const P=()=>'<img src="'+("/captcha/flat?rand="+D)+'" alt="CAPTCHA" style="display: block; margin: 0 auto; ">';try{B.fire({title:P(),html:'<div style="font-weight: bold; font-size:25px">Enter Captcha Code</div> <input id="captcha-input" class="swal2-input text-center"><p id="invalid-captcha-text" style="color: red; font-size: 14px; margin-top: 5px; display: none;">Invalid CAPTCHA code</p>',inputAttributes:{autocapitalize:"off"},showCancelButton:!0,confirmButtonText:"Submit",showLoaderOnConfirm:!0,preConfirm:()=>{const A=document.getElementById("captcha-input").value;return e.captcha=A,!0}}).then(A=>{A.isConfirmed&&ut.post("/csf_submission",e)})}catch{B.fire({title:"Failed",icon:"error",text:"Something went wrong pease check"})}},C=(g,d)=>{e.dimension_form.rate_score[g]<4?e.complaint_scanner.value[g]=!0:e.complaint_scanner.value[g]=!1,e.is_complaint=!1,e.complaint_scanner.value.forEach(D=>{if(D===!0){e.is_complaint=!0;return}})},W=()=>{new N(S.value)};return it(()=>t.errors.captcha,g=>{g&&B.fire({title:"Error Captcha",text:"Wrong captcha code!",icon:"error"})}),(g,d)=>{const D=E("v-card-title"),P=E("v-card"),A=E("v-text-field"),q=E("v-select"),O=E("v-col"),I=E("v-row"),j=E("v-checkbox"),et=E("v-icon"),z=E("v-btn"),G=E("v-btn-toggle"),J=E("v-chip"),at=E("v-divider"),R=E("v-textarea"),H=E("v-container"),st=E("v-form");return f(),y(U,null,[o(lt(dt),{title:"Survey Form"}),pt,o(P,{class:"container","data-aos":"fade-up","data-aos-duration":"2000","data-aos-delay":"500"},{default:c(()=>[o(I,{justify:"center",class:"py-3 bg-gray-200"},{default:c(()=>[o(O,{cols:"12",md:"8",sm:"6"},{default:c(()=>[o(st,{class:"max-w",onSubmit:ct(T,["prevent"])},{default:c(()=>[r("div",ft,[o(P,{class:"mb-3 md:mb-0 sm:mb-0 text-center"},{default:c(()=>[o(D,{class:"m-5 font-black flex flex-col items-center"},{default:c(()=>[bt,vt,yt]),_:1})]),_:1}),o(P,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto text-base md:text-base sm:text-sm"},{default:c(()=>[gt,r("div",xt,[r("a",wt,[r("h5",kt,[r("span",null,k(x.unit.data[0].unit_name),1),w(),Ct,x.sub_unit.data.length>0?(f(),y("span",Et,k(x.sub_unit.data[0].sub_unit_name),1)):V("",!0),x.unit_psto.data.length>0?(f(),y("span",St,k(x.unit_psto.data[0].psto.psto_name),1)):V("",!0),x.sub_unit_psto.data.length>0?(f(),y("span",Pt,k(x.sub_unit_psto.data[0].psto.psto_name),1)):V("",!0),e.sub_unit_type?(f(),y("span",Vt,k(e.sub_unit_type),1)):V("",!0)])]),Tt,r("div",null,[o(A,{modelValue:e.email,"onUpdate:modelValue":d[0]||(d[0]=u=>e.email=u),type:"email",placeholder:"email@gmail.com",label:"Email(Optional)",variant:"outlined"},null,8,["modelValue"]),o(A,{modelValue:e.name,"onUpdate:modelValue":d[1]||(d[1]=u=>e.name=u),placeholder:"Enter your full name",label:"Name(Optional)",variant:"outlined"},null,8,["modelValue"]),o(I,{class:"mb-5"},{default:c(()=>[o(O,{cols:"12",md:"",sm:"4",style:{"margin-bottom":"-23px"}},{default:c(()=>[o(q,{label:"Client_type*",variant:"outlined",modelValue:e.client_type,"onUpdate:modelValue":d[2]||(d[2]=u=>e.client_type=u),items:["Internal Employees","General Public","Government Employees","Business/Organization"],rules:[u=>!!u||x.errors.client_type||"This field is required"]},null,8,["modelValue","rules"])]),_:1}),o(O,{cols:"12",md:"",sm:"4",style:{"margin-bottom":"-23px"}},{default:c(()=>[o(q,{label:"Sex*",variant:"outlined",modelValue:e.sex,"onUpdate:modelValue":d[3]||(d[3]=u=>e.sex=u),items:["Male","Female"],rules:[u=>!!u||x.errors.sex||"This field is required"]},null,8,["modelValue","rules"])]),_:1}),o(O,{cols:"12",md:"",sm:"4",style:{"margin-bottom":"-23px"}},{default:c(()=>[o(q,{label:"Age Group*",variant:"outlined",modelValue:e.age_group,"onUpdate:modelValue":d[4]||(d[4]=u=>e.age_group=u),items:["15-19","20-29","30-39","40-49","50-59","60-69","70-79","80+"],rules:[u=>!!u||x.errors.sex||"This field is required"]},null,8,["modelValue","rules"])]),_:1})]),_:1}),r("div",Dt,[Ot,o(I,null,{default:c(()=>[o(O,{cols:"12",md:"",sm:"4",class:"flex items-center ps-4 rounded",style:{"margin-bottom":"-25px"}},{default:c(()=>[Y(r("input",{"onUpdate:modelValue":d[5]||(d[5]=u=>e.pwd=u),id:"bordered-checkbox-2",type:"checkbox",value:"",name:"bordered-checkbox",class:"w-4 h-4 text-blue-600 bg-gray-100 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"},null,512),[[X,e.pwd]]),Mt]),_:1}),o(O,{cols:"12",md:"",sm:"4",class:"flex items-center ps-4 rounded",style:{"margin-bottom":"-25px"}},{default:c(()=>[Y(r("input",{"onUpdate:modelValue":d[6]||(d[6]=u=>e.pregnant=u),id:"bordered-checkbox-3",type:"checkbox",value:"",name:"bordered-checkbox",class:"w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"},null,512),[[X,e.pregnant]]),Ut]),_:1}),o(O,{cols:"12",md:"",sm:"4",class:"flex items-center ps-4 rounded",style:{"margin-bottom":"-25px"}},{default:c(()=>[Y(r("input",{"onUpdate:modelValue":d[7]||(d[7]=u=>e.senior_citizen=u),id:"bordered-checkbox-4",type:"checkbox",value:"",name:"bordered-checkbox",class:"w-4 h-4 text-blue-600 bg-gray-100 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"},null,512),[[X,e.senior_citizen]]),Wt]),_:1})]),_:1})])])])]),_:1}),o(P,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto text-base sm:text-sm"},{default:c(()=>[(f(!0),y(U,null,F(x.cc_questions,(u,b)=>(f(),y("div",{key:b,class:"mb-10"},[o(D,{class:"m-5 font-black mb-10"},{default:c(()=>[w(k(u.title)+". "+k(u.question),1)]),_:2},1024),b==0?(f(),y(U,{key:0},F(s,(v,M)=>r("div",{key:M},[o(j,{onClick:L=>_(b,u.id,v.value),modelValue:e.cc1,"onUpdate:modelValue":d[8]||(d[8]=L=>e.cc1=L),color:"primary",style:{"margin-top":"-50px","margin-left":"7%","margin-bottom":"-5%"},label:v.label,value:v.label},null,8,["onClick","modelValue","label","value"])])),64)):V("",!0),b==1?(f(),y(U,{key:1},F(a,(v,M)=>r("div",{key:M},[o(j,{onClick:L=>_(b,u.id,v.value),modelValue:e.cc2,"onUpdate:modelValue":d[9]||(d[9]=L=>e.cc2=L),color:"primary",style:{"margin-top":"-50px","margin-left":"7%","margin-bottom":"-5%"},label:v.label,value:v.label},null,8,["onClick","modelValue","label","value"])])),64)):V("",!0),b==2?(f(),y(U,{key:2},F(i,(v,M)=>r("div",{key:M},[o(j,{onClick:L=>_(b,u.id,v.value),modelValue:e.cc3,"onUpdate:modelValue":d[10]||(d[10]=L=>e.cc3=L),color:"primary",style:{"margin-top":"-50px","margin-left":"7%","margin-bottom":"-5%"},label:v.label,value:v.value},null,8,["onClick","modelValue","label","value"])])),64)):V("",!0),n.value&&!e.cc_form.answer[b]?(f(),y("div",At,k("This selection is required"))):V("",!0)]))),128))]),_:1}),o(P,{"data-aos":"fade-left","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:c(()=>[o(D,{class:"text-lg text-white bg-blue_grey p-3"},{default:c(()=>[w(" HOW WOULD YOU RATE OUR "),r("span",Lt,k(x.unit.data[0].unit_name),1),w(" SERVICES? ")]),_:1}),r("div",null,[(f(!0),y(U,null,F(x.dimensions,(u,b)=>(f(),K(P,{"data-aos":"fade-left","data-aos-duration":"1000","data-aos-delay":"500",class:"text-center over-flowhidden scroll-none mb-1",border:"1",key:u.id},{default:c(()=>[o(D,{class:"text-4xl mt-5 mb-3 text-uppercase"},{default:c(()=>[w(k(u.name),1)]),_:2},1024),r("input",{type:"hidden",value:p(b,u.id)},null,8,Ft),r("div",zt,[(f(),y(U,null,F(l,v=>o(G,{class:"mb-5",modelValue:e.dimension_form.rate_score[b],"onUpdate:modelValue":M=>e.dimension_form.rate_score[b]=M,key:v.value,rules:[()=>n.value?!!e.dimension_form.rate_score[b]||"This selection is required":!0]},{default:c(()=>[o(z,{onClick:M=>C(b,e.dimension_form.rate_score[b]),rounded:"",class:"mr-2 bg-gray-200",value:v.value,color:"secondary"},{default:c(()=>[o(et,{color:e.dimension_form.rate_score[b]===v.value?v.color:"gray",size:"40"},{default:c(()=>[w(k(v.icon),1)]),_:2},1032,["color"]),It,r("label",null,k(v.label),1)]),_:2},1032,["onClick","value"])]),_:2},1032,["modelValue","onUpdate:modelValue","rules"])),64)),n.value&&!e.dimension_form.rate_score[b]?(f(),y("div",Nt,k("This selection is required"))):V("",!0)]),r("div",Bt,[$t,r("div",null,[r("div",qt,[(f(),y(U,null,F(h,v=>o(G,{modelValue:e.dimension_form.importance_rate_score[b],"onUpdate:modelValue":M=>e.dimension_form.importance_rate_score[b]=M,key:v.value,mandatory:""},{default:c(()=>[o(z,{class:"mr-2",value:v.value,color:"secondary",style:{"border-radius":"40%"}},{default:c(()=>[o(J,null,{default:c(()=>[r("label",null,k(v.label),1)]),_:2},1024)]),_:2},1032,["value"])]),_:2},1032,["modelValue","onUpdate:modelValue"])),64)),n.value&&!e.dimension_form.importance_rate_score[b]?(f(),y("div",jt,k("This selection is required"))):V("",!0)])])])]),_:2},1024))),128)),o(at)])]),_:1}),o(P,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:c(()=>[Gt,r("div",Rt,[(f(),y(U,null,F(m,u=>o(G,{modelValue:e.recommend_rate_score,"onUpdate:modelValue":d[11]||(d[11]=b=>e.recommend_rate_score=b),mandatory:"",key:u.value},{default:c(()=>[o(z,{value:u.value,class:"mr-2",color:(e.recommend_rate_score===u.color,"secondary"),style:{"border-radius":"40%"}},{default:c(()=>[o(J,null,{default:c(()=>[r("label",null,k(u.label),1)]),_:2},1024)]),_:2},1032,["value","color"])]),_:2},1032,["modelValue"])),64)),n.value&&!e.recommend_rate_score?(f(),y("div",Ht,k("This selection is required"))):V("",!0)])]),_:1}),o(P,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:c(()=>[r("div",Yt,[w("Please write your comment/suggestions below. "),e.is_complaint==!0?(f(),y("span",Xt,"*")):(f(),y("span",Kt,"(Optional)"))]),o(H,{fluid:""},{default:c(()=>[e.is_complaint==!0?(f(),K(R,{key:0,modelValue:e.comment,"onUpdate:modelValue":d[12]||(d[12]=u=>e.comment=u),placeholder:"Input here!",rules:[u=>!!u||n.value&&!e.comment||"This field is required"]},null,8,["modelValue","rules"])):e.is_complaint==!1?(f(),K(R,{key:1,modelValue:e.comment,"onUpdate:modelValue":d[13]||(d[13]=u=>e.comment=u),placeholder:"Input here"},null,8,["modelValue"])):V("",!0)]),_:1}),n.value&&e.is_complaint==!0?(f(),y("div",Zt,[w(k("This selection is required because you rate low to our services with the options above."),1),Jt,w(" Please input the reason/s why you have rated low.")])):V("",!0)]),_:1}),o(P,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:c(()=>[Qt,o(H,{fluid:""},{default:c(()=>[o(R,{modelValue:e.indication,"onUpdate:modelValue":d[14]||(d[14]=u=>e.indication=u),placeholder:"Input here"},null,8,["modelValue"])]),_:1})]),_:1}),o(P,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:c(()=>[te,o(H,{class:"text-center"},{default:c(()=>[o(I,null,{default:c(()=>[o(O,null,{default:c(()=>[r("div",null,[r("canvas",{class:"signature-pad mb-3 mx-auto",ref_key:"signaturePad",ref:S},null,512)]),o(z,{onClick:W,class:""},{default:c(()=>[w("Clear")]),_:1})]),_:1})]),_:1})]),_:1})]),_:1}),o(P,{"data-aos":"zoom-out-up","data-aos-duration":"1000","data-aos-delay":"500",class:"mb-5 mx-auto"},{default:c(()=>[o(I,{class:"mt-5 mb-5 text-center"},{default:c(()=>[o(O,{cols:"6",class:"text-right"},{default:c(()=>[r("a",ee,[o(z,{class:"bg-secondary"},{default:c(()=>[w("Back")]),_:1})])]),_:1}),o(O,{cols:"6",class:"text-left"},{default:c(()=>[o(z,{color:"success",type:"submit",class:"mr-2","prepend-icon":"mdi-send",disabled:e.processing||e.is_complaint&&!e.comment},{default:c(()=>[w("Submit")]),_:1},8,["disabled"])]),_:1})]),_:1})]),_:1})])]),_:1})]),_:1})]),_:1})]),_:1})],64)}}};export{ie as default};
