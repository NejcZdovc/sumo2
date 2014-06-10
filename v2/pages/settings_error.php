<?php
	$file = "../logs/error.log";
	$data="";
    if(!is_file($file)) {
        fopen($file, 'w');
    }
    
	if(is_file($file)) {
		$file = file($file, FILE_SKIP_EMPTY_LINES);
		if(count($file)>500) {
			$array=array_slice($file, (count($file)-500), count($file));
			$array=array_reverse($array);
			array_unshift($array, $file[0].PHP_EOL);
			array_push($array, PHP_EOL.PHP_EOL."Error log is longer then 500 lines. Please open detailed view of log.");
			$data=implode("", $array);
		} else {
			$data=implode("", $file);
		}
	}
?>
<div id="a_settings_error">
<a href="/v2/logs/error.log" target="_blank" class="sumo2-tooltip view" style="float:right; margin:0px 10px 10px;" title="<?php echo $lang->MOD_55?>"></a>
<?php if($user->errorLog=="1") { ?>
<div class="sumo2-tooltip" style="float:right; margin-bottom:10px; cursor:pointer;" onclick="sumo2.sumoSettings.CleanLog('error.log')" title="<?php echo $lang->SETTINGS_41?>"><div style="display:block;width:16px;height:16px;background-image:url(images/css_sprite.png);background-position:-604px -1629px;"></div></div>
<?php } ?>
<div class="sumo2-tooltip" style="float:right; margin-bottom:10px; margin-right:10px; cursor:pointer;" onclick="window.open('http://'+document.domain+'/v2/includes/download_errors.php', '_newtab');" title="<?php echo $lang->MOD_54?>"><div style="display:block;width:16px;height:16px;background-image:url(images/css_sprite.png);background-position:-540px -1629px;"></div></div>
<textarea class="input-area" disabled="disabled" style="margin-left:2%; margin-right:2%; height:500px; width:96%; clear:both;"><?php echo $data?></textarea>
</div>