<?php	require_once('../initialize.php');
if(!$session->isLogedIn() || !$security->checkURL()) {
	exit;
}
if(ob_get_length()>0) {
	ob_end_clean();
}
if($db->is('type')) {
	if($db->filter('type') == 'add') {
		$name = $db->filter('name');
		$exist=$db->rows($db->query('SELECT ID FROM cms_domains WHERE name="'.$name.'"'));
		if($exist>0) {
			echo "exist";
			exit;
		}
			
		$alias = $db->filter('alias');
		$main = $db->filter('main');
		$language = $db->filter('language');
		$white = $db->filter('white');
		$black = $db->filter('black');
		$iplocator = $db->filter('iplocator');
		$countries = $db->filter('countries');
				
		$db->query("INSERT INTO cms_domains (name, parentID, locator) VALUES ('".$name."', '".$main."', '".$iplocator."')");
		$id=$db->getLastId();
		
		//language
		$languages=explode("*/*", $language);
		foreach($languages as $language) {
			if(strlen($language)>1) {
				$db->query("INSERT INTO cms_domains_ids (domainID, value, type)  VALUES	('".$id."', '".$language."', 'lang')");
				$lang=$db->get($db->query('SELECT ID FROM cms_language_front WHERE short="'.$language.'" LIMIT 1'));
				$db->query("INSERT INTO cms_homepage (title,lang,selection, domain) VALUES ('Home - ".$name."', '".$lang['ID']."', 1, '".$id."')");
				$db->query("INSERT INTO cms_menus_items (link,title,menuID,status,parentID,orderID,template,selection,target) VALUES ('".$db->getLastId()."', 'Home - ".$name."', '-1','DD','-1','-1','-1','-1','-1')"); 
			}
		}		
		
		//white ip
		$ips=explode("*/*", $white);
		foreach($ips as $ip) {
			if(strlen($ip)>2)
				$db->query("INSERT INTO cms_domains_ips (domainID, value, type)  VALUES	('".$id."', '".$ip."', '1')");
		}
		
		//balck ip
		$ips=explode("*/*", $black);
		foreach($ips as $ip) {
			if(strlen($ip)>2)
				$db->query("INSERT INTO cms_domains_ips (domainID, value, type)  VALUES	('".$id."', '".$ip."', '0')");
		}
		
		//countries
		$countries=explode("*/*", $countries);
		foreach($countries as $country) {
			if(strlen($country)>1)
				$db->query("INSERT INTO cms_domains_countries (domainID, value)  VALUES	('".$id."', '".$country."')");
		}
		
		//alias
		$alias=explode("*/*", $alias);
		foreach($alias as $alia) {
			if(strlen($alia)>3)
				$db->query("INSERT INTO cms_domains (name, parentID, alias) VALUES ('".$alia."', '".$id."', '1')");
		}
		//template
		mkdir('../../templates/'.$name.'/', PER_FOLDER);
		chmod('../../templates/'.$name.'/', PER_FOLDER);
		mkdir('../../templates/'.$name.'/images', PER_FOLDER);
		chmod('../../templates/'.$name.'/images', PER_FOLDER);
		
		//module
		mkdir('../../modules/'.$name, PER_FOLDER);
		chmod('../../modules/'.$name, PER_FOLDER);
		mkdir('../../modules/'.$name.'/images', PER_FOLDER);
		chmod('../../modules/'.$name.'/images', PER_FOLDER);
		
		//off
		mkdir('../../off/'.$name, PER_FOLDER);
		chmod('../../off/'.$name, PER_FOLDER);
		copyFiles('../../off/default', '../../off/'.$name);
		
		//block
		mkdir('../../block/'.$name, PER_FOLDER);
		chmod('../../block/'.$name, PER_FOLDER);
		copyFiles('../../block/default', '../../block/'.$name);
		
		//block
		mkdir('../../404/'.$name, PER_FOLDER);
		chmod('../../404/'.$name, PER_FOLDER);
		copyFiles('../../404/default', '../../404/'.$name);
		
		//images
		mkdir('../../images/'.$name, PER_FOLDER);
		chmod('../../images/'.$name, PER_FOLDER);
		mkdir('../../images/'.$name.'/article', PER_FOLDER);
		chmod('../../images/'.$name.'/article', PER_FOLDER);
		
		//storage
		mkdir('../../storage/'.$name, PER_FOLDER);
		chmod('../../storage/'.$name, PER_FOLDER);
		mkdir('../../storage/'.$name.'/Documents', PER_FOLDER);
		chmod('../../storage/'.$name.'/Documents', PER_FOLDER);
		mkdir('../../storage/'.$name.'/Flash', PER_FOLDER);
		chmod('../../storage/'.$name.'/Flash', PER_FOLDER);
		mkdir('../../storage/'.$name.'/Images', PER_FOLDER);
		chmod('../../storage/'.$name.'/Images', PER_FOLDER);
		mkdir('../../storage/'.$name.'/Documents/Pdf', PER_FOLDER);
		chmod('../../storage/'.$name.'/Documents/Pdf', PER_FOLDER);
		mkdir('../../storage/'.$name.'/Documents/Word', PER_FOLDER);
		chmod('../../storage/'.$name.'/Documents/Word', PER_FOLDER);
		
		
		//global settings
		$db->query("INSERT INTO cms_global_settings (domain, title, display_title) VALUES ('".$id."', 'Website', 'D')");
		
		//add domain to super admin group
		$db->query("INSERT INTO cms_domains_ids (type, domainID, elementID) VALUES ('group', '".$id."', '1')");
		
		//module prefix
		$db->query('INSERT INTO cms_modul_prefix (domain, name, enabled, prefix) VALUES ("'.$id.'", "default", "1", "default")');
		
		//add error log
		$fp = fopen("../logs/errorFront_".$name.".si.log","w");
		if( $fp == false ){
			error_log("Error while creating front end error log file with name: ");
		}else{
			$string="Error log cleaned (".date("d.m.Y H:m", time()).")\r\n\n";
			fwrite($fp,$string);
			fclose($fp);
		}
		
		echo 'ok';
		exit;
	} else if($db->filter('type') == 'edit') {
		$id = $db->filter('id');
		$alias = $db->filter('alias');
		$main = $db->filter('main');
		$language = $db->filter('language');
		$white = $db->filter('white');
		$black = $db->filter('black');
		$iplocator = $db->filter('iplocator');
		$countries = $db->filter('countries');
				
		$db->query("UPDATE cms_domains SET parentID='".$main."', locator='".$iplocator."' WHERE ID='".$id."'");

		//language
		$db->query('DELETE FROM cms_domains_ids WHERE type="lang" AND domainID="'.$id.'"');
		$languages=explode("*/*", $language);
		foreach($languages as $language) {
			if(strlen($language)>1) {
				$lang=$db->get($db->query('SELECT ID FROM cms_language_front WHERE short="'.$language.'" LIMIT 1'));
				$num=$db->rows($db->query('SELECT ID FROM cms_homepage WHERE domain="'.$id.'" AND lang="'.$lang['ID'].'" AND selection="1"'));
				if($num==0) {				
					$db->query("INSERT INTO cms_homepage (title,lang,selection, domain) VALUES ('Home - ".$language."', '".$lang['ID']."', 1, '".$id."')");
					$db->query("INSERT INTO cms_menus_items (link,title,menuID,status,parentID,orderID,template,selection,target) VALUES ('".$db->getLastId()."', 'Home - ".$language."', '-1','DD','-1','-1','-1','-1','-1')"); 
				} 
				$db->query("INSERT INTO cms_domains_ids (domainID, value, type)  VALUES	('".$id."', '".$language."', 'lang')");	
			}
		}	
		
		//white ip
		$db->query("DELETE FROM cms_domains_ips WHERE domainID='".$id."'");
		$ips=explode("*/*", $white);
		foreach($ips as $ip) {
			if(strlen($ip)>2)
				$db->query("INSERT INTO cms_domains_ips (domainID, value, type)  VALUES	('".$id."', '".$ip."', '1')");
		}
		
		//balck ip
		$ips=explode("*/*", $black);
		foreach($ips as $ip) {
			if(strlen($ip)>2)
				$db->query("INSERT INTO cms_domains_ips (domainID, value, type)  VALUES	('".$id."', '".$ip."', '0')");
		}
		
		//countries
		$db->query("DELETE FROM cms_domains_countries WHERE domainID='".$id."'");
		$countries=explode("*/*", $countries);
		foreach($countries as $country) {
			if(strlen($country)>1)
				$db->query("INSERT INTO cms_domains_countries (domainID, value)  VALUES	('".$id."', '".$country."')");
		}
		
		//alias
		$db->query("DELETE FROM cms_domains WHERE parentID='".$id."' AND alias='1'");
		$alias=explode("*/*", $alias);
		foreach($alias as $alia) {
			if(strlen($alia)>2)
				$db->query("INSERT INTO cms_domains (name, parentID, alias) VALUES ('".$alia."', '".$id."', '1')");
		}
		echo 'ok';
		exit;
	} else if($db->filter('type')=="saveSelection") {		
		$id=$crypt->decrypt($db->filter('id'));
		$domains=$db->get($db->query('SELECT value FROM cms_domains_ids WHERE type="lang" AND domainID="'.$id.'" LIMIT 1'));
		$lang=$db->get($db->query('SELECT ID FROM cms_language_front WHERE short="'.$domains['value'].'" LIMIT 1'));
		$db->query('UPDATE cms_user_settings SET domain="'.$id.'", translate_lang="'.$lang['ID'].'" WHERE userID="'.$user->id.'"');
		echo 'ok';
		exit;
	}
    else if($db->filter('type')=="delete")
    {
		$id=$db->filter('id');
        $domain=$db->get($db->query('SELECT name FROM cms_domains WHERE ID="'.$id.'"'));
        if(!$domain) {
            return "error";
        }
        
        $name=$domain['name'];
        
        //module panles
        $tables=$db->query('SELECT prefix FROM cms_template_position WHERE domain="'.$id.'"');
        while($table=$db->fetch($tables)) {
            $db->query('DELETE FROM cms_panel_'.$table['prefix'].' WHERE domain="'.$id.'"');
        }
        
        //article images
        $tables=$db->query('SELECT ID FROM cms_article WHERE domain="'.$id.'"');
        while($table=$db->fetch($tables)) {
            $db->query('DELETE FROM cms_article_images WHERE articleID="'.$table['ID'].'"');
            $db->query('DELETE FROM cms_article_tags WHERE articleID="'.$table['ID'].'"');
        }        
        
        $db->query('DELETE FROM cms_article WHERE domain="'.$id.'"');
        $db->query('DELETE FROM cms_article_categories WHERE domain="'.$id.'"');
        $db->query("DELETE FROM cms_domains WHERE ID='".$id."'");
        $db->query("DELETE FROM cms_domains WHERE parentID='".$id."'");
        $db->query("DELETE FROM cms_domains_countries WHERE domainID='".$id."'");
        $db->query("DELETE FROM cms_domains_ids WHERE domainID='".$id."'");
        $db->query("DELETE FROM cms_domains_ips WHERE domainID='".$id."'");
        $db->query('DELETE FROM cms_global_settings WHERE domain="'.$id.'"');
        $db->query("DELETE FROM cms_homepage WHERE domain='".$id."'");
        $db->query("DELETE FROM cms_menus WHERE domain='".$id."'");
        $db->query("DELETE FROM cms_menus_items WHERE domain='".$id."'");
        $db->query("DELETE FROM cms_modul_prefix WHERE domain='".$id."'");
        $db->query("DELETE FROM cms_seo_redirects WHERE domainID='".$id."'"); 
        $db->query("DELETE FROM cms_template WHERE domain='".$id."'");
        $db->query('DELETE FROM cms_template_position WHERE domain="'.$id.'"');        
        
        //template
		recursive_remove_directory('../../templates/'.$name.'/');
		
		//module
		recursive_remove_directory('../../modules/'.$name);
		
		//off
		recursive_remove_directory('../../off/'.$name);
		
		//block
		recursive_remove_directory('../../block/'.$name);
		
		//block
		recursive_remove_directory('../../404/'.$name);
		
		//images
		recursive_remove_directory('../../images/'.$name);
		
		//storage
		recursive_remove_directory('../../storage/'.$name);
        
        return "ok";
	}
}
?>