<?php 
	require_once('../initialize.php');
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 	exit;
	}
	$result=$db->get($db->query('SELECT * FROM cms_seo_redirects WHERE ID="'.$crypt->decrypt($db->filter('id')).'"'));
?>
<form action="" name="d_seo_redirect_edit" method="post" class="form2">
	<input type="hidden" name="id" value="<?=$db->filter('id')?>" />
 	<table cellpadding="0" cellspacing="4" border="0" width="99%" >       
        <tr>
            <td colspan="2" class="left_td" valign="top">
            <div class="title_form_big"><?=$lang->MOD_233?>:</div><div class="title_form_small"><?=$lang->MOD_234?></div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="right_td" style="padding:5px;">
                <textarea name="source" class="input-area" rows="3" cols="53"><?php echo $result['source']; ?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="left_td" valign="top">
            <div class="title_form_big"><?=$lang->MOD_235?>:</div><div class="title_form_small"><?=$lang->MOD_236?></div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="right_td" style="padding:5px;">
                <textarea  name="destination" class="input-area" rows="3" cols="53"><?php echo $result['destination']; ?></textarea>
            </td>
        </tr>
         <tr>
            <td colspan="2" class="left_td" valign="top">
            <div class="title_form_big"><?=$lang->MOD_237?>:</div><div class="title_form_small"><?=$lang->MOD_238?></div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="right_td" style="padding:5px;">                
               <select name="code" class="input">
                	<option value="300 Multiple Choices" <? if($result['type']=="300 Multiple Choices") echo 'selected="selected"'; ?>>300 Multiple Choices</option>
                	<option value="301 Moved Permanently" <? if($result['type']=="301 Moved Permanently") echo 'selected="selected"'; ?>>301 Moved Permanently</option>
                	<option value="302 Found" <? if($result['type']=="302 Found") echo 'selected="selected"'; ?>>302 Found</option>
					<option value="303 See Other" <? if($result['type']=="303 See Other") echo 'selected="selected"'; ?>>303 See Other</option>
                    <option value="304 Not Modified" <? if($result['type']=="304 Not Modified") echo 'selected="selected"'; ?>>304 Not Modified</option>
                    <option value="305 Use Proxy" <? if($result['type']=="305 Use Proxy") echo 'selected="selected"'; ?>>305 Use Proxy</option>
                	<option value="306 Switch Proxy" <? if($result['type']=="306 Switch Proxy") echo 'selected="selected"'; ?>>306 Switch Proxy</option>
					<option value="307 Temporary Redirect" <? if($result['type']=="307 Temporary Redirect") echo 'selected="selected"'; ?>>307 Temporary Redirect</option>
                    <option value="308 Permanent Redirect" <? if($result['type']=="308 Permanent Redirect") echo 'selected="selected"'; ?>>308 Permanent Redirect</option>
                </select>
            </td>
        </tr>
    </table>
</form>