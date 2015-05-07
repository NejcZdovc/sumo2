<?php 
	require_once('../initialize.php');
	$security->checkFull();
	
	$perm=array();
	$q=$db->query('SELECT * FROM cms_user_groups_permissions WHERE groupID="'.$id.'"');
	while($r=$db->fetch($q)) {
		$perm["'".$r['objectID']."'"]=array("enabled"=>$r['enabled'], "perm"=>$r['permission']);
	}	
    
    $system = simplexml_load_file('..'.DS.'system.xml');
    $modules = simplexml_load_file('..'.DS.'modules'.DS.'system.xml');
	
	$specialArray=array();
	$result = $db->get($db->query("SELECT short FROM cms_language WHERE ID='".$user->lang."'"));
	if($result) {
		$myfile = SITE_ROOT.SITE_FOLDER.'/v2/language/'.$result['short'].'/javascript.lang.xml';
		$specialArray = $xml->getSpecialArray($myfile);
		$moduleQ = $db->query("SELECT * FROM cms_modules_def WHERE status='N'");
		while($resultM = $db->fetch($moduleQ)) {
			$myfile = SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['moduleName'].'/language/javascript/'.$result['short'].'.xml';
			if(!is_file(SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['moduleName'].'/language/javascript/'.$result['short'].'.xml')) {
				if(is_file(SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['moduleName'].'/language/javascript/en.xml')) {
					$myfile = SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['moduleName'].'/language/javascript/en.xml';
				} else {
					continue;
				}
			}
			$specialArray=array_merge($specialArray, $xml->getSpecialArray($myfile));
		}
		$componentQ = $db->query("SELECT * FROM cms_components_def WHERE status='N'");
		while($resultM = $db->fetch($componentQ)) {
			$myfile = SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['componentName'].'/language/javascript/'.$result['short'].'.xml';
			if(!is_file(SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['componentName'].'/language/javascript/'.$result['short'].'.xml')) {
				if(is_file(SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['componentName'].'/language/javascript/en.xml')) {
					$myfile = SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['componentName'].'/language/javascript/en.xml';
				} else {
					continue;
				}
			}
			$specialArray=array_merge($specialArray, $xml->getSpecialArray($myfile));
		}
	} else {
		$myfile = SITE_ROOT.SITE_FOLDER.'/v2/language/en/javascript.lang.xml';
		$specialArray = $xml->getSpecialArray($filename);
		$moduleQ = $db->query("SELECT * FROM cms_modules_def WHERE status='N'");
		while($resultM = $db->fetch($moduleQ)) {
			if(is_file(SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['moduleName'].'/language/javascript/en.xml')) {
				$myfile = SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['moduleName'].'/language/javascript/en.xml';
			} else {
				continue;
			}
			$specialArray=array_merge($specialArray, $xml->getSpecialArray($myfile));
		}
		$componentQ = $db->query("SELECT * FROM cms_components_def WHERE status='N'");
		while($resultM = $db->fetch($componentQ)) {
			if(is_file(SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['componentName'].'/language/javascript/en.xml')) {
				$myfile = SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['componentName'].'/language/javascript/en.xml';
			} else {
				continue;
			}
			$specialArray=array_merge($specialArray, $xml->getSpecialArray($myfile));
		}
	}
	
	$values=array();
	foreach($specialArray as $item) {
		if(isset($item['attributes']) && isset($item['attributes']['constant']) && isset($item['value'])) {
			$values[$item['attributes']['constant']]=$item['value'];
		}
	}
	
	function getTitle($value, $values) {
		$return="";
		$temp=explode("+", $value);
		foreach($temp as $item) {
			$item=str_replace("sumo2.language.VARIABLES.", "", $item);
			if(isset($values[$item])) {
				$return.=$values[$item];
			} else {
				$return.=str_replace(array('"', "'"), "", $item);
			}
		}
		return $return;
	}
?>
<table cellpadding="0" cellspacing="4" border="0" width="100%" id="sumo2-user-group-permission">
    <tr>
        <td class="left_td" style="width: 50%; font-size:14px;" valign="top" id="sumo2-user-group-permission-accordion">
            <div class="title_form_big" style="text-align: center;"><?php echo $lang->MOD_256?></div>
			<table cellpadding="0" cellspacing="4" border="0">
				<tr>
					<td colspan="3"><b><?php echo $lang->MOD_258?></b> - <a href="javascript:sumo2.user.ToggleCheckbox('accordion');" style="cursor: pointer; color: #666;"><?php echo $lang->MOD_259 ?></a> / <a href="javascript:sumo2.user.TogglePerm('accordion', '5');" style="cursor: pointer; color: #666;"><?php echo $lang->MOD_260 ?></a><br/><br/>
					<?php echo $lang->USER_ADD_A_2?><br/><br/>
					</td>
				</tr>
				<?php
					foreach($system->accordion->item as $element) {
						if($element->uniqueId!="a_welcome"){
							$check="";
							$value=0;
							if(isset($perm["'".$element->uniqueId."'"]) && $perm["'".$element->uniqueId."'"]['enabled']=="1") {
								$check='checked="checked"';
								$value=$perm["'".$element->uniqueId."'"]['perm'];
							}
							echo '<tr>
									<td><input type="checkbox" value="sumo2-user-group-sel-'.$element->uniqueId.'" '.$check.' /></td>
									<td>'.getTitle($element->title, $values).'</td>
									<td>
										<select id="sumo2-user-group-sel-'.$element->uniqueId.'">
											<option value="1" '.($value == '1' ? 'selected="selected"' : '').'>1</option>
											<option value="2" '.($value == '2' ? 'selected="selected"' : '').'>2</option>
											<option value="3" '.($value == '3' ? 'selected="selected"' : '').'>3</option>
											<option value="4" '.($value == '4' ? 'selected="selected"' : '').'>4</option>
											<option value="5" '.($value == '5' ? 'selected="selected"' : '').'>5</option>
										</select>
									</td>
								</tr>';
						}
					}
				if(count($modules->accordion->item)>0) { ?>
					<tr>
						<td colspan="3"><b><?php echo $lang->FAV_MODULES?></b></td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<?php
					foreach($modules->accordion->item as $element) {
						$check="";
						$value=0;
						if(isset($perm["'".$element->uniqueId."'"]) && $perm["'".$element->uniqueId."'"]['enabled']=="1") {
							$check='checked="checked"';
							$value=$perm["'".$element->uniqueId."'"]['perm'];
						}
						echo '<tr>
								<td><input type="checkbox" value="sumo2-user-group-sel-'.$element->uniqueId.'" '.$check.' /></td>
								<td>'.getTitle($element->title, $values).'</td>
								<td>
									<select id="sumo2-user-group-sel-'.$element->uniqueId.'">
										<option value="1" '.($value == '1' ? 'selected="selected"' : '').'>1</option>
										<option value="2" '.($value == '2' ? 'selected="selected"' : '').'>2</option>
										<option value="3" '.($value == '3' ? 'selected="selected"' : '').'>3</option>
										<option value="4" '.($value == '4' ? 'selected="selected"' : '').'>4</option>
										<option value="5" '.($value == '5' ? 'selected="selected"' : '').'>5</option>
									</select>
								</td>
							</tr>';
					}
				}
				?>
			</table>
        </td>
        <td class="right_td" valign="top" style="font-size:14px;" id="sumo2-user-group-permission-dialog">
            <div class="title_form_big" style="text-align: center;"><?php echo $lang->MOD_257?></div>
			<table cellpadding="0" cellspacing="4" border="0">
				<tr>
					<td colspan="3"><b><?php echo $lang->MOD_258?></b> - <a href="javascript:sumo2.user.ToggleCheckbox('dialog');" style="cursor: pointer; color: #666;"><?php echo $lang->MOD_259 ?></a> / <a href="javascript:sumo2.user.TogglePerm('dialog', '2');" style="cursor: pointer; color: #666;"><?php echo $lang->MOD_260 ?></a><br/><br/>
					<?php echo $lang->USER_ADD_A_3?><br/><br/>
					</td>
				</tr>			
				<?php
					foreach($system->dialog->item as $element) {
						if($element->uniqueId!="d_relogin"){
							$check="";
							$value=0;
							if(isset($perm["'".$element->uniqueId."'"]) && $perm["'".$element->uniqueId."'"]['enabled']=="1") {
								$check='checked="checked"';
								$value=$perm["'".$element->uniqueId."'"]['perm'];
							}
							echo '<tr>
									<td><input type="checkbox" value="sumo2-user-group-sel-'.$element->uniqueId.'" '.$check.' /></td>
									<td>'.getTitle($element->title, $values).'</td>
									<td>
										<select id="sumo2-user-group-sel-'.$element->uniqueId.'">
											<option value="1" '.($value == '1' ? 'selected="selected"' : '').'>1</option>
											<option value="2" '.($value == '2' ? 'selected="selected"' : '').'>2</option>
										</select>
									</td>
								</tr>';
						}
					}
				if(count($modules->dialog->item)>0) { ?>
					<tr>
						<td colspan="3"><b><?php echo $lang->FAV_MODULES?></b></td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<?php
					foreach($modules->dialog->item as $element) {
						$check="";
						$value=0;
						if(isset($perm["'".$element->uniqueId."'"]) && $perm["'".$element->uniqueId."'"]['enabled']=="1") {
							$check='checked="checked"';
							$value=$perm["'".$element->uniqueId."'"]['perm'];
						}
						echo '<tr>
								<td><input type="checkbox" value="sumo2-user-group-sel-'.$element->uniqueId.'" '.$check.' /></td>
								<td>'.getTitle($element->title, $values).'</td>
								<td>
									<select id="sumo2-user-group-sel-'.$element->uniqueId.'">
										<option value="1" '.($value == '1' ? 'selected="selected"' : '').'>1</option>
										<option value="2" '.($value == '2' ? 'selected="selected"' : '').'>2</option>
									</select>
								</td>
							</tr>';
					}
				}
				?>
			</table>
		</td>
    </tr>
</table>