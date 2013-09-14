<form action="" name="a_trash_article" method="post" id="a_trash_article_id">
<?
    $query=$db->query("SELECT ID,title,category,dateStart,views,dateEnd,lang,author,date, published FROM cms_article WHERE status='D' order by title asc");
?>
	<div class="flt-right display" style="cursor:pointer;"><span onclick="sumo2.trash.SelectAll('a_trash_article_check', '1');"><?=$lang->MOD_58?></span> / <span onclick="sumo2.trash.SelectAll('a_trash_article_check', '0');"><?=$lang->MOD_59?></span></div><div class="flt-right display" onclick="sumo2.trash.DeleteAll('a_trash_article_check', 'article');" style="cursor:pointer;"><?=$lang->MENU_5?></div>
    <div style="clear:both;"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table1 table2" id="viewgroups" width="99%">
        <tr>
        	<th></th>
            <th><?=$lang->MOD_40?></th>
            <th><?=$lang->TITLE?></th>
            <th><?=$lang->ARTICLE_13?></th>
            <th><?=$lang->ARTICLE_5?></th>
            <th><?=$lang->ARTICLE_6?></th>
            <th><?=$lang->ARTICLE_7?></th>
            <th><?=$lang->CREATE_DATE?></th>
            <th width="50"><?=$lang->MOD_56?></th>
            <th><?=$lang->CONTROL?></th>
        </tr>
        <?php 
            $counter = 1;
            while($result = $db->fetch($query)) {			
                
                $group_id = $result['ID'];
				$catt=explode('#??#', $result['category']);
				$kategorija="";
				for($i=0; $i<count($catt)-1; $i++) {
					if($catt[$i] != "") {
						$new_query = $db->fetch($db->query("SELECT title FROM cms_article_categories WHERE ID='".$catt[$i]."'"));
						$kategorija.=$new_query['title'];
						if(count($catt)-1>1 && $i != count($catt)-2)
							$kategorija.=", ";
					}
				}
                $new_query = $db->fetch($db->query("SELECT title FROM cms_article_categories WHERE ID='".$result['category']."'"));
                    if($counter&1) {
                        echo '<tr class="odd">';
                    } else {
                        echo '<tr class="even">';
                    }
                    ?>
                    	<td><input type="checkbox" name="a_trash_article_check" value="<?php echo $crypt->encrypt($result['ID']); ?>" /></td>
                        <td width="30px;" style="text-align:center;"><img src="images/icons/flags/<?=lang_name_front($result['lang'])?>.png" alt="<?=lang_name_front($result['lang'])?>"/></td>
                        <td><?php echo $result['title'];?></td>
                        <td><?=$kategorija?></td>
                        <td><?php echo $result['author'];?></td>
                        <td><?php if($result['dateStart']==0) echo $lang->ARTICLE_11; else echo date($lang->DATE_1, $result['dateStart']);?></td>
                        <td><?php if($result['dateEnd']==0) echo $lang->ARTICLE_12; else  echo date($lang->DATE_1, $result['dateEnd']);?></td>
                        <td><?php echo  date($lang->DATE_1, $result['date']);?></td>
                        <td><?php echo $result['views'];?></td>
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
                echo '<tr><td colspan="11" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_10.'</b></td></td>';
        ?>
    </table>
</form>