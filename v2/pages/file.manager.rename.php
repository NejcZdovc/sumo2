<?php require_once('../initialize.php'); 
$security->checkFull();
$folder = explode("/", $db->filter('id'));
$name=end($folder);
$type=explode('.', $name);
$only=explode('.'.end($type), $name);
?>
<div class="center-inputs"><div class="input-label"><?php echo $lang->FILE_1?>:</div> 
<input type="text" name="rename" id="rename-item" value="<?php echo $only[0]?>" class="input" />
<input type="text" name="enterfix" style="display:none;" /></div>
