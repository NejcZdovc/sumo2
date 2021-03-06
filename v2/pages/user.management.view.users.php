<?php require_once('../initialize.php'); 
	$security->checkFull();
	$accordion_id='a_user_view_u';
	$query="";
	if($db->is('query') && strlen($db->filter('query'))>2) {
		$search=" AND (username LIKE '%".$db->filter('query')."%' OR email LIKE '%".$db->filter('query')."%' OR name LIKE '%".$db->filter('query')."%')";
		$query=$db->filter('query');
	} else
		$search='';
	$pagging=check_pagging("SELECT ID,username,email,GroupID,name,visit,enabled,registration FROM cms_user WHERE status='N' ".$search."", $user->items);
	$dropdown=dropdown_pagging($accordion_id, $pagging[0]);
	echo '<div class="flt-right display">'.$dropdown.'</div>';
?>
<div>
    <div style="margin-left:10px; float:left; margin-top:10px; font-weight:bold;"><?php echo $lang->MOD_44?></div>
    <input id="a_user_view_u_table_id_search" name="search" value="<?php echo $query ?>" class="input" style="width:200px; margin-left:10px;" value="" type="text" maxlength="50" />
</div>
<div id="a_user_view_u_table" style="clear:both;">
<?php echo  pagging($accordion_id, $pagging); ?>
<table cellpadding="0" cellspacing="1" border="0" id="a_user_view_u_table" summary="View users" class="table1 table2" width="99%">
<caption class="hide"><?php echo $lang->LEFT_USER_1?></caption>
	<tr>
		<th scope="col" abbr=""></th>
		<th scope="col" abbr="<?php echo $lang->NAME?>"><?php echo $lang->NAME?></th>
		<th scope="col" abbr="<?php echo $lang->USERNAME?>"><?php echo $lang->USERNAME?></th>
		<th scope="col" abbr="<?php echo $lang->MAIL?>"><?php echo $lang->MAIL?></th>
		<th scope="col" abbr="<?php echo $lang->GROUP?>"><?php echo $lang->GROUP?></th>
		<th scope="col" abbr="<?php echo $lang->LAST_VISIT?>"><?php echo $lang->LAST_VISIT?></th>
		<th scope="col" abbr="<?php echo $lang->REGISTRATION?>"><?php echo $lang->REGISTRATION?></th>
        <?php if($user->getAuth('a_user_view_u') == 2 || $user->getAuth('a_user_view_u') == 4 || $user->getAuth('a_user_view_u') == 5)
			echo '<th scope="col" abbr="'.$lang->CONTROL.'">'.$lang->CONTROL.'</th>';
		?>
	</tr>
	<tbody>
	<?php
		$query = $db->query($pagging[4]);
		$counter = 1;
		while($result = $db->fetch($query)) {
			$group_id = $result['GroupID'];
			$new_result = $db->get($db->query("SELECT title FROM cms_user_groups WHERE ID='".$group_id."' AND status='N'"));
			if($new_result) {
				if($counter&1) {
					echo '<tr class="odd">';
				} else {
					echo '<tr class="even">';
				}
				$rowEncryption = $crypt->encrypt($result['ID']);
				?>
					<td><a style="text-decoration:none;color:inherit;" href="#" onclick="sumo2.user.ToggleInfo('<?php echo $rowEncryption; ?>',this)">+</a></td>
					<td><?php echo $result['name'];?></td>
					<td><?php echo $result['username'];?></td>
					<td><?php echo $result['email'];?></td>
					<td><?php echo $new_result['title'];?></td>
					<td><?php echo date($lang->DATE_1, strtotime($result['visit']));?></td>
					<td><?php echo date($lang->DATE_1, strtotime($result['registration']));?></td>
                    <?php if($user->getAuth('a_user_view_u') == 2 || $user->getAuth('a_user_view_u') == 4 || $user->getAuth('a_user_view_u') == 5) {?>
					<td width="65px">
						<div title="<?php echo $lang->MOD_4?>" class="<?php echo $result['enabled']?"enable":"disable"; ?> sumo2-tooltip" onclick="sumo2.user.ChangeStatus('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
						<div title="<?php echo $lang->MOD_5?>" class="edit sumo2-tooltip" onclick="sumo2.user.EditUser('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
						<div title="<?php echo $lang->MOD_6?>" class="delete sumo2-tooltip" onclick="sumo2.user.DeleteUser('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
					</td>
                    <?php } ?>
				</tr>
				<tr id="sumo2-user-row-<?php echo $rowEncryption; ?>" style="display:none;">
					<td colspan="7">
						<?php
							$addInfo = $db->get($db->query("SELECT * FROM cms_user_aditional WHERE userID='".$result['ID']."'"));
							if($addInfo) {
								$fieldQuery = $db->query("SELECT * FROM cms_user_fields WHERE enabled='1' AND status='N'");
								echo '<table border="0" cellpadding="0" cellspacing="1" width="100%">';
								while($fieldResult = $db->fetch($fieldQuery)) {
									if(isset($addInfo[$fieldResult['fieldId']]) && $fieldResult['type'] <= 4) {
										echo '<tr>';
										echo '<td style="background:#efefef;"><div style="font-size:13px;font-weight:bold;">'.$fieldResult['labelName'].'</div><div style="font-size:10px">'.$fieldResult['description'].'</div></td>';
										echo '<td style="font-size:12px;vertical-align:middle">'.$addInfo[$fieldResult['fieldId']].'</td>';
										echo '</tr>';
									} else if(isset($addInfo[$fieldResult['fieldId']]) && $fieldResult['type'] == 6) {
										$checkArray = explode("!",$addInfo[$fieldResult['fieldId']]);
										$extraArray = explode(",",$fieldResult['extra']);
										echo '<tr>';
										echo '<td style="background:#efefef;"><div style="font-size:13px;font-weight:bold;">'.$fieldResult['labelName'].'</div><div style="font-size:10px">'.$fieldResult['description'].'</div></td><td style="font-size:12px;vertical-align:middle">';
										$first = true;
										foreach($checkArray as $item) {
											if(isset($extraArray[$item*1-1])) {
											if(!$first) echo ', ';
											if($first) $first = false;
											echo $extraArray[$item*1-1];
											}
										}
										echo '</td></tr>';
									} else if(isset($addInfo[$fieldResult['fieldId']]) && ($fieldResult['type'] == 5 || $fieldResult['type'] == 7 )) {
										$extraArray = explode(",",$fieldResult['extra']);
										echo '<tr>';
										echo '<td style="background:#efefef;"><div style="font-size:13px;font-weight:bold;">'.$fieldResult['labelName'].'</div><div style="font-size:10px">'.$fieldResult['description'].'</div></td><td style="font-size:12px;vertical-align:middle">';
											if(isset($extraArray[$addInfo[$fieldResult['fieldId']]*1-1])) {
											echo $extraArray[$addInfo[$fieldResult['fieldId']]*1-1];
											}
										echo '</td></tr>';
									}
								}
								echo '</table>';
							} else {
								echo $lang->MOD_75;
							}
						?>
					</td>
				</tr>
				<?php 
				$counter++;
			}
		}
		if($counter==1)
			echo '<tr><td colspan="7" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_3.'</b></td></td>';
	?>
	</tbody>
</table>
<?php echo  pagging($accordion_id, $pagging); ?>
</div>