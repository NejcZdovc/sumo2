<? require_once('../initialize.php'); 
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$id = $crypt->encrypt($user->id);
?>
<div id="settings_container">
	<table border="0" cellpadding="0" cellspacing="0" style="vertical-align:top;" width="100%" height="100%">
    	<tr style="vertical-align:top;">
        	<td width="180px" style="vertical-align:top;">
                <ul class="tabs" >
                    <li id="user1tab"><a href="#user1"><?=$lang->SETTINGS_46?></a></li>
                    <li id="use21tab" style="display:none;"><a href="#user2"><?=$lang->SETTINGS_47?></a></li>
                    <li id="sumotab"><a href="#sumo"><?=$lang->SETTINGS_48?></a></li>
                    <? if($user->getAuth('FAV_SITE_3') >= 3) {?>
                        <li id="pagetab"><a href="#page"><?=$lang->SETTINGS_49?></a></li>
                    <? } ?>
                    <? if($user->getAuth('FAV_SITE_3') == 5) {?>
                        <li id="globaltab"><a href="#global"><?=$lang->SETTINGS_50?></a></li>
                    <? } ?>
                    <li id="errortab"><a href="#error"><?=$lang->SETTINGS_51?></a></li>
                    <li id="errorFronttab"><a href="#errorFront"><?=$lang->SETTINGS_62?></a></li>
                     <? if($user->getAuth('FAV_SITE_3') == 5) {?>
                    	<li id="welcometab"><a href="#welcome"><?=$lang->SETTINGS_52?></a></li>
                    <? } ?>
                </ul>
            </td>
            <td style="vertical-align:top;" width="100%">
                <div id="tab_container_id" class="tab_container" style="width:100% !important;">
                    <input id="settings_current_tab" type="hidden" value="#tab1" /> 
                    <div id="user1" class="tab_content" style="overflow:auto;">
                        <? include("settings_user.php") ?>
                    </div>
                    <div id="user2" class="tab_content" style="overflow:auto;">
                        <? include("settings_more.php") ?>
                    </div>
                     <div id="sumo" class="tab_content" style="overflow:auto;">
                        <? include("settings_sumo.php") ?>
                    </div>
                    <? if($user->getAuth('FAV_SITE_3') >= 3) {?>
                        <div id="page" class="tab_content" style="overflow:auto;">
                            <? include("settings_page.php") ?>
                        </div>
                    <? } ?>
                    <? if($user->getAuth('FAV_SITE_3') == 5) {?>
                        <div id="global" class="tab_content" style="overflow:auto;">
                            <? include("settings_global.php") ?>
                        </div>
                    <? } ?>
                    <div id="error" class="tab_content" style="overflow:auto;">
                        <? include("settings_error.php") ?>
                    </div>
                    <div id="errorFront" class="tab_content" style="overflow:auto;">
                        <? include("settings_errorFront.php") ?>
                    </div>
                    <? if($user->getAuth('FAV_SITE_3') == 5) {?>
                        <div id="welcome" class="tab_content" style="overflow:auto;">
                            <? include("settings_welcome.php") ?>
                        </div>
                    <? } ?>
                </div>
            </td>
        </tr>
    </table>
</div>