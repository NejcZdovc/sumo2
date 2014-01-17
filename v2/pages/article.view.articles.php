<?php require_once('../initialize.php'); 
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$accordion_id='a_article_view_a';
	$selected_lang_cat=1;
	if($db->is('lang_art'))
		$selected_lang_cat=$db->filter('lang_art');
	else
		$selected_lang_cat=$user->translate_lang;
	if($db->is('cat_id')) {
		if($db->filter('cat_id')!='-1')
			$selected_cat=" AND category LIKE '%#??#".$db->filter('cat_id')."#??#%'";
		else
			$selected_cat='';
	} else
		$selected_cat='';
		
	if($db->is('autor_id')) {
		if($db->filter('autor_id')!="-1")
			$selected_author=" AND author='".$db->filter('autor_id')."'";
		else
			$selected_author='';
	} else
		$selected_author='';
		
	$pagging=check_pagging("SELECT ID,title,category,dateStart,views,dateEnd,lang,author,date, published FROM cms_article WHERE status='N' AND lang='".$selected_lang_cat."' AND domain='".$user->domain."' ".$selected_cat." ".$selected_author." order by title asc", $user->items);
	$dropdown=dropdown_pagging($accordion_id, $pagging[0]);
	echo '<div class="flt-right display">'.$dropdown.'</div>';
	if($user->translate_state=="ON") {
		echo '<div class="flt-right display">'.$lang->ARTICLE_3.' '.lang_dropdown(''.$selected_lang_cat.'', ''.$accordion_id.'', 'lang_art').'</div>';
	}
	echo '<div class="flt-right display"><select onchange="sumo2.accordion.ReloadAccordion(\''.$accordion_id.'\', \'cat_id=\'+this.value);">
		  <option value="-1">'.$lang->MOD_185.'</option>';
	$catListQ=$db->query('SELECT ID, title FROM cms_article_categories WHERE status="N" AND enabled="1"');
	while($catListR=$db->fetch($catListQ)) {
		if($db->filter('cat_id')==$catListR['ID'])
			echo '<option value="'.$catListR['ID'].'" selected="selected" style="text-transform:none;">'.$catListR['title'].'</option>';
		else
			echo '<option value="'.$catListR['ID'].'" style="text-transform:none;">'.$catListR['title'].'</option>';
	}	
	echo '</select></div>';
	echo '<div class="flt-right display"><select onchange="sumo2.accordion.ReloadAccordion(\''.$accordion_id.'\', \'autor_id=\'+this.value);">
		  <option value="-1">'.$lang->MOD_186.'</option>';
	$catListQ=$db->query('SELECT DISTINCT author FROM cms_article WHERE status="N" '.$selected_cat.'');
	while($catListR=$db->fetch($catListQ)) {
		if($db->filter('autor_id')==$catListR['author'])
			echo '<option value="'.$catListR['author'].'" selected="selected" style="text-transform:none;">'.$catListR['author'].'</option>';
		else
			echo '<option value="'.$catListR['author'].'" style="text-transform:none;">'.$catListR['author'].'</option>';
	}	
	echo '</select></div>';
	echo '<div style="margin-right:10px; margin-top:5px; background: #E3E3E3; float:right; height:30px; width:30px;"><div title="'.$lang->MOD_36.'" class="counterAll sumo2-tooltip" onclick="sumo2.dialog.NewConfirmation(\''.$lang->MOD_33.'\',\''.$lang->MOD_34.'\',250,250,function() {sumo2.article.CounterAll()});"></div></div>';
?>
<div>
      <div style="margin-left:10px; float:left; margin-top:10px; font-weight:bold;"><?php echo $lang->MOD_44?></div>
      <input id="a_article_view_a_table_id_search" name="search" class="input" style="width:200px; margin-left:10px;" value="" type="text" maxlength="50" />
</div>
<div id="a_article_view_a_table_div" style="clear:both;">
<table cellpadding="0" cellspacing="1" border="0" class="table1 table2" id="a_article_view_a_table" width="99%">
	<thead>
    <tr>
    	<th><?php echo $lang->MOD_40?></th>
		<th><?php echo $lang->TITLE?></th>
		<th><?php echo $lang->ARTICLE_13?></th>
		<th><?php echo $lang->ARTICLE_5?></th>
        <th><?php echo $lang->ARTICLE_6?></th>
        <th><?php echo $lang->ARTICLE_7?></th>
		<th><?php echo $lang->CREATE_DATE?></th>
        <th width="65"><?php echo $lang->MOD_56?></th>
		<?php if($user->getAuth('FAV_ARTICLES') == 2 || $user->getAuth('FAV_ARTICLES') == 4 || $user->getAuth('FAV_ARTICLES') == 5)
			echo '<th>'.$lang->CONTROL.'</th>';
		?>
         <?php if($user->translate_state=="ON")
        	echo '<th style="text-align:center;">'.$lang->ARTICLE_2.'</th>';
		?>
	</tr>
    </thead>
    <tbody>
	<?php 
		$query = $db->query($pagging[4]);
		$counter = 1;
		while($result = $db->fetch($query)) {			
			//dobimo vse parente od prevodov
			$end=$result['ID'];
			$count=0;
			$items=NULL;
			$array = array();
			do{
				$language=$db->get($db->query('SELECT ID, lang, parent FROM cms_article WHERE ID='.$end.'  AND status="N"'));
				$array[$count]=$language['lang'];
				$count++;
				$end=$language['parent'];
			}while($end!=0);
			$end=$result['ID'];
			do{
				$language=$db->get($db->query('SELECT ID, lang, parent FROM cms_article WHERE parent='.$end.'  AND status="N"'));
				$array[$count]=$language['lang'];
				$count++;
				$end=$language['ID'];
			}while($end!=0);
			
			$group_id = $result['ID'];
			$catt=explode('#??#', $result['category']);
			$kategorija="";
			for($i=0; $i<count($catt)-1; $i++) {
				if($catt[$i] != "") {
					$new_query = $db->fetch($db->query("SELECT title FROM cms_article_categories WHERE ID='".$catt[$i]."'"));
					$kategorija.=$new_query['title'];
					if(count($catt)-1>1 && $i != count($catt)-2)
						$kategorija.=", ";
				}
			}
				if($counter&1) {
					echo '<tr class="odd">';
				} else {
					echo '<tr class="even">';
				}
				?>
                	<td width="30px;" style="text-align:center;"><img src="images/icons/flags/<?php echo lang_name_front($result['lang'])?>.png" alt="<?php echo lang_name_front($result['lang'])?>"/></td>
					<td class="title"><?php echo $result['title'];?></td>
					<td><?php echo $kategorija?></td>
					<td><?php echo $result['author'];?></td>
					<td><?php if($result['dateStart']==0) echo $lang->ARTICLE_11; else echo date($lang->DATE_1, $result['dateStart']);?></td>
                    <td><?php if($result['dateEnd']==0) echo $lang->ARTICLE_12; else  echo date($lang->DATE_1, $result['dateEnd']);?></td>
                    <td><?php echo  date($lang->DATE_1, $result['date']);?></td>
                    <td><div class="counter_num"><?php echo $result['views'];?></div><div title="<?php echo $lang->MOD_35?>" class="counter sumo2-tooltip" onclick="sumo2.dialog.NewConfirmation('<?php echo $lang->MOD_33?>','<?php echo $lang->MOD_32?>',250,250,function() {sumo2.article.Counter('<?php echo $crypt->encrypt($result['ID']); ?>')});"></div></td>
                    <?php if($user->getAuth('FAV_ARTICLES') == 2 || $user->getAuth('FAV_ARTICLES') == 4 || $user->getAuth('FAV_ARTICLES') == 5) { ?>
					<td width="65px">
						<div title="<?php echo $lang->ARTICLE_9?>" class="<?php echo $result['published']?"enable":"disable"; ?> sumo2-tooltip" onclick="sumo2.article.ChangeStatusArticle('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
						<div title="<?php echo $lang->ARTICLE_8?>" class="edit sumo2-tooltip" onclick="sumo2.accordion.NewPanel('a_article_edit_a','id=<?php echo  $crypt->encrypt($result['ID']); ?>','a_article_edit_a<?php echo $result['ID']?>','<?php echo $lang->MOD_43?> - <?php echo  str_replace("'", "", $result['title']);?>')"></div>
						<div title="<?php echo $lang->ARTICLE_10?>" class="delete sumo2-tooltip" onclick="sumo2.article.DeleteArticle('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
					</td>
                    <?php } if($user->translate_state=="ON") { ?>
                    <td width="150px" style="text-align:center;">
                    <select id="lang_trans">
                    <option value="0" >--- <?php echo $lang->ARTICLE_4?> ---</option>
                   <?
				   	$query1=$db->query("SELECT ID,name,short FROM cms_language_front WHERE enabled='1'");
				  	while($result1 = $db->fetch($query1)) {
						if($selected_lang_cat != $result1['ID']) {
							$language=$db->query('SELECT lang FROM cms_article WHERE parent='.$result['ID'].' AND lang='.$result1['ID'].'');
							if(in_array($result1['ID'], $array, true) || $db->rows($language)>0)
								echo '<option value="'.$result1['ID'].'" disabled="disabled" class="select_lang_option" style="background-image: url(images/icons/flags/'.lang_name_front($result1['ID']).'.png), url(images/icons/small/tick.png); background-position: top left, top right;" >'.$result1['name'].'</option>';
							else
								echo '<option value="'.$result1['ID'].'" onclick="sumo2.accordion.NewPanel(\'a_article_a_translate\', \'lang='.$crypt->encrypt($result1['ID']).'$!$article='.$crypt->encrypt($result['ID']).'$!$current='.$crypt->encrypt($selected_lang_cat).'\')" class="select_lang_option" style="background-image: url(images/icons/flags/'.lang_name_front($result1['ID']).'.png);" >'.$result1['name'].'</option>';
						}
                    }
					?>
                    </select>
                    </td>
                    <?php } ?>
				</tr>
				<?php 
				$counter++;
			}
			if($counter==1)
			echo '<tr><td colspan="10" id="noresults" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_10.'</b></td></td>';
	?>
    </tbody>
</table>
<?php echo  pagging($accordion_id, $pagging); ?>
</div>