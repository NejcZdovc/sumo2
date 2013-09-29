<?php 
	$result = $db->get($db->query("SELECT title,email,keywords,description,template,SEO,display_title,offline,front_lang FROM cms_global_settings WHERE domain='".$user->domain."'"));
?>
<form action="" name="a_settings_page" method="post" class="form2">
	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->MOD_25?>:</div><div class="title_form_small"><?=$lang->MOD_26?></div>
        </td>
        <td class="right_td">
        <select class="input" id="offline" style="margin-left:5%; margin-right:5%; width:90%;">
        	<option value="Y" <? if($result['offline']=="Y") echo 'selected="selected"'; ?>><?=$lang->MOD_27?></option>
            <option value="N" <? if($result['offline']=="N") echo 'selected="selected"'; ?>><?=$lang->MOD_28?></option>
        </select>
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->SETTINGS_19?>:</div><div class="title_form_small"><?=$lang->SETTINGS_20?></div>
        </td>
        <td class="right_td">
        <input class="input" id="title" value="<?= $result['title']?>" style="margin-left:5%; margin-right:5%; width:90%;" />
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->SETTINGS_21?>:</div><div class="title_form_small"><?=$lang->SETTINGS_22?></div>
        </td>
        <td class="right_td">
       <select class="input" id="display_title" style="margin-left:5%; margin-right:5%; width:90%;">
        	<option value="D" <? if($result['display_title']=="D") echo 'selected="selected"'; ?>><?=$lang->SETTINGS_23?></option>
            <option value="F" <? if($result['display_title']=="F") echo 'selected="selected"'; ?>><?=$lang->SETTINGS_24?></option>
        </select>
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->SETTINGS_25?>:</div><div class="title_form_small"><?=$lang->SETTINGS_26?></div>
        </td>
        <td class="right_td">
        <textarea class="input-area" rows="5" id="keyword" style="margin-left:5%; margin-right:5%; width:90%;"><?= $result['keywords']?></textarea>
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->SETTINGS_27?>:</div><div class="title_form_small"><?=$lang->SETTINGS_28?></div>
        </td>
        <td class="right_td">
        <textarea class="input-area" id="description" rows="5" style="margin-left:5%; margin-right:5%; width:90%;"><?= $result['description']?></textarea>
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
			<td class="left_td" valign="top">
			<div class="title_form_big"><?=$lang->MOD_104?>:</div><div class="title_form_small"><?=$lang->MOD_105?></div>
			</td>
			<td class="right_td">
			<select class="input" id="translate_lang" style="margin-left:5%; margin-right:5%; width:90%;">
            	<option value="0">-- <?=$lang->MOD_106?> --</option>
				<?php 
					$query2 = $db->query("SELECT ID,name FROM cms_language_front WHERE enabled=1");
					while($result2 = $db->fetch($query2)) {
						if($result2['ID'] == $result['front_lang']) {
							echo '<option value="'.$result2['ID'].'" selected="selected">'.$result2['name'].'</option>';
						} else {
							echo '<option value="'.$result2['ID'].'">'.$result2['name'].'</option>';
						}
					}
				?>
			</select>
			</td>
		</tr>
    <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->SETTINGS_29?>:</div><div class="title_form_small"><?=$lang->SETTINGS_30?></div>
        </td>
        <td class="right_td">
         <select class="input" id="template" style="margin-left:5%; margin-right:5%; width:90%;">
         <?
		 $query1=$db->query("SELECT ID, name FROM cms_template WHERE enabled=1 AND status='N' AND domain='".$user->domain."'");
		 while($result1=$db->fetch($query1)) {
		 ?>
        	<option value="<?=$result1['ID']?>" <? if($result['template']==$result1['ID']) echo 'selected="selected"'; ?>><?=$result1['name']?></option>
         <? } ?>
        </select>
        </td>
    </tr>
     <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->SETTINGS_33?>:</div><div class="title_form_small"><?=$lang->SETTINGS_34?></div>
        </td>
        <td class="right_td">
        <input class="input" id="email" value="<?= $result['email']?>" style="margin-left:5%; margin-right:5%; width:90%;" />
        </td>
    </tr>
     <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->SETTINGS_63?>:</div><div class="title_form_small"><?=$lang->SETTINGS_64?></div>
        </td>
        <td class="right_td" style="text-align:center; cursor:pointer;" onclick="sumo2.sumoSettings.ChangeChacheNumber();">
        	<span style="font-weight:bold;font-size:14px;"><?=$lang->SETTINGS_65?></span>
        </td>
    </tr>
    </table>
    </div>
</form>