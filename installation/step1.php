<?php
	$stanje=0;

echo '<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<th width="80%">File</th>
    <th width="10%">Permission</th>
	<th width="10%">Status</th>
</tr>';
	$system = new DOMDocument();
	$system->load('permissions.xml');
	$files = $system->getElementsByTagName('files')->item(0);
	foreach($files->getElementsByTagName('item') as $item) {
		$file=$item->nodeValue;
		$ss = stat('../'.$item->nodeValue);
		echo '<tr><td>'.str_replace("../", "", $item->nodeValue).'</td>';
		echo '<td>'.sprintf("%o", ($ss['mode'] & 000644)).'</td>';
		if(sprintf("%o", ($ss['mode'] & 000644))!=644) {
			echo '<td><img src="no.png" /></td></tr>';
			$stanje+=1;
		}
		else
			echo '<td><img src="yes.png" /></td></tr>';
	}

echo '</table>';	
echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:30px;">
<tr>
	<th width="80%">Folder</th>
    <th width="10%">Permission</th>
	<th width="10%">Status</th>
</tr>';

	$system = new DOMDocument();
	$system->load('permissions.xml');
	$files = $system->getElementsByTagName('folders')->item(0);
	foreach($files->getElementsByTagName('item') as $item) {
		$file=$item->nodeValue;
		$ss = stat('../'.$item->nodeValue);
		echo '<tr><td>'.str_replace("../", "", $item->nodeValue).'</td>';
		echo '<td>'.sprintf("%o", ($ss['mode'] & 000755)).'</td>';
		if(sprintf("%o", ($ss['mode'] & 000755))!=755) {
			echo '<td><img src="no.png" /></td></tr>';
			$stanje+=1;
		}
		else
			echo '<td><img src="yes.png" /></td></tr>';
	}

echo '</table>';
echo '%#%#%'.$stanje;
?>