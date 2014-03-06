<?php require_once('../initialize.php'); 
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$results=$db->fetch($db->query('SELECT ID,title,description FROM cms_menus  WHERE ID='.$crypt->decrypt($db->filter('id')).''));
?>
<form action="" name="d_menus_edit_m" method="post" class="form2">
	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->NAME?>:</div><div class="title_form_small"><?php echo $lang->MENU_21?></div>
        </td>
        <td class="right_td">
        <input type="hidden" id="id" value="<?php echo  $db->filter('id')?>" />
        <input name="name" id="name" value="<?php echo $results['title']?>" type="text" maxlength="50" class="input" />
        <input type="text" name="enterfix" style="display:none;" />
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
    <tr>
        <td colspan="2" class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->USER_ADD_D_1?>:</div><div class="title_form_small"><?php echo $lang->MENU_3?></div>
        </td>
    </tr>
    <tr>
    	<td colspan="2" class="right_td" style="padding:5px;">
			<textarea  id="contentd" class="input-area" name="content" rows="10" cols="50"><?php echo $results['description']?></textarea>
        </td>
    
    </tr>
    </table>
    </div>
</form>
