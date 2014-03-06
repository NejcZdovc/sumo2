<?php 
	require_once('../initialize.php');
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$id = $crypt->decrypt($db->filter('id'));
	$result = $db->get($db->query("SELECT * FROM cms_user WHERE ID='".$id."'"));
	if($result) {
?>
<form action="" name="d_user_edit_user" method="post" class="form2">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <input type="hidden" name="verify" value="<?php echo $db->filter('id'); ?>" />
    <?php
		if($db->is('accId')) {
			echo '<input type="hidden" name="accid" value="'.$db->filter('accId').'" />';	
		} else {
			echo '<input type="hidden" name="accid" value="" />';
		}
	?>
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->NAME?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_N_1?></div>
        </td>
        <td class="right_td">
        <input name="subject" class="input" id="name" value="<?php echo $result['name'];?>" type="text" maxlength="50" />
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->USERNAME?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_U_1?></div>
        </td>
        <td class="right_td">
        <input name="subject" class="input" id="username" readonly="readonly" value="<?php echo $result['username'];?>" type="text" maxlength="50" />
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
    <tr>
        <td class="left_td" valign="top">
		<div class="title_form_big"><?php echo $lang->USER_ADDU_P_10?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_P_11?></div>
        </td>
        <td class="right_td">
        <input name="subject" class="input" id="password" value="" type="password" maxlength="50" onkeyup="sumo2.user.CheckPassword(this, 'd_user_edit_user')" />
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->USER_ADDU_P_3?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_P_4?></div>
        </td>
        <td class="right_td">
        <input name="subject" class="input" id="password2" value="" type="password" maxlength="50" />
        </td>
    </tr>
    <tr>
        <td class="left_td clear" valign="top" colspan="2">
        <div class="flt-left"><div class="title_form_big"><?php echo $lang->USER_ADDU_P_5?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_P_6?></div></div>
        <div class="flt-right password"><div><div id="password_indicator_d_user_edit_user"><div style="display:block;background-image:url(images/css_sprite.png);background-position:-1109px -1634px;width:230px;height:19px;"></div></div></div><div id="password_strength_d_user_edit_user" class="password-title"><?php echo $lang->USER_ADDU_P_12?></div></div>
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
     <tr>
        <td class="left_td" valign="top">
       <div class="title_form_big"><?php echo $lang->USER_ADDU_E_1?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_E_2?></div>
        </td>
        <td class="right_td">
        <input name="subject" class="input" id="email" value="<?php echo $result['email'];?>" type="text" maxlength="50" />
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
       	<div class="title_form_big"><?php echo $lang->USER_ADDU_E_3?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_E_4?></div>
        </td>
        <td class="right_td">
        <input name="subject" class="input" id="email2" value="<?php echo $result['email'];?>" type="text" maxlength="50" />
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
     <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->USER_ADDU_U_2?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_U_3?></div>
        </td>
        <td class="right_td">
        <select id="group">
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
<tr><td height="10px" width="100%" colspan="2"></td></tr>
<tr><td width="100%" colspan="2"><h2><?php echo $lang->MOD_78?></h2></td></tr>
<tr><td height="10px" width="100%" colspan="2"></td></tr>
<?php
	$fieldQuery = $db->query("SELECT * FROM cms_user_fields WHERE enabled='1' AND status='N'");
	$infoResult = $db->get($db->query("SELECT * FROM cms_user_aditional WHERE userID='".$result['ID']."'"));
	if($infoResult) {
		$found = true;
	} else {
		$found = false;
	}
	while($result = $db->fetch($fieldQuery)) {
		switch($result['type']) {
			case 1:
				?>
				<tr>
				<td class="left_td" valign="top">
				<div class="title_form_big"><?php echo $result['labelName']?>:</div><div class="title_form_small"><?php echo $result['description']?></div>
				</td>
				<td class="right_td">
				<input name="<?php echo $result['name']?>" id="<?php echo $result['fieldId']?>#<?php echo $result['type']?>#<?php echo $result['required']?>#<?php echo $result['labelName']?>#<?php echo $result['min']?>" class="input extra" value="<?php echo $found?$infoResult[$result['fieldId']]:''?>" type="text" maxlength="<?php echo $result['max']?>"/>
				</td>
			    </tr>
				<?php
				break;
			case 2:
				?>
					<tr>
					<td colspan="2" class="left_td" valign="top">
					<div class="title_form_big"><?php echo $result['labelName']?>:</div><div class="title_form_small"><?php echo $result['description']?></div>
					</td>
				    </tr>
					<tr>
					    	<td colspan="2" class="right_td" style="padding:5px;">
								<textarea  id="<?php echo $result['fieldId']?>#<?php echo $result['type']?>#<?php echo $result['required']?>#<?php echo $result['labelName']?>#<?php echo $result['min']?>#<?php echo $result['max']?>" name="<?php echo $result['name']?>" rows="10" class="input-area extra" cols="54"><?php echo $found?$infoResult[$result['fieldId']]:''?></textarea>
						</td>
					    
					    </tr>
				<?php
				break;
			case 3:
				?>
				<tr>
				<td class="left_td" valign="top">
				<div class="title_form_big"><?php echo $result['labelName']?>:</div><div class="title_form_small"><?php echo $result['description']?></div>
				</td>
				<td class="right_td">
				<input name="<?php echo $result['name']?>" id="<?php echo $result['fieldId']?>#<?php echo $result['type']?>#<?php echo $result['required']?>#<?php echo $result['labelName']?>#<?php echo $result['min']?>" value="<?php echo $found?$infoResult[$result['fieldId']]:''?>" class="input extra sumo2-dcsibfvs" maxlength="<?php echo $result['max']?>" type="text" readonly="readonly"/>
				</td>
			    </tr>
				<?php
				break;
			case 4:
				?>
				<tr>
				<td class="left_td" valign="top">
				<div class="title_form_big"><?php echo $result['labelName']?>:</div><div class="title_form_small"><?php echo $result['description']?></div>
				</td>
				<td class="right_td">
				<input name="<?php echo $result['name']?>" id="<?php echo $result['fieldId']?>#<?php echo $result['type']?>#<?php echo $result['required']?>#<?php echo $result['labelName']?>#<?php echo $result['min']?>" class="input extra" value="<?php echo $found?$infoResult[$result['fieldId']]:''?>" type="text" maxlength="<?php echo $result['max']?>"/>
				</td>
			    </tr>
				<?php
				break;
			case 5:
				?>
				<tr>
				<td class="left_td" valign="top">
				<div class="title_form_big"><?php echo $result['labelName']?>:</div><div class="title_form_small"><?php echo $result['description']?></div>
				</td>
				<td class="right_td">
				<?php
					$extraArray = explode(",",$result['extra']);
					$counter = 1;
					foreach($extraArray as $item) {
						if($found && $infoResult[$result['fieldId']] == $counter) {
							echo '<input name="'.$result['name'].'" id="'.$result['fieldId'].'#'.$result['type'].'#'.$result['required'].'#'.$result['labelName'].'" class="extra" value="'.$counter.'" checked="checked" type="radio"/> '.$item.'<br />';
						} else {
							echo '<input name="'.$result['name'].'" id="'.$result['fieldId'].'#'.$result['type'].'#'.$result['required'].'#'.$result['labelName'].'" class="extra" value="'.$counter.'" type="radio"/> '.$item.'<br />';
						}
					$counter++;
					}
				?>
				</td>
			    </tr>
				<?php
				break;
			case 6:
				?>
				<tr>
				<td class="left_td" valign="top">
				<div class="title_form_big"><?php echo $result['labelName']?>:</div><div class="title_form_small"><?php echo $result['description']?></div>
				</td>
				<td class="right_td">
				<?php
					$extraArray = explode(",",$result['extra']);
					$counter = 1;
					if($found) {
						$checkArray = explode("!",$infoResult[$result['fieldId']]);
					}
					foreach($extraArray as $item) {
						if($found) {
							$sel = '';
							foreach($checkArray as $value) {
								if($value == $counter) {
									$sel = 'checked="checked"';
									break;
								}
							}
							echo '<input name="'.$result['name'].'" id="'.$result['fieldId'].'#'.$result['type'].'#'.$result['required'].'#'.$result['labelName'].'" class="extra" value="'.$counter.'" '.$sel.' type="checkbox"/> '.$item.'<br />';
						} else {
							echo '<input name="'.$result['name'].'" id="'.$result['fieldId'].'#'.$result['type'].'#'.$result['required'].'#'.$result['labelName'].'" class="extra" value="'.$counter.'" type="checkbox"/> '.$item.'<br />';
						}
					$counter++;
					}
				?>
				</td>
			    </tr>
				<?php
				break;
			case 7:
				?>
				<tr>
				<td class="left_td" valign="top">
				<div class="title_form_big"><?php echo $result['labelName']?>:</div><div class="title_form_small"><?php echo $result['description']?></div>
				</td>
				<td class="right_td">
				<select name="<?php echo $result['name']?>" id="<?php echo $result['fieldId']?>#<?php echo $result['type']?>#<?php echo $result['required']?>#<?php echo $result['labelName']?>" class="extra">
				<?php
					$extraArray = explode(",",$result['extra']);
					$counter = 1;
					foreach($extraArray as $item) {
						if($found && $infoResult[$result['fieldId']] == $counter) {
							echo '<option selected="selected" value="'.$counter.'"> '.$item.'</option>';
						} else {
							echo '<option value="'.$counter.'"> '.$item.'</option>';
						}
					$counter++;
					}
				?>
				</select>
				</td>
			    </tr>
				<?php
				break;
		}
	}
?>
    </table>
</form>
<?php 
	}
?>