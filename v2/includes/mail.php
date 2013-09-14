<? 
	$isNoUpdateFile=1;
	require_once('../initialize.php');
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 exit;
	} 
	if(ob_get_length()>0) {ob_end_clean(); }
	if(isset($_POST['type'])) {
		$id=$user->id;
		if($_POST['type'] == 'send') {
			$recipients=$db->filter('recipient');
			$array=preg_split("/!/", $recipients);
			$subject=$db->filter('subject');
			$content=$db->filter('content');
			$content=AESEncryptCtr($content,code_hidden_full,256);
			$subject=AESEncryptCtr($subject,code_hidden_full,256);
			$query = $db->query('INSERT INTO cms_mail_main (senderID, subject, content) VALUES ('.$id.', "'.$subject.'", "'.$content.'")');
			$last_insert=mysql_insert_id();
			foreach($array as $value) {
				if($valid->isNumber($value)) 
					$db->query('INSERT INTO cms_mail_sent (recipientID, mainID, status) VALUES ('.$value.', '.$last_insert.', "C")');
			}
			echo "Ok";
			exit;
		}
		
		if($_POST['type'] == 'remi') {
			$idmail=$crypt->decrypt($_POST['idmail']);
			$db->query("UPDATE cms_mail_sent SET status='D' WHERE ID='".$idmail."'");
			echo "Ok";
			exit;
		}
		if($_POST['type'] == 'rems') {
			$idmail=$crypt->decrypt($_POST['idmail']);
			$db->query("UPDATE cms_mail_main SET status='D' WHERE ID='".$idmail."'");
			echo "Ok";
			exit;
		}
		if($_POST['type'] == 'open') {
			$idmail=$crypt->decrypt($_POST['idmail']);
			$db->query("UPDATE cms_mail_sent SET status='O' WHERE ID='".$idmail."'");
			echo $id;
			exit;
		}
		if($_POST['type'] == 'checkmain') {
			$id1=$user->id;
			$result=$db->query("SELECT ID FROM cms_mail_sent WHERE status='C' AND recipientID='".$id1."'");
			$int=$db->rows($result);
			echo "(".$int.")";
			exit;
		}
	}
?>
