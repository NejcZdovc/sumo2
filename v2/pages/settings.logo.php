<?php require_once('../initialize.php'); ?>
<form action="" name="a_settings_add_t" id="test" method="post" enctype="multipart/form-data" class="form2">
	<input type="hidden" id="random_number" value="" />
   	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr>
        <td  class="left_td" valign="top">
        <span class="title_form_big"><?php echo $lang->SETTINGS_53?>:</span><br/><span class="title_form_small"><?php echo $lang->SETTINGS_54?></span>
        </td>
    	<td class="right_td" style="padding:5px;">
			<input type="file" style="margin-left:auto; margin-right:auto;"  id="uploadify5" />
        </td>
    </tr>
    <tr>
    	<td colspan="2" class="left_td" style="width:100%;"><div id="fileq"></div></td>
    </tr>
    </table>
    </div>
</form>