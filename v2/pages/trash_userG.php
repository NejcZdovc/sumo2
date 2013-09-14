<form action="" name="a_trash_userG" method="post" id="a_trash_userG_id">
<?
    $query=$db->query("SELECT ID,title,description,creation,enabled FROM cms_user_groups WHERE status='D' order by ID asc");
?>
	<div class="flt-right display" style="cursor:pointer;"><span onclick="sumo2.trash.SelectAll('a_trash_userG_check', '1');"><?=$lang->MOD_58?></span> / <span onclick="sumo2.trash.SelectAll('a_trash_userG_check', '0');"><?=$lang->MOD_59?></span></div><div class="flt-right display" onclick="sumo2.trash.DeleteAll('a_trash_userG_check', 'userG');" style="cursor:pointer;"><?=$lang->FILE_3?></div>
    <div style="clear:both;"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table1 table2" id="viewgroups" width="99%">
        <tr>
        	<th></th>
            <th><?=$lang->TITLE?></th>
            <th><?=$lang->USER_ADD_D_1?></th>
            <th><?=$lang->USER_NUMBER?></th>
            <th><?=$lang->CREATE_DATE?></th>
            <th><?=$lang->CONTROL?></th>
        </tr>
        <?php 
            $counter = 1;
            while($result = $db->fetch($query)) {			
                    if($counter&1) {
                        echo '<tr class="odd">';
                    } else {
                        echo '<tr class="even">';
                    }
					$group_id = $result['ID'];
					$new_query = $db->query("SELECT ID FROM cms_user WHERE GroupID='".$group_id."'");
					$rows = $db->rows($new_query);
					$description = substr($result['description'],0,80);
					if(strlen($description) >= 50) {
						$description .= "...";
					}
                    ?>
                    	<td><input type="checkbox" name="a_trash_userG_check" value="<?php echo $crypt->encrypt($result['ID']); ?>" /></td>
                        <td><?php echo $result['title'];?></td>
                        <td><div class="sumo2-tooltip" title="<?php echo $result['description']; ?>"><?php echo $description;?></div></td>
                        <td width="130px"><?php echo $rows;?></td>
                        <td width="120px"><?php echo date($lang->DATE_1, strtotime($result['creation']));?></td>
                        <td width="65px">
                        <div style="margin:0 auto; width:45px;">
                            <div title="<?=$lang->MOD_57?>" class="enable sumo2-tooltip" style="margin-right:5px;" onclick="sumo2.trash.Recover('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
                            <div title="<?=$lang->ARTICLE_10?>" class="delete sumo2-tooltip"  onclick="sumo2.trash.Delete('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
                        </div>
                        </td>
                    </tr>
                    <?php 
                    $counter++;
                }
                if($counter==1)
                echo '<tr><td colspan="6" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_76.'</b></td></td>';
        ?>
    </table>
</form>