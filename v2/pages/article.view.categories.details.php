<?php require_once('../initialize.php'); 
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$id_cat=$crypt->decrypt($db->filter('gida'));
	$accordion_id='a_article_view_cd'.$id_cat;
	$pagging=check_pagging("SELECT ID,title,category,dateStart,dateEnd,author,lang,date, published FROM cms_article WHERE status='N' AND category LIKE '%#??#".$id_cat."#??#%' order by title asc", $user->items);
	$dropdown=dropdown_pagging($accordion_id, $pagging[0]);
	echo '<div class="flt-right display">'.$dropdown.'</div>';
?>
<div>
      <div style="margin-left:10px; float:left; margin-top:10px; font-weight:bold;"><?=$lang->MOD_44?></div>
      <input id="a_article_view_cd_table_id_search" name="search" class="input" style="width:200px; margin-left:10px;" value="" type="text" maxlength="50" />
</div>
<table cellpadding="0" cellspacing="1" border="0" class="table1 table2" id="viewgroups_detailed" width="99%">
	<thead>
	<tr>
    	<th><?=$lang->MOD_40?></th>
		<th><?=$lang->TITLE?></th>
		<th><?=$lang->ARTICLE_5?></th>
        <th><?=$lang->ARTICLE_6?></th>
        <th><?=$lang->ARTICLE_7?></th>
		<th><?=$lang->CREATE_DATE?></th>
		<? if($user->getAuth('FAV_ARTICLES_3') == 2 || $user->getAuth('FAV_ARTICLES_3') == 4 || $user->getAuth('FAV_ARTICLES_3') == 5)
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
                	<td width="30px;" style="text-align:center;"><img src="images/icons/flags/<?=lang_name_front($result['lang'])?>.png" alt="<?=lang_name_front($result['lang'])?>"/></td>
					<td><?php echo $result['title'];?></td>
					<td><?php echo $result['author'];?></td>
					<td><?php if($result['dateStart']==0) echo $lang->ARTICLE_11; else echo date($lang->DATE_1, $result['dateStart']);?></td>
                    <td><?php if($result['dateEnd']==0) echo $lang->ARTICLE_12; else  echo date($lang->DATE_1, $result['dateEnd']);?></td>
                    <td><?php echo  date($lang->DATE_1, $result['date']);?></td>
                     <? if($user->getAuth('FAV_ARTICLES_3') == 2 || $user->getAuth('FAV_ARTICLES_3') == 4 || $user->getAuth('FAV_ARTICLES_3') == 5) {?>
					<td width="65px">
						<div title="<?=$lang->ARTICLE_9?>" class="<?php echo $result['published']?"enable":"disable"; ?> sumo2-tooltip" onclick="sumo2.article.ChangeStatusArticle('<?php echo $crypt->encrypt($result['ID']); ?>','<?=$accordion_id?>')"></div>
						<div title="<?=$lang->ARTICLE_8?>" class="edit sumo2-tooltip" onclick="sumo2.accordion.NewPanel('a_article_edit_a','id=<?= $crypt->encrypt($result['ID']); ?>',null,'a_article_edit_a<?=$result['ID']?>','<?=$lang->MOD_43?> - <?= str_replace("'", "", $result['title']);?>')"></div>
						<div title="<?=$lang->ARTICLE_10?>" class="delete sumo2-tooltip" onclick="sumo2.article.DeleteArticle('<?php echo $crypt->encrypt($result['ID']); ?>','<?=$accordion_id?>')"></div>
					</td>
                    <? } ?>
				</tr>
				<?php 
				$counter++;
			}
			if($counter==1)
			echo '<tr><td colspan="7" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_10.'</b></td></td>';
	?>
    </tbody>
</table>
<?= pagging($accordion_id, $pagging); ?>
