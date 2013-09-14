<?php 
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Language {
	public $_specialArray = null;
	
	private $_open = false;
	
	private $_oldVariable = null;
	
	public function setLanguage($short) {
		global $xml, $db;	
		$filename = SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'language/'.$short.'/php.lang.xml';
		if(!is_file($filename)) {
			$filename = SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'language/en/php.lang.xml';
		}
		$this->_specialArray = $xml->getSpecialArray($filename);
		foreach($this->_specialArray as $element) {
			if($element['tag'] == 'item' && $element['type'] == 'complete') {
				if(isset($element['value']))
				$this->{$element['attributes']['constant']} = $element['value'];
			} else if($element['tag'] == 'item' && $element['type'] == 'open') {
				$this->_open = true;
				$this->{$element['attributes']['constant']} = $element['value'];
				$this->_oldVariable = $element['attributes']['constant'];
			} else if($element['tag'] == 'item' && $element['type'] == 'close' && $this->_open == true && $this->_oldVariable != null) {
				$this->_open = false;
				if(isset($element['value']))
				$this->{$this->_oldVariable} .= $element['value'];
				$this->_oldVariable = null;
			} else if($this->_open == true && $this->_oldVariable != null) {
				if(isset($element['value'])) {
					$this->{$this->_oldVariable} .= '<'.$element['tag'].'>'.$element['value'].'</'.$element['tag'].'>';
				} else {
					$this->{$this->_oldVariable} .= '<'.$element['tag'].' />';
				}
			}
		}
		$modulesQ = $db->query("SELECT * FROM cms_modules_def WHERE status='N'");
		while($result = $db->fetch($modulesQ)) {
			$filename = SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'modules/'.$result['moduleName'].'/language/php/'.$short.'.xml';
			if(!is_file($filename)) {
				$filename = SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'modules/'.$result['moduleName'].'/language/php/en.xml';
			}
			$this->_specialArray = $xml->getSpecialArray($filename);
			foreach($this->_specialArray as $element) {
				if($element['tag'] == 'item' && $element['type'] == 'complete') {
					$this->{$element['attributes']['constant']} = $element['value'];
				} else if($element['tag'] == 'item' && $element['type'] == 'open') {
					$this->_open = true;
					$this->{$element['attributes']['constant']} = $element['value'];
					$this->_oldVariable = $element['attributes']['constant'];
				} else if($element['tag'] == 'item' && $element['type'] == 'close' && $this->_open == true && $this->_oldVariable != null) {
					$this->_open = false;
					$this->{$this->_oldVariable} .= $element['value'];
					$this->_oldVariable = null;
				} else if($this->_open == true && $this->_oldVariable != null) {
					if(isset($element['value'])) {
						$this->{$this->_oldVariable} .= '<'.$element['tag'].'>'.$element['value'].'</'.$element['tag'].'>';
					} else {
						$this->{$this->_oldVariable} .= '<'.$element['tag'].' />';
					}
				}
			}
		}
		$componentsQ = $db->query("SELECT * FROM cms_components_def WHERE status='N'");
		while($result = $db->fetch($componentsQ)) {
			$filename = SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'modules/'.$result['componentName'].'/language/php/'.$short.'.xml';
			if(!is_file($filename)) {
				$filename = SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'modules/'.$result['componentName'].'/language/php/en.xml';
			}
			$this->_specialArray = $xml->getSpecialArray($filename);
			foreach($this->_specialArray as $element) {
				if($element['tag'] == 'item' && $element['type'] == 'complete') {
					if(isset($element['value']))
					$this->{$element['attributes']['constant']} = $element['value'];
				} else if($element['tag'] == 'item' && $element['type'] == 'open') {
					$this->_open = true;
					if(isset($element['value']))
					$this->{$element['attributes']['constant']} = $element['value'];
					$this->_oldVariable = $element['attributes']['constant'];
				} else if($element['tag'] == 'item' && $element['type'] == 'close' && $this->_open == true && $this->_oldVariable != null) {
					$this->_open = false;
					if(isset($element['value']))
					$this->{$this->_oldVariable} .= $element['value'];
					$this->_oldVariable = null;
				} else if($this->_open == true && $this->_oldVariable != null) {
					if(isset($element['value'])) {
						$this->{$this->_oldVariable} .= '<'.$element['tag'].'>'.$element['value'].'</'.$element['tag'].'>';
					} else {
						$this->{$this->_oldVariable} .= '<'.$element['tag'].' />';
					}
				}
			}
		}
	}
	
	public function getLanguage() {
		global $session;
		if($session->isLogedIn()) {
			global $db;
			$id = $session->getId();
			$query2 = $db->get($db->query("SELECT lang FROM cms_user_settings WHERE userID='".$id."'"));
			$query = $db->get($db->query("SELECT short FROM cms_language WHERE ID='".$query2['lang']."'"));
			$this->setLanguage($query['short']);
		} else {
			$this->setLanguage('en');
		}
	}
	
	function __construct() {
		
	}
}
$lang = new Language();
$lang->getLanguage();
?>