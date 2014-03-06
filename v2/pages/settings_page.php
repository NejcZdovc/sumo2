<?php 
	$result = $db->get($db->query("SELECT title,email,keywords,description,template,SEO,display_title,offline,front_lang FROM cms_global_settings WHERE domain='".$user->domain."'"));
?>
<form action="" name="a_settings_page" method="post" class="form2">
	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->MOD_25?>:</div><div class="title_form_small"><?php echo $lang->MOD_26?></div>
        </td>
        <td class="right_td">
        <select class="input" id="offline" style="margin-left:5%; margin-right:5%; width:90%;">
        	<option value="Y" <?php if($result['offline']=="Y") echo 'selected="selected"'; ?>><?php echo $lang->MOD_27?></option>
            <option value="N" <?php if($result['offline']=="N") echo 'selected="selected"'; ?>><?php echo $lang->MOD_28?></option>
        </select>
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->SETTINGS_19?>:</div><div class="title_form_small"><?php echo $lang->SETTINGS_20?></div>
        </td>
        <td class="right_td">
        <input class="input" id="title" value="<?php echo  $result['title']?>" style="margin-left:5%; margin-right:5%; width:90%;" />
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->SETTINGS_21?>:</div><div class="title_form_small"><?php echo $lang->SETTINGS_22?></div>
        </td>
        <td class="right_td">
       <select class="input" id="display_title" style="margin-left:5%; margin-right:5%; width:90%;">
        	<option value="D" <?php if($result['display_title']=="D") echo 'selected="selected"'; ?>><?php echo $lang->SETTINGS_23?></option>
            <option value="F" <?php if($result['display_title']=="F") echo 'selected="selected"'; ?>><?php echo $lang->SETTINGS_24?></option>
        </select>
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->SETTINGS_25?>:</div><div class="title_form_small"><?php echo $lang->SETTINGS_26?></div>
        </td>
        <td class="right_td">
        <textarea class="input-area" rows="5" id="keyword" style="margin-left:5%; margin-right:5%; width:90%;"><?php echo  $result['keywords']?></textarea>
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->SETTINGS_27?>:</div><div class="title_form_small"><?php echo $lang->SETTINGS_28?></div>
        </td>
        <td class="right_td">
        <textarea class="input-area" id="description" rows="5" style="margin-left:5%; margin-right:5%; width:90%;"><?php echo  $result['description']?></textarea>
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
			<td class="left_td" valign="top">
			<div class="title_form_big"><?php echo $lang->MOD_104?>:</div><div class="title_form_small"><?php echo $lang->MOD_105?></div>
			</td>
			<td class="right_td">
			<select class="input" id="translate_lang" style="margin-left:5%; margin-right:5%; width:90%;">
            	<option value="0">-- <?php echo $lang->MOD_106?> --</option>
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
        <div class="title_form_big"><?php echo $lang->SETTINGS_29?>:</div><div class="title_form_small"><?php echo $lang->SETTINGS_30?></div>
        </td>
        <td class="right_td">
         <select class="input" id="template" style="margin-left:5%; margin-right:5%; width:90%;">
         <?php
		 $query1=$db->query("SELECT ID, name FROM cms_template WHERE enabled=1 AND status='N' AND domain='".$user->domain."'");
		 while($result1=$db->fetch($query1)) {
		 ?>
        	<option value="<?php echo $result1['ID']?>" <?php if($result['template']==$result1['ID']) echo 'selected="selected"'; ?>><?php echo $result1['name']?></option>
         <?php } ?>
        </select>
        </td>
    </tr>
     <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->SETTINGS_33?>:</div><div class="title_form_small"><?php echo $lang->SETTINGS_34?></div>
        </td>
        <td class="right_td">
        <input class="input" id="email" value="<?php echo  $result['email']?>" style="margin-left:5%; margin-right:5%; width:90%;" />
        </td>
    </tr>
     <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->SETTINGS_63?>:</div><div class="title_form_small"><?php echo $lang->SETTINGS_64?></div>
        </td>
        <td class="right_td" style="text-align:center; cursor:pointer;" onclick="sumo2.sumoSettings.ChangeChacheNumber();">
        	<span style="font-weight:bold;font-size:14px;"><?php echo $lang->SETTINGS_65?></span>
        </td>
    </tr>
    </table>
    </div>
</form>