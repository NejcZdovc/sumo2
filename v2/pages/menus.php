<?php 
	require_once('../initialize.php');
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 exit;
	}
	$accordion_id='a_menus';
	$selected_lang_menus=1;
	if($db->is('lang_menus'))
		$selected_lang_menus=$db->filter('lang_menus');
	else
		$selected_lang_menus=$user->translate_lang;
	$pagging=check_pagging("SELECT ID,title,description,status,date,enabled,lang,parent,s_default FROM cms_menus WHERE status='N' AND domain='".$user->domain."' AND lang='".$selected_lang_menus."'", $user->items);
	$dropdown=dropdown_pagging($accordion_id, $pagging[0]);
	echo '<div class="flt-right display">'.$dropdown.'</div>';
	if($user->translate_state=="ON") {
		$dialog='d_menus_sitetree_trans';
		echo '<div class="flt-right display">Language '.lang_dropdown(''.$selected_lang_menus.'', ''.$accordion_id.'', 'lang_menus').'</div>';
	}
	else
		$dialog='d_menus_sitetree';

?>
<form id="">
<input id="selected_lang_menu_id" value="<?php echo $selected_lang_menus?>" type="hidden"/>
</form>
<div id="a_menus_table" style="clear:both;">
<table cellpadding="0" cellspacing="1" border="0" class="table1 table2" id="viewgroups" width="99%">
	<tr>
    <th></th>
    	<th><?php echo $lang->MOD_40?></th>
		<th><?php echo $lang->TITLE?></th>
		<th><?php echo $lang->USER_ADD_D_1?></th>
		<th><?php echo $lang->MENU_12?></th>
		<th><?php echo $lang->CREATE_DATE?></th>
        
        <?php if($user->getAuth('FAV_SITE_5') == 2 || $user->getAuth('FAV_SITE_5') == 4 || $user->getAuth('FAV_SITE_5') == 5)
			echo '<th>'.$lang->CONTROL.'</th>';
		?>
        <?php if($user->translate_state=="ON")
        	echo '<th style="text-align:center;">'.$lang->MENU_18.'</th>';
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
				$language=$db->get($db->query('SELECT ID, lang, parent,status FROM cms_menus WHERE ID='.$end.' AND status="N"'));
				$array[$count]=$language['lang'];
				if($count>0 && $language['status']=="N")
					$items.=$language['ID']."#";
				$count++;
				$end=$language['parent'];
			}while($end!=0);
			$end=$result['ID'];
			do{
				$language=$db->get($db->query('SELECT ID, lang, parent FROM cms_menus WHERE parent='.$end.' AND status="N"'));
				$array[$count]=$language['lang'];
				$count++;
				$end=$language['ID'];
			}while($end!=0);
			
			$group_id = $result['ID'];
			$items_query = $db->query("SELECT ID FROM cms_menus_items WHERE menuID='".$group_id."' AND status='N'");
			$rows = $db->rows($items_query);
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
                	<td width="17px" style="cursor:pointer;" onclick="sumo2.menu.Dialog_m('<?php echo  $crypt->encrypt($result['ID']); ?>')"><?php if($result['s_default']==1) echo '<div style="height:16px;width:16px;background:url(/v2/images/css_sprite.png); background-position:-669px -1709px; cursor:pointer;"></div>';?></td>
                	<td width="30px;" style="text-align:center;"><img src="images/icons/flags/<?php echo lang_name_front($result['lang'])?>.png" alt="<?php echo lang_name_front($result['lang'])?>"/></td>
					<td><?php echo $result['title'];?></td>
					<td><div class="sumo2-tooltip" title="<?php echo $result['description']; ?>"><?php echo $description;?></div></td>
					<td width="200px;"><?php echo $rows?></td>
					<td><?php echo date($lang->DATE_1, strtotime($result['date']));?></td>
                    <?php if($user->getAuth('FAV_SITE_5') == 2 || $user->getAuth('FAV_SITE_5') == 4 || $user->getAuth('FAV_SITE_5') == 5) {?>
					<td width="100px">
						<div title="<?php echo $lang->MENU_13?>" class="<?php echo $result['enabled']?"enable":"disable"; ?> sumo2-tooltip" onclick="sumo2.menu.Status('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
                        <div title="<?php echo $lang->MENU_14?>" class="add sumo2-tooltip" onclick="sumo2.dialog.NewDialog('<?php echo $dialog?>','id=<?php echo  $crypt->encrypt($result['ID']); ?>$!$items=<?php echo  $crypt->encrypt($items) ?>$!$choos=-666$!$current=<?php echo $crypt->encrypt($selected_lang_menus)?>');"></div>
						<div title="<?php echo $lang->MENU_15?>" class="edit sumo2-tooltip" onclick="sumo2.dialog.NewDialog('d_menus_edit_m','id=<?php echo  $crypt->encrypt($result['ID']); ?>');"></div>
						<div title="<?php echo $lang->MENU_16?>" class="delete sumo2-tooltip" onclick="sumo2.menu.DeleteMenu('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
					</td>
                    <?php } if($user->translate_state=="ON") { ?>
                    <td width="150px" style="text-align:center;">
                    <select id="lang_trans">
                    <option value="0" >--- <?php echo $lang->MENU_17?> ---</option>
                   <?php
				   	$query1=$db->query("SELECT ID,name,short FROM cms_language_front WHERE enabled='1'");
				  	while($result1 = $db->fetch($query1)) {
						if($selected_lang_menus != $result1['ID']) {
							$language=$db->query('SELECT lang FROM cms_menus WHERE parent='.$result['ID'].' AND lang='.$result1['ID'].'');
							if(in_array($result1['ID'], $array, true) || $db->rows($language)>0)
								echo '<option value="'.$result1['ID'].'" disabled="disabled" class="select_lang_option" style="background-image: url(images/icons/flags/'.lang_name_front($result1['ID']).'.png), url(images/icons/small/tick.png); background-position: top left, top right;" >'.$result1['name'].'</option>';
							else
								echo '<option value="'.$result1['ID'].'" onclick="sumo2.dialog.NewDialog(\'d_menues_trans\', \'lang='.$crypt->encrypt($result1['ID']).'$!$menu='.$crypt->encrypt($result['ID']).'$!$current='.$crypt->encrypt($selected_lang_menus).'\')" class="select_lang_option" style="background-image: url(images/icons/flags/'.lang_name_front($result1['ID']).'.png);" >'.$result1['name'].'</option>';
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
			echo '<tr><td colspan="8" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_8.'</b></td></td>';
	?>
</table>
<?php echo  pagging($accordion_id, $pagging); ?>
</div>
