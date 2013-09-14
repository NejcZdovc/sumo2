var st = {
	IE : /*@cc_on!@*/false,
	
	OPERA : window.opera?true:false,
	
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

	IE : /*@cc_on!@*/false,

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
	
	SendPost : function(location, parameters, func, sync) {
		var httpObj = this.GetAjaxObject();
		if(!httpObj) {
			response(false);
		}
		var response = func;
		if(typeof func == 'function') {
			httpObj.onreadystatechange = function() {
				if (httpObj.readyState == 4) {
					 if (httpObj.status == 200) {
						response(httpObj.responseText);        
					 } else {
						response(false);
					 }
				  }
			};	
		} else {
			httpObj.onreadystatechange = function() {};
		}
		if(sync === true) {
			httpObj.open('POST', location, false);
		} else {
			httpObj.open('POST', location, true);	
		}
		httpObj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		httpObj.setRequestHeader("Content-length", parameters.length);
		httpObj.setRequestHeader("Connection", "close");
		httpObj.send(parameters);
	},

	GetTarget : function(event) {
		if(event.srcElement) {
			return event.srcElement;
		} else {
			return event.target;	
		}
	},

	GetPos : function(event) {
		var Pos = { X:null, Y:null };
		if(this.IE) {
			Pos.X = event.clientX +(document.documentElement.scrollLeft || document.body.scrollLeft) -document.documentElement.clientLeft;
			Pos.Y = event.clientY +(document.documentElement.scrollTop || document.body.scrollTop) -document.documentElement.clientTop;	
		} else {
			Pos.X = event.pageX;
			Pos.Y = event.pageY;
		}
		return Pos;
	},

	GetPosition : function(obj) {
		var result = {top:0, left:0};
		if(obj.offsetParent) {
			do {
				result.top += obj.offsetTop;
				result.left += obj.offsetLeft;
			} while(obj = obj.offsetParent);
		}
		return result;
	},

	DisableSelection : function(target) {
		if (typeof target.onselectstart!="undefined") {
			target.onselectstart=function(){return false};
		} else if (typeof target.style.MozUserSelect!="undefined") {
			target.style.MozUserSelect="none";
		} else {
			target.onmousedown=function(){return false};
		}
		target.style.cursor = "default";
	},

	MODULES : [],
	PANELS : [],

	GetSize : function(obj) {
	    var size = {x1:0,y1:0,x2:0,y2:0};
	    var pos = this.GetPosition(obj);
	    size.x1 = pos.left;
	    size.y1 = pos.top;
	    size.x2 = pos.left + obj.clientWidth;
	    size.y2 = pos.top + obj.clientHeight;
	    return size;
	},

	CreateStructure : function() {
		var divs = document.getElementsByTagName('div');
		var len = divs.length;
		var secondary = len;
		var i = len;
		var mods = [];
		var panels = [];
		do {
			i = len - secondary;
			var obj = divs[i];
			if(obj.className === 'modules-template-item') {
				var size = this.GetSize(obj);
				mods.push({object:obj,location:size});
			} else if(obj.className === 'site-tree-panel') {
				var size = this.GetSize(obj);
				panels.push({object:obj,location:size});
			} else if(obj.className === 'site-tree-layout') {
				var size = this.GetSize(obj);
				panels.push({object:obj,location:size});
			}
		} while(--secondary);
		this.MODULES = mods;
		this.PANELS = panels;
	},
	
	DRAG : false,
	TARGET_OBJ : null,
	DRAG_OBJ : null,
	OVER_OBJ : null,

	StartDrag: function(obj, event) {
		var drag = document.createElement('div');
		drag.className = 'modules-template-item-drag';
		drag.innerHTML = obj.title;
		this.TARGET_OBJ = obj;
		var posClicked = this.GetPos(event);
		drag.style.top = (posClicked.Y -7) + 'px';
		drag.style.left = (posClicked.X -40) + 'px';
		document.body.appendChild(drag);
		this.DRAG_OBJ = drag;
		this.DRAG = true;
	},

	EndDrag : function() {
		document.body.style.cursor = 'default';
		this.DRAG_OBJ.parentNode.removeChild(this.DRAG_OBJ);
		this.DRAG = false;
		this.DRAG_OBJ = null;				
		if(this.OVER_OBJ != null) {
			var over = this.OVER_OBJ;
			var target = this.TARGET_OBJ;
			if(over.id.indexOf("#") > -1) {
				var params = "type=moveInner&obj=" + this.TARGET_OBJ.id + "&target=" + this.OVER_OBJ.id;
				this.SendPost("site.tree.data.php",params,function(data) {
					location.reload(true);
				});
			} else {
				var params = "type=moveOuter&obj=" + this.TARGET_OBJ.id + "&target=" + this.OVER_OBJ.id;
				this.SendPost("site.tree.data.php",params,function(data) {
					location.reload(true);
				});
			}
		}
	},

	GetInfo : function(evt, frameInfo) {
		var pos = st.GetPos(evt);
		pos.X -= frameInfo.left;
		pos.Y -= frameInfo.top;
		var target = document.elementFromPoint(pos.X,pos.Y);
		var ret = {lay:'notarget',page:0};
		if(target != null) {
			if(target.className === 'site-tree-panel') {
				ret.lay = target.id;
			} else if(target.className === 'modules-template-item') {
				ret.lay = target.id.split("#")[1];			
			} else if(target.className === 'site-tree-layout') {
				ret.lay = target.id;			
			}
		}
		ret.page = this.PAGE;
		return ret;
	},

	AddModules : function(id, name , pages, prefix, target, copyModule, currentPage) {
		var params = "type=add&page=C3GH64v8A5UcJ&name=" + name + "&id=" + id + "&pages=" + pages + "&prefix=" + prefix + "&target=" + target + "&copyModule=" + copyModule+ "&currentPage=" + currentPage;
		this.SendPost("site.tree.data.php",params,function(data) {
			location.reload(true);
		});
	},	

	ToggleButtons : function(obj,type) {
		var childs = obj.childNodes;
		for(var i=0,len = childs.length;i<len;i++) {
			if(childs[i].className == 'modules-template-options') {
				if(type == 'H') {
					childs[i].style.visibility = 'hidden';
				} else if(type == 'S') {
					childs[i].style.visibility = 'visible';
				}
			}
		}
	},

	TOGGLE_OBJ : null,
	
	ToggleModule : function(obj) {
	    if(this.TOGGLE_OBJ) {
	        this.TOGGLE_OBJ.parentNode.style.paddingTop = '3px';
	        obj.parentNode.style.paddingTop = '30px';
	        this.TOGGLE_OBJ = obj;
	    } else {
	       obj.parentNode.style.paddingTop = '30px';
	        this.TOGGLE_OBJ = obj;
	    }
	}, 
	
	tooltip : {
		MAX_WIDTH : 290,
		MAX_HEIGHT : 400,
		
		GetPosition : function(event) {
			var Pos = { X:null, Y:null };
			if(st.IE) {
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
			var maxWidth = st.TextWidth(data,'10px','"Trebuchet MS", Arial, sans-serif');
			if(maxWidth > this.MAX_WIDTH) {
				result.width = this.MAX_WIDTH;
			} else {
				result.width = maxWidth;	
			}
			var maxHeight = st.TextHeight(data,'10px','"Trebuchet MS", Arial, sans-serif',result.width+"px");
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
			st.client.SetVariables();
			if(pos.X + 1 + size.width + 30 > st.client.WIDTH ) {
				pos.X = pos.X - ((pos.X + 1 + size.width + 30)-st.client.WIDTH);	//Refine if problem
			}
			if(pos.Y + 25 + size.height + 30 > st.client.HEIGHT ) {
				pos.Y = pos.Y - ((pos.Y + 25 + size.height + 30)-st.client.HEIGHT); //Refine if problem
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
							if(!event) var event = window.event;
							var pos = st.tooltip.GetPosition(event);
							st.tooltip.CreateTooltip(pos,this.realData);
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
		}
	}
};

st.AddLoadEvent(function() {
	st.CreateStructure();
	st.DisableSelection(document.body);
	st.tooltip.FindTooltips(document.getElementsByTagName('body')[0]);
	st.AddEvent(document.body,'mousedown',function(evt) {
   		if(!evt) var evt = window.event;
        	var target = st.GetTarget(evt);
        	if(target.className == 'modules-template-item') {
        	    	st.StartDrag(target,evt);
            		document.body.style.cursor = 'pointer';
       		}
    	},false);
	st.AddEvent(document.body,'mousemove',function(evt) {
		if(!evt) var evt = window.event;
		if(st.DRAG === true && st.DRAG_OBJ != null) {
			var pos = st.GetPos(evt);
			var obj = st.DRAG_OBJ;
			obj.style.left = (pos.X - 40) + 'px';
			obj.style.top = (pos.Y - 7) + 'px';
			var modules = st.MODULES;
			var len = modules.length;
			var secondary = len;
			var i = len;
			st.OVER_OBJ = null;
			var found = false;
			do {
				i = len - secondary;
				var mod = modules[i];
				if(pos.X > mod.location.x1 && pos.X < mod.location.x2 && pos.Y > mod.location.y1 && pos.Y < mod.location.y2) {
					if(mod.object.id != st.TARGET_OBJ.id) {					
						st.ToggleModule(mod.object);
						st.OVER_OBJ = mod.object;
					}
					found = true;
					break;
				}
			} while(--secondary);
			if(found === false) {
				var panels = st.PANELS;
				var len = panels.length;
				var secondary = len;
				var i = len;
				do {
					i = len - secondary;
					var mod = panels[i];
					if(pos.X > mod.location.x1 && pos.X < mod.location.x2 && pos.Y > mod.location.y1 && pos.Y < mod.location.y2) {
						st.OVER_OBJ = mod.object;
						if(st.TOGGLE_OBJ != null) {
							st.TOGGLE_OBJ.parentNode.style.paddingTop = '3px';
							st.TOGGLE_OBJ = null;
						}
						break;
					}
				} while(--secondary);
			}
		}
	},false);
	st.AddEvent(document.body,'mouseup',function(evt) {
		if(!evt) var evt = window.event;
		if(st.DRAG === true && st.DRAG_OBJ != null) {
			if(st.TOGGLE_OBJ != null) {
				st.TOGGLE_OBJ.parentNode.style.paddingTop = '3px';
				st.TOGGLE_OBJ = null;
			}
			st.EndDrag();
		}
		var checkFake = parent.document.getElementsByClassName('modules-itemdrag')[0];
		if (checkFake) parent.document.body.removeChild(checkFake);
	},false);
	window.onresize = function() {
		st.CreateStructure();
	};
});