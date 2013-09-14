<?php
	require_once('../initialize.php');
	if(($db->is('page') && ($db->filter('page')!='C3GH64v8A5UcJ' || !$session->isLogedIn())) && (!$session->isLogedIn() || !$security->checkURL())) {
	 exit;
	}
	if (ob_get_length() > 0) { ob_end_clean(); }
		if($db->is('type')) {
			if($db->filter('type') == "moveInner") {
			$obj = $db->filter('obj');
			$target = $db->filter('target');
			$objArray = explode("#",$obj);
			$targetArray = explode("#",$target);
			$objResult = $db->get($db->query("SELECT * FROM cms_panel_".$objArray[1]." WHERE ID='".$objArray[0]."'"));		
			$targetResult = $db->get($db->query("SELECT * FROM cms_panel_".$targetArray[1]." WHERE ID='".$targetArray[0]."'"));
			$incQuery = $db->query("SELECT * FROM cms_panel_".$targetArray[1]." WHERE orderID >= ".$targetResult['orderID']." AND pageID='".$objResult['pageID']."'");
			while($incResult = $db->fetch($incQuery)) {
				$db->query("UPDATE cms_panel_".$targetArray[1]." SET orderID='".($incResult['orderID']+1)."' WHERE ID='".$incResult['ID']."'");
			}
			$db->query("INSERT INTO cms_panel_".$targetArray[1]." (modulID,orderID,enabled,pageID,lang,title,prefix,table_name,domain) VALUES ('".$objResult['modulID']."','".$targetResult['orderID']."','".$objResult['enabled']."','".$objResult['pageID']."','".$objResult['lang']."','".$objResult['title']."','".$objResult['prefix']."','".$objResult['table_name']."', '".$user->domain."')");
			$newId = mysql_insert_id();
			$db->query("UPDATE ".$objResult['table_name']." SET cms_layout='".$targetArray[1]."',cms_panel_id='".$newId."' WHERE cms_layout='".$objArray[1]."' AND cms_panel_id='".$objArray[0]."'");
			$db->query("DELETE FROM cms_panel_".$objArray[1]." WHERE ID='".$objArray[0]."'");
			$decQuery = $db->query("SELECT * FROM cms_panel_".$objArray[1]." WHERE orderID > ".$objResult['orderID']." AND pageID='".$objResult['pageID']."'");		
			while($decResult = $db->fetch($decQuery)) {
				$db->query("UPDATE cms_panel_".$objArray[1]." SET orderID='".($decResult['orderID']-1)."' WHERE ID='".$decResult['ID']."'");
			}
			echo $newId.'#'.$targetArray[1];
		} else if($db->filter('type') == "moveOuter") {
			$obj = $db->filter('obj');
			$target = $db->filter('target');
			$objArray = explode("#",$obj);
			$objResult = $db->get($db->query("SELECT * FROM cms_panel_".$objArray[1]." WHERE ID='".$objArray[0]."'"));
			$orderResult = $db->get($db->query("SELECT * FROM cms_panel_".$target." WHERE pageID='".$objResult['pageID']."' ORDER BY orderID DESC LIMIT 1"));
			$db->query("INSERT INTO cms_panel_".$target." (modulID,orderID,enabled,pageID,lang,title,prefix,table_name,domain) VALUES ('".$objResult['modulID']."','".($orderResult['orderID']+1)."','".$objResult['enabled']."','".$objResult['pageID']."','".$objResult['lang']."','".$objResult['title']."','".$objResult['prefix']."','".$objResult['table_name']."', '".$user->domain."')");
			$newId = mysql_insert_id();
			$db->query("UPDATE ".$objResult['table_name']." SET cms_layout='".$target."',cms_panel_id='".$newId."' WHERE cms_layout='".$objArray[1]."' AND cms_panel_id='".$objArray[0]."'");
			$db->query("DELETE FROM cms_panel_".$objArray[1]." WHERE ID='".$objArray[0]."'");		
			$decQuery = $db->query("SELECT * FROM cms_panel_".$objArray[1]." WHERE orderID > ".$objResult['orderID']." AND pageID='".$objResult['pageID']."'");		
			while($decResult = $db->fetch($decQuery)) {
				$db->query("UPDATE cms_panel_".$objArray[1]." SET orderID='".($decResult['orderID']-1)."' WHERE ID='".$decResult['ID']."'");
			}
			$newOrder = $db->get($db->query("SELECT * FROM cms_panel_".$target." WHERE ID='".$newId."'"));		
			echo $newId.'#'.$target;
		} else if($db->filter('type') == "add") {
			$name = $db->filter('name');
			$id = $db->filter('id');
			$prefix = $db->filter('prefix');
			$target = $db->filter('target');
			$pages = $db->filter('pages');
			$currentPage = $db->filter('currentPage');
			$copyModule = $db->filter('copyModule');
			$pagesArray = explode("!",$pages);
			$tableResult = $db->get($db->query("SELECT * FROM cms_modules_def WHERE ID='".$id."'"));
			$prefixResult = $db->get($db->query("SELECT * FROM cms_modul_prefix WHERE ID='".$prefix."'"));
			if(count($pagesArray) > 1) {
				$lastId = $db->get($db->query("SELECT cms_group_id FROM ".$tableResult['editTable']." ORDER BY cms_group_id DESC LIMIT 1"));
				if($lastId['cms_group_id'] == 0) {
					$group_id = 1;
				} else {
					$group_id = $lastId['cms_group_id'] + 1;
				}
			} else {
				$group_id = 0;
			}
			if($copyModule=='0')
				$currentID=0;
			else
				$currentID=-1;
				
			//dodamo currentPage
			$orderResult = $db->get($db->query("SELECT * FROM cms_panel_".$target." WHERE pageID='".$currentPage."' ORDER BY orderID DESC LIMIT 1"));
			$parentPageResult = $db->get($db->query("SELECT * FROM cms_menus_items WHERE ID='".$currentPage."'"));
			if($parentPageResult['selection'] == '4') {
				$langResult['lang'] = $parentPageResult['target'];
			} else if($parentPageResult['menuID'] != -1) {
			$langResult = $db->get($db->query("SELECT * FROM cms_menus WHERE ID='".$parentPageResult['menuID']."'"));
			} else {
				$langResult = $db->get($db->query("SELECT * FROM cms_homepage WHERE ID='".$parentPageResult['link']."'"));
			}
			$db->query("INSERT INTO cms_panel_".$target." (modulID,orderID,enabled,pageID,lang,title,prefix,table_name, copyModul,domain) VALUES ('".$id."','".($orderResult['orderID']+1)."','1','".$currentPage."','".$langResult['lang']."','".$name."','".$prefixResult['prefix']."','".$tableResult['editTable']."', '".$currentID."', '".$user->domain."')");
			$curId = mysql_insert_id();
			$db->query("INSERT INTO ".$tableResult['editTable']." (cms_layout,cms_panel_id,cms_group_id) VALUES ('".$target."','".$curId."','".$group_id."')");
			if($copyModule=='0')
				$currentID=0;
			else
				$currentID=$curId;;
			
			//dodamo ostale
			foreach($pagesArray as $page) {
				$page = explode("#",$page);
				$page=$page[0];
				if($page!=$currentPage) {
					$orderResult = $db->get($db->query("SELECT * FROM cms_panel_".$target." WHERE pageID='".$page."' ORDER BY orderID DESC LIMIT 1"));
					$parentPageResult = $db->get($db->query("SELECT * FROM cms_menus_items WHERE ID='".$page."'"));
					if($parentPageResult['selection'] == '4') {
						$langResult['lang'] = $parentPageResult['target'];
					} else if($parentPageResult['menuID'] != -1) {
						$langResult = $db->get($db->query("SELECT * FROM cms_menus WHERE ID='".$parentPageResult['menuID']."'"));
					} else {
						$langResult = $db->get($db->query("SELECT * FROM cms_homepage WHERE ID='".$parentPageResult['link']."'"));
					}
					$db->query("INSERT INTO cms_panel_".$target." (modulID,orderID,enabled,pageID,lang,title,prefix,table_name, copyModul,domain) VALUES ('".$id."','".($orderResult['orderID']+1)."','1','".$page."','".$langResult['lang']."','".$name."','".$prefixResult['prefix']."','".$tableResult['editTable']."', '".$currentID."', '".$user->domain."')");
					$curId = mysql_insert_id();
					$db->query("INSERT INTO ".$tableResult['editTable']." (cms_layout,cms_panel_id,cms_group_id) VALUES ('".$target."','".$curId."','".$group_id."')");
				}
			}
			echo "done";
		} else if($db->filter('type') == "rename") {
			$name = $db->filter('title');
			$prefix = $db->filter('prefix');
			$id = $db->filter('id');
			$layout = $db->filter('layout');
			$cache = $db->filter("cache");
			$specialPage = $db->filter("specialPage");
			$copyModul = $db->filter("copyModul");
			if($copyModul=="0")
				$copyModul="copyModul='0', ";
			else
				$copyModul="";
				
			$db->query("UPDATE cms_panel_".$layout." SET title='".$name."', ".$copyModul." prefix='".$prefix."', cache='".$cache."', specialPage='".$specialPage."' WHERE ID='".$id."'");
			echo "done";
		} else if($db->filter('type') == "remove") {
			$layout = $db->filter('layout');
			$id = $db->filter('id');
			$all = $db->filter('all');
			$objResult = $db->get($db->query("SELECT * FROM cms_panel_".$layout." WHERE ID='".$id."'"));
			if($all=="yes") {
				$groupID=$db->get($db->query("SELECT * FROM ".$objResult['table_name']." WHERE cms_layout='".$layout."' AND cms_panel_id='".$id."'"));
				if($groupID['cms_group_id']==0) {
					$decQuery = $db->query("SELECT * FROM cms_panel_".$layout." WHERE orderID > ".$objResult['orderID']." AND pageID='".$objResult['pageID']."'");		
					while($decResult = $db->fetch($decQuery)) {
						$db->query("UPDATE cms_panel_".$layout." SET orderID='".($decResult['orderID']-1)."' WHERE ID='".$decResult['ID']."'");
					}
					$db->query("DELETE FROM cms_panel_".$layout." WHERE ID='".$id."'");
					$db->query("DELETE FROM ".$objResult['table_name']." WHERE cms_layout='".$layout."' AND cms_panel_id='".$id."'");						
				} else {
					$groupReader=$db->query("SELECT * FROM ".$objResult['table_name']." WHERE cms_layout='".$layout."' AND cms_group_id='".$groupID['cms_group_id']."'");
					while($groupResult = $db->fetch($groupReader)) {
						$id=$groupResult['cms_panel_id'];
						$objResult = $db->get($db->query("SELECT * FROM cms_panel_".$layout." WHERE ID='".$id."'"));
						$decQuery = $db->query("SELECT * FROM cms_panel_".$layout." WHERE orderID > ".$objResult['orderID']." AND pageID='".$objResult['pageID']."'");		
						while($decResult = $db->fetch($decQuery)) {
							$db->query("UPDATE cms_panel_".$layout." SET orderID='".($decResult['orderID']-1)."' WHERE ID='".$decResult['ID']."'");
						}
						$db->query("DELETE FROM cms_panel_".$layout." WHERE ID='".$id."'");
						$db->query("DELETE FROM ".$objResult['table_name']." WHERE cms_layout='".$layout."' AND cms_panel_id='".$id."'");
					}
				}
			} else {			
				$decQuery = $db->query("SELECT * FROM cms_panel_".$layout." WHERE orderID > ".$objResult['orderID']." AND pageID='".$objResult['pageID']."'");		
				while($decResult = $db->fetch($decQuery)) {
					$db->query("UPDATE cms_panel_".$layout." SET orderID='".($decResult['orderID']-1)."' WHERE ID='".$decResult['ID']."'");
				}
				if($objResult['copyModul']=='-1') {
					$db->query("UPDATE cms_panel_".$layout." SET copyModul='0' WHERE copyModul='".$id."'");
				}
				$db->query("DELETE FROM cms_panel_".$layout." WHERE ID='".$id."'");
				$db->query("DELETE FROM ".$objResult['table_name']." WHERE cms_layout='".$layout."' AND cms_panel_id='".$id."'");		
			}
			echo "done";
		} else if($db->filter('type') == "saveall") {
			$pages = $db->filter("pages");
			$panelID = $db->filter("id");
			$layout = $db->filter("layout");
			$cache = $db->filter("cache");
			$name = $db->filter("name");
			$prefix = $db->filter("prefix");
			$specialPage = $db->filter("specialPage");
			$copyModul = $db->filter("copyModul");
			if($copyModul=="0")
				$copyModul="copyModul='0', ";
			else
				$copyModul="";
			$pagesArray = explode("!",$pages);
			$panelResult = $db->get($db->query("SELECT * FROM cms_panel_".$layout." WHERE ID='".$panelID."'"));
			$modulInfo = $db->get($db->query("SELECT * FROM cms_modules_def WHERE ID='".$panelResult['modulID']."'"));
			$modulResult = $db->get($db->query("SELECT * FROM ".$modulInfo['editTable']." WHERE cms_layout='".$layout."' AND cms_panel_id='".$panelID."' LIMIT 1"));
			$columnNames = $db->getColumnNames($modulInfo['editTable']);
			if($panelResult['copyModul']=="0" || $copyModul=="0")
				$copyValue=0;
			else if($panelResult['copyModul']=="-1")
				$copyValue=$panelID;
			else
				$copyValue=$panelResult['copyModul'];
			
			if($modulResult['cms_group_id']==0 && count($pagesArray)>1) {
				$lastId = $db->get($db->query("SELECT cms_group_id FROM ".$modulInfo['editTable']." ORDER BY cms_group_id DESC LIMIT 1"));
				if($lastId['cms_group_id'] == 0) {
					$group_id = 1;
				} else {
					$group_id = $lastId['cms_group_id'] + 1;
				}
				$db->query("UPDATE ".$modulInfo['editTable']." SET cms_group_id='".$group_id."' WHERE cms_layout='".$layout."' AND cms_panel_id='".$panelID."'");
				$modulResult['cms_group_id']=$group_id;
			}
			
			foreach($pagesArray as $page) {
				if($page == $panelResult['pageID']) {
					$db->query("UPDATE cms_panel_".$layout." SET title='".$name."', ".$copyModul." prefix='".$prefix."' , cache='".$cache."', specialPage='".$specialPage."' WHERE ID='".$panelID."'");
					continue;	
				}
				$search = $db->query("SELECT ".$modulInfo['editTable'].".ID, cms_panel_".$layout.".ID AS layoutID FROM cms_panel_".$layout.", ".$modulInfo['editTable']." WHERE cms_panel_".$layout.".pageID='".$page."' AND cms_panel_".$layout.".modulID='".$modulInfo['ID']."' AND ".$modulInfo['editTable'].".cms_group_id='".$modulResult['cms_group_id']."'  AND ".$modulInfo['editTable'].".cms_layout='".$layout."'  AND ".$modulInfo['editTable'].".cms_panel_id=cms_panel_".$layout.".ID LIMIT 1");
				if($db->rows($search) > 0) {
					$searchResult = $db->get($search);
					$setString = '';
					$first = true;
					foreach($columnNames as $column) {
						if($column != "ID" && $column != "cms_layout" && $column != "cms_panel_id" && $column != "cms_group_id") {
							if($first) {
								$first = false;
								$setString .= $column."='".$content=str_replace("'mailto:'", "\'mailto:\'", $modulResult[$column])."'";
							} else {
								$setString .= ", ".$column."='".$$content=str_replace("'mailto:'", "\'mailto:\'", $modulResult[$column])."'";
							}
						}
					}
					$db->query("UPDATE cms_panel_".$layout." SET title='".$name."', ".$copyModul." prefix='".$prefix."', cache='".$cache."', specialPage='".$specialPage."' WHERE ID='".$searchResult['layoutID']."'");
					if($setString != "")
						$db->query("UPDATE ".$modulInfo['editTable']." SET ".$setString." WHERE ID='".$searchResult['ID']."'");
				} else  {
					$orderResult = $db->get($db->query("SELECT * FROM cms_panel_".$layout." WHERE pageID='".$page."' ORDER BY orderID DESC LIMIT 1"));
					if($orderResult) {
						$newOrder = $orderResult['orderID'] + 1;
					} else {
						$newOrder = 1;
					}
					$db->query("INSERT INTO cms_panel_".$layout." (modulID,orderID,pageID,lang,title,prefix,table_name,cache,specialPage,copyModul,domain) VALUES ('".$modulInfo['ID']."','".$newOrder."','".$page."','".$panelResult['lang']."','".$name."','".$prefix."','".$panelResult['table_name']."', '".$cache."', '".$specialPage."', '".$copyValue."', '".$user->domain."')");
					$newId = $db->getLastId();
					$setString = '';
					$colString = '';
					$first = true;
					foreach($columnNames as $column) {
						if($column != "ID") {
							if($first) {
								$first = false;
								$setString .= "'".$content=str_replace("'mailto:'", "\'mailto:\'", $modulResult[$column])."'";
								$colString .= $column;
							} else if($column == "cms_panel_id") {
								$setString .= ", '".$newId."'";
								$colString .= ", ".$column;
							} else {
								$setString .= ", '".$content=str_replace("'mailto:'", "\'mailto:\'", $modulResult[$column])."'";
								$colString .= ", ".$column;
							}
						}
					}
					$db->query("INSERT INTO ".$modulInfo['editTable']." (".$colString.") VALUES (".$setString.")");
				}
			}
			echo 'ok';
			exit;
		} else if($db->filter('type') == "clearCache") {
			$layout = $db->filter('layout');
			$panel = $db->filter('panel');
			$query = $db->fetch($db->query("SELECT * FROM cms_panel_".$layout." WHERE ID='".$panel."'"));
			$modul = $db->get($db->query("SELECT * FROM cms_modules_def WHERE ID='".$query['modulID']."'"));
			echo $modul['moduleName'];
		} else if($db->filter('type') == "changeStatus") {
			$id = $db->filter('id');
			$table = $db->filter('table');
			$result = $db->get($db->query("SELECT cms_enabled, cms_group_id FROM ".$table." WHERE ID='".$id."'"));
			if($result) {
				if($result['cms_enabled'] == 0) {
					$new = 1;
				} else {
					$new = 0;
				}
				if($result['cms_group_id']!=0) {
					$db->query("UPDATE ".$table." SET cms_enabled='".$new."' WHERE cms_group_id='".$result['cms_group_id']."'");
				}else {
					$db->query("UPDATE ".$table." SET cms_enabled='".$new."' WHERE ID='".$id."'");
				}
				echo 'ok';
				exit;			
			}
		}
	}
?>