<?php require_once('../initialize.php'); 
	$security->checkFull();
	$id = $crypt->decrypt($db->filter('gid'));
	$accordion_id='a_user_group_vd'.$id;
	$pagging=check_pagging("SELECT ID,username,email,GroupID,name,visit,enabled FROM cms_user WHERE GroupID='".$id."' AND status='N'", $user->items);
	$dropdown=dropdown_pagging($accordion_id, $pagging[0]);
	echo '<div class="flt-right display">'.$dropdown.'</div>';
?>
<div id="a_user_group_vd" style="clear:both;">
<table cellpadding="0" cellspacing="1" border="0" class="table1 table2" width="99%">
	<tr>
		<th><?php echo $lang->NAME?></th>
		<th><?php echo $lang->USERNAME?></th>
		<th><?php echo $lang->MAIL?></th>
		<th><?php echo $lang->GROUP?></th>
		<th><?php echo $lang->LAST_VISIT?></th>
		<?php if($user->getAuth('a_user_group_vd') == 2 || $user->getAuth('a_user_group_vd') == 4 || $user->getAuth('a_user_group_vd') == 5)
			echo '<th>'.$lang->CONTROL.'</th>';
		?>
	</tr>
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
				?>
					<td><?php echo $result['name'];?></td>
					<td><?php echo $result['username'];?></td>
					<td><?php echo $result['email'];?></td>
					<td><?php echo $new_result['title'];?></td>
					<td><?php echo $result['visit'];?></td>
                    <?php if($user->getAuth('a_user_group_vd') == 2 || $user->getAuth('a_user_group_vd') == 4 || $user->getAuth('a_user_group_vd') == 5) {?>
					<td width="65px">
						<div title="<?php echo $lang->MOD_4?>" class="<?php echo $result['enabled']?"enable":"disable"; ?> sumo2-tooltip" onclick="sumo2.user.ChangeStatus('<?php echo $crypt->encrypt($result['ID']); ?>','<?php echo $accordion_id?>')"></div>
						<div title="<?php echo $lang->MOD_5?>" class="edit sumo2-tooltip" onclick="sumo2.user.EditUser('<?php echo $crypt->encrypt($result['ID']); ?>','<?php echo $accordion_id?>')"></div>
						<div title="<?php echo $lang->MOD_6?>" class="delete sumo2-tooltip" onclick="sumo2.user.DeleteUser('<?php echo $crypt->encrypt($result['ID']); ?>','<?php echo $accordion_id?>')"></div>
					</td>
                    <?php } ?>
				</tr>
				<?php 
				$counter++;
			}
		}
		if($counter==1)
			echo '<tr><td colspan="6" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_77.'</b></td></td>';
	?>
</table>
<?php echo  pagging($accordion_id, $pagging); ?>
</div>