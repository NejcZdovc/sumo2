<?php 
	require_once('../initialize.php');
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
?>
<form action="" name="d_layoutmodule" method="post" class="form2">
<table cellpadding="0" cellspacing="4" border="0" width="99%" >
	<tr>
    <td>
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->SITE_TREE_2?>:</div><div class="title_form_small"><?=$lang->SITE_TREE_3?></div>
        </td>
        <td class="right_td">
        <input class="input" id="nameOfModule" value="" type="text" maxlength="50" />
        <input type="text" name="enterfix" style="display:none;" />
        <input type="hidden" id="idOfModule" value="<?=$db->filter('modid')?>" />
        <input type="hidden" id="layoutOfModule" value="<?=$db->filter('layout')?>" />
        <input type="hidden" id="tpageOfModule" value="<?=$db->filter('tpage')?>" />
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->SITE_TREE_4?>:</div><div class="title_form_small"><?=$lang->SITE_TREE_5?></div>
        </td>
        <td class="right_td">
        <select id="prefixOfModule" class="input">
        <?
			$query=$db->query('SELECT name, ID FROM cms_modul_prefix WHERE enabled=1 AND domain="'.$user->domain.'" ORDER BY ID');
			while($results=$db->fetch($query)) {
				echo '<option value="'.$results['ID'].'">'.$results['name'].'</option>';
			}		
		?>
        </select>
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
		<div class="title_form_big"><?=$lang->SITE_TREE_6?>:</div><div class="title_form_small"><?=$lang->SITE_TREE_7?></div>
        </td>
        <td class="right_td">
        <select id="languageForModule" class="input" onchange="sumo2.siteTree.SelectLanguage(this)">
        <?
			$query=$db->query('SELECT name, ID FROM cms_language_front WHERE enabled=1 ORDER BY ID');
			while($results=$db->fetch($query)) {
				echo '<option value="'.$results['ID'].'">'.$results['name'].'</option>';
			}		
		?>
        </select>
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
		<div class="title_form_big"><?=$lang->MOD_199?>:</div><div class="title_form_small"><?=$lang->MOD_200?></div>
        </td>
        <td class="right_td">
        <select id="copyForModule" class="input">
       		<option value="0"><?=$lang->ARTICLE_20?></option>
            <option value="1"><?=$lang->ARTICLE_19?></option>
        </select>
        </td>
    </tr>
    </table>
    </td>
    <td width="200px" class="right_td">
        <? 
		$first = true;
		$counter = 0;
		$int = 0;
		$langMain="";
		$tpage = $db->filter('tpage');
		$tempResult = $db->get($db->query("SELECT * FROM cms_menus_items WHERE ID='".$tpage."'"));
		if($tempResult['parentID'] == -1 && $tempResult['orderID'] == -1 && $tempResult['selection'] != '4') {
			$infoResults = $db->get($db->query("SELECT * FROM cms_homepage WHERE ID='".$tempResult['link']."'"));
			$tempResult['template'] = $infoResults['template'];
			$langMain=$infoResults['lang'];
		} else if($tempResult['parentID'] == -1 && $tempResult['selection'] == '4') {
			$langMain=$tempResult['target'];
		} else {
			$infoResults = $db->get($db->query("SELECT * FROM cms_menus WHERE ID='".$tempResult['menuID']."'"));
			$langMain=$infoResults['lang'];
		} 
		
		$query=$db->query('SELECT name, ID FROM cms_language_front WHERE enabled="1" AND ID="'.$langMain.'"');
			while($resultsL=$db->fetch($query)) {
				if($first) {
					$show = '';
					$first = false;	
				} else {
					$show = 'style="display:none;"';
				}
				echo '<div '.$show.' id="lang-tree-'.$resultsL['ID'].'">';
				echo '<ul id="tree-menu'.$counter.'" class="the-tree">';
				$counter++;
				$main=$db->query('SELECT ID, title FROM cms_menus WHERE status="N" AND lang="'.$resultsL['ID'].'" AND domain="'.$user->domain.'"');
				$homeResult = $db->get($db->query("SELECT * FROM cms_homepage WHERE lang='".$resultsL['ID']."' AND domain='".$user->domain."'"));
				if($homeResult['template'] == $tempResult['template']) {
					$homeIdResult = $db->get($db->query("SELECT * FROM cms_menus_items WHERE link='".$homeResult['ID']."' AND parentID='-1' AND orderID='-1'"));
					if($homeIdResult['ID'] == $tpage) {
						$checked = 'checked="checked"';	
					} else {
						$checked = '';	
					}
					echo '<li><div id="'.$homeIdResult['ID'].'#'.$homeIdResult['parentID'].'" style="cursor:pointer; margin-bottom:10px;"><input '.$checked.' type="checkbox" name="test" value="'.$homeIdResult['ID'].'" />'.$homeResult['title'].'</div></li>';
				}
				while($mainresults=$db->fetch($main)){
					$id_main=$mainresults['ID'];
					//base level
					$queryT=$db->query('SELECT ID, title, parentID, orderID, enabled, template FROM cms_menus_items WHERE menuID='.$id_main.' AND parentID="-1" AND status="N" AND template="'.$tempResult['template'].'" ORDER BY orderID asc');
					$int=$db->rows($queryT);
					echo '<li class="special"><div id="0#'.$id_main.'" style="cursor:pointer;"><input type="checkbox" disabled="disabled" name="test" value="root" /><span style="display:inline-block;width:16px;height:16px;background:url(/v2/images/css_sprite.png); background-position:-669px -1709px;cursor:pointer;" onclick="sumo2.siteTree.CheckForChildsButton(this)"></span>'.$mainresults['title'].'</div>';
					if($int>0) {
						//first level
						echo '<ul>';
						$int = 0;
						while($results=$db->fetch($queryT)){
							$query2=$db->query('SELECT ID, title, parentID, orderID, enabled, template FROM cms_menus_items WHERE menuID='.$id_main.' AND parentID="'.$results['ID'].'" AND status="N" AND template="'.$tempResult['template'].'" ORDER BY orderID asc');
							$int=$db->rows($query2);
							if($results['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
							if($results['ID'] == $tpage) {
								$checked = 'checked="checked"';	
							} else {
								$checked = '';	
							}
							echo '<li><div '.$ena.' id="'.$results['ID'].'#'.$id_main.'" onclick=""><input type="checkbox" '.$checked.' name="test" value="'.$results['ID'].'#'.$resultsL['ID'].'" id="'.$results['ID'].'#$#1" /><span style="display:inline-block;width:16px;height:16px;background:url(/v2/images/css_sprite.png); background-position:-669px -1709px;cursor:pointer;" onclick="sumo2.siteTree.CheckForChildsButton(this)"></span><label for="'.$results['ID'].'#$#1">'.$results['title'].'</label></div>';
							if($int>0) {
								$int = 0;
								//second level
								echo '<ul>';
								while($results2=$db->fetch($query2)){
									$query3=$db->query('SELECT ID, title, parentID, orderID, enabled, template FROM cms_menus_items WHERE menuID='.$id_main.' AND parentID="'.$results2['ID'].'" AND status="N" AND template="'.$tempResult['template'].'" ORDER BY orderID asc');
									$int=$db->rows($query3);
									if($results2['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
									if($results2['ID'] == $tpage) {
										$checked = 'checked="checked"';	
									} else {
										$checked = '';	
									}
									echo '<li><div '.$ena.' id="'.$results2['ID'].'#'.$id_main.'" onclick=""><input type="checkbox" '.$checked.' name="test" value="'.$results2['ID'].'#'.$resultsL['ID'].'" id="'.$results2['ID'].'#$#2" /><span style="display:inline-block;width:16px;height:16px;background:url(/v2/images/css_sprite.png); background-position:-669px -1709px;cursor:pointer;" onclick="sumo2.siteTree.CheckForChildsButton(this)"></span><label for="'.$results2['ID'].'#$#2">'.$results2['title'].'</label></div>';
									if($int>0) {
										$int = 0;
										//third level
										echo '<ul>';
										while($results3=$db->fetch($query3)){
											$query4=$db->query('SELECT ID, title, parentID, orderID, enabled, template FROM cms_menus_items WHERE menuID='.$id_main.' AND parentID="'.$results3['ID'].'" AND status="N" AND template="'.$tempResult['template'].'" ORDER BY orderID asc');
											$int=$db->rows($query4);
											if($results3['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
											if($results3['ID'] == $tpage) {
												$checked = 'checked="checked"';	
											} else {
												$checked = '';	
											}
											echo '<li><div '.$ena.' id="'.$results3['ID'].'#'.$id_main.'" onclick=""><input type="checkbox" '.$checked.' name="test" value="'.$results3['ID'].'#'.$resultsL['ID'].'" id="'.$results3['ID'].'#$#3" /><span style="display:inline-block;width:16px;height:16px;background:url(/v2/images/css_sprite.png); background-position:-669px -1709px;cursor:pointer;" onclick="sumo2.siteTree.CheckForChildsButton(this)"></span><label for="'.$results3['ID'].'#$#3">'.$results3['title'].'</label></div>';
											if($int>0) {
												//fourth level
												echo "<ul>";
												while($results4=$db->fetch($query4)){
													if($results4['enabled']==1) $ena=''; else $ena='style="color:#ff0000;"';
													if($results4['ID'] == $tpage) {
														$checked = 'checked="checked"';	
													} else {
														$checked = '';	
													}
													echo '<li><div '.$ena.' id="'.$results4['ID'].'#'.$id_main.'" onclick=""><input type="checkbox" '.$checked.' name="test" value="'.$results4['ID'].'#'.$resultsL['ID'].'" id="'.$results4['ID'].'#$#3" /><span style="display:inline-block;width:16px;height:16px;background:url(/v2/images/css_sprite.png); background-position:-669px -1709px;cursor:pointer;" onclick="sumo2.siteTree.CheckForChildsButton(this)"></span><label for="'.$results4['ID'].'#$#4">'.$results4['title'].'</label></div></li>';
												}
												echo "</ul>";	
											}
											echo "</li>";
										}
										echo "</ul>";
									}
									echo "</li>";
								}	
								echo "</ul>";
							}
							echo "</li>";		
						}
						echo "</ul>";
					}
					echo "</li>";
				}
				echo '<li class="special"><div id="0#0" style="cursor:pointer;"><input type="checkbox" name="test" value="root" /><span style="display:inline-block;width:16px;height:16px;background:url(/v2/images/css_sprite.png); background-position:-669px -1709px;cursor:pointer;" onclick="sumo2.siteTree.CheckForChildsButton(this)"></span>'.$lang->MOD_98.'</div>';
				$specialRows = $db->rows($db->query("SELECT * FROM cms_menus_items WHERE selection='4' AND status='N' AND enabled='1' AND target='".$resultsL['ID']."' AND domain='".$user->domain."'"));
				if($specialRows > 0) {
					echo "<ul>";
				}
				$modulesQuery = $db->query("SELECT DISTINCT menuID FROM cms_menus_items WHERE selection='4' AND status='N' AND enabled='1' AND target='".$resultsL['ID']."' AND domain='".$user->domain."'");
				while($modulesRes = $db->fetch($modulesQuery)) {
					$modInfo = $db->get($db->query("SELECT * FROM cms_modules_def WHERE ID='".$modulesRes['menuID']."'"));
					echo '<li class="special"><div id="0#0" style="cursor:pointer;"><input type="checkbox" name="test" value="root" /><span style="display:inline-block;width:16px;height:16px;background:url(/v2/images/css_sprite.png); background-position:-669px -1709px;cursor:pointer;" onclick="sumo2.siteTree.CheckForChildsButton(this)"></span>'.$modInfo['name'].'</div><ul>';
					$spQuery = $db->query("SELECT * FROM cms_menus_items WHERE menuID='".$modInfo['ID']."' AND selection='4' AND status='N' AND enabled='1' AND target='".$resultsL['ID']."' AND domain='".$user->domain."'");
					while($result = $db->fetch($spQuery)) {
						if($result['ID'] == $tpage) {
												$checked = 'checked="checked"';	
											} else {
												$checked = '';	
											}
						echo '<li><div id="'.$result['ID'].'#0" onclick=""><input type="checkbox" '.$checked.' name="test" value="'.$result['ID'].'#'.$resultsL['ID'].'" /><span style="display:inline-block;width:16px;height:16px;background:url(/v2/images/css_sprite.png); background-position:-669px -1709px;cursor:pointer;" onclick="sumo2.siteTree.CheckForChildsButton(this)"></span>'.$result['title'].'</div></li>';
					}
					echo "</ul>";
				}
				if($specialRows > 0) {
					echo "</ul>";
				}
				echo "</li>";
				echo "</ul>";
				echo '</div>';		
			}	
		?>
    </td>
    </tr>
</table>
</form>