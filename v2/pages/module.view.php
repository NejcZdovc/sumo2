<?php require_once('../initialize.php'); 
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
?>
<div class="flt-right display"><span style="cursor:pointer;margin:6px 2px;display:inline-block;" onclick="sumo2.moduleManager.Reload('com')"><?=$lang->MOD_115?></span></div>
<div class="flt-right display"><span style="margin:6px 2px;display:inline-block;cursor:pointer;" onclick="sumo2.moduleManager.Reload('mod')"><?=$lang->MOD_116?></span></div>
<div id="a_module_view_table" style="clear:both;">
	<?php
		if($db->is('type') && $db->filter('type') == 'com') {
			$query = $db->query("SELECT * FROM cms_components_def WHERE status='N'");
			$counter = 1;
			while($result = $db->fetch($query)) {
				$counter++;
				$version="";
				if($result['version']!='null' && $result['version']!='NULL' && $result['version']!=0 && $result['version']!="") {
					$version='['.$result['version'].']';
				}
					?>
		       <div class="module-outer">
		       <div class="module-inner">
				<div style="width:150px;height:150px;" onmouseover="document.getElementById('modul_view_control_<?=$result['ID']?>').style.display='block'" onmouseout="document.getElementById('modul_view_control_<?=$result['ID']?>').style.display='none'">
				<? if(file_exists($_SERVER['DOCUMENT_ROOT'].'/v2/modules/'.$result['componentName'].'/big.png')) { ?>
					<img src="modules/<?=$result['componentName']?>/big.png" width="150" height="150" style="float:left;" alt="<?php echo $result['name'];?>" />
				<? } else if(file_exists($_SERVER['DOCUMENT_ROOT'].'/v2/modules/'.$result['componentName'].'/big.jpg')) { ?>
					<img src="modules/<?=$result['componentName']?>/big.jpg" width="150" height="150" style="float:left;" alt="<?php echo $result['name'];?>" />
				<? } else if(file_exists($_SERVER['DOCUMENT_ROOT'].'/v2/modules/'.$result['componentName'].'/big.gif')) { ?>
					<img src="modules/<?=$result['componentName']?>/big.gif" width="150" height="150" style="float:left;" alt="<?php echo $result['name'];?>" />
				<? } ?>
				<div style="margin-left:80px; z-index:2; position:absolute; display:none;" id="modul_view_control_<?=$result['ID']?>">
                	<div title="<?=$lang->MOD_205?>" style="margin-top:4px; margin-left:5px; background: url(images/css_sprite.png) -668px -1677px;" class="edit sumo2-tooltip" onclick="sumo2.dialog.NewDialog('d_module_edit', 'id=<?php echo $crypt->encrypt($result['ID']); ?>$!$type=com')"></div>
					<div title="<?=$lang->MOD_117?>" style="margin-top:4px;  background: url(images/css_sprite.png) -652px -1661px;width:16px;height:16px;" class="delete sumo2-tooltip" onclick="sumo2.moduleManager.View('<?php echo $result['componentName']; ?>')"></div>
					<div title="<?=$lang->MOD_118?>" style="margin-top:4px; margin-left:5px; background: url(images/css_sprite.png) -636px -1629px;" class="delete sumo2-tooltip" onclick="sumo2.moduleManager.DeleteC('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
				</div>
			    </div>
			    <div style="text-align:center; font-size:16px; font-weight:bold;"><?php echo $result['name'];?> <?=$version?></div>
			    <div style="text-align:center; margin-top:3px;"><?php echo date($lang->DATE_1, strtotime($result['date']));?></div>
		       </div>
		       </div>
					<?php 
				}
			if($counter==1)
				echo '<div style="text-align:center; font-size:13px;"><b>'.$lang->MOD_119.'</b></div>';
		} else {
			$query = $db->query("SELECT * FROM cms_modules_def WHERE status='N'");
			$counter = 1;
			while($result = $db->fetch($query)) {
				$counter++;
				$version="";
				if($result['version']!='null' && $result['version']!='NULL' && $result['version']!=0 && $result['version']!="") {
					$version='['.$result['version'].']';
				}
					?>
		       <div class="module-outer">
		       <div class="module-inner">
				<div style="width:150px;height:150px;" onmouseover="document.getElementById('modul_view_control_<?=$result['ID']?>').style.display='block'" onmouseout="document.getElementById('modul_view_control_<?=$result['ID']?>').style.display='none'">
				<? if(file_exists($_SERVER['DOCUMENT_ROOT'].'/v2/modules/'.$result['moduleName'].'/big.png')) { ?>
					<img src="modules/<?=$result['moduleName']?>/big.png" width="150" height="150" style="float:left;" alt="<?php echo $result['name'];?>" />
				<? } else if(file_exists($_SERVER['DOCUMENT_ROOT'].'/v2/modules/'.$result['moduleName'].'/big.jpg')) { ?>
					<img src="modules/<?=$result['moduleName']?>/big.jpg" width="150" height="150" style="float:left;" alt="<?php echo $result['name'];?>" />
				<? } else if(file_exists($_SERVER['DOCUMENT_ROOT'].'/v2/modules/'.$result['moduleName'].'/big.gif')) { ?>
					<img src="modules/<?=$result['moduleName']?>/big.gif" width="150" height="150" style="float:left;" alt="<?php echo $result['name'];?>" />
				<? } ?>
				<div style="margin-left:80px; z-index:2; position:absolute; display:none;" id="modul_view_control_<?=$result['ID']?>">
                	<div title="<?=$lang->MOD_205?>" style="margin-top:4px; margin-left:5px; background: url(images/css_sprite.png) -668px -1677px;" class="edit sumo2-tooltip" onclick="sumo2.dialog.NewDialog('d_module_edit', 'id=<?php echo $crypt->encrypt($result['ID']); ?>$!$type=mod')"></div>
					<div title="<?=$lang->MOD_120?>" style="margin-top:4px;  background: url(images/css_sprite.png) -652px -1661px;width:16px;height:16px;" class="delete sumo2-tooltip" onclick="sumo2.moduleManager.View('<?php echo $result['moduleName']; ?>')"></div>
					<div title="<?=$lang->MOD_121?>" style="margin-top:4px; margin-left:5px; background: url(images/css_sprite.png) -636px -1629px;" class="delete sumo2-tooltip" onclick="sumo2.moduleManager.Delete('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
				</div>
			    </div>
			    <div style="text-align:center; font-size:16px; font-weight:bold;"><?php echo $result['name'];?> <?=$version?></div>
			    <div style="text-align:center; margin-top:3px;"><?php echo date($lang->DATE_1, strtotime($result['date']));?></div>
		       </div>
		       </div>
					<?php 
				}
			if($counter==1)
				echo '<div style="text-align:center; font-size:13px;"><b>'.$lang->MOD_1.'</b></div>';
		}
	?>

</div>
