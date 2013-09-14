<? require_once('../initialize.php'); 
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}

	echo '<ul id="tree-menu">';
	$main=$db->query('SELECT ID, title FROM cms_menus WHERE status="N" AND lang="'.$user->translate_lang.'"');
	while($mainresults=$db->fetch($main)){
		$id_main=$mainresults['ID'];
		//base level
		echo '<li><div id="-1#'.$id_main.'" style="cursor:pointer;">'.$mainresults['title'].'</div>';
		$query=$db->query('SELECT ID, title, parentID, orderID, enabled, template FROM cms_menus_items WHERE menuID='.$id_main.' AND parentID="-1" AND status="N" AND selection!="4"');
		$int=$db->rows($query);
		if($int>0) {
			//first level
			echo '<ul>';
			while($results=$db->fetch($query)){
				$query2=$db->query('SELECT ID, title, parentID, orderID, enabled, template FROM cms_menus_items WHERE menuID='.$id_main.' AND parentID="'.$results['ID'].'" AND status="N"');
				$int=$db->rows($query2);
				if($results['enabled']==1) $ena='style="color:#000;"'; else $ena='style="color:#ff0000;"';
				echo '<li><div '.$ena.' id="'.$results['ID'].'#'.$id_main.'" onclick="sumo2.siteTree.ReloadLayout('.$results['ID'].','.$user->translate_lang.','.$results['template'].')">'.$results['title'].'</div>';
				if($int>0) {
					//second level
					echo '<ul>';
					while($results2=$db->fetch($query2)){
						$query3=$db->query('SELECT ID, title, parentID, orderID, enabled, template FROM cms_menus_items WHERE menuID='.$id_main.' AND parentID="'.$results2['ID'].'" AND status="N"');
						$int=$db->rows($query3);
						if($results2['enabled']==1) $ena='style="color:#000;"'; else $ena='style="color:#ff0000;"';
						echo '<li><div '.$ena.' id="'.$results2['ID'].'#'.$id_main.'" onclick="sumo2.siteTree.ReloadLayout('.$results2['ID'].','.$user->translate_lang.','.$results2['template'].')">'.$results2['title'].'</div>';
						if($int>0) {
							//third level
							echo '<ul>';
							while($results3=$db->fetch($query3)){
									if($results3['enabled']==1) $ena='style="color:#000;"'; else $ena='style="color:#ff0000;"';
									echo '<li><div '.$ena.' id="'.$results3['ID'].'#'.$id_main.'" onclick="sumo2.siteTree.ReloadLayout('.$results3['ID'].','.$user->translate_lang.','.$results3['template'].')">'.$results3['title'].'</div></li>';
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
	echo "</ul>";
?>