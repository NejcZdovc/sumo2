<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Xml
{
	private $filename = NULL;
	private $parser = NULL;
	private $xmlFile = NULL;
	private $indexArray = array();
	private $valArray = array();
	
	private function getFile($filename)
	{
		$this->xmlFile = file_get_contents($filename);
		$this->filename = $filename;
	}
	
	private function setParser()
	{
		$this->parser = xml_parser_create();
		xml_set_object($this->parser, $this);
		xml_parser_set_option($this->parser, XML_OPTION_TARGET_ENCODING, 'UTF-8');
    	xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
   		xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);
	}
	
	private function parseStruct()
	{
		xml_parse_into_struct($this->parser, str_replace(array("\n", "\r", "\t"), '', $this->xmlFile), $this->valArray, $this->indexArray);
		xml_parser_free($this->parser);	
	}
	
	public function getSpecialArray($filename)
	{
		$this->getFile($filename);
		$this->setParser();
		$this->parseStruct();
		return $this->valArray;
	}

	public function getSpecialArrayFromNet($filename)
	{
		$this->xmlFile=$filename;
		$this->setParser();
		$this->parseStruct();
		return $this->valArray;
	}
}

$xml = new Xml();
?>