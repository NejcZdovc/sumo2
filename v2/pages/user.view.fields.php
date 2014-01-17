<?php require_once('../initialize.php'); 
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 	exit;
	}
	$accordion_id='a_user_view_f';
	$pagging=check_pagging("SELECT * FROM cms_user_fields WHERE status='N'", $user->items);
	$dropdown=dropdown_pagging($accordion_id, $pagging[0]);
	echo '<div class="flt-right display">'.$dropdown.'</div>';
?>
<div id="a_user_view_f_table" style="clear:both;">
<table cellpadding="0" cellspacing="1" border="0" summary="View fields" class="table1 table2" width="99%">
<caption class="hide">View fields</caption>
	<tr>
		<th scope="col" abbr=""><?php echo $lang->MOD_60?></th>
		<th scope="col" abbr=""><?php echo $lang->MOD_61?></th>
		<th scope="col" abbr=""><?php echo $lang->MOD_62?></th>
		<th scope="col" abbr=""><?php echo $lang->MOD_11?></th>
		<th scope="col" abbr=""><?php echo $lang->MOD_63?></th>
		<th scope="col" abbr=""><?php echo $lang->MOD_64?></th>
		<th scope="col" abbr=""><?php echo $lang->MOD_65?></th>
        <?php if($user->getAuth('FAV_USER_5') == 2 || $user->getAuth('FAV_USER_5') == 4 || $user->getAuth('FAV_USER_5') == 5)
			echo '<th scope="col" abbr="'.$lang->CONTROL.'">'.$lang->CONTROL.'</th>';
		?>
	</tr>
	<?php
		$query = $db->query($pagging[4]);
		$counter = 1;
		while($result = $db->fetch($query)) {
				if($counter&1) {
					echo '<tr class="odd">';
				} else {
					echo '<tr class="even">';
				}
				?>
					<td><?php echo $result['labelName'];?></td>
					<td><?php echo $result['name'];?></td>
					<td><?php echo $result['fieldId'];?></td>
					<?php
					switch($result['type']) {
						case 1:
							echo "<td>".$lang->MOD_66."</td>";
							break;
						case 2:
							echo "<td>".$lang->MOD_67."</td>";
							break;
						case 3:
							echo "<td>".$lang->MOD_68."</td>";
							break;
						case 4:
							echo "<td>".$lang->MOD_69."</td>";
							break;
						case 5:
							echo "<td>".$lang->MOD_70."</td>";
							break;
						case 6:
							echo "<td>".$lang->MOD_71."</td>";
							break;
						case 7:
							echo "<td>".$lang->MOD_72."</td>";
							break;
						default:
							echo "<td>".$lang->MOD_73."</td>";
							break;
					}
					?>
					<td><?php echo $result['required']?('Yes'):('No');?></td>
					<td><?php echo $result['min'];?></td>
					<td><?php echo $result['max'];?></td>
                    <?php if($user->getAuth('FAV_USER_5') == 2 || $user->getAuth('FAV_USER_5') == 4 || $user->getAuth('FAV_USER_5') == 5) {?>
					<td width="65px">
						<div title="<?php echo $lang->MOD_4?>" class="<?php echo $result['enabled']?"enable":"disable"; ?> sumo2-tooltip" onclick="sumo2.user.ChangeStatusField('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
						<div title="<?php echo $lang->MOD_5?>" class="edit sumo2-tooltip" onclick="sumo2.user.EditFieldShow('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
						<div title="<?php echo $lang->MOD_6?>" class="delete sumo2-tooltip" onclick="sumo2.user.DeleteField('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
					</td>
                    <?php } ?>
				</tr>
				<?php 
				$counter++;
			}
		if($counter==1)
			echo '<tr><td colspan="8" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_74.'</b></td></td>';
	?>
</table>
<?php echo  pagging($accordion_id, $pagging); ?>
</div>