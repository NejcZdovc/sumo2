//I. faza
function pricni1 () {
	$("#zacni").hide();
	$("#button").html('<div onclick="preveri1();" style="cursor:pointer"><img src="check.png" width="97" height="41" /></div>');
	$("#help").html('Vse spodaj navedene datoteke in mape morajo imeti pravice 777.');
	$.ajax({
	  url: 'step1.php',
	  success: function(data) {
		var spli=data.split("%#%#%");
		$('#content').html(spli[0]);
		$("#progressbar").show("slow");
		$("#help").show("slow");
		$("#content").show("slow");	
		if(spli[1]=="0") {
			$("#button").hide("slow"); 
			$("#button").html('<div onclick="pricni2();" style="cursor:pointer"><img src="next.png" width="77" height="37" /></div>');
			$("#button").show("slow");
		}
		else 
			$("#button").show("slow");
	  }
	});
}
 
function preveri1() {
	$.ajax({
	  url: 'step1.php',
	  success: function(data) {
		var spli=data.split("%#%#%");
		$('#content').html(spli[0]);
		if(spli[1]=="0") {
			$("#button").hide("slow", function () {
				$("#button").html('<div onclick="pricni2();" style="cursor:pointer"><img src="next.png" width="77" height="37" /></div>');
			});			
			$("#button").show("slow");
		}
	  }
	});
}

//II. faza
function pricni2 () {
	$("#button").hide("slow", function () { 
		$("#button").html('<div onclick="preveri2();" style="cursor:pointer"><img src="check.png" width="97" height="41" /></div>');	
	});
	$("#content").hide("slow");
	$("#help").hide("slow", function () {
		$("#help").html('Vnesite podate do vaše baze, nato pritisnite prever in če ste vnesli pravilne podatke boste lahko nadaljevali z delov, v nasprotnem primeru pa popravite podatke!');		
	});
	$.ajax({
		url: 'step2.php',
		success: function(data) {
			var spli=data.split("%#%#%");
			$('#content').html(spli[0]);
			$("#progressbar").progressbar({ value: 25 });
			$("#help").show("slow");
			$("#content").show("slow");	
			$("#button").show("slow");
		}
	});
}

function preveri2() {
	$.ajax({
	  url: 'step2.php',
	  data: "tip="+$('#tip').val()+"&user="+$('#user').val()+"&pass="+$('#pass').val()+"&name="+$('#name').val()+"&show=ok",
	  success: function(data) {
		var spli=data.split("%#%#%");
		$('#content').html(spli[0]);
		if(spli[1]=="0") {
			$("#button").hide("slow", function () {
				$("#button").html('<div onclick="pricni3();" style="cursor:pointer"><img src="next.png" width="77" height="37" /></div>');
			});			
			$("#button").show("slow");
		}
	  }
	});
}

//III. faza
function pricni3 () {
	$("#button").hide("slow");
	$("#content").hide("slow");
	$("#help").hide("slow", function () {
		$("#help").html('V tem koraku se bo ustvarila baza in vstavili podatki, ki so potrebni za osnovno delovanje strani. Če pride do napake, prosim poskusite ponovno s klikom preveri, drugače nadaljujte na naslednji korak.');	
	});	
	$.ajax({
	  url: 'step3.php',
	  success: function(data) {
		$("#progressbar").progressbar({ value: 50 });
		$("#help").show("slow");
		$('#content').html("");
		$("#content").show("slow");	
		if(data=="0") {
			$('#content').html('<span style="color:#387C44;">Vsi vnosi so bili uspešni!</span>');
			$("#button").hide("slow", function () {
				$("#button").html('<div onclick="pricni4();" style="cursor:pointer"><img src="next.png" width="77" height="37" /></div>');
			});
			$("#button").show("slow");
		}
		else  {
			$('#content').html(data);
			$("#button").html('<div onclick="preveri3();" style="cursor:pointer"><img src="check.png" width="97" height="41" /></div>');
			$("#button").show("slow");
		}
	  }
	});
}

function preveri3() {
	$("#button").hide();
	$.ajax({
	  url: 'step3.php',
	  success: function(data) {
		if(data=="0") {
			$('#content').html('<span style="color:#387C44;">Vsi vnosi so bili uspešni!</span>');
			$("#content").show("slow");	
			$("#button").html('<div onclick="pricni4();" style="cursor:pointer"><img src="next.png" width="77" height="37" /></div>');
			$("#button").show("slow");
		}
		else {
			$('#content').html(data);
			$("#button").html('<div onclick="preveri3();" style="cursor:pointer"><img src="check.png" width="97" height="41" /></div>');
			$("#button").show("slow");
		}
	  }
	});
}

//IV. faza
function pricni4 () {
	$("#button").hide("slow");
	$("#content").hide("slow");
	$("#help").hide("slow", function () {
		$("#help").html('V tem koraku se bo ustvaril administratorski račun. Podana bosta uporabniško ime in geslo.<br/> V CMS-u si lahko nato spremenite geslo.');	
	});
	$.ajax({
		url: 'step4.php',
		success: function(data) {
			$("#progressbar").progressbar({ value: 75 });
			$("#help").show("slow");
			var spli=data.split("%#%#%");
			$('#content').html(spli[0]);
			$("#content").show("slow");		
			$("#button").html('<div onclick="preveri4();" style="cursor:pointer"><img src="check.png" width="97" height="41" /></div>');
			$("#button").show("slow");
		}
	});
}

function preveri4() {
	$("#content").hide("slow");
	$.ajax({
		url: 'step4.php',
		data: "username="+$('#username').val()+"&password="+$('#password').val()+"&repassword="+$('#repassword').val()+"&domain="+$('#domain').val()+"&email="+$('#email').val()+"&name="+$('#name').val()+"&language="+$('#language').val()+"&show=ok",
		success: function(data) {
			var spli=data.split("%#%#%");
			$('#content').html(spli[0]);
			$("#content").show("slow");	
			var error=false;
			if($('#username').val().length<2) {
				$('#username').css('border', '#F00 1px solid');
				error=true;
			}
			if($('#password').val().length<6) {
				$('#password').css('border', '#F00 1px solid');
				error=true;
			} else if($('#password').val()!=$('#repassword').val()) {
				$('#password').css('border', '#F00 1px solid');
				$('#repassword').css('border', '#F00 1px solid');
				error=true;
			}
			if($("#checkBoxN").prop("checked") && $('#domain').val().length<3) {
				$('#domain').css('border', '#F00 1px solid');	
				error=true;			
			}
			if(!error && spli[1]=="0") {
				$("#content").show("slow", function () {
					$('#content').html('<span style="color:#387C44;">Uporabnik je bil uspešno ustvarjen!</span>');
				});	
				$("#button").html('<div onclick="pricni5();" style="cursor:pointer"><img src="next.png" width="77" height="37" /></div>');
				$("#button").show("slow");
			}
			else {
				if(!error) {
					$("#content").show("slow", function () {
						$('#content').html("Postopek ni uspel, prosim poskusite ponovno.<br/>"+spli[1]);			
					});
				}
				$("#button").html('<div onclick="preveri4();" style="cursor:pointer"><img src="check.png" width="97" height="41" /></div>');
				$("#button").show("slow");
			}		
		}
	});	
}

//V. faza
function pricni5 () {
	$("#button").hide("slow", function () {
		$("#button").html('<div onclick="window.location=\'http://'+window.location.hostname+'/v2/\'" style="cursor:pointer"><img src="finish.png" width="96" height="41" /></div>');	
	});
	$("#content").hide("slow");
	$("#help").hide("slow", function () {
		$("#help").html('Uspešno ste zaključili nastavitev Sumo 2 sistema.<br/> S klikom na zaključi vas bomo preusmerili na administracijske strani.');	
	});
	$("#progressbar").progressbar({ value: 100 });
	$("#help").show("slow");
	$("#button").show("slow");
}

function changeCheckbox (type) {
	if(type=="y") {
		$("#mainDomain").hide();
		$("#checkBoxN").prop("checked", false);
	} else {
		$("#mainDomain").show();
		$("#checkBoxY").prop("checked", false);
	}
}