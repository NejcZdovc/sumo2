<?php require_once('../initialize.php'); ?>
<form action="" name="a_settings_add_t" class="form2">
	<input type="hidden" id="random_number" value="" />
   	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr style="margin-bottom:20px;">
        <td style="width:250px;" class="left_td" valign="top">
        <span class="title_form_big"><?php echo $lang->SETTINGS_42?>:</span><br /><span class="title_form_small"><?php echo $lang->SETTINGS_55?> </span>
        </td>
        <td class="right_td">
        <input id="name" value="" type="text" class="input" maxlength="50" style="margin-left:5%; margin-right:5%; width:90%;" />
        <input type="text" name="enterfix" style="display:none;" />
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
    <tr>
        <td  class="left_td" valign="top">
        <span class="title_form_big"><?php echo $lang->SETTINGS_53?>:</span><br/><span class="title_form_small"><?php echo $lang->SETTINGS_56?></span>
        </td>
    	<td class="right_td" style="padding:5px;">
			<input type="file"  id="uploadify2" />
        </td>
    </tr>
    <tr>
    	<td colspan="2" class="left_td" style="width:100%;"><div id="fileq"></div></td>
    </tr>
    </table>
    </div>
</form>