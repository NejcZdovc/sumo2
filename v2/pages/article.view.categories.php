<?php require_once('../initialize.php'); 
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$accordion_id='a_article_view_c';
	$selected_lang_cat=1;
	if($db->is('lang_cat'))
		$selected_lang_cat=$db->filter('lang_cat');
	else
		$selected_lang_cat=$user->translate_lang;
	
	$pagging=check_pagging("SELECT ID,title,description,enabled,date,lang FROM cms_article_categories WHERE status='N' AND domain='".$user->domain."' AND lang='".$selected_lang_cat."'", $user->items);
	$dropdown=dropdown_pagging($accordion_id, $pagging[0]);
	echo '<div class="flt-right display">'.$dropdown.'</div>';
	if($user->translate_state=="ON") {
		echo '<div class="flt-right display">'.$lang->ARTICLE_3.' '.lang_dropdown(''.$selected_lang_cat.'', ''.$accordion_id.'', 'lang_cat').'</div>';
	}
?>
<div id="a_article_view_c_table" style="clear:both;">
<table cellpadding="0" cellspacing="1" border="0" class="table1 table2" id="viewgroups" width="99%">
	<tr>
    	<th><?php echo $lang->MOD_40?></th>
		<th><?php echo $lang->TITLE?></th>
		<th><?php echo $lang->USER_ADD_D_1?></th>
		<th><?php echo $lang->ARTICLE_1?></th>
		<th><?php echo $lang->CREATE_DATE?></th>
		<?php if($user->getAuth('FAV_ARTICLES_3') == 2 || $user->getAuth('FAV_ARTICLES_3') == 4 || $user->getAuth('FAV_ARTICLES_3') == 5)
			echo '<th>'.$lang->CONTROL.'</th>';
		?>
         <?php if($user->translate_state=="ON")
        	echo '<th style="text-align:center;">'.$lang->ARTICLE_2.'</th>';
		?>
	</tr>
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
				$language=$db->get($db->query('SELECT ID, lang, parent FROM cms_article_categories WHERE ID='.$end.' AND status="N"'));
				$array[$count]=$language['lang'];
				$count++;
				$end=$language['parent'];
			}while($end!=0);
			$end=$result['ID'];
			do{
				$language=$db->get($db->query('SELECT ID, lang, parent FROM cms_article_categories WHERE parent='.$end.' AND status="N"'));
				$array[$count]=$language['lang'];
				$count++;
				$end=$language['ID'];
			}while($end!=0);
			
			$group_id = $result['ID'];
			$new_query = $db->query("SELECT ID FROM cms_article WHERE category LIKE '%".$group_id."%' AND status='N'");
			$rows = $db->rows($new_query);
				if($counter&1) {
					echo '<tr class="odd">';
				} else {
					echo '<tr class="even">';
				}
				$description = substr($result['description'],0,80);
				if(strlen($description) >= 60) {
					$description .= "...";
				}
				?>
                	<td width="30px;" style="text-align:center;"><img src="images/icons/flags/<?php echo lang_name_front($result['lang'])?>.png" alt="<?php echo lang_name_front($result['lang'])?>"/></td>
					<td><?php echo $result['title'];?></td>
					<td><div class="sumo2-tooltip" title="<?php echo $result['description']; ?>"><?php echo $description;?></div></td>
					<td><?php echo $rows;?></td>
					<td><?php echo date($lang->DATE_1, strtotime($result['date']));?></td>
                     <?php if($user->getAuth('FAV_ARTICLES_3') == 2 || $user->getAuth('FAV_ARTICLES_3') == 4 || $user->getAuth('FAV_ARTICLES_3') == 5) {?>
					<td width="85px">
						<div title="<?php echo $lang->USER_TOOLTIP_ENABLE_1?>" class="<?php echo $result['enabled']?"enable":"disable"; ?> sumo2-tooltip" onclick="sumo2.article.ChangeStatusGroup('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
						<div title="<?php echo $lang->USER_TOOLTIP_EDIT_1?>" class="edit sumo2-tooltip" onclick="sumo2.dialog.NewDialog('d_article_edit_c','id=<?php echo  $crypt->encrypt($result['ID']); ?>');"></div>
						<div title="<?php echo $lang->USER_TOOLTIP_DELETE_1?>" class="delete sumo2-tooltip" onclick="sumo2.article.DeleteGroup('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
                        <div title="<?php echo $lang->MOD_41?>" class="view sumo2-tooltip" onclick="sumo2.accordion.NewPanel('a_article_view_cd','gida=<?php echo  $crypt->encrypt($result['ID'])?>','a_article_view_cd<?php echo $result['ID']?>','<?php echo $lang->MOD_42?> <?php echo  $result['title'] ?>')"></div>
					</td>
                    <?php } ?>
                    <?php if($user->translate_state=="ON") { ?>
                    <td width="150px" style="text-align:center;">
                    <select id="lang_trans">
                    <option value="0" >--- <?php echo $lang->ARTICLE_4?> ---</option>
                   <?
				   	$query1=$db->query("SELECT ID,name,short FROM cms_language_front WHERE enabled='1'");
				  	while($result1 = $db->fetch($query1)) {
						if($selected_lang_cat != $result1['ID']) {
							$language=$db->query('SELECT lang FROM cms_article_categories WHERE parent='.$result['ID'].' AND lang='.$result1['ID'].'');
							if(in_array($result1['ID'], $array, true) || $db->rows($language)>0)
								echo '<option value="'.$result1['ID'].'" disabled="disabled" class="select_lang_option" style="background-image: url(images/icons/flags/'.lang_name_front($result1['ID']).'.png), url(images/icons/small/tick.png); background-position: top left, top right;" >'.$result1['name'].'</option>';
							else
								echo '<option value="'.$result1['ID'].'" onclick="sumo2.dialog.NewDialog(\'d_article_c_translate\', \'lang='.$crypt->encrypt($result1['ID']).'$!$cat='.$crypt->encrypt($result['ID']).'$!$current='.$crypt->encrypt($selected_lang_cat).'\')" class="select_lang_option" style="background-image: url(images/icons/flags/'.lang_name_front($result1['ID']).'.png);" >'.$result1['name'].'</option>';
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
			echo '<tr><td colspan="7" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_9.'</b></td></td>';
	?>
</table>
<?php echo  pagging($accordion_id, $pagging); ?>
</div>