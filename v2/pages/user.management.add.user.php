<?php require_once('../initialize.php');
if(!$session->isLogedIn() || !$security->checkURL()) {
		exit;
	}
 ?>
<form action="" name="d_user_add_user" method="post" class="form2">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->NAME?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_N_1?></div>
        </td>
        <td class="right_td">
        <input name="name" id="name" class="input" value="" type="text" maxlength="50"/>
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->USERNAME?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_U_1?></div>
        </td>
        <td class="right_td">
        <input name="username" class="input" value="" type="text" maxlength="50" />
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->USER_ADDU_P_1?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_P_2?></div>
        </td>
        <td class="right_td">
        <input name="password1" class="input" value="" type="password" maxlength="50" onkeyup="sumo2.user.CheckPassword(this, 'd_user_add_user')" />
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
		<div class="title_form_big"><?php echo $lang->USER_ADDU_P_3?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_P_4?></div>
        </td>
        <td class="right_td">
        <input name="password2" class="input" value="" type="password" maxlength="50" />
        </td>
    </tr>
    <tr>
        <td class="left_td clear" valign="top" colspan="2">
        <div class="flt-left"><div class="title_form_big"><?php echo $lang->USER_ADDU_P_5?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_P_6?></div></div>
        <div class="flt-right password"><div><div id="password_indicator_d_user_add_user"><div style="display:block;background-image:url(images/css_sprite.png);background-position:-1109px -1634px;width:230px;height:19px;"></div></div></div><div id="password_strength_d_user_add_user" class="password-title"><?php echo $lang->USER_ADDU_P_7?></div></div>
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
     <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->USER_ADDU_E_1?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_E_2?></div>
        </td>
        <td class="right_td">
        <input name="email1" class="input" value="" type="text" maxlength="50" />
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->USER_ADDU_E_3?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_E_4?></div>
        </td>
        <td class="right_td">
        <input name="email2" class="input" value="" type="text" maxlength="50" />
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
     <tr>
        <td class="left_td" valign="top">
		<div class="title_form_big"><?php echo $lang->USER_ADDU_U_2?>:</div><div class="title_form_small"><?php echo $lang->USER_ADDU_U_3?></div>
        </td>
        <td class="right_td">
        <select name="group">
        	<?php 
        		$query = $db->query("SELECT ID,title FROM cms_user_groups WHERE status='N'");
        		while($result = $db->fetch($query)) {
        			echo '<option value="'.$result['ID'].'">'.$result['title'].'</option>';
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
	while($result = $db->fetch($fieldQuery)) {
		switch($result['type']) {
			case 1:
				?>
				<tr>
				<td class="left_td" valign="top">
				<div class="title_form_big"><?php echo $result['labelName']?>:</div><div class="title_form_small"><?php echo $result['description']?></div>
				</td>
				<td class="right_td">
				<input name="<?php echo $result['name']?>" id="<?php echo $result['fieldId']?>#<?php echo $result['type']?>#<?php echo $result['required']?>#<?php echo $result['labelName']?>#<?php echo $result['min']?>" class="input extra" value="" type="text" maxlength="<?php echo $result['max']?>"/>
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
								<textarea  id="<?php echo $result['fieldId']?>#<?php echo $result['type']?>#<?php echo $result['required']?>#<?php echo $result['labelName']?>#<?php echo $result['min']?>#<?php echo $result['max']?>" name="<?php echo $result['name']?>" rows="10" class="input-area extra" cols="54"></textarea>
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
				<input name="<?php echo $result['name']?>" id="<?php echo $result['fieldId']?>#<?php echo $result['type']?>#<?php echo $result['required']?>#<?php echo $result['labelName']?>#<?php echo $result['min']?>" class="input extra sumo2-dcsibfvs" value="" type="text" maxlength="<?php echo $result['max']?>" readonly="readonly"/>
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
				<input name="<?php echo $result['name']?>" id="<?php echo $result['fieldId']?>#<?php echo $result['type']?>#<?php echo $result['required']?>#<?php echo $result['labelName']?>#<?php echo $result['min']?>" class="input extra" value="" type="text" maxlength="<?php echo $result['max']?>"/>
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
						echo '<input name="'.$result['name'].'" id="'.$result['fieldId'].'#'.$result['type'].'#'.$result['required'].'#'.$result['labelName'].'" class="extra" value="'.$counter.'" type="radio"/> '.$item.'<br />';
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
					foreach($extraArray as $item) {
						echo '<input name="'.$result['name'].'" id="'.$result['fieldId'].'#'.$result['type'].'#'.$result['required'].'#'.$result['labelName'].'" class="extra" value="'.$counter.'" type="checkbox"/> '.$item.'<br />';
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
						echo '<option value="'.$counter.'"> '.$item.'</option>';
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
