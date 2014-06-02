<?php require_once('../initialize.php'); 
	if(!$session->isLogedIn() || !$security->checkURL()) {
		exit;
	}
	$accordion_id='a_mail_sent';
	$pagging=check_pagging('select ID,subject,content,date from cms_mail_main where senderID="'.$user->id.'" and status="N" order by date desc', $user->items);
	$dropdown=dropdown_pagging($accordion_id, $pagging[0]);
	echo '<div class="flt-right display">'.$dropdown.'</div>';
?>
<div id="a_mail_sent_table" style="clear:both;">
<table cellpadding="0" cellspacing="1" border="0" class="table1 table2" width="99%">
	<tr>
    	<th><?php echo $lang->MAIL_TO?></th>
		<th><?php echo $lang->MAIL_SUBJECT?></th>
		<th><?php echo $lang->SENT_DATE?></th>
		<th><?php echo $lang->CONTROL?></th>
	</tr>
    <?php 
	$counter = 1;
	$query = $db->query($pagging[4]);
	while($result = $db->fetch($query)) {
		if($counter&1)
			echo '<tr class="odd">';
		else
			echo '<tr class="even">';
		
		$counter1 = 1;
		$recipient = "";
		$query1 = $db->query("SELECT recipientID FROM cms_mail_sent WHERE mainID=".$result['ID']."");
		while($result1 = $db->fetch($query1)) {
			$query2 = $db->query("SELECT username, name FROM cms_user WHERE ID=".$result1['recipientID']."");
			while($result2 = $db->fetch($query2)) {
				if($counter1==1)
					$recipient .= $result2['username'].' ('.$result2['name'].')';
				else
					$recipient .= ', '.$result2['username'].' ('.$result2['name'].')';
				$counter1++;
			}
		}
		$date_now=date($lang->DATE_1, strtotime($result['date']));
		
		echo '<td style="width:25%">'.$recipient.'</td>';
		echo '<td >'.AESDecryptCtr($result['subject'],code_hidden_full,256).'</td>';
		echo '<td style="width:135px; text-align:left;">'.$date_now.'</td>';
		
		//Tvorimo Notification okno tekst
										
		$text ='<u>'.$lang->MAIL_SENT_1.':</u>&nbsp;&nbsp;&nbsp;'. $recipient .'<br/><br/>';
		$text .='<u>'.$lang->SENT_DATE.':</u>&nbsp;&nbsp;&nbsp;'. $date_now .'<br/><br/><hr/>';
		$text .='<br/><div>'. preg_replace('/\n/', '', AESDecryptCtr($result['content'],code_hidden_full,256)) .'</div>';
		
		echo '<td style="width:55px"><div title="'.$lang->MAIL_SENTITEMS_2.'" class="view sumo2-tooltip" onclick="sumo2.dialog.NewNotification(\''.AESDecryptCtr($result['subject'],code_hidden_full,256).'\',\''.$text.'\',450,450,3);"></div><div title="'.$lang->MAIL_SENTITEMS_3.'" class="delete sumo2-tooltip" onclick="sumo2.mail.Remove(\'s\',\''.$crypt->encrypt($result['ID']).'\')")></div></td>';
		echo '</tr>';
		$counter++;
	} 
	if($counter==1)
			echo '<tr><td colspan="6" style="text-align:center; font-size:13px;"><b>'.$lang->MAIL_SENTITEMS.'</b></td></td>';	
	?>
</table>
<?php echo  pagging($accordion_id, $pagging); ?>
</div>
