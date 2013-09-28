<?	require_once('../initialize.php');
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 exit;
	}
if(ob_get_length()>0) {ob_end_clean(); }
if($db->is('type')) {
	if($db->filter('type') == 'addRedirect') {
		$source = $db->filter('source');
		$destination = $db->filter('destination');
		$code = $db->filter('code');
		$db->query("INSERT INTO cms_seo_redirects (source, destination, type, lang, domainID) VALUES ('".$source."', '".$destination."', '".$code."', '".$user->translate_lang."', '".$user->domain."')");
		echo 'ok';
		exit;
	}else if($db->filter('type') == 'editRedirect') {
		$source = $db->filter('source');
		$destination = $db->filter('destination');
		$code = $db->filter('code');
		$id = $crypt->decrypt($db->filter('id'));
		$db->query('UPDATE cms_seo_redirects SET source="'.$source.'", destination="'.$destination.'", type="'.$code.'" WHERE ID="'.$id.'"');
		echo 'ok';
		exit;
	}else if($db->filter('type') == 'deleteRedirect') {
		$db->query('DELETE FROM cms_seo_redirects WHERE ID="'.$crypt->decrypt($db->filter('id')).'"');
		echo 'ok';
		exit;
	}
}