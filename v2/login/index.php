<?php  
$isLoginFile=821662345037610915243762;
include_once('../initialize.php');
if($session->isLogedIn()) {
	redirect_to('../index.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sumo 2 - Login</title>
<meta name="author" content="3Z Sistemi"/>
<meta name="copyright" content="3Z Sistemi" />
<link type="text/css" rel="stylesheet" href="style.css" />
<link type="image/png" rel="icon" href="../images/favicon.png" />
</head>

<body>
<div id="logo"><img id="logo_image" src="../images/logoBig.png" height="90px" alt="logo" style="display:none;"  /></div>
<div class="error" id="error"></div>
<div class="panel">
	<form method="post" action="" name="login">
		<input type="hidden" name="token" value="<?php echo $session->setFormToken(); ?>" />
		<div class="label">Username:</div>
		<input type="text" name="user[username]" id="username" tabindex="10" class="input" />
		<div class="label">Password:</div>
        <input id="dummypass" class="input" type="text" readonly="" onfocus="this.style.display='none';p=document.getElementById('pass');p.style.display='block';p.focus();" value="" style="display: block; margin-left:0px;">
		<input type="password" name ="user[password]" id="pass" tabindex="20" class="input" autocomplete="off" style="display: none; margin-left:0px;" onblur="if(this.value==''){this.style.display='none';p=document.getElementById('dummypass');p.style.display='block';}" />
	</form>
	<div class="button" id="button"></div>
	<div class="clear"></div>
</div>
<div id="copyright">
    <a href="http://www.3zsistemi.si/sumo.html" target="_blank" title="SUMO2 CMS">SUMO2 CMS</a> Copyright &copy; 2011-2013 <a href="http://www.3zsistemi.si" target="_blank" title="3Z Sistemi">3Z Sistemi</a>
</div>
<script type="text/javascript" src="script.js"></script>
</body>
</html>