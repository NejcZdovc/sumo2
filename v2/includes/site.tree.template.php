<? 
	define( '_VALID_MOS', 1 );
	define( '_VALID_EXT', 1 );
	require_once('../configs/settings.php'); 
	require_once('../includes/errors.php');
	require_once('../essentials/security.class.php');
	require_once('../essentials/database.class.php');
	require_once('../essentials/xml.class.php');
	require_once('../essentials/template.class.php'); 
	require_once('../essentials/cryptography.class.php');
	require_once('../essentials/cookie.class.php');
	require_once('../essentials/session.class.php');
	require_once('../essentials/language.class.php');
	require_once('../essentials/user.class.php');
	
	
	
	if(isset($_GET['menu'])) {
		$menuID = $db->filter('menu');;
	} else {
		$menuID = 1;
	}
	if(isset($_GET['lang_sel'])) {
		$langID = $db->filter('lang_sel');	
	} else {
		$langID = 1;
	}
	if(isset($_GET['temp'])) {
		$tempID = $db->filter('temp');
		if($tempID == 0) {
			$tempID = 1;	
		}
	} else {
		$tempID = 1;
	}
	$layout = 'foo';
	if(isset($_GET['layout'])) {
		$lay_set = $db->filter('layout');
		if($lay_set == 'ok') {
			$layout = true;
		} else {
			$layout = false;
		}
	} else {
		$layout = false;
	}
	$templateResult = $db->get($db->query("SELECT * FROM cms_template WHERE ID='".$tempID."' AND enabled='1' AND status='N'"));
	$template->tempName = $templateResult['folder'];
	$template->menuID = $menuID;
	$template->langID = $langID;
	$template->tempID = $tempID;
	if($templateResult && $layout == false) 
	{
			
		$path='../../templates/'.$user->domainName.'/'.$templateResult['folder'].'/';
		/*Smarty*/
		if(!defined('CACHE_LIFETIME')) {
			define('CACHE_LIFETIME', 60 * 60 * 24 * 7); // secs (60*60*24*7 = 1 week)			
		}
		require_once(SITE_ROOT.SITE_FOLDER.'/Smarty/Smarty.class.php');	
		require_once(SITE_ROOT.SITE_FOLDER.'/Smarty/plugins/function.sumo_admin_panel.php');
			
		$smarty = new Smarty;
		$smarty->registerPlugin('function', 'panel', 'sumo_admin_panel');
		$smarty->template_dir = '';
		if($user->developer=="1") {
			$smarty->caching = 0;
		} else {
			$smarty->caching = 2;
			$smarty->cache_lifetime = 4*24*60*60;
		}
			
		$smarty->config_dir = SITE_ROOT.SITE_FOLDER.'/Smarty/';
		
		if(!is_dir($path.'templates_c/')) {
			mkdir($path.'templates_c/', PER_FOLDER);
			chmod($path.'templates_c/', PER_FOLDER);
		}
		$smarty->compile_dir = $path.'templates_c/';
		
		if(!is_dir($path.'cache/')) {
			mkdir($path.'cache/', PER_FOLDER);
			chmod($path.'cache/', PER_FOLDER);
		}
		$smarty->cache_dir = $path.'cache/';
	
		$smarty->_file_perms = PER_FILE;			
		
		$smarty->assign('head', $template->head());
		$smarty->assign('footer', "");
		$smarty->display($path.'template.tpl');	
	} 
	else if($templateResult && $layout == true) {
		$groupArray = array();
		$content = file_get_contents('../../templates/'.$user->domainName.'/'.$templateResult['folder'].'/template.tpl');
		$firstArray = explode("{panel",$content);
		for($i=count($firstArray)-1;$i>=0;$i--) {
			
			$posEnd = strpos($firstArray[$i],"'}");
			$removeString = substr($firstArray[$i],$posEnd);
			$params = str_replace("' row='",",",$firstArray[$i]);
			$params = str_replace($removeString,"",$params);
			$params = str_replace("id='","",$params);
			$params = str_replace(" ", "", $params);
			if(strlen($params)>1) {
				$secondArray = explode(",",$params);
				if(count($secondArray) == 2) {
					$group = $secondArray[1];
					$groupArray[$group][] = $secondArray[0];
				} else {
					array_push($groupArray,$secondArray[0]);
				}
			}
		}
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<script type="text/javascript" src="../scripts/site.tree.js"></script>
		<link type="text/css" rel="stylesheet" href="../css/site.tree.css" />
		<script type="text/javascript">st.PAGE='.$template->menuID.';st.LANG='.$template->langID.';</script>
		</head>
		<body style="height:100%;width:100%;padding:0px;margin:0px;">
		<table style="width:100%;height:100%;">';
		$tableArray = array();
		foreach($groupArray as $item) {
			$tr = '<tr style="padding:0px;margin:0px;width:100%;">';
			if(is_array($item)) {
				$tr .= '<td style="padding:0px;margin:0px;height:100%;width:100%;vertical-align:top;"><table style="padding:0px;margin:0px;width:100%;height:100%;"><tr style="width:100%;height:100%;">';
				for($j=count($item)-1;$j>=0;$j--) {
					$tr .= '<td class="sitetree_td" style="background:white;width:'.(floor(100/count($item))).'%;height:100%;vertical-align:top;">';
					$tr .= '<div style="color:#a3a3a3;font-size:12px;">['.$item[$j].']</div>';					
					$tr .= '<div class="site-tree-layout" id="'.$item[$j].'" style="padding-bottom:20px;width:100%;min-height:100px;">';
					$query = $db->query("SELECT * FROM cms_panel_".$item[$j]." WHERE pageID='".$template->menuID."' AND lang='".$template->langID."' AND domain='".$user->domain."' ORDER BY orderID ASC");
					if($db->rows($query) > 0) {
						while($result = $db->fetch($query)) {
							$result2 = $db->get($db->query("SELECT * FROM cms_modules_def WHERE ID='".$result['modulID']."'"));
							//modul data
							$result3 = $db->get($db->query("SELECT * FROM ".$result2['editTable']." WHERE cms_panel_id='".$result['ID']."' AND cms_layout='".$item[$j]."'"));						
							$classEnable=$result3['cms_enabled']? "enable":"disable";
							if($result2['editName'] == '') {
								$edit_button = '';
							} else {
								$edit_button = '<div class="icon-edit sumo2-tooltip" title="'.$lang->MOD_205.'"  onclick="parent.sumo2.dialog.NewDialog(\''.$result2['editName'].'\',\'layout='.$item[$j].'$!$panel='.$result['ID'].'\');"></div>';
							}
							if(is_file('../modules/'.$result2['moduleName'].'/small.jpg')) {
								$icon = '<img style="float:left;display:block;margin-top:2px;" src="../modules/'.$result2['moduleName'].'/small.jpg" alt="icon" />';
							} else if(is_file('../modules/'.$result2['moduleName'].'/small.png')) {
								$icon = '<img style="float:left;display:block;margin-top:2px;" src="../modules/'.$result2['moduleName'].'/small.png" alt="icon" />';
							} else if(is_file('../modules/'.$result2['moduleName'].'/small.gif')) {
								$icon = '<img style="float:left;display:block;margin-top:2px;" src="../modules/'.$result2['moduleName'].'/small.gif" alt="icon" />';
							} else {
								$icon = '';
							}
							$tr .= '<div class="module-wrapper"><div id="'.$result['ID'].'#'.$item[$j].'" class="modules-template-item" onmouseover="st.ToggleButtons(this,\'S\')" onmouseout="st.ToggleButtons(this,\'H\')">'.$icon.'<span style="display:inline-block;margin-left:5px;">'.$result['title'].'</span><span style="color:#a3a3a3;font-size:12px;"> ['.$result['prefix'].']</span><div class="modules-template-options" style="visibility: hidden;"><div title="'.$lang->ARTICLE_9.'" class="'.$classEnable.' sumo2-tooltip" onclick="parent.sumo2.siteTree.ChangeStatusModule(\''.$crypt->encrypt($result3['ID']).'\', \''.$result2['editTable'].'\')"></div>'.$edit_button.'<div class="icon-rename sumo2-tooltip" title="'.$lang->MOD_206.'" onclick="parent.sumo2.dialog.NewDialog(\'d_site_tree_rename\',\'layout='.$item[$j].'$!$panel='.$result['ID'].'\');"></div><div class="icon-delete sumo2-tooltip" title="'.$lang->MOD_121.'" onclick="parent.sumo2.siteTree.RemoveModule(\''.$item[$j].'\',\''.$crypt->encrypt($result['ID']).'\')"></div></div></div></div>';	
						}
					}
					$tr .= '</div>';
					$tr .= '</td>';
				}
				$tr .= '</tr></table></td>';
			} else {
				$tr .= '<td class="sitetree_td" style="background:white;height:100%;width:100%;vertical-align:top;">';
				$tr .= '<div style="color:#a3a3a3;font-size:12px;">['.$item.']</div>';
				$tr .= '<div class="site-tree-layout" id="'.$item.'" style="padding-bottom:20px;width:100%;min-height:100px;">';
				$query = $db->query("SELECT * FROM cms_panel_".$item." WHERE pageID='".$template->menuID."' AND lang='".$template->langID."' AND domain='".$user->domain."' ORDER BY orderID ASC");
				if($db->rows($query) > 0) {
					while($result = $db->fetch($query)) {
						$result2 = $db->get($db->query("SELECT * FROM cms_modules_def WHERE ID='".$result['modulID']."'"));
						//modul data
						$result3 = $db->get($db->query("SELECT * FROM ".$result2['editTable']." WHERE cms_panel_id='".$result['ID']."' AND cms_layout='".$item."'"));						
						$classEnable=$result3['cms_enabled']? "enable":"disable";
						if($result2['editName'] == '') {
							$edit_button = '';
						} else {
							$edit_button = '<div class="icon-edit sumo2-tooltip" title="'.$lang->MOD_205.'"  onclick="parent.sumo2.dialog.NewDialog(\''.$result2['editName'].'\',\'layout='.$item.'$!$panel='.$result['ID'].'\');"></div>';
						}
						if(is_file('../modules/'.$result2['moduleName'].'/small.jpg')) {
								$icon = '<img style="float:left;display:block;margin-top:2px;" src="../modules/'.$result2['moduleName'].'/small.jpg" alt="icon" />';
							} else if(is_file('../modules/'.$result2['moduleName'].'/small.png')) {
								$icon = '<img style="float:left;display:block;margin-top:2px;" src="../modules/'.$result2['moduleName'].'/small.png" alt="icon" />';
							} else if(is_file('../modules/'.$result2['moduleName'].'/small.gif')) {
								$icon = '<img style="float:left;display:block;margin-top:2px;" src="../modules/'.$result2['moduleName'].'/small.gif" alt="icon" />';
							} else {
								$icon = '';
							}
						$tr .= '<div class="module-wrapper"><div id="'.$result['ID'].'#'.$item.'" class="modules-template-item" onmouseover="st.ToggleButtons(this,\'S\')" onmouseout="st.ToggleButtons(this,\'H\')">'.$icon.'<span style="margin-left:5px;">'.$result['title'].'</span><span style="color:#a3a3a3;font-size:12px;"> ['.$result['prefix'].']</span><div class="modules-template-options" style="visibility: hidden;"><div title="'.$lang->ARTICLE_9.'" class="'.$classEnable.' sumo2-tooltip" onclick="parent.sumo2.siteTree.ChangeStatusModule(\''.$crypt->encrypt($result3['ID']).'\', \''.$result2['editTable'].'\')"></div>'.$edit_button.'<div class="icon-rename sumo2-tooltip" title="'.$lang->MOD_206.'" onclick="parent.sumo2.dialog.NewDialog(\'d_site_tree_rename\',\'layout='.$item.'$!$panel='.$result['ID'].'\');"></div><div class="icon-delete sumo2-tooltip" title="'.$lang->MOD_121.'" onclick="parent.sumo2.siteTree.RemoveModule(\''.$item.'\',\''.$crypt->encrypt($result['ID']).'\')"></div></div></div></div>';	
					}
				}
				$tr .= '</div>';
				$tr .= '</td>';
			}
			$tr .= '</tr>';
			$tableArray[] = $tr;
		}
		for($i=count($tableArray)-1;$i>=0;$i--) {
			echo $tableArray[$i];
		}
		echo '</table>
		</body>
		</html>';
	}
?>