<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log','../v2/logs/error.log');
	include('../v2/configs/settings.php');
	
	$stanje=0;
	echo '<div id="help">Set the current permissions to match the required permissions.</div>';
	echo '<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<th width="70%">File</th>
		<th width="10%">Current</th>
		<th width="10%">Required</th>
		<th width="10%">Status</th>
	</tr>';
	$system = new DOMDocument();
	$system->load('permissions.xml');
	$files = $system->getElementsByTagName('files')->item(0);
	foreach($files->getElementsByTagName('item') as $item) {
		$file=$item->nodeValue;
		$ss = fileperms('../'.$item->nodeValue);
		
		echo '<tr><td>'.str_replace("../", "", $item->nodeValue).'</td>';
		if($item->getAttribute('perm')) {
			$filePerm=floatval($item->getAttribute('perm'));
		} else {
			$filePerm=sprintf('%o',PER_FILE);
		}
		echo '<td>'.substr(sprintf('%o', $ss), 3).'</td>';
		echo '<td>'.$filePerm.'</td>';
		if(substr(sprintf('%o', $ss), 2)!=$filePerm) {
			echo '<td><img src="no.png" /></td></tr>';
			$stanje+=1;
		}
		else
			echo '<td><img src="yes.png" /></td></tr>';
	}

echo '</table>';	
echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:30px;">
<tr>
	<th width="70%">File</th>
	<th width="10%">Current</th>
	<th width="10%">Required</th>
	<th width="10%">Status</th>
</tr>';

	$system = new DOMDocument();
	$system->load('permissions.xml');
	$files = $system->getElementsByTagName('folders')->item(0);
	foreach($files->getElementsByTagName('item') as $item) {
		$file=$item->nodeValue;
		$ss = fileperms('../'.$item->nodeValue);		
		echo '<tr><td>'.str_replace("../", "", $item->nodeValue).'</td>';
		
		if($item->getAttribute('perm')) {
			$fodlerPerm=$item->getAttribute('perm');
		} else {
			$fodlerPerm=sprintf('%o',PER_FOLDER);
		}
		
		echo '<td>'.substr(sprintf('%o', $ss), 2).'</td>';
		echo '<td>'.$fodlerPerm.'</td>';
		if(substr(sprintf('%o', $ss), 1)!=$fodlerPerm) {
			echo '<td><img src="no.png" /></td></tr>';
			$stanje+=1;
		}
		else
			echo '<td><img src="yes.png" /></td></tr>';
	}

echo '</table>';
echo '%#%#%'.$stanje;
?>