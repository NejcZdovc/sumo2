<?php require_once('../initialize.php'); ?>
<form action="" name="a_settings_add_lb" class="form2" onsubmit="return false;">
	<input type="hidden" id="random_number" value="" />
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
                <option value="it"><?php echo $lang->LANG_IT?></option>
                <option value="ru"><?php echo $lang->LANG_RU?></option>
                <option value="sr"><?php echo $lang->LANG_SR?></option>
                <option value="sh"><?php echo $lang->LANG_SH?></option>
                <option value="sl"><?php echo $lang->LANG_SL?></option>
                <option value="es"><?php echo $lang->LANG_ES?></option>
            </select>
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
    <tr>
        <td  class="left_td" valign="top">
        <span class="title_form_big"><?php echo $lang->SETTINGS_53?>:</span><br/><span class="title_form_small"><?php echo $lang->SETTINGS_56?></span>
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