<form action="" name="a_trash_menuI" method="post" id="a_trash_menuI_id">
<?php
    $query=$db->query("SELECT ID,title,description,menuID FROM cms_menus_items WHERE status='D' order by ID asc");
?>
	<div class="flt-right display" style="cursor:pointer;"><span onclick="sumo2.trash.SelectAll('a_trash_menuI_check', '1');"><?php echo $lang->MOD_58?></span> / <span onclick="sumo2.trash.SelectAll('a_trash_menuI_check', '0');"><?php echo $lang->MOD_59?></span></div><div class="flt-right display" onclick="sumo2.trash.DeleteAll('a_trash_menuI_check', 'menuI');" style="cursor:pointer;"><?php echo $lang->FILE_3?></div>
    <div style="clear:both;"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table1 table2" id="viewgroups" width="99%">
        <tr>
        	<th></th>
            <th><?php echo $lang->MOD_40?></th>
            <th><?php echo $lang->TITLE?></th>
            <th><?php echo $lang->USER_ADD_D_1?></th>
            <th><?php echo $lang->MOD_90?></th>
            <th><?php echo $lang->CONTROL?></th>
        </tr>
        <?php 
            $counter = 1;
            while($result = $db->fetch($query)) {			
                    if($counter&1) {
                        echo '<tr class="odd">';
                    } else {
                        echo '<tr class="even">';
                    }
					$group_id = $result['menuID'];
					$items_query = $db->get($db->query("SELECT title,lang FROM cms_menus WHERE ID='".$group_id."'"));
					$description = substr($result['description'],0,80);
					if(strlen($description) >= 50) {
						$description .= "...";
					}
                    ?>
                    <td><input type="checkbox" name="a_trash_menuI_check" value="<?php echo $crypt->encrypt($result['ID']); ?>" /></td>
                	<td width="30px;" style="text-align:center;"><img src="images/icons/flags/<?php echo lang_name_front($items_query['lang'])?>.png" alt="<?php echo lang_name_front($items_query['lang'])?>"/></td>
					<td><?php echo $result['title'];?></td>
					<td><div class="sumo2-tooltip" title="<?php echo $result['description']; ?>"><?php echo $description;?></div></td>
                    <td><?php echo $items_query['title']?></td>
                        <td width="65px">
                        <div style="margin:0 auto; width:45px;">
                            <div title="<?php echo $lang->MOD_57?>" class="enable sumo2-tooltip" style="margin-right:5px;" onclick="sumo2.trash.Recover('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
                            <div title="<?php echo $lang->ARTICLE_10?>" class="delete sumo2-tooltip"  onclick="sumo2.trash.Delete('<?php echo $crypt->encrypt($result['ID']); ?>')"></div>
                        </div>
                        </td>
                    </tr>
                    <?php 
                    $counter++;
                }
                if($counter==1)
                echo '<tr><td colspan="6" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_91.'</b></td></td>';
        ?>
    </table>
</form>