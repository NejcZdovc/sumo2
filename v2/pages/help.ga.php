<? 
	if(isset($_REQUEST['type'])) {
		if($_REQUEST['type']=="GA"){ ?>
			<img src="images/help_ga.jpg" width="750" /><br/><br/>
            <?=$lang->SETTINGS_60?>
		<? } else { ?>
			<img src="images/help_wa.jpg" width="750" /><br/><br/>
           <?=$lang->SETTINGS_61?> 		
		<? }
	}
?>