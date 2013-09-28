<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Database
{
	private $connection;
	
	function __construct()
	{
		$this->open_connection();
	}
	
	public function open_connection()
	{
		$this->connection = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
		if($this->connection->connect_errno)
		{
			error_log(ERR_DB_CON.': '.$this->connection->error);
		}		
		$this->connection->set_charset('utf8');
	}
	
	public function close_connection()
	{
		if(isset($this->connection))
		{
			$this->connection->close();
			unset($this->connection);
		}
	}
	
	public function free($result_set)
	{
		$result_set->free();
	}
	
	public function query($sql)
	{
		$result = $this->connection->query($sql);
		if(!$result)
		{
			error_log(ERR_DB_QRY.': '.$this->connection->error.' '.ERR_QUERY.': '.$sql);
		}
		return $result;
	}
	
	public function getLastId() {
		return $this->connection->insert_id;
	}
	
	public function fetch($result_set)
	{
		return $result_set->fetch_assoc();;
	}
	
	public function rows($result_set)
	{
		return $result_set->num_rows;
	}
	
	public function get($result_set)
	{
		if($result_set->num_rows > 0)
		{
			return $result_set->fetch_assoc();
		}
		else
		{
			return false;	
		}
	}
	
	public function filter($variable)
	{
		if(isset($_POST[$variable])) {
			$variable = $_POST[$variable];
		}
		else if(isset($_GET[$variable])) {
			$variable = $_GET[$variable];
		}
		else {
			$variable="";
		}
		$variable = str_replace('###','&',$variable);
		$variable = str_replace('?!?!##','+',$variable);		
		return $this->connection->real_escape_string($variable);
	}
	
	public function filterVar($variable)
	{		
		return $this->connection->real_escape_string($variable);
	}
	
	#TODO
	public function checkField($tableName,$columnName)
	{
		if($query = $this->query("SELECT ".$db->filterVar($columnName)." FROM ".$db->filterVar($table))) {
			return 1;
		}
		return 0;
	}
	
	#TODO
	public function getColumnNames($table) {
		$query = $this->query("SHOW COLUMNS FROM ".$db->filterVar($tableName)."");
		$columnNames = array();
		while ($field = $this->fetch($query)) { 
            $columnNames[]=$field["Field"]; //prints out all columns 
        } 
		return $columnNames;
	}
	
	public function is($variable) 
	{
		if(isset($_POST[$variable]))
			return true;
		else if(isset($_GET[$variable]))
			return true;
		else
			return false;		
	}
	
	public function error() {
		if($this->connection->error=="") {
			return false;	
		} else {
			return $this->connection->error;
		}
	}
	
	public function isError() {
		if($this->connection->error=="") {
			return false;
		} else {
			return true;
		}
	}
}

$db = new Database();
?>