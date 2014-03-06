<form action="" name="a_trash_template" method="post" id="a_trash_template_id">
<?php
    $query=$db->query("SELECT ID,name,folder FROM cms_template WHERE status='D' order by ID asc");
?>
    <table cellpadding="0" cellspacing="0" border="0" class="table1 table2" id="viewgroups" width="99%">
        <tr>
            <th><?php echo $lang->MOD_61?></th>
            <th><?php echo $lang->FILE_5?></th>
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
                    <td><?php echo $result['name']?></td>
                    <td><?php echo $result['folder']?></td>
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
                echo '<tr><td colspan="4" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_88.'</b></td></td>';
        ?>
    </table>
</form>