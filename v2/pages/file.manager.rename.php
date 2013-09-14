<?php require_once('../initialize.php'); 
if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
$folder = explode("/", $db->filter('id'));
$name=end($folder);
$type=explode('.', $name);
$only=explode('.'.end($type), $name);
?>
<div class="center-inputs"><div class="input-label"><?=$lang->FILE_1?>:</div> 
<input type="text" name="rename" id="rename-item" value="<?=$only[0]?>" class="input" />
<input type="text" name="enterfix" style="display:none;" /></div>
