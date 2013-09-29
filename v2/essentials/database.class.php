<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Database
{
	private $connection;
	
	function __construct($default=true)
	{
		if($default) {
			$this->open_connection(__DB_SERVER__, __DB_USER__, __DB_PASSWORD__, __DB_DATABASE__);
		}
	}
	
	public function open_connection($server, $user, $pass, $database)
	{
		$this->connection = new mysqli($server, $user, $pass, $database);
		if($this->connection->connect_errno)
		{
			error_log(ERR_DB_CON.': '.$this->connection->error);
		}		
		$this->connection->set_charset(__ENCODING__);
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
		if(is_object($result_set)) {
			return $result_set->fetch_assoc();	
		} else {
			return false;
		}
	}
	
	public function rows($result_set)
	{
		if(is_object($result_set)) {
			return $result_set->num_rows;
		} else {
			return false;
		}
	}
	
	public function get($result_set)
	{
		if(is_object($result_set)) {
			if($result_set->num_rows > 0)
			{
				return $result_set->fetch_assoc();	
			}
			else
			{
				return false;	
			}
		} else {
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
	
	public function checkField($tableName,$columnName)
	{
		if($query = $this->query("SELECT ".$this->filterVar($columnName)." FROM ".$this->filterVar($tableName))) {
			return true;
		}
		return false;
	}
	
	public function getColumnNames($tableName) {
		$query = $this->query("SHOW COLUMNS FROM ".$this->filterVar($tableName)."");
		$columnNames = array();
		while ($field = $this->fetch($query)) { 
            $columnNames[]=$field["Field"];
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