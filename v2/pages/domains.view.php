<table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr>
        <td class="left_td" valign="top">
        	<div class="title_form_big"><?=$lang->NAME?>:</div><div class="title_form_small"><?=$lang->MOD_210?></div>
        </td>
        <td class="right_td">
        	<input id="name" class="input" value="<?=$result['name']?>" type="text" disabled="disabled" />
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
        	<div class="title_form_big"><?=$lang->MOD_211?>:</div><div class="title_form_small"><?=$lang->MOD_212?></div>
        </td>
        <td class="right_td">
            <ul class="domain_alias">
            <?
                $aliasq=$db->query('SELECT name FROM cms_domains WHERE alias="1" AND parentID="'.$result['ID'].'"');
                while($aliasr=$db->fetch($aliasq)) {
                    echo '<li data-value="'.$aliasr['name'].'">'.$aliasr['name'].'</li>';
                }		
            ?>
            </ul>
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
    <tr>
        <td class="left_td" valign="top">
        	<div class="title_form_big"><?=$lang->MOD_213?>:</div><div class="title_form_small"><?=$lang->MOD_214?></div>
        </td>
        <td class="right_td">
            <select id="main" class="input">
                <option value="-1" <? if($result['parentID']=="-1") echo 'selected="selected"';?>><?=$lang->MOD_215?></option>
                <?
                    $mainq=$db->query('SELECT * FROM cms_domains WHERE alias="0" AND ID!="'.$result['ID'].'"');
                    while($mainr=$db->fetch($mainq)) {
						if($result['parentID']==$mainr['ID'])
                        	echo '<option value="'.$mainr['ID'].'" selected="selected">'.$mainr['name'].'</option>';
						else
							echo '<option value="'.$mainr['ID'].'">'.$mainr['name'].'</option>';
                    }
                ?>            
            </select>
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
        	<div class="title_form_big"><?=$lang->ARTICLE_3?>:</div><div class="title_form_small"><?=$lang->MOD_216?></div>
        </td>
        <td class="right_td">
        		<?
					$langArray=array();
					$langq=$db->query('SELECT value FROM cms_domains_ids WHERE domainID="'.$result['ID'].'" AND type="lang"');
					while($langr=$db->fetch($langq)) {
						array_push($langArray, $langr['value']);						
					}
				?>
            <select id="domains_language" class="input" multiple="multiple" style="height:52px">
                <?
					
                    $langq=$db->query('SELECT * FROM cms_language_front');
                    while($langr=$db->fetch($langq)) {						
						if(in_array($langr['short'], $langArray))
                        	echo '<option value="'.$langr['short'].'" selected="selected">'.$langr['name'].'</option>';
						else
							echo '<option value="'.$langr['short'].'">'.$langr['name'].'</option>';
                    }	
                ?>            
            </select>
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
    <tr>
        <td class="left_td" valign="top">
        	<div class="title_form_big"><?=$lang->MOD_217?>:</div><div class="title_form_small"><?=$lang->MOD_218?></div>
        </td>
        <td class="right_td">
            <ul class="whiteIP greenTag">
            <?
                $ipq=$db->query('SELECT value FROM cms_domains_ips WHERE type="1" AND domainID="'.$result['ID'].'"');
                while($ipr=$db->fetch($ipq)) {
                    echo '<li data-value="'.$ipr['value'].'">'.$ipr['value'].'</li>';
                }		
            ?>
            </ul>
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
        	<div class="title_form_big"><?=$lang->MOD_219?>:</div><div class="title_form_small"><?=$lang->MOD_220?></div>
        </td>
        <td class="right_td">
            <ul class="blackIP redTag">
            <?
                $ipq=$db->query('SELECT value FROM cms_domains_ips WHERE type="0" AND domainID="'.$result['ID'].'"');
                while($ipr=$db->fetch($ipq)) {
                    echo '<li data-value="'.$ipr['value'].'">'.$ipr['value'].'</li>';
                }		
            ?>
            </ul>
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
    <tr>
        <td class="left_td" valign="top">
        	<div class="title_form_big"><?=$lang->MOD_221?>:</div><div class="title_form_small"><?=$lang->MOD_222?></div>
        </td>
        <td class="right_td">
            <select id="iplocator" class="input">
                <option value="-1">-- <?=$lang->MOD_58?> --</option>
                <option value="1" <? if($result['locator']=="1") echo 'selected="selected"';?>><?=$lang->ARTICLE_19?></option>
                <option value="0" <? if($result['locator']=="0") echo 'selected="selected"';?>><?=$lang->ARTICLE_20?></option>        
            </select>
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
        	<div class="title_form_big"><?=$lang->MOD_223?>:</div><div class="title_form_small"><?=$lang->MOD_224?></div>
        </td>
        <td class="right_td">
            <ul class="domain_countries">
            <?
                $ipq=$db->query('SELECT value FROM cms_domains_countries WHERE domainID="'.$result['ID'].'"');
                while($ipr=$db->fetch($ipq)) {
                    echo '<li data-value="'.$ipr['value'].'">'.$ipr['value'].'</li>';
                }		
            ?>
            </ul>
        </td>
    </tr> 
</table>