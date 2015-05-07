<?php require_once('../initialize.php'); 
$security->checkFull();
?>
<table width="100%" cellpadding="0" cellspacing="0" style="height:inherit; background:#FFF;">
<tr>
	<td width="250px" valign="top" style="border-right:1px solid #cccccc;vertical-align: top !important;">
    	<input type="hidden" value="<?php echo  SITE_ROOT.SITE_FOLDER.'/storage/Documents/' ?>" id="file_manager_path" />
        <input type="hidden" value="" id="file_manager_selected" />
    	<div class="file-tree flt-left" valign="top" id="<?php echo SITE_ROOT.SITE_FOLDER.'/storage/'; ?>">
        	<ul id="file-manager-treeview" class="ending">
        	<?php
				function countDir($dir){
					$directory = opendir($dir);
					$inc = 0;
					while(($item = readdir($directory)) !== false){
							$path = $dir.$item;
						 if(($item != ".") && ($item != "..") && is_dir($path)){
							  $inc++;
						 }
					}
					return $inc;
				}
				
				function countFiles($dir){
					$directory = opendir($dir);
					$inc = 0;
					while(($item = readdir($directory)) !== false){
						$inc++;
					}
					return $inc;
				}
				
				function traverseDirTree($base,$root){
				  global $db;
				  $subdirectories=opendir($base);
				  $dirs = countDir($base);
				  $length = countFiles($base);
				  $dirInc = 0;
				  if($dirs <= 0) return;
				  for($i=0;$i<$length;$i++) {
					  $subdirectory=readdir($subdirectories);
					  $path = $base.$subdirectory;
					  if($subdirectory != '.' && $subdirectory != '..' && is_dir($path)) {
						  if($root && $dirInc == 0) echo '<ul>';
						  if($db->is('path') && $subdirectory != '_thumbs') {
							  if($db->filter('path') == $path."/") {
							  echo '<li class="show"><div id="'.$path.'" class="conMenu" onclick="sumo2.ftp.RefreshFileView(\''.$path.'/'.'\')">'.$subdirectory.'</div>';
							  } else {
						  	echo '<li><div id="'.$path.'" class="conMenu" onclick="sumo2.ftp.RefreshFileView(\''.$path.'/'.'\')">'.$subdirectory.'</div>';  
						  }
						  } else {
							if($subdirectory == 'Documents') {
							  echo '<li class="show"><div id="'.$path.'" class="conMenu" onclick="sumo2.ftp.RefreshFileView(\''.$path.'/'.'\')">'.$subdirectory.'</div>';
						  } else {
							  if($subdirectory != '_thumbs')
						  			echo '<li><div id="'.$path.'" class="conMenu" onclick="sumo2.ftp.RefreshFileView(\''.$path.'/'.'\')">'.$subdirectory.'</div>';  
						  }  
						  }
						  traverseDirTree($path.'/',true);
						  echo '</li>';
						  if($root && $dirInc == $dirs-1) echo '</ul>';
						  $dirInc++;
					  }
				  }
				}
				traverseDirTree(SITE_ROOT.SITE_FOLDER.'/storage/',false);
				traverseDirTree(SITE_ROOT.SITE_FOLDER.'/images/',false);
			?>
            </ul>
        </div>
        </td>
        <td width="20px" style="background:#F1F1F1;">
        </td>
        <td style="border-left:1px solid #cccccc;">
        <div class="file-view flt-right" valign="top" id="file-manager-fileview">
        
        </div>
        </td>
     </tr>
 </table>
<div class="clear"></div>
 <div class="contextMenu" id="myMenu1" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?php echo $lang->FILE_2?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?php echo $lang->FILE_3?></li>
        <li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?php echo $lang->FILE_4?></li>
      </ul>
</div>
 <div class="contextMenu" id="myMenu3" style="display:none;">
      <ul style="font-size:12px;">
        <li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?php echo $lang->FILE_4?></li>
      </ul>
</div>