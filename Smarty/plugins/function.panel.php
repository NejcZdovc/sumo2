<?php
function function_sumo_panel($parameters, $smarty)
{
	global $db, $template, $globals;
	
	$className=$parameters['id'];
	$check = $db->query('SELECT ID FROM cms_template_position WHERE prefix="'.$className.'" AND domain="'.$globals->domainID.'"');
	if($db->rows($check)>0) {
		$query1 = $db->query("SELECT * FROM cms_panel_".$className." WHERE pageID='".$template->pageID."' AND lang='".$template->langID."' AND domain='".$globals->domainID."' ORDER BY orderID ASC");
		while($result1 = $db->fetch($query1)) {
			if($result1['copyModul']>0) {
				$queryModul=$db->query("SELECT * FROM cms_panel_".$className." WHERE ID='".$result1['copyModul']."' AND domain='".$globals->domainID."'");					
				if($db->rows($queryModul)>0) {
					$result1=$db->get($queryModul);
				}
			}
			$result2 = $db->get($db->query("SELECT * FROM cms_modules_def WHERE ID='".$result1['modulID']."' AND enabled='1' AND status='N'"));
			$result3 = $db->get($db->query("SELECT * FROM ".$result2['editTable']." WHERE cms_layout='".$className."' AND cms_panel_id='".$result1['ID']."' AND cms_enabled='1'"));
			if(isset($result3['ID'])) {
				$template->id = $result3['ID'];
				$template->tableName = $result2['editTable'];
				$template->folderName = $result2['moduleName'];
				$template->title = $result1['title'];
				$template->modul = SITE_ROOT.SITE_FOLDER.DS.'modules/'.$globals->domainName.'/'.$result2['moduleName'].'/index.php';
				$globals->GLOBAL_ID = $template->id;
				$globals->GLOBAL_TN = $template->tableName;
				$globals->GLOBAL_FN = $template->folderName;
				if(is_file($template->modul)) {
					include($template->modul);
				} else {
					error_log("Modul is missing: ".$result2['moduleName']);
					die("Modul is missing: ".$result2['moduleName']);
				}
			}
		}
	}
	else {
		error_log('Template position '.$className.' does not exist, please create it in CMS.');
		die('Template position '.$className.' does not exist, please create it in CMS.');
	}
}
