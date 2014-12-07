<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Template {
	public $tempName = '';
	public $tempID = 1;
	public $langID = 1;
	public $menuID = 1;

	public function head() {
		global $xml, $user;
		$xmlParse = $xml->getSpecialArray(SITE_ROOT.DS.'templates/'.$user->domainName.'/'.$this->tempName.'/settings.xml');
		foreach($xmlParse as $element) {
			if($element['tag'] == 'style') {
				echo '<link type="text/css" rel="stylesheet" href="../../templates/'.$user->domainName.'/'.$this->tempName.'/'.$element['value'].'" />';
			}
		}
		echo '<script type="text/javascript" src="../scripts/site.tree.js"></script>';
		echo '<link type="text/css" rel="stylesheet" href="../css/site.tree.css" />';
		echo '<script type="text/javascript">st.PAGE='.$this->menuID.';st.LANG='.$this->langID.';</script>';
	}
}

$template = new Template();
?>
