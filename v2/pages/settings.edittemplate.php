<?php require_once('../initialize.php'); 
$security->checkFull();
$id = $crypt->decrypt($db->filter('ID'));
$new_query = $db->get($db->query("SELECT name FROM cms_template WHERE ID='".$id."'"));

?>
<form action="" name="a_settings_edit_t" id="test" method="post" enctype="multipart/form-data" class="form2">
   	<div class="">
    <input type="hidden" id="id" value="<?php echo $db->filter('ID')?>" />
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr style="margin-bottom:20px;">
        <td style="width:250px;" class="left_td" valign="top">
        <span class="title_form_big"><?php echo $lang->SETTINGS_42?>:</span><br /><span class="title_form_small"><?php echo $lang->SETTINGS_55?></span>
        </td>
        <td class="right_td">
        <input id="name" value="<?php echo $new_query['name']?>" type="text" maxlength="50" style="margin-left:5%; margin-right:5%; width:90%;" />
        <input type="text" name="enterfix" style="display:none;" />
        </td>
    </tr>
    </table>
    </div>
</form>