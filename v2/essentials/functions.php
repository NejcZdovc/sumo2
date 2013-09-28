<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
function redirect_to($location = NULL)
{
	if($location != NULL)
	{
		header('Location: '.$location);
		exit;
	}
}

function getDomain() {
	return $_SERVER['HTTP_HOST'];	
}

function pagging ($number_acc, $pagging)
{
	$size=$pagging[0];
	$current=$pagging[1];
	$results=$pagging[2];
	
	$content='<div style="margin-top:10px;margin-left:10px;">';
	$int=ceil($results/$size);
	if($int>1) {
		if($current>0) {
			if($int>3 && $current!=1 && ($current-3)>0)
				$content.='<div class="pagging-button" onclick="sumo2.accordion.ReloadAccordion(\''.$number_acc.'\',\'pag_id=0&size='.$size.'\')">&lt;&lt;</div>';
			for($i=3; $i>0; $i--) {
				if(($current-$i+1)>0) 
					$content.='<div class="pagging-button" onclick="sumo2.accordion.ReloadAccordion(\''.$number_acc.'\',\'pag_id='.($current-$i).'&size='.$size.'\')">'.($current-$i+1).'</div>';
			}
		}
		else $current=0;
				
		$content.='<div class="pagging-button sel-button" onclick="sumo2.accordion.ReloadAccordion(\''.$number_acc.'\',\'pag_id='.$current.'&size='.$size.'\')"><b>'.($current+1).'</b></div>';
		for($i=1; $i<4; $i++) {
			if(($current+$i)<$int)
				$content.= '<div class="pagging-button" onclick="sumo2.accordion.ReloadAccordion(\''.$number_acc.'\',\'pag_id='.($current+$i).'&size='.$size.'\')">'.($current+$i+1)."</div>";
		}
		if($int>3 && $current!=$int)
			$content.='<div class="pagging-button" onclick="sumo2.accordion.ReloadAccordion(\''.$number_acc.'\',\'pag_id='.$int.'&size='.$size.'\')">&gt;&gt;</div>';
	}
	$content.='</div>';
	return $content;
}

function check_pagging($sql, $page) {
	global $db;
	$int_rows =$db->rows($db->query($sql));
	if(isset($_POST['size']))
		$page_size=$_POST['size'];
	else
		$page_size=$page;
		
	if(isset($_POST['pag_id']))
		$pag_id=$_POST['pag_id'];
	else
		$pag_id=0;
		
	if($int_rows>$page_size) 
		$limit=" LIMIT ".($pag_id*$page_size).", ".$page_size."";
	else
		$limit="";
	$sql.=$limit;
	return Array($page_size, $pag_id, $int_rows, $limit, $sql);
}

function dropdown_pagging($number_acc, $current) {
	
	$array = array(10, 20, 50, 100, 200, 666);
	$result='Display #<select name="displaySelection" class="input" onchange="sumo2.accordion.ReloadAccordion(\''.$number_acc.'\',\'pag_id=0$!$size=\'+this.value+\'\')">';
	foreach ($array as $i => $value) {
		if($current==$array[$i]) $selected='selected="selected"'; else $selected='';
		if($array[$i]===666)
			$result.='<option value="666" '.$selected.'>All</option>';
		else
    		$result.='<option value="'.$array[$i].'" '.$selected.'>'.$array[$i].'</option>';
	}	
	$result.='</select>';
	
	return $result;
}

function lang_name($int) {
	global $db;
	$query = $db->fetch($db->query("SELECT short FROM cms_language WHERE ID='".$int."'"));
	return $query['short'];	
}
function lang_name_front($int) {
	global $db;
	$query = $db->fetch($db->query("SELECT short FROM cms_language_front WHERE ID='".$int."'"));
	return $query['short'];	
}

function lang_dropdown($current,$number_acc, $id) {
	global $db, $user;
	$result='<select onchange="sumo2.languageSelection.ChangeLang(\''.$number_acc.'\',\''.$id.'\')" name="'.$number_acc.'-langselect" id="'.$number_acc.'-langselect" style="text-transform:capitalize;">';
	
	$query = $db->query("SELECT value FROM cms_domains_ids WHERE type='lang' AND domainID='".$user->domain."'");
	while($rez=$db->fetch($query)) {
		$resultL = $db->get($db->query("SELECT ID, short, name FROM cms_language_front WHERE short='".$rez['value']."'"));
		if($current==$resultL['ID']) 
    		$result.='<option value="'.$resultL['ID'].'" style="background: url(images/icons/flags/'.$resultL['short'].'.png) top left no-repeat; padding-left:20px; text-transform:capitalize; font-weight:bold;" selected="selected">'.$resultL['name'].'</option>';
		else
		$result.='<option value="'.$resultL['ID'].'" style="background: url(images/icons/flags/'.$resultL['short'].'.png) top left no-repeat; padding-left:20px; text-transform:capitalize;">'.$resultL['name'].'</option>';
	}	
	$result.='</select>';
	
	return $result;
	
}

function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
   
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
   
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
   
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
   
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
   
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
} 

function checkTemplate($ID) {
	global $db;
	$template = $db->query("SELECT ID FROM cms_template WHERE status='N' AND enabled='1' AND ID='".$ID."'");
	if($db->rows($template)!=1)
		$ID=-1;
	return $ID;	
}

function getSize($size) {
	$endSize = $size/1000;
	$end = ' KB';
	if($endSize > 1000) {
		$endSize = $endSize/1000;
		$end = ' MB';	
	}
	return round($endSize,2).$end;
}

function getImageFav($data,$subtitle) {
	$dataArray = explode('.',$data);
	if(strtolower($dataArray[count($dataArray)-1]) == 'png' ||	strtolower($dataArray[count($dataArray)-1]) == 'jpg' || strtolower($dataArray[count($dataArray)-1]) == 'gif' || strtolower($dataArray[count($dataArray)-1]) == 'jpeg') {
		return '<img src="'.$data.'" alt="'.$subtitle.'" />';
	} else {
		if($data=="")
			$data='background-image:url(images/css_sprite.png);background-position:-1741px -1631px;height:48px;width:48px;';
		return '<div style="display:block;'.$data.'"></div>';	
	}
}
function recursive_remove_directory($directory, $empty=FALSE) {
	if(substr($directory,-1) == '/') {
		$directory = substr($directory,0,-1);
	}
	if(!file_exists($directory) || !is_dir($directory)) {
		return FALSE;
	} else if(!is_readable($directory)) {
		return FALSE;
	} else {
		$handle = opendir($directory);
		while (FALSE !== ($item = readdir($handle))) {
			if($item != '.' && $item != '..') {
				$path = $directory.'/'.$item;
				if(is_dir($path)) {
					recursive_remove_directory($path);
				} else {
					unlink($path);
				}
			}
		}
		closedir($handle);
		if($empty == FALSE) {
			if(!rmdir($directory)) {
				return FALSE;
			}
		}
		return TRUE;
	}
}
	



function addToName($name, $add) {
	$dotArray = explode('.',$name);
	$dotArray[count($dotArray)-2] .= $add;
	return implode('.',$dotArray);	
}

function setImages($path,$id) {
	global $user;
	$files = scandir($path);
	foreach($files as $file) {
		if(is_dir($path.$file)) {
			if($file != '.' && $file != '..') {
				setImages($path.$file.'/',$id);	
			}
		} else {
			if(is_file('../../templates/'.$user->domainName.'/images/'.$file)) {
				$newname = addToName($file,'_'.$id);
				copy($path.$file,'../../templates/'.$user->domainName.'/images/'.$newname);
				chmod('../../templates/images/'.$user->domainName.'/'.$newname,PER_FILE);
			} else {
				copy($path.$file,'../../templates/'.$user->domainName.'/images/'.$file);
				chmod('../../templates/images/'.$user->domainName.'/'.$file,PER_FILE);
			}
		}
	}
}
function chmodAll($path, $filePerm=PER_FILE, $dirPerm=PER_FOLDER) {
	if(!file_exists($path)) {
		return false;
	}
	if(is_file($path)) {
		chmod($path, $filePerm);
	} else if(is_dir($path)) {
		$foldersAndFiles = scandir($path);
		$entries = array_slice($foldersAndFiles, 2);
		foreach($entries as $entry) {
					chmodAll($path."/".$entry, $filePerm, $dirPerm);
		}
		chmod($path, $dirPerm);
	}
	return true;
}

function copyFiles($src, $dst) {
	$dir = opendir($src);
	if(!is_dir($dst)) {
		mkdir($dst, PER_FOLDER);
	}
	chmodAll($src);
	while(false !== ( $file = readdir($dir)) ) {
		if (( $file != '.' ) && ( $file != '..' )) {
			if ( is_dir($src . '/' . $file) ) {
				copyFiles($src . '/' . $file,$dst . '/' . $file);
				chmodAll($dst . '/' . $file);					
			}
			else {
				copy($src . '/' . $file,$dst . '/' . $file);
				chmodAll($dst . '/' . $file);					
			}
		}
	}
	closedir($dir);
}
	
function Clear ($folder, $domain=null) {
	global $user;
	if($domain==null)
		$dir=$_SERVER['DOCUMENT_ROOT'].'/modules/'.$user->domainName.'/'.$folder.'/cache/';
	else
		$dir=$_SERVER['DOCUMENT_ROOT'].'/modules/'.$domain.'/'.$folder.'/cache/';
	if(is_dir($dir)) {
		if($handler = opendir($dir)) { 
			while (($sub = readdir($handler)) !== FALSE) {
				if ($sub != "." && $sub != "..") {
					if(is_file($dir."/".$sub)) {
						chmod($dir."/".$sub, PER_FILE);
						unlink($dir."/".$sub);
					}
				}
			}
			closedir($handler);
		}
	}
	
	$dir=$_SERVER['DOCUMENT_ROOT'].'/modules/'.$folder.'/templates_c/';
	if(is_dir($dir)) {
		if($handler = opendir($dir)) { 
			while (($sub = readdir($handler)) !== FALSE) {
				if ($sub != "." && $sub != "..") {
					if(is_file($dir."/".$sub)) {
						chmod($dir."/".$sub, PER_FILE);
						unlink($dir."/".$sub);
					}
				}
			}
			closedir($handler);
		}
	}
	echo 'yes';
}

function checkPrefixTitle($exist, $table, $colum, $id="") {
	global $db;
	$sqlQuery="";
	if($id!="") {
		$sqlQuery=' AND ID!="'.$id.'"';		
	}
	if($exist!="") {
		$check=$db->query('SELECT ID FROM '.$table.' WHERE '.$colum.'="'.$exist.'" '.$sqlQuery.'');
		if($db->rows($check)==0) {			
			return false;
		} else {
			return true;
		}
	}
		
	return false;
}

function getPrefixTitle($niz, $tableQuery, $colum, $id="", $exist="", $customSQl="") {
	global $db;
	
	$sqlQuery="";
	if($id!="") {
		$sqlQuery=' AND ID!="'.$id.'"';		
	}
	
	if($exist!="") {
		$niz=strtolower($exist);
	} else {
		$niz=strtolower($niz);
	}
	
	$table = array(
		'š'=>'s', 'đ'=>'dj', 'ž'=>'z', 'č'=>'c', 'ć'=>'c','Þ'=>'B', 'ß'=>'Ss',
		'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
		'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
		'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
		'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r'
	);
	$niz=strtr($niz, $table);
	$niz=trim($niz, '-');
	$niz = preg_replace('/[^a-zA-Z0-9]+/', '-', $niz);

	if($exist!="") {
		$check=$db->query('SELECT ID FROM '.$tableQuery.' WHERE '.$colum.'="'.$niz.'" '.$sqlQuery.' '.$customSQl.'');
		if($db->rows($check)==0) {
			return $niz;
		}
	}
	
	$check=$db->query('SELECT ID FROM '.$tableQuery.' WHERE '.$colum.'="'.$niz.'" '.$sqlQuery.' '.$customSQl.'');
	if($db->rows($check)>0) {
		$number=1;
		while(true) {
			$check=$db->query('SELECT ID FROM '.$tableQuery.' WHERE '.$colum.'="'.$niz.'-'.$number.'" '.$sqlQuery.' '.$customSQl.'');
			if($db->rows($check)>0) {
				$number++;
			}
			else {
				$niz=$niz.'-'.$number;
				break;
			}
			if($number>5) {
				$niz=$niz.time();
				break;
			}		
		}
	}
	
	return $niz;
}

function getSpaces($level) {
	$spaces = '';
	for($i=0;$i<$level;$i++) {
		$spaces .= '&nbsp;&nbsp;';
	}
	return $spaces;
}
?>