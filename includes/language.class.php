<?php 
if( !defined( '_VALID_MIX' ) && !defined( '_VALID_ETT' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}

class Language {
	public $_specialArray = null;
	
	private $_open = false;
	
	private $_oldVariable = null;
	
	public function getLanguage($short, $path) {
		global $xml, $db;	
		$filename = $path.'language/php/'.$short.'.xml';
		if(!is_file($filename)) {
			$filename = $path.'language/php/en.xml';
		}
		if(!is_file($filename))  return;
		
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
	}
	
	function __construct() {
		
	}
}
?>