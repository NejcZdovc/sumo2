<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log','../v2/logs/error.log');
	include('../v2/configs/settings.php');
	$url="localhost";
	$name="";
	$user="";
	$pass="";
	
	if(isset($_REQUEST['tip'])) {
		$url=$_REQUEST['tip'];
	}
	if(isset($_REQUEST['name'])) {
		$name=$_REQUEST['name'];
	}
	if(isset($_REQUEST['user'])) {
		$user=$_REQUEST['user'];
	}
	if(isset($_REQUEST['pass'])) {
		$pass=$_REQUEST['pass'];
	}
	$stanje=0;
	echo '<div id="help">Insert your database access information and press check. If the inserted access data are correct the installation will continue. If the installation does not continue, please correct inserted data.</div>';
		echo '<form action="" name="forma" method="post" class="form2">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" >
		<tr>
			<td class="left_td" valign="middle">
			Server URL:
			</td>
			<td class="right_td" style="padding:5px;">
			<input type="text" name="tip" id="tip" class="input" value="'.$url.'" />
			<input type="text" name="enterfix" style="display:none;" />
			</td>
		</tr>
		<tr>
			<td class="left_td" valign="middle">
			Database name:
			</td>
	
			<td  class="right_td" style="padding:5px;">
				<input type="text" name="name" id="name" class="input" value="'.$name.'" />
			</td>
		</tr>
		 <tr>
			<td class="left_td" valign="middle">
			User name:
			</td>
			<td class="right_td" style="padding:5px;">
				<input type="text" name="user" id="user" class="input" value="'.$user.'" />
			</td>
		</tr>
		<tr>
			<td  class="left_td" valign="middle">
			Password:
			</td>
			<td  class="right_td" style="padding:5px;">
				<input type="password" name="pass" id="pass" class="input" value="'.$pass.'" />
			</td>
		
		</tr>		
		</table>
	</form>';

	if(isset($_REQUEST['show']) && $_REQUEST['show']=="ok") {
		$link = new mysqli($url, $user, $pass, $name);
		$link->set_charset(__ENCODING__);
		if ($link->connect_errno) {
			printf("Connect failed: %s", $link->connect_error);
    		exit();
		} else {
			$fp = fopen('../v2/configs/settings.php', 'ab');
			$besedilo="
				
	//Database
	define('__DB_SERVER__','".$url."');
	define('__DB_USER__','".$user."');
	define('__DB_PASSWORD__','".$pass."');
	define('__DB_DATABASE__','".$name."');
?>";
		
			$besedilo=str_replace("/*/", "", $besedilo);
			fwrite($fp, $besedilo);
			fclose($fp);
			@chmod('../v2/configs/settings.php', PER_FILE);
		}
	}
	echo '%#%#%'.$stanje;
?>