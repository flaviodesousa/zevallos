var gadgets=gadgets||{};
gadgets.util=function(){function f(){var k;
var j=document.location.href;
var h=j.indexOf("?");
var i=j.indexOf("#");
if(i===-1){k=j.substr(h+1)
}else{k=[j.substr(h+1,i-h-1),"&",j.substr(i+1)].join("")
}return k.split("&")
}var d=null;
var c={};
var e=[];
var a={0:false,10:true,13:true,34:true,39:true,60:true,62:true,92:true,8232:true,8233:true};
function b(h,i){return String.fromCharCode(i)
}function g(h){c=h["core.util"]||{}
}if(gadgets.config){gadgets.config.register("core.util",null,g)
}return{getUrlParameters:function(){if(d!==null){return d
}d={};
var m=f();
var p=window.decodeURIComponent?decodeURIComponent:unescape;
for(var k=0,h=m.length;
k<h;
++k){var o=m[k].indexOf("=");
if(o===-1){continue
}var n=m[k].substring(0,o);
var l=m[k].substring(o+1);
l=l.replace(/\+/g," ");
d[n]=p(l)
}return d
},makeClosure:function(m,o,n){var l=[];
for(var k=2,h=arguments.length;
k<h;
++k){l.push(arguments[k])
}return function(){var p=l.slice();
for(var r=0,q=arguments.length;
r<q;
++r){p.push(arguments[r])
}return o.apply(m,p)
}
},makeEnum:function(j){var l={};
for(var k=0,h;
h=j[k];
++k){l[h]=h
}return l
},getFeatureParameters:function(h){return typeof c[h]==="undefined"?null:c[h]
},hasFeature:function(h){return typeof c[h]!=="undefined"
},registerOnLoadHandler:function(h){e.push(h)
},runOnLoadHandlers:function(){for(var k=0,h=e.length;
k<h;
++k){e[k]()
}},escape:function(h,n){if(!h){return h
}else{if(typeof h==="string"){return gadgets.util.escapeString(h)
}else{if(typeof h==="array"){for(var m=0,k=h.length;
m<k;
++m){h[m]=gadgets.util.escape(h[m])
}}else{if(typeof h==="object"&&n){var l={};
for(var o in h){if(h.hasOwnProperty(o)){l[gadgets.util.escapeString(o)]=gadgets.util.escape(h[o],true)
}}return l
}}}}return h
},escapeString:function(n){var k=[],m,o;
for(var l=0,h=n.length;
l<h;
++l){m=n.charCodeAt(l);
o=a[m];
if(o===true){k.push("&#",m,";")
}else{if(o!==false){k.push(n.charAt(l))
}}}return k.join("")
},unescapeString:function(h){return h.replace(/&#([0-9]+);/g,b)
}}
}();
gadgets.util.getUrlParameters();
var gadgets=gadgets||{};
gadgets.json=function(){function f(n){return n<10?"0"+n:n
}Date.prototype.toJSON=function(){return[this.getUTCFullYear(),"-",f(this.getUTCMonth()+1),"-",f(this.getUTCDate()),"T",f(this.getUTCHours()),":",f(this.getUTCMinutes()),":",f(this.getUTCSeconds()),"Z"].join("")
};
var m={"\b":"\\b","\t":"\\t","\n":"\\n","\f":"\\f","\r":"\\r",'"':'\\"',"\\":"\\\\"};
function stringify(value){var a,i,k,l,r=/["\\\x00-\x1f\x7f-\x9f]/g,v;
switch(typeof value){case"string":return r.test(value)?'"'+value.replace(r,function(a){var c=m[a];
if(c){return c
}c=a.charCodeAt();
return"\\u00"+Math.floor(c/16).toString(16)+(c%16).toString(16)
})+'"':'"'+value+'"';
case"number":return isFinite(value)?String(value):"null";
case"boolean":case"null":return String(value);
case"object":if(!value){return"null"
}a=[];
if(typeof value.length==="number"&&!(value.propertyIsEnumerable("length"))){l=value.length;
for(i=0;
i<l;
i+=1){a.push(stringify(value[i])||"null")
}return"["+a.join(",")+"]"
}for(k in value){if(value.hasOwnProperty(k)){if(typeof k==="string"){v=stringify(value[k]);
if(v){a.push(stringify(k)+":"+v)
}}}}return"{"+a.join(",")+"}"
}}return{stringify:stringify,parse:function(text){if(/^[\],:{}\s]*$/.test(text.replace(/\\["\\\/b-u]/g,"@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,"]").replace(/(?:^|:|,)(?:\s*\[)+/g,""))){return eval("("+text+")")
}return false
}}
}();
var gadgets=gadgets||{};
gadgets.rpc=function(){var n="__cb";
var l="";
var y="__g2c_rpc";
var e="__c2g_rpc";
var b={};
var s=[];
var c={};
var q={};
var h={};
var j=0;
var z={};
var p={};
var d={};
var x={};
if(gadgets.util){x=gadgets.util.getUrlParameters()
}h[".."]=x.rpctoken||x.ifpctok||0;
function u(){return typeof window.postMessage==="function"?"wpm":typeof document.postMessage==="function"?"dpm":navigator.product==="Gecko"?"fe":"ifpc"
}function w(){if(g==="dpm"||g==="wpm"){window.addEventListener("message",function(A){o(gadgets.json.parse(A.data))
},false)
}}var g=u();
w();
b[l]=function(){throw new Error("Unknown RPC service: "+this.s)
};
b[n]=function(B,A){var C=z[B];
if(C){delete z[B];
C(A)
}};
function k(A){if(p[A]){return
}if(g==="fe"){try{var C=document.getElementById(A);
C[y]=function(D){o(gadgets.json.parse(D))
}
}catch(B){}}p[A]=true
}function r(C){var E=gadgets.json.stringify;
var A=[];
for(var D=0,B=C.length;
D<B;
++D){A.push(encodeURIComponent(E(C[D])))
}return A.join("&")
}function o(B){if(B&&typeof B.s==="string"&&typeof B.f==="string"&&B.a instanceof Array){if(h[B.f]){if(h[B.f]!=B.t){throw new Error("Invalid auth token.")
}}if(B.c){B.callback=function(C){gadgets.rpc.call(B.f,n,null,B.c,C)
}
}var A=(b[B.s]||b[l]).apply(B,B.a);
if(B.c&&typeof A!="undefined"){gadgets.rpc.call(B.f,n,null,B.c,A)
}}}function a(B,C,H,D,F){try{if(H!=".."){var A=window.frameElement;
if(typeof A[y]==="function"){if(typeof A[y][e]!=="function"){A[y][e]=function(I){o(gadgets.json.parse(I))
}
}A[y](D);
return
}}else{var G=document.getElementById(B);
if(typeof G[y]==="function"&&typeof G[y][e]==="function"){G[y][e](D);
return
}}}catch(E){}v(B,C,H,D,F)
}function v(A,B,G,C,D){var F=gadgets.rpc.getRelayUrl(A);
if(!F){throw new Error("No relay file assigned for IFPC")
}var E=null;
if(q[A]){E=[F,"#",r([G,j,1,0,r([G,B,"","",G].concat(D))])].join("")
}else{E=[F,"#",A,"&",G,"@",j,"&1&0&",encodeURIComponent(C)].join("")
}i(E)
}function i(D){var B;
for(var A=s.length-1;
A>=0;
--A){var E=s[A];
try{if(E&&(E.recyclable||E.readyState==="complete")){E.parentNode.removeChild(E);
if(window.ActiveXObject){s[A]=E=null;
s.splice(A,1)
}else{E.recyclable=false;
B=E;
break
}}}catch(C){}}if(!B){B=document.createElement("iframe");
B.style.border=B.style.width=B.style.height="0px";
B.style.visibility="hidden";
B.style.position="absolute";
B.onload=function(){this.recyclable=true
};
s.push(B)
}B.src=D;
setTimeout(function(){document.body.appendChild(B)
},0)
}function f(B,D){if(typeof d[B]==="undefined"){d[B]=false;
var C=null;
if(B===".."){C=parent
}else{C=frames[B]
}try{d[B]=C.gadgets.rpc.receiveSameDomain
}catch(A){}}if(typeof d[B]==="function"){d[B](D);
return true
}return false
}if(gadgets.config){function t(A){if(A.rpc.parentRelayUrl.substring(0,7)==="http://"){c[".."]=A.rpc.parentRelayUrl
}else{var E=document.location.search.substring(0).split("&");
var D="";
for(var B=0,C;
C=E[B];
++B){if(C.indexOf("parent=")===0){D=decodeURIComponent(C.substring(7));
break
}}c[".."]=D+A.rpc.parentRelayUrl
}q[".."]=!!A.rpc.useLegacyProtocol
}var m={parentRelayUrl:gadgets.config.NonEmptyStringValidator};
gadgets.config.register("rpc",m,t)
}return{register:function(B,A){if(B==n){throw new Error("Cannot overwrite callback service")
}if(B==l){throw new Error("Cannot overwrite default service:"+" use registerDefault")
}b[B]=A
},unregister:function(A){if(A==n){throw new Error("Cannot delete callback service")
}if(A==l){throw new Error("Cannot delete default service:"+" use unregisterDefault")
}delete b[A]
},registerDefault:function(A){b[""]=A
},unregisterDefault:function(){delete b[""]
},call:function(H,D,I,G){++j;
H=H||"..";
if(I){z[j]=I
}var F="..";
if(H===".."){F=window.name
}var C={s:D,f:F,c:I?j:0,a:Array.prototype.slice.call(arguments,3),t:h[H]};
if(f(H,C)){return
}var A=gadgets.json.stringify(C);
var B=g;
if(q[H]){B="ifpc"
}switch(B){case"dpm":var J=H===".."?parent.document:frames[H].document;
J.postMessage(A);
break;
case"wpm":var E=H===".."?parent:frames[H];
E.postMessage(A,"*");
break;
case"fe":a(H,D,F,A,C.a);
break;
default:v(H,D,F,A,C.a);
break
}},getRelayUrl:function(A){return c[A]
},setRelayUrl:function(B,A,C){c[B]=A;
q[B]=!!C
},setAuthToken:function(A,B){h[A]=B;
k(A)
},getRelayChannel:function(){return g
},receive:function(A){if(A.length>4){o(gadgets.json.parse(decodeURIComponent(A[A.length-1])))
}},receiveSameDomain:function(A){A.a=Array.prototype.slice.call(A.a);
window.setTimeout(function(){o(A)
},0)
}}
}();
(function(){if(typeof(window.LinkedIn)=="undefined"){window.LinkedIn={}
}LinkedIn.Badges=(function(){var e,j;
e=function(){var m,o,k,n,p=false,l;
if(window.location.href.match(/^https:\/\//)){LinkedIn.Badges.Config.protocol="https"
}l=0;
m=a(LinkedIn.Badges.Config.match_class.profilepop,null,document);
o=0;
while(node=m[o++]){if(LinkedIn.Badges.Config.match_urls.profile.test(node.href)){k=new LinkedIn.Badges.ProfilePopup(node);
l++
}continue
}m=a(LinkedIn.Badges.Config.match_class.profileinline,null,document);
o=0;
while(node=m[o++]){if(LinkedIn.Badges.Config.match_urls.profile.test(node.href)){k=new LinkedIn.Badges.ProfileInline(node);
l++
}}LinkedIn.Badges.trackTotals({widget_count:l});
LinkedIn.Badges.Util.addListener(document,"click",function(q){LinkedIn.Badges.hideAllPopups()
})
};
j={};
j.init=e;
return j
})();
LinkedIn.Badges.trackTotals=function(k){var e,j=LinkedIn.Badges.Config.protocol+"://"+LinkedIn.Badges.Config.overrides.tracking_domain+"/analytics"+"?type=widgetJSTracking&trk={HOST}&ct={WIDGETCOUNT}&wt=pprofile4";
j=j.replace(/\{HOST\}/,escape(window.location.href)).replace(/\{WIDGETCOUNT\}/,escape(k.widget_count));
e=document.createElement("img");
e.setAttribute("src",j)
};
LinkedIn.Badges.ids={ProfilePopup:{}};
LinkedIn.Badges.ProfileInline=function(k){var e,m,j,l;
e=LinkedIn.Badges.Config.query_urls.profile.replace("{PROTOCOL}",LinkedIn.Badges.Config.protocol).replace("{DOMAIN}",LinkedIn.Badges.Config.overrides.domain).replace("{INPUB}",k.href.match(LinkedIn.Badges.Config.match_urls.profile)[1]).replace("{NAME}",k.href.match(LinkedIn.Badges.Config.match_urls.profile)[2])+"?trk={HOST}&widget=1&type=inline#rpctoken={RPCTOKEN}&rpcname={RPCNAME}";
m=LinkedIn.Badges.Util.getIFrame(k);
m.style.position="relative";
j=LinkedIn.Badges.Util.generateUUID();
l=e.replace("{RPCTOKEN}",j).replace("{RPCNAME}",m.name).replace("{HOST}",escape(window.location.href));
m.style.width="1px";
m.style.height="1px";
gadgets.rpc.setAuthToken(m.name,j);
m.src=l;
k.parentNode.removeChild(k)
};
LinkedIn.Badges.ProfilePopup=function(o){var k,s,q,j,p,r,l,m,e,n;
if(!o.id){o.id=LinkedIn.Badges.Util.generateId()
}o.style.paddingRight="16px";
o.style.backgroundImage="url("+LinkedIn.Badges.Config.in_btn_url.replace("{PROTOCOL}",LinkedIn.Badges.Config.protocol).replace("{DOMAIN}",LinkedIn.Badges.Config.overrides.img_domain)+")";
o.style.backgroundRepeat="no-repeat";
o.style.backgroundPosition="right bottom";
k=LinkedIn.Badges.Config.query_urls.profile.replace("{PROTOCOL}",LinkedIn.Badges.Config.protocol).replace("{DOMAIN}",LinkedIn.Badges.Config.overrides.domain).replace("{INPUB}",o.href.match(LinkedIn.Badges.Config.match_urls.profile)[1]).replace("{NAME}",o.href.match(LinkedIn.Badges.Config.match_urls.profile)[2])+"?trk={HOST}&widget=1&type=popup#x={X}&y={Y}&vx={VX}&vy={VY}&px={PX}&py={PY}&rpctoken={RPCTOKEN}&rpcname={RPCNAME}";
LinkedIn.Badges.Util.addListener(o,"click",function(w){var x,v,t,u;
LinkedIn.Badges.hideAllPopups();
LinkedIn.Badges.Util.preventDefault(w);
LinkedIn.Badges.Util.stopPropagation(w);
v=d(w);
x=LinkedIn.Badges.Util.getIFrame(document.body.firstChild);
t=LinkedIn.Badges.Util.generateUUID();
u=k.replace("{X}",v[0]).replace("{Y}",v[1]).replace("{VX}",getViewportWidth()).replace("{VY}",getViewportHeight()).replace("{PX}",(document.height||document.body.offsetHeight)).replace("{PY}",(document.width||document.body.offsetWidth)).replace("{RPCTOKEN}",t).replace("{RPCNAME}",x.name).replace("{HOST}",escape(window.location.href));
LinkedIn.Badges.ids.ProfilePopup[x.name]=true;
x.style.width="1px";
x.style.height="1px";
x.style.zIndex="999";
gadgets.rpc.setAuthToken(x.name,t);
x.src=u
})
};
LinkedIn.Badges.hideAllPopups=function(){for(var e in LinkedIn.Badges.ids.ProfilePopup){if(LinkedIn.Badges.ids.ProfilePopup.hasOwnProperty(e)){node=document.getElementById(e);
node.parentNode.removeChild(node);
delete LinkedIn.Badges.ids.ProfilePopup[e]
}}};
LinkedIn.Badges.ProfilePopup.resizeFrame=function(e){LinkedIn.Badges.Util.resizeIFrame("popup",this.f,e)
};
LinkedIn.Badges.ProfilePopup.repositionFrame=function(m){var j,q,n,l,k,e,p,r,o;
j=document.getElementById(this.f);
if(!j){return
}event_x=m[0];
event_y=m[1];
l=m[2];
k=m[3];
r=(document.height||document.body.offsetHeight);
o=(document.width||document.body.offsetWidth);
e=(j.style.width).replace(/px/,"");
p=(j.style.height).replace(/px/,"");
if(l){q=event_x-e+LinkedIn.Badges.Config.offsets.invert_x
}else{q=event_x+LinkedIn.Badges.Config.offsets.x
}if(k){n=event_y-p+LinkedIn.Badges.Config.offsets.invert_y
}else{n=event_y+LinkedIn.Badges.Config.offsets.y
}j.style.left=q+"px";
j.style.top=n+"px"
};
LinkedIn.Badges.ProfilePopup.addChrome=function(n){var o,m,l,j,k,e,j;
o=document.createElement("div");
m=document.createElement("div");
l=document.createElement("div");
j=document.createElement("div");
k=document.createElement("div");
k.style.height="1px";
k.style.width="1px";
k.style.clear="both";
k.style.visibility="hidden";
e=document.createElement("span");
e.appendChild(document.createTextNode("Close"));
LinkedIn.Badges.Util.addListener(e,"click",function(p){LinkedIn.Badges.Util.preventDefault(p);
LinkedIn.Badges.Util.stopPropagation(p);
gadgets.rpc.call("..","close_profileiframe",null,null)
});
LinkedIn.Badges.Util.addClass(o,"chrome-top");
LinkedIn.Badges.Util.addClass(m,"chrome-mid");
LinkedIn.Badges.Util.addClass(l,"chrome-bot");
LinkedIn.Badges.Util.addClass(e,"close");
LinkedIn.Badges.Util.addClass(j,"carat");
LinkedIn.Badges.Util.addClass(n,"linkedin-public-profile-with-chrome");
while(n.firstChild){m.appendChild(n.firstChild)
}n.appendChild(o);
o.appendChild(e);
n.appendChild(m);
m.appendChild(k);
n.appendChild(l);
n.appendChild(j)
};
LinkedIn.Badges.Config={initial_url:null,page_is_widget:false,match_class:{profilepop:"linkedin-profileinsider-popup",profileinline:"linkedin-profileinsider-inline"},match_urls:{profile:new RegExp("^http(?:s)?://(?:.*)/(in|pub)/(.*$)")},query_urls:{profile:"{PROTOCOL}://{DOMAIN}/{INPUB}/{NAME}"},offsets:{x:-30,invert_x:40,y:10,invert_y:0},popup_padding:{x:15,y:25},link_url:"http://www.linkedin.com",in_btn_url:"{PROTOCOL}://{DOMAIN}/img/icon/icon_company_insider_in_12x12.gif",protocol:"http",overrides:{domain:"www.linkedin.com",img_domain:"static.linkedin.com",tracking_domain:"www.linkedin.com"}};
LinkedIn.Badges.Util={generateId_counter:0,generateId:function(){return"linkedin_badge_gen_"+(LinkedIn.Badges.Util.generateId_counter++)
},getIFrame:function(k){var m,j;
m=LinkedIn.Badges.Util.generateId();
try{j=document.createElement('<iframe allowtransparency=true frameborder="0" scrolling="no">')
}catch(l){j=document.createElement("iframe")
}j.id=m;
j.style.position="absolute";
j.style.border=0;
j.style.overflow="hidden";
j.style.backgroundColor="transparent";
j.name=m;
k.parentNode.insertBefore(j,k);
return j
},resizeIFrame:function(l,n,j){var m,k,e;
m=document.getElementById(n);
if(!m){return
}k=j[0];
e=j[1];
m.style.width=k+"px";
m.style.height=e+"px"
},generateUUID:(function(){var e="0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
return function(j){var l=[],k;
if(j){for(var k=0;
k<j;
k++){l[k]=e[0|Math.random()*62]
}return l.join("")
}else{return LinkedIn.Badges.Util.generateUUID(32)
}}
})(),addListener:function(k,j,e){if(window.addEventListener){k.addEventListener(j,e,false)
}else{k.attachEvent("on"+j,e)
}return true
},stopPropagation:function(j){if(!j){var j=window.event
}j.cancelBubble=true;
if(j.stopPropagation){j.stopPropagation()
}},preventDefault:function(k){try{k.preventDefault()
}catch(j){k.returnValue=false
}return false
},hasClass:function(j,e){return getClassNameRegex(e).test(j.className)
},removeClass:function(j,e){return j.className.replace(getClassNameRegex(e),"")
},addClass:function(j,e){if(!LinkedIn.Badges.Util.hasClass(j,e)){j.className=[j.className,e].join(" ")
}}};
getClassNameRegex=function(e){return new RegExp(("(^|\\s)"+e+"(\\s|$)"),"i")
};
getViewportWidth=function(){return self["innerWidth"]||(document.documentElement["clientWidth"]||document.body["clientWidth"])
};
getViewportHeight=function(){return self["innerHeight"]||(document.documentElement["clientHeight"]||document.body["clientHeight"])
};
var d=function(k){var j=[];
if(!k){k=window.event
}if(k){if(k.pageX||k.pageY){j[0]=k.pageX;
j[1]=k.pageY
}else{if(document.documentElement&&document.documentElement.scrollTop){j[0]=k.clientX+document.documentElement.scrollLeft;
j[1]=k.clientY+document.documentElement.scrollTop
}else{if(k.clientX||k.clientY){j[0]=k.clientX+document.body.scrollLeft;
j[1]=k.clientY+document.body.scrollTop
}}}}return j
};
var a=function(j,e,k){if(document.getElementsByClassName){a=function(q,t,p){p=p||document;
var l=p.getElementsByClassName(q),s=(t)?new RegExp("\\b"+t+"\\b","i"):null,m=[],o;
for(var n=0,r=l.length;
n<r;
n+=1){o=l[n];
if(!s||s.test(o.nodeName)){m.push(o)
}}return m
}
}else{if(document.evaluate){a=function(u,x,t){x=x||"*";
t=t||document;
var n=u.split(" "),v="",r="http://www.w3.org/1999/xhtml",w=(document.documentElement.namespaceURI===r)?r:null,o=[],l,m;
for(var p=0,q=n.length;
p<q;
p+=1){v+="[contains(concat(' ', @class, ' '), ' "+n[p]+" ')]"
}try{l=document.evaluate(".//"+x+v,t,w,0,null)
}catch(s){l=document.evaluate(".//"+x+v,t,null,0,null)
}while((m=l.iterateNext())){o.push(m)
}return o
}
}else{a=function(y,B,x){B=B||"*";
x=x||document;
var r=y.split(" "),A=[],n=(B==="*"&&x.all)?x.all:x.getElementsByTagName(B),w,t=[],v;
for(var s=0,o=r.length;
s<o;
s+=1){A.push(new RegExp("(^|\\s)"+r[s]+"(\\s|$)"))
}for(var q=0,z=n.length;
q<z;
q+=1){w=n[q];
v=false;
for(var p=0,u=A.length;
p<u;
p+=1){v=A[p].test(w.className);
if(!v){break
}}if(v){t.push(w)
}}return t
}
}}return a(j,e,k)
};
var f=(function(){var m=false;
var n=false;
var l=navigator.userAgent.toLowerCase();
var e={version:(l.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/)||[])[1],safari:/webkit/.test(l),opera:/opera/.test(l),msie:/msie/.test(l)&&!(/opera/.test(l)),mozilla:/mozilla/.test(l)&&!(/(compatible|webkit)/.test(l))};
var j=function(o){if(m){return
}m=true;
o.apply(window)
};
var k=function(o){if(m){return o.apply(window)
}if(n){return window.setTimeout(function(){k(o)
},0)
}n=true;
if(document.addEventListener&&!e.opera){document.addEventListener("DOMContentLoaded",function(){j(o)
},false)
}if(e.msie&&window==top){(function(){if(m){return j(o)
}try{document.documentElement.doScroll("left")
}catch(q){setTimeout(arguments.callee,0);
return
}j(o)
})()
}if(e.opera){document.addEventListener("DOMContentLoaded",function(){if(m){return
}for(var q=0;
q<document.styleSheets.length;
q++){if(document.styleSheets[q].disabled){setTimeout(arguments.callee,0);
return
}}j(o)
},false)
}if(e.safari){var p;
(function(){if(m){return
}if(document.readyState!="loaded"&&document.readyState!="complete"){setTimeout(arguments.callee,0);
return
}if(p===undefined){p=(function(){var q=document.getElementsByTagName("style");
var s=[];
var t=q.length;
for(var r=0;
r<t;
q++){if(q[r].rel=="stylesheet"){s.push(q[r])
}}return s
}).length
}if(document.styleSheets.length!=p){setTimeout(arguments.callee,0);
return
}j(o)
})()
}LinkedIn.Badges.Util.addListener(window,"load",function(){j(o)
})
};
return k
})();
f(function(){if(!LinkedIn.Badges.Config.acts_as_widget){gadgets.rpc.register("resize_profileiframe",LinkedIn.Badges.ProfilePopup.resizeFrame);
gadgets.rpc.register("reposition_profileiframe",LinkedIn.Badges.ProfilePopup.repositionFrame);
gadgets.rpc.register("close_profileiframe",LinkedIn.Badges.hideAllPopups);
LinkedIn.Badges.init()
}});
LinkedIn.Badges.Util.addListener(window,"load",function(){if(!LinkedIn.Badges.Config.acts_as_widget){return
}var l,m;
l=document.getElementById("pprofile");
m=gadgets.util.getUrlParameters();
if(window!=window.top){window.name=m.rpcname;
var k=0,u=0;
if(m.x){k=LinkedIn.Badges.Config.popup_padding.x;
u=LinkedIn.Badges.Config.popup_padding.y;
LinkedIn.Badges.ProfilePopup.addChrome(l)
}u=u+Math.max(l.offsetHeight,l.scrollHeight);
k=k+Math.max(l.offsetWidth,l.scrollWidth);
m.trk=unescape(m.trk);
if(m.trk.indexOf("?")==-1){m.trk=m.trk+"?"
}else{m.trk=m.trk+"&"
}m.trk=m.trk+"li_rand="+parseInt(new Date().getTime().toString().substring(0,10),10)+"_"+Math.random();
m.trk=m.trk+"&liwidgetmode=true";
gadgets.rpc.setRelayUrl("..",m.trk);
gadgets.rpc.call("..","resize_profileiframe",null,[k,u]);
if(!m.x){return
}var s=m.x*1,p=m.y*1,q=m.vx,o=m.vy,t=m.px,r=m.py,n=k+LinkedIn.Badges.Config.popup_padding.x,e=u+LinkedIn.Badges.Config.popup_padding.y,j={x:false,y:false};
if(s+n>t&&s-n>0){j.x=true;
LinkedIn.Badges.Util.addClass(l,"carat-right")
}else{LinkedIn.Badges.Util.addClass(l,"carat-left")
}if(p-e>0){j.y=true;
LinkedIn.Badges.Util.addClass(l,"carat-bottom")
}else{LinkedIn.Badges.Util.addClass(l,"carat-top")
}gadgets.rpc.call("..","reposition_profileiframe",null,[s,p,j.x,j.y])
}});
if(window.location.href.indexOf("liwidgetmode=true")!=-1){var c=window.location.href,b,i,h;
b=c.substr(c.indexOf("#")+1).split("&");
try{i=b[0]===".."?window.parent.parent:window.parent.frames[b[0]];
h=i.gadgets.rpc.receive
}catch(g){}h&&h(b);
try{window.stop()
}catch(g){}try{document.execCommand("Stop")
}catch(g){}}})();