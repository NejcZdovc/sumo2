<?php
if( !defined( '_VALID_MIX' ) && !defined( '_VALID_ETT' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}

class Fields
{	
	public $javascript="";
	
	public function input($min, $max, $fieldId, $required="0", $value=null, $dynamic=false) {
		global $db;
		if($required=="1") {
		$this->javascript.="var element=document.getElementById('".$fieldId."');
		if (element)  {
		 if(element.value.length<".$min." || element.value.length>".$max.") {ok=false;element.className=element.className.replace('error','');element.className+='error';} 
		 else
		  {element.className=element.className.replace('error','');}}";
		}
		if($dynamic && $db->is($fieldId))
			return '<input type="text" maxlength="'.$max.'" value="'.$db->filter($fieldId).'" name="'.$fieldId.'" id="'.$fieldId.'" />';		
		else if($value!=null)
			return '<input type="text" maxlength="'.$max.'" value="'.$value.'" name="'.$fieldId.'" id="'.$fieldId.'" />';
		else
			return '<input type="text" maxlength="'.$max.'" name="'.$fieldId.'" id="'.$fieldId.'" />';		
	}
	
	public function email($fieldId, $value=null, $required="0", $dynamic=false) {
		global $db;
		if($required=="0") {
			$this->javascript.="var element=document.getElementById('".$fieldId."'); var reg = /^([a-zA-Z0-9\u00A1-\uFFFF]+([\.+_-][a-zA-Z0-9\u00A1-\uFFFF]+)*)@(([a-zA-Z0-9]+((\.|[-]{1,2})[a-zA-Z0-9]+)*)\.[a-zA-Z]{2,6})$/;
			if (element)  {
			if(element.value.length>0 && !reg.test(element.value)) {ok=false;element.className=element.className.replace('error','');element.className+='error';} else {element.className=element.className.replace('error','');}}";
		} else {
			$this->javascript.="var element=document.getElementById('".$fieldId."'); var reg = /^([a-zA-Z0-9\u00A1-\uFFFF]+([\.+_-][a-zA-Z0-9\u00A1-\uFFFF]+)*)@(([a-zA-Z0-9]+((\.|[-]{1,2})[a-zA-Z0-9]+)*)\.[a-zA-Z]{2,6})$/;
			if (element)  {
			if(!reg.test(element.value)) {ok=false;element.className=element.className.replace('error','');element.className+='error';} else {element.className=element.className.replace('error','');}}";
		}
		if($dynamic && $db->is($fieldId))		
			return '<input type="text" name="'.$fieldId.'" id="'.$fieldId.'" value="'.$db->filter($fieldId).'" />';
		else if($value!=null)
			return '<input type="text" name="'.$fieldId.'" id="'.$fieldId.'" value="'.$value.'" />';
		else
			return '<input type="text" name="'.$fieldId.'" id="'.$fieldId.'"/>';
	}
	
	public function password($min, $fieldId, $required, $value=null, $dynamic=false) {
		global $db;
		if($required=="1") {
		$this->javascript.="var element=document.getElementById('".$fieldId."'); if (element)  {if(element.value.length<".$min.") {ok=false;element.className=element.className.replace('error','');element.className+='error';} else {element.className=element.className.replace('error','');}}";
		}
		if($dynamic && $db->is($fieldId))
			return '<input type="password" value="'.$db->filter($fieldId).'" name="'.$fieldId.'" id="'.$fieldId.'" />';		
		else if($value!=null) 
			return '<input type="password" value="'.$value.'" name="'.$fieldId.'" id="'.$fieldId.'" />';		
		else
			return '<input type="password" name="'.$fieldId.'" id="'.$fieldId.'" />';		
	}
	
	public function check($fieldId, $options, $required, $position, $value=null, $dynamic=false) {
		global $db;
		$options=explode(',', $options);
		$code="";
		$stevilo=0;
		if($required=="1") {
			$this->javascript.="var element=document.getElementsByName('".$fieldId."[]');if (element)  {var napaka=true; for (i = 0; i < element.length; i++) {if(element[i].checked) {napaka=false;}} if(napaka){ok=false;for (i = 0; i < element.length; i++) {document.getElementById(element[i].id+'_label').className=element[i].className.replace('error_text','');document.getElementById(element[i].id+'_label').className+=' error_text';}} else {for (i = 0; i < element.length; i++) {document.getElementById(element[i].id+'_label').className=document.getElementById(element[i].id+'_label').className.replace('error_text','');}}}";	
		}
		foreach($options as $i) {
			if($dynamic && isset($_REQUEST[$fieldId]) && in_array($i, $_REQUEST[$fieldId]))	{	
				if($position=="L")	
					$code.='<label for="'.$fieldId.'_'.$stevilo.'_label" id="'.$fieldId.'_'.$stevilo.'_label">'.$i.'</label><input type="checkbox" name="'.$fieldId.'[]" class="'.$fieldId.'" checked="checked" id="'.$fieldId.'_'.$stevilo.'" value="'.$i.'" />';
				else
					$code.='<input type="checkbox" name="'.$fieldId.'[]" class="'.$fieldId.'" checked="checked" id="'.$fieldId.'_'.$stevilo.'" value="'.$i.'" /><label for="'.$fieldId.'_'.$stevilo.'_label" id="'.$fieldId.'_'.$stevilo.'_label">'.$i.'</label>';
			} else {
				if($position=="L")
					$code.='<label for="'.$fieldId.'_'.$stevilo.'" id="'.$fieldId.'_'.$stevilo.'_label">'.$i.'</label><input type="checkbox" name="'.$fieldId.'[]" class="'.$fieldId.'" id="'.$fieldId.'_'.$stevilo.'" value="'.$i.'" />';
				else
					$code.='<input type="checkbox" name="'.$fieldId.'[]" class="'.$fieldId.'" id="'.$fieldId.'_'.$stevilo.'" value="'.$i.'" /><label for="'.$fieldId.'_'.$stevilo.'" id="'.$fieldId.'_'.$stevilo.'_label">'.$i.'</label>';		
			}
			$stevilo++;		
		}		
		return $code;
	}
	
	public function text($min, $max, $fieldId, $required="0", $value=null, $dynamic=false) {
		global $db;
		if($required=="1") {
		$this->javascript.="var element=document.getElementById('".$fieldId."');
		if (element)  {
		 if(element.value.length<".$min." || element.value.length>".$max.") {ok=false;element.className=element.className.replace('error','');element.className+='error';} 
		 else
		  {element.className=element.className.replace('error','');}}";
		}
		if($dynamic && $db->is($fieldId))
			return '<textarea maxlength="'.$max.'" name="'.$fieldId.'" id="'.$fieldId.'">'.$db->filter($fieldId).'"</textarea>';		
		else if($value!=null)
			return '<textarea maxlength="'.$max.'" name="'.$fieldId.'" id="'.$fieldId.'">'.$value.'</textarea>';
		else
			return '<textarea maxlength="'.$max.'" name="'.$fieldId.'" id="'.$fieldId.'"></textarea>';	
	}
	
	public function select($fieldId, $options, $required="0", $value=null) {
		global $db;
		$options=explode(',', $options);
		$result='<select name="'.$fieldId.'">';
		foreach($options as $i) {
			if($value==$i)
				$result.='<option value="'.$i.'" selected="selected">'.$i.'</option>';
			else
				$result.='<option value="'.$i.'">'.$i.'</option>';
		}
		$result.='</select>';
		return $result;
	}
}