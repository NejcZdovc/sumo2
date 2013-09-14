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
	
	private $server = DB_SERVER;
	
	private $user = DB_USER;
	
	private $pass = DB_PASSWORD;
	
	private $database = DB_DATABASE;
	
	private $last_query;
	
	function __construct()
	{
		$this->open_connection();
	}
	
	public function open_connection()
	{
		$this->connection = mysql_connect($this->server,$this->user,$this->pass);
		if(!$this->connection)
		{
			error_log(ERR_DB_CON.': '.mysql_error());
		}
		else
		{
			$db_select = mysql_select_db($this->database, $this->connection);
			if(!$db_select)
			{
				error_log(ERR_DB_SEL.': '.mysql_error());
			}
		}
	}
	
	public function close_connection()
	{
		if(isset($this->connection))
		{
			mysql_close($this->connection);
			unset($this->connection);
		}
	}
	
	public function query($sql)
	{
		$this->last_query = $sql;
		$result = mysql_query($sql,$this->connection);
		if(!$result)
		{
			error_log(ERR_DB_QRY.': '.mysql_error().' '.ERR_QUERY.': '.$this->last_query);
		}
		return $result;
	}
	
	public function getLastId() {
		return mysql_insert_id($this->connection);
	}
	
	public function fetch($result_set)
	{
		return str_replace('$!$','&',mysql_fetch_array($result_set));	
	}
	
	public function rows($result_set)
	{
		return mysql_num_rows($result_set);
	}
	
	public function get($result_set)
	{
		if($this->rows($result_set) > 0)
		{
			return $this->fetch($result_set);	
		}
		else
		{
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
		}	
		if(is_array($variable)) {
			$postArray = array();
			foreach($variable as $item){
				array_push($postArray, mysql_real_escape_string($item));
			}
			return $postArray;
		} else
			return mysql_real_escape_string($variable);
	}
	
	public function filterVariable($variable)
	{	
		if(is_array($variable)) {
			$postArray = array();
			foreach($variable as $item){
				array_push($postArray, mysql_real_escape_string($item));
			}
			return $postArray;
		} else
			return mysql_real_escape_string($variable);	
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

$database = new Database();
$db =& $database;

?>