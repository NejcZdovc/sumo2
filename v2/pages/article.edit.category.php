<?php 
	require_once('../initialize.php');
	$security->checkFull();
	$id = $crypt->decrypt($db->filter('id'));
	$result = $db->get($db->query("SELECT title,description FROM cms_article_categories WHERE ID='".$id."'"));
	if($result) {
?>
<form action="" name="d_article_edit_c" method="post" class="form2">
	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <input type="hidden" name="subject" id="verify" value="<?php echo $db->filter('id'); ?>" />
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->NAME?>:</div><div class="title_form_small"><?php echo $lang->USER_ADD_N_1?></div>
        </td>
        <td class="right_td">
        <input name="subject" id="name" value="<?php echo $result['title']; ?>" type="text" maxlength="50" class="input" />
        <input type="text" name="enterfix" style="display:none;" />
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
    <tr>
        <td colspan="2" class="left_td" valign="top">
		<div class="title_form_big"><?php echo $lang->USER_ADD_D_1?>:</div><div class="title_form_small"><?php echo $lang->USER_ADD_D_2?></div>
        </td>
    </tr>
    <tr>
    	<td colspan="2" class="right_td" style="padding:5px;">
			<textarea  id="description" name="content" class="input-area" rows="10" cols="53"><?php echo $result['description']; ?></textarea>
        </td>
    </tr>
    </table>
    </div>
</form>
<?php 
	}
?>