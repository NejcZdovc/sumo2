<? require_once('../initialize.php');
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	} 
	$id_main=$crypt->decrypt($db->filter('id'));
	$current=$crypt->decrypt($db->filter('current'));
	$items=$crypt->decrypt($db->filter('items'));
	$choos=$db->filter('choos');
	$parents=explode("#", $items);
	$int = count($parents)-1;
	if($choos==-666)
		$selected=$parents[0];
	else
		$selected=$choos;
	$first=0;
	$second=0;
	$third=0;

	if($db->is('sel')) {
		if(strpos($db->filter('sel'),'#') === false) {
			$sel = 	$db->filter('sel');
		} else {
			$temp = explode('#',$db->filter('sel'));
			$sel = $temp[0];
		}
	} else {
		$sel = false;
	}

	if($int>0) {
	$main=$db->fetch($db->query('SELECT title, lang FROM cms_menus WHERE ID='.$selected.''));
	if($int>1) {
		echo $lang->MENU_19.': <select id="items_lang">';
		for($i=0; $i<$int; $i++) {
			$select='';
			$temp=$db->fetch($db->query('SELECT ID, lang FROM cms_menus WHERE ID='.$parents[$i].''));
			
			if($parents[$i]==$selected)
				echo '<option style="font-weight:bold; background: url(images/icons/flags/'.lang_name_front($temp['lang']).'.png) no-repeat; padding-left:20px;" onclick="sumo2.dialog.ReloadDialog(\'d_menus_sitetree_trans\', \'id='.$id_main.'&current='.$current.'&items='.$items.'&choos='.$temp['ID'].'\');" selected="Selected">'.lang_name_front($temp['lang']).'</option>';
			else			
				echo '<option style="background: url(images/icons/flags/'.lang_name_front($temp['lang']).'.png) no-repeat; padding-left:20px;" onclick="sumo2.dialog.ReloadDialog(\'d_menus_sitetree_trans\', \'id='.$id_main.'&current='.$current.'&items='.$items.'&choos='.$temp['ID'].'\');" '.$select.'>'.lang_name_front($temp['lang']).'</option>';
		}
		
		echo '</select><br/>';
	}
	//first level
	echo '<div style="float:left; width:48%"><div style="width: 100%; height:auto; padding-top:5px;"><b>'.lang_name_front($main['lang']).' version:</b><br/><ul id="menu-sitetree-trans1"><div>'.$main['title'].'</div>';
	$query=$db->query('SELECT ID, title, parentID, orderID, keyword, description, template, enabled, showM FROM cms_menus_items WHERE menuID='.$selected.' AND parentID="0" AND status="N" ORDER BY orderID asc');
	while($results=$db->fetch($query)){
		if($sel === $results['ID']) {
			$show = 'show';	
		} else {
			$show = '';	
		}
		$query2=$db->query('SELECT ID, title, parentID, orderID, keyword, description, template, enabled, showM FROM cms_menus_items WHERE menuID='.$selected.' AND parentID="'.$results['ID'].'" AND status="N" ORDER BY orderID asc');
		$int=$db->rows($query2);
		if($results['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
		if($results['showM']=="Y") $smenu=''; else $smenu='<span style="color:#ff0000 !important;">*</span>';
		echo '<li class="connected '.$show.'"><div '.$ena.' title="Keywords: '.$results['keyword'].'<br/>Description: '.$results['description'].'<br/>Template: '.$results['template'].'" class="sumo2-tooltip">'.$results['title'].' '.$smenu.'</div>';
		if($int>0) {
			//second level
			echo '<ul>';
			while($results2=$db->fetch($query2)){
				if($sel === $results2['ID']) {
					$show = 'class="show"';	
				} else {
					$show = '';	
				}
				$query3=$db->query('SELECT ID, title, parentID, keyword, description, template, orderID, enabled, showM FROM cms_menus_items WHERE menuID='.$selected.' AND parentID="'.$results2['ID'].'" AND status="N" ORDER BY orderID asc');
				$int=$db->rows($query3);
				if($results2['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
				if($results2['showM']=="Y") $smenu=''; else $smenu='<span style="color:#ff0000 !important;">*</span>';
				echo '<li '.$show.'><div '.$ena.' title="Keywords: '.$results2['keyword'].'<br/>Description: '.$results2['description'].'<br/>Template: '.$results2['template'].'" class="sumo2-tooltip">'.$results2['title'].' '.$smenu.'</div>';
				if($int>0) {
					//third level
					echo '<ul>';
					while($results3=$db->fetch($query3)){
						if($sel === $results3['ID']) {
							$show = 'class="show"';	
						} else {
							$show = '';	
						}
						$query4=$db->query('SELECT ID, title, parentID, keyword, description, template, orderID, enabled, showM FROM cms_menus_items WHERE menuID='.$selected.' AND parentID="'.$results3['ID'].'" AND status="N" ORDER BY orderID asc');
						$int=$db->rows($query4);
						if($results3['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
						if($results3['showM']=="Y") $smenu=''; else $smenu='<span style="color:#ff0000 !important;">*</span>';
						echo '<li '.$show.'><div '.$ena.' title="Keywords: '.$results3['keyword'].'<br/>Description: '.$results3['description'].'<br/>Template: '.$results3['template'].'" class="sumo2-tooltip">'.$results3['title'].' '.$smenu.'</div>';
						if($int>0) {
							//fourth level
							echo '<ul>';
							while($results4=$db->fetch($query4)){
								if($sel === $results4['ID']) {
									$show = 'class="show"';	
								} else {
									$show = '';	
								}
								if($results4['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
								if($results4['showM']=="Y") $smenu=''; else $smenu='<span style="color:#ff0000 !important;">*</span>';
								echo '<li '.$show.'><div '.$ena.' title="Keywords: '.$results4['keyword'].'<br/>Description: '.$results4['description'].'<br/>Template: '.$results4['template'].'" class="sumo2-tooltip">'.$results4['title'].' '.$smenu.'</div></li>';
							}
							echo "</ul>";
						}
						echo "</li>";
					}
					echo "</ul>";
				}
				echo "</li>";
			}	
			echo "</ul>";
		}
		echo "</li>";		
	}
	echo "</ul></div></div>";
	}
	echo '<div style="float:left; width:48%;">';
	$main=$db->fetch($db->query('SELECT title FROM cms_menus WHERE ID='.$id_main.' AND status="N"'));
	//first level
	echo '<div id="0#'.$id_main.'" class="T_N" style="width:100%; height:auto; padding-top:5px;"><b>'.lang_name_front($current).' version:</b><br/><ul id="menu-sitetree-trans2"><div id="0#'.$id_main.'" >'.$main['title'].'</div>';
	$query=$db->query('SELECT ID, title, parentID, orderID, enabled, showM FROM cms_menus_items WHERE menuID='.$id_main.' AND parentID="0" AND status="N" ORDER BY orderID asc');
		while($results=$db->fetch($query)){
		if($sel === $results['ID']) {
			$show = 'class="show"';	
		} else {
			$show = '';	
		}
		$query2=$db->query('SELECT ID, title, parentID, orderID, enabled, showM FROM cms_menus_items WHERE menuID='.$id_main.' AND parentID="'.$results['ID'].'" AND status="N" ORDER BY orderID asc');
		$int=$db->rows($query2);
		$int1=$db->rows($query);
		$first++;
		if($results['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
		if($results['showM']=="Y") $smenu=''; else $smenu='<span style="color:#ff0000 !important;">*</span>';
		if($int1==1)
			echo '<li '.$show.'><div class="T_RDNES" '.$ena.' id="'.$results['ID'].'#'.$id_main.'">'.$results['title'].' '.$smenu.'</div>';
		else if($first==1)
			echo '<li '.$show.'><div class="T_RDNDES" '.$ena.' id="'.$results['ID'].'#'.$id_main.'">'.$results['title'].' '.$smenu.'</div>';
		else if($int1==$first)
			echo '<li '.$show.'><div class="T_RDNUES" '.$ena.' id="'.$results['ID'].'#'.$id_main.'">'.$results['title'].' '.$smenu.'</div>';
		else
			echo '<li '.$show.'><div class="T_RDNUDES" '.$ena.' id="'.$results['ID'].'#'.$id_main.'">'.$results['title'].' '.$smenu.'</div>';
		if($int>0) {
			//second level
			$second=0;
			echo '<ul>';
			while($results2=$db->fetch($query2)){
				if($sel === $results2['ID']) {
					$show = 'class="show"';	
				} else {
					$show = '';	
				}
				$query3=$db->query('SELECT ID, title, parentID, orderID, enabled, showM FROM cms_menus_items WHERE menuID='.$id_main.' AND parentID="'.$results2['ID'].'" AND status="N" ORDER BY orderID asc');
				$int=$db->rows($query3);
				$int2=$db->rows($query2);
				$second++;
				if($results2['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
				if($results2['showM']=="Y") $smenu=''; else $smenu='<span style="color:#ff0000 !important;">*</span>';
				if($int2==1)
					echo '<li '.$show.'><div class="T_RDNES" '.$ena.' id="'.$results2['ID'].'#'.$id_main.'">'.$results2['title'].' '.$smenu.'</div>';
				else if($second==1)
					echo '<li '.$show.'><div class="T_RDNDES" '.$ena.' id="'.$results2['ID'].'#'.$id_main.'">'.$results2['title'].' '.$smenu.'</div>';
				else if($int2==$second)
					echo '<li '.$show.'><div class="T_RDNUES" '.$ena.' id="'.$results2['ID'].'#'.$id_main.'">'.$results2['title'].' '.$smenu.'</div>';
				else
					echo '<li '.$show.'><div class="T_RDNUDES" '.$ena.' id="'.$results2['ID'].'#'.$id_main.'">'.$results2['title'].' '.$smenu.'</div>';
				if($int>0) {
				//third level
					echo '<ul>';
					$third=0;
					while($results3=$db->fetch($query3)){
						if($sel === $results3['ID']) {
							$show = 'class="show"';	
						} else {
							$show = '';	
						}
						$query4=$db->query('SELECT ID, title, parentID, orderID, enabled, showM FROM cms_menus_items WHERE menuID='.$id_main.' AND parentID="'.$results3['ID'].'" AND status="N" ORDER BY orderID asc');
						$int=$db->rows($query4);
						$int3=$db->rows($query3);
						$third++;
						if($results3['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
						if($results3['showM']=="Y") $smenu=''; else $smenu='<span style="color:#ff0000 !important;">*</span>';
						if($int3==1)
							echo '<li '.$show.'><div class="T_RDNES" '.$ena.' id="'.$results3['ID'].'#'.$id_main.'">'.$results3['title'].' '.$smenu.'</div>';
						else if($third==1)
							echo '<li '.$show.'><div class="T_RDNDES" '.$ena.' id="'.$results3['ID'].'#'.$id_main.'">'.$results3['title'].' '.$smenu.'</div>';
						else if($int3==$third)
							echo '<li '.$show.'><div class="T_RDNUES" '.$ena.' id="'.$results3['ID'].'#'.$id_main.'">'.$results3['title'].' '.$smenu.'</div>';
						else
							echo '<li '.$show.'><div class="T_RDNUDES" '.$ena.' id="'.$results3['ID'].'#'.$id_main.'">'.$results3['title'].' '.$smenu.'</div>';
							if($int>0) {
								//fourth level
								$fourth=0;
								echo '<ul>';
								while($results4=$db->fetch($query4)){
										$int4=$db->rows($query4);
										$fourth++;
										if($sel === $results4['ID']) {
											$show = 'class="show"';	
										} else {
											$show = '';	
										}
										if($results4['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
										if($results4['showM']=="Y") $smenu=''; else $smenu='<span style="color:#ff0000 !important;">*</span>';
										if($int4==1)
											echo '<li '.$show.'><div class="T_RDES" '.$ena.' id="'.$results4['ID'].'#'.$id_main.'">'.$results4['title'].' '.$smenu.'</div>';
										else if($fourth==1)
											echo '<li '.$show.'><div class="T_RDDES" '.$ena.' id="'.$results4['ID'].'#'.$id_main.'">'.$results4['title'].' '.$smenu.'</div>';
										else if($int4==$fourth)
											echo '<li '.$show.'><div class="T_RDUES" '.$ena.' id="'.$results4['ID'].'#'.$id_main.'">'.$results4['title'].' '.$smenu.'</div>';
										else
											echo '<li '.$show.'><div class="T_RDUDES" '.$ena.' id="'.$results4['ID'].'#'.$id_main.'">'.$results4['title'].' '.$smenu.'</div>';
								}
								echo "</ul>";
							}
					echo "</li>";
				}	
				echo "</ul>";
		}
				echo "</li>";
			}
			echo "</ul>";
		}
		echo "</li>";		
	}
	echo "</ul></div></div>";
?>
<div style="clear:both"></div>
<div style="margin-top:40px; width:100%; text-align:center;"><?=$lang->MENU_10?></div>
<!-- Rename, delete, new, down, enable, show -->
 <div class="contextMenu" id="myMenuT_RDNDES" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?=$lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?=$lang->MENU_5?></li>
        <li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?=$lang->MENU_28?></li>
        <li id="down"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1629px;"></div>&nbsp;<?=$lang->MENU_8?></li>
        <li id="enable"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-668px -1629px;"></div>&nbsp;<?=$lang->MENU_7?></li>
        <li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?=$lang->MOD_122?></li>
      </ul>
</div>
<!-- New -->
<div class="contextMenu" id="myMenuT_N" style="display:none;">
      <ul style="font-size:12px;">
        <li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?=$lang->MENU_28?></li>
      </ul>
</div>
<!-- Rename, delete, down, enable, show -->
<div class="contextMenu" id="myMenuT_RDDES" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?=$lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?=$lang->MENU_5?></li>
        <li id="down"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1629px;"></div>&nbsp;<?=$lang->MENU_8?></li>
        <li id="enable"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-668px -1629px;"></div>&nbsp;<?=$lang->MENU_7?></li>
        <li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?=$lang->MOD_122?></li>
      </ul>
</div>
<!-- Rename, delete, new, up, down, enable, show -->
 <div class="contextMenu" id="myMenuT_RDNUDES" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?=$lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?=$lang->MENU_5?></li>
        <li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?=$lang->MENU_28?></li>
        <li id="up"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1629px;"></div>&nbsp;<?=$lang->MENU_9?></li>
        <li id="down"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1629px;"></div>&nbsp;<?=$lang->MENU_8?></li>
        <li id="enable"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-668px -1629px;"></div>&nbsp;<?=$lang->MENU_7?></li>
        <li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?=$lang->MOD_122?></li>
      </ul>
</div>
<!-- Rename, delete, new, up, enable, show -->
 <div class="contextMenu" id="myMenuT_RDNUES" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?=$lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?=$lang->MENU_5?></li>
        <li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?=$lang->MENU_28?></li>
        <li id="up"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1629px;"></div>&nbsp;<?=$lang->MENU_9?></li>
        <li id="enable"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-668px -1629px;"></div>&nbsp;<?=$lang->MENU_7?></li>
        <li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?=$lang->MOD_122?></li>
      </ul>
</div>
<!-- Rename, delete, up, down, enable, show -->
<div class="contextMenu" id="myMenuT_RDUDES" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?=$lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?=$lang->MENU_5?></li>
        <li id="up"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1629px;"></div>&nbsp;<?=$lang->MENU_9?></li>
        <li id="down"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1629px;"></div>&nbsp;<?=$lang->MENU_8?></li>
        <li id="enable"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-668px -1629px;"></div>&nbsp;<?=$lang->MENU_7?></li>
        <li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?=$lang->MOD_122?></li>
      </ul>
</div>

<!-- Rename, delete, up, enable, show -->
<div class="contextMenu" id="myMenuT_RDUES" style="display:none;  width:auto;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?=$lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?=$lang->MENU_5?></li>
        <li id="up"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1629px;"></div>&nbsp;<?=$lang->MENU_9?></li>
        <li id="enable"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-668px -1629px;"></div>&nbsp;<?=$lang->MENU_7?></li>
        <li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?=$lang->MOD_122?></li>
      </ul>
</div>

<!-- Rename, delete, enable, show -->
<div class="contextMenu" id="myMenuT_RDES" style="display:none;  width:auto;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?=$lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?=$lang->MENU_5?></li>
        <li id="enable"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-668px -1629px;"></div>&nbsp;<?=$lang->MENU_7?></li>
        <li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?=$lang->MOD_122?></li>
      </ul>
</div>

<!-- Rename, delete, new, enable, show -->
<div class="contextMenu" id="myMenuT_RDNES" style="display:none;  width:auto;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?=$lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?=$lang->MENU_5?></li>
        <li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?=$lang->MENU_28?></li>
        <li id="enable"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-668px -1629px;"></div>&nbsp;<?=$lang->MENU_7?></li>
        <li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?=$lang->MOD_122?></li>
      </ul>
</div>
