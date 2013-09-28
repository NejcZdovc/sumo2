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
	
	private $title = '';
	private $modul = '';
	private $id = 0;
	private $tableName = '';
	private $folderName = '';
	private $language = null;	
	
    public function panel($className) {
		global $db, $template, $globals;
		$check = $db->query('SELECT ID FROM cms_template_position WHERE prefix="'.$className.'" AND domain="'.$globals->domainID.'"');
		if($db->rows($check)>0) {
			$query1 = $db->query("SELECT * FROM cms_panel_".$className." WHERE pageID='".$this->pageID."' AND lang='".$this->langID."' AND domain='".$globals->domainID."' ORDER BY orderID ASC");
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
					$this->id = $result3['ID'];
					$this->tableName = $result2['editTable'];
					$this->folderName = $result2['moduleName'];
					echo "<div id=\"".$result2['moduleName']."_".$this->id."\">\n";
					$this->title = $result1['title'];
					$this->modul = SITE_ROOT.SITE_FOLDER.DS.'modules/'.$globals->domainName.'/'.$result2['moduleName'].'/index.php';
					$globals->GLOBAL_ID = $this->id;
					$globals->GLOBAL_TN = $this->tableName;
					$globals->GLOBAL_FN = $this->folderName;
					if(is_file($this->modul)) {
						include($this->modul);
					} else {
						error_log("Modul is missing: ".$result2['moduleName']);
						die("Modul is missing: ".$result2['moduleName']);
					}
					echo "</div>\n";
				}
			}
		}
		else {
			error_log('Template position '.$className.' does not exist, please create it in CMS.');
			die('Template position '.$className.' does not exist, please create it in CMS.');
		}
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
		//specail page title
		if($db->is('spPage')) {
			$module = $db->get($db->query("SELECT moduleName FROM cms_modules_def WHERE ID='".$db->filter('spRID')."'"));
			if(is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$globals->domainName.'/'.$module['moduleName'].'/seo.php')) {
				require_once($_SERVER['DOCUMENT_ROOT'].'/modules/'.$globals->domainName.'/'.$module['moduleName'].'/seo.php');
				$function=$module['moduleName'].'_getTitleSP';
				if($db->is('modulParam'))
					$param=$db->filter('modulParam');
				else
					$param="";
				if(function_exists($function)) {		
					$link .= $function($param, $db->filter('spID')).' - ';
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
			$page = $this->pageID;
				$result = $db->get($db->query("SELECT * FROM cms_menus_items WHERE ID='".$page."'"));
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
		$modArray = array();
		$query1 = $db->query("SELECT * FROM cms_template_position WHERE domain='".$globals->domainID."'");
		$result_glob = $db->get($db->query("SELECT * FROM cms_global_settings WHERE domain='".$globals->domainID."'"));
		while($result1 = $db->fetch($query1)) {
			$query2 = $db->query("SELECT DISTINCT modulID FROM cms_panel_".$result1['prefix']." WHERE pageID='".$this->pageID."' AND lang='".$this->langID."' AND domain='".$globals->domainID."'");
			while($result2 = $db->fetch($query2)) {
				$result3 = $db->get($db->query("SELECT * FROM cms_modules_def WHERE ID='".$result2['modulID']."' AND enabled='1' AND status='N'"));
				$modArray[$result3['moduleName']]= $result2['modulID'];
			}
		}
		$pageURL = 'http';
		 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 	$pageURL .= "://";
		 $pageURL .= $_SERVER["SERVER_NAME"];
		if($user->developer=="1") {
			echo "<script type=\"text/javascript\" src=\"".$pageURL."/min/?g=js&amp;a=".$this->tempName."&amp;b=".implode("-",$modArray)."&amp;debug=1\"></script>\n";
		} else {
			echo "<script type=\"text/javascript\" src=\"".$pageURL."/min/?g=js&amp;a=".$this->tempName."&amp;b=".implode("-",$modArray)."&amp;".$globals->cacheNumber."\"></script>\n";
		}
		
		if($result_glob['GA_enabled'] == 1) {
			if($result_glob['GA_type'] == 1) {
				echo "<script type=\"text/javascript\">
				 var _gaq = _gaq || [];
				 _gaq.push(['_setAccount', '".$result_glob['GA_ID']."']);
				 _gaq.push(['_setDomainName', '".preg_replace("/^(.*\.)?([^.]*\..*)$/", "$2", $_SERVER['HTTP_HOST'])."']); 
				 _gaq.push(['_trackPageview']);";
				if($db->is('googleTracking')) {
					echo str_replace("\'", "'", $db->filter('googleTracking'));
				}
				echo " (function() {
					var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				 })();
				</script>";
			} else if($result_glob['GA_type'] == 2) {
				echo "<script type=\"text/javascript\">
				 var _gaq = _gaq || [];
				 _gaq.push(['_setAccount', '".$result_glob['GA_ID']."']);
				 _gaq.push(['_setDomainName', '".preg_replace("/^(.*\.)?([^.]*\..*)$/", "$2", $_SERVER['HTTP_HOST'])."']); 
				 _gaq.push(['_setAllowLinker', true]);
				 _gaq.push(['_trackPageview']);";
				if($db->is('googleTracking')) {
					echo str_replace("\'", "'", $db->filter('googleTracking'));
				}
				echo "(function() {
					var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				 })();
				</script>";
			}
		}
	}
	
	public function head() {
		global $db, $user, $globals, $crypt;
		$result_lang = $db->get($db->query("SELECT * FROM cms_language_front WHERE ID='".$this->langID."'"));
		$result_page = $db->get($db->query("SELECT * FROM cms_menus_items WHERE ID='".$this->pageID."'"));
		$description = $result_page['description'];
		$keywords = $result_page['keyword'];
		$result_glob = $db->get($db->query("SELECT * FROM cms_global_settings WHERE domain='".$globals->domainID."'"));
		$keywords="";
		$description="";
		if($db->is('spPage')) {
			$module = $db->get($db->query("SELECT moduleName FROM cms_modules_def WHERE ID='".$db->filter('spRID')."'"));
			if(is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$globals->domainName.'/'.$module['moduleName'].'/seo.php')) {
				require_once($_SERVER['DOCUMENT_ROOT'].'/modules/'.$globals->domainName.'/'.$module['moduleName'].'/seo.php');			
				if($db->is('modulParam'))
					$param=$db->filter('modulParam');
				else
					$param="";
				$function='GetKeywords';
				if(function_exists($function)) {
					$k=$function($param, $db->filter('spID'));
					if(strlen($k)>0) {		
						$keywords=$k;
					}
				}
				$function='GetDescription';
				if(function_exists($function)) {
					$d=$function($param, $db->filter('spID'));
					if(strlen($d)>0) {	
						$description=$d;
					}
				}
			}
		} 
		$homepageTitle = $db->get($db->query("SELECT altTitle, title, keyword, description FROM cms_homepage WHERE lang='".$this->langID."' AND domain='".$globals->domainID."'"));
		$keywords=$homepageTitle['keyword'];
		$description=$homepageTitle['description'];
		$title=$homepageTitle['altTitle'];
		if(strlen($title)<2)
			$title = $result_glob['title'];
		if(strlen($keywords)<2) 
			$keywords=$result_glob['keywords'];
		if(strlen($description)<2)
			$description=$result_glob['description'];
		echo "<meta name=\"description\" content=\"".$description."\" />\n<meta name=\"keywords\" content=\"".$keywords."\" />\n<meta name=\"author\" content=\"3Z Sistemi\"/>\n<meta name=\"robots\" content=\"index, follow\"/>\n<meta name=\"revisit-after\" content=\"1 days\"/>\n<meta name=\"language\" content=\"".$result_lang['name']."\" />\n<meta http-equiv=\"Content-Language\" content=\"".$result_lang['short']."\"/>\n<meta name=\"copyright\" content=\"3Z Sistemi\" />\n<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />\n";
		if($result_glob['WM_enabled'] == 1) {
			echo "<meta name=\"google-site-verification\" content=\"".$result_glob['WM_ID']."\" />\n";
		}
		if($result_glob['display_title'] == 'F') {
			echo "<title>".$title."</title>\n";
		} else if($this->firstPage) {
			echo "<title>".$homepageTitle['title']."</title>\n";
		} else {
			echo "<title>".$this->getTitle($title)."</title>\n";
		}
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			$pageURL .= "://";
		$pageURL .= $_SERVER["SERVER_NAME"];
		
		$modArray = array();
		$query1 = $db->query("SELECT * FROM cms_template_position WHERE domain='".$globals->domainID."'");
		while($result1 = $db->fetch($query1)) {
			$query2 = $db->query("SELECT DISTINCT modulID FROM cms_panel_".$result1['prefix']." WHERE pageID='".$this->pageID."' AND lang='".$this->langID."' AND domain='".$globals->domainID."'");
			while($result2 = $db->fetch($query2)) {
				$query3 = $db->get($db->query("SELECT * FROM cms_modules_def WHERE ID='".$result2['modulID']."' AND enabled='1' AND status='N'"));
				$modArray[$query3['moduleName']]= $result2['modulID'];
			}
		}
		if($user->developer=="1") {
			echo "<link type=\"text/css\" rel=\"stylesheet\" href=\"".$pageURL."/min/?g=css&amp;a=".$this->tempName."&amp;b=".implode("-",$modArray)."&amp;debug=1\" />\n";
		} else {
			echo "<link type=\"text/css\" rel=\"stylesheet\" href=\"".$pageURL."/min/?g=css&amp;a=".$this->tempName."&amp;b=".implode("-",$modArray)."&amp;".$globals->cacheNumber."\" />\n";
		}
	}
}

$template = new Template();
?>