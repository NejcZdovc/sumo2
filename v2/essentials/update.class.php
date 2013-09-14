<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}

class Update
{
	private $CurrentVersion = NULL;
	private $xmlFile = NULL;
	private $valArray = array();
	private $FolderArray = array();
	private $error= array();
	private $src="";
	private $dest="";
	
	private function checkServer($ftpConn) {
		if(@ftp_put($ftpConn, '/v2/temp/index.html', '/v2/index.html', FTP_BINARY)) {
			$_SESSION['FTPsrc']='/';
			$_SESSION['FTPdest']="/";
			error_log("0");
		} else if(@ftp_put($ftpConn, '/v2/temp/index.html', $_SERVER['DOCUMENT_ROOT'].'/v2/index.html', FTP_BINARY)) {
			$_SESSION['FTPsrc']=$_SERVER['DOCUMENT_ROOT'].'/';
			$_SESSION['FTPdest']="/";
			error_log("1");
		} else if(@ftp_put($ftpConn, $_SERVER['DOCUMENT_ROOT'].'/v2/temp/index.html', basename($_SERVER['DOCUMENT_ROOT']).'/v2/index.html', FTP_BINARY)) {
			$_SESSION['FTPsrc']=basename($_SERVER['DOCUMENT_ROOT']).'/';
		  	$_SESSION['FTPdest']=$_SERVER['DOCUMENT_ROOT'].'/';			
			error_log("2");
		} else if(@ftp_put($ftpConn, basename($_SERVER['DOCUMENT_ROOT']).'/v2/temp/index.html', $_SERVER['DOCUMENT_ROOT'].'/v2/index.html', FTP_BINARY)) {
			$_SESSION['FTPsrc']=$_SERVER['DOCUMENT_ROOT'].'/';
		    $_SESSION['FTPdest']=basename($_SERVER['DOCUMENT_ROOT']).'/';	
			error_log("3");
		} else if(@ftp_put($ftpConn, $_SERVER['DOCUMENT_ROOT'].'/v2/temp/index.html', $_SERVER['DOCUMENT_ROOT'].'/v2/index.html', FTP_BINARY)) {
			$_SESSION['FTPsrc']=$_SERVER['DOCUMENT_ROOT'].'/';
		  	$_SESSION['FTPdest']=$_SERVER['DOCUMENT_ROOT'].'/';			
			error_log("4");
		}
		else {
			error_log("no");
		}
	}
	
	private function removeDirectory($directory, $empty = false) {
		if(substr($directory,-1) == '/') {
			$directory = substr($directory,0,-1);
		}

		if(!file_exists($directory) || !is_dir($directory))	{
			return false;
		} else if(!is_readable($directory)) {
			return false;
		} else {
			$handle = opendir($directory);
			while (false !== ($item = readdir($handle))) {
				if($item != '.' && $item != '..') {
					$path = $directory.'/'.$item;
					if(is_dir($path))  {
						$this->removeDirectory($path);
					} else {
						unlink($path);
					}
				}
			}
			closedir($handle);
			if($empty == false) {
				if(!rmdir($directory)) {
					return false;
				}
			}
			return false;
		}
    }
	
	function ftp_delAll($ftp_stream, $directory)
	{
		global $lang;
		if (!is_resource($ftp_stream) || get_resource_type($ftp_stream) !== 'FTP Buffer') {	 
			return false;
		}

		$i             = 0;
		$files         = array();
		$folders       = array();
		$statusnext    = false;
		$currentfolder = $directory;

		$list = ftp_rawlist($ftp_stream, $directory, true);

		foreach ($list as $current) {	 
			if (empty($current)) {
				$statusnext = true;
				continue;
			}
			if ($statusnext === true) {
				$currentfolder = substr($current, 0, -1);
				$statusnext = false;
				continue;
			}	
			$split = preg_split('[ ]', $current, 9, PREG_SPLIT_NO_EMPTY);
			$entry = $split[8];
			$isdir = ($split[0]{0} === 'd') ? true : false;
			if ($entry === '.' || $entry === '..') {
				continue;
			}	 
			if ($isdir === true) {
				$folders[] = $currentfolder . '/' . $entry;
			} else {
				$files[] = $currentfolder . '/' . $entry;
			}	 
		}
	 
		foreach ($files as $file) {
			ftp_delete($ftp_stream, $file) ? '' : array_push($this->error, $lang->MOD_130.": ".$file);
		}	 
		rsort($folders);
		foreach ($folders as $folder) {
			ftp_rmdir($ftp_stream, $folder) ? '' : array_push($this->error, $lang->MOD_131.": ".$folder);
		}	 
		return ftp_rmdir($ftp_stream, $directory) ? '' : array_push($this->error, $lang->MOD_131.": ".$directory);
	}
	
	public function getFTP() {
		global $db, $crypt;
		$query=$db->get($db->query('SELECT FTP_user, FTP_url FROM cms_sumo_settings WHERE ID="1"'));
		$ftpUserName = $crypt->decrypt($query['FTP_user']);
		$ftpUserUrl = $query['FTP_url'];
		return $ftpUserName.'&&'.$ftpUserUrl;
	}
	
	private function ftp_copyAll($conn_id, $src_dir, $dst_dir) {
		global $lang;
		if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$dst_dir)){
		  $d = dir($src_dir);
		  ftp_mkdir($conn_id, $dst_dir); 
		  ftp_chmod($conn_id, 0755, $dst_dir);
		   while($file = $d->read()) {
			  if ($file != "." && $file != "..") {
				  if (is_dir($src_dir."/".$file)) { 
					  $this->ftp_copyAll($conn_id, $src_dir."/".$file, $dst_dir."/".$file);
				  } else {
					$upload = ftp_put($conn_id, $dst_dir."/".$file, $src_dir."/".$file, FTP_BINARY) ? '' : array_push($this->error, $lang->MOD_133.": ".$directory);;
				  }
			  }
		  }
		  $d->close();
		}
    } 
	
	public function getVersion()
	{
		global $crypt;
		unset($_SESSION['valArray']);
		unset($_SESSION['FTPdest']);
		unset($_SESSION['FTPsrc']);
		unset($_SESSION['CurrentVersion']);
		$this->getValues();
		$version="";
		if($_SESSION['valArray']!="")
			$version= "Yes&&N&&".$crypt->encrypt(''.time().'');
		else
			$version=  "No&&N&&".$crypt->encrypt(''.time().'');
		
		return $version;
	}
	
	public function getVersions()
	{
		if(!isset($_SESSION['valArray']))
			$this->getValues();
		if(!isset($_SESSION['CurrentVersion']))
			$this->getCurrent();
		$this->getArchive($_SESSION['valArray']);
		
		return 	$_SESSION['valArray'];
	}
	
	public function getTxt() 
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://3zsistemi.eu/update/'.$_SESSION['valArray'].'.txt');
		curl_setopt($ch, CURLOPT_FAILONERROR,1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
		$text = curl_exec($ch);
		curl_close($ch);
		if(strlen($text)>5 && $text != "")
			return $_SESSION['valArray'];
		else
			return 0;	
	}
	
	private function getCurrent() {
		global $globals;		
		$_SESSION['CurrentVersion']=$globals->version;
	}
	
	private function getValues() {
		global $xml, $user;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://3zsistemi.eu/update/versions.xml');
		curl_setopt($ch, CURLOPT_FAILONERROR,1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
		$versions = curl_exec($ch);
		curl_close($ch);
		$specialArray = $xml->getSpecialArrayFromNet($versions);
		if(!isset($_SESSION['CurrentVersion']))
			$this->getCurrent();
		$valArray=array();
		foreach($specialArray as $element) {
			if($element['tag'] == 'item') {
				$pos1 = strpos($_SESSION['CurrentVersion'], 'b');
				if(floatval($_SESSION['CurrentVersion'])<floatval($element['value']))
					array_push($valArray, $element['value']);
				else if($pos1!==false && floatval($_SESSION['CurrentVersion'])<=floatval($element['value']) && $_SESSION['CurrentVersion']!=$element['value'])
					array_push($valArray, $element['value']);
			}
		}
		sort($valArray, SORT_NUMERIC);
		if(count($valArray)==0) {
			if($user->beta==1) {
				foreach($specialArray as $element) {
					if($element['tag'] == 'beta') {
						if(floatval($_SESSION['CurrentVersion'])<floatval($element['value']))
							array_push($valArray, $element['value']);
					}
				}
				sort($valArray);
				if(count($valArray)==0)
					$_SESSION['valArray']="";
				else
					$_SESSION['valArray']=$valArray[0];
			} else
				$_SESSION['valArray']="";
		} else
			$_SESSION['valArray']=$valArray[0];
	}
	
	private function getArchive($value)
	{
		$url  = 'http://3zsistemi.eu/update/'.$value.'.zip';
		$path = '../temp/update/';
		$file = $value.'.zip';
		$fullPath = $path.$file;
		if(!is_dir($path)) {
			mkdir($path, 0755);
			chmod($path, 0755);
		}
		$fp = fopen($fullPath, 'w');
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		$data = curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		chmod($fullPath, 0755);
	}
	
	public function UnZip() {
		global $lang;
		if(!isset($_SESSION['valArray']))
			$this->getValues();
		$error= array();
		$zip = new ZipArchive;
		if ($zip->open('../temp/update/'.$_SESSION['valArray'].'.zip') === TRUE) {
			if(is_dir('../temp/update/'.$_SESSION['valArray'].'/')) {
				recursive_remove_directory('../temp/update/'.$_SESSION['valArray'].'/');
			}
			mkdir('../temp/update/'.$_SESSION['valArray'].'/', 0755);
			chmod('../temp/update/'.$_SESSION['valArray'].'/', 0755);
			$zip->extractTo('../temp/update/'.$_SESSION['valArray'].'/');
			if(!$zip->close())
				array_push($error, $lang->MOD_134."<br/>");
			else
				chmodAll('../temp/update/'.$_SESSION['valArray'].'/',0775,0755);
		}
		else
			array_push($error, $lang->MOD_135." ".$_SESSION['valArray'].".zip<br/>");
		if(count($error)==0)
			return "yes";
		else
			return $error;
	}
	
	public function SetPermissions() {
		global $xml, $db,$crypt, $lang, $globals;		
		$error= array();
		$ftpUserName = $crypt->decrypt($globals->FTP_user);
		$ftpUserPass = $crypt->decrypt($globals->FTP_pass);
		$ftpServer = $globals->FTP_url;		
		if($ftpUserName=='' || $ftpUserPass=='' || $ftpServer=='')
			return 'no';
		
		$ftpConn = ftp_connect($ftpServer, $globals->FTP_port);		 
		if (!$ftpConn) {
			array_push($error, $lang->MOD_128." $ftpServer<br/>".$lang->MOD_136);
		}		
		if (@ftp_login($ftpConn, $ftpUserName, $ftpUserPass)){
			if(!isset($_SESSION['valArray']))
				$this->getValues();
			if(!isset($_SESSION['FTPdest']) || !isset($_SESSION['FTPsrc']))
				$this->checkServer($ftpConn);
			if(is_file('../temp/update/'.$_SESSION['valArray'].'/permission.xml')) {
				$system = new DOMDocument();
				$system->load('../temp/update/'.$_SESSION['valArray'].'/permission.xml');
				$files = $system->getElementsByTagName('files')->item(0);
				foreach($files->getElementsByTagName('item') as $item) {
					if(file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$item->nodeValue.''))
						ftp_chmod($ftpConn, 0775, $_SESSION['FTPdest'].$item->nodeValue.'') ? '' : array_push($error, $lang->MOD_137.": ".$item->nodeValue."<br/>");
				}
				$folder = $system->getElementsByTagName('folder')->item(0);
				foreach($folder->getElementsByTagName('item') as $item) {
					if(file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$item->nodeValue.''))
						ftp_chmod($ftpConn, 0775, $_SESSION['FTPdest'].$item->nodeValue.'') ? '' : array_push($error, $lang->MOD_137.": ".$item->nodeValue."<br/>");
				}
			}
			ftp_close($ftpConn);
			if(count($error)==0)
				return "yes";
			else
				return $error;
		}
		else {
		   array_push($error, $lang->MOD_129." $ftpUserName<br/>".$lang->MOD_136);
		   ftp_close($ftpConn);	
		   return $error;		
		}
	}
	
	public function MYSQL() {
		global $db, $crypt, $lang, $xml;
		if(!isset($_SESSION['valArray']))
			$this->getValues();
		$error= array();
		if(is_file('../temp/update/'.$_SESSION['valArray'].'/base.php')) {
			include_once('../temp/update/'.$_SESSION['valArray'].'/base.php');
		}
		return "yes";	
	}
	
	public function DeleteFiles() {
		global $xml, $db,$crypt, $lang;		
		$error= array();
		$ftpUserName = $crypt->decrypt($globals->FTP_user);
		$ftpUserPass = $crypt->decrypt($globals->FTP_pass);
		$ftpServer = $globals->FTP_url;		
		if($ftpUserName=='' || $ftpUserPass=='' || $ftpServer=='')
			return 'no';
		
		$ftpConn = ftp_connect($ftpServer, $globals->FTP_port);		 
		if (!$ftpConn) {
			array_push($error, $lang->MOD_128." $ftpServer<br/>".$lang->MOD_136);
		}		
		if (@ftp_login($ftpConn, $ftpUserName, $ftpUserPass)){
			if(!isset($_SESSION['valArray']))
				$this->getValues();
			if(!isset($_SESSION['FTPdest']) || !isset($_SESSION['FTPsrc']))
				$this->checkServer($ftpConn);
			if(is_file('../temp/update/'.$_SESSION['valArray'].'/delete.xml')) {
				$system = new DOMDocument();
				$system->load('../temp/update/'.$_SESSION['valArray'].'/delete.xml');
				$files = $system->getElementsByTagName('files')->item(0);
				foreach($files->getElementsByTagName('item') as $item) {
					if(file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$item->nodeValue.'')) {
						ftp_delete($ftpConn, $_SESSION['FTPdest'].$item->nodeValue.'') ? '' : array_push($error, $lang->MOD_138.": ".$item->nodeValue."<br/>");
					}
				}
				$folder = $system->getElementsByTagName('folder')->item(0);
				foreach($folder->getElementsByTagName('item') as $item) {
					if(file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$item->nodeValue.'/')) {
						$this->error=array();
						$this->ftp_delAll($ftpConn, $_SESSION['FTPdest'].$item->nodeValue.'/');
						if(count($this->error) !=0)
							array_merge($error, $this->error);
						$this->error=array();
					}
				}
			}
			ftp_close($ftpConn);
			if(count($error)==0)
				return "yes";
			else
				return $error;
		}
		else {
		   array_push($error, $lang->MOD_129." $ftpUserName<br/>".$lang->MOD_136);
		   ftp_close($ftpConn);	
		   return $error;		
		}
	}
	
	public function CopyFiles() {
		global $xml, $db,$crypt, $lang;		
		$error= array();
		$ftpUserName = $crypt->decrypt($globals->FTP_user);
		$ftpUserPass = $crypt->decrypt($globals->FTP_pass);
		$ftpServer = $globals->FTP_url;		
		if($ftpUserName=='' || $ftpUserPass=='' || $ftpServer=='')
			return 'no';
		
		$ftpConn = ftp_connect($ftpServer, $globals->FTP_port);		 
		if (!$ftpConn) {
			array_push($error, $lang->MOD_128." $ftpServer<br/>".$lang->MOD_136);
		}		
		if (@ftp_login($ftpConn, $ftpUserName, $ftpUserPass)){
			if(!isset($_SESSION['valArray']))
				$this->getValues();
			if(!isset($_SESSION['FTPdest']) || !isset($_SESSION['FTPsrc']))
				$this->checkServer($ftpConn);
			if(is_file('../temp/update/'.$_SESSION['valArray'].'/copy.xml')) {
				$system = new DOMDocument();
				$system->load('../temp/update/'.$_SESSION['valArray'].'/copy.xml');
				$files = $system->getElementsByTagName('files')->item(0);
				foreach($files->getElementsByTagName('item') as $item) {
					ftp_put($ftpConn, $_SESSION['FTPdest'].$item->nodeValue.'', $_SESSION['FTPsrc'].'v2/temp/update/'.$_SESSION['valArray'].'/files_update/'.$item->nodeValue, FTP_BINARY) ? '' : array_push($error, $lang->MOD_132.": ".$item->nodeValue."<br/>");
				}
				$folder = $system->getElementsByTagName('folder')->item(0);
				foreach($folder->getElementsByTagName('item') as $item) {
					$this->error=array();
					$this->ftp_copyAll($ftpConn, $_SESSION['FTPsrc'].'v2/temp/update/'.$_SESSION['valArray'].'/files_update/'.$item->nodeValue, $_SESSION['FTPdest'].$item->nodeValue.'');
					if(count($this->error) !=0)
						array_merge($error, $this->error);
					$this->error=array();
				}
			}
			ftp_close($ftpConn);
			if(count($error)==0)
				return "yes";
			else
				return $error;
		}
		else {
		   array_push($error, $lang->MOD_129." $ftpUserName<br/>".$lang->MOD_136);
		   ftp_close($ftpConn);	
		   return $error;		
		}
	}
	
	public function Finish() {
		global $db;
		if(!isset($_SESSION['valArray']))
				$this->getValues();
		recursive_remove_directory('../temp/update/');
		$db->query('UPDATE cms_sumo_settings SET version="'.$_SESSION['valArray'].'"');
		$db->query('UPDATE cms_state SET state="empty"');
		unset($_SESSION['valArray']);
		unset($_SESSION['FTPdest']);
		unset($_SESSION['FTPsrc']);
		unset($_SESSION['CurrentVersion']);
	}
}

$update = new Update();
?>