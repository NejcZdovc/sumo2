<?php require_once('../initialize.php');
	$security->checkFull();
	$language= $crypt->decrypt($db->filter('lang'));
	$cat= $crypt->decrypt($db->filter('cat'));
	$short_old=lang_name_front($crypt->decrypt($db->filter('current')));
	$old=$db->get($db->query('SELECT title, description FROM cms_article_categories WHERE ID='.$cat.''));
 ?>
<form action="" name="d_article_c_translate" method="post" class="form2">
	<input type="hidden" id="parent" value="<?php echo $cat?>" />
    <input type="hidden" id="lang" value="<?php echo $language?>" />
	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->ARTICLE_NEW_T_1?>:</div><div class="title_form_small"><?php echo $lang->ARTICLE_30?></div>
        </td>
   </tr>
   <tr>
        <td class="right_td">
        <div style="float:left; margin-right:30px; width:245px;">
        	<b><?php echo $short_old?> <?php echo $lang->ARTICLE_29?>:</b><br/><br/><?php echo $old['title']?>
        </div>
        <div style="float:left;">
        	<b><?php echo lang_name_front($language)?> <?php echo $lang->ARTICLE_29?>:</b><br/>
            <input name="name" id="name" value="" type="text" maxlength="50" style="width:260px;" class="input" />
            <input type="text" name="enterfix" style="display:none;" />
       	</div>
        </td>
    </tr>
    <tr><td height="10px" width="100%"></td></tr>
    <tr>
        <td colspan="2" class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->USER_ADD_D_1?>:</div><div class="title_form_small"><?php echo $lang->USER_ADD_D_2?></div>
        </td>
    </tr>
    <tr>
    	<td class="right_td">
        <div style="float:left; margin-right:30px; width:245px">
        <b><?php echo $short_old?> <?php echo $lang->ARTICLE_29?>:</b><br/><br/>
        	<?php echo $old['description']?>
        </div>
        <div style="float:left; margin-right:10px;">
        	<b><?php echo lang_name_front($language)?> <?php echo $lang->ARTICLE_29?>:</b><br/><br/>
			<textarea  id="content" class="input-area" name="content" rows="10" cols="28"></textarea>
        </div>
        </td>
    </tr>
    </table>
    </div>
</form>