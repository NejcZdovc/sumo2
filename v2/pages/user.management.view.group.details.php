
<?php require_once('../initialize.php'); 
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$id = $crypt->decrypt($db->filter('gid'));
	$accordion_id='a_user_group_vd'.$id;
	$pagging=check_pagging("SELECT ID,username,email,GroupID,name,visit,enabled FROM cms_user WHERE GroupID='".$id."' AND status='N'", $user->items);
	$dropdown=dropdown_pagging($accordion_id, $pagging[0]);
	echo '<div class="flt-right display">'.$dropdown.'</div>';
?>
<div id="a_user_group_vd" style="clear:both;">
<table cellpadding="0" cellspacing="1" border="0" class="table1 table2" width="99%">
	<tr>
		<th><?=$lang->NAME?></th>
		<th><?=$lang->USERNAME?></th>
		<th><?=$lang->MAIL?></th>
		<th><?=$lang->GROUP?></th>
		<th><?=$lang->LAST_VISIT?></th>
		<? if($user->getAuth('FAV_USER_4') == 2 || $user->getAuth('FAV_USER_4') == 4 || $user->getAuth('FAV_USER_4') == 5)
			echo '<th>'.$lang->CONTROL.'</th>';
		?>
	</tr>
	<?php 
		
		$query = $db->query($pagging[4]);
		$counter = 1;
		while($result = $db->fetch($query)) {
			$group_id = $result['GroupID'];
			$new_query = $db->query("SELECT title FROM cms_user_groups WHERE ID='".$group_id."' AND status='N'");
			$new_result = $db->get($new_query);
			if($new_result) {
				if($counter&1) {
					echo '<tr class="odd">';
				} else {
					echo '<tr class="even">';
				}
				?>
					<td><?php echo $result['name'];?></td>
					<td><?php echo $result['username'];?></td>
					<td><?php echo $result['email'];?></td>
					<td><?php echo $new_result['title'];?></td>
					<td><?php echo $result['visit'];?></td>
                    <? if($user->getAuth('FAV_USER_4') == 2 || $user->getAuth('FAV_USER_4') == 4 || $user->getAuth('FAV_USER_4') == 5) {?>
					<td width="65px">
						<div title="<?=$lang->MOD_4?>" class="<?php echo $result['enabled']?"enable":"disable"; ?> sumo2-tooltip" onclick="sumo2.user.ChangeStatus('<?php echo $crypt->encrypt($result['ID']); ?>','<?=$accordion_id?>')"></div>
						<div title="<?=$lang->MOD_5?>" class="edit sumo2-tooltip" onclick="sumo2.user.EditUser('<?php echo $crypt->encrypt($result['ID']); ?>','<?=$accordion_id?>')"></div>
						<div title="<?=$lang->MOD_6?>" class="delete sumo2-tooltip" onclick="sumo2.user.DeleteUser('<?php echo $crypt->encrypt($result['ID']); ?>','<?=$accordion_id?>')"></div>
					</td>
                    <? } ?>
				</tr>
				<?php 
				$counter++;
			}
		}
		if($counter==1)
			echo '<tr><td colspan="6" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_77.'</b></td></td>';
	?>
</table>
<?= pagging($accordion_id, $pagging); ?>
</div>