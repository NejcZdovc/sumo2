<form action="" name="a_trash_articleG" method="post" id="a_trash_articleG_id">
<?
    $query=$db->query("SELECT ID,title,description,enabled,date,lang FROM cms_article_categories WHERE status='D' order by title asc");
?>
	<div class="flt-right display" style="cursor:pointer;"><span onclick="sumo2.trash.SelectAll('a_trash_articleCat_check', '1');"><?=$lang->MOD_58?></span> / <span onclick="sumo2.trash.SelectAll('a_trash_articleCat_check', '0');"><?=$lang->MOD_59?></span></div><div class="flt-right display" onclick="sumo2.trash.DeleteAll('a_trash_articleCat_check', 'articleCat');" style="cursor:pointer;"><?=$lang->FILE_3?></div>
    <div style="clear:both;"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table1 table2" id="viewgroups" width="99%">
        <tr>
        	<th></th>
            <th><?=$lang->MOD_40?></th>
            <th><?=$lang->TITLE?></th>
            <th><?=$lang->USER_ADD_D_1?></th>
            <th><?=$lang->ARTICLE_1?></th>
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
					$description = substr($result['description'],0,80);
				if(strlen($description) >= 60) {
					$description .= "...";
				}
						$group_id = $result['ID'];
						$rows=0;
						$new_query = $db->query("SELECT ID FROM cms_article WHERE category LIKE '%#??#".$group_id."#??#%' AND status='N'");
						$rows = $db->rows($new_query);
                    ?>
                    	<td><input type="checkbox" name="a_trash_articleCat_check" value="<?php echo $crypt->encrypt($result['ID']); ?>" /></td>
                        <td width="30px;" style="text-align:center;"><img src="images/icons/flags/<?=lang_name_front($result['lang'])?>.png" alt="<?=lang_name_front($result['lang'])?>"/></td>
                        <td><?=	 $result['title'];?></td>
                        <td><?=$description?></td>
                        <td><?= $rows?></td>
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
                echo '<tr><td colspan="10" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_9.'</b></td></td>';
        ?>
    </table>
</form>