<?php 
	$result = $db->get($db->query("SELECT username,email,GroupID,name FROM cms_user WHERE ID='".$user->id."'"));
	if($result) {
?>
<form action="" name="a_settings_user" method="post" class="form2">
	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <input type="hidden" name="subject" id="verify" value="<?php echo $id; ?>" />
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->NAME?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_N_1?></div>
        </td>
        <td class="right_td">
        <input name="subject" id="name" class="input" value="<?php echo  $result['name'];?>" type="text" maxlength="50" />
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->USERNAME?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_U_1?></div>
        </td>
        <td class="right_td">
        <input name="subject" id="username" class="input" readonly="readonly" value="<?php echo $result['username'];?>" type="text" maxlength="50"  />
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
    <tr>
        <td class="left_td" valign="top">
		<div class="title_form_big"><?php echo $lang->USER_ADDU_P_8?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_P_9?></div>
        </td>
        <td class="right_td">
        <input name="subject" id="oldpassword" class="input" value="" type="password" maxlength="50" />
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
		<div class="title_form_big"><?php echo $lang->USER_ADDU_P_10?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_P_11?></div>
        </td>
        <td class="right_td">
        <input name="subject" id="password" value="" class="input" type="password" maxlength="50" onkeyup="sumo2.user.CheckPassword(this, 'a_settings_user')" />
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->USER_ADDU_P_3?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_P_4?></div>
        </td>
        <td class="right_td">
        <input name="subject" id="password2" value="" type="password" maxlength="50" class="input" />
        </td>
    </tr>
    <tr>
        <td class="left_td clear" valign="top" colspan="2">
        <div style="float: left"><div class="title_form_big"><?php echo $lang->USER_ADDU_P_5?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_P_6?></div></div>
        <div class="flt-right password"><div><div id="password_indicator_a_settings_user"><div style="display:block;background-image:url(images/css_sprite.png);background-position:-1109px -1634px;width:230px;height:19px;"></div></div></div><div id="password_strength_a_settings_user" class="password-title"><?php echo $lang->USER_ADDU_P_7?></div></div>
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
     <tr>
        <td class="left_td" valign="top">
       <div class="title_form_big"><?php echo $lang->USER_ADDU_E_1?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_E_2?></div>
        </td>
        <td class="right_td">
        <input name="subject" id="email" value="<?php echo $result['email'];?>" type="text" maxlength="50" class="input" />
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
       	<div class="title_form_big"><?php echo $lang->USER_ADDU_E_3?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_E_4?></div>
        </td>
        <td class="right_td">
        <input name="subject" id="email2" value="<?php echo $result['email'];?>" type="text" maxlength="50" class="input"  />
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
     <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->USER_ADDU_U_2?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_U_3?></div>
        </td>
        <td class="right_td">
        <select id="group" disabled="disabled">
        	<?php 
        		$query2 = $db->query("SELECT ID,title FROM cms_user_groups WHERE status='N'");
        		while($result2 = $db->fetch($query2)) {
        			if($result2['ID'] == $result['GroupID']) {
        				echo '<option value="'.$result2['ID'].'" selected="selected">'.$result2['title'].'</option>';
        			} else {
        				echo '<option value="'.$result2['ID'].'">'.$result2['title'].'</option>';
        			}
        		}
        	?>
        </select>
        </td>
    </tr>
    </table>
    </div>
</form>
<?php 
	}
?>
