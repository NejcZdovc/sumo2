<form action="" name="a_trash_menu" method="post" id="a_trash_menu_id">
<?
    $query=$db->query("SELECT ID,title,description,status,date,enabled,lang,parent,s_default FROM cms_menus WHERE status='D' order by ID asc");
?>
	<div class="flt-right display" style="cursor:pointer;"><span onclick="sumo2.trash.SelectAll('a_trash_menu_check', '1');"><?=$lang->MOD_58?></span> / <span onclick="sumo2.trash.SelectAll('a_trash_menu_check', '0');"><?=$lang->MOD_59?></span></div><div class="flt-right display" onclick="sumo2.trash.DeleteAll('a_trash_menu_check', 'menu');" style="cursor:pointer;"><?=$lang->FILE_3?></div>
    <div style="clear:both;"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table1 table2" id="viewgroups" width="99%">
        <tr>
        	<th></th>
            <th><?=$lang->MOD_40?></th>
            <th><?=$lang->TITLE?></th>
            <th><?=$lang->USER_ADD_D_1?></th>
            <th><?=$lang->MENU_12?></th>
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
					$items_query = $db->query("SELECT ID FROM cms_menus_items WHERE menuID='".$group_id."'");
					$rows = $db->rows($items_query);
					$description = substr($result['description'],0,80);
					if(strlen($description) >= 50) {
						$description .= "...";
					}
                    ?>
                    <td><input type="checkbox" name="a_trash_menu_check" value="<?php echo $crypt->encrypt($result['ID']); ?>" /></td>
                	<td width="30px;" style="text-align:center;"><img src="images/icons/flags/<?=lang_name_front($result['lang'])?>.png" alt="<?=lang_name_front($result['lang'])?>"/></td>
					<td><?php echo $result['title'];?></td>
					<td><div class="sumo2-tooltip" title="<?php echo $result['description']; ?>"><?php echo $description;?></div></td>
					<td width="200px;"><?php echo $rows?></td>
					<td><?php echo date($lang->DATE_1, strtotime($result['date']));?></td>
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
                echo '<tr><td colspan="7" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_8.'</b></td></td>';
        ?>
    </table>
</form>