<?php require_once('../initialize.php'); 
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
                    <li id="user1tab"><a href="#user1"><?php echo $lang->SETTINGS_46?></a></li>
                    <li id="use21tab" style="display:none;"><a href="#user2"><?php echo $lang->SETTINGS_47?></a></li>
                    <li id="sumotab"><a href="#sumo"><?php echo $lang->SETTINGS_48?></a></li>
                    <?php if($user->getAuth('FAV_SITE_3') >= 3) {?>
                        <li id="pagetab"><a href="#page"><?php echo $lang->SETTINGS_49?></a></li>
                    <?php } ?>
                    <?php if($user->getAuth('FAV_SITE_3') == 5) {?>
                        <li id="globaltab"><a href="#global"><?php echo $lang->SETTINGS_50?></a></li>
                    <?php } ?>
                    <li id="errortab"><a href="#error"><?php echo $lang->SETTINGS_51?></a></li>
                    <li id="errorFronttab"><a href="#errorFront"><?php echo $lang->SETTINGS_62?></a></li>
					<li id="datatab"><a href="#data"><?php echo $lang->SETTINGS_66?></a></li>
                     <?php if($user->getAuth('FAV_SITE_3') == 5) {?>
                    	<li id="welcometab"><a href="#welcome"><?php echo $lang->SETTINGS_52?></a></li>
                    <?php } ?>
                </ul>
            </td>
            <td style="vertical-align:top;" width="100%">
                <div id="tab_container_id" class="tab_container" style="width:100% !important;">
                    <input id="settings_current_tab" type="hidden" value="#tab1" /> 
                    <div id="user1" class="tab_content" style="overflow:auto;">
                        <?php include("settings_user.php") ?>
                    </div>
                    <div id="user2" class="tab_content" style="overflow:auto;">
                        <?php include("settings_more.php") ?>
                    </div>
                     <div id="sumo" class="tab_content" style="overflow:auto;">
                        <?php include("settings_sumo.php") ?>
                    </div>
                    <?php if($user->getAuth('FAV_SITE_3') >= 3) {?>
                        <div id="page" class="tab_content" style="overflow:auto;">
                            <?php include("settings_page.php") ?>
                        </div>
                    <?php } ?>
                    <?php if($user->getAuth('FAV_SITE_3') == 5) {?>
                        <div id="global" class="tab_content" style="overflow:auto;">
                            <?php include("settings_global.php") ?>
                        </div>
                    <?php } ?>
                    <div id="error" class="tab_content" style="overflow:auto;">
                        <?php include("settings_error.php") ?>
                    </div>
                    <div id="errorFront" class="tab_content" style="overflow:auto;">
                        <?php include("settings_errorFront.php") ?>
                    </div>
					<div id="data" class="tab_content" style="overflow:auto;">
                        <?php include("settings_dataLog.php") ?>
                    </div>
                    <?php if($user->getAuth('FAV_SITE_3') == 5) {?>
                        <div id="welcome" class="tab_content" style="overflow:auto;">
                            <?php include("settings_welcome.php") ?>
                        </div>
                    <?php } ?>
                </div>
            </td>
        </tr>
    </table>
</div>