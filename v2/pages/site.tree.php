<?php require_once('../initialize.php');
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}	
	$accordion_id='a_sitetree';
	if($db->is('lang_menus'))
		$selected_lang_menus=$db->filter('lang_menus');
	else
		$selected_lang_menus=$user->translate_lang;
	$firstLayout = false;
	$firstNum = NULL;
	$firstTmp = NULL;
	$isSelected = false;
	$specialSite=false;
	if($db->is('sel_page')) {
		$selPage = $db->filter('sel_page');
		$selResult = $db->get($db->query("SELECT * FROM cms_menus_items WHERE ID='".$selPage."'"));
		if($selResult['menuID'] != -1) {
			$langResult = $db->get($db->query("SELECT * FROM cms_menus WHERE ID='".$selResult['menuID']."'"));
			if($langResult['lang'] == $selected_lang_menus) {
				$isSelected = true;
			} else if($selResult['orderID']==-1 && $selResult['target'] == $selected_lang_menus) {
				$isSelected = true;
			} else {
				$isSelected = false;
			}
		} else {
			$langResult = $db->get($db->query("SELECT * FROM cms_homepage WHERE ID='".$selResult['link']."'"));
			if($langResult['lang'] == $selected_lang_menus) {
				$isSelected = true;
			} else {
				$isSelected = false;
			}
		}
	} else {
		$isSelected = false;
        $selPage="";
	}
	
	function generateMenuItems($menuID, $parentID, $lang, $isSelected, $selPage, $firstLayout)
	{
		global $db;
        $firstNum=NULL;
        $firstTmp=NULL;
		$query=$db->query('SELECT * FROM cms_menus_items WHERE menuID='.$menuID.' AND parentID="'.$parentID.'" AND status="N" AND selection!="4" ORDER BY orderID asc');
		$int=$db->rows($query);
		if($int>0) {
			echo '<ul>';
			while($results = $db->fetch($query)) {
				$shown = '';
				if($isSelected && $selPage == $results['ID']) {
					$firstLayout = true;
					$firstNum = $results['ID'];
					$firstTmp = $results['template'];
					$shown = 'class="show"';
				} else if(!$isSelected && $firstLayout === false) {
					$firstLayout = true;
					$firstNum = $results['ID'];
					$firstTmp = $results['template'];
					$shown = 'class="show"';
				}
				if($results['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
				if($results['showM']=="Y") $smenu=''; else $smenu='<span style="color:#ff0000 !important;">*</span>';
				if($results['selection'] == 1) {
					echo '<li '.$shown.'><div '.$ena.' class="S_RND" id="'.$results['ID'].'#'.$menuID.'" onclick="sumo2.siteTree.RefreshLayout('.$results['ID'].','.$lang.','.checkTemplate($results['template']).')">'.$results['title'].' '.$smenu.'</div>';
				} else if($results['selection'] == 2) {
					$nameResult = $db->get($db->query("SELECT * FROM cms_menus_items WHERE ID='".$results['link']."' LIMIT 1"));
					echo '<li class="nosel"><div title="'.$lang->MOD_96.''.$nameResult['title'].'" style="color:green;" id="'.$results['ID'].'#'.$menuID.'" class="sumo2-tooltip S_RND">'.$results['title'].' '.$smenu.'</div>';
				} else if($results['selection'] == 3) {
					echo '<li class="nosel"><div title="'.$lang->MOD_97.''.$results['link'].'" style="color:blue;" id="'.$results['ID'].'#'.$menuID.'" class="sumo2-tooltip S_RND">'.$results['title'].' '.$smenu.'</div>';
				}
				$tmp=generateMenuItems($menuID, $results['ID'], $lang, $isSelected, $selPage, $firstLayout);
                if($tmp[0]!=NULL) {
                    $firstNum=$tmp[0];
                    $firstTmp=$tmp[1];
                }
			}
			echo '</ul>';
		}
        
        return array($firstNum, $firstTmp);
	}
?>
<div class="contextMenu" id="myMenuS_RND" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?php echo $lang->MENU_4?></li>
		<li id="show"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/menuShow.png);"></div>&nbsp;<?php echo $lang->MOD_122?></li>
        <li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?php echo $lang->MENU_28?></li>		
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?php echo $lang->MENU_5?></li>
      </ul>
</div>
<div class="contextMenu" id="myMenuS_RN" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?php echo $lang->MENU_4?></li>
        <li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?php echo $lang->MENU_28?></li>
      </ul>
</div>
<div class="contextMenu" id="myMenuS_RD" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?php echo $lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?php echo $lang->MENU_5?></li>
      </ul>
</div>
<div class="contextMenu" id="myMenuHS_RN" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?php echo $lang->MENU_4?></li>
        <li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?php echo $lang->MENU_6?></li>
      </ul>
</div>
<div class="contextMenu" id="myMenuS_R" style="display:none;">
      <ul style="font-size:12px;">
        <li id="rename"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-556px -1645px;"></div>&nbsp;<?php echo $lang->MENU_4?></li>
        <li id="delete"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-524px -1645px;"></div>&nbsp;<?php echo $lang->MENU_5?></li>
      </ul>
</div>
<div class="contextMenu" id="myMenuS_N" style="display:none;">
      <ul style="font-size:12px;">
       	<li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?php echo $lang->MENU_28?></li>
      </ul>
</div>
<div class="contextMenu" id="myMenuSpecial_N" style="display:none;">
      <ul style="font-size:12px;">
       	<li id="new"><div style="width:16px;height:16px;display:inline-block;background-image:url(images/css_sprite.png);background-position:-540px -1645px;"></div>&nbsp;<?php echo $lang->MENU_28?></li>
      </ul>
</div>
<table cellspacing="0" height="100%" width="100%">
	<tr>
    	<td class="str-panel1" id="sumo2-tree-menus-panel">
        	<div class="str-lang">
            	<?php echo $lang->SITE_TREE_1?> <?php echo lang_dropdown(''.$selected_lang_menus.'', ''.$accordion_id.'', 'lang_menus')?>
            </div>
            <div class="str-menus-container" id="sumo2-tree-menus">
            	<?php
			echo '<ul id="tree-menu">';
			$main=$db->query('SELECT * FROM cms_menus WHERE status="N" AND lang="'.$selected_lang_menus.'" AND domain="'.$user->domain.'"');
			$homeResult = $db->get($db->query("SELECT * FROM cms_homepage WHERE lang='".$selected_lang_menus."' AND domain='".$user->domain."'"));
			$homeIdResult = $db->get($db->query("SELECT * FROM cms_menus_items WHERE link='".$homeResult['ID']."' AND parentID='-1' AND orderID='-1'"));
			if($homeResult) {
				if($homeResult['selection'] == 1) {
					$shown = '';
					if($isSelected && $selPage == $homeIdResult['ID']) {
					    $firstLayout = true;
						$firstNum = $homeIdResult['ID'];
						$firstTmp = $homeResult['template'];
						$shown = 'show';
					} else if(!$isSelected && $firstLayout === false) {
						$firstLayout = true;
						$firstNum = $homeIdResult['ID'];
						$firstTmp = $homeResult['template'];
						$shown = 'show';
					}
					if($user->view == "L") {
						$layout = "ok";	
					} else {
						$layout = "no";		
					}
					echo '<li style="padding-bottom:10px;" class="no-exp '.$shown.'"><div onclick="sumo2.siteTree.RefreshLayout('.$homeIdResult['ID'].','.$selected_lang_menus.','.checkTemplate($homeResult['template']).',\''.$layout.'\')" class="globus HS_RN" id="'.$homeResult['ID'].'" style="cursor:pointer;">'.$homeResult['title'].'</div>';
				} else if($homeResult['selection'] == 2) {
					if($isSelected && $selPage == $homeIdResult['ID']) {
						$isSelected = false;
					}
					$nameResult = $db->get($db->query("SELECT * FROM cms_menus_items WHERE ID='".$homeResult['link']."'"));
					echo '<li style="padding-bottom:10px;" class="no-exp nosel"><div title="'.$lang->MOD_96.''.$nameResult['title'].'" id="'.$homeResult['ID'].'" class="globus sumo2-tooltip HS_RN" style="color:green;cursor:pointer;">'.$homeResult['title'].'</div>';
				}
				if($db->rows($main) > 0) {
					echo "<ul>";
				}
			}
            
			while($mainresults = $db->fetch($main)) {
				$id_main=$mainresults['ID'];
				echo '<li class="special"><div class="S_RN" id="'.$crypt->encrypt('-1').'#'.$crypt->encrypt($id_main).'#'.$id_main.'" style="cursor:pointer;">'.$mainresults['title'].'</div>';
				$return=generateMenuItems($id_main, "-1", $selected_lang_menus, $isSelected, $selPage, $firstLayout);
                 if($return[0]!=NULL) {
                    $firstNum=$return[0];
                    $firstTmp=$return[1];
                }
			}           
			if($db->rows($main) > 0) {
				echo "</ul>";
			}
			echo "</li>";
			echo '<li class="special spLink"><div class="ParentMenu Special_N" id="'.$crypt->encrypt($selected_lang_menus).'" style="cursor:pointer;">'.$lang->MOD_98.'</div>';
			$specialRows = $db->rows($db->query("SELECT * FROM cms_menus_items WHERE selection='4' AND status='N' AND enabled='1' AND target='".$selected_lang_menus."' AND domain='".$user->domain."'"));
			if($specialRows > 0) {
				echo "<ul>";
			}
			$modulesQuery = $db->query("SELECT DISTINCT menuID FROM cms_menus_items WHERE selection='4' AND status='N' AND enabled='1' AND target='".$selected_lang_menus."'");
			while($modulesRes = $db->fetch($modulesQuery)) {
				$modInfo = $db->get($db->query("SELECT * FROM cms_modules_def WHERE ID='".$modulesRes['menuID']."'"));
				echo '<li class="special"><div class="ParentMenu S_N" id="'.$crypt->encrypt($modInfo['ID'].'#'.$selected_lang_menus.'').'" style="cursor:pointer;">'.$modInfo['name'].'</div><ul>';
				$spQuery = $db->query("SELECT * FROM cms_menus_items WHERE menuID='".$modInfo['ID']."' AND selection='4' AND status='N' AND enabled='1' AND target='".$selected_lang_menus."' AND domain='".$user->domain."'");
				while($result = $db->fetch($spQuery)) {
					$shown ="";
					if($isSelected && $selPage == $result['ID']) {
						$shown = 'class="show"';
						$specialSite=true;
					} else if(!$isSelected && $firstLayout === false) {
						$firstLayout = true;
						$firstNum = $result['ID'];
						$firstTmp = $result['template'];
						$shown = 'class="show"';
					}
					echo '<li '.$shown.'><div class="S_R" id="'.$result['ID'].'#0" onclick="sumo2.siteTree.RefreshLayout('.$result['ID'].','.$selected_lang_menus.','.checkTemplate($result['template']).')">'.$result['title'].'</div></li>';
				}
				echo "</ul>";
			}
			if($specialRows > 0) {
				echo "</ul>";
			}
			echo "</ul>";
		?>
            </div>
		<div id="sumo2-sitetree-legend" style="font-size:10px;">
			<p style="margin: 4px 3px;"><?php echo $lang->MOD_99?>:</p>
			<p style="margin: 3px 5px;"><span style="color:#a3a3ac;"><?php echo $lang->MOD_100?></span></p>
			<p style="margin: 3px 5px;"><span style="color:red;"><?php echo $lang->MOD_101?></span></p>
			<p style="margin: 3px 5px;"><span style="color:green;"><?php echo $lang->MOD_102?></span></p>
			<p style="margin: 3px 5px;"><span style="color:blue;"><?php echo $lang->MOD_103?></span></p>
		</div>
        </td>
        <td class="str-button" onclick="sumo2.siteTree.TogglePanel('sumo2-tree-menus-panel','men-side')"><div id="men-side" class="str-arrw-l"></div></td>
        	<?php
			$template = $db->query("SELECT ID FROM cms_template WHERE status='N' AND enabled='1' AND ID='".$firstTmp."'");
			if($db->rows($template)!=1)
				$firstTmp=NULL;
			if($firstNum==NULL || $firstTmp==NULL) {
				$page = 'includes/site.tree.notmp.php';
				$links = '<div id="sumo2-sitetree-template-link" title="Template" class="sumo2-tooltip" style="background:url(/v2/images/css_sprite.png); background-position:-540px -1725px;width:16px;height:16px;cursor:pointer;float:right;margin-right:3px;"></div><div id="sumo2-sitetree-layout-link" title="Layout" class="sumo2-tooltip" style="background:url(/v2/images/css_sprite.png); background-position:-507px -1725px;width:16px;height:16px;cursor:pointer;float:right;margin-right:3px;"></div>';
			} else {
				$page = 'includes/site.tree.template.php?menu='.$firstNum.'&lang_sel='.$selected_lang_menus.'&temp='.$firstTmp;
				if($db->is('layout') && $db->filter('layout') == 'no') {
					//Do nothing
				} else if($user->view == "L") {
					$page .= '&layout=ok';
				} else if($db->is('layout') && $db->filter('layout') == 'ok') {
					$page .= '&layout=ok';
				}
				$links = '<div id="sumo2-sitetree-template-link" title="Template" class="sumo2-tooltip" style="background:url(/v2/images/css_sprite.png); background-position:-540px -1725px;width:16px;height:16px;cursor:pointer;float:right;margin-right:3px;" onclick="sumo2.siteTree.RefreshLayout(\''.$firstNum.'\',\''.$selected_lang_menus.'\',\''.checkTemplate($firstTmp).'\',\'no\')"></div><div id="sumo2-sitetree-layout-link" class="sumo2-tooltip" title="Layout" style="background:url(/v2/images/css_sprite.png); background-position:-507px -1725px;width:16px;height:16px;cursor:pointer;float:right;margin-right:3px;" onclick="sumo2.siteTree.RefreshLayout(\''.$firstNum.'\',\''.$selected_lang_menus.'\',\''.checkTemplate($firstTmp).'\',\'ok\')"></div>';	
			}
		?>
        <td class="str-main">
		<div id="sumo2-sitetree-uppermenu" style="background:#efefef;height:20px;width:100%;"><div style="float:right;padding-top:3px;padding-right:5px;"><?php echo $links?></div></div>
		<iframe id="sumo2-tree-frame" width="100%" name="layout" src="<?php echo $page?>"></iframe>
	</td>
        <td class="str-button" onclick="sumo2.siteTree.TogglePanel('sumo2-tree-modules','mod-side')"><div id="mod-side" class="str-arrw-r"></div></td>
        <td class="str-panel2" id="sumo2-tree-modules">
        	<div class="modules-container" id="sumo2-module-container">
                <?php
					$query = $db->query("SELECT cms_modules_def.ID, cms_modules_def.moduleName, cms_modules_def.name FROM cms_modules_def, cms_domains_ids WHERE cms_modules_def.status='N' AND cms_modules_def.enabled='1' AND cms_domains_ids.elementID=cms_modules_def.ID AND cms_domains_ids.domainID='".$user->domain."' AND cms_domains_ids.type='mod'");                    
					if(($firstNum==NULL || $firstTmp==NULL) && !$specialSite) {
						$drag = '1';
					} else {
						if($user->getAuth('FAV_SITE_6') == 5)
							$drag = 'onmousedown="sumo2.siteTree.SetDrag(event);"';
						else
							$drag = '2';
					}
                    
					while($result = $db->fetch($query)) {
						$url="";
						if(is_file('../modules/'.$result['moduleName'].'/small.png')) {
						   $url='background-image:url(modules/'.$result['moduleName'].'/small.png)';	
						} else if(is_file('../modules/'.$result['moduleName'].'/small.jpg')) {
						   $url='background-image:url(modules/'.$result['moduleName'].'/small.jpg)';
						}  else if(is_file('../modules/'.$result['moduleName'].'/small.gif')) {
						   $url='background-image:url(modules/'.$result['moduleName'].'/small.gif)';
						}
						echo '<div id="'.$result['ID'].'" class="modules-item" '.$drag.' style="'.$url.'">';
						if(strlen($result['name'])>18) {
						   echo substr($result['name'], 0, 17).'...'; 
						} else {
						   echo $result['name'];
						}
						 echo '</div>';	
					}
				?>
            </div>
        </td>
    </tr>
</table>