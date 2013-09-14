<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Database
{
	private $connection;
	
	private $server = DB_SERVER;
	
	private $user = DB_USER;
	
	private $pass = DB_PASSWORD;
	
	private $database = DB_DATABASE;
	
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
	
	public function query($sql, $connection=null)
	{
		if($connection!=null)
			$result = mysql_query($sql,$connection);
		else
			$result = mysql_query($sql,$this->connection);
		if(!$result)
		{
			error_log(ERR_DB_QRY.': '.mysql_error().' '.ERR_QUERY.': '.$sql);
		}
		return $result;
	}
	
	public function getLastId($connection=null) {
		if($connection!=null)
			return mysql_insert_id($connection);
		else
			return mysql_insert_id($this->connection);
	}
	
	public function fetch($result_set)
	{
		return mysql_fetch_array($result_set);	
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
		return mysql_real_escape_string($variable);
	}
	
	public function filterVariable($variable)
	{		
		return mysql_real_escape_string($variable);
	}
	
	public function checkField($tableName,$columnName)
	{
		$tableFields = mysql_list_fields($this->database, $tableName);
		for($i=0;$i<mysql_num_fields($tableFields);$i++){
			if(mysql_field_name($tableFields, $i)==$columnName)
				return 1;
		}
		return 0;
	}
	
	public function getColumnNames($table) {
		$query = $this->query("SELECT * FROM ".$table);
		$columnNames = array();
		if($query) {
			$i = 0;
			while($i < mysql_num_fields($query)) {
				$meta = mysql_fetch_field($query, $i);
				if($meta) {
					$columnNames[] = $meta->name;
				}
				$i++;
			}
			mysql_free_result($query);
			return $columnNames;
		} else {
			return $columnNames;
		}
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
	
	public function returnError($connection=null) {
		if($connection!=null)
			return mysql_error($connection);
		else
			return mysql_error();	
	}
}

$database = new Database();
$db =& $database;

?>