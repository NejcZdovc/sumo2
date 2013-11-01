<? require_once('../initialize.php'); 
if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
?>
<form action="" name="d_user_add_group" id="d_user_add_group" method="post" class="form2">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->NAME?>:</div><div class="title_form_small"><?=$lang->USER_ADD_N_1?></div>
        </td>
        <td class="right_td">
        <input name="subject" id="name" value="" type="text" maxlength="50" class="input" />
        <input type="text" name="enterfix" style="display:none;" />
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
    <tr>
        <td colspan="2" class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->USER_ADD_D_1?>:</div><div class="title_form_small"><?=$lang->USER_ADD_D_2?></div>
        </td>
    </tr>
    <tr>
    	<td colspan="2" class="right_td" style="padding:5px;">
			<textarea  id="description" name="content" rows="10" class="input-area" cols="50">&nbsp;</textarea>
        </td>
    
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
    <tr>
        <td class="left_td"  style="width:100px;" valign="top">
        <div class="title_form_big"><?=$lang->USER_ADD_A_1?>:</div>
        <div class="title_form_small">
		<?=$lang->USER_ADD_A_2?>
        </div>
 
        </td>
        <td class="right_td" style="padding:10px;">
        <div style="float:left; margin-right:15px; cursor:pointer;"><a onclick="sumo2.user.SelectAll(1);"><?=$lang->SELECT_ALL?></a></div> <div style="float:left; cursor:pointer;"><a onclick="sumo2.user.SelectAll(2);"><?=$lang->MANUAL_SEL?></a></div><div style="clear:both; margin-bottom:10px;"></div>
           <div class="group-permission-wrapper">
                <table width="100%" cellspacing="0" id="sumo2-user-group-permission">
                    <?php
                        $query = $db->query("SELECT ID,title,subtitle FROM cms_favorites_def ORDER BY ID ASC");
                        while($result = $db->fetch($query)) {
                            ?>
                            <tr>
                                <td><input onclick="sumo2.user.ToggleRow(this)" value="sumo2-user-group-sel-<?=$result['ID']?>" type="checkbox" name="select" /></td>
                                <td><?=$lang->$result['title']?> - <?=$lang->$result['subtitle']?></td>
                                <td><select id="sumo2-user-group-sel-<?=$result['ID']?>"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option selected="selected" value="5">5</option></select></td>
                            </tr>
                            <?php
                        }
                    ?>
                </table>
           </div>
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->MOD_225?>:</div><div class="title_form_small"><?=$lang->MOD_226?></div>
        </td>
        <td class="right_td">
            <select id="domain" class="input" multiple="multiple" style="height:60px;">
                <?
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