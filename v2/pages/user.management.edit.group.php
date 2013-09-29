<?php 
	require_once('../initialize.php');
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$id = $crypt->decrypt($db->filter('id'));
	$result = $db->get($db->query("SELECT title,description,access FROM cms_user_groups WHERE ID='".$id."'"));
	if($result) {
	$access = unserialize(urldecode($result['access']));
?>
<form action="" name="d_user_edit_group" id="d_user_edit_group" method="post" class="form2">
	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <input type="hidden" name="subject" id="verify" value="<?php echo $db->filter('id'); ?>" />
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->NAME?>:</div><div class="title_form_small"><?=$lang->USER_ADD_N_1?></div>
        </td>
        <td class="right_td">
        <? if($id==1 && $result['title']=='Super administrator') { ?>
        	<input name="subject" id="name" disabled="disabled" value="<?php echo $result['title']; ?>" type="text" maxlength="50" class="input" />
        <? }else { ?>
        	<input name="subject" id="name" value="<?php echo $result['title']; ?>" type="text" maxlength="50" class="input" />
        <? } ?>
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
			<textarea  id="description" class="input-area" name="content" rows="10" cols="50"><?php echo $result['description']; ?></textarea>
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
    <tr>
        <td class="left_td"  style="width:100px;" valign="top">
        <div class="title_form_big"><?=$lang->USER_ADD_A_1?>:</div><div class="title_form_small"><?=$lang->USER_ADD_A_2?></div>
        </td>
        <td class="right_td" style="padding:10px;">
        <div style="float:left; margin-right:15px; cursor:pointer;"><a onclick="sumo2.user.SelectAll(1);"><?=$lang->SELECT_ALL?></a></div> <div style="float:left; cursor:pointer;"><a onclick="sumo2.user.SelectAll(2);"><?=$lang->MANUAL_SEL?></a></div><div style="clear:both; margin-bottom:10px;"></div>
        <div class="group-permission-wrapper">
                <table width="100%" cellspacing="0" id="sumo2-user-group-permission">
                    <?php
                        $query = $db->query("SELECT ID,title,subtitle FROM cms_favorites_def ORDER BY ID ASC");
                        while($result = $db->fetch($query)) {
							$opt1 = '';
								$opt2 = '';
								$opt3 = '';
								$opt4 = '';
								$opt5 = '';
							if(array_key_exists($result['subtitle'],$access)) {
								$set = ' checked="checked" ';
								$class = ' class="sel" ';
								switch($access[$result['subtitle']]) {
									case 1:
										$opt1 = ' selected="selected" ';
										break;
									case 2:
										$opt2 = ' selected="selected" ';
										break;
									case 3:
										$opt3 = ' selected="selected" ';
										break;
									case 4:
										$opt4 = ' selected="selected" ';
										break;
									case 5:
										$opt5 = ' selected="selected" ';
										break;
								}
							} else {
								$set = '';
								$class = '';
								$opt5 = ' selected="selected" ';
							}
                            ?>
                            <tr<?=$class?>>
                                <td><input onclick="sumo2.user.ToggleRow(this)" value="sumo2-user-group-sel-<?=$result['ID']?>" type="checkbox" name="select" <?=$set?>/></td>
                                <td><?=$lang->$result['title']?> - <?=$lang->$result['subtitle']?></td>
                                <td><select id="sumo2-user-group-sel-<?=$result['ID']?>"><option<?=$opt1?> value="1">1</option><option<?=$opt2?> value="2">2</option><option<?=$opt3?> value="3">3</option><option<?=$opt4?> value="4">4</option><option<?=$opt5?> value="5">5</option></select></td>
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
        	<?
				$domain=array();
				$query=$db->query('SELECT domainID FROM cms_domains_ids WHERE type="group" AND elementID="'.$id.'"');
				while($result=$db->fetch($query)) {
					array_push($domain, $result['domainID']);
				}
			?>
            <select id="domain" class="input" multiple="multiple" style="height:52px;">                
				<?
                    $query=$db->query('SELECT * FROM cms_domains WHERE alias="0"');
                    while($result=$db->fetch($query)) {
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
    </div>
</form>
<?php 
	}
?>