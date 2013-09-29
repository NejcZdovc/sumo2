<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Namestitev Sumo2 sistema</title>
    <link href="style.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="script.js"></script>
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript" src="jquery.plugins.js"></script>
    <script type="text/javascript">
		$(document).ready(function() {
			$("#progressbar").progressbar({ value: 0 });
		});
    </script>
</head>
<body>
    <div style="display:none;" id="procent">/</div>
    <div id="okvir">
        <h1>Welcome to SUMO2 CMS instalation</h1>
        <p>
            <br/>Sistem Vas bo vodil skozi nastavitev ter vam sproti izpisoval navodila za vsak korak.
        </p>
        <div style="background-color:#232323; margin:0 auto; height:1px; width:90%; margin-bottom:10px;"></div>
        <div id="zacni" onclick="pricni1();"></div>
        <div id="progressbar"></div>
        <div id="help"></div>
        <div id="content"></div>
        <div id="button"></div>
        <div style="clear:both;"></div>
    </div>
</body>
</html>