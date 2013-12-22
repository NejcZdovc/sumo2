<?php
require_once('../initialize.php'); 
if(!$session->isLogedIn() || !$security->checkURL()) {
 exit;
}
if(ob_get_length()>0) { ob_end_clean(); }
if($db->filter('type') == 'install') {
	include('../essentials/module.class.php');
	if(($return = $module->checkSystem()) === true) { 
		$module->number = $db->filter('number');
		$zip = new ZipArchive();
		if($zip->open('../temp/'.$module->number.'.zip') === TRUE) {
			mkdir('../temp/'.$module->number, PER_FOLDER);
			chmod('../temp/'.$module->number, PER_FOLDER);
			$zip->extractTo('../temp/'.$module->number.'/');
			chmodAll('../temp/'.$module->number);
			if($zip->close()) {
			       unlink('../temp/'.$module->number.'.zip');
			       if(is_file('../temp/'.$module->number.'/sqlmagic.php')) {
					   $domains=explode('*/*', $db->filter('domain'));
					   $module->domains=$domains;
				       include_once('../temp/'.$module->number.'/sqlmagic.php');
				       if($module->isSuccess() == true) {
					    if(is_file('../temp/'.$module->number.'/files.php')) {
						 include('../temp/'.$module->number.'/files.php');
						 $javascript = new DOMDocument();
						 $javascript->load('../modules/javascript.xml');
						 $jsRoot = $javascript->getElementsByTagName("javascript")->item(0);
						 foreach($backend_scripts as $path) {
						     if(is_file('../temp/'.$module->number.'/'.$path)) {
							 $item = $javascript->createElement("item");
							 $id = $javascript->createAttribute("deleteId");
							 $idVal = $javascript->createTextNode($module->getUnique().'_'.$module->getID());
							 $id->appendChild($idVal);
							 $content = file_get_contents('../temp/'.$module->number.'/'.$path);
							 $cdata = $javascript->createCDATASection($content);
							 $item->appendChild($id);
							 $item->appendChild($cdata);
							 $jsRoot->appendChild($item);
						     } else {
							 recursive_remove_directory('../temp/'.$module->number);
							 $module->reverseActions();
							 $module->reverseFiles();
							 if(ob_get_length()>0)
							 	ob_end_clean();
							 echo 'NOFILE!'.$path;
							 exit;
						     }
						 }
						 $javascript->save('../modules/javascript.xml');
						 $css = new DOMDocument();
						 $css->load('../modules/css.xml');
						 $cssRoot = $css->getElementsByTagName("css")->item(0);
						 foreach($backend_css as $path) {
						     if(is_file('../temp/'.$module->number.'/'.$path)) {
							 $item = $css->createElement("item");
							 $id = $css->createAttribute("deleteId");
							 $idVal = $css->createTextNode($module->getUnique().'_'.$module->getID());
							 $id->appendChild($idVal);
							 $content = file_get_contents('../temp/'.$module->number.'/'.$path);
							 $cdata = $css->createCDATASection($content);
							 $item->appendChild($id);
							 $item->appendChild($cdata);
							 $cssRoot->appendChild($item);
						     } else {
							 recursive_remove_directory('../temp/'.$module->number);
							 $module->reverseActions();
							 $module->reverseFiles();
							 if(ob_get_length()>0)
							 	ob_end_clean();
							 echo 'NOFILE!'.$path;
							 exit;
						     }
						 }
						 $css->save('../modules/css.xml');
						 if(is_dir('../temp/'.$module->number.'/files-b')) {
						     copyFiles('../temp/'.$module->number.'/files-b/','../modules/'.$module->getUnique().'/');
						     chmodAll('../modules/'.$module->getUnique().'/');
						 } else {
						     recursive_remove_directory('../temp/'.$module->number);
						     $module->reverseActions();
						     $module->reverseFiles();
						    if(ob_get_length()>0)
							 	ob_end_clean();
						     echo 'nofbf';
						     exit;
						 }						 
						 if(is_dir('../temp/'.$module->number.'/files-f')) {							 
							 copyFiles('../temp/'.$module->number.'/files-f/','../../modules/default/'.$module->getUnique().'/');
							 chmodAll('../../modules/default/'.$module->getUnique().'/');
							 foreach($domains as $domain) {
								 $nameD=$db->get($db->query('SELECT name FROM cms_domains WHERE ID="'.$domain.'"'));
								 copyFiles('../temp/'.$module->number.'/files-f/','../../modules/'.$nameD['name'].'/'.$module->getUnique().'/');	
								 chmodAll('../../modules/'.$nameD['name'].'/'.$module->getUnique().'/');		
							 }						     
						 } else {
						     recursive_remove_directory('../temp/'.$module->number);
						     $module->reverseActions();
						     $module->reverseFiles();
						     if(ob_get_length()>0)
							 	ob_end_clean();
						     echo 'nofff';
						     exit;
						 }
						 if(is_dir('../temp/'.$module->number.'/front_images')) {
							$files = scandir('../temp/'.$module->number.'/front_images/');
							foreach($files as $file) {
								if(is_file('../temp/'.$module->number.'/front_images/'.$file)) {
									if(is_file('../../modules/default/images/'.$file)) {
										 $newFilename = $module->addToFilename($file,'_'.$module->getUnique());
										 copy('../temp/'.$module->number.'/front_images/'.$file,'../../modules/default/images/'.$newFilename);
										 chmod('../../modules/default/images/'.$newFilename,PER_FILE);
										 foreach($domains as $domain) {
											 $nameD=$db->get($db->query('SELECT name FROM cms_domains WHERE ID="'.$domain.'"'));
											 copy('../temp/'.$module->number.'/front_images/'.$file,'../../modules/'.$nameD['name'].'/images/'.$newFilename);
											 chmod('../../modules/images/'.$newFilename,PER_FILE);
										 }
									 } else {
										 copy('../temp/'.$module->number.'/front_images/'.$file,'../../modules/default/images/'.$file);
										 chmod('../../modules/default/images/'.$file,PER_FILE);
										  foreach($domains as $domain) {
											 $nameD=$db->get($db->query('SELECT name FROM cms_domains WHERE ID="'.$domain.'"'));
											 copy('../temp/'.$module->number.'/front_images/'.$file,'../../modules/'.$nameD['name'].'/images/'.$file);
										 	 chmod('../../modules/'.$nameD['name'].'/images/'.$file,PER_FILE);
										  }
									 }
								}
							}
						 }
						 if(is_file('../temp/'.$module->number.'/system.xml')) {
							 $srcSystem = new DOMDocument();
							 $desSystem = new DOMDocument();
							 $desSystem->load('../modules/system.xml');
							 $srcSystem->load('../temp/'.$module->number.'/system.xml');
							 if($srcSystem->getElementsByTagName('dialog')->length > 0) {
							    $xmlDialog = $srcSystem->getElementsByTagName('dialog')->item(0);
							    $dialog = $desSystem->getElementsByTagName('dialog')->item(0);
							    $newItems = $xmlDialog->getElementsByTagName('item');
							    foreach($newItems as $item) {
							       $node = $desSystem->importNode($item, true);
							       $attribute = $desSystem->createAttribute('deleteId');
							       $attr_val = $desSystem->createTextNode($module->getUnique().'_'.$module->getID());
							       $attribute->appendChild($attr_val);
							       $node->appendChild($attribute);
							       $dialog->appendChild($node);
							    }
							 } else {
							     recursive_remove_directory('../temp/'.$module->number);
							     $module->reverseActions();
							     $module->reverseFiles();
							     $module->reverseSystem();
							     echo 'nodialog';
							     exit;
							 }
							 if($srcSystem->getElementsByTagName('accordion')->length > 0) {
							     $accordion = $desSystem->getElementsByTagName('accordion')->item(0);
							     $xmlAccordion = $srcSystem->getElementsByTagName('accordion')->item(0);
							     $newItems = $xmlAccordion->getElementsByTagName('item');
							     foreach($newItems as $item) {
									$node = $desSystem->importNode($item, true);
									$attribute = $desSystem->createAttribute('deleteId');
									$attr_val = $desSystem->createTextNode($module->getUnique().'_'.$module->getID());
									$attribute->appendChild($attr_val);
									$node->appendChild($attribute);
									$accordion->appendChild($node);
							     }
							 } else {
							     recursive_remove_directory('../temp/'.$module->number);
							     $module->reverseActions();
							     $module->reverseFiles();
							     $module->reverseSystem();
							     echo 'noaccordion';
							     exit;
							 }
							 $desSystem->save('../modules/system.xml');
						     recursive_remove_directory('../temp/'.$module->number);
							 $module->insertDomain($db->filter('domain'));
						     echo 'ok';
						     exit;
						 } else {
						     recursive_remove_directory('../temp/'.$module->number);
						     $module->reverseActions();
						     $module->reverseFiles();
						     echo 'nosystem';
						     exit;
						 }
					    } else {
						 recursive_remove_directory('../temp/'.$module->number);
						 $module->reverseActions();
						 echo 'nofile';
						 exit;
					    }
				       } else {
					    recursive_remove_directory('../temp/'.$module->number);
					    $module->reverseActions();
					    echo 'SQL!'.implode('!',$module->error);
					    exit;
				       }
			       } else {
				       recursive_remove_directory('../temp/'.$module->number);
				       echo 'nosql';
				       exit;
			       }
			} else {
			       unlink('../temp/'.$module->number.'.zip');
			       recursive_remove_directory('../temp/'.$module->number);
			       echo 'zipclose';
			       exit;
			}
		} else {
			echo 'zipopen';
			exit;
		}
	} else {
		$output = '';
		$first = true;
		foreach($return as $item) {
			$output .= '!'.$item;
		}
		echo 'PERM'.$output;
		exit;
	}
} else if($db->filter('type') == 'editdata') {
	$id = $crypt->decrypt($db->filter('id'));
	$result = $db->get($db->query("SELECT name FROM cms_modules_def WHERE ID='".$id."'"));
	echo $result['name'];
	exit;
} else if($db->filter('type') == 'delete') {
	$id = $crypt->decrypt($db->filter('id'));
	$db->query("UPDATE cms_modules_def SET status='D' WHERE ID='".$id."'");
	echo 'ok';
	exit;
} else if($db->filter('type') == 'deleteC') {
	$id = $crypt->decrypt($db->filter('id'));
	$db->query("UPDATE cms_components_def SET status='D' WHERE ID='".$id."'");
	$query=$db->query('SELECT ID FROM cms_favorites_def WHERE comID="'.$id.'"');
	while($result=$db->fetch($query)) {
		$db->query("UPDATE cms_favorites_def SET statusID='D' WHERE ID='".$result['ID']."'");		
	}
	echo 'ok';
	exit;
} else if($db->filter('type') == 'cache') {	 
	 if($db->is('folder')) {
		Clear('cache/modules/'.$user->domainName.'/'.$db->filter('folder'));
		Clear('cache/modules/'.$user->domainName.'/'.$db->filter('folder'), 'default');
		echo 'ok';
	 }
	 else
	 	echo 'no';
	exit;
}else if($db->filter('type') == 'clearCache') {
	if($crypt->decrypt($db->filter('folder'))=='All') {
		$query = $db->query("SELECT ID, moduleName FROM cms_modules_def WHERE status='N' ORDER BY ID asc");
		while($result = $db->fetch($query)) {
			$query1 = $db->query("SELECT cms_domains.name FROM cms_domains, cms_domains_ids WHERE cms_domains_ids.type='mod' AND cms_domains_ids.elementID='".$result['ID']."' AND cms_domains_ids.domainID=cms_domains.ID");
			while($result1 = $db->fetch($query1)) {
				Clear('cache/modules/'.$result1['name'].'/'.$result['moduleName']);				
			}
			Clear('cache/modules/default/'.$result['moduleName']);
		}
	}
	else {
		Clear('cache/modules/'.$user->domainName.'/'.$crypt->decrypt($db->filter('folder')));
	}
	echo 'ok';
	exit;
} else if($db->filter('type') == 'changeDomain') {
	$id=$crypt->decrypt($db->filter('id'));
	$domains=explode('*/*', $db->filter('domain'));	
	//delete existisng
	$delete='';
	foreach($domains as $domain) {
		$delete.=' AND ids.domainID!="'.$domain.'"';
	}	
	$qD=$db->query('SELECT def.moduleName, domain.name, ids.ID FROM cms_modules_def as def LEFT JOIN cms_domains_ids as ids ON def.ID=ids.elementID LEFT JOIN cms_domains as domain ON ids.domainID=domain.ID WHERE def.ID="'.$id.'" AND ids.type="mod" '.$delete.'');
	while($rD=$db->fetch($qD)) {
		$db->query("DELETE FROM cms_domains_ids WHERE elementID='".$id."' AND type='mod'");
		recursive_remove_directory('../../modules/'.$rD['name'].'/'.$rD['moduleName'].'/');
	}
	
	//Add
	$moduleData=$db->get($db->query('SELECT moduleName FROM cms_modules_def WHERE ID="'.$id.'"'));
	foreach($domains as $domain) {
		$qA=$db->rows($db->query('SELECT ID FROM cms_domains_ids WHERE domainID="'.$domain.'" AND elementID="'.$id.'" AND type="mod" '));
		if($qA==0) {
			$nameD=$db->get($db->query('SELECT name FROM cms_domains WHERE ID="'.$domain.'"'));
			 if(!is_dir('../../modules/'.$nameD['name'].'/'.$moduleData['moduleName'].'')) {							 
				 copyFiles('../../modules/default/'.$moduleData['moduleName'].'/','../../modules/'.$nameD['name'].'/'.$moduleData['moduleName'].'/');
				 chmodAll('../../modules/'.$nameD['name'].'/'.$moduleData['moduleName'].'/');				     
			 }		
			$files = scandir('../../modules/default/images/');
			foreach($files as $file) {
				if(strlen($file)>2) {
					if(!is_file('../../modules/'.$nameD['name'].'/images/'.$file)) {
						 copy('../../modules/default/images/'.$file, '../../modules/'.$nameD['name'].'/images/'.$file);
						 chmod('../../modules/'.$nameD['name'].'/images/'.$file,PER_FILE);
					 } 
				}
			}
			$db->query("INSERT INTO cms_domains_ids (elementID,domainID,type) VALUES ('".$id."','".$domain."','".$db->filter('ver')."')");	
		}
	 }	
	echo 'ok';
	exit;
}
?>