<?php 
	require_once('../initialize.php');
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 	exit;
	}
?>
<form action="" name="d_seo_redirect_add" method="post" class="form2">
 	<table cellpadding="0" cellspacing="4" border="0" width="99%" >       
        <tr>
            <td colspan="2" class="left_td" valign="top">
            <div class="title_form_big"><?=$lang->MOD_233?>:</div><div class="title_form_small"><?=$lang->MOD_234?></div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="right_td" style="padding:5px;">
                <textarea name="source" class="input-area" rows="3" cols="53"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="left_td" valign="top">
            <div class="title_form_big"><?=$lang->MOD_235?>:</div><div class="title_form_small"><?=$lang->MOD_236?></div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="right_td" style="padding:5px;">
                <textarea  name="destination" class="input-area" rows="3" cols="53"></textarea>
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
                	<option value="300 Multiple Choices">300 Multiple Choices</option>
                	<option value="301 Moved Permanently" selected="selected">301 Moved Permanently</option>
                	<option value="302 Found">302 Found</option>
					<option value="303 See Other">303 See Other</option>
                    <option value="304 Not Modified">304 Not Modified</option>
                    <option value="305 Use Proxy">305 Use Proxy</option>
                	<option value="306 Switch Proxy">306 Switch Proxy</option>
					<option value="307 Temporary Redirect">307 Temporary Redirect</option>
                    <option value="308 Permanent Redirect">308 Permanent Redirect</option>
                </select>
            </td>
        </tr>
    </table>
</form>