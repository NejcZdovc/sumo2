// Sumo2 System v3.0 | 12082010
var sumo2 = {
	AddLoadEvent : function(func) {
		var oldonload = window.onload;
		if (typeof window.onload != 'function') {
			window.onload = func;
		} else {
			window.onload = function() {
				oldonload();
				func();
			}
		}
	},
	
	AddEvent : function(elm, evType, fn, useCapture) {
		if (elm.addEventListener) {
			elm.addEventListener(evType, fn, useCapture);
			return true;
		} else if (elm.attachEvent) {
			var r = elm.attachEvent('on' + evType, fn);
			return r;
		} else {
			elm['on' + evType] = fn;
		}
	},
	
	DisableSelection : function(target) {
		if (typeof target.onselectstart!="undefined") {
			target.onselectstart=function(){return false}
		} else if (typeof target.style.MozUserSelect!="undefined") {
			target.style.MozUserSelect="none"
		} else {
			target.onmousedown=function(){return false}
		}
		target.style.cursor = "default"
	},
	
	RandomString : function (string_length) {
		var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
		var randomstring = '';
		for (var i=0; i<string_length; i++) {
			var rnum = Math.floor(Math.random() * chars.length);
			randomstring += chars.substring(rnum,rnum+1);
		}
		var now = new Date();
		randomstring +=now.getTime();
		return randomstring;
	},
	
	IsImage : function(data) {
		var imgArray = data.split('.');
		var last = imgArray[imgArray.length-1].toLowerCase();
		if(last == 'png' || last == 'jpg' || last == 'gif' || last == 'jpeg') {
			return true;	
		}
		return false;
	},

	
	Logout : function() {
		var finish=false;		
		var i=0;
		do {
			finish=sumo2.state.SaveState();
			i++;
		} while(!finish && i<3);
		sumo2.ajax.SendPost('includes/logout.php','',function(data) {			
			window.location.replace("login/");
		});
	},

	TextHeight : function(text, size, family, width) {
		var textArea = document.createElement("div");
		textArea.style.fontSize = size;
		textArea.style.fontFamily = family;
		textArea.style.position = "absolute";
		textArea.style.visibility = "hidden";
		textArea.style.width = width;
		document.body.appendChild(textArea);
		textArea.innerHTML = text;
		var height = false;
		height = textArea.clientHeight;
		document.body.removeChild(textArea);
		return height;
	},
	
	TextWidth : function(text, size, family) {
		var textArea = document.createElement("div");
		textArea.style.fontSize = size;
		textArea.style.fontFamily = family;
		textArea.style.position = "absolute";
		textArea.style.visibility = "hidden";
		document.body.appendChild(textArea);
		textArea.innerHTML = text;
		var width = false;
		width = textArea.clientWidth;
		document.body.removeChild(textArea);
		return width;
	},
	
	IE : /*@cc_on!@*/false,
	
	OPERA : window.opera?true:false,
	
	error : {
		TOKEN : "haC7wrEjepahuswa#?=_c373rupR@9efeafrujesUQA7_u7hESpu49Dat34swa",
		
		SendError : function(description, location) {
			sumo2.ajax.SendPost("includes/javascript.error.php","hash="+this.TOKEN+"$!$desc="+description+"$!$loc="+location);
		}
	},
	
	ajax : {
		GetAjaxObject : function() {
			var activeXModes=["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"];
			if(window.ActiveXObject) {
				for(var i=0;i<2;i++) {
					try {
						return new ActiveXObject(activeXModes[i]);
					} catch(e) {}
				}
			} else if(window.XMLHttpRequest) {
				return new XMLHttpRequest();
			} else {
				return false;
			}
		},
		
		LoadXml : function(location) {
			var httpObj = this.GetAjaxObject();
			if(!httpObj) {
				sumo2.error.SendError('Ajax object doesn\'t exist',location);
				sumo2.message.NewMessage('Ajax object doesn\'t exist'+location,3);
			}
			httpObj.open("GET",location,false);
			try {
				httpObj.send();
			} catch(e) {
				location.reload(true);
			}
			if(sumo2.IE) {
				return httpObj.responseText;
			} else {
				return httpObj.responseXML;
			}
		},
		
		HasClass : function(element, selClass) {
			var classes = element.className.split(' ');
			var len = classes.length;
			for(var i=0;i<len;i++) {
				if(classes[i] == selClass) {
				 	return true;
				}
			}

			return false;
		},
		
		AddClass : function(element, selClass) {
			if(!this.HasClass(element,selClass)) {
				element.className += " " + selClass;
			}
		},
		
		RemoveClass : function(element, selClass) {
			if(this.HasClass(element, selClass)) {
				element.className = element.className.replace(selClass,'');
			} 
		},
		
		LOADING: 0,

		ShowLoading : function() {
			var overlay = document.getElementById('sumo2-main-overlay');
			var loading = document.getElementById('sumo2-main-loading');
			this.RemoveClass(overlay,'hdn');
			this.RemoveClass(loading,'hdn');
			this.LOADING++;
		},

		HideLoading : function() {
			this.LOADING--;
			if(this.LOADING === 0) {
				var overlay = document.getElementById('sumo2-main-overlay');
				var loading = document.getElementById('sumo2-main-loading');
				this.AddClass(overlay,'hdn');
				this.AddClass(loading,'hdn');
				if(document.getElementById('javascriptLoginBox')) {
					eval(document.getElementById('javascriptLoginBox').innerHTML);
					document.getElementById('javascriptLoginBox').innerHTML="";
				}
			}
			if(this.LOADING < 0) this.LOADING = 0;
		},
		
		SendPost : function(location, parameters, func, sync) {
			if(location!='pages/relogin.php' && location!='includes/login.php' && $.cookie(cookieIDGlobal)==null) {
					sumo2.dialog.NewDialog('d_relogin', null, true);
			} else {
				this.ShowLoading();
				var httpObj = this.GetAjaxObject();
				if(!httpObj) {
					sumo2.error.SendError('Ajax object doesn\'t exist',location+parameters);
					sumo2.message.NewMessage('Ajax object doesn\'t exist'+location+'?'+parameters,3);
					this.HideLoading();
				}
				var response = func;
				if(typeof func == 'function') {
					httpObj.onreadystatechange = function() {
						if (httpObj.readyState == 4) {
							 if (httpObj.status == 200) {
								response(httpObj.responseText);
								sumo2.ajax.HideLoading();    
							 } else {
								sumo2.error.SendError('Ajax post request failed',location+'?'+parameters);
								sumo2.message.NewMessage('Ajax post request failed at'+location+'?'+parameters,3);
								sumo2.ajax.HideLoading();
							 }
						  }
					};	
				} else {
					httpObj.onreadystatechange = function() {};
					this.HideLoading();
				}
				if(sync === true) {
					httpObj.open('POST', location, false);
					this.HideLoading();
				} else {
					httpObj.open('POST', location, true);	
				}
				if(parameters.length > 0) {
					var andReplace = parameters.replace(/\&/g,'###');
					var dedReplace = andReplace.replace(/\$\!\$/g,'&');
					dedReplace = dedReplace.replace(/\+/g,'?!?!##');
					parameters = dedReplace;
				}
				httpObj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				httpObj.setRequestHeader("X-Requested-With", "XMLHttpRequest");
				httpObj.send(parameters);
			}
		}
	},
	
	validate : {
		IsNumerical : function(string, min, max) {
			if(string.length >= min && string.length <= max && string.match(/^[0-9 .,-]+$/)) {
				return true;	
			}
			return false;
		},
		
		IsLength : function(string, min, max) {
			if(string.length >= min && string.length <= max) {
				return true;	
			}
			return false;
		},
		
		IsAlpha : function(string, min, max) {
			if(string.length >= min && string.length <= max && string.match(/^[a-zA-Z \u00A1-\uFFFF]+$/)) {
				return true;	
			}
			return false;
		},
		
		IsAlphaNumerical : function(string, min, max) {
			if(string.length >= min && string.length <= max && string.match(/^[a-zA-Z0-9 \u00A1-\uFFFF]+$/)) {
				return true;	
			}
			return false;
		},
		
		IsFile : function(string, min, max) {
			if(string.length >= min && string.length <= max && string.match(/^[a-zA-Z\u00A1-\uFFFF0-9()_\s-.#$, ]+$/)) {
				return true;	
			}
			return false;
		},
		
		IsFolder : function(string, min, max) {
			if(string.length >= min && string.length <= max && string.match(/^[a-zA-Z\u00A1-\uFFFF0-9_\s-.#$, ]+$/)) {
				return true;	
			}
			return false;
		},
		
		IsEmail : function(string) {
			if(string.match(/^([a-zA-Z0-9\u00A1-\uFFFF]+([\.+_-][a-zA-Z0-9\u00A1-\uFFFF]+)*)@(([a-zA-Z0-9]+((\.|[-]{1,2})[a-zA-Z0-9]+)*)\.[a-zA-Z]{2,6})$/)) {
				return true;	
			}
			return false;
		},
		
		IsChecked : function(obj) {
			if(obj.checked) {
				return true;
			} else {
				return false;
			}
		},
		
		AreChecked : function(list, num) {
			var checkedNum = 0;
			for(var i=0, len = list.length;i<len;i++) {
				if(list[i].checked) {
					checkedNum++;
				}
			}
			if(checkedNum != num) {
				return false;
			} else {
				return true;
			}
		},
		
		IsSelected : function(obj) {
			if(obj.selected) {
				return true;
			} else {
				return false;
			}
		},
		
		AreSelected : function(list, num) {
			var selectedNum = 0;
			for(var i=0, len = list.length;i<len;i++) {
				if(list[i].selected) {
					selectedNum++;
				}
			}
			if(selectedNum != num) {
				return false;
			} else {
				return true;
			}
		},
		IsUrl : function(string) {
			var re = new RegExp('^(http|https|ftp)\://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\._\?\,\'/\\\+&amp;%\$#\=~])*$');
			if(string.match(re)) {
				return true;	
			}
			return false;
		},
		IsDomain : function(string) {
			var re = new RegExp('^[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$');
			if(string.match(re)) {
				return true;	
			}
			return false;
		},
		IsIP4 : function(string) {
			var re = new RegExp('^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$');
			if(string.match(re)) {
				return true;	
			}
			return false;
		},
		IsIP6 : function(string) {
			var re = new RegExp('^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])(.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])){3}))|:)))(%.+)?\s*$');
			if(string.match(re)) {
				return true;	
			}
			return false;
		}
	},
	
	animate : {
		FadeIn : function(obj, func) {
			if(obj) {
				var timer = null,
					steps = 10,
					step = 0;
				timer = setInterval(function() {
					step++;
					if(step < steps) {
						obj.style.opacity = (step/10);	
					} else {
						clearInterval(timer);
						obj.style.opacity = "1";
						func();
					}
				},33);
			}
		},
		
		FadeOut : function(obj, func) {
			if(obj) {
				var timer = null,
					steps = 0,
					step = 10;
				timer = setInterval(function() {
					step--;
					if(step > steps) {
						obj.style.opacity = (step/10);	
					} else {
						clearInterval(timer);
						obj.style.opacity = "0";
						func();
					}
				},33);
			}
		},
		
		DecWidth : function(obj, endWidth, steps, func) {
			if(obj) {
				var timer = null,
					width = obj.offsetWidth,
					unit = width/steps,
					step = 0;
				timer = setInterval(function() {
					step++;
					if(step < steps) {
						obj.style.width = (width - step*unit) + "px";	
					} else {
						clearInterval(timer);
						obj.style.width = endWidth + "px";
						func();
					}
				},33);
			}
		},
		
		IncWidth : function(obj, endWidth, steps, func) {
			if(obj) {
				var timer = null,
					width = obj.offsetWidth,
					delta = endWidth - width,
					unit = delta/steps,
					step = 0;
				timer = setInterval(function() {
					step++;
					if(step < steps) {
						obj.style.width = (width + step*unit) + "px";	
					} else {
						clearInterval(timer);
						obj.style.width = endWidth + "px";
						func();
					}
				},33);
			}
		},
		
		DecHeight : function(obj, endHeight, steps, func) {
			if(obj) {
				var timer = null,
					height = obj.offsetHeight,
					unit = height/steps,
					step = 0;
				timer = setInterval(function() {
					step++;
					if(step < steps) {
						obj.style.height = (height - step*unit) + "px";	
					} else {
						clearInterval(timer);
						obj.style.height = endHeight + "px";
						func();
					}
				},33);
			}
		},
		
		IncHeight : function(obj, endHeight, steps, func) {
			if(obj) {
				var timer = null,
					height = obj.offsetHeight,
					delta = endHeight - height,
					unit = delta/steps,
					step = 0;
				timer = setInterval(function() {
					step++;
					if(step < steps) {
						obj.style.height = (height + step*unit) + "px";	
					} else {
						clearInterval(timer);
						obj.style.height = endHeight + "px";
						func();
					}
				},33);
			}
		}
	},
	
	client : {
		WIDTH: null,
		HEIGHT : null,
		LEFT_HEIGHT : null,
		MAIN_WIDTH : null,
		MAIN_HEIGHT : null,
		REAL_HEIGHT : null,
		REAL_WIDTH : null,
		
		SetVariables : function() {
			if(typeof window.innerWidth != 'undefined') {
				this.WIDTH = window.innerWidth;
				this.HEIGHT = window.innerHeight;
			}
			else if(typeof document.documentElement != 'undefined' && typeof document.documentElement.clientWidth != 'undefined' && document.documentElement.clientWidth != 0) {
				this.WIDTH = document.documentElement.clientWidth;
				this.HEIGHT = document.documentElement.clientHeight;
			}
			else {
				this.WIDTH = document.body.clientWidth;
				this.HEIGHT = document.body.clientHeight;
			}
			this.REAL_HEIGHT = this.HEIGHT;
			this.REAL_WIDTH = this.WIDTH;
			if(this.WIDTH < 1024)
				this.WIDTH = 1024;
			if(this.HEIGHT < 768)
				this.HEIGHT = 768;
			this.MAIN_HEIGHT = this.HEIGHT - 185;
			this.LEFT_HEIGHT = this.HEIGHT - 189;
			this.MAIN_WIDTH = this.WIDTH - 130;
		},
		
		SetContainers : function() {
			document.body.style.height = this.HEIGHT + 'px';
			document.body.style.width = this.WIDTH + 'px';
			var main = document.getElementById('sumo2-main');
			main.style.height = this.MAIN_HEIGHT + 'px';
			main.style.width = this.MAIN_WIDTH + 'px';
			var left = document.getElementById('sumo2-left');
			left.style.height = this.LEFT_HEIGHT + 'px';
			var leftContetn = document.getElementById('sumo2-favourites');
			leftContetn.style.height = (this.LEFT_HEIGHT-26) + 'px';
			if(document.getElementById('sumo2-module-container')) {
				document.getElementById('sumo2-module-container').style.height = (this.MAIN_HEIGHT - 35) + 'px';
			}
			if(document.getElementById('sumo2-main-overlay')) {
				var overlay = document.getElementById('sumo2-main-overlay');
				overlay.style.width = sumo2.client.WIDTH + 'px';
				overlay.style.height = sumo2.client.HEIGHT + 'px';
			}
		},
		
		SetSize : function() {
			sumo2.client.SetVariables();
			sumo2.client.SetContainers();
			sumo2.dialog.ResizeDialogs();
			sumo2.accordion.ResizeAccordions();
			sumo2.client.ResizeOverlay();
		},
		
		ResizeOverlay : function() {
			var divs = document.getElementsByTagName("div");
			var len = divs.length;
			var secondary = len;
			do {
				var elem = divs[secondary];
				if(elem) {
					if(elem.className == "overlay") {
						var elemStyle = elem.style;
						elemStyle.height = this.HEIGHT + "px";
						elemStyle.width = this.WIDTH + "px";
					}
				}
			} while(--secondary);
		},

		Init : function() {
			this.SetVariables();
			this.SetContainers();
			window.onresize = this.SetSize;
		}
		
	},
	
	tooltip : {
		MAX_WIDTH : 290,
		MAX_HEIGHT : 400,
		
		GetPosition : function(event) {
			var Pos = { X:null, Y:null };
			if(sumo2.IE) {
				Pos.X = window.event.x + document.body.scrollLeft;
				Pos.Y = window.event.y + document.body.scrollTop;	
			} else {
				Pos.X = event.pageX;
				Pos.Y = event.pageY;
			}
			return Pos;
		},
		
		GetSize : function(data) {
			var result = {width:null,height:null};
			var maxWidth = sumo2.TextWidth(data,'10px','"Trebuchet MS", Arial, sans-serif');
			if(maxWidth > this.MAX_WIDTH) {
				result.width = this.MAX_WIDTH;
			} else {
				result.width = maxWidth;	
			}
			var maxHeight = sumo2.TextHeight(data,'10px','"Trebuchet MS", Arial, sans-serif',result.width+"px");
			if(maxHeight > this.MAX_HEIGHT) {
				result.height = this.MAX_HEIGHT;
			} else {
				result.height = maxHeight;	
			}
			return result;
		},
		
		CreateTooltip : function(pos, data) {
			var size = this.GetSize(data);
			var tooltip = document.createElement('div');
			tooltip.className = 'tooltip';
			tooltip.id = 'sumo2-tooltip-float';
			tooltip.innerHTML = data;
			tooltip.style.height = size.height + 'px';
			tooltip.style.width = size.width + 'px';
			if(pos.X + 1 + size.width + 30 > sumo2.client.WIDTH ) {
				pos.X = pos.X - ((pos.X + 1 + size.width + 30)-sumo2.client.WIDTH);	//Refine if problem
			}
			if(pos.Y + 25 + size.height + 30 > sumo2.client.HEIGHT ) {
				pos.Y = pos.Y - ((pos.Y + 25 + size.height + 30)-sumo2.client.HEIGHT); //Refine if problem
			}
			tooltip.style.top = (pos.Y + 25) + "px";
			tooltip.style.left = (pos.X + 1) + "px";
			document.body.appendChild(tooltip);
		},
		
		FindTooltips : function(parent) {
			if(parent) {
				var allTags = parent.getElementsByTagName("*");
				var tagsLen = allTags.length;
				var secondary = tagsLen;
				var i = null;
				do {
					i = tagsLen - secondary;
					if(i == tagsLen) break;
					if(allTags[i].className.search("sumo2-tooltip") > -1) {
						var data = allTags[i].title;
						allTags[i].title = "";
						allTags[i].realData = data;
						allTags[i].onmouseover = function(event) {
							if(!event) event = window.event;
							var pos = sumo2.tooltip.GetPosition(event);
							sumo2.tooltip.CreateTooltip(pos,this.realData);
						};
						allTags[i].onmouseout = function() {
							var wrapper = document.getElementById("sumo2-tooltip-float");
							if(wrapper && wrapper.parentNode) {
								wrapper.parentNode.removeChild(wrapper);
							}
						};
					}
				} while(--secondary);
			}
		}
	},
	
	button : {
		CreateButton : function(title, func, icon) {
			var button = document.createElement('div');
			button.className = 'button';
			button.onclick = func;
			if(icon) {
				if(sumo2.IsImage(icon)) {
					button.innerHTML = '<img src="' + icon + '" alt="' + title + '" class="flt-left" />' + title;	
				} else {
					button.innerHTML = '<div style="float:left;display:block;'+icon+'"></div><div style="margin-left:2px;float:left;">'+title+'</div>';
				}
			} else {
				button.innerHTML = title;	
			}
			return button;
		}
	},
	
	navigation : {
		MENU : null,
		SUBMENU : null,
		SELECTED : null,
		
		Init : function() {
			this.MENU = document.getElementById("sumo2-navigation");
			this.SUBMENU = document.getElementById("sumo2-subnavigation");
			this.SetNavigation();
		},
		
		SetNavigation : function() {
			var children = this.MENU.getElementsByTagName('li');
			var len = children.length;
			var secondary = len;
			var i = null;
			do {
				i = len - secondary;
				if(i == len) break;
				if(children[i].className.search('sel') > -1) {
					this.SELECTED = children[i];
				}
				children[i].onclick = function() {
					sumo2.navigation.ChangeSelected(this);
				};
			} while(--secondary);
		},
		
		ChangeSelected : function(obj) {
			if(obj.className.search('sel') == -1) {
				var classes = this.SELECTED.className.replace(/\s*sel\s*/,'');
				this.SELECTED.className = classes;
				obj.className += ' sel';
				this.SUBMENU.innerHTML = this.GetSubmenu(obj).innerHTML;
				this.SELECTED = obj;
			}
		},
		
		GetSubmenu : function(obj) {
			var uls = obj.getElementsByTagName('ul');
			return uls[0];
		}
	},
	
	accordion : {
		MAX_PANELS : 5,
		CUR_PANELS : 0,
		SELECTED : null,
		ACC_WORKING : false,
		ACCORDIONS : [],
		
		FindAccordion : function(id) {
			var len = this.ACCORDIONS.length;
			var secondary = len;
			var i = null;
			do {
				i = len - secondary;
				if(i == len) break;
				if(this.ACCORDIONS[i].id == id) {
					return {obj:this.ACCORDIONS[i], num:i};
				}
			} while(--secondary);
			return false;
		},
		
		RemoveAccordion : function(id) {
			var accordion = this.FindAccordion(id);
			if(!(accordion === false)) {
				this.ACCORDIONS.splice(accordion.num,1);
			}
		},
		
		AddAccordion : function(id, number) {
			this.ACCORDIONS.push({id:id, number:number});
		},
		
		CloseAccordion : function(id, changes) {
			if($.cookie(cookieIDGlobal)==null) {
					sumo2.dialog.NewDialog('d_relogin', null, true);
			} else {
				var accordion = this.FindAccordion(id);
				if(!(accordion === false)) {
					if(changes==2) {
						sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_205,300,60,function(param) {
							if(sumo2.accordion.ACC_WORKING) return false;
							if(sumo2.accordion.CUR_PANELS == 1) return false;
							sumo2.accordion.ACC_WORKING = true;
							var next = null;
							var wrapper = document.getElementById('sumo2-accordion-' + id);
							wrapper.parentNode.removeChild(wrapper);
							sumo2.accordion.RemoveAccordion(id);
							sumo2.accordion.CUR_PANELS--;
							sumo2.client.MAIN_WIDTH += 41;
							if(sumo2.accordion.SELECTED == id) {
								next = sumo2.accordion.ACCORDIONS[sumo2.accordion.ACCORDIONS.length-1].id;
								sumo2.accordion.SELECTED = next;
								sumo2.accordion.ShowAccordion(next, function() {
									if(sumo2.accordion.ACC_WORKING) sumo2.accordion.ACC_WORKING = false;
								});
							} else {
								sumo2.accordion.ShowAccordion(this.SELECTED, function() {
									if(sumo2.accordion.ACC_WORKING) sumo2.accordion.ACC_WORKING = false;
								},true);
							}
						}, id);
					} 
					else
					{
						if(this.ACC_WORKING) return false;
						if(this.CUR_PANELS == 1) return false;
						this.ACC_WORKING = true;
						var next = null;
						var wrapper = document.getElementById('sumo2-accordion-' + id);
						wrapper.parentNode.removeChild(wrapper);
						this.RemoveAccordion(id);
						this.CUR_PANELS--;
						sumo2.client.MAIN_WIDTH += 41;
						if(this.SELECTED == id) {
							next = this.ACCORDIONS[this.ACCORDIONS.length-1].id;
							this.SELECTED = next;
							this.ShowAccordion(next, function() {
								if(sumo2.accordion.ACC_WORKING) sumo2.accordion.ACC_WORKING = false;
							});
						} else {
							this.ShowAccordion(this.SELECTED, function() {
								if(sumo2.accordion.ACC_WORKING) sumo2.accordion.ACC_WORKING = false;
							},true);
						}
					}
				}
			}
		},
		
		HandleParameters : function(oldP, newP) {
			if(oldP == '' && newP == '') return '';
			var oldA = oldP.split('$!$');
			for(var i=0,len=oldA.length;i<len;i++) {
				var temp = oldA[i].split('=');
				if(temp.length > 2) {
					var temp2 = temp[0];
					temp.splice(0,1);
					var temp3 = temp;
					var temp4 = temp3.join('=');
					temp = [temp2,temp4];
				}
				oldA[i] = temp;
			}
			var newA = newP.split('$!$');
			for(var i=0,len=newA.length;i<len;i++) {
				var temp = newA[i].split('=');
				if(temp.length > 2) {
					var temp2 = temp[0];
					temp.splice(0,1);
					var temp3 = temp;
					var temp4 = temp3.join('=');
					temp = [temp2,temp4];
				}
				newA[i] = temp;
			}
			var splicedOnes = [];
			for(var i=0,len1=oldA.length;i<len1;i++) {
				for(var j=0,len2=newA.length;j<len2;j++) {
					if(oldA[i][0] == newA[j][0]) {
						oldA[i][1] = newA[j][1];
						splicedOnes.push(j);
					}
				}
			}
			for(var i=0,len=splicedOnes.length;i<len;i++) {
				newA.splice(splicedOnes[i],1);
			}
			var result = '';
			for(var i=0,len=oldA.length;i<len;i++) {
				var temp = oldA[i].join('=');
				oldA[i] = temp;
			}
			result += oldA.join('$!$');
			for(var i=0,len=newA.length;i<len;i++) {
				var temp = newA[i].join('=');
				newA[i] = temp;
			}
			var newPrm = newA.join('$!$');
			if(result != '' && newPrm != '') result += '$!$';
			result += newPrm;
			return result;
		},
		
		ReloadAccordion : function(id, parameters, title, change) {
			if($.cookie(cookieIDGlobal)==null) {
					sumo2.dialog.NewDialog('d_relogin', null, true);
			} else {
				if (typeof change !== "undefined") {
					change=false;
				}
				if(this.ACC_WORKING) return false;
				if(!document.getElementById('sumo2-accordion-' + id)) return false;
				var wrapper = document.getElementById('sumo2-accordion-' + id);
				var accordion = wrapper.accordion;				
				var foundAccordion = this.FindAccordion(id);
				if(parameters != null) {
					var param = this.HandleParameters(accordion.params,parameters);
				}
				if(change && accordion.changes==2) {
					sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_205,300,60,function() {
						if(parameters != null) {
							accordion.params = param;
						}
						if(title != null) {
							var tit = title.split(' ');
							var endTit = '';
							for(var i=0,len=tit.length;i<len;i++) {
								endTit += tit[i]+'&nbsp;';
							}
							$('#sumo2-accordion-' + id+' .accordion-title').html(endTit);									
						}
						var content = document.getElementById('sumo2-accordion-content-' + accordion.uniqueId);
						content.innerHTML = '<div class="ajax"></div>';
						sumo2.ajax.SendPost(accordion.page,accordion.params,function(data) {
							content.innerHTML = data;
							accordion.func(accordion.uniqueId);
							accordion.changes=true;
							$("#sumo2-accordion-content-" + accordion.uniqueId+" :input").each(function(e) {
								$(this).bind('keyup', function(e) {
									accordion.changes=2;
								});	
							});	
						});
					});
				} 
				else 
				{
					if(parameters != null) {
						accordion.params = param;
					}
					if(title != null) {
						var tit = title.split(' ');
						var endTit = '';
						for(var i=0,len=tit.length;i<len;i++) {
							endTit += tit[i]+'&nbsp;';
						}
						$('#sumo2-accordion-' + id+' .accordion-title').html(endTit);									
					}
					var content = document.getElementById('sumo2-accordion-content-' + accordion.uniqueId);
					content.innerHTML = '<div class="ajax"></div>';
					sumo2.ajax.SendPost(accordion.page,accordion.params,function(data) {
						content.innerHTML = data;
						accordion.func(accordion.uniqueId);
						if(accordion.changes) {							
							$("#sumo2-accordion-content-" + accordion.uniqueId+" :input").each(function(e) {
								$(this).bind('keyup', function(e) {
									accordion.changes=2;
								});	
							});	
						}
					});
				}
			}
		},
		
		ToggleCloseButton : function(id) {
			var obj = document.getElementById('sumo2-accordion-hide-close-' + id);
			if(obj.className.search('hidden') > -1) {
				var classes = obj.className.replace(/\s*hidden\s*/,'');
				obj.className = classes;
			} else {
				obj.className += ' hidden';
			}
		},
		
		CreateAccordion : function(accordion) {
			var width = sumo2.client.MAIN_WIDTH;
			var height = sumo2.client.MAIN_HEIGHT;
			var doc = document;
			var obj = this;
			var wrapper = doc.createElement('span');
			wrapper.className = 'height fade';
			wrapper.id = 'sumo2-accordion-' + accordion.uniqueId;
			wrapper.accordion = accordion;
			var hide = doc.createElement('div');
			hide.className = 'accordion-hide flt-left';
			hide.id = 'sumo2-accordion-hide-' + accordion.uniqueId;
			hide.accordionId = accordion.uniqueId;
			hide.onclick = function() {
				obj.ChangeAccordion(this.accordionId);
				return false;
			};
			var hideClose = doc.createElement('div');
			hideClose.className = 'close-button accordion-hidebttn hidden';
			hideClose.id = 'sumo2-accordion-hide-close-' + accordion.uniqueId;
			hideClose.accordionId = accordion.uniqueId;
			hideClose.onclick = function() {
				obj.CloseAccordion(this.accordionId, accordion.changes);
			};
			hide.appendChild(hideClose);
			var parsedTit = '';
			if(accordion.title.search("sumo2.language.VARIABLES.") > -1) {
				parsedTit = eval(accordion.title);
			} else {
				parsedTit = accordion.title;
			}
			
			var tit = parsedTit.split(' ');
			var endTit = '';
			for(var i=0,len=tit.length;i<len;i++) {
				endTit += tit[i]+'&nbsp;';
			}
			var title2 = doc.createElement('div');
			title2.className = 'accordion-title';
			title2.innerHTML = endTit;		
			hide.appendChild(title2);
			var main = doc.createElement('div');
			main.className = 'accordion-main flt-left';
			main.id = 'sumo2-accordion-main-' + accordion.uniqueId;
			main.style.width = (width - 41) + 'px';
			var fix = doc.createElement('div');
			fix.className = 'button flt-left accordion-bttn hidden';
			main.appendChild(fix);
			var len = accordion.buttons.length;
			var secondary = len;
			var i = null;
			do {
				i = len - secondary;
				if(i == len) break;
				var button = sumo2.button.CreateButton(accordion.buttons[i].title,accordion.buttons[i].func,accordion.buttons[i].icon);
				button.className += ' flt-left accordion-bttn';
				main.appendChild(button);
			} while(--secondary);
			var close = doc.createElement('div');
			close.className = 'close-button flt-right accordion-funcbttn';
			close.accordionId = accordion.uniqueId;
			close.onclick = function() {
				obj.CloseAccordion(this.accordionId, accordion.changes);
			};
			main.appendChild(close);
			if(accordion.refresh === true) {
				var refresh = doc.createElement('div');
				refresh.className = 'refresh-button flt-right accordion-funcbttn';
				refresh.accordionId = accordion.uniqueId;
				refresh.onclick = function() {
					obj.ReloadAccordion(this.accordionId);
				};
				main.appendChild(refresh);
			}
			var content = doc.createElement('div');
			content.className = 'accordion-content clear';
			content.id = 'sumo2-accordion-content-' + accordion.uniqueId;
			content.style.height = (height - 35) + 'px';
			content.innerHTML = '<div class="ajax"></div>';
			sumo2.ajax.SendPost(accordion.page,accordion.params,function(data) {
				var content = document.getElementById('sumo2-accordion-content-' + accordion.uniqueId);
				content.innerHTML = data;
				accordion.func(accordion.uniqueId);
			});
			
			main.appendChild(content);
			wrapper.appendChild(hide);
			wrapper.appendChild(main);
			var parent = doc.getElementById('sumo2-main');
			parent.appendChild(wrapper);
			sumo2.animate.FadeIn(wrapper,function() {
				if(accordion.changes) {
					$("#"+content.id+" :input").each(function(e) {
						$(this).bind('keyup', function(e) {
							accordion.changes=2;
						});	
					});	
				}
			});
		},
		
		HideAccordion : function(id, func) {
			$("#sumo2-accordion-content-" + this.SELECTED).css("visibility",  "hidden");
			var main = document.getElementById('sumo2-accordion-main-' + id);
			var obj = this;
			sumo2.animate.DecWidth(main,0,15,function() {
				obj.ToggleCloseButton(id);
				func();
			});
		},
		
		ShowAccordion : function(id, func,toggle) {
			var main = document.getElementById('sumo2-accordion-main-' + id);
			var main_name = "#sumo2-accordion-content-" + id;
			$(main_name).css("visibility",  "hidden");			
			var obj = this;
			var width = sumo2.client.MAIN_WIDTH;
			sumo2.animate.IncWidth(main,width,15,function() {
				if(!(toggle === true)) obj.ToggleCloseButton(id);
				$(main_name).css("visibility",  "visible");
				if(typeof func == 'function') func();
			});
		},
		
		ChangeAccordion : function(id) {
			if(this.ACC_WORKING) return false;
			if(this.SELECTED == id) return false;
			$("#sumo2-accordion-content-" + this.SELECTED).css("visibility",  "hidden");
			$("#sumo2-accordion-content-" + id).css("visibility",  "hidden");
			var foundAccordion = this.FindAccordion(id);
			var timer = null;
			var accordion1 = document.getElementById("sumo2-accordion-main-" + this.SELECTED);
			var accordion1_name = "#sumo2-accordion-content-" + this.SELECTED;
			var accordion2 = document.getElementById("sumo2-accordion-main-" + id);
			var accordion2_name = "#sumo2-accordion-content-" + id;
			var width = accordion1.clientWidth;
			var steps = 15;
			var unit = width/steps;
			var step = 0;
			this.ToggleCloseButton(this.SELECTED);
			this.ToggleCloseButton(id);
			this.SELECTED = id;
			this.ACC_WORKING = true;
			timer = setInterval(function() {
				step++;
				if(step < steps) {
					accordion1.style.width = (width - step*unit) + "px";
					accordion2.style.width = (step*unit) + "px";
				} else {
					clearInterval(timer);
					accordion1.style.width = "0px";
					accordion2.style.width = width + "px";
					if(sumo2.accordion.ACC_WORKING) sumo2.accordion.ACC_WORKING = false;
					$(accordion2_name).css("visibility",  "visible");
					$(accordion1_name).css("visibility",  "visible");
				}
			},33);
		},
		
		DeleteParameters : function(id) {
			if(document.getElementById("sumo2-accordion-"+id)) {
				var wrapper = document.getElementById("sumo2-accordion-"+id);
				wrapper.accordion.params = "";
				return true;
			} else {
				return false;	
			}
		},
		
		GetParameter : function(key,parameters) {
			var split1 = parameters.split("$!$");
			for(var i=0,len=split1.length;i<len;i++) {
				var split2 = split1[i].split("=");
				if(key == split2[0]) {
					split2.splice(0,1);
					var value = split2.join("=");
					return value;
				}
			}
			return false;
		},
		
		GetParamFromAccordion : function(id, key) {
			if(document.getElementById("sumo2-accordion-"+id)) {
				var wrapper = document.getElementById("sumo2-accordion-"+id);
				var params = wrapper.accordion.params;
				var end = this.GetParameter(key,params);
				return end;
			} else {
				return false;	
			}
		},
		
		GetParamFromAccordionAll : function(id) {
			if(document.getElementById("sumo2-accordion-"+id)) {
				var wrapper = document.getElementById("sumo2-accordion-"+id);
				return wrapper.accordion.params;
			} else {
				return false;	
			}
		},
		
		SetParamForAccordion : function(id,param) {
			if(document.getElementById("sumo2-accordion-"+id)) {
				var wrapper = document.getElementById("sumo2-accordion-"+id);
				wrapper.accordion.params = this.HandleParameters(wrapper.accordion.params,param);
				return true;
			} else {
				return false;	
			}
		},
		
		QuickHideAccordion : function(id) {
			var wrapper = document.getElementById('sumo2-accordion-main-' + id);
			this.ToggleCloseButton(id);
			wrapper.style.width = '0px';
		},
		
		HAS_OPENED : false,
		
		ShowSelectedAccordion : function(id,page,params) {
			var accordion = this.FindAccordion(id);
			this.SELECTED = id;
			var main = document.getElementById('sumo2-accordion-main-' + id);
			var width = sumo2.client.MAIN_WIDTH;
			main.style.width = width + 'px';
			this.ToggleCloseButton(id);
			this.HAS_OPENED = true;
		},
		
		LoadPanelsFromJSON : function(JSONstring) {
			this.HAS_OPENED = false;
		    var JSONobject = JSON.parse(JSONstring);
		    var panels = JSONobject.panels;
		    var len = panels.length;
		    var secondary = len;
		    var i = null;
		    var selPage = null;
			var selParams = null;

		    do {
				i = len - secondary;
				if(i == len) break;
				var elem = panels[i];
				var number = parseInt(elem.number);
				var id = elem.id;
				var pageParams = elem.pageParams;
				var title = elem.title;
				var setAcc = sumo2.settings.ACCORDIONS[number];
				var accordion = 
					{
					 refresh: setAcc.refresh,
					 uniqueId: setAcc.uniqueId,
					 title: setAcc.title,
					 page: setAcc.page,
					 params: setAcc.params,
					 minWidth: setAcc.minWidth,
					 func: setAcc.func,
					 buttons : setAcc.buttons,
					 changes : setAcc.changes	
				};
				accordion.uniqueId = id;
				accordion.params = pageParams.replace(/\&/g,'$!$');
				accordion.title = title;
				this.CreateAccordion(accordion);
				this.QuickHideAccordion(accordion.uniqueId);
				this.AddAccordion(accordion.uniqueId,number);
				sumo2.client.MAIN_WIDTH -= 41;
				this.CUR_PANELS++;
			} while(--secondary);
			this.ShowSelectedAccordion(JSONobject.accSelected,selPage,selParams);
		},

		NewPanel : function(number,pageParameters,id,title, func) {
			if($.cookie(cookieIDGlobal)==null) {
					sumo2.dialog.NewDialog('d_relogin', null, true);
			} else {
				var changed = false;
				if(isNaN(number)) {
					number = sumo2.specialArray.accordions[number];	
				}
				if(this.ACC_WORKING) {
					if(typeof func == 'function') func();
					changed = true;	
				} else if(id && this.SELECTED == id) {
					if(typeof func == 'function') func();
					changed = true;
				} else {
					var setAcc = sumo2.settings.ACCORDIONS[number];
					var accordion = 
						{
						 refresh: setAcc.refresh,
						 uniqueId: setAcc.uniqueId,
						 title: setAcc.title,
						 page: setAcc.page,
						 params: setAcc.params,
						 minWidth: setAcc.minWidth,
						 func: setAcc.func,
						 buttons : setAcc.buttons,
						 changes : setAcc.changes	
					};
					if(id) accordion.uniqueId = id;
					if(title) accordion.title = title;
					var findAcc = this.FindAccordion(accordion.uniqueId);
					if(!(findAcc === false)) {						
						if(pageParameters!=this.GetParamFromAccordionAll(accordion.uniqueId)) {
							try {
								var tt;
								if(title)
									tt=accordion.title;
								else
									tt=null;
								this.ReloadAccordion(accordion.uniqueId, pageParameters, tt);
							} finally {
								this.ChangeAccordion(findAcc.obj.id);
								if(typeof func == 'function') func();
								changed = true;
							}
						} else {
							this.ChangeAccordion(findAcc.obj.id);
							if(typeof func == 'function') func();
							changed = true;
						}
					}
				}
				if(changed === false && this.CUR_PANELS < HiddenCtr.show(this.MAX_PANELS, "h39oyMN9cXzKT7loxCzYIUgD4uyHt9Fvccigc39GXpTjlAfkAlPegh3lnAIqJRDnAmJwc91WtwPHSs", 256)) {
					if(pageParameters) {
						if(accordion.params != '') accordion.params += '$!$';
						accordion.params += pageParameters;
					}
					var obj = this;
					this.ACC_WORKING = true;
					if(!(this.SELECTED === null)) {
						this.HideAccordion(this.SELECTED, function() {
							obj.CreateAccordion(accordion);
							obj.SELECTED = accordion.uniqueId;
							obj.AddAccordion(accordion.uniqueId, number);
							sumo2.client.MAIN_WIDTH -= 41;
							obj.CUR_PANELS++;
							if(sumo2.accordion.ACC_WORKING == true) sumo2.accordion.ACC_WORKING = false;
							if(typeof func == 'function') func();
						});
					} else {
						this.CreateAccordion(accordion);
						this.SELECTED = accordion.uniqueId;	
						this.AddAccordion(accordion.uniqueId, number);
						this.MEN_SELECTED = null;
						sumo2.client.MAIN_WIDTH -= 41;
						this.CUR_PANELS++;
						if(sumo2.accordion.ACC_WORKING == true) sumo2.accordion.ACC_WORKING = false;
						if(typeof func == 'function') func();
					}
					
				} else if(changed === false) {
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_133,2);
					if(typeof func == 'function') func();	
				}
			}
		},
		
		ResizeAccordions : function() {
			if(this.SELECTED == null) return false;
			var len = this.ACCORDIONS.length;
			var secondary = len;
			var i = null;
			sumo2.client.MAIN_WIDTH -= len*41;
			var width = sumo2.client.MAIN_WIDTH;
			var height = sumo2.client.MAIN_HEIGHT;
			do {
				i = len - secondary;
				if(i == len) break;
				var id = this.ACCORDIONS[i].id;
				var main = document.getElementById('sumo2-accordion-main-' + id);
				var content = document.getElementById('sumo2-accordion-content-' + id);
				if(this.SELECTED == id)
					main.style.width = width + 'px';
				content.style.height = (height - 35) + 'px';
			} while(--secondary);
		}
	},
	
	dialog : {
		IS_POPUP : false,
		OPENED : [],
		SELECTED : null,
		OVERLAY : false,
		
		FindDialog : function(id) {
			var len = this.OPENED.length;
			var secondary = len;
			var i = null;
			do {
				i = len - secondary;
				if(i == len) break;
				if(this.OPENED[i] == id) {
					return i;
				}
			} while(--secondary);
			return false;
		},
		
		RemoveDialog : function(id) {
			var dialog = this.FindDialog(id);
			if(!(dialog === false)) {
				this.OPENED.splice(dialog,1);
			}
		},
		
		AddDialog : function(id) {
			this.OPENED.push(id);
		},
		
		CloseDialog : function(id) {
			if(id!='notification' && $.cookie(cookieIDGlobal)==null) {
					sumo2.dialog.NewDialog('d_relogin', null, true);
			} else {
				if(document.getElementById('sumo2-dialog-' + id)) {
					var overlay = document.getElementById('sumo2-dialog-' + id);
					overlay.parentNode.removeChild(overlay);
					overlay.dialog.params="";
					this.RemoveDialog(id);
					if(overlay.className == "overlay") {
						this.OVERLAY = false;
					} 
					this.SELECTED = this.OPENED[this.OPENED.length-1];
				}
			}
		},
		
		HandleParameters : function(oldP, newP) {
			if(oldP == '' && newP == '') return '';
			var oldA = oldP.split('$!$');
			for(var i=0,len=oldA.length;i<len;i++) {
				var temp = oldA[i].split('=');
				if(temp.length > 2) {
					var temp2 = temp[0];
					temp.splice(0,1);
					var temp3 = temp;
					var temp4 = temp3.join('=');
					temp = [temp2,temp4];
				}
				oldA[i] = temp;
			}
			var newA = newP.split('$!$');
			for(var i=0,len=newA.length;i<len;i++) {
				var temp = newA[i].split('=');
				if(temp.length > 2) {
					var temp2 = temp[0];
					temp.splice(0,1);
					var temp3 = temp;
					var temp4 = temp3.join('=');
					temp = [temp2,temp4];
				}
				newA[i] = temp;
			}
			var splicedOnes = [];
			for(var i=0,len1=oldA.length;i<len1;i++) {
				for(var j=0,len2=newA.length;j<len2;j++) {
					if(oldA[i][0] == newA[j][0]) {
						oldA[i][1] = newA[j][1];
						splicedOnes.push(j);
					}
				}
			}
			for(var i=0,len=splicedOnes.length;i<len;i++) {
				newA.splice(splicedOnes[i],1);
			}
			var result = '';
			for(var i=0,len=oldA.length;i<len;i++) {
				var temp = oldA[i].join('=');
				oldA[i] = temp;
			}
			result += oldA.join('$!$');
			for(var i=0,len=newA.length;i<len;i++) {
				var temp = newA[i].join('=');
				newA[i] = temp;
			}
			var newPrm = newA.join('$!$');
			if(result != '' && newPrm != '') result += '$!$';
			result += newPrm;
			return result;
		},
		
		ReloadDialog : function(id, parameters) {
			if(id!='d_relogin' && $.cookie(cookieIDGlobal)==null) {
					sumo2.dialog.NewDialog('d_relogin', null, true);
			} else {
				if(!document.getElementById('sumo2-dialog-' + id)) return false;
				var overlay = document.getElementById('sumo2-dialog-' + id);
				var dialog = overlay.dialog;
				if(parameters) {
					var param = this.HandleParameters(dialog.params,parameters);
					dialog.params = param;
				}
				var content = document.getElementById('sumo2-dialog-content-' + dialog.uniqueId);
				sumo2.ajax.SendPost(dialog.page,dialog.params,function(data) {
					content.innerHTML = data;
					dialog.end();
				});
			}
		},
		
		GetParameter : function(key,parameters) {
			var split1 = parameters.split("$!$");
			for(var i=0,len=split1.length;i<len;i++) {
				var split2 = split1[i].split("=");
				if(key == split2[0]) {
					split2.splice(0,1);
					var value = split2.join("=");
					return value;
				}
			}
			return false;
		},
		
		GetParamFromDialog : function (id, key) {
			if(document.getElementById("sumo2-dialog-"+id)) {
				var overlay = document.getElementById('sumo2-dialog-' + id);
				var param = overlay.dialog.params;
				var end = this.GetParameter(key,param);
				return end;	
			} else {
				return false;	
			}
		},
		
		GetHeight : function(text, width) {
			return sumo2.TextHeight(text,'12px','"Trebuchet MS", Arial, sans-serif',width + 'px');
		},
		
		GetDialog : function(id) {
			return sumo2.specialArray.dialogs[id];
		},
		
		CreateDialog : function(dialog, text) {
			var width = sumo2.client.WIDTH;
			var height = sumo2.client.HEIGHT;
			var doc = document;
			if(text) {
				var newHeight =  this.GetHeight(text, dialog.width) + 5;
				if(newHeight < dialog.height) {
					dialog.height = newHeight + 14;
				}
			}
			var overlay = doc.createElement('div');
			if(this.OVERLAY === false) {
				overlay.className = 'overlay';
				this.OVERLAY = true;
			} else {
				overlay.className = 'overlay-trsp';
			}
			overlay.id = 'sumo2-dialog-' + dialog.uniqueId;
			overlay.dialog = dialog;
			overlay.style.height = sumo2.client.HEIGHT + 'px';
			overlay.style.width = sumo2.client.WIDTH + 'px';
			var box = doc.createElement('div');
			box.className = 'box dialog';
			box.id = 'sumo2-dialog-box-' + dialog.uniqueId;
			box.style.height = (dialog.height + 70) + 'px';
			box.style.width = dialog.width + 'px';
			box.style.top = (height - (dialog.height + 70))/2 + 'px';
			box.style.left = (width - dialog.width)/2 + 'px';
			var top = doc.createElement('div');
			top.className = 'dialog-title clear';
			var title = doc.createElement('div');
			title.className = 'title2 flt-left';
			title.innerHTML = dialog.title;
			top.appendChild(title);
			if(dialog.close === true) {
				var close = doc.createElement('div');
				close.className = 'close-button dialog-topbttn flt-right';
				close.onclick = function() {
					sumo2.dialog.CloseDialog(dialog.uniqueId);
				};
				top.appendChild(close);
			}
			if(dialog.refresh === true) {
				var refresh = doc.createElement('div');
				refresh.className = 'refresh-button dialog-topbttn flt-right';
				refresh.onclick = function() {
					sumo2.dialog.ReloadDialog(dialog.uniqueId);
				};
				top.appendChild(refresh);
			}
			var content = doc.createElement('div');
			content.className = 'dialog-content';
			content.id = 'sumo2-dialog-content-' + dialog.uniqueId;
			content.style.height = dialog.height + 'px';
			content.innerHTML = '<div class="ajax"></div>';
			if(text) {
				content.innerHTML = text;
			} else {
				sumo2.ajax.SendPost(dialog.page,dialog.params,function(data) {
					var content = document.getElementById('sumo2-dialog-content-' + dialog.uniqueId);
					content.innerHTML = data;
					dialog.end();
				});
			}
			var bottom = doc.createElement('div');
			bottom.className = 'dialog-buttons clear';
			var len = dialog.buttons.length;
			var secondary = len;
			var i = null;
			do {
				i = len - secondary;
				if(i == len) break;
				var button = sumo2.button.CreateButton(dialog.buttons[i].title,dialog.buttons[i].func,dialog.buttons[i].icon);
				button.className += ' flt-right';
				bottom.appendChild(button);
			} while(--secondary);
			box.appendChild(top);
			box.appendChild(content);
			box.appendChild(bottom);
			overlay.appendChild(box);
			document.body.appendChild(overlay);
			sumo2.dialog.Corner('.box', 10, 10, 10, 10);	
			sumo2.dialog.Corner('.dialog-title', 10, 10, 0, 0);		
		},
		
		Corner: function(id, tl, tr, bl, br) {
			var settings = {
			  tl: { radius: tl },
			  tr: { radius: tr },
			  bl: { radius: bl },
			  br: { radius: br },
			  antiAlias: true
			};
			curvyCorners(settings, id);
		},
		
		NewNotification : function(title, text, width, maxHeight, icon, esc) {
			var endText = '';
			if(icon==1) {
				endText += '<div style="width:16px;display:block;height:16px;background-image:url(images/css_sprite.png);background-position:-620px -1645px;" class="flt-left title2-icon"></div>'+title;
			} else if(icon==2) {
				endText += '<div style="width:16px;display:block;height:16px;background-image:url(images/css_sprite.png);background-position:-588px -1693px;" class="flt-left title2-icon"></div>'+title;
			} else if(icon==3) {
				endText += '<div style="width:16px;display:block;height:16px;background-image:url(images/css_sprite.png);background-position:-588px -1661px;" class="flt-left title2-icon"></div>'+title;
			} else {
				endText = title;
			}
			var dialog = {
				refresh : false,
				close : false,
				uniqueId : 'notification',
				title : endText,
				page : '',
				params : '',
				height : maxHeight,
				width : width,
				buttons : Array(
					{
						title : sumo2.language.VARIABLES.OK,
						icon : 'background-image:url(images/css_sprite.png);background-position:-588px -1693px;width:16px;height:16px;',
						func : function() {
							sumo2.dialog.CloseDialog('notification');
						}
					}
				)
			};
			this.CreateDialog(dialog,text);
			this.SELECTED = dialog.uniqueId;
			this.AddDialog(dialog.uniqueId);
			if(esc === true) {
				sumo2.AddEvent(document,'keydown',function(event) {
					if(!event) event = window.event;
					if(event.keyCode == 27 && sumo2.dialog.SELECTED == dialog.uniqueId) {
						sumo2.dialog.CloseDialog(dialog.uniqueId);
					}
				},false);
			}
		},
		
		NewConfirmation : function(title, text, width, maxHeight, buttonFunction, param) {
			var dialog = {
				refresh : false,
				close : false,
				uniqueId : 'confirmation',
				title : title,
				page : '',
				params : '',
				height : maxHeight,
				width : width,
				buttons : Array(
					{
						title : sumo2.language.VARIABLES.CANCEL,
						icon : 'background-image:url(images/css_sprite.png);background-position:-540px -1693px;width:16px;height:16px;',
						func : function() {
							sumo2.dialog.CloseDialog('confirmation');
						}
					},
					{
						title : sumo2.language.VARIABLES.OK,
						icon : 'background-image:url(images/css_sprite.png);background-position:-588px -1693px;width:16px;height:16px;',
						func : function() {
							buttonFunction(param);
							sumo2.dialog.CloseDialog('confirmation');
						}
					}
				)
			};
			this.CreateDialog(dialog,text);
			this.SELECTED = dialog.uniqueId;
			this.AddDialog(dialog.uniqueId);
		},
		
		YesNoDialog : function(title, text, width, maxHeight, yesFunction, noFunction) {
			var dialog = {
				refresh : false,
				close : false,
				uniqueId : 'yesnoDialog',
				title : title,
				page : '',
				params : '',
				height : maxHeight,
				width : width,
				buttons : Array(
					{
						title : sumo2.language.VARIABLES.NO,
						icon : 'background-image:url(images/css_sprite.png);background-position:-540px -1693px;width:16px;height:16px;',
						func : function() {
							noFunction();							
							sumo2.dialog.CloseDialog('yesnoDialog');
						}
					},
					{
						title : sumo2.language.VARIABLES.YES,
						icon : 'background-image:url(images/css_sprite.png);background-position:-588px -1693px;width:16px;height:16px;',
						func : function() {
							yesFunction();
							sumo2.dialog.CloseDialog('yesnoDialog');
						}
					}
				)
			};
			this.CreateDialog(dialog,text);
			this.SELECTED = dialog.uniqueId;
			this.AddDialog(dialog.uniqueId);
		},
		
		CustomDialog : function(title, text, width, maxHeight, buttons) {
			var dialog = {
				refresh : false,
				close : false,
				uniqueId : 'customDialog',
				title : title,
				page : '',
				params : '',
				height : maxHeight,
				width : width,
				buttons : buttons
			};
			this.CreateDialog(dialog,text);
			this.SELECTED = dialog.uniqueId;
			this.AddDialog(dialog.uniqueId);
		},
		
		NewDialog : function(number, parameters, esc) {
			if(number!='d_relogin' && $.cookie(cookieIDGlobal)==null) {
					sumo2.dialog.NewDialog('d_relogin', null, true);
			}
			else if(number=="d_relogin" && (this.SELECTED=="d_relogin" || this.SELECTED=="notification")) {
				
			} else {
				if(isNaN(number)) {
					if(number in sumo2.specialArray.dialogs) {
						number = sumo2.specialArray.dialogs[number];
					} else {
						return;
					}
				}			
				var dialog = sumo2.settings.DIALOGS[number];
				if(parameters) {
					if(dialog.params != '') dialog.params += '$!$';
					dialog.params += parameters;
				}			
				this.CreateDialog(dialog);
				this.SELECTED = dialog.uniqueId;
				this.AddDialog(dialog.uniqueId);
				if(esc==null && esc==false) {
					sumo2.AddEvent(document,'keydown',function(event) {
						if(!event) event = window.event;
						if(event.keyCode == 27 && sumo2.dialog.SELECTED == dialog.uniqueId) {
							sumo2.dialog.CloseDialog(dialog.uniqueId);
						}
					},false);
				}
			}
		},
		
		ResizeDialogs : function() {
			var width = sumo2.client.WIDTH;
			var height = sumo2.client.HEIGHT;
			var len = this.OPENED.length;
			var secondary = len;
			var i = null;
			do {
				i = len - secondary;
				if(i == len) break;
				var overlay = document.getElementById('sumo2-dialog-' + this.OPENED[i]);
				var dialog = overlay.dialog;
				var box = document.getElementById('sumo2-dialog-box-' + this.OPENED[i]);
				box.style.top = (height - (dialog.height + 70))/2 + 'px';
				box.style.left = (width - dialog.width)/2 + 'px';
			} while(--secondary);
		}
	},
	
	message: {
		timeOuts: [], 
		
		NewMessage: function(text, type, timeout) {
			var classId;
			var timeOutId;
			if (timeout === undefined ) {
			  timeout = 5000;
		   }
			var tempID=this.timeOuts.length;
			if(type=='2') {
				classId='warning';
			} else if(type=='3') {
				classId='error';
			} else {
				classId='ok';
			}
			timeOutId=setTimeout ( "sumo2.message.CloseMessage('"+tempID+"')", timeout);
			this.timeOuts.push(timeOutId);
			$('#sumo2-main-message').append('<div id="note_'+tempID+'" class="'+classId+' note" onclick="sumo2.message.CloseMessage(\''+tempID+'\');">'+text+'</div>');		
		},
		ClearAll: function() {
			for(key in this.timeOuts ){  
				clearTimeout(this.timeOuts[key]);  
			}
			this.timeOuts=new Array();
			$('#sumo2-main-message').fadeOut('slow', function() {
				$('#sumo2-main-message').html('');
			});
		},
		CloseMessage: function(id) {
			clearTimeout(this.timeOuts[id]);
			$('#note_'+id).fadeOut('slow', function() {
				$('#note_'+id).remove();
		  	});
		}		
	},
		
	language : {
		FILENAME : 'includes/javascript.language.php',
		VARIABLES : {},
		
		SerializeXml : function(node) {
			try {
				var oXmlSerializer=new XMLSerializer();
				return oXmlSerializer.serializeToString(node);
			}
			catch (e) {
			 try {
				// Internet Explorer.
				return node.xml;
			 }
			 catch (e) { 
				alert('Xmlserializer not supported');
				return false;
			 }
		   }
		},
		
		SerializeNode : function(node) {
			var childs = node.childNodes;
			var len = childs.length;
			var secondary = len;
			var i = null;
			var text = '';
			do {
				i = len - secondary;
				if(i == len) break;
				text += this.SerializeXml(childs[i]);
			} while(--secondary);
			return text;
		},
		
		GetLanguage : function(func) {
			var xml = sumo2.ajax.LoadXml(this.FILENAME);
			if(sumo2.IE) {
				var xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
				xmlDoc.async = "false";
				xmlDoc.loadXML(xml);
			} else {
				var xmlDoc = xml;
			}
			var items = xmlDoc.getElementsByTagName('item');
			var len = items.length;
			var secondary = len;
			var i = null;
			do {
				i = len - secondary;
				if(i == len) break;
				var node = items.item(i);
				var attribute = node.attributes.getNamedItem('constant').value;				
				var string = this.SerializeNode(node);
				sumo2.language.VARIABLES[attribute] = string;
			} while(--secondary);
			if(typeof func == 'function') func();
		}
	},
	
	image : {
		GetSize : function() {
			var img = new Image();
			img.src = 'images/logo.png';
			var widthimg=img.width;
			var heightimg=img.height;
			var margin=0;
			if(heightimg<=90) {
				margin=((90-heightimg)/2)+5;
			}
			else {
				var diff = heightimg - 90;
				diff = diff/heightimg;
				widthimg=widthimg-(widthimg*diff);
				heightimg=90;
				margin=5;
			}
			document.getElementById('logo_image').height=heightimg;
			document.getElementById('logo_image').width=widthimg;
			document.getElementById('logo_image').style.display="block";
			document.getElementById('logo_image').style.marginTop=margin + "px";
		}		
	},
	
	state : {
		Init : function() {
			if(sumo2.OPERA) {
				document.body.onunload = function() {
					sumo2.state.SaveState();				
					return;	
				};
			} else {				
				$(window).bind('beforeunload', function () {
					sumo2.state.SaveState(); 
					return;				
				});							
			}				
		},
		
		GenerateJSON : function() {
		    var accordions = sumo2.accordion.ACCORDIONS;
			var len = accordions.length;
			var secondary = len;
			var i = null;
			var JSONobject = new Object();
			if(sumo2.accordion.SELECTED === null) return 'empty';
			if(len == 0) return 'empty';
			JSONobject.accSelected = sumo2.accordion.SELECTED;
			JSONobject.panels = new Array();
			do {
			    i = len - secondary;
			    if(i == len) break;
			    var accObj = accordions[i];
			    var accWrapper = document.getElementById('sumo2-accordion-' + accObj.id);
				JSONobject.panels[i] = new Object();
				JSONobject.panels[i].number = accObj.number;
				JSONobject.panels[i].id = accObj.id;
				JSONobject.panels[i].title = accWrapper.accordion.title;
				var endparams = accWrapper.accordion.params.replace(/\$\!\$/g,'&');
				JSONobject.panels[i].pageParams = endparams;
			} while(--secondary);
			var JSONstring = JSON.stringify(JSONobject);
			JSONstring =JSONstring.replace(/\+/g,'##$##');
            return JSONstring;
		},
		
		SaveState : function() {
			if(typeof JSON == "object") {
				this.RemoveCkeditorInstances();
			    var stateString = this.GenerateJSON();
				var params = 'update=ok$!$state=' + stateString;
				var ok=false;
				sumo2.ajax.SendPost('includes/javascript.state.php',params,function(data){
					if(data=="ok")
						ok=true;
				},true);
				return ok;
			}
			return false;
		},
		
		RemoveCkeditorInstances : function() {
			var instances = CKEDITOR.instances;
			var len = instances.length;
			var secondary = len;
			var i = null;
			do {
				i = len - secondary;
				if(i == len) break;
				if(instances[i])
					CKEDITOR.remove(instances[i]);
			} while(--secondary);
		},
		
		LoadState : function() {
			if(typeof JSON == "object") {
				this.RemoveCkeditorInstances();
			    var params = 'get=ok';
				sumo2.ajax.SendPost('includes/javascript.state.php',params,function(data) {
					if(data == 'default') {
						sumo2.accordion.NewPanel('a_welcome');	
					} else {
						sumo2.accordion.LoadPanelsFromJSON(data);
					}
				});
			} else {
				sumo2.accordion.NewPanel('a_welcome');
			}
		}
	},
	module : {
		ClearCache : function(folder) {
			var params = 'type=cache$!$folder=' + folder;
			sumo2.ajax.SendPost('includes/module.data.php',params,function(data) {});			
		}		
	},
	
	preview : {
		Update : function() {
			sumo2.ajax.SendPost('includes/preview.php','',function(data) {
				$('#preview-link-refresh').html(data);
			});			
		}
	},
	
	update : {
		Checked: true,
		Init : function (manully) {
			if ($("#sumo2-main-update").length > 0){
				var params = 'type=check';
				sumo2.ajax.SendPost('includes/update.php',params,function(data) {
					var version=data.split('&&N&&');
					if(version[0] == 'Yes') {
						$('#sumo2-main-update #top').html('<b>'+sumo2.language.VARIABLES.MOD_143+'</b><br/><br/>'+sumo2.language.VARIABLES.MOD_144+'<br/><br/><div class="button flt-left accordion-bttn" onclick="sumo2.update.Close();"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-620px -1629px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_145+'</div></div><div class="button flt-right accordion-bttn" onclick="sumo2.update.Step1();"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-557px -1661px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_146+'</div></div><div style="clear:both;"></div>');
						$('#sumo2-main-update').slideDown('slow');
					} else {
						if (!!manully) {
							sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_214,1);
						}
					}
				});	
			}
		},
		/*Prenos verzij na sistem in izpis teh*/
		Step1 : function() {
			$('#sumo2-main-update').fadeOut();			
			sumo2.ajax.SendPost('includes/update.php','type=step1',function(data) {
				var first = data.split('////');
				var temp=first[0].split('&&');
				$('#sumo2-main-update #top').html("<b>"+sumo2.language.VARIABLES.MOD_172+"</b>: "+temp[0]+"<br/><b>"+sumo2.language.VARIABLES.MOD_171+"</b>: "+temp[1]+"<br/><br/><b>"+sumo2.language.VARIABLES.MOD_148+":</b><br/>");
				if(first.length>2) {
					$('#sumo2-main-update #top').append(' - '+first[1]+' <span style="color:#fffd56; font-weight:bold; cursor:pointer; font-size: 12px;" onclick="sumo2.dialog.NewDialog(\'d_update_text\',\'version='+first[2]+'\');">'+sumo2.language.VARIABLES.MOD_185+'!</span><br/>');
				} else {
					$('#sumo2-main-update #top').append(' - '+first[1]+'<br/>');
				}
				$('#sumo2-main-update #top').append('<br/><div class="button flt-left accordion-bttn" onclick="$(\'#sumo2-main-update\').slideUp(\'slow\');"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-620px -1629px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_145+'</div></div><div class="button flt-right accordion-bttn" onclick="sumo2.update.Step2();"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-620px -1710px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_173+'</div></div><div style="clear:both;"></div>');
				$('#sumo2-main-update').fadeIn();
			});	
						
		},
		/*Preverjanje datotek in map za pravice, odraranje zipov v mapo*/
		Step2 : function() {
			$('#sumo2-main-update').fadeOut();	
			sumo2.ajax.SendPost('includes/update.php','type=step2',function(data) {
				if(data=="yes") {
					$('#sumo2-main-update #top').html("<b>"+sumo2.language.VARIABLES.MOD_149+"<b/><br/>");
					$('#sumo2-main-update #top').append('<br/><div class="button flt-left accordion-bttn" onclick="$(\'#sumo2-main-update\').slideUp(\'slow\');"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-620px -1629px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_145+'</div></div><div class="button flt-right accordion-bttn" onclick="sumo2.update.Step3();"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-620px -1710px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_173+'</div></div><div style="clear:both;"></div>');
				} else if(data=='no') {
					$('#sumo2-main-update #top').html("<b>"+sumo2.language.VARIABLES.MOD_150+"</b><br/>");
					$('#sumo2-main-update #top').append('<br/><div class="button flt-left accordion-bttn" onclick="$(\'#sumo2-main-update\').slideUp(\'slow\');"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-620px -1629px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_145+'</div></div><div class="button flt-right accordion-bttn" onclick="sumo2.update.Step2();"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-636px -1710px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_147+'</div></div><div style="clear:both;"></div>');
					sumo2.accordion.NewPanel('a_settings', 'tab_val=global');
			    } else {
					$('#sumo2-main-update #top').html("<b>"+sumo2.language.VARIABLES.MOD_151+":</b><br/>");
					var array = data.split('!!!!!');
					for(var i in array)
					{
						$('#sumo2-main-update #top').append(' - '+array[i]+'<br/>');
					}
					$('#sumo2-main-update #top').append('<br/><div class="button flt-left accordion-bttn" onclick="$(\'#sumo2-main-update\').slideUp(\'slow\');"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-620px -1629px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_145+'</div></div><div class="button flt-right accordion-bttn" onclick="sumo2.update.Step2();"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-636px -1710px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_147+'</div></div><div style="clear:both;"></div>');
				}
				$('#sumo2-main-update').fadeIn();	
			});		
		},
		/*Vnos tabele in polj v bazo in odstranitev nepotrebnih tabel in polj*/
		Step3 : function() {
			$('#sumo2-main-update').fadeOut();	
			sumo2.ajax.SendPost('includes/update.php','type=step3',function(data) {
				if(data=="yes") {
					$('#sumo2-main-update #top').html("<b>"+sumo2.language.VARIABLES.MOD_152+"</b><br/>");
					$('#sumo2-main-update #top').append('<br/><div class="button flt-left accordion-bttn" onclick="$(\'#sumo2-main-update\').slideUp(\'slow\');"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-620px -1629px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_145+'</div></div><div class="button flt-right accordion-bttn" onclick="sumo2.update.Step4();"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-620px -1710px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_173+'</div></div><div style="clear:both;"></div>');
				} else {
					$('#sumo2-main-update #top').html("<b>"+sumo2.language.VARIABLES.MOD_153+":</b><br/>");
					$('#sumo2-main-update #top').append(data);
					$('#sumo2-main-update #top').append('<br/><div class="button flt-left accordion-bttn" onclick="$(\'#sumo2-main-update\').slideUp(\'slow\');"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-620px -1629px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_145+'</div></div><div class="button flt-right accordion-bttn" onclick="sumo2.update.Step3();"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-636px -1710px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_147+'</div></div><div style="clear:both;"></div>');
				}
				$('#sumo2-main-update').fadeIn();	
			});			
		},
		/*Prenos datotek na strežnik in odstranitev nepotrebnih datotek*/
		Step4 : function() {
			$('#sumo2-main-update').fadeOut();	
			sumo2.ajax.SendPost('includes/update.php','type=step4',function(data) {
				if(data=="yes") {
					$('#sumo2-main-update #top').html("<b>"+sumo2.language.VARIABLES.MOD_154+"</b><br/>");
					$('#sumo2-main-update #top').append('<br/><div class="button flt-left accordion-bttn" onclick="$(\'#sumo2-main-update\').slideUp(\'slow\');"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-620px -1629px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_145+'</div></div><div class="button flt-right accordion-bttn" onclick="sumo2.update.Step5();"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-620px -1710px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_173+'</div></div><div style="clear:both;"></div>');
				} else {
					$('#sumo2-main-update #top').html("<b>"+sumo2.language.VARIABLES.MOD_155+":</b><br/>");
					$('#sumo2-main-update #top').append(data);
					$('#sumo2-main-update #top').append('<br/><div class="button flt-left accordion-bttn" onclick="$(\'#sumo2-main-update\').slideUp(\'slow\');"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-620px -1629px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_145+'</div></div><div class="button flt-right accordion-bttn" onclick="sumo2.update.Step4();"><div style="float:left;display:block;background-image:url(images/css_sprite.png);background-position:-636px -1710px;width:16px;height:16px;"></div><div style="margin-left:2px;float:left;color:#000;">'+sumo2.language.VARIABLES.MOD_147+'</div></div><div style="clear:both;"></div>');
				}
				$('#sumo2-main-update').fadeIn();					
			});			
		},
		/*Zaključna faza in ponastavitev uporabniških nastavitev v bazi*/
		Step5 : function() {
			$('#sumo2-main-update').fadeOut();
			sumo2.ajax.SendPost('includes/update.php','type=step5',function(data) {
				$('#sumo2-main-update #top').html("<b>"+sumo2.language.VARIABLES.MOD_156+"</b><br/>"+sumo2.language.VARIABLES.MOD_157+"");
				$('#sumo2-main-update').fadeIn();
				sumo2.cacheSelection.Select('c28b874debda32d5a1QO');
				sumo2.sumoSettings.ChangeChacheNumber();
				setTimeout("window.location.reload()", 2000);
			});
		},
		Close : function() {
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_167,300,60,function() {
				sumo2.ajax.SendPost('includes/update.php','type=close',function(data) {
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_168,1);
					$('#sumo2-main-update').slideUp('slow');
					sumo2.accordion.ReloadAccordion('a_settings');
				});
			});	
		}
	}
};

$.fn.enterKey = function (fnc) {
    return this.each(function () {
        $(this).keypress(function (ev) {
            var keycode = (ev.keyCode ? ev.keyCode : ev.which);
            if (keycode == '13') {
                fnc.call(this, ev);
            }
        })
    })
}