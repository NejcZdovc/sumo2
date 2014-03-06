<?php
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$file = "../logs/error.log";
	$fh = fopen($file, 'r');
	$data = fread($fh, filesize($file));
	fclose($fh);
?>
<div id="a_settings_error">
<div class="sumo2-tooltip" style="float:right; margin-bottom:10px; cursor:pointer;" onclick="sumo2.sumoSettings.Error()" title="<?php echo $lang->SETTINGS_41?>"><div style="display:block;width:16px;height:16px;background-image:url(images/css_sprite.png);background-position:-604px -1629px;"></div></div>
<div class="sumo2-tooltip" style="float:right; margin-bottom:10px; margin-right:10px; cursor:pointer;" onclick="window.open('http://'+document.domain+'/v2/includes/download_errors.php', '_newtab');" title="<?php echo $lang->MOD_54?>"><div style="display:block;width:16px;height:16px;background-image:url(images/css_sprite.png);background-position:-540px -1629px;"></div></div>
<textarea class="input-area" disabled="disabled" style="margin-left:2%; margin-right:2%; height:500px; width:96%; clear:both;"><?php echo $data?></textarea>
</div>