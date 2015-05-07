<?php require_once('../initialize.php'); 
	$security->checkFull();
	$id_cat=$crypt->decrypt($db->filter('gida'));
	$accordion_id='a_article_view_cd'.$id_cat;
	$pagging=check_pagging("SELECT ID,title,category,dateStart,dateEnd,author,lang,date, published FROM cms_article WHERE status='N' AND category LIKE '%#??#".$id_cat."#??#%' order by title asc", $user->items);
	$dropdown=dropdown_pagging($accordion_id, $pagging[0]);
	echo '<div class="flt-right display">'.$dropdown.'</div>';
?>
<div>
      <div style="margin-left:10px; float:left; margin-top:10px; font-weight:bold;"><?php echo $lang->MOD_44?></div>
      <input id="a_article_view_cd_table_id_search" name="search" class="input" style="width:200px; margin-left:10px;" value="" type="text" maxlength="50" />
</div>
<table cellpadding="0" cellspacing="1" border="0" class="table1 table2" id="viewgroups_detailed" width="99%">
	<thead>
	<tr>
    	<th><?php echo $lang->MOD_40?></th>
		<th><?php echo $lang->TITLE?></th>
		<th><?php echo $lang->ARTICLE_5?></th>
        <th><?php echo $lang->ARTICLE_6?></th>
        <th><?php echo $lang->ARTICLE_7?></th>
		<th><?php echo $lang->CREATE_DATE?></th>
		<?php if($user->getAuth('a_article_view_cd') == 2 || $user->getAuth('a_article_view_cd') == 4 || $user->getAuth('a_article_view_cd') == 5)
			echo '<th>'.$lang->CONTROL.'</th>';
		?>
	</tr>
    </thead>
    <tbody>
	<?php 
		$query = $db->query($pagging[4]);
		$counter = 1;
		while($result = $db->fetch($query)) {
			$group_id = $result['ID'];
				if($counter&1) {
					echo '<tr class="odd">';
				} else {
					echo '<tr class="even">';
				}
				?>
                	<td width="30px;" style="text-align:center;"><img src="images/icons/flags/<?php echo lang_name_front($result['lang'])?>.png" alt="<?php echo lang_name_front($result['lang'])?>"/></td>
					<td><?php echo $result['title'];?></td>
					<td><?php echo $result['author'];?></td>
					<td><?php if($result['dateStart']==0) echo $lang->ARTICLE_11; else echo date($lang->DATE_1, $result['dateStart']);?></td>
                    <td><?php if($result['dateEnd']==0) echo $lang->ARTICLE_12; else  echo date($lang->DATE_1, $result['dateEnd']);?></td>
                    <td><?php echo  date($lang->DATE_1, $result['date']);?></td>
                     <?php if($user->getAuth('a_article_view_cd') == 2 || $user->getAuth('a_article_view_cd') == 4 || $user->getAuth('a_article_view_cd') == 5) {?>
					<td width="65px">
						<div title="<?php echo $lang->ARTICLE_9?>" class="<?php echo $result['published']?"enable":"disable"; ?> sumo2-tooltip" onclick="sumo2.article.ChangeStatusArticle('<?php echo $crypt->encrypt($result['ID']); ?>','<?php echo $accordion_id?>')"></div>
						<div title="<?php echo $lang->ARTICLE_8?>" class="edit sumo2-tooltip" onclick="sumo2.accordion.NewPanel('a_article_edit_a','id=<?php echo  $crypt->encrypt($result['ID']); ?>',null,'a_article_edit_a<?php echo $result['ID']?>','<?php echo $lang->MOD_43?> - <?php echo  str_replace("'", "", $result['title']);?>')"></div>
						<div title="<?php echo $lang->ARTICLE_10?>" class="delete sumo2-tooltip" onclick="sumo2.article.DeleteArticle('<?php echo $crypt->encrypt($result['ID']); ?>','<?php echo $accordion_id?>')"></div>
					</td>
                    <?php } ?>
				</tr>
				<?php 
				$counter++;
			}
			if($counter==1)
			echo '<tr><td colspan="7" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_10.'</b></td></td>';
	?>
    </tbody>
</table>
<?php echo  pagging($accordion_id, $pagging); ?>
