/*Encription*/
var Hidden={};Hidden.Cipher=function(e,a){var d=4;var h=a.length/d-1;var g=[[],[],[],[]];for(var f=0;f<4*d;f++){g[f%4][Math.floor(f/4)]=e[f]}g=Hidden.AddRoundKey(g,a,0,d);for(var c=1;c<h;c++){g=Hidden.SubBytes(g,d);g=Hidden.ShiftRows(g,d);g=Hidden.MixColumns(g,d);g=Hidden.AddRoundKey(g,a,c,d)}g=Hidden.SubBytes(g,d);g=Hidden.ShiftRows(g,d);g=Hidden.AddRoundKey(g,a,h,d);var b=new Array(4*d);for(var f=0;f<4*d;f++){b[f]=g[f%4][Math.floor(f/4)]}return b};Hidden.KeyExpansion=function(f){var d=4;var b=f.length/4;var g=b+6;var e=new Array(d*(g+1));var h=new Array(4);for(var c=0;c<b;c++){var a=[f[4*c],f[4*c+1],f[4*c+2],f[4*c+3]];e[c]=a}for(var c=b;c<(d*(g+1));c++){e[c]=new Array(4);for(var j=0;j<4;j++){h[j]=e[c-1][j]}if(c%b==0){h=Hidden.SubWord(Hidden.RotWord(h));for(var j=0;j<4;j++){h[j]^=Hidden.Rcon[c/b][j]}}else{if(b>6&&c%b==4){h=Hidden.SubWord(h)}}for(var j=0;j<4;j++){e[c][j]=e[c-b][j]^h[j]}}return e};Hidden.SubBytes=function(b,a){for(var d=0;d<4;d++){for(var e=0;e<a;e++){b[d][e]=Hidden.Sbox[b[d][e]]}}return b};Hidden.ShiftRows=function(d,a){var b=new Array(4);for(var e=1;e<4;e++){for(var f=0;f<4;f++){b[f]=d[e][(f+e)%a]}for(var f=0;f<4;f++){d[e][f]=b[f]}}return d};Hidden.MixColumns=function(h,f){for(var j=0;j<4;j++){var e=new Array(4);var d=new Array(4);for(var g=0;g<4;g++){e[g]=h[g][j];d[g]=h[g][j]&128?h[g][j]<<1^283:h[g][j]<<1}h[0][j]=d[0]^e[1]^d[1]^e[2]^e[3];h[1][j]=e[0]^d[1]^e[2]^d[2]^e[3];h[2][j]=e[0]^e[1]^d[2]^e[3]^d[3];h[3][j]=e[0]^d[0]^e[1]^e[2]^d[3]}return h};Hidden.AddRoundKey=function(f,a,d,b){for(var e=0;e<4;e++){for(var g=0;g<b;g++){f[e][g]^=a[d*4+g][e]}}return f};Hidden.SubWord=function(a){for(var b=0;b<4;b++){a[b]=Hidden.Sbox[a[b]]}return a};Hidden.RotWord=function(a){var c=a[0];for(var b=0;b<3;b++){a[b]=a[b+1]}a[3]=c;return a};Hidden.Sbox=[99,124,119,123,242,107,111,197,48,1,103,43,254,215,171,118,202,130,201,125,250,89,71,240,173,212,162,175,156,164,114,192,183,253,147,38,54,63,247,204,52,165,229,241,113,216,49,21,4,199,35,195,24,150,5,154,7,18,128,226,235,39,178,117,9,131,44,26,27,110,90,160,82,59,214,179,41,227,47,132,83,209,0,237,32,252,177,91,106,203,190,57,74,76,88,207,208,239,170,251,67,77,51,133,69,249,2,127,80,60,159,168,81,163,64,143,146,157,56,245,188,182,218,33,16,255,243,210,205,12,19,236,95,151,68,23,196,167,126,61,100,93,25,115,96,129,79,220,34,42,144,136,70,238,184,20,222,94,11,219,224,50,58,10,73,6,36,92,194,211,172,98,145,149,228,121,231,200,55,109,141,213,78,169,108,86,244,234,101,122,174,8,186,120,37,46,28,166,180,198,232,221,116,31,75,189,139,138,112,62,181,102,72,3,246,14,97,53,87,185,134,193,29,158,225,248,152,17,105,217,142,148,155,30,135,233,206,85,40,223,140,161,137,13,191,230,66,104,65,153,45,15,176,84,187,22];Hidden.Rcon=[[0,0,0,0],[1,0,0,0],[2,0,0,0],[4,0,0,0],[8,0,0,0],[16,0,0,0],[32,0,0,0],[64,0,0,0],[128,0,0,0],[27,0,0,0],[54,0,0,0]];var HiddenCtr={};HiddenCtr.encrypt=function(j,a,t){var k=16;if(!(t==128||t==192||t==256)){return""}j=Utf8.encode(j);a=Utf8.encode(a);var l=t/8;var f=new Array(l);for(var r=0;r<l;r++){f[r]=isNaN(a.charCodeAt(r))?0:a.charCodeAt(r)}var y=Hidden.Cipher(f,Hidden.KeyExpansion(f));y=y.concat(y.slice(0,l-16));var e=new Array(k);var s=(new Date()).getTime();var d=Math.floor(s/1000);var g=s%1000;for(var r=0;r<4;r++){e[r]=(d>>>r*8)&255}for(var r=0;r<4;r++){e[r+4]=g&255}var n="";for(var r=0;r<8;r++){n+=String.fromCharCode(e[r])}var v=Hidden.KeyExpansion(y);var q=Math.ceil(j.length/k);var m=new Array(q);for(var w=0;w<q;w++){for(var u=0;u<4;u++){e[15-u]=(w>>>u*8)&255}for(var u=0;u<4;u++){e[15-u-4]=(w/4294967296>>>u*8)}var h=Hidden.Cipher(e,v);var p=w<q-1?k:(j.length-1)%k+1;var o=new Array(p);for(var r=0;r<p;r++){o[r]=h[r]^j.charCodeAt(w*k+r);o[r]=String.fromCharCode(o[r])}m[w]=o.join("")}var x=n+m.join("");x=Base64.encode(x);return x};HiddenCtr.show=function(t,e,p){var m=16;if(!(p==128||p==192||p==256)){return""}t=Base64.decode(t);e=Utf8.encode(e);var n=p/8;var j=new Array(n);for(var o=0;o<n;o++){j[o]=isNaN(e.charCodeAt(o))?0:e.charCodeAt(o)}var u=Hidden.Cipher(j,Hidden.KeyExpansion(j));u=u.concat(u.slice(0,n-16));var f=new Array(8);ctrTxt=t.slice(0,8);for(var o=0;o<8;o++){f[o]=ctrTxt.charCodeAt(o)}var r=Hidden.KeyExpansion(u);var g=Math.ceil((t.length-8)/m);var h=new Array(g);for(var s=0;s<g;s++){h[s]=t.slice(8+s*m,8+s*m+m)}t=h;var a=new Array(t.length);for(var s=0;s<g;s++){for(var q=0;q<4;q++){f[15-q]=((s)>>>q*8)&255}for(var q=0;q<4;q++){f[15-q-4]=(((s+1)/4294967296-1)>>>q*8)&255}var l=Hidden.Cipher(f,r);var d=new Array(t[s].length);for(var o=0;o<t[s].length;o++){d[o]=l[o]^t[s].charCodeAt(o);d[o]=String.fromCharCode(d[o])}a[s]=d.join("")}var k=a.join("");k=Utf8.decode(k);return k};var Base64={};Base64.code="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";Base64.encode=function(n,p){p=(typeof p=="undefined")?false:p;var g,b,a,r,o,k,j,h,i=[],f="",m,q,l;var d=Base64.code;q=p?n.encodeUTF8():n;m=q.length%3;if(m>0){while(m++<3){f+="=";q+="\0"}}for(m=0;m<q.length;m+=3){g=q.charCodeAt(m);b=q.charCodeAt(m+1);a=q.charCodeAt(m+2);r=g<<16|b<<8|a;o=r>>18&63;k=r>>12&63;j=r>>6&63;h=r&63;i[m/3]=d.charAt(o)+d.charAt(k)+d.charAt(j)+d.charAt(h)}l=i.join("");l=l.slice(0,l.length-f.length)+f;return l};Base64.decode=function(n,e){e=(typeof e=="undefined")?false:e;var g,b,a,o,k,i,h,q,j=[],p,m;var f=Base64.code;m=e?n.decodeUTF8():n;for(var l=0;l<m.length;l+=4){o=f.indexOf(m.charAt(l));k=f.indexOf(m.charAt(l+1));i=f.indexOf(m.charAt(l+2));h=f.indexOf(m.charAt(l+3));q=o<<18|k<<12|i<<6|h;g=q>>>16&255;b=q>>>8&255;a=q&255;j[l/4]=String.fromCharCode(g,b,a);if(h==64){j[l/4]=String.fromCharCode(g,b)}if(i==64){j[l/4]=String.fromCharCode(g)}}p=j.join("");return e?p.decodeUTF8():p};var Utf8={};Utf8.encode=function(a){var b=a.replace(/[\u0080-\u07ff]/g,function(e){var d=e.charCodeAt(0);return String.fromCharCode(192|d>>6,128|d&63)});b=b.replace(/[\u0800-\uffff]/g,function(e){var d=e.charCodeAt(0);return String.fromCharCode(224|d>>12,128|d>>6&63,128|d&63)});return b};Utf8.decode=function(b){var a=b.replace(/[\u00c0-\u00df][\u0080-\u00bf]/g,function(e){var d=(e.charCodeAt(0)&31)<<6|e.charCodeAt(1)&63;return String.fromCharCode(d)});a=a.replace(/[\u00e0-\u00ef][\u0080-\u00bf][\u0080-\u00bf]/g,function(e){var d=((e.charCodeAt(0)&15)<<12)|((e.charCodeAt(1)&63)<<6)|(e.charCodeAt(2)&63);return String.fromCharCode(d)});return a};

/*Rounded corners*/
function browserdetect(){var b=navigator.userAgent.toLowerCase();this.isIE=b.indexOf("msie")>-1;if(this.isIE){this.ieVer=/msie\s(\d\.\d)/.exec(b)[1];this.quirksMode=!document.compatMode||document.compatMode.indexOf("BackCompat")>-1;this.get_style=function(f,h){if(!(h in f.currentStyle)){return""}var d=/^([\d.]+)(\w*)/.exec(f.currentStyle[h]);if(!d){return f.currentStyle[h]}if(d[1]==0){return"0"}if(d[2]&&d[2]!=="px"){var c=f.style.left;var g=f.runtimeStyle.left;f.runtimeStyle.left=f.currentStyle.left;f.style.left=d[1]+d[2];d[0]=f.style.pixelLeft;f.style.left=c;f.runtimeStyle.left=g}return d[0]};this.supportsCorners=false}else{this.ieVer=this.quirksMode=0;this.get_style=function(c,d){d=d.replace(/([a-z])([A-Z])/g,"$1-$2").toLowerCase();return document.defaultView.getComputedStyle(c,"").getPropertyValue(d)};this.isSafari=b.indexOf("safari")!=-1;this.isWebKit=b.indexOf("webkit")!=-1;this.isOp="opera"in window;if(this.isOp){this.supportsCorners=(this.isOp=window.opera.version())>=10.5}else{if(!this.isWebkit){if(!(this.isMoz=b.indexOf("firefox")!==-1)){for(var a=document.childNodes.length;--a>=0;){if("style"in document.childNodes[a]){this.isMoz="MozBorderRadius"in document.childNodes[a].style;break}}}}this.supportsCorners=this.isWebKit||this.isMoz}}}var curvyBrowser=new browserdetect;if(curvyBrowser.isIE){try{document.execCommand("BackgroundImageCache",false,true)}catch(e){}}function curvyCnrSpec(a){this.selectorText=a;this.tlR=this.trR=this.blR=this.brR=0;this.tlu=this.tru=this.blu=this.bru="";this.antiAlias=true}curvyCnrSpec.prototype.setcorner=function(b,c,a,d){if(!b){this.tlR=this.trR=this.blR=this.brR=parseInt(a);this.tlu=this.tru=this.blu=this.bru=d}else{var f=b.charAt(0)+c.charAt(0);this[f+"R"]=parseInt(a);this[f+"u"]=d}};curvyCnrSpec.prototype.get=function(d){if(/^(t|b)(l|r)(R|u)$/.test(d)){return this[d]}if(/^(t|b)(l|r)Ru$/.test(d)){var c=d.charAt(0)+d.charAt(1);return this[c+"R"]+this[c+"u"]}if(/^(t|b)Ru?$/.test(d)){var b=d.charAt(0);b+=this[b+"lR"]>this[b+"rR"]?"l":"r";var a=this[b+"R"];if(d.length===3&&d.charAt(2)==="u"){a+=this[b="u"]}return a}throw new Error("Don't recognize property "+d)};curvyCnrSpec.prototype.radiusdiff=function(a){if(a!=="t"&&a!=="b"){throw new Error("Param must be 't' or 'b'")}return Math.abs(this[a+"lR"]-this[a+"rR"])};curvyCnrSpec.prototype.setfrom=function(a){this.tlu=this.tru=this.blu=this.bru="px";if("tl"in a){this.tlR=a.tl.radius}if("tr"in a){this.trR=a.tr.radius}if("bl"in a){this.blR=a.bl.radius}if("br"in a){this.brR=a.br.radius}if("antiAlias"in a){this.antiAlias=a.antiAlias}};curvyCnrSpec.prototype.cloneOn=function(f){var j=["tl","tr","bl","br"];var k=0;var d,g;for(d in j){if(!isNaN(d)){g=this[j[d]+"u"];if(g!==""&&g!=="px"){k=new curvyCnrSpec;break}}}if(!k){k=this}else{var c,b,h=curvyBrowser.get_style(f,"left");for(d in j){if(!isNaN(d)){c=j[d];g=this[c+"u"];b=this[c+"R"];if(g!=="px"){var a=f.style.left;f.style.left=b+g;b=f.style.pixelLeft;f.style.left=a}k[c+"R"]=b;k[c+"u"]="px"}}f.style.left=h}return k};curvyCnrSpec.prototype.radiusSum=function(a){if(a!=="t"&&a!=="b"){throw new Error("Param must be 't' or 'b'")}return this[a+"lR"]+this[a+"rR"]};curvyCnrSpec.prototype.radiusCount=function(a){var b=0;if(this[a+"lR"]){++b}if(this[a+"rR"]){++b}return b};curvyCnrSpec.prototype.cornerNames=function(){var a=[];if(this.tlR){a.push("tl")}if(this.trR){a.push("tr")}if(this.blR){a.push("bl")}if(this.brR){a.push("br")}return a};function operasheet(c){var a=document.styleSheets.item(c).ownerNode.text;a=a.replace(/\/\*(\n|\r|.)*?\*\//g,"");var d=new RegExp("^\\s*([\\w.#][-\\w.#, ]+)[\\n\\s]*\\{([^}]+border-((top|bottom)-(left|right)-)?radius[^}]*)\\}","mg");var h;this.rules=[];while((h=d.exec(a))!==null){var g=new RegExp("(..)border-((top|bottom)-(left|right)-)?radius:\\s*([\\d.]+)(in|em|px|ex|pt)","g");var f,b=new curvyCnrSpec(h[1]);while((f=g.exec(h[2]))!==null){if(f[1]!=="z-"){b.setcorner(f[3],f[4],f[5],f[6])}}this.rules.push(b)}}operasheet.contains_border_radius=function(a){return/border-((top|bottom)-(left|right)-)?radius/.test(document.styleSheets.item(a).ownerNode.text)};function curvyCorners(){var g,c,d,b,l;if(typeof arguments[0]!=="object"){throw curvyCorners.newError("First parameter of curvyCorners() must be an object.")}if(arguments[0]instanceof curvyCnrSpec){b=arguments[0];if(!b.selectorText&&typeof arguments[1]==="string"){b.selectorText=arguments[1]}}else{if(typeof arguments[1]!=="object"&&typeof arguments[1]!=="string"){throw curvyCorners.newError("Second parameter of curvyCorners() must be an object or a class name.")}c=arguments[1];if(typeof c!=="string"){c=""}if(c!==""&&c.charAt(0)!=="."&&"autoPad"in arguments[0]){c="."+c}b=new curvyCnrSpec(c);b.setfrom(arguments[0])}if(b.selectorText){l=0;var h=b.selectorText.replace(/\s+$/,"").split(/,\s*/);d=new Array;for(g=0;g<h.length;++g){if((c=h[g].lastIndexOf("#"))!==-1){h[g]=h[g].substr(c)}d=d.concat(curvyCorners.getElementsBySelector(h[g].split(/\s+/)))}}else{l=1;d=arguments}for(g=l,c=d.length;g<c;++g){var k=d[g];var a=false;if(!k.className){k.className="curvyIgnore"}else{a=k.className.indexOf("curvyIgnore")!==-1;if(!a){k.className+=" curvyIgnore"}}if(!a){if(k.className.indexOf("curvyRedraw")!==-1){if(typeof curvyCorners.redrawList==="undefined"){curvyCorners.redrawList=new Array}curvyCorners.redrawList.push({node:k,spec:b,copy:k.cloneNode(false)})}var f=new curvyObject(b,k);f.applyCorners()}}}curvyCorners.prototype.applyCornersToAll=function(){throw curvyCorners.newError("This function is now redundant. Just call curvyCorners(). See documentation.")};curvyCorners.redraw=function(){if(curvyBrowser.supportsCorners){return}if(!curvyCorners.redrawList){throw curvyCorners.newError("curvyCorners.redraw() has nothing to redraw.")}var h=curvyCorners.block_redraw;curvyCorners.block_redraw=true;for(var c in curvyCorners.redrawList){if(isNaN(c)){continue}var g=curvyCorners.redrawList[c];if(!g.node.clientWidth){continue}var d=g.copy.cloneNode(false);for(var f=g.node.firstChild;f!==null;f=f.nextSibling){if(f.className.indexOf("autoPadDiv")!==-1){break}}if(!f){curvyCorners.alert("Couldn't find autoPad DIV");break}g.node.parentNode.replaceChild(d,g.node);var a=f.getElementsByTagName("script");for(var b=a.length-1;b>=0;--b){a[b].parentNode.removeChild(a[b])}while(f.firstChild){d.appendChild(f.removeChild(f.firstChild))}g=new curvyObject(g.spec,g.node=d);g.applyCorners()}curvyCorners.block_redraw=h};curvyCorners.adjust=function(obj,prop,newval){if(!curvyBrowser.supportsCorners){if(!curvyCorners.redrawList){throw curvyCorners.newError("curvyCorners.adjust() has nothing to adjust.")}var i,j=curvyCorners.redrawList.length;for(i=0;i<j;++i){if(curvyCorners.redrawList[i].node===obj){break}}if(i===j){throw curvyCorners.newError("Object not redrawable");}obj=curvyCorners.redrawList[i].copy}if(prop.indexOf(".")===-1){obj[prop]=newval}else{eval("obj."+prop+"='"+newval+"'")}};curvyCorners.handleWinResize=function(){if(!curvyCorners.block_redraw){curvyCorners.redraw()}};curvyCorners.setWinResize=function(a){curvyCorners.block_redraw=!a};curvyCorners.newError=function(a){return new Error("curvyCorners Error:\n"+a)};curvyCorners.alert=function(a){if(typeof curvyCornersVerbose==="undefined"||curvyCornersVerbose){alert(a)}};function curvyObject(){var B;this.box=arguments[1];this.settings=arguments[0];this.topContainer=this.bottomContainer=this.shell=B=null;var p=this.box.clientWidth;if(("canHaveChildren"in this.box&&!this.box.canHaveChildren)||this.box.tagName==="TABLE"){throw new Error(this.errmsg("You cannot apply corners to "+this.box.tagName+" elements.","Error"))}if(!p&&curvyBrowser.isIE){this.box.style.zoom=1;p=this.box.clientWidth}if(!p&&curvyBrowser.get_style(this.box,"display")==="inline"){this.box.style.display="inline-block";curvyCorners.alert(this.errmsg("Converting inline element to inline-block","warning"));p=this.box.clientWidth}if(!p){if(!this.box.parentNode){throw this.newError("box has no parent!")}for(B=this.box;B=B.parentNode;){if(!B||B.tagName==="BODY"){this.applyCorners=function(){};curvyCorners.alert(this.errmsg("zero-width box with no accountable parent","warning"));return}if(curvyBrowser.get_style(B,"display")==="none"){break}}var u=B.style.display;B.style.display="block";p=this.box.clientWidth}if(!p){curvyCorners.alert(this.errmsg("zero-width box, cannot display","error"));this.applyCorners=function(){};return}if(arguments[0]instanceof curvyCnrSpec){this.spec=arguments[0].cloneOn(this.box)}else{this.spec=new curvyCnrSpec("");this.spec.setfrom(this.settings)}var J=curvyBrowser.get_style(this.box,"borderTopWidth");var o=curvyBrowser.get_style(this.box,"borderBottomWidth");var h=curvyBrowser.get_style(this.box,"borderLeftWidth");var c=curvyBrowser.get_style(this.box,"borderRightWidth");var n=curvyBrowser.get_style(this.box,"borderTopColor");var k=curvyBrowser.get_style(this.box,"borderBottomColor");var b=curvyBrowser.get_style(this.box,"borderLeftColor");var I=curvyBrowser.get_style(this.box,"borderRightColor");var d=curvyBrowser.get_style(this.box,"borderTopStyle");var m=curvyBrowser.get_style(this.box,"borderBottomStyle");var g=curvyBrowser.get_style(this.box,"borderLeftStyle");var a=curvyBrowser.get_style(this.box,"borderRightStyle");var i=curvyBrowser.get_style(this.box,"backgroundColor");var f=curvyBrowser.get_style(this.box,"backgroundImage");var F=curvyBrowser.get_style(this.box,"backgroundRepeat");var z,x;if(this.box.currentStyle&&this.box.currentStyle.backgroundPositionX){z=curvyBrowser.get_style(this.box,"backgroundPositionX");x=curvyBrowser.get_style(this.box,"backgroundPositionY")}else{z=curvyBrowser.get_style(this.box,"backgroundPosition");z=z.split(" ");x=z.length===2?z[1]:0;z=z[0]}var w=curvyBrowser.get_style(this.box,"position");var G=curvyBrowser.get_style(this.box,"paddingTop");var K=curvyBrowser.get_style(this.box,"paddingBottom");var y=curvyBrowser.get_style(this.box,"paddingLeft");var H=curvyBrowser.get_style(this.box,"paddingRight");var s=curvyBrowser.ieVer>7?curvyBrowser.get_style(this.box,"filter"):null;var l=this.spec.get("tR");var r=this.spec.get("bR");var D=function(L){if(typeof L==="number"){return L}if(typeof L!=="string"){throw new Error("unexpected styleToNPx type "+typeof L)}var t=/^[-\d.]([a-z]+)$/.exec(L);if(t&&t[1]!="px"){throw new Error("Unexpected unit "+t[1])}if(isNaN(L=parseInt(L))){L=0}return L};var A=function(t){return t<=0?"0":t+"px"};try{this.borderWidth=D(J);this.borderWidthB=D(o);this.borderWidthL=D(h);this.borderWidthR=D(c);this.boxColour=curvyObject.format_colour(i);this.topPadding=D(G);this.bottomPadding=D(K);this.leftPadding=D(y);this.rightPadding=D(H);this.boxWidth=p;this.boxHeight=this.box.clientHeight;this.borderColour=curvyObject.format_colour(n);this.borderColourB=curvyObject.format_colour(k);this.borderColourL=curvyObject.format_colour(b);this.borderColourR=curvyObject.format_colour(I);this.borderString=this.borderWidth+"px "+d+" "+this.borderColour;this.borderStringB=this.borderWidthB+"px "+m+" "+this.borderColourB;this.borderStringL=this.borderWidthL+"px "+g+" "+this.borderColourL;this.borderStringR=this.borderWidthR+"px "+a+" "+this.borderColourR;this.backgroundImage=((f!="none")?f:"");this.backgroundRepeat=F}catch(E){throw this.newError(E.message)}var j=this.boxHeight;var C=p;if(curvyBrowser.isOp){var v;z=D(z);x=D(x);if(z){v=C+this.borderWidthL+this.borderWidthR;if(z>v){z=v}z=(v/z*100)+"%"}if(x){v=j+this.borderWidth+this.borderWidthB;if(x>v){x=v}x=(v/x*100)+"%"}}if(curvyBrowser.quirksMode){}else{this.boxWidth-=this.leftPadding+this.rightPadding;this.boxHeight-=this.topPadding+this.bottomPadding}this.contentContainer=document.createElement("div");if(s){this.contentContainer.style.filter=s}while(this.box.firstChild){this.contentContainer.appendChild(this.box.removeChild(this.box.firstChild))}if(w!="absolute"){this.box.style.position="relative"}this.box.style.padding="0";this.box.style.border=this.box.style.backgroundImage="none";this.box.style.backgroundColor="transparent";this.box.style.width=(C+this.borderWidthL+this.borderWidthR)+"px";this.box.style.height=(j+this.borderWidth+this.borderWidthB)+"px";var q=document.createElement("div");q.style.position="absolute";if(s){q.style.filter=s}if(curvyBrowser.quirksMode){q.style.width=(C+this.borderWidthL+this.borderWidthR)+"px"}else{q.style.width=C+"px"}q.style.height=A(j+this.borderWidth+this.borderWidthB-l-r);q.style.padding="0";q.style.top=l+"px";q.style.left="0";if(this.borderWidthL){q.style.borderLeft=this.borderStringL}if(this.borderWidth&&!l){q.style.borderTop=this.borderString}if(this.borderWidthR){q.style.borderRight=this.borderStringR}if(this.borderWidthB&&!r){q.style.borderBottom=this.borderStringB}q.style.backgroundColor=i;q.style.backgroundImage=this.backgroundImage;q.style.backgroundRepeat=this.backgroundRepeat;q.style.direction="ltr";this.shell=this.box.appendChild(q);p=curvyBrowser.get_style(this.shell,"width");if(p===""||p==="auto"||p.indexOf("%")!==-1){throw this.newError("Shell width is "+p)}this.boxWidth=(p!==""&&p!="auto"&&p.indexOf("%")==-1)?parseInt(p):this.shell.clientWidth;this.applyCorners=function(){this.backgroundPosX=this.backgroundPosY=0;if(this.backgroundObject){var Z=function(ar,ap,aq){if(ar===0){return 0}if(ar==="right"||ar==="bottom"){return aq-ap}if(ar==="center"){return(aq-ap)/2}if(ar.indexOf("%")>0){return(aq-ap)*100/parseInt(ar)}return D(ar)};this.backgroundPosX=Z(z,this.backgroundObject.width,C);this.backgroundPosY=Z(x,this.backgroundObject.height,j)}else{if(this.backgroundImage){this.backgroundPosX=D(z);this.backgroundPosY=D(x)}}if(l){q=document.createElement("div");q.style.width=this.boxWidth+"px";q.style.fontSize="1px";q.style.overflow="hidden";q.style.position="absolute";q.style.paddingLeft=this.borderWidth+"px";q.style.paddingRight=this.borderWidth+"px";q.style.height=l+"px";q.style.top=-l+"px";q.style.left=-this.borderWidthL+"px";this.topContainer=this.shell.appendChild(q)}if(r){q=document.createElement("div");q.style.width=this.boxWidth+"px";q.style.fontSize="1px";q.style.overflow="hidden";q.style.position="absolute";q.style.paddingLeft=this.borderWidthB+"px";q.style.paddingRight=this.borderWidthB+"px";q.style.height=r+"px";q.style.bottom=-r+"px";q.style.left=-this.borderWidthL+"px";this.bottomContainer=this.shell.appendChild(q)}var ah=this.spec.cornerNames();for(var al in ah){if(!isNaN(al)){var ad=ah[al];var ae=this.spec[ad+"R"];var af,ai,O,ag;if(ad=="tr"||ad=="tl"){af=this.borderWidth;ai=this.borderColour;ag=this.borderWidth}else{af=this.borderWidthB;ai=this.borderColourB;ag=this.borderWidthB}O=ae-ag;var Y=document.createElement("div");Y.style.height=this.spec.get(ad+"Ru");Y.style.width=this.spec.get(ad+"Ru");Y.style.position="absolute";Y.style.fontSize="1px";Y.style.overflow="hidden";var W,V,T;var R=s?parseInt(/alpha\(opacity.(\d+)\)/.exec(s)[1]):100;for(W=0;W<ae;++W){var Q=(W+1>=O)?-1:Math.floor(Math.sqrt(Math.pow(O,2)-Math.pow(W+1,2)))-1;if(O!=ae){var N=(W>=O)?-1:Math.ceil(Math.sqrt(Math.pow(O,2)-Math.pow(W,2)));var L=(W+1>=ae)?-1:Math.floor(Math.sqrt(Math.pow(ae,2)-Math.pow((W+1),2)))-1}var t=(W>=ae)?-1:Math.ceil(Math.sqrt(Math.pow(ae,2)-Math.pow(W,2)));if(Q>-1){this.drawPixel(W,0,this.boxColour,R,(Q+1),Y,true,ae)}if(O!=ae){if(this.spec.antiAlias){for(V=Q+1;V<N;++V){if(this.backgroundImage!==""){var M=curvyObject.pixelFraction(W,V,O)*100;this.drawPixel(W,V,ai,R,1,Y,M>=30,ae)}else{if(this.boxColour!=="transparent"){var ac=curvyObject.BlendColour(this.boxColour,ai,curvyObject.pixelFraction(W,V,O));this.drawPixel(W,V,ac,R,1,Y,false,ae)}else{this.drawPixel(W,V,ai,R>>1,1,Y,false,ae)}}}if(L>=N){if(N==-1){N=0}this.drawPixel(W,N,ai,R,(L-N+1),Y,false,0)}T=ai;V=L}else{if(L>Q){this.drawPixel(W,(Q+1),ai,R,(L-Q),Y,false,0)}}}else{T=this.boxColour;V=Q}if(this.spec.antiAlias&&this.boxColour!=="transparent"){while(++V<t){this.drawPixel(W,V,T,(curvyObject.pixelFraction(W,V,ae)*R),1,Y,ag<=0,ae)}}}var ak;for(v=0,ak=Y.childNodes.length;v<ak;++v){var X=Y.childNodes[v];var aj=parseInt(X.style.top);var an=parseInt(X.style.left);var ao=parseInt(X.style.height);if(ad=="tl"||ad=="bl"){X.style.left=(ae-an-1)+"px"}if(ad=="tr"||ad=="tl"){X.style.top=(ae-ao-aj)+"px"}X.style.backgroundRepeat=this.backgroundRepeat;if(this.backgroundImage){switch(ad){case"tr":X.style.backgroundPosition=(this.backgroundPosX-this.borderWidthL+ae-C-an)+"px "+(this.backgroundPosY+ao+aj+this.borderWidth-ae)+"px";break;case"tl":X.style.backgroundPosition=(this.backgroundPosX-ae+an+1+this.borderWidthL)+"px "+(this.backgroundPosY-ae+ao+aj+this.borderWidth)+"px";break;case"bl":X.style.backgroundPosition=(this.backgroundPosX-ae+an+1+this.borderWidthL)+"px "+(this.backgroundPosY-j-this.borderWidth+(curvyBrowser.quirksMode?aj:-aj)+ae)+"px";break;case"br":if(curvyBrowser.quirksMode){X.style.backgroundPosition=(this.backgroundPosX-this.borderWidthL-C+ae-an)+"px "+(this.backgroundPosY-j-this.borderWidth+aj+ae)+"px"}else{X.style.backgroundPosition=(this.backgroundPosX-this.borderWidthL-C+ae-an)+"px "+(this.backgroundPosY-j-this.borderWidth+ae-aj)+"px"}}}}switch(ad){case"tl":Y.style.top=Y.style.left="0";this.topContainer.appendChild(Y);break;case"tr":Y.style.top=Y.style.right="0";this.topContainer.appendChild(Y);break;case"bl":Y.style.bottom=Y.style.left="0";this.bottomContainer.appendChild(Y);break;case"br":Y.style.bottom=Y.style.right="0";this.bottomContainer.appendChild(Y)}}}var aa={t:this.spec.radiusdiff("t"),b:this.spec.radiusdiff("b")};for(var U in aa){if(typeof U==="function"){continue}if(!this.spec.get(U+"R")){continue}if(aa[U]){var am=(this.spec[U+"lR"]<this.spec[U+"rR"])?U+"l":U+"r";var P=document.createElement("div");P.style.height=aa[U]+"px";P.style.width=this.spec.get(am+"Ru");P.style.position="absolute";P.style.fontSize="1px";P.style.overflow="hidden";P.style.backgroundColor=this.boxColour;if(s){P.style.filter=s}P.style.backgroundImage=this.backgroundImage;P.style.backgroundRepeat=this.backgroundRepeat;switch(am){case"tl":P.style.bottom=P.style.left="0";P.style.borderLeft=this.borderStringL;P.style.backgroundPosition=this.backgroundPosX+"px "+(this.borderWidth+this.backgroundPosY-this.spec.tlR)+"px";this.topContainer.appendChild(P);break;case"tr":P.style.bottom=P.style.right="0";P.style.borderRight=this.borderStringR;P.style.backgroundPosition=(this.backgroundPosX-this.boxWidth+this.spec.trR)+"px "+(this.borderWidth+this.backgroundPosY-this.spec.trR)+"px";this.topContainer.appendChild(P);break;case"bl":P.style.top=P.style.left="0";P.style.borderLeft=this.borderStringL;P.style.backgroundPosition=this.backgroundPosX+"px "+(this.backgroundPosY-this.borderWidth-this.boxHeight+aa[U]+this.spec.blR)+"px";this.bottomContainer.appendChild(P);break;case"br":P.style.top=P.style.right="0";P.style.borderRight=this.borderStringR;P.style.backgroundPosition=(this.borderWidthL+this.backgroundPosX-this.boxWidth+this.spec.brR)+"px "+(this.backgroundPosY-this.borderWidth-this.boxHeight+aa[U]+this.spec.brR)+"px";this.bottomContainer.appendChild(P)}}var S=document.createElement("div");if(s){S.style.filter=s}S.style.position="relative";S.style.fontSize="1px";S.style.overflow="hidden";S.style.width=this.fillerWidth(U);S.style.backgroundColor=this.boxColour;S.style.backgroundImage=this.backgroundImage;S.style.backgroundRepeat=this.backgroundRepeat;switch(U){case"t":if(this.topContainer){if(curvyBrowser.quirksMode){S.style.height=100+l+"px"}else{S.style.height=100+l-this.borderWidth+"px"}S.style.marginLeft=this.spec.tlR?(this.spec.tlR-this.borderWidthL)+"px":"0";S.style.borderTop=this.borderString;if(this.backgroundImage){var ab=this.spec.tlR?(this.borderWidthL+this.backgroundPosX-this.spec.tlR)+"px ":this.backgroundPosX+"px ";S.style.backgroundPosition=ab+this.backgroundPosY+"px";this.shell.style.backgroundPosition=this.backgroundPosX+"px "+(this.backgroundPosY-l+this.borderWidthL)+"px"}this.topContainer.appendChild(S)}break;case"b":if(this.bottomContainer){if(curvyBrowser.quirksMode){S.style.height=r+"px"}else{S.style.height=r-this.borderWidthB+"px"}S.style.marginLeft=this.spec.blR?(this.spec.blR-this.borderWidthL)+"px":"0";S.style.borderBottom=this.borderStringB;if(this.backgroundImage){var ab=this.spec.blR?(this.backgroundPosX+this.borderWidthL-this.spec.blR)+"px ":this.backgroundPosX+"px ";S.style.backgroundPosition=ab+(this.backgroundPosY-j-this.borderWidth+r)+"px"}this.bottomContainer.appendChild(S)}}}this.contentContainer.style.position="absolute";this.contentContainer.className="autoPadDiv";this.contentContainer.style.left=this.borderWidthL+"px";this.contentContainer.style.paddingTop=this.topPadding+"px";this.contentContainer.style.top=this.borderWidth+"px";this.contentContainer.style.paddingLeft=this.leftPadding+"px";this.contentContainer.style.paddingRight=this.rightPadding+"px";U=C;if(!curvyBrowser.quirksMode){U-=this.leftPadding+this.rightPadding}this.contentContainer.style.width=U+"px";this.contentContainer.style.textAlign=curvyBrowser.get_style(this.box,"textAlign");this.box.style.textAlign="left";this.box.appendChild(this.contentContainer);if(B){B.style.display=u}};if(this.backgroundImage){z=this.backgroundCheck(z);x=this.backgroundCheck(x);if(this.backgroundObject){this.backgroundObject.holdingElement=this;this.dispatch=this.applyCorners;this.applyCorners=function(){if(this.backgroundObject.complete){this.dispatch()}else{this.backgroundObject.onload=new Function("curvyObject.dispatch(this.holdingElement);")}}}}}curvyObject.prototype.backgroundCheck=function(b){if(b==="top"||b==="left"||parseInt(b)===0){return 0}if(!(/^[-\d.]+px$/.test(b))&&!this.backgroundObject){this.backgroundObject=new Image;var a=function(d){var c=/url\("?([^'"]+)"?\)/.exec(d);return(c?c[1]:d)};this.backgroundObject.src=a(this.backgroundImage)}return b};curvyObject.dispatch=function(a){if("dispatch"in a){a.dispatch()}else{throw a.newError("No dispatch function")}};curvyObject.prototype.drawPixel=function(k,h,a,g,i,j,c,f){var b=document.createElement("div");b.style.height=i+"px";b.style.width="1px";b.style.position="absolute";b.style.fontSize="1px";b.style.overflow="hidden";var d=this.spec.get("tR");b.style.backgroundColor=a;if(c&&this.backgroundImage!==""){b.style.backgroundImage=this.backgroundImage;b.style.backgroundPosition="-"+(this.boxWidth-(f-k)+this.borderWidth)+"px -"+((this.boxHeight+d+h)-this.borderWidth)+"px"}if(g!=100){curvyObject.setOpacity(b,g)}b.style.top=h+"px";b.style.left=k+"px";j.appendChild(b)};curvyObject.prototype.fillerWidth=function(b){var a,c;a=curvyBrowser.quirksMode?0:this.spec.radiusCount(b)*this.borderWidthL;if((c=this.boxWidth-this.spec.radiusSum(b)+a)<0){throw this.newError("Radius exceeds box width")}return c+"px"};curvyObject.prototype.errmsg=function(c,d){var b="\ntag: "+this.box.tagName;if(this.box.id){b+="\nid: "+this.box.id}if(this.box.className){b+="\nclass: "+this.box.className}var a;if((a=this.box.parentNode)===null){b+="\n(box has no parent)"}else{b+="\nParent tag: "+a.tagName;if(a.id){b+="\nParent ID: "+a.id}if(a.className){b+="\nParent class: "+a.className}}if(d===undefined){d="warning"}return"curvyObject "+d+":\n"+c+b};curvyObject.prototype.newError=function(a){return new Error(this.errmsg(a,"exception"))};curvyObject.IntToHex=function(b){var a=["0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F"];return a[b>>>4]+""+a[b&15]};curvyObject.BlendColour=function(m,k,h){if(m==="transparent"||k==="transparent"){throw this.newError("Cannot blend with transparent")}if(m.charAt(0)!=="#"){m=curvyObject.format_colour(m)}if(k.charAt(0)!=="#"){k=curvyObject.format_colour(k)}var d=parseInt(m.substr(1,2),16);var l=parseInt(m.substr(3,2),16);var g=parseInt(m.substr(5,2),16);var c=parseInt(k.substr(1,2),16);var j=parseInt(k.substr(3,2),16);var f=parseInt(k.substr(5,2),16);if(h>1||h<0){h=1}var i=Math.round((d*h)+(c*(1-h)));if(i>255){i=255}if(i<0){i=0}var b=Math.round((l*h)+(j*(1-h)));if(b>255){b=255}if(b<0){b=0}var a=Math.round((g*h)+(f*(1-h)));if(a>255){a=255}if(a<0){a=0}return"#"+curvyObject.IntToHex(i)+curvyObject.IntToHex(b)+curvyObject.IntToHex(a)};curvyObject.pixelFraction=function(i,h,a){var k;var f=a*a;var b=new Array(2);var g=new Array(2);var j=0;var c="";var d=Math.sqrt(f-Math.pow(i,2));if(d>=h&&d<(h+1)){c="Left";b[j]=0;g[j]=d-h;++j}d=Math.sqrt(f-Math.pow(h+1,2));if(d>=i&&d<(i+1)){c+="Top";b[j]=d-i;g[j]=1;++j}d=Math.sqrt(f-Math.pow(i+1,2));if(d>=h&&d<(h+1)){c+="Right";b[j]=1;g[j]=d-h;++j}d=Math.sqrt(f-Math.pow(h,2));if(d>=i&&d<(i+1)){c+="Bottom";b[j]=d-i;g[j]=0}switch(c){case"LeftRight":k=Math.min(g[0],g[1])+((Math.max(g[0],g[1])-Math.min(g[0],g[1]))/2);break;case"TopRight":k=1-(((1-b[0])*(1-g[1]))/2);break;case"TopBottom":k=Math.min(b[0],b[1])+((Math.max(b[0],b[1])-Math.min(b[0],b[1]))/2);break;case"LeftBottom":k=g[0]*b[1]/2;break;default:k=1}return k};curvyObject.rgb2Array=function(a){var b=a.substring(4,a.indexOf(")"));return b.split(/,\s*/)};curvyObject.rgb2Hex=function(b){try{var c=curvyObject.rgb2Array(b);var h=parseInt(c[0]);var f=parseInt(c[1]);var a=parseInt(c[2]);var d="#"+curvyObject.IntToHex(h)+curvyObject.IntToHex(f)+curvyObject.IntToHex(a)}catch(g){var i="getMessage"in g?g.getMessage():g.message;throw new Error("Error ("+i+") converting RGB value to Hex in rgb2Hex")}return d};curvyObject.setOpacity=function(g,c){c=(c==100)?99.999:c;if(curvyBrowser.isSafari&&g.tagName!="IFRAME"){var b=curvyObject.rgb2Array(g.style.backgroundColor);var f=parseInt(b[0]);var d=parseInt(b[1]);var a=parseInt(b[2]);g.style.backgroundColor="rgba("+f+", "+d+", "+a+", "+c/100+")"}else{if(typeof g.style.opacity!=="undefined"){g.style.opacity=c/100}else{if(typeof g.style.MozOpacity!=="undefined"){g.style.MozOpacity=c/100}else{if(typeof g.style.filter!=="undefined"){g.style.filter="alpha(opacity="+c+")"}else{if(typeof g.style.KHTMLOpacity!=="undefined"){g.style.KHTMLOpacity=c/100}}}}}};curvyCorners.addEvent=function(d,c,b,a){if(d.addEventListener){d.addEventListener(c,b,a);return true}if(d.attachEvent){return d.attachEvent("on"+c,b)}d["on"+c]=b;return false};if(typeof addEvent==="undefined"){addEvent=curvyCorners.addEvent}curvyObject.getComputedColour=function(g){var h=document.createElement("DIV");h.style.backgroundColor=g;document.body.appendChild(h);if(window.getComputedStyle){var f=document.defaultView.getComputedStyle(h,null).getPropertyValue("background-color");h.parentNode.removeChild(h);if(f.substr(0,3)==="rgb"){f=curvyObject.rgb2Hex(f)}return f}else{var a=document.body.createTextRange();a.moveToElementText(h);a.execCommand("ForeColor",false,g);var b=a.queryCommandValue("ForeColor");var c="rgb("+(b&255)+", "+((b&65280)>>8)+", "+((b&16711680)>>16)+")";h.parentNode.removeChild(h);a=null;return curvyObject.rgb2Hex(c)}};curvyObject.format_colour=function(a){if(a!==""&&a!=="transparent"){if(a.substr(0,3)==="rgb"){a=curvyObject.rgb2Hex(a)}else{if(a.charAt(0)!=="#"){a=curvyObject.getComputedColour(a)}else{if(a.length===4){a="#"+a.charAt(1)+a.charAt(1)+a.charAt(2)+a.charAt(2)+a.charAt(3)+a.charAt(3)}}}}return a};curvyCorners.getElementsByClass=function(j,g){var f=new Array;if(g===undefined){g=document}j=j.split(".");var a="*";if(j.length===1){a=j[0];j=false}else{if(j[0]){a=j[0]}j=j[1]}var d,c,b;if(a.charAt(0)==="#"){c=document.getElementById(a.substr(1));if(c){f.push(c)}}else{c=g.getElementsByTagName(a);b=c.length;if(j){var h=new RegExp("(^|\\s)"+j+"(\\s|$)");for(d=0;d<b;++d){if(h.test(c[d].className)){f.push(c[d])}}}else{for(d=0;d<b;++d){f.push(c[d])}}}return f};curvyCorners.getElementsBySelector=function(f,g){var b;var h=f[0];if(g===undefined){g=document}if(h.indexOf("#")===-1){b=curvyCorners.getElementsByClass(h,g)}else{var d=g.getElementById(h.substr(1));if(!d){return[]}b=[d]}if(f.length>1){var a=[];for(var c=b.length;--c>=0;){a=a.concat(curvyCorners.getElementsBySelector(f.slice(1),b[c]))}b=a}return b};if(curvyBrowser.supportsCorners){var curvyCornersNoAutoScan=true;curvyCorners.init=function(){}}else{curvyCorners.scanStyles=function(){function b(h){if(!parseInt(h)){return"px"}var i=/^[\d.]+(\w+)$/.exec(h);return i[1]}var f,d,c;if(curvyBrowser.isIE){function a(o){var j=o.style,h,i,m,l,n;if(curvyBrowser.ieVer>6){h=j["-moz-border-radius"]||0;i=j["-moz-border-radius-topright"]||0;m=j["-moz-border-radius-topleft"]||0;l=j["-moz-border-radius-bottomright"]||0;n=j["-moz-border-radius-bottomleft"]||0}else{h=j["moz-border-radius"]||0;i=j["moz-border-radius-topright"]||0;m=j["moz-border-radius-topleft"]||0;l=j["moz-border-radius-bottomright"]||0;n=j["moz-border-radius-bottomleft"]||0}if(h){var p=h.split("/");p=p[0].split(/\s+/);if(p[p.length-1]===""){p.pop()}switch(p.length){case 3:m=p[0];i=n=p[1];l=p[2];h=false;break;case 2:m=l=p[0];i=n=p[1];h=false;case 1:break;case 4:m=p[0];i=p[1];l=p[2];n=p[3];h=false;break;default:curvyCorners.alert("Illegal corners specification: "+h)}}if(h||m||i||l||n){var k=new curvyCnrSpec(o.selectorText);if(h){k.setcorner(null,null,parseInt(h),b(h))}else{if(i){k.setcorner("t","r",parseInt(i),b(i))}if(m){k.setcorner("t","l",parseInt(m),b(m))}if(n){k.setcorner("b","l",parseInt(n),b(n))}if(l){k.setcorner("b","r",parseInt(l),b(l))}}curvyCorners(k)}}for(f=0;f<document.styleSheets.length;++f){try{if(document.styleSheets[f].imports){for(d=0;d<document.styleSheets[f].imports.length;++d){for(c=0;c<document.styleSheets[f].imports[d].rules.length;++c){a(document.styleSheets[f].imports[d].rules[c])}}}for(d=0;d<document.styleSheets[f].rules.length;++d){a(document.styleSheets[f].rules[d])}}catch(g){if(typeof curvyCornersVerbose!=="undefined"&&curvyCornersVerbose){alert(g.message+" - ignored")}}}}else{if(curvyBrowser.isOp){for(f=0;f<document.styleSheets.length;++f){if(operasheet.contains_border_radius(f)){c=new operasheet(f);for(d in c.rules){if(!isNaN(d)){curvyCorners(c.rules[d])}}}}}else{curvyCorners.alert("Scanstyles does nothing in Webkit/Firefox/Opera")}}};curvyCorners.init=function(){if(arguments.callee.done){return}arguments.callee.done=true;if(curvyBrowser.isWebKit&&curvyCorners.init.timer){clearInterval(curvyCorners.init.timer);curvyCorners.init.timer=null}curvyCorners.scanStyles()}}if(typeof curvyCornersNoAutoScan==="undefined"||curvyCornersNoAutoScan===false){if(curvyBrowser.isOp){document.addEventListener("DOMContentLoaded",curvyCorners.init,false)}else{curvyCorners.addEvent(window,"load",curvyCorners.init,false)}};

/*Pixlr*/
var pixlr=function(){function windowSize(){var w=0,h=0;if(!(document.documentElement.clientWidth==0)){w=document.documentElement.clientWidth;h=document.documentElement.clientHeight}else{w=document.body.clientWidth;h=document.body.clientHeight}return{width:w,height:h}}function extend(ob,extender){var o=ob;for(var attribute in extender){if(extender.hasOwnProperty(attribute)){o[attribute]=extender[attribute]||ob[attribute]}}return o}function buildUrl(opt){var url='http://pixlr.com/'+opt.service+'/?s=c';for(var attribute in opt){if(attribute!=='service')url+="&"+attribute+"="+escape(opt[attribute])}return url}var bo={ie:window.ActiveXObject,ie6:window.ActiveXObject&&(document.implementation!=null)&&(document.implementation.hasFeature!=null)&&(window.XMLHttpRequest==null),quirks:document.compatMode==='BackCompat'};return{settings:{'service':'editor'},overlay:{show:function(options){var opt=extend(pixlr.settings,options||{});var iframe=document.createElement('iframe'),div=pixlr.overlay.div=document.createElement('div'),idiv=pixlr.overlay.idiv=document.createElement('div');idiv.id="pixlr_window_1"; div.id="pixlr_window_2";div.style.background='#696969';div.style.opacity=0.8;div.style.filter='alpha(opacity=80)';if((bo.ie&&bo.quirks)||bo.ie6){var size=windowSize();div.style.position='absolute';div.style.width=size.width+'px';div.style.height=size.height+'px';div.style.setExpression('top',"(t=document.documentElement.scrollTop||document.body.scrollTop)+'px'");div.style.setExpression('left',"(l=document.documentElement.scrollLeft||document.body.scrollLeft)+'px'")}else{div.style.width='100%';div.style.height='100%';div.style.top='0';div.style.left='0';div.style.position='fixed'}div.style.zIndex=99998;idiv.style.border='1px solid #2c2c2c';if((bo.ie&&bo.quirks)||bo.ie6){idiv.style.position='absolute';idiv.style.setExpression('top',"25+((t=document.documentElement.scrollTop||document.body.scrollTop))+'px'");idiv.style.setExpression('left',"35+((l=document.documentElement.scrollLeft||document.body.scrollLeft))+'px'")}else{idiv.style.position='fixed';idiv.style.top='25px';idiv.style.left='35px'}idiv.style.zIndex=99999;document.body.appendChild(div);document.body.appendChild(idiv);iframe.style.width=(div.offsetWidth-70)+'px';iframe.style.height=(div.offsetHeight-50)+'px';iframe.style.border='1px solid #b1b1b1';iframe.style.backgroundColor='#606060';iframe.style.display='block';iframe.frameBorder=0;iframe.src=buildUrl(opt);idiv.appendChild(iframe)},hide:function(callback){if(pixlr.overlay.idiv&&pixlr.overlay.div){document.body.removeChild(pixlr.overlay.idiv);document.body.removeChild(pixlr.overlay.div)}if(callback){eval(callback)}}},edit:function(options){var opt=extend(pixlr.settings,options||{});location.href=buildUrl(opt)}}}();