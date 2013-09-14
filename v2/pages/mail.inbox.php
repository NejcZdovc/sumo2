<? require_once('../initialize.php'); 
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$accordion_id='a_mail_inbox';
	$pagging=check_pagging("SELECT ID, mainID, date, status FROM cms_mail_sent WHERE recipientID='".$user->id."' and status!='D' and status!='DD' ORDER BY status desc, date desc ", $user->items);
	$dropdown=dropdown_pagging($accordion_id, $pagging[0]);
	echo '<div class="flt-right display">'.$dropdown.'</div>';
?>
<div id="a_mail_inbox_table" style="clear:both;">
<table cellpadding="0" cellspacing="1" border="0" class="table1 table2" width="99%">
	<tr>
    	<th></th>
    	<th><?=$lang->MAIL_FROM?></th>
		<th><?=$lang->MAIL_SUBJECT?></th>
		<th><?=$lang->SENT_DATE?></th>
		<th><?=$lang->CONTROL?></th>
	</tr>
	<? 	
	$query = $db->query($pagging[4]);
	$counter = 1;
	while($result = $db->fetch($query)) {
		if($counter&1)
			echo '<tr class="odd">';
		else
			echo '<tr class="even">';
		
		$recipient = "";
		$query1 = $db->query("SELECT subject, senderID, content FROM cms_mail_main WHERE ID=".$result['mainID']."");
		while($result1 = $db->fetch($query1)) {
			$subject=$result1['subject'];
			$content=$result1['content'];
			$query2 = $db->query("SELECT username, name FROM cms_user WHERE ID=".$result1['senderID']."");
			while($result2 = $db->fetch($query2)) {
				$recipient = $result2['username'].' ('.$result2['name'].')';
			}
		}
		$date_now=date($lang->DATE_1, strtotime($result['date']));
		
		if($result['status']=="O")
			echo '<td style="width:25px" class="table-icon"><div class="opened"></div></td>';
		else if($result['status']=="C")
			echo '<td style="width:25px" class="table-icon"><div class="closed"></div></td>';
		echo '<td style="width:15%">'.$recipient.'</td>';
		echo '<td >'.AESDecryptCtr($subject,code_hidden_full,256).'</td>';
		echo '<td style="width:150px; text-align:left;">'.$date_now.'</td>';
		
		/*Tvorimo Notification okno tekst*/
										
		$text ='<u>'.$lang->MAIL_SENT_1.':</u>&nbsp;&nbsp;&nbsp;'. $recipient .'<br/><br/>';
		$text .='<u>'.$lang->SENT_DATE.':</u>&nbsp;&nbsp;&nbsp;'. $date_now .'<br/><br/><hr/>';
		$text .='<br/><div>'. preg_replace('/\n/', '', AESDecryptCtr($content,code_hidden_full,256)) .'</div>';
		
		echo '<td style="width:55px"><div title="'.$lang->MAIL_SENTITEMS_2.'" class="view sumo2-tooltip" onclick="sumo2.dialog.NewNotification(\''.AESDecryptCtr($subject,code_hidden_full,256).'\',\''.$text.'\',450,450,3); sumo2.mail.Open(\''.$crypt->encrypt($result['ID']).'\');sumo2.mail.CheckMain();"></div><div title="'.$lang->MAIL_SENTITEMS_3.'" class="delete sumo2-tooltip" onclick="sumo2.mail.Remove(\'i\',\''.$crypt->encrypt($result['ID']).'\')"></div></td>';
		echo '</tr>';
		$counter++;
	}
		if($counter==1)
			echo '<tr><td colspan="6" style="text-align:center; font-size:13px;"><b>'.$lang->MAIL_INBOX.'</b></td></td>';
?>
</table>
<?= pagging($accordion_id, $pagging); ?>
</div>