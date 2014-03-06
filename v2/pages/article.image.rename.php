<?php require_once('../initialize.php'); 
if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$id=$crypt->decrypt($db->filter('id'));
	$results_basic=$db->fetch($db->query('SELECT name FROM cms_article_images WHERE ID="'.$id.'"'));
?>
<div class="center-inputs"><div class="input-label"><?php echo $lang->MOD_31?>:</div>
<form action="" name="d_article_image_rename" method="post" class="form2">
<input type="text" name="rename" id="rename_article_image" class="input" value="<?php echo $results_basic['name']?>" />
<input type="hidden" name="id" value="<?php echo $db->filter('id')?>" id="image_id" />
<input type="text" name="enterfix" style="display:none;" />
</form>
</div>
