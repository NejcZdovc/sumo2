<? require_once('../initialize.php');
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
 ?>
<form action="" name="d_settings_add_lf" id="test" method="post" enctype="multipart/form-data" class="form2">
   	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr style="margin-bottom:20px;">
        <td style="width:250px;" class="left_td" valign="top">
        <span class="title_form_big"><?=$lang->SETTINGS_42?>:</span><br /><span class="title_form_small"><?=$lang->SETTINGS_57?></span>
        </td>
        <td class="right_td">
        <input id="name" value="" type="text" class="input" maxlength="50" style="margin-left:5%; margin-right:5%; width:90%;" />
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
        <td style="width:250px;" class="left_td" valign="top">
        <span class="title_form_big"><?=$lang->SETTINGS_58?>:</span><br /><span class="title_form_small"><?=$lang->SETTINGS_59?></span>
        </td>
        <td class="right_td">
           <select id="short" class="input">
                <option value="0">-- <?=$lang->ARTICLE_4?> --</option>
                <option value="hr"><?=$lang->LANG_HR?></option>
                <option value="en"><?=$lang->LANG_EN?></option>
                <option value="en-us"><?=$lang->LANG_EN_US?></option>
                <option value="en-gb"><?=$lang->LANG_EN_GB?></option>
                <option value="de"><?=$lang->LANG_DE?></option>
                <option value="de-lu"><?=$lang->LANG_DE_LU?></option>
                <option value="it"><?=$lang->LANG_IT?></option>
                <option value="ru"><?=$lang->LANG_RU?></option>
                <option value="sr"><?=$lang->LANG_SR?></option>
                <option value="sh"><?=$lang->LANG_SH?></option>
                <option value="sl"><?=$lang->LANG_SL?></option>
                <option value="es"><?=$lang->LANG_ES?></option>
            </select>
        </td>
    </tr>
    </table>
    </div>
</form>