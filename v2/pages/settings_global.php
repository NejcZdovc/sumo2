<form action="" name="a_settings_global" method="post" class="form2">
<input type="hidden" id="GA" value="<?=$globals->GA_enabled?>" />
<input type="hidden" id="WM" value="<?=$globals->WM_enabled?>" />
	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top" style="width:50%; min-width:25%;">
            <div style="clear:both; margin-bottom:20px;">
                <div class="title_form_big" style="float:left;"><?=$lang->SETTINGS_29?></div>
                <div style="float:right; margin-right:10px; cursor:pointer;" onclick="sumo2.dialog.NewDialog('d_settings_add_t',null)"><div style="display:block;width:16px;height:16px;background-image:url(images/css_sprite.png);background-position:-508px -1709px;"></div></div>
            </div>
            <table width="99%" cellpadding="0" cellspacing="0" border="0">
            <?
			$template_q=$db->query("SELECT ID, name, enabled, folder FROM cms_template WHERE status='N' AND domain='".$user->domain."' order by ID asc");
			while($template_f=$db->fetch($template_q)) {			
			?>
                <tr>
                <td style="background:#F3F3F3;vertical-align:middle; height:30px; padding:10px;">
                    <?=$template_f['name']?>
                </td>
                <td style="background:#F3F3F3;vertical-align:middle; padding:10px;" width="100px">
                    <div title="<?=$lang->MENU_7?>" class="<?php echo $template_f['enabled']?"enable":"disable"; ?> sumo2-tooltip" onclick="sumo2.sumoSettings.ChangeStatus('<?php echo $crypt->encrypt($template_f['ID']); ?>')"></div>
                    <div title="<?=$lang->MENU_14?>" class="add sumo2-tooltip" onclick="sumo2.sumoSettings.NewPageT('<?php echo $crypt->encrypt($template_f['ID']); ?>')"></div>
                    <div title="<?=$lang->MOD_107?>" class="edit sumo2-tooltip" onclick="sumo2.dialog.NewDialog('d_settings_edit_t','ID=<?php echo $crypt->encrypt($template_f['ID']); ?>')"></div>
                    <div title="<?=$lang->MOD_246?>" class="edit sumo2-tooltip" style="background-image: url(/v2/images/css_sprite.png);background-position: -604px -1629px;" onclick="sumo2.sumoSettings.ClearCacheT('<?php echo $crypt->encrypt($template_f['folder']); ?>')"></div>
                    <div title="<?=$lang->MOD_108?>" class="delete sumo2-tooltip" onclick="sumo2.sumoSettings.DeleteT('<?php echo $crypt->encrypt($template_f['ID']); ?>')"></div>
                </td>
                </tr>
            <? } ?>
            </table>
        </td>
        <td class="right_td" style="width:50%; vertical-align:top; min-width:25%;">
       		<div style="clear:both; margin-bottom:20px;">
                <div class="title_form_big" style="float:left;"><?=$lang->SETTINGS_35?></div>
                <div style="float:right; margin-right:10px; cursor:pointer;" onclick="sumo2.dialog.NewDialog('a_settings_add_tp',null)"><div style="display:block;width:16px;height:16px;background-image:url(images/css_sprite.png);background-position:-620px -1661px;"></div></div>
            </div>
            <table width="99%" cellpadding="0" cellspacing="0" border="0">
            <?
			$template_q=$db->query("SELECT ID, name, prefix FROM cms_template_position WHERE domain='".$user->domain."' order by ID asc");
			while($template_p=$db->fetch($template_q)) {			
			?>
            <tr>
            <td style="background:#E6ECF2;vertical-align:middle; height:30px; padding:10px;">
            	<?=$template_p['name']?>
            </td>
            <td style="background:#E6ECF2;vertical-align:middle; height:30px; padding:10px;">
            	<?=$template_p['prefix']?>
            </td>
            <td style="background:#E6ECF2;vertical-align:middle; height:30px; width:28px; padding-left:8px;">
            	<div title="<?=$lang->MOD_108?>" class="delete sumo2-tooltip" onclick="sumo2.sumoSettings.DeleteTP('<?php echo $crypt->encrypt($template_p['ID']); ?>')"></div>
            </td>
            </tr>
            <? } ?>
            </table>
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
        <td class="right_td" valign="top" style="width:50%; vertical-align:top;">
            <div style="clear:both; margin-bottom:20px;">
                <div class="title_form_big" style="float:left;"><?=$lang->SETTINGS_36?></div>
                <div style="float:right; margin-right:10px; cursor:pointer;" onclick="sumo2.dialog.NewDialog('d_settings_add_lb',null)"><div style="display:block;width:16px;height:16px;background-image:url(images/css_sprite.png);background-position:-556px -1629px;"></div></div>
            </div>
             <table width="99%" cellpadding="0" cellspacing="0" border="0">
            <?
            $template_q=$db->query("SELECT ID, name, short, enabled FROM cms_language order by ID asc");
            while($template_f=$db->fetch($template_q)) {			
            ?>
            <tr>
            <td style="background:#E6ECF2;vertical-align:middle; height:30px; padding:10px;">
                <?=$template_f['name']?>
            </td>
            <td style="background:#E6ECF2;vertical-align:middle; height:30px; padding:10px;">
                <?=$template_f['short']?>
            </td>
            <td style="background:#E6ECF2;vertical-align:middle; padding:10px;" width="20px">
                <div title="<?=$lang->MENU_7?>" class="<?php echo $template_f['enabled']?"enable":"disable"; ?> sumo2-tooltip" onclick="sumo2.sumoSettings.ChangeStatusLb('<?php echo $crypt->encrypt($template_f['ID']); ?>')"></div>
            </td>
            </tr>
            <? } ?>
            </table>
        </td>
        <td class="left_td" valign="top" style="width:50%;">
            <div style="clear:both; margin-bottom:20px;">
                 <div class="title_form_big" style="float:left;"><?=$lang->SETTINGS_37?></div>
                 <div style="float:right; margin-right:10px; cursor:pointer;" onclick="sumo2.dialog.NewDialog('d_settings_add_lf',null)"><div style="display:block;width:16px;height:16px;background-image:url(images/css_sprite.png);background-position:-556px -1629px;"></div></div>
            </div>
              <table width="99%" cellpadding="0" cellspacing="0" border="0">
            <?
            $template_q=$db->query("SELECT ID, name, short, enabled FROM cms_language_front order by ID asc");
            while($template_f=$db->fetch($template_q)) {			
            ?>
            <tr>
            <td style="background:#F3F3F3;vertical-align:middle; height:30px; padding:10px;">
                <?=$template_f['name']?>
            </td>
            <td style="background:#F3F3F3;vertical-align:middle; height:30px; padding:10px;">
                <?=$template_f['short']?>
            </td>
            <td style="background:#F3F3F3;vertical-align:middle; padding:10px;" width="20px">
                <div title="<?=$lang->MENU_7?>" class="<?php echo $template_f['enabled']?"enable":"disable"; ?> sumo2-tooltip" onclick="sumo2.sumoSettings.ChangeStatusLf('<?php echo $crypt->encrypt($template_f['ID']); ?>')"></div>
            </td>
            </tr>
            <? } ?>
            </table>
        </td>
    </tr>
        <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top" style="width:50%;">
            <div class="title_form_big" style="float:left;"><?=$lang->SETTINGS_38?></div>
            <div style="margin-top:30px;">
                <div style="float:left;">Google Analytics</div>
                <div style="float:left;" title="<?=$lang->MENU_7?>" class="<?php echo $globals->GA_enabled?"enable":"disable"; ?> sumo2-tooltip" onclick="sumo2.sumoSettings.GAstat()"></div>
            </div>
            <div style="clear:both; padding-top:5px;">
            <div style="float:left; margin-top:10px; margin-right:5px;" class="title_form_small"><?=$lang->MOD_109?>:</div><input id="GA_ID" type="text" class="input" maxlength="50" style="float:left; width:150px; margin-right:20px;" value="<?=$globals->GA_ID?>"  />
            <select id="GA_type" disabled="disabled" class="input" style="float:left; width:197px; margin-top:7px;" >
                <option value="1" <? if($globals->GA_type=="1") echo 'selected="Selected"';?>><?=$lang->MOD_110?></option>
                <option value="2" <? if($globals->GA_type=="2") echo 'selected="Selected"';?>><?=$lang->MOD_112?></option>       
            </select>
            </div>
            <div style="height:16px; width:16px; float:left; margin-top:10px; margin-left:20px; cursor:pointer;" onclick="sumo2.dialog.NewDialog('h_GA', 'type=GA')"><div style="display:block;width:16px;height:16px;background-image:url(images/css_sprite.png);background-position:-524px -1693px;"></div></div>
            <div style="padding-top:30px; clear:both;">
                <div style="float:left;">Google Webmaster</div>
                <div style="float:left;" title="<?=$lang->MENU_7?>" class="<?php echo $globals->WM_enabled?"enable":"disable"; ?> sumo2-tooltip" onclick="sumo2.sumoSettings.WMstat()"></div>
            </div>
            <div style="clear:both; padding-top:5px;">
                <div style="float:left; margin-top:10px; margin-right:5px;" class="title_form_small"><?=$lang->MOD_113?>:</div><input id="WM_ID" type="text" class="input" maxlength="60" style="float:left; width:400px; margin-right:20px;" value="<?=$globals->WM_ID?>"  />
                <div style="height:16px; width:16px; float:left; margin-top:10px; cursor:pointer;" onclick="sumo2.dialog.NewDialog('h_GA', 'type=WM')"><div style="display:block;width:16px;height:16px;background-image:url(images/css_sprite.png);background-position:-524px -1693px;"></div></div>
            </div>
        </td>
		<td class="right_td" style="width:50%; vertical-align:top;">
       		<div style="clear:both; margin-bottom:20px;">
                <div class="title_form_big" style="float:left;"><?=$lang->SETTINGS_39?></div>
                <div style="float:right; margin-right:10px; cursor:pointer;" onclick="sumo2.dialog.NewDialog('a_settings_add_p',null)"><div style="display:block;width:16px;height:16px;background-image:url(images/css_sprite.png);background-position:-604px -1661px;"></div></div>
            </div>
            <table width="99%" cellpadding="0" cellspacing="0" border="0">
            <?
			$modul_q=$db->query("SELECT ID, name, enabled, prefix FROM cms_modul_prefix WHERE domain='".$user->domain."' order by ID asc");
			while($modul_p=$db->fetch($modul_q)) {			
			?>
            <tr>
            <td style="background:#E6ECF2;vertical-align:middle; height:30px; padding:10px;">
            	<?=$modul_p['name']?>
            </td>
            <td style="background:#E6ECF2;vertical-align:middle; height:30px; padding:10px;">
            	<?=$modul_p['prefix']?>
            </td>
            <td style="background:#E6ECF2;vertical-align:middle; padding:10px;" width="40px">
            	<div title="<?=$lang->MENU_7?>" class="<?php echo $modul_p['enabled']?"enable":"disable"; ?> sumo2-tooltip" onclick="sumo2.sumoSettings.Status_prefix('<?php echo $crypt->encrypt($modul_p['ID']); ?>')"></div>
                <div title="<?=$lang->MOD_108?>" class="delete sumo2-tooltip" onclick="sumo2.sumoSettings.DeleteP('<?php echo $crypt->encrypt($modul_p['ID']); ?>')"></div>
            </td>
            </tr>
            <? } ?>
            </table>
        </td>
    </tr> 
    <tr>
        <td class="right_td" style="width:50%; vertical-align:top;">
        	<div style="clear:both; margin-bottom:20px;">
                <div class="title_form_big" style="float:left;"><?=$lang->SETTINGS_40?></div>
                <div style="float:right; margin-right:10px; cursor:pointer;" onclick="sumo2.dialog.NewDialog('d_logo',null)"><div style="display:block;width:16px;height:16px;background-image:url(images/css_sprite.png);background-position:-540px -1661px;"></div></div>
            </div>
            <div style="width:100%; height:auto;"><img src="images/logo.png" style="margin-left: auto; margin-right: auto; display:block;" height="90" /></div>
        </td>
        <td class="left_td" valign="top" style="width:50%;">
        <div style="clear:both; margin-bottom:20px;">
                <div class="title_form_big" style="float:left;"><?=$lang->MOD_123?></div>
                <div style="float:right; margin-right:10px; cursor:pointer;" onclick="sumo2.sumoSettings.SaveFTP()"><div style="display:block;width:16px;height:16px;background-image:url(images/css_sprite.png);background-position:-652px -1629px;"></div></div>
                <table width="99%" cellpadding="0" cellspacing="0" border="0" style="clear:both;">
                    <tr>
                        <td width="150px"><?=$lang->MOD_124?>:</td>
                        <td><input type="password" class="input" name="FTP_user" style="width:250px;" value="000000000000" /></td>
                    </tr>
                	<tr>
                        <td><?=$lang->MOD_125?>:</td>
                        <td><input type="password" class="input" name="FTP_pass" style="width:250px;" value="000000000000" /></td>
                    </tr>
                    <tr>
                        <td><?=$lang->MOD_126?>:</td>
                        <td><input type="text" class="input" name="FTP_url" style="width:250px;" value="<?=$globals->FTP_url?>" /></td>
                    </tr>
                    <tr>
                        <td><?=$lang->MOD_127?>:</td>
                        <td><input type="text" class="input" name="FTP_port" style="width:50px;" value="<?=$globals->FTP_port?>" /></td>
                    </tr>
                </table>
            </div>
        </td>	
    </tr> 
    </table>
    </div>
</form>