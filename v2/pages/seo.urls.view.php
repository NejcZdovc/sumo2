<?php require_once('../initialize.php'); 
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$accordion_id='a_seo_redirect_view';
		
	$pagging=check_pagging("SELECT ID,source,destination,type FROM cms_seo_redirects WHERE lang='".$user->translate_lang."' AND domainID='".$user->domain."'order by ID asc", $user->items);
	
	$dropdown=dropdown_pagging($accordion_id, $pagging[0]);
	echo '<div class="flt-right display">'.$dropdown.'</div>';	
?>
<div>
      <div style="margin-left:10px; float:left; margin-top:10px; font-weight:bold;"><?php echo $lang->MOD_44?></div>
      <input id="a_seo_redirect_view_table_id_search" name="search" class="input" style="width:200px; margin-left:10px;" value="" type="text" maxlength="50" />
</div>
<table cellpadding="0" cellspacing="1" border="0" class="table1 table2" id="a_seo_redirect_view_table" width="99%">
	<thead>
    <tr>
    	<th><?php echo $lang->MOD_233?></th>
		<th><?php echo $lang->MOD_235?></th>
		<th><?php echo $lang->MOD_237?></th>
		<?php if($user->getAuth('FAV_SITE_8') == 2 || $user->getAuth('FAV_SITE_8') == 4 || $user->getAuth('FAV_SITE_8') == 5)
			echo '<th>'.$lang->CONTROL.'</th>';
		?>
	</tr>
    </thead>
    <tbody>
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
				<td><?php echo $result['source'];?></td>
                <td><?php echo $result['destination'];?></td>
                <td><?php echo $result['type'];?></td>
				<?php if($user->getAuth('FAV_SITE_8') == 2 || $user->getAuth('FAV_SITE_8') == 4 || $user->getAuth('FAV_SITE_8') == 5) { ?>
				<td width="65px">
					<div title="<?php echo $lang->MOD_240?>" class="edit sumo2-tooltip" onclick="sumo2.dialog.NewDialog('d_seo_redirect_edit', 'id=<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
					<div title="<?php echo $lang->MOD_241?>" class="delete sumo2-tooltip" onclick="sumo2.seo.deleteRedirect('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
				</td>
				<?php } ?>
			</tr>
			<?php 
			$counter++;
		}
		if($counter==1)
			echo '<tr><td colspan="10" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_239.'</b></td></td>';
	?>
    </tbody>
</table>
<?php echo  pagging($accordion_id, $pagging); ?>