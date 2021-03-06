<?php require_once('../initialize.php'); 
	$security->checkFull();
	$accordion_id='a_user_view_g';
	$pagging=check_pagging("SELECT ID,title,description,creation,enabled FROM cms_user_groups WHERE status='N'", $user->items);
	$dropdown=dropdown_pagging($accordion_id, $pagging[0]);
	echo '<div class="flt-right display">'.$dropdown.'</div>';
?>
<div id="a_user_view_g_table" style="clear:both;">
<table cellpadding="0" cellspacing="1" border="0" class="table1 table2" width="99%" id="viewgroups">
	<tr>
		<th><?php echo $lang->TITLE?></th>
		<th><?php echo $lang->USER_ADD_D_1?></th>
		<th><?php echo $lang->USER_NUMBER?></th>
		<th><?php echo $lang->CREATE_DATE?></th>
		<?php if($user->getAuth('a_user_view_g') == 2 || $user->getAuth('a_user_view_g') == 4 || $user->getAuth('a_user_view_g') == 5)
			echo '<th>'.$lang->CONTROL.'</th>';
		?>
	</tr>
	<?php 
		$query = $db->query($pagging[4]);
		$counter = 1;
		while($result = $db->fetch($query)) {
			$group_id = $result['ID'];
			$new_query = $db->query("SELECT ID FROM cms_user WHERE GroupID='".$group_id."' AND status='N'");
			$rows = $db->rows($new_query);
				if($counter&1) {
					echo '<tr class="odd">';
				} else {
					echo '<tr class="even">';
				}
				$description = substr($result['description'],0,80);
				if(strlen($description) >= 50) {
					$description .= "...";
				}
				?>
					<td><?php echo $result['title'];?></td>
					<td><div class="sumo2-tooltip" title="<?php echo $result['description']; ?>"><?php echo $description;?></div></td>
					<td width="130px"><?php echo $rows;?></td>
					<td width="120px"><?php echo date($lang->DATE_1, strtotime($result['creation']));?></td>
                    <?php if($user->getAuth('a_user_view_g') == 2 || $user->getAuth('a_user_view_g') == 4 || $user->getAuth('a_user_view_g') == 5) {?>
					<td width="85px">
						<div title="<?php echo $lang->USER_TOOLTIP_ENABLE_1?>" class="<?php echo $result['enabled']?"enable":"disable"; ?> sumo2-tooltip" onclick="sumo2.user.ChangeStatusGroup('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
						<div title="<?php echo $lang->USER_TOOLTIP_EDIT_1?>" class="edit sumo2-tooltip" onclick="sumo2.accordion.NewPanel('a_user_edit_group','id=<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
                         <div title="<?php echo $lang->MOD_41?>" class="view sumo2-tooltip" onclick="sumo2.accordion.NewPanel('a_user_group_vd','gid=<?php echo  $crypt->encrypt($result['ID'])?>','a_user_group_vd<?php echo $result['ID']?>','<?php echo $lang->MOD_179?> - <?php echo  $result['title'] ?>')"></div>
                    <?php if($result['ID']>3) { ?>
						<div title="<?php echo $lang->USER_TOOLTIP_DELETE_1?>" class="delete sumo2-tooltip" onclick="sumo2.user.DeleteGroup('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
						<?php } ?>
					</td>
                    <?php } ?>
				</tr>
				<?php 
				$counter++;
			}
			if($counter==1)
			echo '<tr><td colspan="6" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_76.'</b></td></td>';
	?>
</table>
<?php echo  pagging($accordion_id, $pagging); ?>
</div>