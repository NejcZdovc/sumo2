<?php
	function chmodAll($path, $filePerm=0644, $dirPerm=0755) {
		if(!file_exists($path)) {
			return false;
		}
		if(is_file($path)) {
			chmod($path, $filePerm) or die("Chmode problem: ".$path);
		} else if(is_dir($path)) {
			$foldersAndFiles = scandir($path);
			$entries = array_slice($foldersAndFiles, 2);
			foreach($entries as $entry) {
				chmodAll($path."/".$entry, $filePerm, $dirPerm);
			}
			chmod($path, $dirPerm)  or die("Chmode problem: ".$path);
		}
		return true;
	}

	function copyFiles($src, $dst) {
		$dir = opendir($src);
		if(!is_dir($dst)) {
			mkdir($dst, 0755) or die("Mkdir problem: ".$dst);
		}
		
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if (is_dir($src . '/' . $file) ) {
					copyFiles($src . '/' . $file,$dst . '/' . $file);
					chmodAll($dst . '/' . $file, 0644);					
				}
				else {
					copy($src . '/' . $file,$dst . '/' . $file) or die("Copy problem: ".$src . '/' . $file."  ->  ".$dst . '/' . $file);
					chmodAll($dst . '/' . $file, 0644);					
				}
			}
		}
		closedir($dir);
	}
	
	include('../v2/configs/settings.php');
	$link = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	if ($link->connect_errno) {
		printf("Connect failed: %s", $link->mysqli_error);
		exit();
	}
		
	echo '<form action="" name="forma" method="post" class="form2">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" >
		<tr>
			<td class="left_td" valign="middle">
			Is this main domain?
			</td>
			<td class="right_td" style="padding:5px;">
			<input type="checkbox" name="main[]" value="1" id="checkBoxY" checked="checked" onclick="changeCheckbox(\'y\');" /><label for="checkBoxY">Yes</label>
			<input type="checkbox" name="main[]" value="0" id="checkBoxN" onclick="changeCheckbox(\'n\');" /><label for="checkBoxN">No</label><br />
			<input type="text" name="enterfix" style="display:none;" />
			</td>
		</tr>
		<tr id="mainDomain" style="display:none;">
			<td class="left_td" valign="middle">
			Main domain:
			</td>	
			<td  class="right_td" style="padding:5px;">
				<input type="text" name="domain" id="domain" class="input" value="'.$_REQUEST['domain'].'" />
			</td>
		</tr>	
		<tr>
			<td class="left_td" valign="middle">
			Front-end language:
			</td>	
			<td  class="right_td" style="padding:5px;">
				 <select id="language" class="input">
					<option value="hr">Croatian</option>
					<option value="en">English</option>
					<option value="en-us">English (United States)</option>
					<option value="en-gb">English (United Kingdom)</option>
					<option value="de">German</option>
					<option value="de-lu">German (Austria)</option>
					<option value="it">Italian</option>
					<option value="ru">Russian</option>
					<option value="sr">Serbian (Latin)</option>
					<option value="sh">Serbo-Croatian</option>
					<option value="sl" selected="selected">Slovenian</option>
					<option value="es">Spanish (Spain)</option>
				</select>
			</td>
		</tr>	
		<tr>
			<td class="left_td" valign="middle">
			Name:
			</td>
	
			<td  class="right_td" style="padding:5px;">
				<input type="text" name="name" id="name" class="input" value="'.$_REQUEST['name'].'" />
			</td>
		</tr>	
		<tr>
			<td class="left_td" valign="middle">
			Username:
			</td>
	
			<td  class="right_td" style="padding:5px;">
				<input type="text" name="username" id="username" class="input" value="'.$_REQUEST['username'].'" />
			</td>
		</tr>	
		<tr>
			<td class="left_td" valign="middle">
			Password:
			</td>
	
			<td  class="right_td" style="padding:5px;">
				<input type="password" name="password" id="password" class="input" value="'.$_REQUEST['password'].'" />
			</td>
		</tr>	
		<tr>
			<td class="left_td" valign="middle">
			Retype password:
			</td>
	
			<td  class="right_td" style="padding:5px;">
				<input type="password" name="repassword" id="repassword" class="input" value="'.$_REQUEST['repassword'].'" />
			</td>
		</tr>	
		<tr>
			<td class="left_td" valign="middle">
			Email:
			</td>
	
			<td  class="right_td" style="padding:5px;">
				<input type="text" name="email" id="email" class="input" value="'.$_REQUEST['email'].'" />
			</td>
		</tr> 	
		</table>
	</form>';
	echo '%#%#%';
	
	if($_REQUEST['show']=="ok") 
	{
		$domain=$_REQUEST['domain'];
		$name=str_replace('www.', '', $_SERVER['HTTP_HOST']);
		$name=str_replace('http://', '',$name);
		$name=str_replace('/', '',$name);
		
		//template
		if(!is_dir('../templates/'.$name.'/')) {
			mkdir('../templates/'.$name.'/', 0755)or die("Mkdir problem: ".'../templates/'.$name.'/');			
		}
		chmod('../templates/'.$name.'/', 0755) or die("Chmode problem: ".'../templates/'.$name.'/');
		
		if(!is_dir('../templates/'.$name.'/images')) {
			mkdir('../templates/'.$name.'/images', 0755)or die("Mkdir problem: ".'../templates/'.$name.'/images');
		}
		chmod('../templates/'.$name.'/images', 0755) or die("Chmode problem: ".'../templates/'.$name.'/images');
		
		//module
		if(!is_dir('../modules/'.$name.'/')) {
			mkdir('../modules/'.$name, 0755)or die("Mkdir problem: ".'../modules/'.$name);			
		}
		chmod('../modules/'.$name, 0755) or die("Chmode problem: ".'../modules/'.$name);
		
		if(!is_dir('../modules/'.$name.'/images')) {
			mkdir('../modules/'.$name.'/images', 0755)or die("Mkdir problem: ".'../modules/'.$name.'/images');			
		}
		chmod('../modules/'.$name.'/images', 0755) or die("Chmode problem: ".'../modules/'.$name.'/images');
		
		//off
		if(!is_dir('../off/'.$name.'')) {
			mkdir('../off/'.$name, 0755) or die("Mkdir problem: ".'../off/'.$name);			
		}
		chmod('../off/'.$name, 0755) or die("Chmode problem: ".'../off/'.$name);
		copyFiles('../off/default', '../off/'.$name);
		
		
		//block
		if(!is_dir('../block/'.$name.'')) {
			mkdir('../block/'.$name, 0755) or die("Mkdir problem: ".'../block/'.$name);
		}
		chmod('../block/'.$name, 0755) or die("Chmode problem: ".'../block/'.$name);
		copyFiles('../block/default', '../block/'.$name);
		
		//404
		if(!is_dir('../404/'.$name.'')) {
			mkdir('../404/'.$name, 0755) or die("Mkdir problem: ".'../404/'.$name);
		}
		chmod('../404/'.$name, 0755) or die("Chmode problem: ".'../404/'.$name);
		copyFiles('../404/default', '../404/'.$name);
		
		//images
		if(!is_dir('../images/'.$name.'')) {
			mkdir('../images/'.$name, 0755) or die("Mkdir problem: ".'../images/'.$name);
		}
		chmod('../images/'.$name, 0755) or die("Chmode problem: ".'../images/'.$name);
		
		if(!is_dir('../images/'.$name.'/article')) {
			mkdir('../images/'.$name.'/article', 0755) or die("Mkdir problem: ".'../images/'.$name.'/article');
		}
		chmod('../images/'.$name.'/article', 0755) or die("Chmode problem: ".'../images/'.$name.'/article');
		
		//storage
		if(!is_dir('../storage/'.$name.'')) {
			mkdir('../storage/'.$name, 0755) or die("Mkdir problem: ".'../storage/'.$name);
		}
		chmod('../storage/'.$name, 0755) or die("Chmode problem: ".'../storage/'.$name);
		
		if(!is_dir('../storage/'.$name.'/Images')) {
			mkdir('../storage/'.$name.'/Images', 0755) or die("Mkdir problem: ".'../storage/'.$name.'/Images');
		}
		chmod('../storage/'.$name.'/Images', 0755) or die("Chmode problem: ".'../storage/'.$name.'/Images');
		
		if(!is_dir('../storage/'.$name.'/Documents')) {
			mkdir('../storage/'.$name.'/Documents', 0755) or die("Mkdir problem: ".'../storage/'.$name.'/Documents');
		}
		chmod('../storage/'.$name.'/Documents', 0755) or die("Chmode problem: ".'../storage/'.$name.'/Documents');
		
		//add user
		$string1=$_REQUEST['password'];
		$string2=$_REQUEST['username'];
		$pass=hash("sha512", $string1).hash("ripemd256",$string1).hash("ripemd320",$string2).hash("sha256",$string1);
		$link->query("INSERT INTO cms_user (username, pass, email, GroupID, name, enabled, status) VALUES ('".$link->real_escape_string($_REQUEST['username'])."', '".$pass."', '".$link->real_escape_string($_REQUEST['email'])."', 1, '".$link->real_escape_string($_REQUEST['name'])."', 1, 'N');") or die($link->mysqli_error);
		$userID=$link->insert_id;
		
		//add language
		$link->query("INSERT INTO cms_language_front (name,short) VALUES ('Main language', '".$link->real_escape_string($_REQUEST['language'])."')") or die($link->mysqli_error);
		$languageID=$link->insert_id;
		
		//add domain		
		if(strlen($domain)>3) {
			$link->query("INSERT INTO cms_domains (name, parentID, locator, alias) VALUES ('".$domain."', '-1', '0', '0')") or die($link->mysqli_error);
			$link->query("INSERT INTO cms_domains (name, parentID, locator, alias) VALUES ('".$name."', '".$link->insert_id."', '0', '0')") or die($link->mysqli_error);
			$name=str_replace('www.', '', $domain);
			$name=str_replace('http://', '',$name);
			$name=str_replace('/', '',$name);
		} else {
			$link->query("INSERT INTO cms_domains (name, parentID, locator, alias) VALUES ('".$name."', '-1', '0', '0')") or die($link->mysqli_error);
		}
		$domainID=$link->insert_id;
		
		//add domain language 
		$link->query("INSERT INTO cms_domains_ids (domainID, value, type)  VALUES	('".$domainID."', '".$link->real_escape_string($_REQUEST['language'])."', 'lang')") or die($link->mysqli_error);
		$link->query("INSERT INTO cms_homepage (title,lang,selection, domain) VALUES ('Home - Main language', '".$languageID."', 1, '".$domainID."')");
		$homeID=$link->insert_id;
		$link->query("INSERT INTO cms_menus_items (link,title,menuID,status,parentID,orderID,template,selection,target) VALUES ('".$homeID."', 'Home - Main language', '-1','DD','-1','-1','-1','-1','-1')"); 		
		
		
		//global settings for domain
		$link->query("INSERT INTO cms_global_settings (domain, title, display_title) VALUES ('".$domainID."', '', 'D')") or die($link->mysqli_error);
		
		//add domain group
		$link->query("INSERT INTO cms_domains_ids (type, domainID, elementID) VALUES ('group', '".$domainID."', '1')") or die($link->mysqli_error);
		$link->query("INSERT INTO cms_domains_ids (type, domainID, elementID) VALUES ('group', '".$domainID."', '2')") or die($link->mysqli_error);
		
		//favorites user
		$link->query("INSERT INTO cms_favorites (UserID, option1, option2, option3, option4, option5, option6, option7, option8, option9, option10) VALUES
(".$userID.", 1, 6, 0, 0, 0, 0, 0, 0, 0, 0);") or die($link->mysqli_error);
		
		//user state
		$link->query("INSERT INTO cms_state (userID, state) VALUES (".$userID.", 'empty');") or die($link->mysqli_error);
		
		//user settings
		$link->query("INSERT INTO cms_user_settings (userID, lang, items, accordion, preview, view, translate_state, translate_lang, updateOption, beta, developer, domain) VALUES (".$userID.", 1, 666, 5, 1, 'L', 'OFF', 0, 'OFF', 0, 0, ".$domainID.");") or die($link->mysqli_error);
		echo "0";
	}		
?>