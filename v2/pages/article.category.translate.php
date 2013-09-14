<?php require_once('../initialize.php');
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 	exit;
	}
	$language= $crypt->decrypt($db->filter('lang'));
	$cat= $crypt->decrypt($db->filter('cat'));
	$short_old=lang_name_front($crypt->decrypt($db->filter('current')));
	$old=$db->get($db->query('SELECT title, description FROM cms_article_categories WHERE ID='.$cat.''));
 ?>
<form action="" name="d_article_c_translate" method="post" class="form2">
	<input type="hidden" id="parent" value="<?=$cat?>" />
    <input type="hidden" id="lang" value="<?=$language?>" />
	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->ARTICLE_NEW_T_1?>:</div><div class="title_form_small"><?=$lang->ARTICLE_30?></div>
        </td>
   </tr>
   <tr>
        <td class="right_td">
        <div style="float:left; margin-right:30px; width:245px;">
        	<b><?=$short_old?> <?=$lang->ARTICLE_29?>:</b><br/><br/><?=$old['title']?>
        </div>
        <div style="float:left;">
        	<b><?=lang_name_front($language)?> <?=$lang->ARTICLE_29?>:</b><br/>
            <input name="name" id="name" value="" type="text" maxlength="50" style="width:260px;" class="input" />
            <input type="text" name="enterfix" style="display:none;" />
       	</div>
        
        </td>
    </tr>
    <tr><td height="10px" width="100%"></td></tr>
    <tr>
        <td colspan="2" class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->USER_ADD_D_1?>:</div><div class="title_form_small"><?=$lang->USER_ADD_D_2?></div>
        </td>
    </tr>
    <tr>
    	<td class="right_td">
        <div style="float:left; margin-right:30px; width:245px">
        <b><?=$short_old?> <?=$lang->ARTICLE_29?>:</b><br/><br/>
        	<?=$old['description']?>
        </div>
        <div style="float:left; margin-right:10px;">
        	<b><?=lang_name_front($language)?> <?=$lang->ARTICLE_29?>:</b><br/><br/>
			<textarea  id="content" class="input-area" name="content" rows="10" cols="28"></textarea>
        </div>    
        </td>
    </tr>
    </table>
    </div>
</form>