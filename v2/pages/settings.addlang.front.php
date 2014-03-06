<?php require_once('../initialize.php');
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
 ?>
<form action="" name="d_settings_add_lf" class="form2" onsubmit="return false;">
   	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr style="margin-bottom:20px;">
        <td style="width:250px;" class="left_td" valign="top">
        <span class="title_form_big"><?php echo $lang->SETTINGS_42?>:</span><br /><span class="title_form_small"><?php echo $lang->SETTINGS_57?></span>
        </td>
        <td class="right_td">
        <input id="name" value="" type="text" class="input" maxlength="50" style="margin-left:5%; margin-right:5%; width:90%;" />
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
        <td style="width:250px;" class="left_td" valign="top">
        <span class="title_form_big"><?php echo $lang->SETTINGS_58?>:</span><br /><span class="title_form_small"><?php echo $lang->SETTINGS_59?></span>
        </td>
        <td class="right_td">
           <select id="short" class="input">
                <option value="0">-- <?php echo $lang->ARTICLE_4?> --</option>
                <option value="hr"><?php echo $lang->LANG_HR?></option>
                <option value="en"><?php echo $lang->LANG_EN?></option>
                <option value="en-us"><?php echo $lang->LANG_EN_US?></option>
                <option value="en-gb"><?php echo $lang->LANG_EN_GB?></option>
                <option value="de"><?php echo $lang->LANG_DE?></option>
                <option value="de-lu"><?php echo $lang->LANG_DE_LU?></option>
                <option value="it"><?php echo $lang->LANG_IT?></option>
                <option value="ru"><?php echo $lang->LANG_RU?></option>
                <option value="sr"><?php echo $lang->LANG_SR?></option>
                <option value="sh"><?php echo $lang->LANG_SH?></option>
                <option value="sl"><?php echo $lang->LANG_SL?></option>
                <option value="es"><?php echo $lang->LANG_ES?></option>
            </select>
        </td>
    </tr>
    </table>
    </div>
</form>