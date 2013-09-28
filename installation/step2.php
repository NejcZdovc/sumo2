<?php
	$stanje=0;
		echo '<form action="" name="forma" method="post" class="form2">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" >
		<tr>
			<td class="left_td" valign="middle">
			Server URL:
			</td>
			<td class="right_td" style="padding:5px;">
			<input type="text" name="tip" id="tip" class="input" value="'.$_REQUEST['tip'].'" />
			<input type="text" name="enterfix" style="display:none;" />
			</td>
		</tr>
		<tr>
			<td class="left_td" valign="middle">
			Database name:
			</td>
	
			<td  class="right_td" style="padding:5px;">
				<input type="text" name="name" id="name" class="input" value="'.$_REQUEST['name'].'" />
			</td>
		</tr>
		 <tr>
			<td class="left_td" valign="middle">
			User name:
			</td>
			<td class="right_td" style="padding:5px;">
				<input type="text" name="user" id="user" class="input" value="'.$_REQUEST['user'].'" />
			</td>
		</tr>
		<tr>
			<td  class="left_td" valign="middle">
			Password:
			</td>
			<td  class="right_td" style="padding:5px;">
				<input type="password" name="pass" id="pass" class="input" value="'.$_REQUEST['pass'].'" />
			</td>
		
		</tr>		
		</table>
	</form>';

	if($_REQUEST['show']=="ok") {
		$link = new mysqli($_REQUEST['tip'], $_REQUEST['user'], $_REQUEST['pass'], $_REQUEST['name']);
		$link->set_charset("utf8");
		if ($link->connect_errno) {
			printf("Connect failed: %s", $link->mysqli_error);
    		exit();
		} else {
			$fp = fopen('../v2/configs/settings.php', 'ab');
			$besedilo="
				
			//Database
			define('DB_SERVER','".$_REQUEST['tip']."');
			define('DB_USER','".$_REQUEST['user']."');
			define('DB_PASSWORD','".$_REQUEST['pass']."');
			define('DB_DATABASE','".$_REQUEST['name']."');
		?>";
		
			$besedilo=str_replace("/*/", "", $besedilo);
			fwrite($fp, $besedilo);
			fclose($fp);
			chmod('../v2/configs/settings.php', 0644);
		}
	}
	echo '%#%#%'.$stanje;
?>