<?php require_once('../initialize.php'); $security->checkFull(); ?>
<form action="" name="d_menus_new_m" method="post" class="form2">
	<input value="" id="lang_id" type="hidden" />
	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->NAME?>:</div><div class="title_form_small"><?php echo $lang->MENU_21?>.</div>
        </td>
        <td class="right_td">
        <input name="name" id="name" value="" type="text" maxlength="50" class="input" />
        <input type="text" name="enterfix" style="display:none;" />
        </td>
    </tr>
    <tr>
            <td colspan="2" class="left_td" valign="top">
                <div class="title_form_big"><?php echo $lang->USER_ADD_D_1?>:</div><div class="title_form_small"><?php echo $lang->MENU_3?></div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="right_td" style="padding:5px;">
                <textarea  id="contentd" class="input-area" name="content" rows="10" cols="53"></textarea>
            </td>
        
    </tr>
  	</table>
    </div>
</form>
