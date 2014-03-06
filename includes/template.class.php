<?php
if( !defined( '_VALID_MIX' ) && !defined( '_VALID_ETT' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Template {
	public $tempName = '';
	public $langID = 0;
	public $pageID = 0;
	public $firstPage = false;
	public $specialPage = false;
	
	public $title = '';
	public $modul = '';
	public $id = 0;
	public $tableName = '';
	public $folderName = '';
	public $language = null;	
	
	public $smarty;
	
	public function setSmarty() {
		global $globals, $user,$db;
		$path=SITE_ROOT.SITE_FOLDER.'/templates/'.$globals->domainName.'/'.$this->tempName.'/';
		$cachepath=SITE_ROOT.SITE_FOLDER.'/cache/templates/'.$globals->domainName.'/'.$this->tempName.'/';
		/*Smarty*/
		if(!defined('CACHE_LIFETIME')) {
			define('CACHE_LIFETIME', 60 * 60 * 24 * 7); // secs (60*60*24*7 = 1 week)			
		}
		
		require_once(SITE_ROOT.SITE_FOLDER.'/Smarty/Smarty.class.php');	
		require_once(SITE_ROOT.SITE_FOLDER.'/Smarty/plugins/function.sumo_panel.php');
			
		$this->smarty = new Smarty;
		$this->smarty->registerPlugin('function', 'panel', 'sumo_panel');
		$this->smarty->template_dir = '';
		$this->smarty->caching = 0;		
			
		$this->smarty->config_dir = SITE_ROOT.SITE_FOLDER.'/Smarty/';
		
		if(!is_dir($cachepath.'templates_c/')) {
			mkdir($cachepath.'templates_c/', PER_FOLDER, true);
			chmod($cachepath.'templates_c/', PER_FOLDER);
		}
		$this->smarty->compile_dir = $cachepath.'templates_c/';
		
		if(!is_dir($cachepath.'cache/')) {
			mkdir($cachepath.'cache/', PER_FOLDER, true);
			chmod($cachepath.'cache/', PER_FOLDER);
		}
		$this->smarty->cache_dir = $cachepath.'cache/';
	
		$this->smarty->_file_perms = PER_FILE;			
		
		$this->smarty->assign('head', $this->head());
		$this->smarty->assign('footer', $this->footer());
		$this->smarty->assign('page', $db->filter('page'));
		$this->smarty->assign('templateName', $this->tempName);
		$this->smarty->assign('domain', $globals->domainName);
		$this->smarty->assign('lang', $db->filter('lang'));
		$this->smarty->assign('langShort', $db->filter('langShort'));
		$this->smarty->assign('specialPage', $this->specialPage);
		$this->smarty->assign('firstPage', $this->firstPage);
		
		$this->smarty->display($path.'template.tpl');
	}
	
	public function title() {
		echo $this->title;
	}
	
	
	public function modul() {
		global $globals;
		if(is_file($this->modul)) {
			$globals->GLOBAL_ID = $this->id;
			$globals->GLOBAL_TN = $this->tableName;
			$globals->GLOBAL_FN = $this->folderName;
			include($this->modul);	
		} else {
			error_log("Modul is missing: ".$this->modul);
			die("Modul is missing: ".$this->modul);	
		}
	}
	
	private function getTitle($begin) {
		global $db,$globals;
		$link="";
		$customModuleID=$db->get($db->query('SELECT ID, moduleID, alias FROM cms_menus_items WHERE ID="'.$this->pageID.'"'));
		//specail page title
		if($db->is('spPage') || ($customModuleID && $customModuleID['moduleID']!="-1")) {
			$spID=$db->filter('spRID');
			if($customModuleID && $customModuleID['moduleID']!="-1") {
				$spID=$customModuleID['moduleID'];
			}
			$module = $db->get($db->query("SELECT moduleName FROM cms_modules_def WHERE ID='".$spID."'"));
			if(is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$globals->domainName.'/'.$module['moduleName'].'/seo.php')) {
				require_once($_SERVER['DOCUMENT_ROOT'].'/modules/'.$globals->domainName.'/'.$module['moduleName'].'/seo.php');
				if($db->is('modulParam'))
					$param=$db->filter('modulParam');
				else
					$param="";
				$function=$module['moduleName'].'\\getTitle';
				if(function_exists($function)) {
					if($customModuleID['alias']=="") {
						$link .= $function($param, $customModuleID['ID']).' - ';
					} else {
						$link .= $function($param, $customModuleID['alias']).' - ';
					}
				}else {
					$link = '';
				}
			}
			if(strlen($link)<4) {
				$query_page = $db->get($db->query("SELECT title FROM cms_menus_items WHERE ID='".$this->pageID."'"));
				$link=$query_page['title'].' - ';
			}
			return $link.$begin;			
		}
		//ordinary
		else {
			$titleArray = array();
			array_push($titleArray,$begin);
			$title = '';
			$result = $db->get($db->query("SELECT * FROM cms_menus_items WHERE ID='".$this->pageID."'"));
			$curTitle = $result['title'];
			array_push($titleArray,$curTitle); 
			for($i = count($titleArray)-1;$i>=0;$i--) {
				$title .= $titleArray[$i];
				if(strlen($title) > 0 && $i != 0) {
					$title .= ' - ';	
				}
			}
		}
		return $title;
	}

	public function footer() {
		global $db, $user, $globals;
		$result="";
		$modArray = array();
		$query1 = $db->query("SELECT * FROM cms_template_position WHERE domain='".$globals->domainID."'");
		$result_glob = $db->get($db->query("SELECT * FROM cms_global_settings WHERE domain='".$globals->domainID."'"));
		while($result1 = $db->fetch($query1)) {
			$query2 = $db->query("SELECT DISTINCT modulID, moduleName FROM cms_panel_".$result1['prefix']." as panel LEFT JOIN cms_modules_def as def ON panel.modulID=def.ID WHERE panel.pageID='".$this->pageID."' AND panel.lang='".$this->langID."' AND panel.domain='".$globals->domainID."' AND def.enabled='1' AND def.status='N'");
			while($result2 = $db->fetch($query2)) {
				$modArray[$result2['moduleName']]= $result2['modulID'];
			}
		}
		$pageURL = 'http';
		 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 	$pageURL .= "://";
		 $pageURL .= $_SERVER["SERVER_NAME"];
		if($user->developer=="1") {
			$result.="<script type=\"text/javascript\" src=\"".$pageURL."/min/?g=js&amp;a=".$this->tempName."&amp;b=".implode("-",$modArray)."&amp;debug=1\"></script>\n";
		} else {
			$result.="<script type=\"text/javascript\" src=\"".$pageURL."/min/?g=js&amp;a=".$this->tempName."&amp;b=".implode("-",$modArray)."&amp;".$globals->cacheNumber."\"></script>\n";
		}
		
		if($result_glob['GA_enabled'] == 1) {
			if($result_glob['GA_type'] == 1) {
				$result.= "<script type=\"text/javascript\">
				 var _gaq = _gaq || [];
				 _gaq.push(['_setAccount', '".$result_glob['GA_ID']."']);
				 _gaq.push(['_setDomainName', '".str_replace("www.", "", $_SERVER['HTTP_HOST'])."']); 
				 _gaq.push(['_trackPageview']);";
				if($db->is('googleTracking')) {
					$result.= str_replace("\'", "'", $db->filter('googleTracking'));
				}
				$result.= " (function() {
					var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				 })();
				</script>";
			} else if($result_glob['GA_type'] == 2) {
				$result.= "<script type=\"text/javascript\">
				 var _gaq = _gaq || [];
				 _gaq.push(['_setAccount', '".$result_glob['GA_ID']."']);
				 _gaq.push(['_setDomainName', '".str_replace("www.", "", $_SERVER['HTTP_HOST'])."']); 
				 _gaq.push(['_setAllowLinker', true]);
				 _gaq.push(['_trackPageview']);";
				if($db->is('googleTracking')) {
					$result.= str_replace("\'", "'", $db->filter('googleTracking'));
				}
				$result.= "(function() {
					var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				 })();
				</script>";
			}
		}
		return $result;
	}
	
	public function head() {
		global $db, $user, $globals, $crypt;
		$result="";
        $title="";
		$result_lang = $db->get($db->query("SELECT * FROM cms_language_front WHERE ID='".$this->langID."'"));
		$result_page = $db->get($db->query("SELECT * FROM cms_menus_items WHERE ID='".$this->pageID."'"));
		$description = $result_page['description'];
		$keywords = $result_page['keyword'];
		$result_glob = $db->get($db->query("SELECT * FROM cms_global_settings WHERE domain='".$globals->domainID."'"));
		$keywords="";
		$description="";
		$customHead="";
		$customModuleID=$db->get($db->query('SELECT moduleID FROM cms_menus_items WHERE ID="'.$this->pageID.'"'));
		if($db->is('spPage') || ($customModuleID && $customModuleID['moduleID']!="-1")) {
			$spID=$db->filter('spRID');
			if($customModuleID && $customModuleID['moduleID']!="-1") {
				$spID=$customModuleID['moduleID'];
			}
			$module = $db->get($db->query("SELECT moduleName FROM cms_modules_def WHERE ID='".$spID."'"));
			if(is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$globals->domainName.'/'.$module['moduleName'].'/seo.php')) {
				require_once($_SERVER['DOCUMENT_ROOT'].'/modules/'.$globals->domainName.'/'.$module['moduleName'].'/seo.php');			
				if($db->is('modulParam')) {
					$param=$db->filter('modulParam');
				}
				else
					$param="";
				
				$function=$module['moduleName'].'\\getKeywords';
				if(function_exists($function)) {
					$k=$function($param, $db->filter('spID'));
					if(strlen($k)>0) {		
						$keywords=$k;
					}
				}
				$function=$module['moduleName'].'\\getDescription';
				if(function_exists($function)) {	
					$d=$function($param, $db->filter('spID'));
					if(strlen($d)>0) {	
						$description=$d;
					}
				}
				$function=$module['moduleName'].'\\getCustom';
				if(function_exists($function)) {	
					$customHead=$function($param, $db->filter('spID'));
				}
			}
		} 
		$homepageTitle = $db->get($db->query("SELECT altTitle, title, keyword, description FROM cms_homepage WHERE lang='".$this->langID."' AND domain='".$globals->domainID."'"));
        if($homepageTitle) {
            $keywords=$homepageTitle['keyword'];
            $description=$homepageTitle['description'];
            $title=$homepageTitle['altTitle'];
        }
        
		if(strlen($title)<2){
			$title = $result_glob['title'];
		}
		if(strlen($keywords)<2) {
			$keywords=$result_glob['keywords'];
		}
		if(strlen($description)<2) {
			$description=$result_glob['description'];
		}
            
		$result.="<meta name=\"description\" content=\"".$description."\" />\n<meta name=\"keywords\" content=\"".$keywords."\" />\n<meta name=\"author\" content=\"3Z Sistemi\"/>\n<meta name=\"robots\" content=\"index, follow\"/>\n<meta name=\"revisit-after\" content=\"1 days\"/>\n<meta name=\"language\" content=\"".$result_lang['name']."\" />\n<meta http-equiv=\"Content-Language\" content=\"".$result_lang['short']."\"/>\n<meta name=\"copyright\" content=\"3Z Sistemi\" />\n<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\n<meta name=\"generator\" content=\"SUMO 2 CMS\" />\n";
		if($result_glob['WM_enabled'] == 1) {
			$result.= "<meta name=\"google-site-verification\" content=\"".$result_glob['WM_ID']."\" />\n";
		}
		if(strlen($customHead)>0) {
				$result.= $customHead;
		}
		if($result_glob['display_title'] == 'F') {
			$result.= "<title>".$title."</title>\n";
		} else if($this->firstPage) {
			$result.= "<title>".$homepageTitle['title']."</title>\n";
		} else {
			$result.= "<title>".$this->getTitle($title)."</title>\n";
		}
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			$pageURL .= "://";
		$pageURL .= $_SERVER["SERVER_NAME"];
		
		$modArray = array();
		$query1 = $db->query("SELECT * FROM cms_template_position WHERE domain='".$globals->domainID."'");
		while($result1 = $db->fetch($query1)) {
			$query2 = $db->query("SELECT DISTINCT panel.modulID, def.moduleName FROM cms_panel_".$result1['prefix']." as panel LEFT JOIN cms_modules_def as def ON panel.modulID=def.ID WHERE panel.pageID='".$this->pageID."' AND panel.lang='".$this->langID."' AND panel.domain='".$globals->domainID."' AND def.enabled='1' AND def.status='N'");
			while($result2 = $db->fetch($query2)) {
				$modArray[$result2['moduleName']]= $result2['modulID'];
			}			
		}
		if($user->developer=="1") {
			$result.= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".$pageURL."/min/?g=css&amp;a=".$this->tempName."&amp;b=".implode("-",$modArray)."&amp;debug=1\" />\n";
		} else {
			$result.= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".$pageURL."/min/?g=css&amp;a=".$this->tempName."&amp;b=".implode("-",$modArray)."&amp;".$globals->cacheNumber."\" />\n";
		}
		return $result;
	}
}

$template = new Template();
?>