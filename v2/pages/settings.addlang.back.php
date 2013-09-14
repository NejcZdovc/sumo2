<? require_once('../initialize.php'); ?>
<form action="" name="a_settings_add_lb" id="test" method="post" enctype="multipart/form-data" class="form2">
	<input type="hidden" id="random_number" value="" />
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
                <option value="it"><?=$lang->LANG_IT?></option>
                <option value="ru"><?=$lang->LANG_RU?></option>
                <option value="sr"><?=$lang->LANG_SR?></option>
                <option value="sh"><?=$lang->LANG_SH?></option>
                <option value="sl"><?=$lang->LANG_SL?></option>
                <option value="es"><?=$lang->LANG_ES?></option>
            </select>
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
    <tr>
        <td  class="left_td" valign="top">
        <span class="title_form_big"><?=$lang->SETTINGS_53?>:</span><br/><span class="title_form_small"><?=$lang->SETTINGS_56?></span>
        </td>
    	<td class="right_td" style="padding:5px;">
			<input type="file"  id="uploadify3" />
        </td>
    </tr>
    <tr>
    	<td colspan="2" class="left_td" style="width:100%;"><div id="fileq"></div></td>
    </tr>
    </table>
    </div>
</form>