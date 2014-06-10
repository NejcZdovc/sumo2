<?php 
	require_once('../initialize.php');
	$security->checkMin();
if (ob_get_length() > 0) { ob_end_clean(); }
if($db->is('type')) {
	//Recover
	if($db->filter('type') == 'articleR') {
		$id=$crypt->decrypt($db->filter('id'));
		$db->query('UPDATE cms_article SET status="N" WHERE ID='.$id.'');
		echo "ok";
		exit;
	} else if($db->filter('type') == 'articleGR') {
		$id=$crypt->decrypt($db->filter('id'));
		$db->query('UPDATE cms_article_categories SET status="N" WHERE ID='.$id.'');
		$articleq = $db->query('SELECT ID,category FROM cms_article WHERE category='.$id.'');
		while($articleF=$db->fetch($articleq)) {
			$db->query("UPDATE cms_article SET status='N' WHERE ID='".$articleF['ID']."'");	
		}
		echo "ok";
		exit;
	} else if($db->filter('type') == 'userR') {
		$id=$crypt->decrypt($db->filter('id'));
		$db->query('UPDATE cms_user SET status="N" WHERE ID='.$id.'');
		echo "ok";
		exit;
	} else if($db->filter('type') == 'userGR') {
		$id=$crypt->decrypt($db->filter('id'));
		$db->query('UPDATE cms_user_groups SET status="N" WHERE ID='.$id.'');
		$articleq = $db->query('SELECT ID FROM cms_user WHERE GroupID='.$id.'');
		while($articleF=$db->fetch($articleq)) {
			$db->query("UPDATE cms_user SET status='N' WHERE ID='".$articleF['ID']."'");	
		}
		echo "ok";
		exit;
	} else if($db->filter('type') == 'menuR') {
		$id=$crypt->decrypt($db->filter('id'));
		$db->query('UPDATE cms_menus SET status="N" WHERE ID='.$id.'');
		$articleq = $db->query('SELECT ID FROM cms_menus_items WHERE menuID='.$id.'');
		while($articleF=$db->fetch($articleq)) {
			$db->query("UPDATE cms_menus_items SET status='N' WHERE ID='".$articleF['ID']."'");	
		}
		echo "ok";
		exit;
	} else if($db->filter('type') == 'menuIR') {
		$id=$crypt->decrypt($db->filter('id'));
		$db->query('UPDATE cms_menus_items SET status="N" WHERE ID='.$id.'');
		echo "ok";
		exit;
	}
	else if($db->filter('type') == 'mailR') {
		$id=$crypt->decrypt($db->filter('id'));
		$db->query('UPDATE cms_mail_sent SET status="O" WHERE ID='.$id.'');
		echo "ok";
		exit;
	}
	else if($db->filter('type') == 'modulR') {
		$id=$crypt->decrypt($db->filter('id'));
		$db->query('UPDATE cms_modules_def SET status="N" WHERE ID='.$id.'');
		echo "ok";
		exit;
	}
	else if($db->filter('type') == 'comR') {
		$id=$crypt->decrypt($db->filter('id'));
		$db->query('UPDATE cms_components_def SET status="N" WHERE ID='.$id.'');
		$query=$db->query('SELECT ID FROM cms_favorites_def WHERE comID="'.$id.'"');
		while($result=$db->fetch($query)) {
			$db->query("UPDATE cms_favorites_def SET statusID='N' WHERE ID='".$result['ID']."'");		
		}
		echo "ok";
		exit;
	}
	else if($db->filter('type') == 'tempR') {
		$id=$crypt->decrypt($db->filter('id'));
		$db->query('UPDATE cms_template SET status="N" WHERE ID='.$id.'');
		echo "ok";
		exit;
	}
	
	//Delete
	else if($db->filter('type') == 'articleD') {
		$id=$crypt->decrypt($db->filter('id'));
		$db->query('DELETE FROM cms_article WHERE ID='.$id.'');
		echo "ok";
		exit;
	} else if($db->filter('type') == 'articleGD') {
		$id=$crypt->decrypt($db->filter('id'));
		$articleq = $db->query('SELECT ID,category FROM cms_article WHERE category='.$id.'');
		while($articleF=$db->fetch($articleq)) {
			$db->query("DELETE FROM cms_article WHERE ID='".$articleF['ID']."'");	
		}
		$db->query('DELETE FROM cms_article_categories WHERE ID='.$id.'');
		echo "ok";
		exit;
	} else if($db->filter('type') == 'userD') {
		$id=$crypt->decrypt($db->filter('id'));
		$db->query('DELETE FROM cms_user WHERE ID='.$id.'');
		$db->query('DELETE FROM cms_user_aditional WHERE userID='.$id.'');
		$db->query('DELETE FROM cms_user_settings WHERE userID='.$id.'');
		$db->query('DELETE FROM cms_state WHERE userID='.$id.'');
		$db->query('DELETE FROM cms_favorites WHERE UserID='.$id.'');
		echo "ok";
		exit;
	} else if($db->filter('type') == 'userGD') {
		$id=$crypt->decrypt($db->filter('id'));
		$articleq = $db->query('SELECT ID FROM cms_user WHERE GroupID='.$id.'');
		while($articleF=$db->fetch($articleq)) {
			$db->query('DELETE FROM cms_user WHERE ID='.$id.'');
			$db->query('DELETE FROM cms_user_aditional WHERE userID='.$id.'');
			$db->query('DELETE FROM cms_user_settings WHERE userID='.$id.'');
			$db->query('DELETE FROM cms_state WHERE userID='.$id.'');
			$db->query('DELETE FROM cms_favorites WHERE UserID='.$id.'');	
		}
		$db->query('DELETE FROM cms_user_groups WHERE ID='.$id.'');
		echo "ok";
		exit;
	} else if($db->filter('type') == 'menuD') {
		$id=$crypt->decrypt($db->filter('id'));
		$articleq = $db->query('SELECT ID FROM cms_menus_items WHERE menuID='.$id.'');
		while($articleF=$db->fetch($articleq)) {
			$template = $db->query('SELECT prefix FROM cms_template_position');
			while($templateR=$db->fetch($template)) {
				$panel = $db->query('SELECT ID, table_name FROM cms_panel_'.$templateR['prefix'].' WHERE pageID='.$articleF['ID'].'');
				while($panelR=$db->fetch($panel)) {
					$modul = $db->get($db->query('SELECT ID FROM '.$panelR['table_name'].' WHERE cms_panel_id='.$panelR['ID'].''));
					$db->query('DELETE FROM '.$panelR['table_name'].' WHERE ID="'.$modul['ID'].'"');	
					$db->query('DELETE FROM cms_panel_'.$templateR['prefix'].' WHERE ID="'.$panelR['ID'].'"');	
				}
			}
			$db->query("DELETE FROM cms_menus_items WHERE ID='".$articleF['ID']."'");	
		}
		$db->query('DELETE FROM cms_menus WHERE ID='.$id.'');
		echo "ok";
		exit;
	} else if($db->filter('type') == 'menuID') {
		$id=$crypt->decrypt($db->filter('id'));
		$template = $db->query('SELECT prefix FROM cms_template_position');
		while($templateR=$db->fetch($template)) {
			$panel = $db->query('SELECT ID, table_name FROM cms_panel_'.$templateR['prefix'].' WHERE pageID='.$id.'');
			while($panelR=$db->fetch($panel)) {
				$modul = $db->get($db->query('SELECT ID FROM '.$panelR['table_name'].' WHERE cms_panel_id='.$panelR['ID'].''));
				$db->query('DELETE FROM '.$panelR['table_name'].' WHERE ID="'.$modul['ID'].'"');	
				$db->query('DELETE FROM cms_panel_'.$templateR['prefix'].' WHERE ID="'.$panelR['ID'].'"');	
			}
		}
		$db->query('DELETE FROM cms_menus_items WHERE ID='.$id.'');
		echo "ok";
		exit;
	} else if($db->filter('type') == 'mailD') {
		$id=$crypt->decrypt($db->filter('id'));
		$main = $db->fetch($db->query('SELECT mainID FROM cms_mail_sent WHERE ID='.$id.''));
		$db->query('DELETE FROM cms_mail_sent WHERE ID='.$id.'');
		$left=$db->query('SELECT ID FROM cms_mail_sent WHERE mainID='.$main['mainID'].'');
		if($db->rows($left)==0) {
			$mainT = $db->fetch($db->query('SELECT status FROM cms_mail_main WHERE ID='.$main['mainID'].''));
			if($mainT['status']=="D")
				$db->query('DELETE FROM cms_mail_main WHERE ID='.$main['mainID'].'');
		}
		echo "ok";
		exit;
	} else if($db->filter('type') == 'modulD') {
		$id=$crypt->decrypt($db->filter('id'));
		$query=$db->fetch($db->query('SELECT ID, moduleName, editTable, tables FROM cms_modules_def WHERE ID="'.$id.'"'));
		
		//izbris datotek
		$q=$db->query('SELECT name FROM cms_domains');
		while($r=$db->fetch($q)) {
			if(is_dir('../../modules/'.$r['name'].'/'.$query['moduleName'])) {
				recursive_remove_directory('../../modules/'.$r['name'].'/'.$query['moduleName']);
				recursive_remove_directory('../modules/'.$query['moduleName']);
			}
		}
		
		//izbris iz system.xml
		if (ob_get_length() > 0) { ob_end_clean(); }
		header('Content-Type: text/xml;charset=UTF-8'); 			
		$xdoc = new DOMDocument('1.0', 'UTF-8');
		$xdoc->formatOutput = true;
		$xdoc->preserveWhiteSpace = false; 
		$xdoc->load('../modules/system.xml'); 
		
		$xmlDialog = $xdoc->getElementsByTagName('dialog')->item(0); 
		$newItems = $xmlDialog->getElementsByTagName('item');
		$length = $newItems->length;
		for ($i=$length-1;$i>=0;$i--) {
			$item = $newItems->item($i);
			if($item->getAttribute('deleteId')==$query['moduleName']."_".$query['ID']) {
				$parent = $item->parentNode; 
				$parent->removeChild($item);
			}
		}
		$xmlAccordion = $xdoc->getElementsByTagName('accordion')->item(0); 
		$newItems = $xmlAccordion->getElementsByTagName('item');
		$length = $newItems->length;
		for ($i=$length-1;$i>=0;$i--) {
			$item = $newItems->item($i);
			if($item->getAttribute('deleteId')==$query['moduleName']."_".$query['ID']) {
				$parent = $item->parentNode; 
				$parent->removeChild($item);
			}
		}
		$xdoc->save('../modules/system.xml');
		
		//izbris iz javascript.xml
		if (ob_get_length() > 0) { ob_end_clean(); }			
		$xdoc = new DOMDocument('1.0', 'UTF-8');
		$xdoc->formatOutput = true;
		$xdoc->preserveWhiteSpace = false; 
		$xdoc->load('../modules/javascript.xml'); 
		
		$xmlDialog = $xdoc->getElementsByTagName('javascript')->item(0); 
		$newItems = $xmlDialog->getElementsByTagName('item');
		$length = $newItems->length;
		for ($i=$length-1;$i>=0;$i--) {
			$item = $newItems->item($i);
			if($item->getAttribute('deleteId')==$query['moduleName']."_".$query['ID']) {
				$parent = $item->parentNode; 
				$parent->removeChild($item);
			}
		} 
		$xdoc->save('../modules/javascript.xml');
		
		//izbris iz css.xml
		if (ob_get_length() > 0) { ob_end_clean(); }			
		$xdoc = new DOMDocument('1.0', 'UTF-8');
		$xdoc->formatOutput = true;
		$xdoc->preserveWhiteSpace = false; 
		$xdoc->load('../modules/css.xml'); 
		
		$xmlDialog = $xdoc->getElementsByTagName('css')->item(0); 
		$newItems = $xmlDialog->getElementsByTagName('item');
		$length = $newItems->length;
		for ($i=$length-1;$i>=0;$i--) {
			$item = $newItems->item($i);
			if($item->getAttribute('deleteId')==$query['moduleName']."_".$query['ID']) {
				$parent = $item->parentNode; 
				$parent->removeChild($item);
			}
		} 
		$xdoc->save('../modules/css.xml');
		
		//izbris iz panelov
		$query0=$db->query('SELECT prefix FROM cms_template_position');
		while($result=$db->fetch($query0)) {
			$query1=$db->query('SELECT ID FROM cms_panel_'.$result['prefix'].' WHERE modulID="'.$query['ID'].'"');
			while($result1=$db->fetch($query1)) {
				$db->query('DELETE FROM cms_panel_'.$result['prefix'].' WHERE ID='.$result1['ID'].'');
			}				
		}
		
		//izbriše include
		$db->query('DELETE FROM includes WHERE modulID='.$id.'');
		
		//izbriše posebne strani, če jih ima
		$query0=$db->query('SELECT ID FROM cms_menus_items WHERE selection="4" AND menuID="'.$id.'"');
		while($result=$db->fetch($query0)) {
				$db->query('DELETE FROM cms_menus_items WHERE ID='.$result['ID'].'');		
		}
		
		//izbris edit tabel
		if($query['editTable'] != '')
			$db->query('DROP TABLE '.$query['editTable'].'');
		
		//izbris dodatnih tabel
		$tabele=explode(',', $query['tables']);
		if(count($tabele)>0) {
			foreach($tabele as $value) {
				if($value != '')
					$db->query('DROP TABLE '.$value.'');
			}
		}
		
		//izbriše definicijo
		$db->query('DELETE FROM cms_modules_def WHERE ID='.$id.'');
		echo "ok";
		exit;
	}else if($db->filter('type') == 'comD') {
		$id=$crypt->decrypt($db->filter('id'));
		$query=$db->fetch($db->query('SELECT ID, componentName, tables FROM cms_components_def WHERE ID="'.$id.'"'));
		
		//izbris datotek
		$q=$db->query('SELECT name FROM cms_domains');
		while($r=$db->fetch($q)) {
			if(is_dir('../../modules/'.$r['name'].'/'.$query['componentName'])) {
				recursive_remove_directory('../../modules/'.$query['componentName']);
				recursive_remove_directory('../modules/'.$query['componentName']);
			}
		}
		
		//izbris iz system.xml
		if (ob_get_length() > 0) { ob_end_clean(); }
		header('Content-Type: text/xml;charset=UTF-8'); 			
		$xdoc = new DOMDocument('1.0', 'UTF-8');
		$xdoc->formatOutput = true;
		$xdoc->preserveWhiteSpace = false; 
		$xdoc->load('../modules/system.xml'); 
		
		$xmlDialog = $xdoc->getElementsByTagName('dialog')->item(0); 
		$newItems = $xmlDialog->getElementsByTagName('item');
		$length = $newItems->length;
		for ($i=$length-1;$i>=0;$i--) {
			$item = $newItems->item($i);
			if($item->getAttribute('deleteId')==$query['componentName']."_".$query['ID']) {
				$parent = $item->parentNode; 
				$parent->removeChild($item);
			}
		} 
		$xmlAccordion = $xdoc->getElementsByTagName('accordion')->item(0); 
		$newItems = $xmlAccordion->getElementsByTagName('item');
		$length = $newItems->length;
		for ($i=$length-1;$i>=0;$i--) {
			$item = $newItems->item($i);
			if($item->getAttribute('deleteId')==$query['componentName']."_".$query['ID']) {
				$parent = $item->parentNode; 
				$parent->removeChild($item);
			}
		}
		$xdoc->save('../modules/system.xml');
		
		//izbris iz javascript.xml
		if (ob_get_length() > 0) { ob_end_clean(); }			
		$xdoc = new DOMDocument('1.0', 'UTF-8');
		$xdoc->formatOutput = true;
		$xdoc->preserveWhiteSpace = false; 
		$xdoc->load('../modules/javascript.xml'); 
		
		$xmlDialog = $xdoc->getElementsByTagName('javascript')->item(0); 
		$newItems = $xmlDialog->getElementsByTagName('item');
		$length = $newItems->length;
		for ($i=$length-1;$i>=0;$i--) {
			$item = $newItems->item($i);
			if($item->getAttribute('deleteId')==$query['componentName']."_".$query['ID']) {
				$parent = $item->parentNode; 
				$parent->removeChild($item);
			}
		} 
		$xdoc->save('../modules/javascript.xml');
		
		//izbris iz css.xml
		if (ob_get_length() > 0) { ob_end_clean(); }			
		$xdoc = new DOMDocument('1.0', 'UTF-8');
		$xdoc->formatOutput = true;
		$xdoc->preserveWhiteSpace = false; 
		$xdoc->load('../modules/css.xml'); 
		
		$xmlDialog = $xdoc->getElementsByTagName('css')->item(0); 
		$newItems = $xmlDialog->getElementsByTagName('item');
		$length = $newItems->length;
		for ($i=$length-1;$i>=0;$i--) {
			$item = $newItems->item($i);
			if($item->getAttribute('deleteId')==$query['componentName']."_".$query['ID']) {
				$parent = $item->parentNode; 
				$parent->removeChild($item);
			}
		} 
		$xdoc->save('../modules/css.xml');

		//izbriše favorites
		$query1=$db->query('SELECT ID FROM cms_favorites_def WHERE comID="'.$id.'"');
		while($result=$db->fetch($query1)) {
			$db->query("DELETE FROM cms_favorites_def WHERE ID='".$result['ID']."'");		
		}
		
		//izbris vseh tabel
		$tabele=explode('!', $query['tables']);
		foreach($tabele as $tabela) {
			$query1=$db->query("SHOW TABLES LIKE '".$tabela."'");
			if ($db->rows($query1)>0) {
				$db->query('DROP TABLE `'.$tabela.'`');
			}
		}
		
		//izbriše definicijo
		$db->query('DELETE FROM cms_components_def WHERE ID='.$id.'');
		echo "ok";
		exit;
	}
	else if($db->filter('type') == 'tempD') {
		$id=$crypt->decrypt($db->filter('id'));
		$template=$db->rows($db->query('SELECT ID FROM cms_menus_items WHERE template="'.$id.'"'));
		$template1=$db->rows($db->query('SELECT ID FROM cms_homepage WHERE template="'.$id.'"'));
		if($template==0 && $template1==0) {
			$query=$db->fetch($db->query('SELECT cms_template.folder, cms_domains.name FROM cms_template, cms_domains WHERE cms_template.ID="'.$id.'" AND cms_template.domain=cms_domains.ID'));
			recursive_remove_directory('../../templates/'.$query['folder'].'/'.$query['folder']);
			$db->query('DELETE FROM cms_template WHERE ID='.$id.'');
			echo "ok";
			exit;
		}
		else {
			$error=$lang->MOD_170.": <br/>";
			$query=$db->query('SELECT title, menuID FROM cms_menus_items WHERE template="'.$id.'"');
			while($result=$db->fetch($query)) {
				$query1=$db->fetch($db->query('SELECT title FROM cms_menus WHERE ID="'.$result['menuID'].'"'));
				if(strlen($query1['title'])<2) 
					$error.='-'.$result['title'].' (Posebna stran)<br/>';
				else
					$error.='-'.$result['title'].' ('.$query1['title'].')<br/>';
			}
			$error.="<br/>";
			$query=$db->query('SELECT title FROM cms_homepage WHERE template="'.$id.'"');
			while($result=$db->fetch($query)) {
				$error.=$lang->MOD_171.': '.$result['title'].' <br/>';
			}
			echo $error;
			exit;
		}			
	}
	
	//DeleteAll
	else if($db->filter('type') == 'DD_#article') {
		$idT=explode('!!##!!!', $db->filter('id'));
		foreach($idT as $ids) {
			if(strlen($ids)>3) {
				$id=$crypt->decrypt($ids);
				$db->query('DELETE FROM cms_article WHERE ID='.$id.'');
			}				
		}	
		echo "ok";
		exit;		
	}
	else if($db->filter('type') == 'DD_#articleG') {
		$idT=explode('!!##!!!', $db->filter('id'));
		foreach($idT as $ids) {
			if(strlen($ids)>3) {
				$id=$crypt->decrypt($ids);
				$articleq = $db->query('SELECT ID,category FROM cms_article WHERE category='.$id.'');
				while($articleF=$db->fetch($articleq)) {
					$db->query("DELETE FROM cms_article WHERE ID='".$articleF['ID']."'");	
				}
				$db->query('DELETE FROM cms_article_categories WHERE ID='.$id.'');
			}				
		}
		echo "ok";
		exit;			
	}
	else if($db->filter('type') == 'DD_#user') {
		$idT=explode('!!##!!!', $db->filter('id'));
		foreach($idT as $ids) {
			if(strlen($ids)>3) {
				$id=$crypt->decrypt($ids);
				$db->query('DELETE FROM cms_user WHERE ID='.$id.'');
				$db->query('DELETE FROM cms_user_aditional WHERE userID='.$id.'');
				$db->query('DELETE FROM cms_user_settings WHERE userID='.$id.'');
				$db->query('DELETE FROM cms_state WHERE userID='.$id.'');
				$db->query('DELETE FROM cms_favorites WHERE UserID='.$id.'');
			}				
		}
		echo "ok";
		exit;			
	}
	else if($db->filter('type') == 'DD_#userG') {
		$idT=explode('!!##!!!', $db->filter('id'));
		foreach($idT as $ids) {
			if(strlen($ids)>3) {
				$id=$crypt->decrypt($ids);
				$articleq = $db->query('SELECT ID FROM cms_user WHERE GroupID='.$id.'');
				while($articleF=$db->fetch($articleq)) {
					$db->query('DELETE FROM cms_user WHERE ID='.$id.'');
					$db->query('DELETE FROM cms_user_aditional WHERE userID='.$id.'');
					$db->query('DELETE FROM cms_user_settings WHERE userID='.$id.'');
					$db->query('DELETE FROM cms_state WHERE userID='.$id.'');
					$db->query('DELETE FROM cms_favorites WHERE UserID='.$id.'');	
				}
				$db->query('DELETE FROM cms_user_groups WHERE ID='.$id.'');
			}				
		}
		echo "ok";
		exit;			
	}
	else if($db->filter('type') == 'DD_#menu') {
		$idT=explode('!!##!!!', $db->filter('id'));
		foreach($idT as $ids) {
			if(strlen($ids)>3) {
				$id=$crypt->decrypt($ids);
				$articleq = $db->query('SELECT ID FROM cms_menus_items WHERE menuID='.$id.'');
				while($articleF=$db->fetch($articleq)) {
					$template = $db->query('SELECT prefix FROM cms_template_position');
					while($templateR=$db->fetch($template)) {
						$panel = $db->query('SELECT ID, table_name FROM cms_panel_'.$templateR['prefix'].' WHERE pageID='.$articleF['ID'].'');
						while($panelR=$db->fetch($panel)) {
							$modul = $db->get($db->query('SELECT ID FROM '.$panelR['table_name'].' WHERE cms_panel_id='.$panelR['ID'].''));
							$db->query('DELETE FROM '.$panelR['table_name'].' WHERE ID="'.$modul['ID'].'"');	
							$db->query('DELETE FROM cms_panel_'.$templateR['prefix'].' WHERE ID="'.$panelR['ID'].'"');	
						}
					}
					$db->query("DELETE FROM cms_menus_items WHERE ID='".$articleF['ID']."'");	
				}
				$db->query('DELETE FROM cms_menus WHERE ID='.$id.'');
			}				
		}
		echo "ok";
		exit;			
	}
	else if($db->filter('type') == 'DD_#menuI') {
		$idT=explode('!!##!!!', $db->filter('id'));
		foreach($idT as $ids) {
			if(strlen($ids)>3) {
				$id=$crypt->decrypt($ids);
				$template = $db->query('SELECT prefix FROM cms_template_position');
				while($templateR=$db->fetch($template)) {
					$panel = $db->query('SELECT ID, table_name FROM cms_panel_'.$templateR['prefix'].' WHERE pageID='.$id.'');
					while($panelR=$db->fetch($panel)) {
						$modul = $db->get($db->query('SELECT ID FROM '.$panelR['table_name'].' WHERE cms_panel_id='.$panelR['ID'].''));
						$db->query('DELETE FROM '.$panelR['table_name'].' WHERE ID="'.$modul['ID'].'"');	
						$db->query('DELETE FROM cms_panel_'.$templateR['prefix'].' WHERE ID="'.$panelR['ID'].'"');	
					}
				}
				$db->query('DELETE FROM cms_menus_items WHERE ID='.$id.'');
			}				
		}
		echo "ok";
		exit;			
	}
	else if($db->filter('type') == 'DD_#mail') {
		$idT=explode('!!##!!!', $db->filter('id'));
		foreach($idT as $ids) {
			if(strlen($ids)>3) {
				$id=$crypt->decrypt($ids);
				$main = $db->fetch($db->query('SELECT mainID FROM cms_mail_sent WHERE ID='.$id.''));
				$db->query('DELETE FROM cms_mail_sent WHERE ID='.$id.'');
				$left=$db->query('SELECT ID FROM cms_mail_sent WHERE mainID='.$main['mainID'].'');
				if($db->rows($left)==0) {
					$mainT = $db->fetch($db->query('SELECT status FROM cms_mail_main WHERE ID='.$main['mainID'].''));
					if($mainT['status']=="D")
						$db->query('DELETE FROM cms_mail_main WHERE ID='.$main['mainID'].'');
				}
			}				
		}
		echo "ok";
		exit;			
	}
	else if($db->filter('type') == 'DD_#moduli') {
		$idT=explode('!!##!!!', $db->filter('id'));
		foreach($idT as $ids) {
			if(strlen($ids)>3) {
				$id=$crypt->decrypt($ids);
				$query=$db->fetch($db->query('SELECT ID, moduleName, editTable, tables FROM cms_modules_def WHERE ID="'.$id.'"'));
				//izbris datotek
				$q=$db->query('SELECT name FROM cms_domains');
				while($r=$db->fetch($q)) {
					if(is_dir('../../modules/'.$r['name'].'/'.$query['moduleName'])) {
						recursive_remove_directory('../../modules/'.$query['moduleName']);
						recursive_remove_directory('../modules/'.$query['moduleName']);
					}
				}
				
				//izbris iz system.xml
				if (ob_get_length() > 0) { ob_end_clean(); }
				header('Content-Type: text/xml;charset=UTF-8'); 			
				$xdoc = new DOMDocument('1.0', 'UTF-8');
				$xdoc->formatOutput = true;
				$xdoc->preserveWhiteSpace = false; 
				$xdoc->load('../modules/system.xml'); 
				
				$xmlDialog = $xdoc->getElementsByTagName('dialog')->item(0); 
				$newItems = $xmlDialog->getElementsByTagName('item');
				$length = $newItems->length;
				for ($i=$length-1;$i>=0;$i--) {
					$item = $newItems->item($i);
					if($item->getAttribute('deleteId')==$query['moduleName']."_".$query['ID']) {
						$parent = $item->parentNode; 
						$parent->removeChild($item);
					}
				} 
				$xmlAccordion = $xdoc->getElementsByTagName('accordion')->item(0); 
				$newItems = $xmlAccordion->getElementsByTagName('item');
				$length = $newItems->length;
				for ($i=$length-1;$i>=0;$i--) {
					$item = $newItems->item($i);
					if($item->getAttribute('deleteId')==$query['moduleName']."_".$query['ID']) {
						$parent = $item->parentNode; 
						$parent->removeChild($item);
					}
				}					
				$xdoc->save('../modules/system.xml');
				
				//izbris iz javascript.xml
				if (ob_get_length() > 0) { ob_end_clean(); }			
				$xdoc = new DOMDocument('1.0', 'UTF-8');
				$xdoc->formatOutput = true;
				$xdoc->preserveWhiteSpace = false; 
				$xdoc->load('../modules/javascript.xml'); 
				
				$xmlDialog = $xdoc->getElementsByTagName('javascript')->item(0); 
				$newItems = $xmlDialog->getElementsByTagName('item');
				$length = $newItems->length;
				for ($i=$length-1;$i>=0;$i--) {
					$item = $newItems->item($i);
					if($item->getAttribute('deleteId')==$query['moduleName']."_".$query['ID']) {
						$parent = $item->parentNode; 
						$parent->removeChild($item);
					}
				}
				$xdoc->save('../modules/javascript.xml');
				
				//izbris iz css.xml
				if (ob_get_length() > 0) { ob_end_clean(); }			
				$xdoc = new DOMDocument('1.0', 'UTF-8');
				$xdoc->formatOutput = true;
				$xdoc->preserveWhiteSpace = false; 
				$xdoc->load('../modules/css.xml'); 
				
				$xmlDialog = $xdoc->getElementsByTagName('css')->item(0); 
				$newItems = $xmlDialog->getElementsByTagName('item');
				$length = $newItems->length;
				for ($i=$length-1;$i>=0;$i--) {
					$item = $newItems->item($i);
					if($item->getAttribute('deleteId')==$query['moduleName']."_".$query['ID']) {
						$parent = $item->parentNode; 
						$parent->removeChild($item);
					}
				}
				$xdoc->save('../modules/css.xml');
				
				//izbris iz panelov
				$query0=$db->query('SELECT prefix FROM cms_template_position');
				while($result=$db->fetch($query0)) {
					$db->query('DELETE FROM cms_panel_'.$result['prefix'].' WHERE modulID='.$query['ID'].'');
				}
				
				//izbris edit tabel
				if($query['editTable'] != '')
					$db->query('DROP TABLE '.$query['editTable'].'');
				
				//izbris dodatnih tabel
				$tabele=explode(',', $query['tables']);
				if(count($tabele)>0) {
					foreach($tabele as $value) {
						if($value != '')
							$db->query('DROP TABLE '.$value.'');
					}
				}
				
				//izbriše include
				$db->query('DELETE FROM includes WHERE modulID='.$id.'');
				
				//izbriše posebne strani, če jih ima
				$db->query('DELETE FROM cms_menus_items WHERE selection="4" AND menuID="'.$id.'"');	
				
				//izbriše definicijo
				$db->query('DELETE FROM cms_modules_def WHERE ID='.$id.'');
			}				
		}
		echo "ok";
		exit;			
	}
	else if($db->filter('type') == 'DD_#com') {
		$id=$crypt->decrypt($db->filter('id'));
		$query=$db->fetch($db->query('SELECT ID, componentName, tables FROM cms_components_def WHERE ID="'.$id.'"'));
		//izbris datotek
		$q=$db->query('SELECT name FROM cms_domains');
		while($r=$db->fetch($q)) {
			if(is_dir('../../modules/'.$r['name'].'/'.$query['componentName'])) {
				recursive_remove_directory('../../modules/'.$query['componentName']);
				recursive_remove_directory('../modules/'.$query['componentName']);
			}
		}
		
		//izbris iz system.xml
		if (ob_get_length() > 0) { ob_end_clean(); }
		header('Content-Type: text/xml;charset=UTF-8'); 			
		$xdoc = new DOMDocument('1.0', 'UTF-8');
		$xdoc->formatOutput = true;
		$xdoc->preserveWhiteSpace = false; 
		$xdoc->load('../modules/system.xml'); 
		
		$xmlDialog = $xdoc->getElementsByTagName('dialog')->item(0); 
		$newItems = $xmlDialog->getElementsByTagName('item');
		$length = $newItems->length;
		for ($i=$length-1;$i>=0;$i--) {
			$item = $newItems->item($i);
			if($item->getAttribute('deleteId')==$query['componentName']."_".$query['ID']) {
				$parent = $item->parentNode; 
				$parent->removeChild($item);
			}
		} 
		$xmlAccordion = $xdoc->getElementsByTagName('accordion')->item(0); 
		$newItems = $xmlAccordion->getElementsByTagName('item');
		$length = $newItems->length;
		for ($i=$length-1;$i>=0;$i--) {
			$item = $newItems->item($i);
			if($item->getAttribute('deleteId')==$query['componentName']."_".$query['ID']) {
				$parent = $item->parentNode; 
				$parent->removeChild($item);
			}
		}
		$xdoc->save('../modules/system.xml');
		
		//izbris iz javascript.xml
		if (ob_get_length() > 0) { ob_end_clean(); }			
		$xdoc = new DOMDocument('1.0', 'UTF-8');
		$xdoc->formatOutput = true;
		$xdoc->preserveWhiteSpace = false; 
		$xdoc->load('../modules/javascript.xml'); 
		
		$xmlDialog = $xdoc->getElementsByTagName('javascript')->item(0); 
		$newItems = $xmlDialog->getElementsByTagName('item');
		$length = $newItems->length;
		for ($i=$length-1;$i>=0;$i--) {
			$item = $newItems->item($i);
			if($item->getAttribute('deleteId')==$query['componentName']."_".$query['ID']) {
				$parent = $item->parentNode; 
				$parent->removeChild($item);
			}
		} 
		$xdoc->save('../modules/javascript.xml');
		
		//izbris iz css.xml
		if (ob_get_length() > 0) { ob_end_clean(); }			
		$xdoc = new DOMDocument('1.0', 'UTF-8');
		$xdoc->formatOutput = true;
		$xdoc->preserveWhiteSpace = false; 
		$xdoc->load('../modules/css.xml'); 
		
		$xmlDialog = $xdoc->getElementsByTagName('css')->item(0); 
		$newItems = $xmlDialog->getElementsByTagName('item');
		$length = $newItems->length;
		for ($i=$length-1;$i>=0;$i--) {
			$item = $newItems->item($i);
			if($item->getAttribute('deleteId')==$query['componentName']."_".$query['ID']) {
				$parent = $item->parentNode; 
				$parent->removeChild($item);
			}
		} 
		$xdoc->save('../modules/css.xml');

		//izbriše favorites
		$db->query("DELETE FROM cms_favorites_def WHERE comID='".$id."'");	
		
		//izbris vseh tabel
		$tabele=explode('!', $query['tables']);
		foreach($tabele as $tabela) {
			$query1=$db->query("SHOW TABLES LIKE '".$tabela."'");
			if ($db->rows($query1)>0) {
				$db->query('DROP TABLE `'.$tabela.'`');
			}
		}
		
		//izbriše definicijo
		$db->query('DELETE FROM cms_components_def WHERE ID='.$id.'');
		echo "ok";
		exit;
	}
}
?>