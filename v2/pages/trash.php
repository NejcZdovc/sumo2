<?php require_once('../initialize.php'); 
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$id = $crypt->encrypt($user->id);
?>
<div id="trash_container">
	<table border="0" cellpadding="0" cellspacing="0" style="vertical-align:top;" width="100%" height="100%">
    	<tr style="vertical-align:top;">
        	<td width="170px" style="vertical-align:top;">
                <ul class="tabs_trash" >
                    <li id="articletab"><a href="#article"><?php echo $lang->MOD_43?></a></li>
                    <li id="articleGtab"><a href="#articleG"><?php echo $lang->MOD_94?></a></li>
                    <li id="usertab"><a href="#user"><?php echo $lang->FAV_USER?></a></li>
                    <li id="userGtab"><a href="#userG"><?php echo $lang->MOD_93?></a></li>
                    <li id="menutab"><a href="#menu"><?php echo $lang->MOD_90?></a></li>
                    <li id="menuItab"><a href="#menuI"><?php echo $lang->MOD_95?></a></li>
                    <li id="mailtab"><a href="#mail"><?php echo $lang->MAIL?></a></li>
                    <li id="modulitab"><a href="#moduli"><?php echo $lang->FAV_MODULES?></a></li>
                    <li id="comtab"><a href="#com"><?php echo $lang->MOD_139?></a></li>
                    <li id="temptab"><a href="#temp"><?php echo $lang->SETTINGS_29?></a></li>                    
                </ul>
            </td>
            <td width="auto" style="vertical-align:top;">
                <div id="trash_tab_container_id" style="min-height:400px;" class="tab_container">
                    <input id="trash_current_tab" type="hidden" value="#article" /> 
                    <div id="article" class="t_tab_content" style="overflow:auto;">
                        <?php include("trash_article.php") ?>
                    </div>
                    <div id="articleG" class="t_tab_content" style="overflow:auto;">
                        <?php include("trash_articleG.php") ?>
                    </div>
                     <div id="user" class="t_tab_content" style="overflow:auto;">
                        <?php include("trash_user.php") ?>
                    </div>
                    <div id="userG" class="t_tab_content" style="overflow:auto;">
                        <?php include("trash_userG.php") ?>
                    </div>
                    <div id="menu" class="t_tab_content" style="overflow:auto;">
                        <?php include("trash_menu.php") ?>
                    </div>
                    <div id="menuI" class="t_tab_content" style="overflow:auto;">
                        <?php include("trash_menuI.php") ?>
                    </div>
                    <div id="mail" class="t_tab_content" style="overflow:auto;">
                        <?php include("trash_mail.php") ?>
                    </div>
                    <div id="moduli" class="t_tab_content" style="overflow:auto;">
                        <?php include("trash_moduli.php") ?>
                    </div>
                    <div id="com" class="t_tab_content" style="overflow:auto;">
                        <?php include("trash_component.php") ?>
                    </div>
                    <div id="temp" class="t_tab_content" style="overflow:auto;">
                        <?php include("trash_template.php") ?>
                    </div>
                    
                </div>
            </td>
        </tr>
    </table>
</div>