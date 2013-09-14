<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php $template->head(); ?>  
    <link rel="Shortcut Icon" href="/templates/rc.3zsistemi.si/images/favicon.ico" />      
</head>
<body>
	<!-- HEADER -->
	<div id="topHolder">
        <?php $template->panel('sumo_top'); ?>
    </div>    
    <div id="logoHolder"><a href="/"><img src="/templates/rc.3zsistemi.si/images/logo.png" alt="Ruski center" /></a></div>
	<!-- MAIN MENU -->    
    <div id="mainMenuHolder">
        <div id="mainMenuTop" onmouseout="hideTop(20);" onmouseover="showTop();">
            <a href="javascript:void()">Najbolj iskane vsebine</a>
        </div>
        <?php $template->panel('sumo_menu'); ?>
    </div>
<? /*
	<!-- HOME -->
    <div id="homeHolder">
		<!-- BANNER -->
    	<div id="homeBanner">
	       <?php $template->panel('sumo_middle_1', 'A'); ?>
        </div>
        <!-- NAPOVEDNIK -->
    	<div id="homeNapovednik">
	      <?php $template->panel('sumo_middle_2', 'A'); ?>          
        </div>
	        <div class="clearBoth" style="height:1px;"></div>
        <!-- NOVICE -->
    	<div id="homeNovice">
	        <?php $template->panel('sumo_middle_3', 'B'); ?>
        </div>
        <!-- TEČAJI -->
    	<div id="homeTecaji">
	       <?php $template->panel('sumo_middle_4', 'B'); ?>       
        </div>
	        <div class="clearBoth" style="height:1px;"></div>        
        <!-- KNJIŽNICA -->
        <div id="homeKnjige">
        	<?php $template->panel('sumo_bottom'); ?>      
        </div>                     
    
	</div>
	<!-- FOOTER -->
    <div id="footerHolder">
    	<?php $template->panel('sumo_footer'); ?>
    </div> */
	
    $template->footer(); ?>
</body>
</html>