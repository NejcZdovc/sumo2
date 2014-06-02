<?php
	require_once('../initialize.php');
	if(!$session->isLogedIn() || !$security->checkURL()) {
		exit;
	}
	
	if($db->is('path') && (strpos($db->filter('path'),'/storage/') !== false || strpos($db->filter('path'),'/images/') !== false)) {
		$dir=opendir($db->filter('path'));
		$dir_array = array();
		$file_array = array();
		while(($file=readdir($dir)) !== false) {
			$path = $db->filter('path').$file;
			if(is_file($path)) {
				$extension = strtolower(end(explode('.',$file)));
				$fileName = str_replace('.'.$extension,'',$file);
				$fullName = $fileName;
				if(strlen($fileName) > 11) {
					$fileName = substr($fileName,0,11).'...';	
				}
				else
					$fileName=$fileName.'.'.$extension;
				$stat = stat($path);
				$size = $stat['size'];
				$finSize = getSize($size);
				$modified = date("m-d-Y H:i:s",$stat['mtime']);
				$type = $extension;
				$info = 'Name: '.$fullName.'<br />Type: '.$type.'<br />Modified: '.$modified.'<br />Size: '.$finSize;
				$split=explode('storage', $path);
				
				if(count($split)<2) {
					$split=explode('images', $path);
					$split='/images'.$split[1];
				}
				else
					$split='/storage'.$split[1];
				
				if(preg_match('/[.](gif|jpg|png|jpeg)$/i', $path)) {
					array_push($file_array,'<div id="'.$path.'" class="file-box"><div title="'.$info.'" class="sumo2-tooltip file-img" onclick="sumo2.ftp.AddSelected(\''.$path.'\', this)">
					<div class="file-div-img"><img src="'.$split.'" class="thumb" alt="'.$fileName.'"/></div></div><div class="file-name">'.$fileName.'</div></div>');
				} 
				elseif(preg_match('/[.](avi|doc|docx|html|mp3|pdf|ppt|pptx|rar|xls|zip|xlsx)$/i', $path)) {
					array_push($file_array,'<div id="'.$path.'" class="file-box"><div title="'.$info.'" class="sumo2-tooltip file-img" onclick="sumo2.ftp.AddSelected(\''.$path.'\', this)"><img src="images/icons/ftp/big/'.$extension.'.gif" class="thumb" alt="'.$fileName.'"/></div><div class="file-name">'.$fileName.'</div></div>');					
				}
				else {
					array_push($file_array,'<div id="'.$path.'" class="file-box"><div title="'.$info.'" class="sumo2-tooltip file-img" onclick="sumo2.ftp.AddSelected(\''.$path.'\', this)"><img src="includes/file.manager.image.php?type='.$extension.'" /></div><div class="file-name">'.$fileName.'</div></div>');
				}
			}
		}
		foreach($dir_array as $dir) {
			echo $dir;
		}
		foreach($file_array as $file) {
			echo $file;
		}
	} else {
	    echo $db->filter('path');
	}
?>
<div class="contextMenu" id="myMenu2" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?php echo $lang->FILE_2?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?php echo $lang->FILE_3?></li>
        <li id="open"><div style="width:16px; height:16px;display: inline-block; background-image: url(images/css_sprite.png); background-position: -540px -1629px;"></div>&nbsp;<?php echo $lang->MOD_54?></li>
        <li id="edit"><div style="width:16px; height:16px;display: inline-block; background-image: url(images/css_sprite.png); background-position: -668px -1677px"></div>&nbsp;<?php echo $lang->EDIT?></li>
        <li id="view"><div class="view"></div>&nbsp;<?php echo $lang->MOD_55?></li>
      </ul>
</div>