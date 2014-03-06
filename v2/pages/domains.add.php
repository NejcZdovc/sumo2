<form action="" name="a_domains_add" id="a_domains_add" method="post" class="form2">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
        <tr>
            <td class="left_td" valign="top">
            	<div class="title_form_big"><?php echo $lang->NAME?>:</div><div class="title_form_small"><?php echo $lang->MOD_210?></div>
            </td>
            <td class="right_td">
            	<input name="name" class="input" value="" type="text" />
            </td>
        </tr>
        <tr>
            <td class="left_td" valign="top">
            	<div class="title_form_big"><?php echo $lang->MOD_211?>:</div><div class="title_form_small"><?php echo $lang->MOD_212?></div>
            </td>
            <td class="right_td">
            	<ul class="domain_alias"></ul>
            </td>
        </tr>
        <tr><td height="10px" width="100%" colspan="2"></td></tr>
        <tr>
            <td class="left_td" valign="top">
            <div class="title_form_big"><?php echo $lang->MOD_213?>:</div><div class="title_form_small"><?php echo $lang->MOD_214?></div>
            </td>
            <td class="right_td">
                <select name="main" class="input">
                    <option value="-1"><?php echo $lang->MOD_215?></option>
                    <?php
                        $query=$db->query('SELECT * FROM cms_domains WHERE alias="0"');
                        while($result=$db->fetch($query)) {
                            echo '<option value="'.$result['ID'].'">'.$result['name'].'</option>';
                        }
                    ?>            
                </select>
            </td>
        </tr>
        <tr>
            <td class="left_td" valign="top">
            <div class="title_form_big"><?php echo $lang->ARTICLE_3?>:</div><div class="title_form_small"><?php echo $lang->MOD_216?></div>
            </td>
            <td class="right_td">
                <select id="domains_language" class="input" multiple="multiple" style="height:52px">
                    <?php
                        $query=$db->query('SELECT * FROM cms_language_front');
                        while($result=$db->fetch($query)) {
                            echo '<option value="'.$result['short'].'">'.$result['name'].'</option>';
                        }	
                    ?>            
                </select>
            </td>
        </tr>
        <tr><td height="10px" width="100%" colspan="2"></td></tr>
        <tr>
            <td class="left_td" valign="top">
            	<div class="title_form_big"><?php echo $lang->MOD_217?>:</div><div class="title_form_small"><?php echo $lang->MOD_218?></div>
            </td>
            <td class="right_td">
            	<ul class="whiteIP greenTag"></ul>
            </td>
        </tr>
        <tr>
            <td class="left_td" valign="top">
            	<div class="title_form_big"><?php echo $lang->MOD_219?>:</div><div class="title_form_small"><?php echo $lang->MOD_220?></div>
            </td>
            <td class="right_td">
            	<ul class="blackIP redTag"></ul>
            </td>
        </tr>
        <tr><td height="10px" width="100%" colspan="2"></td></tr>
        <tr>
            <td class="left_td" valign="top">
            	<div class="title_form_big"><?php echo $lang->MOD_221?>:</div><div class="title_form_small"><?php echo $lang->MOD_222?></div>
            </td>
            <td class="right_td">
                <select name="iplocator" class="input">
                    <option value="-1">-- <?php echo $lang->MOD_58?> --</option>
                    <option value="1"><?php echo $lang->ARTICLE_19?></option>
                    <option value="0"><?php echo $lang->ARTICLE_20?></option>        
                </select>
            </td>
        </tr>
        <tr>
            <td class="left_td" valign="top">
            	<div class="title_form_big"><?php echo $lang->MOD_223?>:</div><div class="title_form_small"><?php echo $lang->MOD_224?></div>
            </td>
            <td class="right_td">
            	<ul class="domain_countries"></ul>
            </td>
        </tr>  
    </table>  
</form>