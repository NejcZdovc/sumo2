sumo2.favorites = {
	ReloadFavorites : function() {
		var content = document.getElementById('sumo2-favourites');
		content.innerhTML = '<div class="ajax"></div>';
		sumo2.ajax.SendPost('includes/favorites.menu.php','',function(data) {
			content.innerHTML = data;
			var stevilo=Math.floor(sumo2.client.LEFT_HEIGHT/75);
			for(i=stevilo+1; i<=10; i++) {
				$('#fav-item-'+i).css('display', 'none');				
			}
		});
	},
	
	SaveFavorites : function() {
		var options = new Array(0,0,0,0,0,0,0,0,0,0);
		var list = document.favoritesEdit.favorites;
		var listLen = list.length;
		var counter = 0;
		for(var i=0;i<listLen;i++) {
			if(list[i].checked) {
				options[counter] = list[i].value;
				counter++;
			}
		}
		var params = "";
		for(var i=0;i<10;i++) {
			params += "$!$o"+(i+1)+"="+options[i];
		}
		var obj = this;
		sumo2.ajax.SendPost('includes/favorites.edit.php',params,function(data) {
                obj.ReloadFavorites();
        });
	},
	
	CheckSelection : function(obj) {
		if(obj.checked) {
			obj.checked = false;
		} else {
			obj.checked = true;
		}
		var list = document.favoritesEdit.favorites;
		var listLen = list.length;
		var checkedNum = 0;
		for(var i=0;i<listLen;i++) {
			if(list[i].checked) {
				checkedNum++;
			}
		}
		if(checkedNum >= 10) {
			if(obj.checked) {
				obj.checked = false;
			} else {
				obj.checked = false;
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.FAV_WARNING,200,200,1);
			}
		} else {
			if(obj.checked) {
				obj.checked = false;
			} else {
				obj.checked = true;
			}
		}
	}
};
sumo2.user = {
	CheckPassword : function(obj, ex) {
		var intScore   = 0 ;
		var strVerdict = sumo2.language.VARIABLES.USER_PASS_VWEAK;
		var strIndicator = '<div style="display:block;background-image:url(images/css_sprite.png);background-position:-1109px -1634px;width:230px;height:19px;"></div>';
		var color = "#cc5656";
		var passwd = obj.value;
		
		if (passwd.length<5) {
			intScore = (intScore+3);
		} else if (passwd.length>4 && passwd.length<8) {
			intScore = (intScore+6);
		} else if (passwd.length>7 && passwd.length<16) {
			intScore = (intScore+12);
		} else if (passwd.length>15) {
			intScore = (intScore+18);
		}
		if (passwd.match(/[a-z]/)) {
			intScore = (intScore+1);
		} 
		if (passwd.match(/[A-Z]/)) {
			intScore = (intScore+5);
		}
		if (passwd.match(/\d+/)) {
			intScore = (intScore+5);
		}
		if (passwd.match(/(.*[0-9].*[0-9].*[0-9])/)) {
			intScore = (intScore+5);
		}
		if (passwd.match(/.[!,@,#,$,%,^,&,*,?,_,~]/)) {
			intScore = (intScore+5);
		}
		if (passwd.match(/(.*[!,@,#,$,%,^,&,*,?,_,~].*[!,@,#,$,%,^,&,*,?,_,~])/)) {
			intScore = (intScore+5);
		}
		if (passwd.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
			intScore = (intScore+2);
		}
		if (passwd.match(/([a-zA-Z])/) && passwd.match(/([0-9])/)) {
			intScore = (intScore+2);
		}
		if (passwd.match(/([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,?,_,~])|([!,@,#,$,%,^,&,*,?,_,~].*[a-zA-Z0-9])/)) {
			intScore = (intScore+2);
		}
		if(intScore < 16) {
			strIndicator = '<div style="display:block;background-image:url(images/css_sprite.png);background-position:-1109px -1634px;width:230px;height:19px;"></div>';
			strVerdict = sumo2.language.VARIABLES.USER_PASS_VWEAK;
			color = "#cc5656";
		} else if (intScore > 15 && intScore < 25) {
			strIndicator = '<div style="display:block;background-image:url(images/css_sprite.png);background-position:-1109px -1661px;width:230px;height:19px;"></div>';
			strVerdict = sumo2.language.VARIABLES.USER_PASS_WEAK;
			color = "#ffcc33";
		} else if (intScore > 24 && intScore < 35) {
			strIndicator = '<div style="display:block;background-image:url(images/css_sprite.png);background-position:-868px -1634px;width:230px;height:19px;"></div>';
			strVerdict = sumo2.language.VARIABLES.USER_PASS_MEDIUM;
			color = "#ffff66";
		} else if (intScore > 34 && intScore < 45) {
			strIndicator = '<div style="display:block;background-image:url(images/css_sprite.png);background-position:-868px -1661px;width:230px;height:19px;"></div>';
			strVerdict = sumo2.language.VARIABLES.USER_PASS_STRONG;
			color = "#a9d44c";
		} else {
			strIndicator = '<div style="display:block;background-image:url(images/css_sprite.png);background-position:-868px -1689px;width:230px;height:19px;"></div>';
			strVerdict = sumo2.language.VARIABLES.USER_PASS_VSTRONG;
			color = "#6383bc";
		}
		var indicator = document.getElementById("password_indicator_"+ex);
		indicator.innerHTML = strIndicator;
		var strength = document.getElementById("password_strength_"+ex);
		strength.innerHTML = strVerdict;
		strength.style.color = color;	
	},
	
	SELECTED : null,
	SEL_ID : null,

	ToggleInfo : function(id, obj) {
		if(obj.innerHTML == '+') {
			obj.innerHTML = '-';
			var hiddenRow = document.getElementById('sumo2-user-row-'+id);
			hiddenRow.style.display = 'table-row';
			if(this.SELECTED === null) {
				this.SELECTED = obj;
				this.SEL_ID = id;
			} else {
				this.SELECTED.innerHTML = '+';
				var hiddenRow = document.getElementById('sumo2-user-row-'+this.SEL_ID);
				hiddenRow.style.display = 'none';
				this.SELECTED = obj;
				this.SEL_ID = id;
			}
		} else {
			obj.innerHTML = '+';
			var hiddenRow = document.getElementById('sumo2-user-row-'+id);
			hiddenRow.style.display = 'none';
			this.SELECTED = null;
			this.SEL_ID = null;
		}
	},

	CheckGrouplist : function() {
		var sel =  document.d_user_add_field.group.selectedIndex;
		var row = document.getElementById("sumo2-grouplist");
		var min = document.getElementById("sumo2-fields-min");
		var max = document.getElementById("sumo2-fields-max");
		if(sel > 3 && sel < 7) {
			row.style.display = 'table-row';
			min.style.display = 'none';
			max.style.display = 'none';
		} else {
			row.style.display = 'none';
			min.style.display = 'table-row';
			max.style.display = 'table-row';
		}
	},

	GetRadio : function(obj) {
		for(var i=0;i<obj.length;i++) {
			if(obj[i].checked) {
				return obj[i].value;
			}
		}
		return "NOWAY";
	},

	GetCheckBox : function(obj) {
		var output = "";
		for(var i=0;i<obj.length;i++) {
			if(obj[i].checked) {
				output += "!" + obj[i].value;
			}
		}
		output = output.replace("!","");
		return output;
	},

	AddField : function() {
		var problem = "";
		var fieldName = document.d_user_add_field.fname.value;
		var name = document.d_user_add_field.name.value;
		var fid = document.d_user_add_field.fid.value;
		var descr = document.d_user_add_field.descr.value;
		var type = document.d_user_add_field.group.options[document.d_user_add_field.group.selectedIndex].value;
		var grouplist = document.d_user_add_field.grouplist.value;		
		var required = this.GetRadio(document.d_user_add_field.required);
		var min = document.d_user_add_field.min.value;
		var max = document.d_user_add_field.max.value;
		if(!sumo2.validate.IsLength(fieldName,2,60)) {
			problem += sumo2.language.VARIABLES.MOD_63;
		}
		if(!sumo2.validate.IsAlphaNumerical(name,3,40)) {
			problem += sumo2.language.VARIABLES.MOD_64;
		}
		if(!sumo2.validate.IsAlphaNumerical(fid,3,20)) {
			problem += sumo2.language.VARIABLES.MOD_65;
		}
		if(!sumo2.validate.IsNumerical(type,1,10)) {
			problem += sumo2.language.VARIABLES.MOD_66;
		}
		if(!sumo2.validate.IsNumerical(required,1,10)) {
			problem += sumo2.language.VARIABLES.MOD_67;
		}
		if(type <= 4 || type >= 8) {
			if(!sumo2.validate.IsNumerical(min,1,10)) {
				problem += sumo2.language.VARIABLES.MOD_68;
			}
			if(!sumo2.validate.IsNumerical(max,1,10)) {
				problem += sumo2.language.VARIABLES.MOD_69;
			}
		}
		if(problem !== "") {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,problem,450,200,1);
		} else {
			var params = "mode=add$!$fname=" + fieldName + "$!$name=" + name + "$!$fid=" + fid + "$!$type=" + type + "$!$required=" + required + "$!$min=" + min + "$!$max=" + max + "$!$descr=" + descr + "$!$grlist=" + grouplist;
			sumo2.ajax.SendPost("includes/user.fields.php",params,function(data) {
				if(data == 'ok') {
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_70,1);
		       		sumo2.accordion.ReloadAccordion('a_user_view_f');
					sumo2.dialog.ReloadDialog('d_user_add_fields');
				} else if(data == 'exists') {
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_71,200,200,1);
				}
			});
		}
	},

	ChangeStatusField : function(id) {
		var params = "mode=status$!$id="+id;
		sumo2.ajax.SendPost("includes/user.fields.php",params,function(data) {
            		sumo2.accordion.ReloadAccordion('a_user_view_f');
        	});	
	},

	DeleteField : function(id) {
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_72,250,30,function() {
			var params = "mode=delete$!$id="+id;
			sumo2.ajax.SendPost("includes/user.fields.php",params,function(data) {
	            		sumo2.accordion.ReloadAccordion('a_user_view_f');
	       		 });
		});	
	},

	EditFieldShow : function(id) {
        	sumo2.dialog.NewDialog('d_user_edit_fields',"id="+id);
	},

	EditField : function() {
		var problem = "";
		var id = document.d_user_edit_field.verify.value;
		var fieldName = document.d_user_edit_field.fname.value;
		var name = document.d_user_edit_field.name.value;
		var fid = document.d_user_edit_field.fid.value;
		var descr = document.d_user_edit_field.descr.value;
		if(document.d_user_edit_field.grouplist) {
			var grouplist = document.d_user_edit_field.grouplist.value;
		}
		var type = document.d_user_edit_field.group.options[document.d_user_edit_field.group.selectedIndex].value;
		var required = this.GetRadio(document.d_user_edit_field.required);
		if(document.d_user_edit_field.min) {
			var min = document.d_user_edit_field.min.value;
			var max = document.d_user_edit_field.max.value;
		}
		if(!sumo2.validate.IsLength(fieldName,2,60)) {
			problem += sumo2.language.VARIABLES.MOD_63;
		}
		if(!sumo2.validate.IsAlphaNumerical(name,3,20)) {
			problem += sumo2.language.VARIABLES.MOD_64;
		}
		if(!sumo2.validate.IsAlphaNumerical(fid,3,20)) {
			problem += sumo2.language.VARIABLES.MOD_65;
		}
		if(!sumo2.validate.IsNumerical(type,1,10)) {
			problem += sumo2.language.VARIABLES.MOD_66;
		}
		if(!sumo2.validate.IsNumerical(required,1,10)) {
			problem += sumo2.language.VARIABLES.MOD_67;
		}
		if(document.d_user_edit_field.min) {
			if(!sumo2.validate.IsNumerical(min,1,10)) {
				problem += sumo2.language.VARIABLES.MOD_68;
			}
			if(!sumo2.validate.IsNumerical(max,1,10)) {
				problem += sumo2.language.VARIABLES.MOD_69;
			}
		}
		if(problem !== "") {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,problem,200,200,1);
		} else {
			var params = "mode=edit$!$fname=" + fieldName + "$!$name=" + name + "$!$fid=" + fid + "$!$type=" + type + "$!$required=" + required + "$!$id=" + id + "$!$descr=" + descr;
			if(document.d_user_edit_field.min) {
				params += "$!$min=" + min + "$!$max=" + max;
			}
			if(document.d_user_edit_field.grouplist) {			
				params += "$!$grlist=" + grouplist;
			}
			sumo2.ajax.SendPost("includes/user.fields.php",params,function(data) {
				if(data == 'ok') {
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_134,1);
		       		sumo2.accordion.ReloadAccordion('a_user_view_f');
					sumo2.dialog.CloseDialog('d_user_edit_fields');
				}
			});
		}
	},

	SaveUser : function() {
		var problem = "";
		var name = document.d_user_add_user.name.value;
		var username = document.d_user_add_user.username.value;
		var password = document.d_user_add_user.password1.value;
		var password2 = document.d_user_add_user.password2.value;

		var email = document.d_user_add_user.email1.value;
		var email2 = document.d_user_add_user.email2.value;
		var group = document.d_user_add_user.group;
		if(!sumo2.validate.IsAlpha(name,3,20)) {
			problem += sumo2.language.VARIABLES.USER_CHECK_NAME+'<br />';
		}
		if(!sumo2.validate.IsAlphaNumerical(username,2,10)) {
			problem += '-'+sumo2.language.VARIABLES.USER_CHECK_USER+'<br />';
		}
		if(username.length < 2 && username.value != "") {
			problem += '-'+sumo2.language.VARIABLES.USER_CHECK_USER_2+'<br />';
		}
		if(password == "") {
			problem += sumo2.language.VARIABLES.USER_CHECK_PASS+'<br />'
		}
		if(password != password2) {
			problem += '-'+sumo2.language.VARIABLES.USER_CHECK_PASS_2+'<br />';
		}
		if(!sumo2.validate.IsEmail(email,5,70)) {
			problem += '-'+sumo2.language.VARIABLES.USER_CHECK_EMAIL+'<br />';
		}
		if(email != email2) {
			problem += '-'+sumo2.language.VARIABLES.USER_CHECK_EMAIL_2+'<br />';
		}
		var extraParams = '';
		var form = document.d_user_add_user;
		var len = form.length-1;
		var forsakenChecks = [];
		var reqChecker = [];
		do {
			if(form[len].className && form[len].className.search('extra') > -1) {
				if(form[len].type == "radio") {
					var idArray = form[len].id.split("#");
					if(idArray[2] == '1') {
						reqChecker.push(idArray[0]+"#"+idArray[3]);					
					}
					if(form[len].checked == true) {
						extraParams += "$!$"+ idArray[0] +"_e_x_t_r_a="+form[len].value;
					} else {
						forsakenChecks.push(idArray[0]);
					}
				} else if(form[len].type == "checkbox") {
					var idArray = form[len].id.split("#");
					if(idArray[2] == '1') {
						reqChecker.push(idArray[0]+"#"+idArray[3]);					
					}
					if(form[len].checked == true) {
						if(extraParams.search(idArray[0] +"_e_x_t_r_a=") > -1) {
							extraParams = extraParams.replace(idArray[0] +"_e_x_t_r_a=",idArray[0] +"_e_x_t_r_a="+form[len].value+"!");
						} else {
							extraParams += "$!$"+ idArray[0] +"_e_x_t_r_a="+form[len].value;
						}
					} else {
						forsakenChecks.push(idArray[0]);
					}
				} else if(form[len].tagName == "SELECT") {
					var idArray = form[len].id.split("#");
					extraParams += "$!$"+ idArray[0] +"_e_x_t_r_a="+form[len].options[form[len].selectedIndex].value;
				} else {
					var idArray = form[len].id.split("#");
					extraParams += "$!$"+ idArray[0] +"_e_x_t_r_a="+form[len].value;
				}
			}
		} while(--len);
		len = forsakenChecks.length -1;
		do {
			if(len < 0) break;
			if(extraParams.search(forsakenChecks[len] +"_e_x_t_r_a=") <= -1) {
				extraParams += "$!$"+ forsakenChecks[len] +"_e_x_t_r_a=0";
			}
		} while(--len);
		if(problem !== "") {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,problem,400,400,1);
		} else {
			var params = "type=add$!$name="+name+"$!$username="+username+"$!$password="+password+"$!$email="+email+"$!$group="+group.options[group.selectedIndex].value+extraParams;
			sumo2.ajax.SendPost("includes/user.management.php",params,function(data) {
				data=data.replace("\n", "");
				data=data.replace("\r", "");
				data=data.replace("\t", "");
	           	if(data == 'ok') {
	           		sumo2.message.NewMessage(sumo2.language.VARIABLES.USER_EDIT_SUCC_3,1);
		       		sumo2.accordion.ReloadAccordion('a_user_view_u');
					sumo2.dialog.ReloadDialog('d_user_add_user');
					sumo2.accordion.ReloadAccordion('a_user_view_g');
	            } else if(data == 'username') {
	            	sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.USER_CHECK_USER_2,200,200);
	            }else if(data == 'password') {
	            	sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.USER_CHECK_PASS_3,200,200);
	            } else if(data == 'group') {
	            	sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.USER_CHECK_GROUP,200,200);
	            } else if(data == 'same') {
	            	sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.USER_CHECK_USER_3,200,200);
	            } else if(data == 'email') {
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_77,200,200);
			}
	        });
		}	
	},
	
	ChangeStatus : function(id,accId) {
		var params = "type=status$!$id="+id;
		sumo2.ajax.SendPost("includes/user.management.php",params,function(data) {





				if(accId) {
					sumo2.accordion.ReloadAccordion(accId);
					sumo2.accordion.ReloadAccordion('a_user_view_u');
				} else {
            		sumo2.accordion.ReloadAccordion('a_user_view_u');
				}
        });	
	},
	
	DeleteUser : function(id,accId) {
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.USER_USER_DELETE,250,30,function() {
			var params = "type=delete$!$id="+id;
			sumo2.ajax.SendPost("includes/user.management.php",params,function(data) {
	            if(accId) {
					sumo2.accordion.ReloadAccordion(accId);
					sumo2.accordion.ReloadAccordion('a_user_view_u');
					sumo2.accordion.ReloadAccordion('a_user_view_g');
				} else {
            		sumo2.accordion.ReloadAccordion('a_user_view_u');
					sumo2.accordion.ReloadAccordion('a_user_view_g');
				}
				sumo2.accordion.ReloadAccordion('a_trash');
	        });
		});	
	},
	
	EditUser : function(id,accId) {
		if(accId) {
			sumo2.dialog.NewDialog('d_user_edit_user',"id="+id+"&accId="+accId);
		} else {
        	sumo2.dialog.NewDialog('d_user_edit_user',"id="+id);
		}
	},
	
	UpdateUser : function() {
		var problem = "";
		var id = document.d_user_edit_user.verify.value;
		var name = document.d_user_edit_user.name.value;
		var password = document.d_user_edit_user.password.value;
		var password2 = document.d_user_edit_user.password2.value;
		var email = document.d_user_edit_user.email.value;
		var email2 = document.d_user_edit_user.email2.value;
		var group = document.d_user_edit_user.group;
		var accId = document.d_user_edit_user.accid.value;
		if(!sumo2.validate.IsAlpha(name,3, 20)) {
			problem += '-'+sumo2.language.VARIABLES.USER_CHECK_NAME+'<br />';
		}
		if(!sumo2.validate.IsEmail(email,5,100)) {
			problem += '-'+sumo2.language.VARIABLES.USER_CHECK_EMAIL+'<br />';
		}
		if(email != email2) {
			problem += '-'+sumo2.language.VARIABLES.USER_CHECK_EMAIL_2+'<br />';
		}
		if(password.length>0 && password != password2) {
			problem += '-'+sumo2.language.VARIABLES.USER_CHECK_PASS_2+'<br />';
		}
		var extraParams = '';
		var form = document.d_user_edit_user;
		var len = form.length-1;
		var forsakenChecks = [];
		var reqChecker = [];
		do {
			if(form[len].className && form[len].className.search('extra') > -1) {
				if(form[len].type == "radio") {
					var idArray = form[len].id.split("#");
					if(idArray[2] == '1') {
						reqChecker.push(idArray[0]+"#"+idArray[3]);					
					}
					if(form[len].checked == true) {
						extraParams += "$!$"+ idArray[0] +"_e_x_t_r_a="+form[len].value;
					} else {
						forsakenChecks.push(idArray[0]);
					}
				} else if(form[len].type == "checkbox") {
					var idArray = form[len].id.split("#");
					if(idArray[2] == '1') {
						reqChecker.push(idArray[0]+"#"+idArray[3]);					
					}
					if(form[len].checked == true) {
						if(extraParams.search(idArray[0] +"_e_x_t_r_a=") > -1) {
							extraParams = extraParams.replace(idArray[0] +"_e_x_t_r_a=",idArray[0] +"_e_x_t_r_a="+form[len].value+"!");
						} else {
							extraParams += "$!$"+ idArray[0] +"_e_x_t_r_a="+form[len].value;
						}
					} else {
						forsakenChecks.push(idArray[0]);
					}
				} else if(form[len].tagName == "SELECT") {
					var idArray = form[len].id.split("#");
					extraParams += "$!$"+ idArray[0] +"_e_x_t_r_a="+form[len].options[form[len].selectedIndex].value;
				} else {
					var idArray = form[len].id.split("#");					
					extraParams += "$!$"+ idArray[0] +"_e_x_t_r_a="+form[len].value;
				}
			}
		} while(--len);
		len = forsakenChecks.length -1;
		do {
			if(len < 0) break;
			if(extraParams.search(forsakenChecks[len] +"_e_x_t_r_a=") <= -1) {
				extraParams += "$!$"+ forsakenChecks[len] +"_e_x_t_r_a=0";
			}
		} while(--len);
		if(problem !== "") {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,problem,200,200,1);
		} else {
			if(password == "") {
				var params = "type=edit$!$id="+id+"$!$name="+name+"$!$email="+email+"$!$group="+group.options[group.selectedIndex].value+extraParams;
			} else {
				var params = "type=edit$!$newpassword="+password+"$!$id="+id+"$!$name="+name+"$!$email="+email+"$!$group="+group.options[group.selectedIndex].value+extraParams;
			}
			sumo2.ajax.SendPost("includes/user.management.php",params,function(data) {
				data=data.replace("\n", "");
				data=data.replace("\r", "");
				data=data.replace("\t", "");
	           	if(data == 'ok') {					
	           		sumo2.message.NewMessage(sumo2.language.VARIABLES.USER_EDIT_SUCC,1);
		       		if(accId != "") {
						sumo2.accordion.ReloadAccordion(accId);
						sumo2.accordion.ReloadAccordion('a_user_view_u');
					} else {
						sumo2.accordion.ReloadAccordion('a_user_view_u');
					}
					sumo2.dialog.CloseDialog('d_user_edit_user');
					sumo2.accordion.ReloadAccordion('a_user_view_g');
	            } else if(data == 'group') {
	            	sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.USER_CHECK_GROUP,200,200);
	            } else if(data == 'password') {
	            	sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.USER_CHECK_PASS_3,200,200);
	            } else if(data == 'oldpassword') {
	            	sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.USER_CHECK_PASS_4,200,200);
	            } else if(data == 'id') {
	            	sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.USER_CHECK_ID,200,200);
	            }
	        });
		}
	},
	
	ChangeStatusGroup : function(id) {
		var params = "type=statusgroup$!$id="+id;
		sumo2.ajax.SendPost("includes/user.management.php",params,function(data) {
            sumo2.accordion.ReloadAccordion('a_user_view_g');
        });
	},
	
	DeleteGroup : function(id) {
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.USER_GOUP_DELETE,250,30,function() {
			var params = "type=deletegroup$!$id="+id;
			sumo2.ajax.SendPost("includes/user.management.php",params,function(data) {
	            sumo2.accordion.ReloadAccordion('a_user_view_g');
				sumo2.accordion.ReloadAccordion('a_user_view_u');
				sumo2.accordion.ReloadAccordion('a_trash');
	        });
		});
	},
	
	SelectAll : function(type) {
		var obj = document.getElementById('sumo2-user-group-permission').getElementsByTagName('input');
		var len = obj.length;
		var secondary = len;
		var i = null;
		do {
			i = len - secondary;
			if(i == len) break;
			var chkBox = obj[i];
			if(type === 1) {
				chkBox.checked = true;
				chkBox.parentNode.parentNode.style.background = '#efefef';	
			} else {
				chkBox.checked = false;
				chkBox.parentNode.parentNode.style.background = '#ffffff';
			}
		} while(--secondary);
	},
	
	ToggleRow : function(obj) {
	    if(obj.checked) {
	        obj.parentNode.parentNode.style.background = '#efefef';
	   } else {
	        obj.parentNode.parentNode.style.background = '#ffffff';
	    }
	},
	
	GetJSONPermission : function() {
		var obj = document.getElementById('sumo2-user-group-permission').getElementsByTagName('input');
		var len = obj.length;
		var secondary = len;
		var i = null;
		var JSONobject = new Object();
		JSONobject.access = new Array();
		do {
			i = len - secondary;
			if(i == len) break;
			var chkBox = obj[i];
			if(chkBox.checked) {
				var sel = document.getElementById(chkBox.value).value;
				var id = chkBox.value.replace('sumo2-user-group-sel-','');
				JSONobject.access.push({id:id,level:sel});
			}
		} while(--secondary);
		JSONstring = JSON.stringify(JSONobject);
		return JSONstring;
	},
	
	SaveGroup : function() {
		var problem = "";
		var name = document.d_user_add_group.name.value;
		var description = document.d_user_add_group.description.value;
		var access = this.GetJSONPermission();
		var domains="";
		$("#d_user_add_group #domain :selected").each(function () {
			domains += $(this).val() + "*/*";
		});
		domains=domains.slice(0,domains.length-3);
		if(!sumo2.validate.IsAlpha(name,3,20)) {
			problem += '-'+sumo2.language.VARIABLES.USER_CHECK_NAME+'<br />';
		}
		if(domains.length==0) {
			problem += '-'+sumo2.language.VARIABLES.MOD_199;
		}
		if(problem !== "") {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,problem,200,200);
		} else {
			var params = "type=addgroup$!$name="+name+"$!$description="+description+"$!$access="+access+"$!$domains="+domains;
			sumo2.ajax.SendPost("includes/user.management.php",params,function(data) {
	           	if(data == 'ok') {
	           		sumo2.message.NewMessage(sumo2.language.VARIABLES.USER_EDIT_SUCC_2,1);
		       		sumo2.accordion.ReloadAccordion('a_user_view_g');
					sumo2.dialog.ReloadDialog('d_user_add_group');
				} else {
					sumo2.message.NewMessage(data, 3);	
				}
	        });
		}
	},
	
	EditGroup : function(id) {
		sumo2.dialog.NewDialog('d_user_edit_group',"id="+id);
	},
	
	UpdateGroup : function() {
		var problem = "";
		var name = document.d_user_edit_group.name.value;
		var description = document.d_user_edit_group.description.value;
		var access = this.GetJSONPermission();
		var id = document.d_user_edit_group.verify.value;
		var domains="";
		$("#d_user_edit_group #domain :selected").each(function () {
			domains += $(this).val() + "*/*";
		});
		domains=domains.slice(0,domains.length-3);
		if(!sumo2.validate.IsAlpha(name,3,20)) {
			problem += '-'+sumo2.language.VARIABLES.USER_CHECK_NAME+'<br />';
		}
		if(domains.length==0) {
			problem += '-'+sumo2.language.VARIABLES.MOD_199;
		}
		if(problem !== "") {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,problem,200,200);
		} else {
			var obj = this;
			var params = "type=editgroup$!$id="+id+"$!$name="+name+"$!$description="+description+"$!$access="+access+"$!$domains="+domains;
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
				sumo2.ajax.SendPost("includes/user.management.php",params,function(data) {
					if(data == 'ok') {
						setTimeout("window.location.reload()", 2000);
					}
				});
			});
		}
	}
};
sumo2.article = {
	ChangeStatusArticle : function(id,accId) {
		var params = "type=statusarticle$!$id="+id;
		sumo2.ajax.SendPost("includes/article.php",params,function(data) {
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_189,1);
		        if(accId) {
		            sumo2.accordion.ReloadAccordion(accId);
		            sumo2.accordion.ReloadAccordion('a_article_view_a');
		        } else {
		            sumo2.accordion.ReloadAccordion('a_article_view_a');
		        }
        });
	},
	
	ChangeView : function(id, edit) {
		var form=$(edit).closest('form');
		if(id=='summary') {
			form.find('#articleT_summary').css('font-weight','bold');
			form.find('#articleS_summary').css('display','block');
			form.find('#summery').css('display','block');
		} else {
			form.find('#articleT_summary').css('font-weight','normal');
			form.find('#articleS_summary').css('display','none');
			form.find('#summery').css('display','none');
		}
		if(id=='keywords') {
			form.find('#articleT_keywords').css('font-weight','bold');
			form.find('#articleS_keywords').css('display','block');
			form.find('#keywords').css('display','block');
		} else {
			form.find('#articleT_keywords').css('font-weight','normal');
			form.find('#articleS_keywords').css('display','none');
			form.find('#keywords').css('display','none');
		}
		if(id=='description') {
			form.find('#articleT_description').css('font-weight','bold');
			form.find('#articleS_description').css('display','block');
			form.find('#description').css('display','block');
		} else {
			form.find('#articleT_description').css('font-weight','normal');
			form.find('#articleS_description').css('display','none');
			form.find('#description').css('display','none');
		}
	},
	
	DeleteArticle : function(id,accId) {
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING, sumo2.language.VARIABLES.MOD_1 ,250,30,function() {
			var params = "type=deletearticle$!$id="+id;
			sumo2.ajax.SendPost("includes/article.php",params,function(data) {
	            if(accId) {
		            sumo2.accordion.ReloadAccordion(accId);
		            sumo2.accordion.ReloadAccordion('a_article_view_a');
		        } else {
		            sumo2.accordion.ReloadAccordion('a_article_view_a');
		        }
				sumo2.accordion.ReloadAccordion('a_trash');
	        });
		});
	},
	
	GetSize : function(name, obj) {
		var mainWidth = sumo2.client.WIDTH - 100;
		var mainHeight = sumo2.client.HEIGHT - 100;
		var img = new Image();
		img.src = name;
		var scale = img.height/img.width;
		var height = img.height;
		var width = img.width;
		var step = 10;
		while(height > mainHeight || width > mainWidth) {
			height -= Math.floor(step*scale);
			width -= step;
		}
		obj.height = height;
		obj.width = width;
	},

	SaveCatNewArticle: function(type){
		var pages="";
		var dialog = document.getElementById('d_article_cat_id');
		if(document.getElementById('edit_id'))
			var id=document.getElementById('edit_id').value;
		var cats = '';
		var inputs = dialog.getElementsByTagName('input');
		for(var i=0,len=inputs.length;i<len;i++) {
			if(inputs[i].type == 'checkbox' && inputs[i].checked) {
				pages += inputs[i].value+"#??#";
			}
		}
		if(pages.length <= 0) {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_79,250,250,1);
		}
		else {
			if(type=="N")
				document.getElementById('category_new_article').value=pages;
			else if(type=="T")
				document.getElementById('category_new_article_translate').value=pages;
			else if(id.length>2)
				document[id].category_edit_article.value=pages;
			sumo2.dialog.CloseDialog('d_article_cat');			
		}
	},

	ShowPicture : function(name) {
		var doc = document;
		var mainWidth = sumo2.client.WIDTH;
		var mainHeight = sumo2.client.HEIGHT;
		var overlay = doc.createElement("div");
		overlay.className = "overlay";
		overlay.style.cursor = "pointer";
		overlay.style.width = mainWidth + "px";
		overlay.style.height = mainHeight + "px";
		overlay.onclick = function() {
			this.parentNode.removeChild(this);
		};
		var wrapper = doc.createElement("div");
		wrapper.className = "img-popup";
		var image = doc.createElement("img");
		image.src = name;
		this.GetSize(name, image);
		wrapper.style.top = (mainHeight - image.height)/2 + 'px';
		wrapper.style.left = (mainWidth - image.width)/2 + 'px';
		wrapper.appendChild(image);
		overlay.appendChild(wrapper);
		document.body.appendChild(overlay);
	},

	DeletePicture : function(id) {
		var parameters = "type=deleteimage$!$id="+id;
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_62,250,30,function() {
			sumo2.ajax.SendPost("includes/article.php",parameters,function(data) {
				sumo2.accordion.ReloadAccordion(sumo2.accordion.SELECTED);
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_80,1);
			});
		});
	},

	RenamePicture : function() {
		var id = document.d_article_image_rename.image_id.value;
		var name = document.d_article_image_rename.rename_article_image.value;
		var params = "type=renameimage$!$id="+id+"$!$name="+name;
		if(!sumo2.validate.IsLength(name,2,50)) {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_13,200,200,1);
		} else {
			sumo2.ajax.SendPost("includes/article.php",params,function(data) {
				sumo2.dialog.CloseDialog('d_article_image_rename');
				sumo2.accordion.ReloadAccordion(sumo2.accordion.SELECTED);
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_81,1);
			});
		}
	},
	
	SaveArticle : function() {
		var error="";
		var published ="";
		var title = document.a_article_new_a.title.value;
		if(!sumo2.validate.IsLength(title, 2, 400))
			error += sumo2.language.VARIABLES.MOD_2;
		var category = document.a_article_new_a.category_new_article.value;
		if(category=="")
			error += sumo2.language.VARIABLES.MOD_3;
		var datestart = document.getElementById("date-start-a").value;
		var dateend = document.getElementById("date-end-a").value;
		if (document.a_article_new_a.published[0].checked)
			 published = document.a_article_new_a.published[0].value;
		else
			published = document.a_article_new_a.published[1].value;
		var author = document.a_article_new_a.author.value;
		var alias = document.a_article_new_a.alias.value;
		var stub = document.a_article_new_a.summery.value;
		var content = CKEDITOR.instances.editor2.getData();	
		var keywords = document.a_article_new_a.keywords.value;
		var description = document.a_article_new_a.description.value;
		var array=$("#article_newTags").tagit("tags");
		var tags="";
		for (var i in array) {
			tags += array[i].value+"*/*";
		}
		tags=tags.slice(0,tags.length-3);
		if(category[0]!='#') {
			if(category[1]!='?')
			category="#??#"+category;
		}
		if(error.length==0)  {
			var params = "type=newarticle$!$title="+title+"$!$category="+category+"$!$datestart="+datestart+"$!$dateend="+dateend+"$!$published="+published+"$!$author="+author+"$!$alias="+alias+"$!$content="+content+"$!$stub="+stub+"$!$keywords="+keywords+"$!$description="+description+"$!$tags="+tags;
			sumo2.ajax.SendPost("includes/article.php",params,function(data) {
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_5,1);
					sumo2.accordion.ReloadAccordion('a_article_view_a');
					sumo2.accordion.ReloadAccordion('a_article_new_a');
			});
		} else
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,error,300,300,1);
		
	},
	
	EditArticle : function(editor) {
		var error="";
		var published ="";
		var title = document[editor].title.value;
		var id_a = document[editor].id_a.value;
		var id_ad = document[editor].id_ad.value;
		var accId = document[editor].accid.value;
		if(!sumo2.validate.IsLength(title, 2, 400))
			error += sumo2.language.VARIABLES.MOD_2;
		var category = document[editor].category_edit_article.value;
		if(category=="")
			error += sumo2.language.VARIABLES.MOD_3;
		var datestart = document.getElementById("date_start_e_"+editor).value;
		var dateend = document.getElementById("date_end_e_"+editor).value;
		var datecreate = document.getElementById("date_create_e_"+editor).value;
		if (document[editor].published[0].checked)
			 published = document[editor].published[0].value;
		else
			published = document[editor].published[1].value;
		var author = document[editor].author.value;
		var alias = document[editor].alias.value;
		var image = document[editor].image;
		var altPrefix = document[editor].altPrefix.value;
		var stub = document[editor].summery.value;
		var keywords = document[editor].keywords.value;
		var description = document[editor].description.value;	
		if(image!=null)
			image=image.value;
		var content = CKEDITOR.instances[editor].getData();
		if(document[editor].imageCat) {
			var imageCat=document[editor].imageCat.value;
		}
		else
			var imageCat = 0;
		if(category[0]!='#') {
			if(category[1]!='?')
			category="#??#"+category;
		}
		var array=$("#article_editTags_"+editor).tagit("tags");
		var tags="";
		for (var i in array) {
			tags += array[i].value+"*/*";
		}
		tags=tags.slice(0,tags.length-3);
		if(error.length==0)  {
			var params = "type=editarticle$!$title="+title+"$!$category="+category+"$!$datestart="+datestart+"$!$dateend="+dateend+"$!$published="+published+"$!$author="+author+"$!$alias="+alias+"$!$content="+content+"$!$id="+id_a+"$!$image="+image+"$!$stub="+stub+"$!$imageCat="+imageCat+"$!$keywords="+keywords+"$!$description="+description+"$!$datecreate="+datecreate+"$!$altPrefix="+altPrefix+"$!$tags="+tags;
				sumo2.ajax.SendPost("includes/article.php",params,function(data) {
						sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_5, 1);
						sumo2.accordion.ReloadAccordion('a_article_edit_a'+id_ad, 'id='+id_a);
						sumo2.accordion.ReloadAccordion('a_article_view_c');
						sumo2.accordion.ReloadAccordion('a_article_view_cd');
						sumo2.accordion.ReloadAccordion('a_article_view_a');
				});
		} else
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,error,300,300,1);
	},
	
	UploadImage : function (state,rlid) {
		if(state=="n") {
			$('#uploadify_'+rlid).uploadify('upload','*');
		}
		else if(state=="y") {
			var id_d = document[rlid].id_ad.value;
			var id_e = document[rlid].id_a.value;
			sumo2.accordion.ReloadAccordion('a_article_edit_a'+id_d, 'id='+id_e);
			sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_7,1);
		}
	},
	
	SaveGroup : function() {
		var error = "";
		var name = document.d_article_new_c.name.value;
		var description = document.d_article_new_c.content.value;
		if(!sumo2.validate.IsAlphaNumerical(name, 3, 30))
			error += sumo2.language.VARIABLES.MOD_8;
		if(error.length==0)  {
			var obj = this;
			var params = "type=addgroup$!$name="+name+"$!$description="+description;
			sumo2.ajax.SendPost("includes/article.php",params,function(data) {
	            sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_9,1);
				sumo2.accordion.ReloadAccordion('a_article_view_c');
				sumo2.dialog.CloseDialog('d_article_new_c');
	        });
		} else
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,error,200,200,1);
	},
	
	ChangeStatusGroup : function(id) {
		var params = "type=statusgroup$!$id="+id;
		sumo2.ajax.SendPost("includes/article.php",params,function(data) {
            	sumo2.accordion.ReloadAccordion('a_article_view_c');
            
        });
	},
	
	DeleteGroup : function(id) {
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_10,250,30,function() {
			var params = "type=deletegroup$!$id="+id;
			sumo2.ajax.SendPost("includes/article.php",params,function(data) {
	            	sumo2.accordion.ReloadAccordion('a_article_view_c');
					sumo2.accordion.ReloadAccordion('a_article_view_a');
					sumo2.accordion.ReloadAccordion('a_trash');
	        });
		});
	},
	
	UpdateGroup : function() {
		var error = "";
		var name = document.d_article_edit_c.name.value;
		var description = document.d_article_edit_c.description.value;
		var id = document.d_article_edit_c.verify.value;
		if(!sumo2.validate.IsAlphaNumerical(name, 3, 30))
			error += sumo2.language.VARIABLES.MOD_8;
			
		if(error.length==0)  {
			var params = "type=editgroup$!$id="+id+"$!$name="+name+"$!$description="+description;
			sumo2.ajax.SendPost("includes/article.php",params,function(data) {
	            sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_9,1);
				sumo2.dialog.CloseDialog('d_article_edit_c');
		        sumo2.accordion.ReloadAccordion('a_article_view_c');
	        });
		} else
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,error,200,200,1);
	},
	Translate_Category : function() {
		var error = "";
		var name = document.d_article_c_translate.name.value;
		var description = document.d_article_c_translate.content.value;
		var lang =document.d_article_c_translate.lang.value;
		var parent = document.d_article_c_translate.parent.value;
		if(!sumo2.validate.IsAlphaNumerical(name, 3, 30))
			error += sumo2.language.VARIABLES.MOD_8;	
		if(error.length==0)  {
			var params = "type=translate_c$!$lang="+lang+"$!$name="+name+"$!$description="+description+"$!$parent="+parent;
			sumo2.ajax.SendPost("includes/article.php",params,function(data) {
	            sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_9,1);
				sumo2.dialog.CloseDialog('d_article_c_translate');
		        sumo2.accordion.ReloadAccordion('a_article_view_c');
	        });
		} else
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,error,200,200,1);
	},
	
	Counter: function(id) {
		var params = "type=counter$!$id="+id;
		sumo2.ajax.SendPost("includes/article.php",params,function(data) {
	            sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_61,1);
		        sumo2.accordion.ReloadAccordion('a_article_view_a');
	   });
	},
	CounterAll: function() {
		var params = "type=counterall";
		sumo2.ajax.SendPost("includes/article.php",params,function(data) {
	            sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_61,1);
		        sumo2.accordion.ReloadAccordion('a_article_view_a');
	   });
	},
	
	Translate_Article : function() {
		var error="";
		var published ="";
		var title = document.a_article_a_translate.title.value;
		if(!sumo2.validate.IsAlphaNumerical(title, 2, 400))
			error += sumo2.language.VARIABLES.MOD_2;
		var category = document.a_article_a_translate.category.value;
		if(category=="0")
			error += sumo2.language.VARIABLES.MOD_3;
		var datestart = document.a_article_a_translate.date_start_t.value;
		var dateend = document.a_article_a_translate.date_end_t.value;
		if (document.a_article_a_translate.published[0].checked)
			 published = document.a_article_a_translate.published[0].value;
		else
			published = document.a_article_a_translate.published[1].value;
		var author = document.a_article_a_translate.author.value;
		var alias = document.a_article_a_translate.alias.value;
		var stub = document.a_article_a_translate.summery.value;
		var lang = document.a_article_a_translate.lang.value;
		var parent = document.a_article_a_translate.parent.value;
		var content = CKEDITOR.instances.editor6.getData();
		if(content.length<10)
			error += sumo2.language.VARIABLES.MOD_4;
			
		if(error.length==0)  {
			var params = "type=translate_a$!$title="+title+"$!$category="+category+"$!$datestart="+datestart+"$!$dateend="+dateend+"$!$published="+published+"$!$author="+author+"$!$alias="+alias+"$!$content="+content+"$!$lang="+lang+"$!$parent="+parent+"$!$stub="+stub;
			sumo2.ajax.SendPost("includes/article.php",params,function(data) {
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_5, 1);
					sumo2.accordion.ReloadAccordion('a_article_view_a');
					sumo2.accordion.CloseAccordion('a_article_a_translate');
			});
		} else
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,error,300,300,1);
		
	}
};
sumo2.ftp = {
	PATH : null,
	View : 'image',
	
	RefreshFileView : function(path) {
		if(path.search('/storage/') > -1 || path.search('/images/') > -1) {
			var param = "path="+path;
			sumo2.accordion.SetParamForAccordion("a_ftp","path="+path);
			document.getElementById("file_manager_path").value=path;
			var content = document.getElementById("file-manager-fileview");
			content.path = path;
			content.innerHTML = '<div class="ajax-loader"></div>';
			var page="";
			if(sumo2.accordion.GetParamFromAccordion("a_ftp", "view")=='image' || sumo2.accordion.GetParamFromAccordion("a_ftp", "view")==false) {
				sumo2.ajax.SendPost('pages/file.manager.file.view.table.php',param,function(data) {
					var content = document.getElementById("file-manager-fileview");
					content.innerHTML = data;
					$("#a_ftp").tablesorter({
						headers: { 
							2: { sorter: false 	}	
						}						
					});
				});
			}else {
				sumo2.ajax.SendPost('pages/file.manager.file.view.php',param,function(data) {
						var content = document.getElementById("file-manager-fileview");
						content.innerHTML = data;
						$('.file-box').contextMenu('myMenu2', {
						  bindings: {
							'rename': function(t) {
								sumo2.ftp.PATH = t.id;
								sumo2.dialog.NewDialog('d_ftp_file_rename', 'id='+t.id);
							},
							'delete': function(t) {
							  sumo2.ftp.PATH = t.id;
							  sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_11,250,200,function() {
								  sumo2.ftp.DeleteFile();
							  });
							},
							'open': function(t) {
								var array_f = t.id.split("/public_html");
								window.open('http://'+document.domain+'/v2/includes/file.manager.thumb.php?name=/'+array_f[1]);
							},
							'view': function(t) {
								var array_f = t.id.split("/public_html");
								var temp = array_f[1].split('.');
								var end = temp[temp.length-1];
								var img = ['gif', 'jpg', 'jpeg', 'png'];
								if(jQuery.inArray(end, img) != -1) {
									var newImg = new Image();
									newImg.src = 'http://'+document.domain+array_f[1];
									var height = newImg.height;
									var width = newImg.width;
									$.colorbox({href:'http://'+document.domain+array_f[1], scalePhotos:true, maxWidth:($(window).width()-40), maxHeight: ($(window).height()-40)});							
								}
								else{
									window.open('http://'+document.domain+array_f[1], '_newtab');
								}
							},
							'edit': function(t) {
								var array_f = t.id.split("/public_html");
								var temp = array_f[1].split('.');
								var end = temp[temp.length-1];
								var imgs = array_f[1].split("/");
								imgs=imgs[imgs.length-1].split("."+end);
								var img = ['gif', 'jpg', 'jpeg', 'png'];
								if(jQuery.inArray(end, img) != -1) {
									pixlr.settings.target = 'http://'+document.domain+'/v2/includes/file.manager.saveImg.php';
									pixlr.settings.credentials = false;
									pixlr.settings.method = 'GET';
									pixlr.settings.referrer = 'SUMO2 CMS';
									pixlr.settings.exit = 'http://'+document.domain+'/v2/includes/PixlrClose.html';
									pixlr.settings.redirect = 'http://'+document.domain+'/v2/includes/PixlrClose.html';
									pixlr.overlay.show({image:'http://'+document.domain+array_f[1], title:imgs[0]});
									
								}
								else{
									window.open('http://'+document.domain+array_f[1], '_newtab');
								}
							}
						  }
						});
						$('.folder-box').contextMenu('myMenu2', {
						  bindings: {
							'rename': function(t) {
								sumo2.ftp.PATH = t.id;
								sumo2.dialog.NewDialog('d_ftp_folder_rename');
							},
							'delete': function(t) {
							  sumo2.ftp.PATH = t.id;
							  sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_12,250,200,function() {
								  sumo2.ftp.DeleteFolder();
							  });
							}
						  }
						});
						sumo2.dialog.Corner('.folder-box', 5, 5, 5, 5);
						sumo2.dialog.Corner('.file-box', 5, 5, 5, 5);
						sumo2.dialog.Corner('.file-img', 5, 5, 5, 5);
						sumo2.tooltip.FindTooltips(document.getElementById("file-manager-fileview"));
						$('body').waitForImages(function() {
							$('.thumb').thumbs();
						});
				});
			}
		}
	},
	
	AddSelected : function(path, object) {
		var temp = document.getElementById('file_manager_selected').value;
		if($(object.parentNode).css('color')=='rgb(255, 0, 0)') {
			$(object.parentNode).css('color', '#676767');
			temp=temp.replace(path+"?.$//?", '');
			document.getElementById('file_manager_selected').value=temp;
		}
		else {
			$(object.parentNode).css('color', '#F00');
			temp=temp+path+"?.$//?";
			document.getElementById('file_manager_selected').value=temp;
		}	
	},	
	GetFileFromPath : function(path) {
		var array = path.split('/');
		return array[array.length-2];
	},
	
	GetFolderFromPath : function(path) {
		var array = path.split('/');
		return array[array.length-1];
	},
	
	GetPathFromFile : function(path) {
		var array = path.split('/');
		var pattern = array[array.length-1];
		var newpath = path.replace('/'+pattern,'');
		return newpath;
	},
	
	DataViewComand: function(param, id) {
		if(param=='rename') {
			sumo2.ftp.PATH = id;
			sumo2.dialog.NewDialog('d_ftp_file_rename', 'id='+id);
		}
		else if(param=='delete') {
			sumo2.ftp.PATH = id;
		    sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_11,250,200,function() {
				sumo2.ftp.DeleteFile();		
			});
		}
		else if(param=='open') {
			var array_f = id.split("/public_html");
			window.open('http://'+document.domain+'/v2/includes/file.manager.thumb.php?name='+array_f[1]);			
		}
		else if(param=='view') {
			var array_f = id.split("/public_html");
			var temp = array_f[1].split('.');
			var end = temp[temp.length-1];
			var img = ['gif', 'jpg', 'jpeg', 'png'];
			if(jQuery.inArray(end, img) != -1) {
				$.colorbox({href:'http://'+document.domain+array_f[1], scalePhotos:true, maxWidth:($(window).width()-40), maxHeight: ($(window).height()-40)});					
			}
			else{
				var width=document.documentElement.clientWidth-200;
				var height=document.documentElement.clientHeight-200;
				window.open('http://'+document.domain+array_f[1], '_newtab');
			}
		}
		else if(param=='edit') {
			var array_f = id.split("/public_html");
			var temp = array_f[1].split('.');
			var end = temp[temp.length-1];
			var imgs = array_f[1].split("/");
			imgs=imgs[imgs.length-1].split("."+end);
			var img = ['gif', 'jpg', 'jpeg', 'png'];
			if(jQuery.inArray(end, img) != -1) {
				pixlr.overlay.show({target:'http://'+document.domain+'/v2/includes/file.manager.saveImg.php', exit:'http://'+document.domain+'/v2/includes/PixlrClose.html', redirect: 'http://'+document.domain+'/v2/includes/PixlrClose.html', referrer:'3Z Sistemi', credentials:false, method: 'GET', image:'http://'+document.domain+array_f[1], title:imgs[0]});				
			}
			else{
				window.open('http://'+document.domain+array_f[1], '_newtab');
			}	
		}
	},
	
	DeleteSelected : function() {
		var files = document.getElementById('file_manager_selected').value;
		if(files.length>5) {
			
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_82,250,200,function() {
			    files = files.split("?.$//?");
				for(var i in files){
					if(files[i]!="") {
						var param = "delete=ok$!$path="+files[i];
						sumo2.ajax.SendPost('includes/file.manager.php',param,function(data) {});
					}
				}
				var file = sumo2.ftp.GetPathFromFile(files[0]);
				sumo2.accordion.ReloadAccordion('a_ftp','folder='+file);
			});
		}
		else
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_83,300,200,1);
		
	},
	
	RenameFile : function() {
		var name = document.getElementById("rename-item");
		if(!sumo2.validate.IsFile(name.value,1,50)) {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_13,200,200,1);
		} else {
			var param = "rename=ok$!$path="+this.PATH+"$!$name="+name.value;
			var file = this.GetPathFromFile(this.PATH);
			sumo2.ajax.SendPost('includes/file.manager.php',param,function(data) {
					sumo2.accordion.ReloadAccordion('a_ftp','folder='+file);
					sumo2.dialog.CloseDialog('d_ftp_file_rename');
			});	
		}
	},
	
	RenameFolder : function() {
		var name = document.getElementById("rename-item");
		if(!sumo2.validate.IsFolder(name.value,2,15)) {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING, sumo2.language.VARIABLES.MOD_14,200,200,1);
		} else {
			var param = "renamef=ok$!$path="+this.PATH+"$!$name="+name.value;
			var file = this.GetPathFromFile(this.PATH);
			sumo2.ajax.SendPost('includes/file.manager.php',param,function(data) {
					sumo2.accordion.ReloadAccordion('a_ftp','folder='+file+'/'+name.value);
					sumo2.dialog.CloseDialog('d_ftp_folder_rename');
			});	
		}
	},
	
	CreateFolder : function() {
		var name = document.getElementById("name-item");
		if(!sumo2.validate.IsFolder(name.value,2,15)) {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_14,200,200,1);
		} else {
			var param = "newf=ok$!$path="+this.PATH+"$!$name="+name.value;
			var path = this.PATH;
			sumo2.ajax.SendPost('includes/file.manager.php',param,function(data) {
					sumo2.accordion.ReloadAccordion('a_ftp','folder='+path+'/'+name.value);
					sumo2.dialog.CloseDialog('d_ftp_new_folder');
			});	
		}
	},
	
	DeleteFolder: function() {
		var param = "deletef=ok$!$path="+this.PATH;
		var file = this.GetPathFromFile(this.PATH);
		sumo2.ajax.SendPost('includes/file.manager.php',param,function(data) {
			sumo2.accordion.ReloadAccordion('a_ftp','folder='+file);
		});	
	},
	
	DeleteFile: function() {
		var param = "delete=ok$!$path="+this.PATH;
		var file = this.GetPathFromFile(this.PATH);
		sumo2.ajax.SendPost('includes/file.manager.php',param,function(data) {
			sumo2.accordion.ReloadAccordion('a_ftp','folder='+file);
		});	
	}
};
sumo2.mail = {
    SelectAll : function(type) {
		var obj = document.getElementById("mail_select");
		for(var i=obj.options.length-1; i>=0; i--) {
			if(type==1 && obj.options[i].disabled==false) 
				obj.options[i].selected = true; 
			else if (type==2) 
				obj.options[i].selected = false;
		}
	},
	
	Check : function() {
		var subject = document.getElementById("subject");
		var content = CKEDITOR.instances.editor1.getData();
		var selection = document.getElementById("mail_select");
		var error="";
		var st=0;
		var recipients="";		
		if(subject.value.length<3)
			error+=sumo2.language.VARIABLES.MAIL_SUBJECT+"<br/><br/>";
		if(content.length<10)
			error+=sumo2.language.VARIABLES.MAIL_CONTENT+"<br/><br/>";
		for(var i=selection.options.length-1; i>=0; i--) {
			if(selection.options[i].selected == true) {
				recipients+=selection.options[i].value+"!";
				st++;
			}
		}
		recipients+="a";
		if(st==0) 
			error+=sumo2.language.VARIABLES.MAIL_RECIPIENT;
		if(error.length==0) {
			var param="type=send$!$subject="+subject.value+"$!$content="+content+"$!$recipient="+recipients;
			sumo2.ajax.SendPost("includes/mail.php",param,function(data) {
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MAIL_SEND_MSG,1);
					sumo2.accordion.ReloadAccordion('a_mail_new');
					sumo2.accordion.ReloadAccordion('a_mail_inbox');
					sumo2.accordion.ReloadAccordion('a_mail_sent');
			});
		}
		else
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,error,200,200,1);
	},
	
	Remove : function(type, id) {
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MAIL_DELETE,250,250,function() {
			var param="type=rem"+type+"$!$idmail="+id;
			sumo2.ajax.SendPost("includes/mail.php",param,function(data) {
				if(type=="i")
					sumo2.accordion.ReloadAccordion('a_mail_inbox');
				else if(type=="s")
					sumo2.accordion.ReloadAccordion('a_mail_sent');
				
				sumo2.accordion.ReloadAccordion('a_trash');
			});
		});
	},
	
    CheckMain : function() {
		sumo2.ajax.SendPost("includes/mail.php","type=checkmain",function(data) {
			data=data.replace("("," ");
			data=data.replace(")"," ");
			$("#mail_number").html(data);
		});
	},
	
	Open : function(id) {
		var param="type=open$!$idmail="+id;
		sumo2.ajax.SendPost("includes/mail.php",param,function(data) {
			sumo2.accordion.ReloadAccordion('a_mail_inbox');
		});
	}
};
sumo2.menu = {
    NewMenu : function() {
		var error="";
		var menu= document.d_menus_new_m.name.value;
		var content= document.d_menus_new_m.contentd.value;
		var lang=document.d_menus_new_m.lang_id.value;
		if(!sumo2.validate.IsAlphaNumerical(menu, 3, 20))
			error += sumo2.language.VARIABLES.MOD_2;
		if(!sumo2.validate.IsLength(content, 0, 200))
			error += sumo2.language.VARIABLES.MOD_15;
		if(error.length==0) {
			var param="type=new$!$menu="+menu+"$!$content="+content+"$!$lang="+lang;
			sumo2.ajax.SendPost("includes/menus.php",param,function(data) {
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_16,1);
					sumo2.accordion.ReloadAccordion('a_menus');
					sumo2.dialog.CloseDialog('d_menus_new_m');
					sumo2.accordion.ReloadAccordion('a_sitetree');
			});
		}
		else
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,error,200,200,1);
	},
	
	EditMenu : function() {
		var error="";
		var menu= document.d_menus_edit_m.name.value;
		var content= document.d_menus_edit_m.contentd.value;
		var id= document.d_menus_edit_m.id.value;
		if(!sumo2.validate.IsAlphaNumerical(menu, 3, 20))
			error += sumo2.language.VARIABLES.MOD_2;
		if(!sumo2.validate.IsLength(content, 0, 200))
			error += sumo2.language.VARIABLES.MOD_15;
		if(error.length==0) {
			var param="type=edit$!$menu="+menu+"$!$content="+content+"$!$id="+id;
			sumo2.ajax.SendPost("includes/menus.php",param,function(data) {
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_16,1);
					sumo2.accordion.ReloadAccordion('a_menus');
					sumo2.dialog.CloseDialog('d_menus_edit_m');
					sumo2.accordion.ReloadAccordion('a_sitetree');
			});
		} else
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,error,200,200,1);
	},
	

	NewItem : function() {
		var error="";
		var menu= document.d_menus_new_i.name.value;
		var id= document.d_menus_new_i.id.value;
		var template= document.d_menus_new_i.template.value;
		var keywords= document.d_menus_new_i.keywords.value;
		var description= document.d_menus_new_i.description.value;
		var short_link=document.d_menus_new_i.shortcut_link.value;
		var elink=document.d_menus_new_i.extra_link.value;
		var target=1;
		var selected=1;
		var parentID=-1;
		var restriction=1;
		if(!sumo2.validate.IsLength(menu, 2, 200))
			error += sumo2.language.VARIABLES.MOD_17;
		if(document.d_menus_new_i.typ.checked && document.d_menus_new_i.typ.value==4) {
			elink=document.d_menus_new_i.linkA.value;
			parentID=document.d_menus_new_i.parent.value;
		}
		else if(document.d_menus_new_i.typ[1].checked) {
			selected=2;
			if(!sumo2.validate.IsNumerical(short_link,1,4))
				error+= sumo2.language.VARIABLES.MOD_18;
		}
		else if(document.d_menus_new_i.typ[2].checked) {
			selected=3;
			if(!sumo2.validate.IsUrl(elink))
				error+= sumo2.language.VARIABLES.MOD_19;
		}
		if(document.d_menus_new_i.restriction[1].checked) {
			restriction=2;
		}
		if(document.d_menus_new_i.target[0].checked)
			target=1;
		else if(document.d_menus_new_i.target[1].checked)
			target=2;
		else if(document.d_menus_new_i.target[2].checked)
			target=3;
			
		if(error.length==0) {
			var param="type=newitem$!$menu="+menu+"$!$id="+id+"$!$template="+template+"$!$keywords="+keywords+"$!$description="+description+"$!$selected="+selected+"$!$short_link="+short_link+"$!$elink="+elink+"$!$target="+target+"$!$restriction="+restriction+"$!$parentID="+parentID;
			sumo2.ajax.SendPost("includes/menus.php",param,function(data) {
					sumo2.dialog.ReloadDialog('d_menus_sitetree','sel='+id);
					sumo2.dialog.ReloadDialog('d_menus_sitetree_trans','sel='+id);
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_20,1);
					sumo2.accordion.ReloadAccordion('a_menus');
					sumo2.dialog.CloseDialog('d_menus_new_i');
					sumo2.accordion.ReloadAccordion('a_sitetree');
					sumo2.module.ClearCache('mod_basicMenu');
					sumo2.module.ClearCache('mod_dynamicMenu');
			});
		} else
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,error,200,200,1);
	},
	
	NewItemSpecial : function() {
		var error="";
		var menu= document.d_menus_new_s.name.value;
		var module = document.d_menus_new_s.module.value;		
		var template= document.d_menus_new_s.template.value;
		var keywords= document.d_menus_new_s.keywords.value;
		var description= document.d_menus_new_s.description.value;
		var elink=document.d_menus_new_s.linkA.value;
		var lang=document.d_menus_new_s.lang.value;
		var selected=4;
		var restriction=1;
		if(!sumo2.validate.IsLength(menu, 2, 200))
			error += sumo2.language.VARIABLES.MOD_17;
		
		if(document.d_menus_new_s.restriction[1].checked) {
			restriction=2;
		}
		var parentID=document.d_menus_new_s.parent.value;
		if(error.length==0) {
			var param="type=newitemspecial$!$menu="+menu+"$!$template="+template+"$!$keywords="+keywords+"$!$description="+description+"$!$selected="+selected+"$!$lang="+lang+"$!$restriction="+restriction+"$!$parentID="+parentID+"$!$module="+module+"$!$elink="+elink;
			sumo2.ajax.SendPost("includes/menus.php",param,function(data) {
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_20,1);

					sumo2.dialog.CloseDialog('d_menus_new_s');
					sumo2.accordion.ReloadAccordion('a_sitetree');
			});
		} else
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,error,200,200,1);
	},
	
	EditItem : function() {
		var error="";
		var menu= document.d_menus_edit_i.name.value;
		var id= document.d_menus_edit_i.id.value;
		var template= document.d_menus_edit_i.template.value;
		var keywords= document.d_menus_edit_i.keywords.value;
		var description= document.d_menus_edit_i.description.value;
		var short_link=document.d_menus_edit_i.shortcut_link.value;
		var elink=document.d_menus_edit_i.extra_link.value;
		var altPrefix=document.d_menus_edit_i.altPrefix.value;
		var target=1;
		var parentID=-1;
		var selected=1;
		var restriction=1;
		if(!sumo2.validate.IsLength(menu, 2, 200))
			error += sumo2.language.VARIABLES.MOD_17;
		if(document.d_menus_edit_i.typ.checked && document.d_menus_edit_i.typ.value==4) {
			selected=4;
			parentID=document.d_menus_edit_i.parent.value;
		} 
		else if(document.d_menus_edit_i.typ[1].checked) {
			selected=2;
			if(!sumo2.validate.IsNumerical(short_link,1,4))
				error+= sumo2.language.VARIABLES.MOD_18;
		}
		else if(document.d_menus_edit_i.typ[2].checked) {
			selected=3;
			if(!sumo2.validate.IsUrl(elink))
				error+= sumo2.language.VARIABLES.MOD_19;
		}
		if(document.d_menus_edit_i.restriction[1].checked) {
			restriction=2;
		}
		
		if(error.length==0) {
			var param="type=edititem$!$menu="+menu+"$!$id="+id+"$!$template="+template+"$!$keywords="+keywords+"$!$description="+description+"$!$selected="+selected+"$!$short_link="+short_link+"$!$elink="+elink+"$!$target="+target+"$!$restriction="+restriction+"$!$parentID="+parentID+"$!$altPrefix="+altPrefix;
			sumo2.ajax.SendPost("includes/menus.php",param,function(data) {
					sumo2.dialog.CloseDialog('d_menus_edit_i', null);
					sumo2.dialog.ReloadDialog('d_menus_sitetree_trans','sel='+id);
					sumo2.dialog.ReloadDialog('d_menus_sitetree','sel='+id);
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_20,1);
					sumo2.accordion.ReloadAccordion('a_menus');
					sumo2.accordion.ReloadAccordion('a_sitetree');
					sumo2.module.ClearCache('mod_basicMenu');
					sumo2.module.ClearCache('mod_dynamicMenu');
					
			});
		} else
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,error,300,200,1);
	},
	
	EditHome: function() {
		var error="";
		var id= document.d_menus_edit_h.id.value;
		var menu= document.d_menus_edit_h.name.value;
		var altTitle= document.d_menus_edit_h.altTitle.value;
		var template= document.d_menus_edit_h.template.value;
		var keywords= document.d_menus_edit_h.keywords.value;
		var description= document.d_menus_edit_h.description.value;
		var short_link=document.d_menus_edit_h.shortcut_link.value;
		var selected=1;
		if(!sumo2.validate.IsLength(menu, 2, 200))
			error += sumo2.language.VARIABLES.MOD_17;
		if(document.d_menus_edit_h.typ[1].checked) {
			selected=2;
			if(!sumo2.validate.IsNumerical(short_link,1,4))
				error+= sumo2.language.VARIABLES.MOD_18;
		}
		if(error.length==0) {
			var param="type=edithome$!$menu="+menu+"$!$id="+id+"$!$template="+template+"$!$keywords="+keywords+"$!$description="+description+"$!$selected="+selected+"$!$short_link="+short_link+"$!$altTitle="+altTitle;
			sumo2.ajax.SendPost("includes/menus.php",param,function(data) {
					sumo2.dialog.CloseDialog('d_menus_edit_h', null);
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_20,1);
					sumo2.accordion.ReloadAccordion('a_menus');
					sumo2.accordion.ReloadAccordion('a_sitetree');
					
			});
		} else
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,error,200,200,1);
	},
	
	DeleteItem : function(id) {
		var param="type=deleteitem$!$id="+id;
		sumo2.ajax.SendPost("includes/menus.php",param,function(data) {
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_21,1);
				sumo2.accordion.ReloadAccordion('a_menus');
				sumo2.dialog.ReloadDialog('d_menus_sitetree_trans');
				sumo2.dialog.ReloadDialog('d_menus_sitetree');
				sumo2.accordion.ReloadAccordion('a_sitetree');
				sumo2.accordion.ReloadAccordion('a_trash');
				sumo2.module.ClearCache('mod_basicMenu');
				sumo2.module.ClearCache('mod_dynamicMenu');
        });
	},
	
	StatusItem : function(id) {
		sumo2.ajax.SendPost("includes/menus.php","type=statusitem$!$id="+id,function(data) {
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_22,1);
				sumo2.accordion.ReloadAccordion('a_menus');
				sumo2.dialog.ReloadDialog('d_menus_sitetree_trans','sel='+id);
				sumo2.dialog.ReloadDialog('d_menus_sitetree','sel='+id);
				sumo2.accordion.ReloadAccordion('a_sitetree');
				sumo2.module.ClearCache('mod_basicMenu');
				sumo2.module.ClearCache('mod_dynamicMenu');
        });
	},
	
	Status : function(id) {
		sumo2.ajax.SendPost("includes/menus.php","type=status$!$id="+id,function(data) {
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_22,1);
				sumo2.accordion.ReloadAccordion('a_menus');
				sumo2.accordion.ReloadAccordion('a_sitetree');
        });
	},
	
	DeleteMenu : function(id) {
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING, sumo2.language.VARIABLES.MOD_23,250,30,function() {
			sumo2.ajax.SendPost("includes/menus.php",'type=delete$!$id='+id,function(data) {
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_24,1);
	            	sumo2.accordion.ReloadAccordion('a_menus');
					sumo2.accordion.ReloadAccordion('a_sitetree');
					sumo2.accordion.ReloadAccordion('a_trash');
	        });
		});
	},
	
	Dialog_m : function(id) {
		var lang=document.getElementById('selected_lang_menu_id').value;
		sumo2.ajax.SendPost("includes/menus.php",'type=default_m$!$id='+id+'$!$lang='+lang,function(data) {
	            	sumo2.accordion.ReloadAccordion('a_menus');
	        });		
	},
	
	Translate : function() {
		var error="";
		var parent= document.d_menues_trans.parent.value;
		var lang= document.d_menues_trans.lang.value;
		var name= document.d_menues_trans.name.value;
		var content= document.d_menues_trans.content.value;
		if(!sumo2.validate.IsAlphaNumerical(name, 3, 20))
			error += sumo2.language.VARIABLES.MOD_2;
		if(error.length==0) {
			var param="type=translate$!$menu="+name+"$!$content="+content+"$!$lang="+lang+"$!$parent="+parent;
			sumo2.ajax.SendPost("includes/menus.php",param,function(data){
					sumo2.dialog.CloseDialog('d_menues_trans');
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_20,1);
					sumo2.accordion.ReloadAccordion('a_menus');
					sumo2.accordion.ReloadAccordion('a_sitetree');
					sumo2.module.ClearCache('mod_basicMenu');
					sumo2.module.ClearCache('mod_dynamicMenu');					
			});
		} else
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,error,200,200,1);
	},
	
	Down : function(id) {
		sumo2.ajax.SendPost("includes/menus.php","type=down$!$id="+id,function(data){
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_25,1);
				sumo2.accordion.ReloadAccordion('a_menus');
				sumo2.dialog.ReloadDialog('d_menus_sitetree_trans','sel='+id);
				sumo2.dialog.ReloadDialog('d_menus_sitetree','sel='+id);
				sumo2.accordion.ReloadAccordion('a_sitetree');
			});
	},
	Up : function(id) {
		sumo2.ajax.SendPost("includes/menus.php","type=up$!$id="+id,function(data){
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_25,1);
				sumo2.accordion.ReloadAccordion('a_menus');
				sumo2.dialog.ReloadDialog('d_menus_sitetree_trans','sel='+id);
				sumo2.dialog.ReloadDialog('d_menus_sitetree','sel='+id);
				sumo2.accordion.ReloadAccordion('a_sitetree');
			});		
	},
	View : function(id) {
		document.getElementById('div_short').style.display="none";
		if(document.getElementById('div_link'))
			document.getElementById('div_link').style.display="none";
		document.getElementById('div_'+id).style.display="block";
	},
	showMenu: function(id) {
		sumo2.ajax.SendPost("includes/menus.php","type=showMenu$!$id="+id,function(data){
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_84,1);
				sumo2.accordion.ReloadAccordion('a_menus');
				sumo2.dialog.ReloadDialog('d_menus_sitetree_trans','sel='+id);
				sumo2.dialog.ReloadDialog('d_menus_sitetree','sel='+id);
				sumo2.accordion.ReloadAccordion('a_sitetree');
			});	
	}
};
sumo2.languageSelection = {
    TIMEOUT : null,
    
	ChangeLang : function(accordion, id) {
		var select = document.getElementById(accordion + '-langselect');
		var selId = select.options[select.selectedIndex].value;
		sumo2.accordion.ReloadAccordion(accordion,id+'='+selId,id+'_menu='+selId);
	},

    Show : function() {
        var div_s = document.getElementById("sumo2-lang-wrapper");
		 div_s.style.display="block";
    },
    
    Hide : function() {
        var div_s = document.getElementById("sumo2-lang-wrapper");
		div_s.style.display="none";
		this.TIMEOUT = null;
    },
	
	Select : function(id, name) {
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.LANG_WAR+"<b>"+name+"</b>?",250,250,function() {
				sumo2.ajax.SendPost("includes/lang.php","type=save$!$id="+id,function(data) {
					setTimeout("window.location.reload()", 1000); 
				});
			});
	}
};
sumo2.cacheSelection = {
    TIMEOUT : null,
	
    Show : function() {
        var div_s = document.getElementById("sumo2-cache-wrapper");
		 div_s.style.display="block";
    },
    
    Hide : function() {
        var div_s = document.getElementById("sumo2-cache-wrapper");
		div_s.style.display="none";
		this.TIMEOUT = null;
    },
	
	Select : function(id) {
		sumo2.ajax.SendPost("includes/module.data.php","type=clearCache$!$folder="+id,function(data) {
			sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_174,1);
		});
	}
};
sumo2.sumoSettings = {
    Save : function() {
		var type=document.getElementById("settings_current_tab").value;
		//USER
		if(type=="#user1") {
			var problem = "";
			var counter=0;
			var id = document.a_settings_user.verify.value;
			var name = document.a_settings_user.name.value;
			var oldpassword = document.a_settings_user.oldpassword.value;
			var password = document.a_settings_user.password.value;
			var password2 = document.a_settings_user.password2.value;
			var email = document.a_settings_user.email.value;
			var email2 = document.a_settings_user.email2.value;
			var group = document.a_settings_user.group;
			if(!sumo2.validate.IsAlpha(name,3, 20)) {
				problem += '-'+sumo2.language.VARIABLES.USER_CHECK_NAME+'<br />';
				counter++;
			}
			if(!sumo2.validate.IsEmail(email,5,100)) {
				problem += '-'+sumo2.language.VARIABLES.USER_CHECK_EMAIL+'<br />';
			}
			if(email != email2) {
				problem += '-'+sumo2.language.VARIABLES.USER_CHECK_EMAIL_2+'<br />';
			}
			if(oldpassword != "" && password == "") {
				problem += '-'+sumo2.language.VARIABLES.USER_CHECK_PASS+'<br />';
			}
			if(oldpassword != "" && password != password2) {
				problem += '-'+sumo2.language.VARIABLES.USER_CHECK_PASS_2+'<br />';
			}
			if(problem !== "") {
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,problem,200,200,1);
			} else {
				if(oldpassword == "") {
					var params = "type=edit$!$id="+id+"$!$name="+name+"$!$email="+email+"$!$group="+group.options[group.selectedIndex].value;
				} else {
					var params = "type=edit$!$oldpassword="+oldpassword+"$!$id="+id+"$!$newpassword="+password+"$!$name="+name+"$!$email="+email+"$!$group="+group.options[group.selectedIndex].value;
				}
				sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
					sumo2.ajax.SendPost("includes/user.management.php",params,function(data) {
						if(data == 'ok') {
							sumo2.message.NewMessage(sumo2.language.VARIABLES.USER_EDIT_SUCC,1);
							setTimeout("window.location.reload()", 2000);
						} else if(data == 'group') {
							sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.USER_CHECK_GROUP,200,200);
						} else if(data == 'password') {
							sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.USER_CHECK_PASS_3,200,200);
						} else if(data == 'oldpassword') {
							sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.USER_CHECK_PASS_4,200,200);
						} else if(data == 'id') {
							sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.USER_CHECK_ID,200,200);
						}
					});
				});
			}
		} 
		//SUMO
		else if(type=="#sumo") {			
			var items =document.a_settings_sumo.items.value;
			var lang =document.a_settings_sumo.language.value;
			var panels =document.a_settings_sumo.accordion.value;
			var preview =document.a_settings_sumo.preview.value;
			var translate_state = document.a_settings_sumo.translate_state.value;
			var view = document.a_settings_sumo.view.value;
			var update =document.a_settings_sumo.update.value;
			var beta =document.a_settings_sumo.beta.value;
			var developer =document.a_settings_sumo.developer.value;
			var translate_lang=-1;
			if(document.a_settings_sumo.translate_lang)
				translate_lang = document.a_settings_sumo.translate_lang.value;
			else
				translate_state="OFF";
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
				var params="type=sumo$!$items="+items+"$!$lang="+lang+"$!$panels="+panels+"$!$preview="+preview+"$!$translate_lang="+translate_lang+"$!$translate_state="+translate_state+"$!$view="+view+"$!$update="+update+"$!$beta="+beta+"$!$developer="+developer;
				sumo2.ajax.SendPost("includes/settings.php",params,function(data) {
						sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_26,1);
						setTimeout("window.location.reload()", 2000);
				});
			});
		}
		//PAGE
		else if(type=="#page") {
			var problem = "";
			var template =document.a_settings_page.template.value;
			var offline =document.a_settings_page.offline.value;
			var description =document.a_settings_page.description.value;
			var title =document.a_settings_page.title.value;
			var keyword =document.a_settings_page.keyword.value;
			var email = document.a_settings_page.email.value;
			var language = document.a_settings_page.translate_lang.value;
			var display_title=document.a_settings_page.display_title.value;
			if(!sumo2.validate.IsLength(title, 2, 500)) 
				problem += sumo2.language.VARIABLES.MOD_85;
			
			if(problem !== "") {
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,problem,250,250,1);
			} else {
				sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
					var param="type=page$!$template="+template+"$!$description="+description+"$!$keyword="+keyword+"$!$title="+title+"$!$email="+email+"$!$display_title="+display_title+"$!$offline="+offline+"$!$language="+language;
					sumo2.ajax.SendPost("includes/settings.php",param,function(data) {
							sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_27,1);
							setTimeout("window.location.reload()", 2000);
					});
				});
			}
		}
		//GLOBAL
		else if(type=="#global") {	
			var problem="";
			var GA = window.document.getElementById('GA').value;
			var WM = window.document.getElementById('WM').value;
			
			var GA_ID=document.a_settings_global.GA_ID.value;
			var GA_type=document.a_settings_global.GA_type.value;
			var WM_ID=document.a_settings_global.WM_ID.value;
			
			if(GA=='1')
				if(!sumo2.validate.IsLength(GA_ID, 10, 20))
					problem += sumo2.language.VARIABLES.MOD_28+'<br />';
			if(WM=='1')
				if(!sumo2.validate.IsLength(WM_ID, 40, 70))
					problem += sumo2.language.VARIABLES.MOD_29+'<br />';
			if(problem !== "") {
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,problem,250,250,1);
			} else {
				var param="type=global$!$GA_ID="+GA_ID+"$!$GA_type="+GA_type+"$!$WM_ID="+WM_ID;
				sumo2.ajax.SendPost("includes/settings.php",param,function(data) {
						sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_30,1);
						sumo2.accordion.ReloadAccordion("a_settings");
				});
			}
		}
		else if(type=="#welcome") {
			var content = CKEDITOR.instances.editorw.getData();
			var param="type=welcome$!$content="+content;
				sumo2.ajax.SendPost("includes/settings.php",param,function(data) {
						sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_31,1);
						sumo2.accordion.ReloadAccordion("a_settings");
						sumo2.accordion.ReloadAccordion("a_welcome");
				});
		}
	},
	SaveTemplate : function (ok) {
		var problem = "";
		var name =document.a_settings_add_t.name.value;
		
		if(ok=="n") {
			if(!sumo2.validate.IsFile(name, 2, 30)) 
				problem += sumo2.language.VARIABLES.MOD_32+'<br />';
			if(problem !== "") {
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,problem,250,250,1);
			}
			else {
				sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
				$('#uploadify2').uploadify('upload','*');
				});
			}
		}
		else if(ok=="y") {
			var random_number = document.a_settings_add_t.random_number.value;
			var param="type=addt$!$name="+name+"$!$number="+random_number;
			sumo2.ajax.SendPost("includes/settings.php",param,function(data) {
				sumo2.dialog.CloseDialog('d_settings_add_t');
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_34,1);
				setTimeout("window.location.reload()",2500);
			});
		}
	},
	SaveLangB : function (ok) {
		var problem = "";
		var name =document.a_settings_add_lb.name.value;
		var short =document.a_settings_add_lb.short.value;
		
		if(ok=="n") {
			if(!sumo2.validate.IsFile(name, 5, 20)) 
				problem += sumo2.language.VARIABLES.MOD_35+'<br />';
			if(short=='0') 
				problem += sumo2.language.VARIABLES.MOD_36+'<br />';
			if(problem !== "") {
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,problem,250,250,1);
			}
			else {
				sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
				$('#uploadify3').uploadify('upload','*');
				});
			}
		}
		else if(ok=="y") {
			var random_number = document.a_settings_add_lb.random_number.value;
			var param="type=addlb$!$name="+name+"$!$short="+short+"$!$number="+random_number;
			sumo2.ajax.SendPost("includes/settings.php",param,function(data) {
				sumo2.dialog.CloseDialog('d_settings_add_lb');
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_37,1);
				setTimeout("window.location.reload()", 2000);
			});
		}
	},
	SaveLangF : function () {
		var problem = "";
		var name =document.d_settings_add_lf.name.value;
		var short =document.d_settings_add_lf.short.value;
		if(!sumo2.validate.IsFile(name, 5, 20)) 
				problem += sumo2.language.VARIABLES.MOD_35+'<br />';
		if(!sumo2.validate.IsLength(short, 2, 10)) 
			problem += sumo2.language.VARIABLES.MOD_36+'<br />';
		if(problem !== "") {
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,problem,250,250,1);
		}
		else {
			var param="type=addlf$!$name="+name+"$!$short="+short;
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
				sumo2.ajax.SendPost("includes/settings.php",param,function(data) {
					sumo2.dialog.CloseDialog('d_settings_add_lf');
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_37,1);
					setTimeout("window.location.reload()", 2000);
				});
			});
		}
	},
	ChangeStatus : function(id) {
		var params = "type=statust$!$id="+id;
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
			sumo2.ajax.SendPost("includes/settings.php",params,function(data) {
				 setTimeout("window.location.reload()", 2000);
			});
		});
	},
	EditT : function() {
		var problem = "";
		var name = document.a_settings_edit_t.name.value;
		var id = document.a_settings_edit_t.id.value;
		if(!sumo2.validate.IsFile(name, 5, 20)) 
				problem += sumo2.language.VARIABLES.MOD_32+'<br />';
		if(problem !== "") {
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,problem,250,250,1);
		}
		else {
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
				var params = "type=editt$!$id="+id+"$!$name="+name;
				sumo2.ajax.SendPost("includes/settings.php",params,function(data) {
					 sumo2.dialog.CloseDialog('d_settings_edit_t');
					 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_34,1);
					 setTimeout("window.location.reload()", 2000);
				});
			});
		}
	},
	DeleteT : function(id) {
		var params = "type=deletet$!$id="+id;
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_38,300,250,function() {
			sumo2.ajax.SendPost("includes/settings.php",params,function(data) {
				 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_39,1);
				 setTimeout("window.location.reload()", 2000);
			});
		});
	},
	DeleteTP : function(id) {
		var params = "type=deletetP$!$id="+id;
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_72,300,250,function() {
			sumo2.ajax.SendPost("includes/settings.php",params,function(data) {
				 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_21,1);
				 setTimeout("window.location.reload()", 2000);
			});
		});
	},
	DeleteP : function(id) {
		var params = "type=deleteP$!$id="+id;
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_72,300,250,function() {
			sumo2.ajax.SendPost("includes/settings.php",params,function(data) {
				 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_21,1);
				 setTimeout("window.location.reload()", 2000);
			});
		});
	},
	ChangeStatusLb : function(id) {
		var params = "type=statuslb$!$id="+id;
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
			sumo2.ajax.SendPost("includes/settings.php",params,function(data) {
				 window.location.reload();
			});
		});
	},
	ChangeStatusLf : function(id) {
		var params = "type=statuslf$!$id="+id;
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
			sumo2.ajax.SendPost("includes/settings.php",params,function(data) {
				 window.location.reload();
			});
		});
	},
	GAstat : function() {
		sumo2.ajax.SendPost("includes/settings.php","type=gastat",function(data) {
			 sumo2.accordion.ReloadAccordion("a_settings");
		});
	},
	WMstat : function() {
		sumo2.ajax.SendPost("includes/settings.php","type=wmstat",function(data) {
			 sumo2.accordion.ReloadAccordion("a_settings");
		});
	},
	Disable : function() {
		var GA ="";
		var WM ="";
		if( window.document.getElementById('GA')!=null)
			GA = window.document.getElementById('GA').value;
		if( window.document.getElementById('WM')!=null)
			WM = window.document.getElementById('WM').value;
		if(GA=='0') {
			document.a_settings_global.GA_ID.disabled=true;
			document.a_settings_global.GA_type.disabled=true;
		} else if(GA=='1') {
			document.a_settings_global.GA_ID.disabled=false;
			document.a_settings_global.GA_type.disabled=false;
		}
		
		if(WM=='0') 
			document.a_settings_global.WM_ID.disabled=true;
		else if(WM=='1') 
			document.a_settings_global.WM_ID.disabled=false;
	},
	Error : function() {
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_40,300,250,function() {
			sumo2.ajax.SendPost("includes/settings.php","type=error",function(data) {

				 sumo2.accordion.ReloadAccordion('a_settings')
			});
		});
	},
	ErrorFront : function() {
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_40,300,250,function() {
			sumo2.ajax.SendPost("includes/settings.php","type=errorFront",function(data) {
				 sumo2.accordion.ReloadAccordion('a_settings')
			});
		});
	},
	Prefix : function() {
		var problem="";
		var name = document.a_settings_add_mp.name.value;
		if(!sumo2.validate.IsFile(name, 3, 200)) 
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_8,250,250,1);
		else {
			sumo2.ajax.SendPost("includes/settings.php","type=checkp&name="+name,function(data) {
				data=data.replace("\n", "");
				data=data.replace("\r", "");
				data=data.replace("\t", "");
				
				 if(data=='error')
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_42+'<br />',250,250,1);
				else {
					sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
						var params = "type=saveprefix$!$name="+name;
						sumo2.ajax.SendPost("includes/settings.php",params,function(data) {
							 sumo2.dialog.CloseDialog('a_settings_add_p');
							 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_41,1);
							 setTimeout("window.location.reload()", 2000);
						});
					});
				}
			});
		}
	},
	Position : function() {
		var problem="";
		var name = document.a_settings_add_tp.name.value;
		if(!sumo2.validate.IsFile(name, 3, 200)) 
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_8,250,250,1);
		else {
			sumo2.ajax.SendPost("includes/settings.php","type=checktp&name="+name,function(data) {
				data=data.replace("\n", "");
				data=data.replace("\r", "");
				data=data.replace("\t", "");
				
				 if(data=='error')
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_42+'<br/>',250,250,1);
				else {
					sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
						var params = "type=saveposition$!$name="+name;
						sumo2.ajax.SendPost("includes/settings.php",params,function(data) {
							 sumo2.dialog.CloseDialog('a_settings_add_tp');
							 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_43,1);
							 setTimeout("window.location.reload()", 2000);
						});
					});
				}
			});
		}
	},
	Status_prefix : function(id) {
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
			var param="type=prefstat$!$id="+id;
			sumo2.ajax.SendPost("includes/settings.php",param,function(data) {
				 window.location.reload();
			});
		});
	},
	
	SaveFTP : function() {
		var problem = "";
		var user =document.a_settings_global.FTP_user.value;
		var password =document.a_settings_global.FTP_pass.value;
		var url =document.a_settings_global.FTP_url.value;
		var port =document.a_settings_global.FTP_port.value;
		if(!sumo2.validate.IsLength(user, 2, 30)) 
			problem += sumo2.language.VARIABLES.MOD_135+'<br />';
		if(!sumo2.validate.IsLength(password, 2, 20)) 
			problem += sumo2.language.VARIABLES.MOD_136+'<br />';
		if(!sumo2.validate.IsLength(url, 5, 50)) 
			problem += sumo2.language.VARIABLES.MOD_137+'<br />';
		if(!sumo2.validate.IsNumerical(port, 1, 6)) 
			problem += sumo2.language.VARIABLES.MOD_138+'<br />';
		if(problem !== "") {
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,problem,250,250,1);
		}
		else {
			var param="type=saveFTP$!$user="+user+"$!$password="+password+"$!$url="+url+"$!$port="+port;
			sumo2.ajax.SendPost("includes/settings.php",param,function(data) {
				if(data=='ok')
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_139,1);
				else
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_140,250,250,1);

			});
		}		
	},
	
	ChangeChacheNumber : function() {
		var param="type=changeChacheNumber";
		sumo2.ajax.SendPost("includes/settings.php",param,function(data) {
			if(data=='ok')
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_213,1);
			else
				sumo2.message.NewMessage(data,3);
		});
	},
	
	ClearCacheT : function(name) {
		var param="type=ClearCacheT$!$name="+name;
		sumo2.ajax.SendPost("includes/settings.php",param,function(data) {
			if(data=='ok')
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_159,1);
			else
				sumo2.message.NewMessage(data,3);
		});
	}
};

sumo2.trash = {
    Delete : function(id) {
		var type=document.getElementById("trash_current_tab").value;
		//Article
		if(type=="#article") {
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_89,250,250,function() {
				var params = "type=articleD$!$id="+id;
				sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
					 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_90,1);
					 setTimeout("window.location.reload()", 2000);
				});
			});
		}
		else if(type=="#articleG") {
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_91,250,250,function() {
				var params = "type=articleGD$!$id="+id;
				sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
					 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_92,1);
					 setTimeout("window.location.reload()", 2000);
				});
			});
		}
		else if(type=="#user") {
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_93,250,250,function() {
				var params = "type=userD$!$id="+id;
				sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
					 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_94,1);
					 setTimeout("window.location.reload()", 2000);
				});
			});
		}
		else if(type=="#userG") {
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_95,250,250,function() {
				var params = "type=userGD$!$id="+id;
				sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
					 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_96,1);
					 setTimeout("window.location.reload()", 2000);
				});
			});
		}
		else if(type=="#menu") {
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_97,250,250,function() {
				var params = "type=menuD$!$id="+id;
				sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
					 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_98,1);
					 setTimeout("window.location.reload()", 2000);
				});
			});
		}
		else if(type=="#menuI") {
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_99,250,250,function() {
				var params = "type=menuID$!$id="+id;
				sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
					 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_100,1);
					 setTimeout("window.location.reload()", 2000);
				});
			});
		}
		else if(type=="#mail") {
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_101,250,250,function() {
				var params = "type=mailD$!$id="+id;
				sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
					 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_102,1);
					 setTimeout("window.location.reload()", 2000);
				});
			});
		}
		else if(type=="#moduli") {
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_103,250,250,function() {
				var params = "type=modulD$!$id="+id;
				sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
					 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_104,1);
					 setTimeout("window.location.reload()", 2000);
				});
			});
		}
		else if(type=="#com") {
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_103,250,250,function() {
				var params = "type=comD$!$id="+id;
				sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
					 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_141,1);
					 setTimeout("window.location.reload()", 2000);
				});
			});
		}
		else if(type=="#temp") {
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_105,250,250,function() {
				var params = "type=tempD$!$id="+id;
				sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
					if(data=="ok") {
						 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_106,1);
						 setTimeout("window.location.reload()", 2000);
					}
					else {
						sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,data,400,550,1);
					}
				});
			});
		}
	},
	SelectAll : function(id, type) {
		var inputs = document.getElementsByName(id);
		for(var i=0,len=inputs.length;i<len;i++) {
			if(type==1)
				inputs[i].checked=true;
			else
				inputs[i].checked=false;
		}		
	},
	DeleteAll : function(id) {
		var type=document.getElementById("trash_current_tab").value;
		var inputs = document.getElementsByName(id);
		var temp="";
		for(var i=0,len=inputs.length;i<len;i++) {
			if(inputs[i].checked == true)
				temp+=inputs[i].value+"!!##!!!";
		}
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_107,250,250,function() {
				var params = "type=DD_"+type+"$!$id="+temp;
				sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_108,1);
					sumo2.accordion.ReloadAccordion('a_trash');
				});
			});
	},
	Recover : function(id) {
		var type=document.getElementById("trash_current_tab").value;
		//Article
		if(type=="#article") {
			var params = "type=articleR$!$id="+id;
			sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
				 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_109,1);
				 setTimeout("window.location.reload()", 2000);
			});
		}
		else if(type=="#articleG") {
			var params = "type=articleGR$!$id="+id;
			sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
				 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_110,1);
				 setTimeout("window.location.reload()", 2000);
			});
		}
		else if(type=="#user") {
			var params = "type=userR$!$id="+id;
			sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
				 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_111,1);
				 setTimeout("window.location.reload()", 2000);
			});
		}
		else if(type=="#userG") {
			var params = "type=userGR$!$id="+id;
			sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
				 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_112,1);
				 setTimeout("window.location.reload()", 2000);
			});
		}
		else if(type=="#menu") {
			var params = "type=menuR$!$id="+id;
			sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
				 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_113,1);
				 setTimeout("window.location.reload()", 2000);
			});
		}
		else if(type=="#menuI") {
			var params = "type=menuIR$!$id="+id;
			sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
				 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_114,1);
				 setTimeout("window.location.reload()", 2000);
			});
		}
		else if(type=="#mail") {
			var params = "type=mailR$!$id="+id;
			sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
				 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_115,1);
				 setTimeout("window.location.reload()", 2000);
			});
		}
		else if(type=="#moduli") {
			var params = "type=modulR$!$id="+id;
			sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
				 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_116,1);
				 setTimeout("window.location.reload()", 2000);
			});
		}
		else if(type=="#com") {
			var params = "type=comR$!$id="+id;
			sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
				 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_142,1);
				 setTimeout("window.location.reload()", 2000);
			});
		}
		else if(type=="#temp") {
			var params = "type=tempR$!$id="+id;
			sumo2.ajax.SendPost("includes/trash.php",params,function(data) {
				 sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_117,1);
				 setTimeout("window.location.reload()", 2000);
			});
		}
	}
};

sumo2.siteTree = {
    TogglePanel : function(id,arrow) {
        var panel = document.getElementById(id);
		var arr = document.getElementById(arrow);
		if(arr.className == 'str-arrw-r') {
			arr.className = 'str-arrw-l';
		} else {
			arr.className = 'str-arrw-r';
		}
        if(panel.style.display == 'none') {
            panel.style.display = 'table-cell';
        } else {
            panel.style.display = 'none';
        }
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
	
	SetPosition : function(obj, pos, offset) {
		var top = parseInt(obj.style.top);
		var left = parseInt(obj.style.left);
		var leftDelta = pos.X - left;
		var topDelta = pos.Y - top;
		obj.style.left = (left + leftDelta - offset.leftD) + 'px';
		obj.style.top = (top + topDelta - offset.topD) + 'px';
	},
	
	GetTarget : function(event) {
		if(event.srcElement) {
			return event.srcElement;
		} else {
			return event.target;	
		}
	},
	
	OLD_LANG : null,
	
	SelectLanguage : function(dropdown) {
		var myindex  = dropdown.selectedIndex;
    	var SelValue = dropdown.options[myindex].value;
		var curLang = document.getElementById('lang-tree-'+SelValue);
		if(this.OLD_LANG) {
			this.OLD_LANG.style.display = 'none';	
		}
		curLang.style.display = 'block';
		this.OLD_LANG = curLang;
	},
	
	SetLang : function(id) {
		var dropdown = document.getElementById(id);
		var SelValue = dropdown.options[0].value;
		var curLang = document.getElementById('lang-tree-'+SelValue);
		this.OLD_LANG = curLang;
	},
	
	CheckForChilds : function(checkbox) {
		var state = checkbox.checked;
		var parent = checkbox.parentNode.parentNode;
		var inputs = parent.getElementsByTagName('input');
		for(var i=0,len=inputs.length;i<len;i++) {
			inputs[i].checked = state;
		}
	},
	
	CheckForChildsButton : function(obj) {
		var checkbox = obj.previousSibling;
		if(checkbox) {
			var state = checkbox.checked;
			checkbox.checked = !state;
			var parent = obj.parentNode.parentNode;
			var inputs = parent.getElementsByTagName('input');
			for(var i=0,len=inputs.length;i<len;i++) {
				inputs[i].checked = !state;
			}
		}
	},
	
	SaveAll : function() {
		var layout = document.getElementById('sitetree-layout').value;
		var id = document.getElementById('sitetree-modul').value;
		var newname = document.getElementById('rename-item-sitetree').value;
		var cache = document.getElementById('cache-item-sitetree').value;
		var prefix = document.getElementById('prefixOfModule').value;			
		var specialPage = document.getElementById('specialPage').value;
		var copyModul = document.getElementById('copyModul').value;
		if(!sumo2.validate.IsLength(newname,2,20)) {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_44,250,250,1);
			return false;
		}
		if(!sumo2.validate.IsNumerical(cache,1,11)) {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_158,250,250,1);
			return false;
		}
		var pages = '';
		var dialog = document.getElementById("sumo2-sitetree-rename-check");
		var inputs = dialog.getElementsByTagName('input');
		var first = true;
		for(var i=0,len=inputs.length;i<len;i++) {
			if(inputs[i].type == 'checkbox' && inputs[i].checked && inputs[i].value != 'root') {
				if(!first) {
					pages += '!';
				} else {
					first = false;	
				}
				pages += inputs[i].value;
			}
		}
		sumo2.ajax.SendPost('includes/site.tree.data.php','type=saveall$!$layout='+layout+'$!$id='+id+'$!$pages='+pages+'$!$name='+newname+'$!$prefix='+prefix+'$!$cache='+cache+'$!$specialPage='+specialPage+'$!$copyModul='+copyModul,function(data) {
			if(data == 'ok') {
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_186,1);
				sumo2.dialog.CloseDialog('d_site_tree_rename');
				sumo2.accordion.ReloadAccordion("a_sitetree");
			} else {
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_187,3);
				sumo2.message.NewMessage(data, 3);
			}
		});
	},
	
	ChangeStatusModule : function(id, table) {
		sumo2.ajax.SendPost('includes/site.tree.data.php','type=changeStatus$!$id='+id+'$!$table='+table,function(data) {
			if(data == 'ok') {
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_191,1);
				sumo2.dialog.CloseDialog('d_site_tree_rename');
				sumo2.accordion.ReloadAccordion("a_sitetree");
			} else {
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_187,3);
				sumo2.message.NewMessage(data, 3);
			}
		});
		
	},
	
	SaveModule : function(id) {
		var dialog = document.getElementById('sumo2-dialog-'+id);
		var name = document.getElementById('nameOfModule').value;
		var prefix = document.getElementById('prefixOfModule');
		var selPrefix = prefix.options[prefix.selectedIndex].value;
		var id = document.getElementById('idOfModule').value;
		var layout = document.getElementById('layoutOfModule').value;
		var tpage = document.getElementById('tpageOfModule').value;
		var copyModule = document.getElementById('copyForModule').value;
		var pages = '';
		var inputs = dialog.getElementsByTagName('input');
		var first = true;
		for(var i=0,len=inputs.length;i<len;i++) {
			if(inputs[i].type == 'checkbox' && inputs[i].checked && inputs[i].value != 'root') {
				if(!first) {
					pages += '!';
				} else {
					first = false;	
				}
				pages += inputs[i].value;
			}
		}
		if(!sumo2.validate.IsLength(name,2,50)) {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_44,250,250,1);
			return false;
		}
		if(pages.length <= 0) {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_45,250,250,1);
			return false;
		}
		if(window.frames.layout.st) {
			window.frames.layout.st.AddModules(id, name , pages, selPrefix, layout, copyModule, sumo2.accordion.GetParamFromAccordion('a_sitetree', 'sel_page'));
			sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_188,1);
		} else {
			window.frames.layout.location.reload(true);
		}
		sumo2.dialog.CloseDialog('d_layoutmodule');
	},
	
	DRAG : {obj:null,offset:null,frameInfo:null,name:null,id:null},
	
	WORKING : false,
	
	SetDrag : function(e) {
		this.WORKING = true;
		if(!e) var e = window.event;
		var frame = document.getElementById('sumo2-tree-frame');
		var frameInfo = this.GetPosition(frame);
		var target = this.GetTarget(e);
		this.DRAG.name = target.innerHTML;
		this.DRAG.id = target.id;
		var drag = target.cloneNode(true);
		drag.className = 'modules-itemdrag';
		drag.onclick = null;
		var posClicked = sumo2.tooltip.GetPosition(e);
		var dragPos = this.GetPosition(target);
		var offset = {topD:0, leftD:0};
		offset.leftD = posClicked.X - dragPos.left;
		offset.topD = posClicked.Y - dragPos.top;
		drag.style.top = dragPos.top + 'px';
		drag.style.left = dragPos.left + 'px';
		this.DRAG.obj = drag;
		this.DRAG.offset = offset;
		this.DRAG.frameInfo = frameInfo;
		document.body.appendChild(drag);
	},
	
	Rename : function() {
		var newname = document.getElementById('rename-item-sitetree').value;
		var layout = document.getElementById('sitetree-layout').value;
		var id = document.getElementById('sitetree-modul').value;
		var prefix = document.getElementById('prefixOfModule').value;	
		var cache = document.getElementById('cache-item-sitetree').value;
		var specialPage = document.getElementById('specialPage').value;
		var copyModul = document.getElementById('copyModul').value;
		if(!sumo2.validate.IsLength(newname,2,50)) {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_44,250,250,1);
			return false;
		}
		if(!sumo2.validate.IsNumerical(cache,1,11)) {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_158,250,250,1);
			return false;
		}
		var param='type=rename$!$title='+newname+'$!$layout='+layout+'$!$id='+id+'$!$prefix='+prefix+'$!$cache='+cache+'$!$specialPage='+specialPage+'$!$copyModul='+copyModul;
		sumo2.ajax.SendPost('includes/site.tree.data.php', param,function(data) {
			if(data == 'done') {
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_186,1);
				sumo2.dialog.CloseDialog('d_site_tree_rename');
				window.frames.layout.location.reload(true);
			}
		});
	},
	
	ClearCache : function(layout, panel) {
		sumo2.ajax.SendPost('includes/site.tree.data.php','type=clearCache$!$layout='+layout+'$!$panel='+panel,function(data) {		
			sumo2.module.ClearCache(data);
			sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_159,1);
			sumo2.dialog.CloseDialog('d_site_tree_rename');
		});
	},
	
	RefreshLayout : function(page,lang,template,layout) {
		var iframe = document.getElementById('sumo2-tree-frame');
		sumo2.accordion.SetParamForAccordion("a_sitetree","sel_page="+page);


		var temp = document.getElementById("sumo2-sitetree-template-link");
		temp.onclick = function() {
			sumo2.siteTree.RefreshLayout(page,lang,template,'no');
		};
		var lay = document.getElementById("sumo2-sitetree-layout-link");
		lay.onclick = function() {
			sumo2.siteTree.RefreshLayout(page,lang,template,'ok');
		};
		if(parseInt(template)<1) {
			sumo2.accordion.SetParamForAccordion("a_sitetree","layout=ok");
			iframe.src = 'includes/site.tree.notmp.php';
		} else {
			if(layout == 'ok') {
				sumo2.accordion.SetParamForAccordion("a_sitetree","layout=ok");
				iframe.src = 'includes/site.tree.template.php?layout=ok&menu='+page+'&lang_sel='+lang+'&temp='+template;
			} else if(layout == 'no') {
				sumo2.accordion.SetParamForAccordion("a_sitetree","layout=no");
				iframe.src = 'includes/site.tree.template.php?menu='+page+'&lang_sel='+lang+'&temp='+template;	
			} else {
				var layt = sumo2.accordion.GetParamFromAccordion("a_sitetree","layout");
				if(layt == 'ok') {
					iframe.src = 'includes/site.tree.template.php?layout=ok&menu='+page+'&lang_sel='+lang+'&temp='+template;
				} else {
					sumo2.accordion.SetParamForAccordion("a_sitetree","layout=no");
					iframe.src = 'includes/site.tree.template.php?ok=ok&menu='+page+'&lang_sel='+lang+'&temp='+template;
				}
			}
		}
	},
	
	ReloadMenus : function(func) {
		sumo2.DisableSelection(document.getElementById('sumo2-tree-modules'));
		var content = document.getElementById('sumo2-tree-menus');
		sumo2.ajax.SendPost('pages/site.tree.menus.php','',function(data) {
			content.innerHTML = data;
			if(typeof func == 'function') func();
		});
	},
	
	RemoveModule : function(layout, id) {	
		sumo2.dialog.YesNoDialog(sumo2.language.VARIABLES.WARNING, sumo2.language.VARIABLES.MOD_190,250,30,function() {
			var params = "type=remove$!$layout=" + layout + "$!$id=" + id+ "$!$all=yes" ;
			sumo2.ajax.SendPost('includes/site.tree.data.php', params, function(data) {
				sumo2.accordion.ReloadAccordion("a_sitetree")
			});
		}, function() {
			var params = "type=remove$!$layout=" + layout + "$!$id=" + id+ "$!$all=no";
			sumo2.ajax.SendPost('includes/site.tree.data.php', params, function(data) {
				sumo2.accordion.ReloadAccordion("a_sitetree")
			});
		});
	}
};
sumo2.moduleManager = {
	Install : function(ok) {
		var problem = "";
		var domain="";
		$("#d_module_install #domain :selected").each(function () {
			domain += $(this).val() + "*/*";
		});
		domain=domain.slice(0,domain.length-3);
		if(domain.length==0) {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.MOD_49,sumo2.language.VARIABLES.MOD_199,250,200,3);
			return;
		}
		if(ok=="n") {
			$('#uploadify_module_install').uploadify('upload','*');
		}
		else if(ok=="y") {
			var random_number = document.d_module_install.random_number.value;
			sumo2.ajax.SendPost("includes/module.data.php","type=install$!$number="+random_number+"$!$domain="+domain,function(data) {
				if(data == 'ok') {
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_48,1);
					sumo2.dialog.CloseDialog('d_module_install');
					setTimeout("window.location.reload()", 2000);						
				} else if(data.search("PERM") > -1) {
					var string = '';
					var array = data.split("!");
					var len = array.length;
					for(var i=1;i<len;i++) {
						if(i != 1) string += ', ';
						string += array[i];
					}
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.MOD_49,sumo2.language.VARIABLES.MOD_118+string,250,200,3);
				} else if(data == 'zipopen') {
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.MOD_49,sumo2.language.VARIABLES.MOD_119,250,200,3);
				} else if(data == 'zipclose') {
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.MOD_49,sumo2.language.VARIABLES.MOD_120,250,200,3);
				} else if(data == 'nosql') {
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.MOD_49,sumo2.language.VARIABLES.MOD_121,250,200,3);
				} else if(data.search("SQL") > -1) {
					var string = '';
					var array = data.split("!");
					var len = array.length;
					for(var i=1;i<len;i++) {
						if(i != 1) string += ', ';
						string += array[i];
					}
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.MOD_49,sumo2.language.VARIABLES.MOD_122 + string,250,200,3);
				} else if(data == 'nofbf') {
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.MOD_49,sumo2.language.VARIABLES.MOD_123,250,200,3);
				} else if(data == 'nofff') {
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.MOD_49,sumo2.language.VARIABLES.MOD_124,250,200,3);
				} else if(data == 'nodialog') {
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.MOD_49,sumo2.language.VARIABLES.MOD_125,250,200,3);
				} else if(data == 'noaccordion') {
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.MOD_49,sumo2.language.VARIABLES.MOD_126,250,200,3);
				} else if(data == 'nomenu') {
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.MOD_49,sumo2.language.VARIABLES.MOD_127,250,200,3);
				} else if(data.search('NOFILE') > -1) {
					var array = data.split('!');
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.MOD_49,sumo2.language.VARIABLES.MOD_128+ array[1],250,200,3);
				} else if(data == 'nosystem') {
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.MOD_49,sumo2.language.VARIABLES.MOD_129,250,200,3);
				} else if(data == 'nofile') {
					sumo2.dialog.NewNotification(sumo2.language.VARIABLES.MOD_49,sumo2.language.VARIABLES.MOD_130,250,200,3);
				} else {
					alert(data);	
				}
				//destroy and recreate upload
				$('#uploadify_module_install').uploadify('destroy');
				var randomnumber=sumo2.RandomString(30);
				document.d_module_install.random_number.value=randomnumber;
				$("#uploadify_module_install").uploadify({
					'swf'             : 'swf/uploadify.swf',
					'uploader'        : 'includes/uploadify_mi.php',
					'multi'           : false,
					'queueID'         : 'fileq_mi',
					'fileSizeLimit'   : '5MB',
					'fileTypeDesc'	  : 'WinZip files (.zip)',
					'fileTypeExts'	  : '*.zip',
					'auto'            : false,
					'method'          : 'post',
					'queueSizeLimit'  : 1,
					'uploadLimit'	  : 1,
					'buttonText'	  : sumo2.language.VARIABLES.BROWSE,
					'formData' 	      : {'randnum' : randomnumber},
					'onQueueComplete' : function(queueData) {
						sumo2.moduleManager.Install('y');
					},
					'onUploadError' : function(file, errorCode, errorMsg, errorString) {
						sumo2.message.NewMessage(file.name+": "+errorString,3);
					}	
				});
			});
		}	
	},
	
	Reload : function(type) {
		sumo2.accordion.SetParamForAccordion("a_module_view","type="+type);
		sumo2.accordion.ReloadAccordion("a_module_view");
	},
	
	View : function(name) {
		sumo2.ajax.SendPost('modules/'+name+'/description.html','',function(data) {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.DESCRIPTION,data,550,340);
		});
	},
	
	ChangeDomain : function() {
		var id=document.d_module_edit.id.value;
		var type=document.d_module_edit.type.value;
		var domain="";
		$("#d_module_edit #domain :selected").each(function () {
			domain += $(this).val() + "*/*";
		});
		domain=domain.slice(0,domain.length-3);
		if(domain.length==0) {
			sumo2.dialog.NewNotification(sumo2.language.VARIABLES.MOD_49,sumo2.language.VARIABLES.MOD_199,250,200,3);
			return;
		}
		var params = 'type=changeDomain$!$id='+id+'$!$domain='+domain+'$!$ver='+type;
		sumo2.ajax.SendPost("includes/module.data.php",params,function(data) {
			sumo2.accordion.ReloadAccordion('a_sitetree');
			sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_202, 1);
			sumo2.dialog.CloseDialog('d_module_edit');			
		});
		
	},
	
	Edit : function(id) {
		var params = 'type=editdata$!$id='+id;
		sumo2.ajax.SendPost("includes/module.data.php",params,function(data) {
		});
	},
	
	Delete : function(id) {
		var params = 'type=delete$!$id='+id;
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_58,250,250,function() {
			sumo2.ajax.SendPost("includes/module.data.php",params,function(data) {
				sumo2.accordion.ReloadAccordion('a_module_view');
				sumo2.accordion.ReloadAccordion('a_sitetree');
				sumo2.accordion.ReloadAccordion('a_trash');
			});
		});
	},
	
	DeleteC : function(id) {
		var params = 'type=deleteC$!$id='+id;
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_131,250,250,function() {
			sumo2.ajax.SendPost("includes/module.data.php",params,function(data) {
				 setTimeout("window.location.reload()", 2000);
			});
		});
	}
};
sumo2.login= {
	Check : function() {
		var username = document.d_relogin.username.value;
		var password = document.d_relogin.password.value;
		var token = document.d_relogin.token.value;
		var remember=true;
		var params = "username=" + username + "$!$password=" + password + "$!$remember=" + remember + "$!$token=" + token;
		sumo2.ajax.SendPost("includes/login.php", params, function (data) {
			if (data == "token") {
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_175,250,200, 1);
				sumo2.dialog.ReloadDialog ('d_relogin');
			}else if (data == "match" || data == "format") {
				sumo2.dialog.NewNotification(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_176,220,200, 1);				
			} else if (data == "ip") {
				window.location="/block/";
			} else if (data == "ok") {
				sumo2.preview.Update();
				sumo2.dialog.CloseDialog('d_relogin');
			}	
		});
	}
};

sumo2.domains= {
	problem: "",
	
	Check: function(){
		var current = $("#domains_current_tab").val();
		if(current==="#add"){
			sumo2.domains.Add();
		} else if(current.indexOf("#domain_")>=0){
			sumo2.domains.Edit(current.replace("#domain_", ""));
		}
	},
	
	Add : function() {
		sumo2.domains.problem="";
		var name=document.a_domains_add.name.value.replace("www.", "");
		var alias = sumo2.domains.Tag($("#a_domains_add .domain_alias").tagit("tags"), "domain");
		var main=document.a_domains_add.main.value;
		var white = sumo2.domains.Tag($("#a_domains_add .whiteIP").tagit("tags"), "IP");
		var black = sumo2.domains.Tag($("#a_domains_add .blackIP").tagit("tags"), "IP");
		var iplocator=document.a_domains_add.iplocator.value;
		var countries = sumo2.domains.Tag($("#a_domains_add .domain_countries").tagit("tags"));
		var language="";
		$("#a_domains_add #domains_language :selected").each(function () {
			language += $(this).val() + "*/*";
		});
		language=language.slice(0,language.length-3);
		if(!sumo2.validate.IsDomain(name)) {
			sumo2.domains.problem+="-"+sumo2.language.VARIABLES.MOD_195+"<br/>";			
		}
		if(language==="" || language.indexOf("-1")>=0) {
			sumo2.domains.problem+="-"+sumo2.language.VARIABLES.MOD_196+"<br/>";			
		}
		if(iplocator==="-1") {
			sumo2.domains.problem+="-"+sumo2.language.VARIABLES.MOD_197+"<br/>";			
		} else if(iplocator==="1" && countries.length==0) {
			sumo2.domains.problem+="-"+sumo2.language.VARIABLES.MOD_198+"<br/>";
		}
		if(sumo2.domains.problem !== "") {
			sumo2.message.NewMessage(sumo2.domains.problem,3);
		} else {
			var params = 'type=add$!$name='+name+'$!$alias='+alias+'$!$main='+main+'$!$language='+language+'$!$white='+white+'$!$black='+black+'$!$iplocator='+iplocator+'$!$countries='+countries;
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
				sumo2.ajax.SendPost("includes/domains.php",params,function(data) {
					if(data=="exist")
						sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_203,3);
					else
						setTimeout("window.location.reload()", 2000);
				});
			});
		}
	},	
	
	Edit : function(id) {
		sumo2.domains.problem="";
		var name=$("#a_domains_edit_"+id+" #name").val();
		var alias = sumo2.domains.Tag($("#a_domains_edit_"+id+" .domain_alias").tagit("tags"), "domain");
		var main=$("#a_domains_edit_"+id+" #main").val();
		var white = sumo2.domains.Tag($("#a_domains_edit_"+id+" .whiteIP").tagit("tags"), "IP");
		var black = sumo2.domains.Tag($("#a_domains_edit_"+id+" .blackIP").tagit("tags"), "IP");
		var iplocator=$("#a_domains_edit_"+id+" #iplocator").val();
		var countries = sumo2.domains.Tag($("#a_domains_edit_"+id+" .domain_countries").tagit("tags"));
		var language="";
		$("#a_domains_edit_"+id+" #domains_language :selected").each(function () {
			language += $(this).val() + "*/*";
		});
		language=language.slice(0,language.length-3);
		if(!sumo2.validate.IsDomain(name)) {
			sumo2.domains.problem+="-"+sumo2.language.VARIABLES.MOD_195+"<br/>";			
		}
		if(language==="" || language.indexOf("-1")>=0) {
			sumo2.domains.problem+="-"+sumo2.language.VARIABLES.MOD_196+"<br/>";			
		}
		if(iplocator==="-1") {
			sumo2.domains.problem+="-"+sumo2.language.VARIABLES.MOD_197+"<br/>";			
		} else if(iplocator==="1" && countries.length==0) {
			sumo2.domains.problem+="-"+sumo2.language.VARIABLES.MOD_198+"<br/>";
		}
		if(sumo2.domains.problem !== "") {
			sumo2.message.NewMessage(sumo2.domains.problem,3);
		} else {
			var params = 'type=edit$!$name='+name+'$!$alias='+alias+'$!$main='+main+'$!$language='+language+'$!$white='+white+'$!$black='+black+'$!$iplocator='+iplocator+'$!$countries='+countries+'$!$id='+id;
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
				sumo2.ajax.SendPost("includes/domains.php",params,function(data) {
					setTimeout("window.location.reload()", 2000);
				});
			});
		}
	},
	
	Tag: function(array, type) {
		var tags="";
		for (var i in array) {
			if(type=="domain") {
				if(!sumo2.validate.IsDomain(array[i].value)) {
					sumo2.domains.problem+="-"+sumo2.language.VARIABLES.MOD_194+"<br/>";
				}
			}
			else if(type=="IP") {
				if(!sumo2.validate.IsIP6(array[i].value) && !sumo2.validate.IsIP4(array[i].value)) {
					sumo2.domains.problem+="-"+sumo2.language.VARIABLES.MOD_193+"<br/>";
				}
			}
			tags += array[i].value+"*/*";
		}
		return tags=tags.slice(0,tags.length-3);
	},
	TIMEOUT : null,
	
    Show : function() {
        var div_s = document.getElementById("sumo2-domain-wrapper");
		 div_s.style.display="block";
    },
    
    Hide : function() {
        var div_s = document.getElementById("sumo2-domain-wrapper");
		div_s.style.display="none";
		this.TIMEOUT = null;
    },
	
	Select : function(id, lang) {
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
			sumo2.ajax.SendPost("includes/domains.php","type=saveSelection$!$id="+id+"$!$lang="+lang,function(data) {
				sumo2.accordion.DeleteParameters('a_sitetree');
				sumo2.accordion.DeleteParameters('a_domains');
				setTimeout("window.location.reload()", 2000);
			});
		});
	},
	
	Delete : function() {
		var id=$('#domains_current_tab').val();
		if(id==="#add") return;
		id=id.replace('#domain_', '');	
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_204,250,250,function() {
			sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_6,250,250,function() {
				sumo2.ajax.SendPost("includes/domains.php","type=delete$!$id="+id,function(data) {
					sumo2.accordion.DeleteParameters('a_sitetree');
					sumo2.accordion.DeleteParameters('a_domains');
					setTimeout("window.location.reload()", 2000);
				});
			});
		});		
	}
};

sumo2.seo= {
	addRedirect : function() {
		var source=document.d_seo_redirect_add.source.value;
		var destination=document.d_seo_redirect_add.destination.value;
		var code=document.d_seo_redirect_add.code.value;
		var params = 'type=addRedirect$!$source='+source+'$!$destination='+destination+'$!$code='+code;
		sumo2.ajax.SendPost("includes/seo.php",params,function(data) {
			if(data=="ok") {
				sumo2.dialog.CloseDialog('d_seo_redirect_add');
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_210,1);
				sumo2.accordion.ReloadAccordion('a_seo_redirect_view');
			} else
				sumo2.message.NewMessage(data,3);
		});
	},
	
	editRedirect : function() {
		var id=document.d_seo_redirect_edit.id.value;
		var source=document.d_seo_redirect_edit.source.value;
		var destination=document.d_seo_redirect_edit.destination.value;
		var code=document.d_seo_redirect_edit.code.value;
		var params = 'type=editRedirect$!$source='+source+'$!$destination='+destination+'$!$code='+code+'$!$id='+id;

		sumo2.ajax.SendPost("includes/seo.php",params,function(data) {
			if(data=="ok") {
				sumo2.dialog.CloseDialog('d_seo_redirect_edit');
				sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_210,1);
				sumo2.accordion.ReloadAccordion('a_seo_redirect_view');
			} else
				sumo2.message.NewMessage(data,3);
		});
	},
	
	deleteRedirect : function(id) {
		sumo2.dialog.NewConfirmation(sumo2.language.VARIABLES.WARNING,sumo2.language.VARIABLES.MOD_211,250,250,function() {
			var params = 'type=deleteRedirect$!$id='+id;
			sumo2.ajax.SendPost("includes/seo.php",params,function(data) {
				if(data=="ok") {
					sumo2.message.NewMessage(sumo2.language.VARIABLES.MOD_212,1);
					sumo2.accordion.ReloadAccordion('a_seo_redirect_view');
				} else
					sumo2.message.NewMessage(data,3);
			});
		});
	}
};
sumo2.AddLoadEvent(function() {
	sumo2.favorites.ReloadFavorites();
	sumo2.mail.CheckMain();
	setInterval('sumo2.mail.CheckMain()', 500000);
	var checkFake;
	sumo2.AddEvent(document.body,'mouseup',function(evt) {
		if(!evt) var evt = window.event;
		if(sumo2.siteTree.WORKING) {
			var drag = sumo2.siteTree.DRAG.obj;
			if(drag && drag.parentNode) {
				drag.parentNode.removeChild(drag);
				var name = sumo2.siteTree.DRAG.name;
				var id = sumo2.siteTree.DRAG.id;
				var frameInfo = sumo2.siteTree.DRAG.frameInfo;
				var info = null;
				if(window.frames.layout != null && window.frames.layout != 'undefined') {
					if(window.frames.layout.st != null && window.frames.layout.st != 'undefined') {
						info = window.frames.layout.st.GetInfo(evt,frameInfo);
					} else {
						window.location.reload(true);
					}	
				} else {
					window.location.reload(true);
				}
				if(info && info.lay != "notarget") {
					sumo2.dialog.NewDialog('d_layoutmodule','modid='+id+'$!$layout='+info.lay+'$!$tpage='+info.page);
				}
				else {
					checkFake = document.getElementsByClassName('modules-itemdrag')[0];
					if (checkFake) document.body.removeChild(checkFake);
				}
			}
			checkFake = document.getElementsByClassName('modules-itemdrag')[0];
			if (checkFake) document.body.removeChild(checkFake);
			sumo2.siteTree.WORKING = false;
		}
	},false);
	sumo2.AddEvent(document.body,'mousemove',function(evt) {
		if(!evt ||evt == null) var evt = window.event;
		if(sumo2.siteTree.WORKING) {
			var drag = sumo2.siteTree.DRAG.obj;
			var offset = sumo2.siteTree.DRAG.offset;
			var movePos = sumo2.tooltip.GetPosition(evt);
			sumo2.siteTree.SetPosition(drag,movePos,offset);
		}
	},false);
});