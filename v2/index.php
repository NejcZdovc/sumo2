<?php 
$isNoUpdateFile=1;
include_once('initialize.php');
if(!$session->isLogedIn()) {
	redirect_to('login/');
} else {
	$user = new User($session->getId());
}
$langDomainAuth = $user->checkLang();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sumo2 Administration</title>
<meta name="author" content="3Z Sistemi"/>
<meta name="copyright" content="3Z Sistemi" />
<?php if($user->developer=="1") { ?>
	<link type="text/css" rel="stylesheet" href="min/?g=css&amp;debug=1&amp;a=css" />
<?php } else { ?>
	<link type="text/css" rel="stylesheet" href="min/?g=css&amp;a=css&amp;<?php echo $globals->cacheNumber?>" />
<?php } ?>
<!--[if IE 7]>
<link type="text/css" rel="stylesheet" href="css/ie7.css" />
<![endif]-->
<!--[if IE 8]>
<link type="text/css" rel="stylesheet" href="css/ie8.css" />
<![endif]-->
<!--[if IE 9]>
<link type="text/css" rel="stylesheet" href="css/ie9.css" />
<![endif]-->
<link type="image/png" rel="icon" href="images/favicon.png" />
</head>

<body class="font-set clear">
<div class="background">
<div class="invisible-overlay hdn" id="sumo2-main-overlay"></div>
<div class="header">
    <div class="top">
    	<div class="logo" id="logo_test" style="color:#FFF; float:left;" onclick="sumo2.accordion.NewPanel('a_welcome');"><img id="logo_image" src="images/logo.png" height="40" alt="logo" style="margin-top:5px; margin-left:10px; display:none;"  /></div>
        <noscript><div class="noJava"><?php echo $lang->MOD_180?></div></noscript>
	    <div class="loading hdn" id="sumo2-main-loading"></div>
        <div class="message" id="sumo2-main-message"></div>
        <div class="top-menu">
        	<div class="tpmnu-left flt-left"></div>
            <div class="tpmnu-main flt-left">
                 <div onmouseout="sumo2.cacheSelection.Hide()" onmouseover="sumo2.cacheSelection.Show()" id="tpmenuCache">
                    <div class="cache-hover hide" id="sumo2-cache-wrapper">
                        <div class="lang-top"></div>
                        <div class="lang-middle" id="sumo2-cache-text">
                        	<?php echo '<div onclick="sumo2.cacheSelection.Select(\''.$crypt->encrypt('All').'\');" class="cache-item"><span class="icon"></span><span class="item">'.$lang->MOD_187.'</span></div>';
                            $query = $db->query("SELECT def.moduleName, def.name FROM cms_modules_def as def, cms_domains_ids as di WHERE def.status='N' AND di.elementID=def.ID AND di.type='mod' AND di.domainID='".$user->domain."' ORDER BY def.name asc");
                            while($result = $db->fetch($query)) {
                                if(file_exists('modules/'.$result['moduleName'].'/small.png')) {
                                    $img = 'modules/'.$result['moduleName'].'/small.png';
                                } else {
                                    $img = 'images/icons/flags/s2-S2.png';
                                }
                                if(strlen($result['name'])>14) {
                                    $name=substr($result['name'], 0, 10).'...';
                                } else {
                                    $name=$result['name'];
                                }
                                    
                                echo '<div onclick="sumo2.cacheSelection.Select(\''.$crypt->encrypt($result['moduleName']).'\');" class="cache-item"><span class="icon"><img src="'.$img.'" alt="'.$result['name'].'" /></span><span class="item">'.$name.'</span></div>';
                            }?>
                        </div>
                        <div class="lang-bottom"></div>
                    </div>
             	</div>&nbsp;| 
             </div>
             <div class="tpmnu-main flt-left">
             	<div onmouseout="sumo2.domains.Hide()" onmouseover="sumo2.domains.Show()" id="tpmenuDomain">
                    <div class="domain-hover hide" id="sumo2-domain-wrapper">
                        <div class="lang-top"></div>
                        <div class="lang-middle" id="sumo2-domain-text">
                        	<?php $query = $db->query("SELECT domain.ID, domain.name FROM cms_domains as domain, cms_domains_ids as ids WHERE domain.alias='0' AND domain.ID=ids.domainID AND ids.type='group' AND ids.elementID='".$user->groupID."' ORDER BY domain.name ASC");
							while($result = $db->fetch($query)) {
								($user->domain==$result['ID'])?$temp='itemb':$temp='item';
								if(strlen($result['name'])>15) {
									$name=substr($result['name'], 0, 11).'...';
								} else {
									$name=$result['name'];
								}				
								echo '<div onclick="sumo2.domains.Select(\''.$crypt->encrypt($result['ID']).'\');" class="domain-item"><span class="'.$temp.'">'.$name.'</span></div>';
							}?>
                        
                        </div>
                        <div class="lang-bottom"></div>
                    </div>
             	</div>&nbsp;|
            </div>             
            <div class="tpmnu-main flt-left"><?php if($user->isAuth('FAV_MAIL_2')) { ?><div style="float:left; cursor:pointer;" onclick="sumo2.accordion.NewPanel('a_mail_inbox');sumo2.accordion.ReloadAccordion('a_mail_inbox');"><div id="tpmenuMail"></div>(<span id="mail_number"> 0 </span>) |</div><?php } ?>
            <?php
			if($user->preview != 2) {
				$preview_url='';
				if(isset($globals->offline)) {
					if($globals->offline=="Y") {
						$off=$shield->protect('offline=true&user='.$user->id.'&username='.$user->username.'&date='.time().'');
						$preview_url='href="http://'.$user->domainName.'/index.php?'.$off.'"';
					}
					else
						$preview_url='href="http://'.$user->domainName.'/index.php"';
					if($user->preview == 1)
						$preview_url.=' target="_blank"';
					if($user->preview == 3)
						$preview_url.=' target="_blank"';
					if($user->preview == 4)
						$preview_url.='';
				}
			} else
				$preview_url=' onclick="sumo2.dialog.NewDialog(\'d_preview\')"';
			
			if(is_file('images/icons/flags/'.$user->langshort($user->translate_lang).'.png')) {
					$img = 'images/icons/flags/'.$user->langshort($user->translate_lang).'.png';
				} else {
					$img = 'images/icons/flags/s2-S2.png';
				}
		?>
            <div style="float:left; margin-left:2px;" id="preview-link-refresh"><a <?php echo $preview_url?>><?php echo $lang->INDEX_PREW?></a> | </div>
            <div onmouseout="sumo2.languageSelection.Hide()" onmouseover="sumo2.languageSelection.Show()" class="language-top" style="background-image:url(<?php echo $img?>); float:left; cursor:pointer; margin-left:2px;"><?php echo $lang->INDEX_LANG?>
                <div class="lang-hover hide" id="sumo2-lang-wrapper">
                    <div class="lang-top"></div>
                    <div class="lang-middle" id="sumo2-lang-text">
                    <?php 
						$query = $db->query("SELECT value FROM cms_domains_ids WHERE type='lang' AND domainID='".$user->domain."'");
						while($result=$db->fetch($query)) {
							$langR = $db->get($db->query("SELECT ID, short, name FROM cms_language_front WHERE short='".$result['value']."'"));
							$temp="";
							($user->langshort($user->translate_lang)==$langR['short'])?$temp='itemb':$temp='item';
							if(file_exists('images/icons/flags/'.$langR['short'].'.png')) {
								$img = 'images/icons/flags/'.$langR['short'].'.png';
							} else {
								$img = 'images/icons/flags/s2-S2.png';
							}
							echo '<div onclick="sumo2.languageSelection.Select(\''.$crypt->encrypt($langR['ID']).'\', \''.$langR['name'].'\');" class="lang-item"><span class="'.$temp.'">'.$langR['name'].'</span><span class="icon"><img src="'.$img.'" alt="'.$langR['name'].'" /></span></div>';
						} ?>                    
                    </div>
                    <div class="lang-bottom"></div>
                </div>
            </div>
            | <a href="http://dev.3zsistemi.si/thebuggenie/sumo2cms/issues/new" target="_blank"><?php echo $lang->MOD_2?></a> | <a href="#" onclick="sumo2.Logout()"><?php echo $lang->INDEX_LOGOUT?></a></div>
            <div class="tpmnu-right flt-left"></div>
            <div id="sumo2-main-version">
        		<?php echo $lang->MOD_39?> <?php echo $globals->version?> &copy; <a style="color:#e3e3e3;" href="http://www.3zsistemi.si" target="_blank" >3Z Sistemi</a>
       		</div>
        </div>        
        <div class="float-right">
            <div class="update" id="sumo2-main-update">
                <div id="bg">
                    <div id="top"></div>
                </div>
                <div id="bottom"></div>
            </div>
        </div>
    </div>
    <div class="menu nav">
    	<ul id="sumo2-navigation">
        	<?php
            $query1 = $db->query("SELECT distinct title FROM cms_favorites_def WHERE statusID='N' ORDER BY ID asc");
	        $first = true;
	        $firstTitle = NULL;
	        while($result1 = $db->fetch($query1)) {
				$content = '';
				$exists = false;
	            if($first) {
	                $firstTitle = $result1['title'];
	                $class = ' class="sel sumo2-navigation-title"';
	            } else {
	                $class = ' class="sumo2-navigation-title"';
	            }
                $content .= '<li'.$class.'><span>'.$lang->$result1['title'].'</span><ul class="hide">';
                    $query2 = $db->query("SELECT subtitle,click FROM cms_favorites_def WHERE title='".$result1['title']."' AND statusID='N' ORDER BY ID ASC");
                    while($result2 = $db->fetch($query2)) {
						if($user->isAuth($result2['subtitle'])) {
							$exists = true;
							if($langDomainAuth=="ok") {
								$alert = $result2['click'];
							} else if($langDomainAuth=="lang") {
								$alert = "sumo2.message.NewMessage('".$lang->MOD_188."',3);sumo2.accordion.NewPanel('a_settings');";
							} else {
								$alert = "sumo2.message.NewMessage('".$lang->MOD_228."',3);sumo2.accordion.NewPanel('a_domains');";
							}
                        	$content .= '<li><div onclick="'.$alert.'"><span class="sumo2-navigation-title">'.$lang->$result2['subtitle'].'</span></div></li>';
						}
                    }
                $content .= ' </ul></li>';
				if($exists) {
					 echo $content;
					 $first = false;
				}
            }
			if($first) {
				$firstTitle = NULL;
			}
            ?>
        </ul>
    </div>
    <div class="submenu subnav" id="sumo2-subnavigation">
    	<ul>
           <?php
		   if($firstTitle != NULL) {
				$query3 = $db->query("SELECT subtitle,click FROM cms_favorites_def WHERE title='".$firstTitle."' ORDER BY ID ASC");
				while($result3 = $db->fetch($query3)) {
					if($user->isAuth($result3['subtitle'])) {
						if($langDomainAuth=="ok") {
							$alert = $result3['click'];
						} else if($langDomainAuth=="lang") {
							$alert = "sumo2.message.NewMessage('".$lang->MOD_188."',3);sumo2.accordion.NewPanel('a_settings');";
						} else {
							$alert = "sumo2.message.NewMessage('".$lang->MOD_228."',3);sumo2.accordion.NewPanel('a_domains');";
						}
						echo '<li><div onclick="'.$alert.'">'.$lang->$result3['subtitle'].'</div></li>';
					}
				}
		   }
            ?>
        </ul>
    </div>
</div>
<div class="left" id="sumo2-left">
	<div class="favorites box">
    	<div class="fav-top title1 flt-left"><div style="display:inline;background-image:url(images/css_sprite.png);background-position:-508px -1661px;height:16px;width:16px;" class="flt-left title1-icon" /></div></div>
        <div class="button flt-right" onclick="sumo2.dialog.NewDialog('d_favorites')"><div style="display:inline;background-image:url(images/css_sprite.png);background-position:-668px -1677px;height:16px;width:16px;" class="flt-left button-icon" ></div><?php echo $lang->EDIT?></div>
        <div class="fav-content clear" id="sumo2-favourites"></div>
    </div>
    <div class="left-accordion" id="sumo2-left-accordion"></div>
</div>
<div class="main" id="sumo2-main"></div>
<script type="text/javascript">
	var cookieIDGlobal="<?php echo $cookie->getName('user')?>";
	var CKEDITOR_BASEPATH = '/v2/ckeditor/';
</script>
<?php if($user->developer=="1") { ?>
	<script type="text/javascript" src="min/?g=js&amp;debug=1&amp;a=js"></script>
<?php } else { ?>
	<script type="text/javascript" src="min/?g=js&amp;a=js&amp;<?php echo $globals->cacheNumber?>"></script>
<?php } ?>
<script type="text/javascript" src="includes/javascript.settings.php?<?php echo $globals->cacheNumber?>"></script>
<script type="text/javascript">
sumo2.AddLoadEvent(function() {	
	sumo2.image.GetSize();
	sumo2.navigation.Init();
	sumo2.client.Init();
	sumo2.tooltip.FindTooltips(document);
	sumo2.state.Init();
	setInterval("sumo2.preview.Update();", 540000);
	<?php if($user->permission==1 && $user->updateOption=='ON') {?>
		if(sumo2.update.Checked===true) {
			if($("#sumo2-main-update").length > 0){
				setTimeout("sumo2.update.Init();", 3000);
			}		
			sumo2.update.Checked=false;
		}
	<?php } else { ?>		
		sumo2.update.Checked=false;
	<?php } ?>
	sumo2.state.LoadState();		
});
</script>
<?php
$ua=getBrowser();
if(strtolower($ua['name']) == strtolower("Google Chrome")) {
	if($ua['version'] < 21.0) {
		echo "<script type=\"text/javascript\">
			setTimeout('sumo2.dialog.NewDialog(\'d_out_of_date\')', 3000);
			</script>";
	}
}
else if(strtolower($ua['name']) == strtolower("Mozilla Firefox")) {
	if($ua['version'] < 14.0) {
		echo "<script type=\"text/javascript\">
			setTimeout('sumo2.dialog.NewDialog(\'d_out_of_date\')', 3000);
			</script>";
	}
} 
else if(strtolower($ua['name']) == strtolower("Internet Explorer")) {
	if($ua['version'] < 9.0) {
		echo "<script type=\"text/javascript\">
			setTimeout('sumo2.dialog.NewDialog(\'d_out_of_date\')', 3000);
			</script>";
	}
}
else if(strtolower($ua['name']) == strtolower("Opera")) {
	if($ua['version'] < 12) {
		echo "<script type=\"text/javascript\">
			setTimeout('sumo2.dialog.NewDialog(\'d_out_of_date\')', 3000);
			</script>";
	}
}
else if(strtolower($ua['name']) == strtolower("Apple Safari")) {
	if($ua['version'] < 5.0) {
		echo "<script type=\"text/javascript\">
			setTimeout('sumo2.dialog.NewDialog(\'d_out_of_date\')', 3000);
			</script>";
	}
}
?>
</body>
</html>