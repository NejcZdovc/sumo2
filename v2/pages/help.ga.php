<?php 
	if(isset($_REQUEST['type'])) {
		if($_REQUEST['type']=="GA"){ ?>
			<img src="images/help_ga.jpg" width="750" /><br/><br/>
            <?php echo $lang->SETTINGS_60?>
		<?php } else { ?>
			<img src="images/help_wa.jpg" width="750" /><br/><br/>
           <?php echo $lang->SETTINGS_61?> 		
		<?php }
	}
?>