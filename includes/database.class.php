<?php
if( !defined( '_VALID_MIX' ) && !defined( '_VALID_ETT' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
include_once(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.'/includes/errors.php');

class Database
{
	private $connection;
	
	private $last_query;
	
	function __construct()
	{
		$this->open_connection();
	}
	
	public function open_connection()
	{
		$this->connection = new mysqli(__DB_SERVER__, __DB_USER__, __DB_PASSWORD__, __DB_DATABASE__);
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
	
	public function free()
	{
		if(isset($this->connection))
		{
			$this->connection->close();
		}
	}
	
	public function query($sql)
	{
		$this->last_query = $sql;
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
			return str_replace('$!$','&', $result_set->fetch_assoc());	
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
		if(isset($_POST[$variable]))
		{
			$variable = $_POST[$variable];
		}
		else if(isset($_GET[$variable]))
		{
			$variable = $_GET[$variable];
		} else {
			return "";
		}
		if(is_array($variable)) {
			$postArray = array();
			foreach($variable as $item){
				array_push($postArray, $this->connection->real_escape_string($item));
			}
			return $postArray;
		} else
			return $this->connection->real_escape_string($variable);
	}
	
	public function filterVar($variable)
	{	
		if(is_array($variable)) {
			$postArray = array();
			foreach($variable as $item){
				array_push($postArray, $this->connection->real_escape_string($item));
			}
			return $postArray;
		} else
			return $this->connection->real_escape_string($variable);	
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
}

$db = new Database();
?>