<? 
	require_once('../initialize.php');
    require_once('../configs/settings.php');
	
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
?>

<table cellpadding="0" cellspacing="1" border="0" class="table1 table2" id="a_ftp" width="99%">
	<thead>
    <tr>
    	<th width="20px"></th>
    	<th><?=$lang->NAME?></th>
		<th width="80"><?=$lang->MOD_51?></th>
		<th><?=$lang->MOD_11?></th>
		<th width="130"><?=$lang->MOD_52?></th>
        <th width="100"><?=$lang->MOD_53?></th>
		<? if($user->getAuth('FAV_SITE_2') == 2 || $user->getAuth('FAV_SITE_2') == 4 || $user->getAuth('FAV_SITE_2') == 5)
			echo '<th width="123">'.$lang->CONTROL.'</th>';
		?>
	</tr>
    </thead>
    <tbody>
<?php
	if(isset($_POST['path']) && (strpos($_POST['path'],'/storage/') !== false || strpos($_POST['path'],'/images/') !== false)) {
		$dir=opendir($_POST['path']);
		$dir_array = array();
		$file_array = array();
		while(($file=readdir($dir)) !== false) {
			$path = $_POST['path'].$file;
			if(is_file($path)) {
				$extension = strtolower(end(explode('.',$file)));
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
	    echo $_POST['path'];
	}
?>
	</tbody>
</table>