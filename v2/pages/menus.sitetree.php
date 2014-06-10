<?php require_once('../initialize.php');
	$security->checkFull();
	$first=0;
	$second=0;
	$third=0;
	$fourth=0;
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
	$id_main=$crypt->decrypt($db->filter('id'));
	$main=$db->fetch($db->query('SELECT title FROM cms_menus WHERE ID='.$id_main.' AND status="N"'));
	//first level
	echo '<div id="-1#'.$id_main.'" class="M_N" style="width:auto; height:auto;"><ul id="menu-sitetree"><div id="-1#'.$id_main.'" >'.$main['title'].'</div>';
	$query=$db->query('SELECT ID, title, parentID, orderID, enabled, showM FROM cms_menus_items WHERE menuID='.$id_main.' AND parentID="-1" AND selection!="4" AND status="N" ORDER BY orderID asc');
	while($results=$db->fetch($query)){
		if($sel === $results['ID']) {
			$show = 'show';	
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
			echo '<li class="connected '.$show.'"><div class="M_RDNES" '.$ena.' id="'.$results['ID'].'#'.$id_main.'">'.$results['title'].' '.$smenu.'</div>';
		else if($first==1)
			echo '<li class="connected '.$show.'"><div class="M_RDNDES" '.$ena.' id="'.$results['ID'].'#'.$id_main.'">'.$results['title'].' '.$smenu.'</div>';
		else if($int1==$first)
			echo '<li class="connected '.$show.'"><div class="M_RDNUES" '.$ena.' id="'.$results['ID'].'#'.$id_main.'">'.$results['title'].' '.$smenu.'</div>';
		else
			echo '<li class="connected '.$show.'"><div class="M_RDNUDES" '.$ena.' id="'.$results['ID'].'#'.$id_main.'">'.$results['title'].' '.$smenu.'</div>';
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
					echo '<li '.$show.'><div class="M_RDNES" '.$ena.' id="'.$results2['ID'].'#'.$id_main.'">'.$results2['title'].' '.$smenu.'</div>';
				else if($second==1)
					echo '<li '.$show.'><div class="M_RDNDES" '.$ena.' id="'.$results2['ID'].'#'.$id_main.'">'.$results2['title'].' '.$smenu.'</div>';
				else if($int2==$second)
					echo '<li '.$show.'><div class="M_RDNUES" '.$ena.' id="'.$results2['ID'].'#'.$id_main.'">'.$results2['title'].' '.$smenu.'</div>';
				else
					echo '<li '.$show.'><div class="M_RDNUDES" '.$ena.' id="'.$results2['ID'].'#'.$id_main.'">'.$results2['title'].' '.$smenu.'</div>';
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
							echo '<li '.$show.'><div class="M_RDNES" '.$ena.' id="'.$results3['ID'].'#'.$id_main.'">'.$results3['title'].' '.$smenu.'</div>';
						else if($third==1)
							echo '<li '.$show.'><div class="M_RDNDES" '.$ena.' id="'.$results3['ID'].'#'.$id_main.'">'.$results3['title'].' '.$smenu.'</div>';
						else if($int3==$third)
							echo '<li '.$show.'><div class="M_RDNUES" '.$ena.' id="'.$results3['ID'].'#'.$id_main.'">'.$results3['title'].' '.$smenu.'</div>';
						else
							echo '<li '.$show.'><div class="M_RDNUDES" '.$ena.' id="'.$results3['ID'].'#'.$id_main.'">'.$results3['title'].' '.$smenu.'</div>';
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
											echo '<li '.$show.'><div class="M_RDES" '.$ena.' id="'.$results4['ID'].'#'.$id_main.'">'.$results4['title'].' '.$smenu.'</div>';
										else if($fourth==1)
											echo '<li '.$show.'><div class="M_RDDES" '.$ena.' id="'.$results4['ID'].'#'.$id_main.'">'.$results4['title'].' '.$smenu.'</div>';
										else if($int4==$fourth)
											echo '<li '.$show.'><div class="M_RDUES" '.$ena.' id="'.$results4['ID'].'#'.$id_main.'">'.$results4['title'].' '.$smenu.'</div>';
										else
											echo '<li '.$show.'><div class="M_RDUDES" '.$ena.' id="'.$results4['ID'].'#'.$id_main.'">'.$results4['title'].' '.$smenu.'</div>';
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
	echo "</ul></div>";
?>
<div style="margin-top:20px; width:100%; text-align:center;"><?php echo $lang->MENU_11?></div>
<!-- Rename, delete, new, down, enable, show -->
 <div class="contextMenu" id="myMenuM_RDNDES" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?php echo $lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?php echo $lang->MENU_5?></li>
        <li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?php echo $lang->MENU_28?></li>
        <li id="down"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1629px;"></div>&nbsp;<?php echo $lang->MENU_8?></li>
        <li id="enable"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-668px -1629px;"></div>&nbsp;<?php echo $lang->MENU_7?></li>
        <li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?php echo $lang->MOD_122?></li>
      </ul>
</div>
<!-- New -->
<div class="contextMenu" id="myMenuM_N" style="display:none;">
      <ul style="font-size:12px;">
        <li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?php echo $lang->MENU_28?></li>
      </ul>
</div>
<!-- Rename, delete, down, enable, show -->
<div class="contextMenu" id="myMenuM_RDDES" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?php echo $lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?php echo $lang->MENU_5?></li>
        <li id="down"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1629px;"></div>&nbsp;<?php echo $lang->MENU_8?></li>
        <li id="enable"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-668px -1629px;"></div>&nbsp;<?php echo $lang->MENU_7?></li>
        <li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?php echo $lang->MOD_122?></li>
      </ul>
</div>
<!-- Rename, delete, new, up, down, enable, show -->
 <div class="contextMenu" id="myMenuM_RDNUDES" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?php echo $lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?php echo $lang->MENU_5?></li>
        <li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?php echo $lang->MENU_28?></li>
        <li id="up"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1629px;"></div>&nbsp;<?php echo $lang->MENU_9?></li>
        <li id="down"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1629px;"></div>&nbsp;<?php echo $lang->MENU_8?></li>
        <li id="enable"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-668px -1629px;"></div>&nbsp;<?php echo $lang->MENU_7?></li>
        <li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?php echo $lang->MOD_122?></li>
      </ul>
</div>
<!-- Rename, delete, new, up, enable, show -->
 <div class="contextMenu" id="myMenuM_RDNUES" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?php echo $lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?php echo $lang->MENU_5?></li>
        <li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?php echo $lang->MENU_28?></li>
        <li id="up"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1629px;"></div>&nbsp;<?php echo $lang->MENU_9?></li>
        <li id="enable"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-668px -1629px;"></div>&nbsp;<?php echo $lang->MENU_7?></li>
        <li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?php echo $lang->MOD_122?></li>
      </ul>
</div>
<!-- Rename, delete, up, down, enable, show -->
<div class="contextMenu" id="myMenuM_RDUDES" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?php echo $lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?php echo $lang->MENU_5?></li>
        <li id="up"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1629px;"></div>&nbsp;<?php echo $lang->MENU_9?></li>
        <li id="down"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1629px;"></div>&nbsp;<?php echo $lang->MENU_8?></li>
        <li id="enable"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-668px -1629px;"></div>&nbsp;<?php echo $lang->MENU_7?></li>
        <li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?php echo $lang->MOD_122?></li>
      </ul>
</div>

<!-- Rename, delete, up, enable, show -->
<div class="contextMenu" id="myMenuM_RDUES" style="display:none;  width:auto;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?php echo $lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?php echo $lang->MENU_5?></li>
        <li id="up"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1629px;"></div>&nbsp;<?php echo $lang->MENU_9?></li>
        <li id="enable"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-668px -1629px;"></div>&nbsp;<?php echo $lang->MENU_7?></li>
        <li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?php echo $lang->MOD_122?></li>
      </ul>
</div>

<!-- Rename, delete, enable, show -->
<div class="contextMenu" id="myMenuM_RDES" style="display:none;  width:auto;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?php echo $lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?php echo $lang->MENU_5?></li>
        <li id="enable"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-668px -1629px;"></div>&nbsp;<?php echo $lang->MENU_7?></li>
        <li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?php echo $lang->MOD_122?></li>
      </ul>
</div>

<!-- Rename, delete, new, enable, show -->
<div class="contextMenu" id="myMenuM_RDNES" style="display:none;  width:auto;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?php echo $lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?php echo $lang->MENU_5?></li>
        <li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?php echo $lang->MENU_28?></li>
        <li id="enable"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-668px -1629px;"></div>&nbsp;<?php echo $lang->MENU_7?></li>
        <li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?php echo $lang->MOD_122?></li>
      </ul>
</div>