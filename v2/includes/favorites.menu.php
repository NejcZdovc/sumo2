<?php 
require_once("../initialize.php");
if(!$session->isLogedIn()) {
redirect_to('../login/');
} else {
	$user = new User($session->getId()); 
}
$langDomainAuth = $user->checkLang();

$id = $user->id;
$result = $db->get($db->query("SELECT option1, option2, option3, option4, option5, option6, option7, option8, option9, option10 FROM cms_favorites WHERE UserID='".$id."' LIMIT 1"));
if($result) { 
	for($i=1; $i<=10; $i++) {
		if($result['option'.$i] != 0) {
			$result1 = $db->get($db->query("SELECT click, img, subtitle FROM cms_favorites_def WHERE ID='".$result['option'.$i]."' LIMIT 1")); 
			if($langDomainAuth=="ok") {
				$alert = $result1['click'];
			} else if($langDomainAuth=="lang") {
				$alert = "sumo2.message.NewMessage('".$lang->MOD_188."',3);sumo2.accordion.NewPanel('a_settings');";
			} else {
				$alert = "sumo2.message.NewMessage('".$lang->MOD_228."',3);sumo2.accordion.NewPanel('a_domains');";
			}
			if($user->isAuth($result1['subtitle'])) {?>
				<div class="fav-item flt-left" id="fav-item-<?=$i?>" onclick="<?php echo $alert; ?>">
					<div class="fav-icon"><?=getImageFav($result1['img'],$lang->$result1['subtitle'])?></div>
					<div class="fav-text"><?php echo $lang->$result1['subtitle']; ?></div>
				</div>
			<? 
			}
		}
	}
}
?>