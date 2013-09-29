<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Template {
	public $tempName = '';
	public $tempID = 1;
	public $langID = 1;
	public $menuID = 1;
    public function panel($className,$group) {
		global $db, $lang;
		
		$query = $db->query("SELECT * FROM cms_panel_".$className." WHERE pageID='".$this->menuID."' AND lang='".$this->langID."' ORDER BY orderID ASC");
		$stevilo=$db->rows($query);
		echo '<div id="'.$className.'" class="site-tree-panel" style="min-height:'.(15+($stevilo*33)+10).'px"><div style="color:#a3a3a3;font-size:12px;">['.$className.']</div>';
		if($stevilo > 0) {
			while($result = $db->fetch($query)) {
				$result2 = $db->get($db->query("SELECT * FROM cms_modules_def WHERE ID='".$result['modulID']."'"));
				//modul data
				$result3 = $db->get($db->query("SELECT * FROM ".$result2['editTable']." WHERE cms_panel_id='".$result['ID']."'"));
				$classEnable=$result3['cms_enabled']? "enable":"disable";
				if($result2['editName'] == '') {
					$edit_button = '';
				} else {
					$edit_button = '<div class="icon-edit sumo2-tooltip" title="'.$lang->MOD_205.'"  onclick="parent.sumo2.dialog.NewDialog(\''.$result2['editName'].'\',\'layout='.$className.'$!$panel='.$result['ID'].'\');"></div>';
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
				echo '<div class="module-wrapper"><div id="'.$result['ID'].'#'.$className.'" class="modules-template-item" onmouseover="st.ToggleButtons(this,\'S\')" onmouseout="st.ToggleButtons(this,\'H\')">'.$icon.'<span style="display:inline-block;margin-left:5px;">'.$result['title'].'</span><span style="color:#a3a3a3;font-size:12px;"> ['.$result['prefix'].']</span><div class="modules-template-options" style="visibility: hidden;"><div title="'.$lang->ARTICLE_9.'" class="'.$classEnable.' sumo2-tooltip" onclick="parent.sumo2.siteTree.ChangeStatusModule(\''.$result3['ID'].'\', \''.$result2['editTable'].'\')"></div>'.$edit_button.'<div class="icon-rename sumo2-tooltip" title="'.$lang->MOD_206.'" onclick="parent.sumo2.dialog.NewDialog(\'d_site_tree_rename\',\'layout='.$className.'$!$panel='.$result['ID'].'\');"></div><div class="icon-delete sumo2-tooltip" title="'.$lang->MOD_121.'" onclick="st.RemoveModule(\''.$className.'\',\''.$result['ID'].'\')"></div></div></div></div>';	
			}
		}
		echo '</div>';
    }
	
	public function head() {
		global $xml, $user;
		$xmlParse = $xml->getSpecialArray(SITE_ROOT.DS.'templates/'.$user->domainName.'/'.$this->tempName.'/settings.xml');
		foreach($xmlParse as $element) {
			if($element['tag'] == 'style') {
				echo '<link type="text/css" rel="stylesheet" href="../../templates/'.$user->domainName.'/'.$this->tempName.'/'.$element['value'].'" />';
			}
		}
		echo '<script type="text/javascript" src="../scripts/site.tree.js"></script>';
		echo '<link type="text/css" rel="stylesheet" href="../css/site.tree.css" />';
		echo '<script type="text/javascript">st.PAGE='.$this->menuID.';st.LANG='.$this->langID.';</script>';
	}
}

$template = new Template();
?>
