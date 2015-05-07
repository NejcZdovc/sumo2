<?php 
	require_once('../initialize.php');
	$security->checkFull();
?>
<table cellpadding="0" cellspacing="4" border="0" width="100%" >
    <input type="hidden" name="verify" value="<?php echo $db->filter('id'); ?>" />
    <tr>
        <td class="left_td" valign="top" style="width:25%">
            <div class="title_form_big"><?php echo $lang->NAME?>:</div><div class="title_form_small"><?php echo $lang->USER_ADD_N_1?></div>
        </td>
        <td class="right_td" colspan="3" style="width:75%">
        	<input name="title" value="<?php echo $result['title']; ?>" type="text" maxlength="50" class="input" />
            <input type="text" name="enterfix" style="display:none;" />
        </td>
    </tr>
    <tr>
        <td colspan="4" class="left_td" valign="top">
            <div class="title_form_big"><?php echo $lang->USER_ADD_D_1?>:</div><div class="title_form_small"><?php echo $lang->USER_ADD_D_2?></div>
        </td>
    </tr>
    <tr>
    	<td colspan="4" class="right_td" style="padding:5px;">
			<textarea id="description" class="input-area" name="content" rows="4" style="width: 99%;"><?php echo $result['description']; ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top" colspan="2" style="width:50%">
        <div class="title_form_big"><?php echo $lang->MOD_225?>:</div><div class="title_form_small"><?php echo $lang->MOD_226?></div>
        </td>
        <td class="right_td" colspan="2" style="width:50%">
        	<?php
				$domain=array();
				$q=$db->query('SELECT domainID FROM cms_domains_ids WHERE type="group" AND elementID="'.$id.'"');
				while($r=$db->fetch($q)) {
					array_push($domain, $r['domainID']);
				}
			?>
            <select id="domain" class="input" multiple="multiple" style="height:100px;">                
				<?php
                    $q=$db->query('SELECT * FROM cms_domains WHERE alias="0"');
                    while($r=$db->fetch($q)) {
						if(in_array($r['ID'], $domain))
                        	echo '<option value="'.$r['ID'].'" selected="selected">'.$r['name'].'</option>';
						else
							echo '<option value="'.$r['ID'].'">'.$r['name'].'</option>';
                    }
                ?>            
            </select>
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top" style="width:25%">
            <div class="title_form_big"><?php echo $lang->MOD_168?>:</div><div class="title_form_small"><?php echo $lang->MOD_251?></div>
        </td>
        <td class="right_td" style="width:25%">
            <select name="cache" class="input">
                <option value="-1">--<?php echo $lang->ARTICLE_4?>--</option>
                <option value="1" <?php if($result['cache']=="1") echo 'selected="selected"'; ?>><?php echo $lang->ARTICLE_19?></option>
                <option value="0" <?php if($result['cache']=="0") echo 'selected="selected"'; ?>><?php echo $lang->ARTICLE_20?></option>
            </select>
        </td>
        <td class="left_td" valign="top" style="width:25%">
            <div class="title_form_big"><?php echo $lang->SETTINGS_51?>:</div><div class="title_form_small"><?php echo $lang->MOD_252?></div>
        </td>
        <td class="right_td" style="width:25%">
            <select name="errorLog" class="input">
                <option value="-1">--<?php echo $lang->ARTICLE_4?>--</option>
                <option value="1" <?php if($result['errorLog']=="1") echo 'selected="selected"'; ?>><?php echo $lang->ARTICLE_19?></option>
                <option value="0" <?php if($result['errorLog']=="0") echo 'selected="selected"'; ?>><?php echo $lang->ARTICLE_20?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top" style="width:25%">
            <div class="title_form_big"><?php echo $lang->SETTINGS_66?>:</div><div class="title_form_small"><?php echo $lang->MOD_253?></div>
        </td>
        <td class="right_td" style="width:25%">
            <select name="dataLog" class="input">
                <option value="-1">--<?php echo $lang->ARTICLE_4?>--</option>
                <option value="1" <?php if($result['dataLog']=="1") echo 'selected="selected"'; ?>><?php echo $lang->ARTICLE_19?></option>
                <option value="0" <?php if($result['dataLog']=="0") echo 'selected="selected"'; ?>><?php echo $lang->ARTICLE_20?></option>
            </select>
        </td>
        <td class="left_td" valign="top" style="width:25%">
            <div class="title_form_big"><?php echo $lang->MOD_254?>:</div><div class="title_form_small"><?php echo $lang->MOD_255?></div>
        </td>
        <td class="right_td"  style="width:25%">
            <select name="backendLogin" class="input">
                <option value="-1">--<?php echo $lang->ARTICLE_4?>--</option>
                <option value="1" <?php if($result['login']=="1") echo 'selected="selected"'; ?>><?php echo $lang->ARTICLE_19?></option>
                <option value="0" <?php if($result['login']=="0") echo 'selected="selected"'; ?>><?php echo $lang->ARTICLE_20?></option>
            </select>
        </td>
    </tr>
    <tr>
		<td height="10px" width="100%" colspan="2"></td>
	</tr>
</table>