<?php
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$fileName=str_replace(array("www.", ":"), "", $user->domainName);
	$file = "../logs/data_".$fileName.".log";
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
	} else {
		error_log("Data log doesn't existst: data_".$user->domainName.".log");
	}
?>
<div id="a_settings_error">
<a href="/v2/logs/errorFront_<?php echo $user->domainName ?>.log" target="_blank" class="sumo2-tooltip view" style="float:right; margin:0px 10px 10px;" title="<?php echo $lang->MOD_55?>"></a>
<div class="sumo2-tooltip" style="float:right; margin-bottom:10px; cursor:pointer;" onclick="sumo2.sumoSettings.CleanLog('<?php echo "data_".$fileName.".log" ?>')" title="<?php echo $lang->SETTINGS_41?>"><div style="display:block;width:16px;height:16px;background-image:url(images/css_sprite.png);background-position:-604px -1629px;"></div></div>
<div class="sumo2-tooltip" style="float:right; margin-bottom:10px; margin-right:10px; cursor:pointer;" onclick="window.open('http://'+document.domain+'/v2/includes/download_dataLog.php?page=<?php echo $fileName?>', '_newtab');" title="<?php echo $lang->MOD_54?>"><div style="display:block;width:16px;height:16px;background-image:url(images/css_sprite.png);background-position:-540px -1629px;"></div></div>
<textarea class="input-area" disabled="disabled" style="margin-left:2%; margin-right:2%; height:500px; width:96%; clear:both;"><?php echo $data?></textarea>
</div>