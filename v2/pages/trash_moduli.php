<form action="" name="a_trash_moduli" method="post" id="a_trash_moduli_id">
<?php
    $query=$db->query("SELECT ID,name,moduleName,editTable,date FROM cms_modules_def WHERE status='D' order by ID asc");
?>
	<div class="flt-right display" style="cursor:pointer;"><span onclick="sumo2.trash.SelectAll('a_trash_modul_check', '1');"><?php echo $lang->MOD_58?></span> / <span onclick="sumo2.trash.SelectAll('a_trash_modul_check', '0');"><?php echo $lang->MOD_59?></span></div><div class="flt-right display" onclick="sumo2.trash.DeleteAll('a_trash_modul_check');" style="cursor:pointer;"><?php echo $lang->FILE_3?></div>
    <div style="clear:both;"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table1 table2" id="viewgroups" width="99%">
        <tr>
        	<th></th>
            <th><?php echo $lang->MOD_61?></th>
            <th><?php echo $lang->FILE_5?></th>
            <th><?php echo $lang->MOD_68?></th>
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
                    ?>
                    <td width="20"><input type="checkbox" name="a_trash_modul_check" value="<?php echo $crypt->encrypt($result['ID']); ?>" /></td>
                    <td><?php echo $result['name']?></td>
                    <td><?php echo $result['moduleName']?></td>
                    <td><?php echo  date($lang->DATE_1, strtotime($result['date']));?></td>
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
                echo '<tr><td colspan="6" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_89.'</b></td></td>';
        ?>
    </table>
</form>