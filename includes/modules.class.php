<?php
if( !defined( '_VALID_MIX' ) && !defined( '_VALID_ETT' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}

class Modules extends Shield {
	public $prefix;
	public $id;
	public $title;
	public $layout;
	public $panelID;
	public $tableName;
	public $folderName;
	public $smarty;
	
	public function __construct() {
		global $db, $globals, $user;
		$this->id = $globals->GLOBAL_ID;
		$this->tableName = $globals->GLOBAL_TN;
		$this->folderName = $globals->GLOBAL_FN;
		$setLocaleLang['hr']='';
		$setLocaleLang['en-gb']='en_UK';
		$setLocaleLang['en-us']='en_US';
		$setLocaleLang['en']='english';
		$setLocaleLang['de']='de_DE';
		$setLocaleLang['de-lu']='de_AT';
		$setLocaleLang['it']='';
		$setLocaleLang['ru']='ru_RU';
		$setLocaleLang['sr']='';
		$setLocaleLang['sh']='';
		$setLocaleLang['sl']='sl_SI';
		$setLocaleLang['es']='es-ES';
		$langShort = $db->get($db->query("SELECT short FROM cms_language_front WHERE ID='".$db->filter('lang')."'"));
		$_GET['langShort'] = $langShort['short'];
		setlocale(LC_TIME, $setLocaleLang[$db->filter('langShort')].'.UTF-8');
		$modulInfo=$db->get($db->query("SELECT * FROM ".$this->tableName." WHERE ID='".$this->id."'"));
		$layoutInfo=$db->get($db->query("SELECT * FROM cms_panel_".$modulInfo['cms_layout']." WHERE ID='".$modulInfo['cms_panel_id']."'"));
		$this->prefix = $layoutInfo['prefix'];
		$this->layout = $modulInfo['cms_layout'];
		$this->panelID = $modulInfo['cms_panel_id'];
		$this->title = $layoutInfo['title'];
		$this->specialPage = $layoutInfo['specialPage'];
		$path=SITE_ROOT.SITE_FOLDER.'/modules/'.$globals->domainName.'/'.$this->folderName.'/';
		$cachePath=SITE_ROOT.SITE_FOLDER.'/cache/modules/'.$globals->domainName.'/'.$this->folderName.'/';
		
		/*Language*/
		$this->lang = new Language();
		$this->lang->getLanguage($langShort['short'], $path);
		
		/*Smarty*/
		if(!defined('CACHE_LIFETIME')) {
			define('CACHE_LIFETIME', 60 * 60 * 24 * 7); // secs (60*60*24*7 = 1 week)			
		}
		require_once(SITE_ROOT.SITE_FOLDER.'/Smarty/Smarty.class.php');
		$this->smarty = new Smarty;
		$this->smarty->template_dir = '';
		$this->smarty->inheritance_merge_compiled_includes=true;
		if($user->developer=="1") {
			$this->smarty->caching = 0;
		} else {
			$this->smarty->caching = 2;
		}
		if($layoutInfo['cache']!=-1)
			$this->smarty->cache_lifetime = $layoutInfo['cache']*60;
		$this->smarty->config_dir = SITE_ROOT.SITE_FOLDER.'/Smarty/';
		
		if(!is_dir($cachePath.'templates_c/')) {
			mkdir($cachePath.'templates_c/', PER_FOLDER, true);
			chmod($cachePath.'templates_c/', PER_FOLDER);
		}
		$this->smarty->compile_dir = $cachePath.'templates_c/';
		
		if(!is_dir($cachePath.'cache/')) {
			mkdir($cachePath.'cache/', PER_FOLDER, true);
			chmod($cachePath.'cache/', PER_FOLDER);
		}
		$this->smarty->cache_dir = $cachePath.'cache/';
	
		$this->smarty->_file_perms = PER_FILE;
		
		$this->smarty->assign("moduleLang", $this->lang);
		$this->smarty->assign("moduleImagePath", '/modules/'.$globals->domainName.'/images');
	}
	
	public function getTplUrl($name) {
		global $globals;
		return 'modules/'.$globals->domainName.'/'.$this->folderName.'/html/'.$name;
	}
	
	public function cutText($string, $maxlen) {
		$string=preg_replace ('/<br>/', '##br##',$string);
		$string=preg_replace ('/<br \/>/', '##br##',$string);
		$string=preg_replace ('/<br \/>/', '##br##',$string);
		$string=preg_replace ('/<[^>]*>/', '',$string);
		$string=preg_replace ('/##br##/', '&nbsp; &nbsp;',$string);
		$str = '';
		$foundSpace=0;
		$length = strlen($string);
		for ($i = 0; $i < $length; $i++) {
			if ($i<=$maxlen) {
				$str .= $string{$i};
			} else {
				if($i>$maxlen && $foundSpace==0) {
					$str .= $string{$i};
					if($string{$i}==' ') {
						$str .= '...';
						$foundSpace=1;
					}
				}		
			}      
		}
	   
		return $str;		
	}
	
	public function encodeLink($string) {
		$string=strtolower($string);
		$temp = str_replace('"','%22',$string);
		$temp = str_replace('#','%23',$temp);
		$temp = str_replace('\'','%27',$temp);
		return $temp;		
	}

	public function toLink($id, $paramString=null, $moduleName=null, $param=null) {
		global $globals, $db;
		$parent = -1;
		$link="";
		
		$page=$db->get($db->query('SELECT item.ID, item.link, item.altPrefix, item.parentID, item.alias, item.selection, item.target, item.menuID, langF.short FROM cms_menus_items as item LEFT JOIN cms_menus as menu ON item.menuID=menu.ID LEFT JOIN cms_language_front as langF ON langF.ID=menu.lang WHERE item.ID="'.$id.'"'));
		
		//preverjanje številka jezikov in dodajanje končnice
		$num=$db->rows($db->query('SELECT ID FROM cms_domains_ids WHERE type="lang" AND domainID="'.$globals->domain.'"'));
		if($num>1) {
			if($page['selection']=="4") {
				$lang=$db->get($db->query('SELECT short FROM cms_menus_items as item LEFT JOIN cms_language_front as front ON item.target=front.ID WHERE item.ID="'.$page['ID'].'"'));
				$link=$lang['short'];
			}
			if($page['short']!="")
				$link=$page['short']."/";
		}
		
		//parents		
		$items_array = array();
		if($page['parentID']==-1)
			array_push($items_array,$page['altPrefix']);
		else {
			while($parent != 0) {
				$result = $db->get($db->query("SELECT * FROM cms_menus_items WHERE ID='".$id."'"));
				$parent = $result['parentID'];
				$id = $parent;
				array_push($items_array,$result['altPrefix']);
			}
		}
		for($i=count($items_array)-1;$i>=0;$i--) {
			if($items_array[$i]!="" && $items_array[$i]!=null)
				$link .= $items_array[$i]."/";
		}
		
		//parameters
		if($paramString!=null) {
			$link.= $paramString;
		} 
		else if($param!=null && $moduleName!=null) {
			if(is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$globals->domainName.'/'.$moduleName.'/seo.php')) {
				require_once($_SERVER['DOCUMENT_ROOT'].'/modules/'.$globals->domainName.'/'.$moduleName.'/seo.php');
				$function=$moduleName.'\\getSEO';
				if(function_exists($function)) {					
					$link.= $this->encodeLink($function($param, $page['alias']));
				}
			}
		}
		
		return "/".$link;
	}
	
	public function splitImagename ($path) {
		$new=array();
		$new[0]="";
		$new[1]="";
		if(strlen($path)>4) {
			$array = explode('.',$path);
			$ending = $array[count($array)-1];
			$nameFull="";			
			for($i=0; $i<count($array)-1; $i++) {				
				if(count($array)-2 == $i)
					$nameFull.=$array[$i];
				else
					$nameFull.=$array[$i].'.';			
			}
			$new[0]=$nameFull;
			$new[1]='.'.$ending;
		}
		return $new;
	}
	
	public function findPage($id, $baza, $findID) {
		global $db;
		$query=$db->get($db->query('SELECT cms_panel_id, cms_layout FROM '.$baza.' WHERE '.$findID.'='.$id.' LIMIT 1'));
		if($query['cms_panel_id'] != "") {
			$query=$db->get($db->query('SELECT pageID FROM cms_panel_'.$query['cms_layout'].' WHERE ID="'.$query['cms_panel_id'].'"'));
			return $query['pageID'];
		}
		else {
			return 1;
		}
	}
	
	public function stripTags($str, $tags, $stripContent=false) {
		$content = '';
		if(!is_array($tags)) {
			$tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
			if(end($tags) == '') array_pop($tags);
		}
		foreach($tags as $tag) {
			if ($stripContent)
				 $content = '(.+</'.$tag.'(>|\s[^>]*>)|)';
			 $str = preg_replace('#</?'.$tag.'(>|\s[^>]*>)'.$content.'#is', '', $str);
		}
		return $str;
	}
	
	function getPrefixTitle($niz, $table, $colum) {
		global $db;
		$findEmpty = array('&quot;','!','@','#','$','%','^','&','*','(',')','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','.','/','*','+','~','`','=', '--', "»", "«");
		$findUnder=array(' ', '__');
		$niz = str_replace($findUnder, '-', $niz);
		$niz = str_replace('---', '-', $niz);
		$niz = str_replace('--', '-', $niz);
		$niz = str_replace($findEmpty, '', $niz);
		$niz = str_replace('č', 'c', $niz);
		$niz = str_replace('ć', 'c', $niz);
		$niz = str_replace('š', 's', $niz);
		$niz = str_replace('ž', 'z', $niz);
		
		
		$check=$db->query('SELECT ID FROM '.$table.' WHERE '.$colum.'="'.$niz.'"');
		if($db->rows($check)>0) {
			$stevilo=1;
			while(true) {
				$check=$db->query('SELECT ID FROM '.$table.' WHERE '.$colum.'="'.$niz.'_'.$stevilo.'"');
				if($db->rows($check)>0) {
					$stevilo++;
				}
				else {
					$niz=$niz.'_'.$stevilo;
					break;
				}
				if($stevilo>10) {
					$niz=$niz.time();
					break;
				}		
			}
		}
		
		$niz=strtolower($niz);
		return $niz;
	}
	
	function addGoogleTracking($string) {
		global $db;
		if($db->is('googleTracking')) {
			$_POST['googleTracking']=$_POST['googleTracking'].$string;			
		} else {
			$_POST['googleTracking']=$string;
		}
	}
	
	function redirect404() {
		header('Location: '.SITE_FOLDER.'/404/');
	}
}
?>