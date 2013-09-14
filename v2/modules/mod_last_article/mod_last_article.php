<? require_once('../../initialize.php');
	if(isset($_POST['type'])) {
		if($_POST['type']=="edit") {
			$id=$crypt->decrypt($db->filter('id'));
			$number=$db->filter('number');
			$animation=$db->filter('animation');
			$cat=$db->filter('cat');
			$db->query('UPDATE mod_last_article SET number="'.$number.'", categoryID="'.$cat.'", animation="'.$animation.'" WHERE ID="'.$id.'"');
		}
	}		
?>