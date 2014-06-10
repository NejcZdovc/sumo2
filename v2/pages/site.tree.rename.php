<?php require_once('../initialize.php');
$security->checkFull();
$query = $db->fetch($db->query("SELECT * FROM cms_panel_".$db->filter('layout')." WHERE ID='".$db->filter('panel')."'"));
$layoutName = "cms_panel_".$db->filter('layout');
$tableName = $db->get($db->query("SELECT * FROM cms_modules_def WHERE ID='".$query['modulID']."'"));
$modulData = $db->get($db->query("SELECT * FROM ".$tableName['editTable']." WHERE cms_layout='".$db->filter('layout')."' AND cms_panel_id='".$db->filter('panel')."'"));
$shortLayout = $db->filter('layout');
?>
<div class="center-inputs"><div class="input-label"><?php echo $lang->MOD_29?>:</div> 
<input type="text" name="rename" id="rename-item-sitetree" class="input" value="<?php echo $query['title']?>" />
<input type="hidden" id="sitetree-modul" value="<?php echo $db->filter('panel')?>" />
<input type="hidden" id="sitetree-layout" value="<?php echo $db->filter('layout')?>" />
<input type="text" name="enterfix" style="display:none;" />
<div class="input-label" style="margin-top:13px; float:left; margin-right:10px;"><?php echo $lang->MOD_30?>:</div>
<select id="prefixOfModule" style="float:left; margin-top:10px;">
		<?php
			$query2=$db->query('SELECT name, ID, prefix FROM cms_modul_prefix WHERE enabled="1" AND domain="'.$user->domain.'"');
			while($results=$db->fetch($query2)) {
				if($results['prefix'] == $query['prefix'])
					$selected='selected="selected"';
				else
					$selected='';
				echo '<option value="'.$results['prefix'].'" '.$selected.' >'.$results['name'].'</option>';
			}		
		?>
</select>
<div style="clear:both; margin-bottom:5px;"></div>
<div class="input-label" style="margin-top:13px; float:left; margin-right:10px;"><?php echo $lang->MOD_168?>:</div>
<input type="text" name="cache" id="cache-item-sitetree" style="width:50px;" class="input" value="<?php echo $query['cache']?>" />&nbsp;<?php echo $lang->MOD_169?>
<div style="clear:both; margin-bottom:5px;"></div>
<div class="input-label" style="margin-top:13px; float:left; margin-right:10px;"><?php echo $lang->MOD_198?>:</div>
<select id="specialPage" style="float:left; margin-top:10px;">
	<option value="1" <?php if($query['specialPage'] == '1') echo 'selected="selected"';?>><?php echo $lang->ARTICLE_19?></option>
	<option value="0" <?php if($query['specialPage'] == '0') echo 'selected="selected"';?>><?php echo $lang->ARTICLE_20?></option>
</select>
<?php if($query['copyModul'] != '0') { ?>
<div style="clear:both; margin-bottom:5px;"></div>
<div class="input-label" style="margin-top:13px; float:left; margin-right:10px;"><?php echo $lang->MOD_199?>:</div>
<select id="copyModul" style="float:left; margin-top:10px;">
	<option value="1" <?php if($query['copyModul'] != '0') echo 'selected="selected"';?>><?php echo $lang->ARTICLE_19?></option>
	<option value="0" <?php if($query['copyModul'] == '0') echo 'selected="selected"';?>><?php echo $lang->ARTICLE_20?></option>
</select>
<?php } else { ?>
	<input type="hidden" value="0" id="copyModul" />
<?php } ?>
<div style="clear:both;"></div>
</div>
<div style="height:10px;display:block;width:100%;"></div>
<div id="sumo2-sitetree-rename-check">
	<?php
		$first = true;
		$counter = 0;
		$int = 0;
		$tpage = $query['pageID'];
		$tempResult = $db->get($db->query("SELECT * FROM cms_menus_items WHERE ID='".$tpage."'"));
		if($tempResult['parentID'] == -1 && $tempResult['orderID'] == -1 && $tempResult['selection']!="4") {
			$infoResults = $db->get($db->query("SELECT * FROM cms_homepage WHERE ID='".$tempResult['link']."'"));
			$tempResult['template'] = $infoResults['template'];
			$langResult = $infoResults;
		} else if($tempResult['selection']=="4") {
			$langResult = $db->get($db->query("SELECT target FROM cms_menus_items WHERE ID='".$tpage."'"));
			$langResult['lang']=$langResult['target'];
		} else {
			$langResult = $db->get($db->query("SELECT lang FROM cms_menus WHERE status='N' AND ID='".$tempResult['menuID']."'"));
		}
				echo '<ul id="tree-menu-rename" class="the-tree">';
				$main=$db->query('SELECT ID, title FROM cms_menus WHERE status="N" AND lang="'.$langResult['lang'].'" AND domain="'.$user->domain.'"');
				$homeResult = $db->get($db->query("SELECT * FROM cms_homepage WHERE lang='".$langResult['lang']."' AND domain='".$user->domain."'"));
if($homeResult['template'] == $tempResult['template']) {
$homeIdResult = $db->get($db->query("SELECT * FROM cms_menus_items WHERE link='".$homeResult['ID']."' AND parentID='-1' AND orderID='-1'"));
				$checkedQuery = $db->query("SELECT * FROM ".$layoutName.", ".$tableName['editTable']." WHERE ".$layoutName.".pageID='".$homeIdResult['ID']."' AND ".$layoutName.".modulID='".$tableName['ID']."' AND ".$tableName['editTable'].".cms_group_id='".$modulData['cms_group_id']."'  AND ".$tableName['editTable'].".cms_layout='".$shortLayout."'  AND ".$tableName['editTable'].".cms_panel_id=".$layoutName.".ID");
							if($db->rows($checkedQuery) > 0) {
								$checked = 'checked="checked"';
							} else {
								$checked = '';
							}
				echo '<li><div id="'.$homeIdResult['ID'].'#'.$homeIdResult['parentID'].'" style="cursor:pointer;"><input type="checkbox" '.$checked.' name="test" value="'.$homeIdResult['ID'].'" />'.$homeResult['title'].'</div></li>';
}
				while($mainresults=$db->fetch($main)){
					$id_main=$mainresults['ID'];
					//base level
					$queryT=$db->query('SELECT ID, title, parentID, orderID, enabled, template FROM cms_menus_items WHERE menuID='.$id_main.' AND parentID="-1" AND status="N" AND template="'.$tempResult['template'].'" ORDER BY orderID asc');
					$int=$db->rows($queryT);
					echo '<li class="special"><div id="0#'.$id_main.'" style="cursor:pointer;"><input type="checkbox" name="test" value="root" disabled="disabled" /><span style="display:inline-block;width:16px;height:16px;background:url(/v2/images/css_sprite.png); background-position:-669px -1709px;cursor:pointer;" onclick="sumo2.siteTree.CheckForChildsButton(this)"></span>'.$mainresults['title'].'</div>';
					if($int>0) {
						//first level
						echo '<ul>';
						$int = 0;
						while($results=$db->fetch($queryT)){
							$checkedQuery = $db->query("SELECT * FROM ".$layoutName.", ".$tableName['editTable']." WHERE ".$layoutName.".pageID='".$results['ID']."' AND ".$layoutName.".modulID='".$tableName['ID']."' AND ".$tableName['editTable'].".cms_group_id='".$modulData['cms_group_id']."' AND ".$tableName['editTable'].".cms_layout='".$shortLayout."'  AND ".$tableName['editTable'].".cms_panel_id=".$layoutName.".ID");
							if($db->rows($checkedQuery) > 0) {
								$checked = 'checked="checked"';
							} else {
								$checked = '';
							}
							$query2=$db->query('SELECT ID, title, parentID, orderID, enabled, template FROM cms_menus_items WHERE menuID='.$id_main.' AND parentID="'.$results['ID'].'" AND status="N" AND template="'.$tempResult['template'].'" ORDER BY orderID asc');
							$int=$db->rows($query2);
							if($results['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
							echo '<li><div '.$ena.' id="'.$results['ID'].'#'.$id_main.'" onclick=""><input type="checkbox" name="test" '.$checked.' value="'.$results['ID'].'" /><span style="display:inline-block;width:16px;height:16px;background:url(/v2/images/css_sprite.png); background-position:-669px -1709px;cursor:pointer;" onclick="sumo2.siteTree.CheckForChildsButton(this)"></span>'.$results['title'].'</div>';
							if($int>0) {
								$int = 0;
								//second level
								echo '<ul>';
								while($results2=$db->fetch($query2)){
									$checkedQuery = $db->query("SELECT * FROM ".$layoutName.", ".$tableName['editTable']." WHERE ".$layoutName.".pageID='".$results2['ID']."' AND ".$layoutName.".modulID='".$tableName['ID']."' AND ".$tableName['editTable'].".cms_group_id='".$modulData['cms_group_id']."'  AND ".$tableName['editTable'].".cms_layout='".$shortLayout."'  AND ".$tableName['editTable'].".cms_panel_id=".$layoutName.".ID");
							if($db->rows($checkedQuery) > 0) {
								$checked = 'checked="checked"';
							} else {
								$checked = '';
							}
									$query3=$db->query('SELECT ID, title, parentID, orderID, enabled, template FROM cms_menus_items WHERE menuID='.$id_main.' AND parentID="'.$results2['ID'].'" AND status="N" AND template="'.$tempResult['template'].'" ORDER BY orderID asc');
									$int=$db->rows($query3);
									if($results2['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
									echo '<li><div '.$ena.' id="'.$results2['ID'].'#'.$id_main.'" onclick=""><input type="checkbox" '.$checked.' name="test" value="'.$results2['ID'].'" /><span style="display:inline-block;width:16px;height:16px;background:url(/v2/images/css_sprite.png); background-position:-669px -1709px;cursor:pointer;" onclick="sumo2.siteTree.CheckForChildsButton(this)"></span>'.$results2['title'].'</div>';
									if($int>0) {
										$int = 0;
										//third level
										echo '<ul>';
										while($results3=$db->fetch($query3)){
										$checkedQuery = $db->query("SELECT * FROM ".$layoutName.", ".$tableName['editTable']." WHERE ".$layoutName.".pageID='".$results3['ID']."' AND ".$layoutName.".modulID='".$tableName['ID']."' AND ".$tableName['editTable'].".cms_group_id='".$modulData['cms_group_id']."'  AND ".$tableName['editTable'].".cms_layout='".$shortLayout."'  AND ".$tableName['editTable'].".cms_panel_id=".$layoutName.".ID");
							if($db->rows($checkedQuery) > 0) {
								$checked = 'checked="checked"';
							} else {
								$checked = '';
							}
							$query4=$db->query('SELECT ID, title, parentID, orderID, enabled, template FROM cms_menus_items WHERE menuID='.$id_main.' AND parentID="'.$results3['ID'].'" AND status="N" AND template="'.$tempResult['template'].'" ORDER BY orderID asc');
									$int=$db->rows($query4);
											if($results3['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
											echo '<li><div '.$ena.' id="'.$results3['ID'].'#'.$id_main.'" onclick=""><input type="checkbox" '.$checked.' name="test" value="'.$results3['ID'].'" /><span style="display:inline-block;width:16px;height:16px;background:url(/v2/images/css_sprite.png); background-position:-669px -1709px;cursor:pointer;" onclick="sumo2.siteTree.CheckForChildsButton(this)"></span>'.$results3['title'].'</div>';
											if($int>0) {
												//fourth level
												echo "<ul>";
												while($results4=$db->fetch($query4)){
													$checkedQuery = $db->query("SELECT * FROM ".$layoutName.", ".$tableName['editTable']." WHERE ".$layoutName.".pageID='".$results4['ID']."' AND ".$layoutName.".modulID='".$tableName['ID']."' AND ".$tableName['editTable'].".cms_group_id='".$modulData['cms_group_id']."'  AND ".$tableName['editTable'].".cms_layout='".$shortLayout."'  AND ".$tableName['editTable'].".cms_panel_id=".$layoutName.".ID");
													if($db->rows($checkedQuery) > 0) {
														$checked = 'checked="checked"';
													} else {
														$checked = '';
													}
													if($results4['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
											echo '<li><div '.$ena.' id="'.$results4['ID'].'#'.$id_main.'" onclick=""><input type="checkbox" '.$checked.' name="test" value="'.$results4['ID'].'" /><span style="display:inline-block;width:16px;height:16px;background:url(/v2/images/css_sprite.png); background-position:-669px -1709px;cursor:pointer;" onclick="sumo2.siteTree.CheckForChildsButton(this)"></span>'.$results4['title'].'</div></li>';
												}
												echo "</ul>";
											}
											echo "</li>";
										}
										echo "</ul>";
									}
									echo "</li>";
								}	
								echo "</ul>";
							}
							echo "</li>";		
						}
						echo "</ul>";
					}
					echo "</li>";	
				}
				echo '<li class="special"><div id="0#0" style="cursor:pointer;"><input disabled="disabled" type="checkbox" name="test" value="root" /><span style="display:inline-block;width:16px;height:16px;background:url(/v2/images/css_sprite.png); background-position:-669px -1709px;cursor:pointer;" onclick="sumo2.siteTree.CheckForChildsButton(this)"></span>'.$lang->MOD_98.'</div>';
				$specialRows = $db->rows($db->query("SELECT * FROM cms_menus_items WHERE selection='4' AND status='N' AND enabled='1' AND target='".$langResult['lang']."' AND template='".$tempResult['template']."' AND domain='".$user->domain."'"));
				if($specialRows > 0) {
					echo "<ul>";
				}
				$modulesQuery = $db->query("SELECT DISTINCT menuID FROM cms_menus_items WHERE selection='4' AND status='N' AND enabled='1' AND target='".$langResult['lang']."' AND template='".$tempResult['template']."' AND domain='".$user->domain."'");
				while($modulesRes = $db->fetch($modulesQuery)) {
					$modInfo = $db->get($db->query("SELECT * FROM cms_modules_def WHERE ID='".$modulesRes['menuID']."'"));
					echo '<li class="special"><div id="0#0" style="cursor:pointer;"><input disabled="disabled" type="checkbox" name="test" value="root" /><span style="display:inline-block;width:16px;height:16px;background:url(/v2/images/css_sprite.png); background-position:-669px -1709px;cursor:pointer;" onclick="sumo2.siteTree.CheckForChildsButton(this)"></span>'.$modInfo['name'].'</div><ul>';
					$spQuery = $db->query("SELECT * FROM cms_menus_items WHERE menuID='".$modInfo['ID']."' AND selection='4' AND status='N' AND enabled='1' AND target='".$langResult['lang']."' AND template='".$tempResult['template']."' AND domain='".$user->domain."'");
					while($result = $db->fetch($spQuery)) {
						$checkedQuery = $db->query("SELECT * FROM ".$layoutName.", ".$tableName['editTable']." WHERE ".$layoutName.".pageID='".$result['ID']."' AND ".$layoutName.".modulID='".$tableName['ID']."' AND ".$tableName['editTable'].".cms_group_id='".$modulData['cms_group_id']."'  AND ".$tableName['editTable'].".cms_layout='".$shortLayout."'  AND ".$tableName['editTable'].".cms_panel_id=".$layoutName.".ID");
						if($db->rows($checkedQuery) > 0) {
							$checked = 'checked="checked"';
						} else {
							$checked = '';
						}
						echo '<li><div id="'.$result['ID'].'#0" onclick=""><input '.$checked.' type="checkbox" name="test" value="'.$result['ID'].'" />'.$result['title'].'</div></li>';
					}
					echo "</ul>";
				}
				if($specialRows > 0) {
					echo "</ul>";
				}
				echo "</li>";
				echo "</ul>";
		?>
</div>
