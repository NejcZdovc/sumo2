<?php 
	require_once('../initialize.php');
    require_once('../configs/settings.php');
	
	$security->checkMin();
?>

<table cellpadding="0" cellspacing="1" border="0" class="table1 table2" id="a_ftp" width="99%">
	<thead>
    <tr>
    	<th width="20px"></th>
    	<th><?php echo $lang->NAME?></th>
		<th width="80"><?php echo $lang->MOD_51?></th>
		<th><?php echo $lang->MOD_11?></th>
		<th width="130"><?php echo $lang->MOD_52?></th>
        <th width="100"><?php echo $lang->MOD_53?></th>
		<?php if($user->getAuth('a_ftp') == 2 || $user->getAuth('a_ftp') == 4 || $user->getAuth('a_ftp') == 5)
			echo '<th width="123">'.$lang->CONTROL.'</th>';
		?>
	</tr>
    </thead>
    <tbody>
<?php
	if($db->is('path') && (strpos($db->filter('path'),'/storage/') !== false || strpos($db->filter('path'),'/images/') !== false)) {
		$dir=opendir($db->filter('path'));
		$dir_array = array();
		$file_array = array();
		while(($file=readdir($dir)) !== false) {
			$path = $db->filter('path').$file;
			if(is_file($path)) {
				$end=explode('.',$file);
				$end=end($end);
				$extension = strtolower($end);
				$fileName = str_replace('.'.$extension,'',$file);
				$stat = stat($path);
				$size = $stat['size'];
				$finSize = getSize($size);
				$modified = date($lang->DATE_1,$stat['mtime']);
				$split=explode('storage', $path);
				
				if(count($split)<2) {
					$split=explode('images', $path);
					$split='/images'.$split[1];
				}
				else
					$split='/storage'.$split[1];
					
				if(file_exists('../images/icons/ftp/small/'.$extension.'.gif'))
					$img='<img src="images/icons/ftp/small/'.$extension.'.gif" alt="'.$extension.'"/>';
				else
					$img='.'.$extension;
				array_push($file_array,'<tr>
					<td>'.$img.'</td>
					<td>'.$fileName.'.'.$extension.'</td>
					<td>'.$finSize.'</td>
					<td>'.$extension.'</td>
					<td>'.$modified.'</td>
					<td>0'.substr(decoct(fileperms($path)), 3).'</td>
					<td>
						<div style="width:16px;height:16px;cursor:pointer;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;" onclick="sumo2.ftp.DataViewComand(\'rename\', \''.$path.'\')"></div>&nbsp;&nbsp;
						<div style="width:16px;height:16px;cursor:pointer;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;" onclick="sumo2.ftp.DataViewComand(\'delete\', \''.$path.'\')"></div>&nbsp;&nbsp;
						<div style="width:16px; height:16px;cursor:pointer;display: inline-block; background-image: url(images/css_sprite.png); background-position: -540px -1629px;" onclick="sumo2.ftp.DataViewComand(\'open\', \''.$path.'\')"></div>&nbsp;&nbsp;
						<div style="width:16px; height:16px;cursor:pointer;display: inline-block; background-image: url(images/css_sprite.png); background-position: -668px -1677px" onclick="sumo2.ftp.DataViewComand(\'edit\', \''.$path.'\')"></div>&nbsp;&nbsp;
						<div style="width:16px; height:16px;cursor:pointer;display: inline-block; background-image: url(images/css_sprite.png); background-position: -636px -1661px" onclick="sumo2.ftp.DataViewComand(\'view\', \''.$path.'\')"></div>
					</td>
				</tr>');
			}
		}
		foreach($dir_array as $dir) {
			echo $dir;
		}
		foreach($file_array as $file) {
			echo $file;
		}
	} else {
	    echo $db->fitler('path');
	}
?>
	</tbody>
</table>