<? require_once('../initialize.php'); ?>
<form action="" name="d_module_install" id="d_module_install" method="post" enctype="multipart/form-data" class="form2">
	<input type="hidden" id="random_number" value="" />
   	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr>
        <td  class="left_td" valign="top">
        <span class="title_form_big"><?=$lang->SETTINGS_53?>:</span><br/><span class="title_form_small"><?=$lang->SETTINGS_56?></span>
        </td>
    	<td class="right_td" style="padding:5px;">
			<input type="file"  id="uploadify_module_install" />
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
        	<div class="title_form_big"><?=$lang->MOD_225?>:</div><div class="title_form_small"><?=$lang->MOD_227?></div>
        </td>
        <td class="right_td">
            <select id="domain" class="input" multiple="multiple" style="height:52px">
                <?
                    $mainq=$db->query('SELECT * FROM cms_domains WHERE alias="0"');
                    while($mainr=$db->fetch($mainq)) {
						if($user->domain==$mainr['ID'])
							echo '<option value="'.$mainr['ID'].'" selected="selected">'.$mainr['name'].'</option>';
						else
							echo '<option value="'.$mainr['ID'].'">'.$mainr['name'].'</option>';
                    }
                ?>            
            </select>
        </td>
    </tr>
    <tr>
    	<td colspan="2" class="left_td" style="width:100%;"><div id="fileq_mi"></div></td>
    </tr>
    </table>
    </div>
</form>