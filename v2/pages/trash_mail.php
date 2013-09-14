<form action="" name="a_trash_mail" method="post" id="a_trash_mail_id">
<?
    $query=$db->query("SELECT ID,mainID FROM cms_mail_sent WHERE status='D' AND recipientID='".$user->userID."' order by ID asc");
?>
	<div class="flt-right display" style="cursor:pointer;"><span onclick="sumo2.trash.SelectAll('a_trash_mail_check', '1');"><?=$lang->MOD_58?></span> / <span onclick="sumo2.trash.SelectAll('a_trash_mail_check', '0');"><?=$lang->MOD_59?></span></div><div class="flt-right display" onclick="sumo2.trash.DeleteAll('a_trash_mail_check', 'mail');" style="cursor:pointer;"><?=$lang->FILE_3?></div>
    <div style="clear:both;"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table1 table2" id="viewgroups" width="99%">
        <tr>
        	<th></th>
            <th><?=$lang->SENT_DATE?></th>
            <th><?=$lang->MAIL_SUBJECT?></th>
            <th><?=$lang->MAIL_FROM?></th>
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
					$mailmain=$db->fetch($db->query("SELECT subject, senderID, date FROM cms_mail_main WHERE ID=".$result['mainID']." LIMIT 1"));
					$user=$db->fetch($db->query("SELECT username FROM cms_user WHERE ID=".$mailmain['senderID']." LIMIT 1"));
                    ?>
                    <td><input type="checkbox" name="a_trash_mail_check" value="<?php echo $crypt->encrypt($result['ID']); ?>" /></td>
					<td><?= date($lang->DATE_1, strtotime($mailmain['date']));?></td>
                    <td><?=AESDecryptCtr($mailmain['subject'],code_hidden_full,256)?></td>
                    <td><?=$user['username']?></td>
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
                echo '<tr><td colspan="5" style="text-align:center; font-size:13px;"><b>'.$lang->MOD_92.'</b></td></td>';
        ?>
    </table>
</form>
