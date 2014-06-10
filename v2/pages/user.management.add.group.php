<?php require_once('../initialize.php'); 
		$security->checkFull();
?>
<form action="" name="d_user_add_group" id="d_user_add_group" method="post" class="form2">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->NAME?>:</div><div class="title_form_small"><?php echo $lang->USER_ADD_N_1?></div>
        </td>
        <td class="right_td">
        <input name="subject" id="name" value="" type="text" maxlength="50" class="input" />
        <input type="text" name="enterfix" style="display:none;" />
        </td>
    </tr>
    <tr>
        <td colspan="2" class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->USER_ADD_D_1?>:</div><div class="title_form_small"><?php echo $lang->USER_ADD_D_2?></div>
        </td>
    </tr>
    <tr>
    	<td colspan="2" class="right_td" style="padding:5px;">
			<textarea  id="description" name="content" rows="5" class="input-area" style="width: 99%">&nbsp;</textarea>
        </td>    
    </tr>
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->MOD_225?>:</div><div class="title_form_small"><?php echo $lang->MOD_226?></div>
        </td>
        <td class="right_td">
            <select id="domain" class="input" multiple="multiple" style="height:100px;">
                <?php
                    $query=$db->query('SELECT * FROM cms_domains WHERE alias="0"');
                    while($result=$db->fetch($query)) {
                        echo '<option value="'.$result['ID'].'">'.$result['name'].'</option>';
                    }
                ?>            
            </select>
        </td>
    </tr>
    </table>
    </div>
</form>