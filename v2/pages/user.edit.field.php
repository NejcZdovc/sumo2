<? require_once('../initialize.php'); 
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$id = $crypt->decrypt($db->filter('id'));
	$query = $db->get($db->query("SELECT * FROM cms_user_fields WHERE ID='".$id."'"));
	if($result) {
?>
<form action="" name="d_user_edit_field" method="post" class="form2">
	<input type="hidden" name="verify" value="<?php echo $db->filter('id'); ?>" />
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->MOD_60?>:</div><div class="title_form_small"><?=$lang->MOD_79?></div>
        </td>
        <td class="right_td">
        <input name="fname" id="fname" class="input" value="<?php echo $result['labelName'];?>" type="text" maxlength="50"/>
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->MOD_61?>:</div><div class="title_form_small"><?=$lang->MOD_80?></div>
        </td>
        <td class="right_td">
        <input name="name" id="name" class="input" value="<?php echo $result['name'];?>" type="text" maxlength="50" />
        </td>
    </tr>
<tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->MOD_62?>:</div><div class="title_form_small"><?=$lang->MOD_81?></div>
        </td>
        <td class="right_td">
        <input name="fid" id="fid" class="input" disabled="disabled" value="<?php echo $result['fieldId'];?>" type="text" maxlength="50" />
        </td>
    </tr>
<tr>
					<td colspan="2" class="left_td" valign="top">
					<div class="title_form_big"><?=$lang->BUGS_9?>:</div><div class="title_form_small"><?=$lang->MOD_82?></div>
					</td>
				    </tr>
					<tr>
					    	<td colspan="2" class="right_td" style="padding:5px;">
								<textarea  id="descr" name="descr" rows="3" class="input-area" cols="79"><?php echo $result['description'];?></textarea>
						</td>
					    
					    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
     <tr>
        <td class="left_td" valign="top">
		<div class="title_form_big"><?=$lang->MOD_11?>:</div><div class="title_form_small"><?=$lang->MOD_83?></div>
        </td>
        <td class="right_td">
        <select name="group" disabled="disabled">
        	<?php 
			$selected = 'selected="selected"';
        		echo '<option '.(($result['type'] == 1)?$selected:'').' value="1">'.$lang->MOD_66.'</option>';
			echo '<option '.(($result['type'] == 2)?$selected:'').' value="2">'.$lang->MOD_67.'</option>';
			echo '<option '.(($result['type'] == 3)?$selected:'').' value="3">'.$lang->MOD_68.'</option>';
			echo '<option '.(($result['type'] == 4)?$selected:'').' value="4">'.$lang->MOD_69.'</option>';
			echo '<option '.(($result['type'] == 5)?$selected:'').' value="5">'.$lang->MOD_70.'</option>';
			echo '<option '.(($result['type'] == 6)?$selected:'').' value="6">'.$lang->MOD_71.'</option>';
			echo '<option '.(($result['type'] == 7)?$selected:'').' value="7">'.$lang->MOD_72.'</option>';
        	?>
        </select>
        </td>
    </tr>
<?php
if($result['type'] > 4 && $result['type'] < 8) {
?>
<tr>
					    	<td colspan="2" class="right_td" style="padding:5px;">
								<div style="margin-bottom:5px;"><?=$lang->MOD_84?></div>
								<textarea  id="grouplist" name="grouplist" rows="3" class="input-area" cols="79"><?php echo $result['extra'];?></textarea>
						</td>
					    
					    </tr>
<?php
}
?>
<tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->MOD_63?>:</div><div class="title_form_small"><?=$lang->MOD_85?></div>
        </td>
        <td class="right_td">
	<input name="required" id="required" value="1" type="radio" <?php echo ($result['required'] == 1)?'checked="checked"':''; ?> /> <?=$lang->ARTICLE_19?>&nbsp;&nbsp;<input name="required" id="required" value="0" type="radio" <?php echo ($result['required'] == 0)?'checked="checked"':''; ?> /> <?=$lang->ARTICLE_20?>
        </td>
    </tr>
<?php
if($result['type'] <= 4 || $result['type'] >= 8) {
?>
<tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->MOD_64?>:</div><div class="title_form_small"><?=$lang->MOD_86?></div>
        </td>
        <td class="right_td">
        <input name="min" id="min" class="input" value="<?php echo $result['min'];?>" type="text" maxlength="50" />
        </td>
    </tr>
<tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->MOD_65?>:</div><div class="title_form_small"><?=$lang->MOD_87?></div>
        </td>
        <td class="right_td">
        <input name="max" id="max" class="input" value="<?php echo $result['max'];?>" type="text" maxlength="50" />
        </td>
    </tr>
<?php
}
?>
    </table>
</form>
<?php 
	}
?>
