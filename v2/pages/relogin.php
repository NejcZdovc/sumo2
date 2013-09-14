<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
define( '_VALID_MOS', 1 );
define( '_VALID_EXT', 1 );
include_once('../configs/settings.php');
include_once('../essentials/database.class.php');
include_once('../essentials/xml.class.php');
include_once('../essentials/cryptography.class.php'); 
include_once('../essentials/cookie.class.php'); 
include_once('../essentials/session.class.php'); 
include_once('../essentials/language.class.php'); 
?>
<br/><b><?=$lang->MOD_192?></b><br/>
<b><?=$lang->MOD_193?></b><br/><br/>
<form method="post" action="" name="d_relogin">
    <input type="hidden" name="token" value="<?php echo base64_encode(time()); ?>" />
    <div class="label"><?=$lang->USERNAME?>:</div>
    <input type="text" name="username" tabindex="10" class="input" />
    <div class="label"><?=$lang->USER_ADDU_P_1?>:</div>
    <input type="password" name ="password" tabindex="20" class="input" autocomplete="off" />
</form>