<? require_once('../initialize.php'); ?>
<form action="" name="d_module_edit" id="d_module_edit" method="post" enctype="multipart/form-data" class="form2">
	<input type="hidden" value="<?=$db->filter('id')?>" name="id" />
    <input type="hidden" value="<?=$db->filter('type')?>" name="type" />
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
        <tr>
            <td class="left_td" valign="top">
                <div class="title_form_big"><?=$lang->MOD_225?>:</div><div class="title_form_small"><?=$lang->MOD_227?></div>
            </td>
            <td class="right_td">
                <?
                    $domain=array();
                    $query=$db->query('SELECT domainID FROM cms_domains_ids WHERE type="'.$db->filter('type').'" AND elementID="'.$crypt->decrypt($db->filter('id')).'"');
                    while($result=$db->fetch($query)) {
                        array_push($domain, $result['domainID']);
                    }
                ?>
                <select id="domain" class="input" multiple="multiple" style="height:100px">
                    <?
                        $mainq=$db->query('SELECT * FROM cms_domains WHERE alias="0"');
                        while($result=$db->fetch($mainq)) {
                                if(in_array($result['ID'], $domain))
                                echo '<option value="'.$result['ID'].'" selected="selected">'.$result['name'].'</option>';
                            else
                                echo '<option value="'.$result['ID'].'">'.$result['name'].'</option>';
                        }
                    ?>            
                </select>
            </td>
        </tr>
    </table>
</form>