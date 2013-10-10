<?php 
	require_once('includes/initialize.php');
	$offline_link=SITE_FOLDER."/off/";
	$f404_link=SITE_FOLDER."/404/";
	
	//Redirect WWW
	$bits = explode('/', $_SERVER['HTTP_HOST']);
	if ($bits[0]=='http:' || $bits[0]=='https:') {
		$domainb= $bits[2];
	} else {
		$domainb= $bits[0];
	}
	unset($bits);
	$bits = explode('.', $domainb);
	if($bits[0] != "www") {
		if(strlen($bits[1])<3) {
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: http://www.".$_SERVER['HTTP_HOST']."".rawurldecode($_SERVER['REQUEST_URI'])."");
			exit();
		}
	}
	
	//attack attemp
	if(strpos($function->getUrl(), '<script')) {
		error_log("Attack attemp. User IP: ".$user->IP." / Path: index.php (25)");
		header('Location: '.$f404_link);
		exit();
	}
	
	function getNewPage($page) {
		global $db;
		$result6 = $db->get($db->query("SELECT * FROM cms_menus_items WHERE NOT status='D' AND enabled='1' AND ID='".$page."'"));
		if($result6['template'] == '-1') {
			$newResult = $db->get($db->query("SELECT selection, link FROM cms_homepage WHERE ID='".$result6['link']."'"));
			if($newResult['selection'] == '2') {
				$newPage = intval($newResult['link']);
				getNewPage($newPage);
			} else if($newResult['selection'] == '3') {
				header('Location: '.$newResult['link']);
				exit();
			} else {
				$newResult['ID'] = $result6['ID'];
				return $newResult;
			}
		} else {
			if($result6['selection'] == '2') {
				$newPage = intval($result6['link']);
				getNewPage($newPage);
			} else if($result6['selection'] == '3') {
				header('Location: '.$result6['link']);
				exit();
			} else {
				return $result6;
			}
		}
	}
	
	function toLink($id) {
		global $globals;
		global $db;
		$link = "";
		$parent = -1;
		$items_array = array();
		$result = $db->get($db->query("SELECT parentID, altPrefix FROM cms_menus_items WHERE ID='".$id."'"));
		if($result['parentID']==-1)
			array_push($items_array,$result['altPrefix']);
		else {
			while($parent != 0) {
				$result = $db->get($db->query("SELECT parentID, altPrefix FROM cms_menus_items WHERE ID='".$id."'"));
				$parent = $result['parentID'];
				$id = $parent;
				array_push($items_array,$result['altPrefix']);
			}
		}
		for($i=count($items_array)-1;$i>=0;$i--) {
			$link .= strtolower($items_array[$i])."/";
		}
		return '/'.$link;
	}
	
	//OFFLINE
	$offline=true;
	if($globals->offline=="N") {
		$offline=false;		
	}else if($globals->offline=="Y" && $db->filter('offline') == 'true') {
		$homeResult = $db->query("SELECT ID FROM cms_user WHERE ID='".$db->filter('user')."' AND username='".$db->filter('username')."'");
		if($db->rows($homeResult)>0 && (time()-$db->filter('date')<600)) {
			if(!isset($_COOKIE[hash('sha512', 'offline')]))	
				setcookie(hash('sha512','offline'),base64_encode($db->filter('date')),time()+60*60*8,NULL,NULL,NULL,FALSE);
			$offline=false;
			header( 'Location: http://'.$_SERVER['HTTP_HOST'].'/');
			exit();		
		}
		else
			$offline=true;	
	}
	else if($globals->offline=="Y" && $db->filter('offline') != 'true') {
		if(isset($_COOKIE[hash('sha512', 'offline')]))
				$offline=false;
		else
			$offline=true;
	} else if($globals->offline=="Y") {
		$numRows = $db->rows($db->query("SELECT ID FROM cms_domains_ips WHERE domainID='".$globals->domainID."' AND value='".$user->IP."' AND type='1'"));
		if($numRows>0)
			$offline=false;		
	}
	
	if($globals->offline=="Y") {
		$rows=$db->rows($db->query('SELECT ID FROM cms_domains_ips WHERE domainID="'.$globals->domainID.'" AND value="'.$user->IP.'" AND type="1"'));
		if($rows>0)
			$offline=false;		
	}		
		
	if($offline==true) {
		header( 'Location: '.$offline_link );
		exit();
	}
	
	//ONLINE
	$firstPage = false;
	$page="";
	$specialPage = false;
	$modulID="";
	$modulParam="";
	$menuID="";	
	$currentURL="";
	
	$urlArray=$function->getUrlArray();
	$lang=$function->getLang();
	$langCode=$function->langCode;	
	$isLang=$function->priLang;
	
	if($lang==0) {
		error_log("Language is not set / Path: index.php (135)");
		header('Location: '.$f404_link);
		exit();	
	}	
	
	//Redirect url
	$uriArray=explode("?", $_SERVER['REQUEST_URI']);
	$uri=$uriArray[0];
	$redirect=$db->get($db->query('SELECT destination, type FROM cms_seo_redirects WHERE source="'.$db->filterVar($uri).'" AND domainID="'.$globals->domainID.'" AND lang="'.$lang.'"'));
	if(isset($redirect['destination'])) {
		header("HTTP/1.1 ".$redirect['type']."");
		if(count($uriArray)>1) 
			header("Location: http://".$_SERVER['HTTP_HOST']."".$redirect['destination']."?".$uriArray[1]);
		else
			header("Location: http://".$_SERVER['HTTP_HOST']."".$redirect['destination']."");
			
		exit();
	}
	
	//FIRST PAGE
	if(count($urlArray)==1 && (strlen($urlArray[0])==0 || $urlArray[0]=='index.php' || ($isLang==true && $urlArray[0]==$langCode))) {
		$homeResult = $db->get($db->query("SELECT cms_menus_items.ID, cms_homepage.selection, cms_homepage.link FROM cms_homepage, cms_menus_items WHERE cms_menus_items.parentID='-1' AND cms_menus_items.orderID='-1' AND cms_homepage.lang='".$lang."' AND  cms_homepage.domain='".$globals->domainID."' AND cms_menus_items.link=cms_homepage.ID"));
		$page = $homeResult['ID'];
		if($homeResult['selection'] == 1) {
			$firstPage = true;
		} else {
			$page = $homeResult['link'];
		}
	}
	
	//PAGE
	else {		
		$parentID = -1;
		$done = false;
		
		if($isLang==true) {
			array_shift($urlArray);
		}		
		$tempArray=$urlArray;
		$selection=0;
		$moduleID=0;
		foreach($urlArray as $pageName) {
			$result3n = $db->get($db->query("SELECT cms_menus_items.ID, cms_menus_items.selection, cms_menus_items.menuID  FROM cms_menus_items WHERE cms_menus_items.status='N' AND cms_menus_items.enabled='1' AND cms_menus_items.altPrefix='".$db->filterVar($pageName)."' AND cms_menus_items.parentID='".$parentID."' AND cms_menus_items.domain='".$globals->domainID."' LIMIT 1"));
			if($result3n) {
				$parentID = $result3n['ID'];
				$currentURL=$tempArray[0]."/";
				array_shift($tempArray);
				$selection=$result3n['selection'];
				$modulID=$result3n['menuID'];
				$done=true;
			} else {
				break;
			}
		}
		
		//Special page
		if($selection=="4") {
			$specialPage=true;			
		}
		
		if(!$done) {		
			error_log("Page not found problem for basic page - ".$_SERVER["REQUEST_URI"]." // Path: index.php (197)");
			header('Location: '.$f404_link);
			exit();
		} else {
			if(count($tempArray)>0) {
				$modulParam=implode("/", $tempArray);
			}
			$page = $parentID;	
		}		
	}
	if(!$firstPage) {			
		$query4 = $db->query("SELECT link, template, selection FROM cms_menus_items WHERE enabled='1' AND ID='".$db->filterVar($page)."'");
	} else {			
		$query4 = $db->query("SELECT cms_menus_items.selection, cms_homepage.lang, cms_menus_items.link, cms_homepage.template  FROM cms_menus_items, cms_homepage WHERE cms_menus_items.ID='".$db->filterVar($page)."' AND cms_homepage.ID=cms_menus_items.link");
	}
	
	$result4 = $db->get($query4);
	//Shortcut
	if($result4['selection'] == 2) {
		$templateID=$result6['template'];
	} else {
		$templateID=$result4['template'];
	}
	if($templateID=="0" || $templateID=="") {
		error_log("Template for this page is not set. / Path: index.php (221)");	
		header('Location: '.$f404_link);		
		exit();
	}
	if($result4['selection'] == 2) {
		$page = intval($result4['link']);
		$result6 = getNewPage($page);
		$page = $result6['ID'];
		$query5 = $db->query("SELECT folder FROM cms_template WHERE ID='".$templateID."'");
	} 
	//External link
	else if($result4['selection'] == 3) {
		header('Location: '.$result4['link']);
		exit();
	} 
	//Home page
	else if($result4['selection'] == -1) {		
		$lang=$result4['lang'];
		$query5 = $db->query("SELECT folder FROM cms_template WHERE ID='".$templateID."'");
	} 
	//Navadno
	else {
		$query5 = $db->query("SELECT folder FROM cms_template WHERE ID='".$templateID."'");
	}
	$result5 = $db->get($query5);
	$template->tempName = $result5['folder'];
	$template->pageID = $page;
	$template->langID = $lang;
	$template->firstPage = $firstPage;
	$template->specialPage = $specialPage;
	
	if($firstPage)
		$page=0;
	$_POST['page'] = $page;
	$_POST['lang'] = $lang;	
	$_POST['currentUrl']=$currentURL;
	$_POST['langShort'] = $langCode;
	
	include('includes/local.php');
	
	if($specialPage) {
		$_POST['spID'] = $modulID;
		$_POST['spRID'] = $modulID;
		$_POST['spPage'] = 'true';		
	}
	if(strlen($modulParam)>0) {
		$_POST['modulParam'] = $modulParam;			
	}
	
	//PREVERJANJE OBSTOJA
	if(ob_get_length()>0) {
		ob_end_clean();
	}
	
	if(is_file('templates/'.$globals->domainName.'/'.$template->tempName.'/template.tpl')) {
		$template->setSmarty();
	} else {
		error_log("Template file was not loaded properly. / Path: index.php (278)");
		header('Location: '.$f404_link);
		exit();
	}
?>