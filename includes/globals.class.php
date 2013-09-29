<?php
if( !defined( '_VALID_MIX' ) && !defined( '_VALID_ETT' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}

class Globals
{	
	function __construct() {
		global $db;
		$domain=$db->get($db->query('SELECT ID, name, parentID, alias FROM cms_domains WHERE name="'.str_replace('www.', '', $_SERVER['HTTP_HOST']).'"'));
		if($domain['alias']!="0") {
			$domain=$db->get($db->query('SELECT ID, name, parentID FROM cms_domains WHERE ID="'.$domain['alias'].'"'));
		}
		$this->domainName=$domain['name'];
		$this->domainID=$domain['ID'];
		$this->domainParent=$domain['parentID'];
		$results = $db->get($db->query("SELECT * FROM cms_global_settings WHERE domain='".$domain['ID']."'"));
		foreach($results as $key => $value) {
			$this->{$key} = $value;
		}
	}
}

$globals = new Globals();
?>
