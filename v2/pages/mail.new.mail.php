<? require_once('../initialize.php'); 
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
?>
<form action="" method="post" class="form2">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr>
        <td class="left_td" width="30%" valign="top">
        <div class="title_form_big"><?=$lang->MAIL_NEW_S_1?>:</div><div class="title_form_small"><?=$lang->MAIL_NEW_S_2?></div>
        </td>
        <td class="right_td" width="70%">
        <input name="subject" id="subject" value="" type="text" maxlength="50" class="input" />
        <input type="text" name="enterfix" style="display:none;" />
        </td>
    </tr>
    <tr>
        <td colspan="2" class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->MAIL_NEW_C_1?>:</div><div class="title_form_small"><?=$lang->MAIL_NEW_C_2?></div>
        </td>
    </tr>
    <tr>
    	<td colspan="2" class="right_td">
        <div id="alerts">
            <noscript>
                <p>
                    <strong>CKEditor requires JavaScript to run</strong>. In a browser with no JavaScript
                    support, like yours, you should still see the contents (HTML data) and you should
                    be able to edit it normally, without a rich editor interface.
                </p>
            </noscript>
		</div>
		<textarea class="ckeditor" id="editor1" name="content" rows="10"></textarea>
        </td>
    
    </tr>
    <tr>
        <td class="left_td"  width="30%" valign="top">
        <div class="title_form_big"><?=$lang->MAIL_NEW_R_1?>:</div><div class="title_form_small"><?=$lang->MAIL_NEW_R_2?></div>
        </td>
        <td class="right_td" style="padding:10px;">
        <div style="float:left; margin-right:15px; cursor:pointer;"><a onclick="sumo2.mail.SelectAll(1);"><?=$lang->SELECT_ALL?></a></div> <div style="float:left; cursor:pointer;"><a onclick="sumo2.mail.SelectAll(2);"><?=$lang->MANUAL_SEL?></a></div><div style="clear:both; margin-bottom:10px;"></div>
        <select multiple="multiple" id="mail_select" name="mail_select" size="10" style="width:250px;">
        <?
		$query = $db->query("SELECT ID, title FROM cms_user_groups WHERE status='N'");
		while($result = $db->fetch($query)) {
			echo '<optgroup label="'.$result['title'].'">';
			$query1 = $db->query("SELECT ID, username, name FROM cms_user WHERE GroupID= ".$result['ID']." AND status='N'");
			while($result1 = $db->fetch($query1)) {
				if($user->id==$result1['ID'])
					echo '<option value="'.$result1['ID'].'" disabled="disabled">'.$result1['username'].' ('.$result1['name'].')</option>';
				else
					echo '<option value="'.$result1['ID'].'">'.$result1['username'].' ('.$result1['name'].')</option>';
			}
			echo '</optgroup>';
		}
		?>
        </select>
        </td>
    </tr>
    </table>
</form>
