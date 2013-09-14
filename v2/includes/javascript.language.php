<? 
$isNoUpdateFile=1;
require_once('../initialize.php');
if(!$session->isLogedIn()) {
 exit;
}
if (ob_get_length() > 0) {ob_end_clean();}
header('Content-Type: application/rss+xml;');
$query = $db->query("SELECT short FROM cms_language WHERE ID='".$user->lang."'");
$result = $db->get($query);
if($result) {
	$myfile = SITE_ROOT.SITE_FOLDER.'/v2/language/'.$result['short'].'/javascript.lang.xml';
	$fh = fopen($myfile,"rb");
	$data = fread($fh,filesize($myfile));
	fclose($fh);
	$data = str_replace('</language>','',$data);
	$moduleQ = $db->query("SELECT * FROM cms_modules_def WHERE status='N'");
	while($resultM = $db->fetch($moduleQ)) {
		$myfile = SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['moduleName'].'/language/javascript/'.$result['short'].'.xml';
		if(!is_file(SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['moduleName'].'/language/javascript/'.$result['short'].'.xml')) {
			if(is_file(SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['moduleName'].'/language/javascript/en-GB.xml')) {
				$myfile = SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['moduleName'].'/language/javascript/en-GB.xml';
			} else {
				continue;
			}
		}
		$fh = fopen($myfile,"rb");
		$readData = fread($fh,filesize($myfile));
		fclose($fh);
		$readData = str_replace('<?xml','',$readData);
		$readData = str_replace('version="1.0"','',$readData);
		$readData = str_replace('encoding="utf-8"','',$readData);
		$readData = str_replace('?>','',$readData);
		$readData = str_replace('<language>','',$readData);
		$readData = str_replace('</language>','',$readData);
		$data .= $readData;
	}
	$componentQ = $db->query("SELECT * FROM cms_components_def WHERE status='N'");
	while($resultM = $db->fetch($componentQ)) {
		$myfile = SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['componentName'].'/language/javascript/'.$result['short'].'.xml';
		if(!is_file(SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['componentName'].'/language/javascript/'.$result['short'].'.xml')) {
			if(is_file(SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['componentName'].'/language/javascript/en-GB.xml')) {
				$myfile = SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['componentName'].'/language/javascript/en-GB.xml';
			} else {
				continue;
			}
		}
		$fh = fopen($myfile,"rb");
		$readData = fread($fh,filesize($myfile));
		fclose($fh);
		$readData = str_replace('<?xml','',$readData);
		$readData = str_replace('version="1.0"','',$readData);
		$readData = str_replace('encoding="utf-8"','',$readData);
		$readData = str_replace('?>','',$readData);
		$readData = str_replace('<language>','',$readData);
		$readData = str_replace('</language>','',$readData);
		$data .= $readData;
	}
	$data .= '</language>';
	echo $data;
} else {
	$myfile = SITE_ROOT.SITE_FOLDER.'/v2/language/en-GB/javascript.lang.xml';
	$fh = fopen($myfile,"rb");
	$data = fread($fh,filesize($myfile));
	fclose($fh);
	$data = str_replace('</language>','',$data);
	$moduleQ = $db->query("SELECT * FROM cms_modules_def WHERE status='N'");
	while($resultM = $db->fetch($moduleQ)) {
		if(is_file(SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['moduleName'].'/language/javascript/en-GB.xml')) {
			$myfile = SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['moduleName'].'/language/javascript/en-GB.xml';
		} else {
			continue;
		}
		$fh = fopen($myfile,"rb");
		$readData = fread($fh,filesize($myfile));
		fclose($fh);
		$readData = str_replace('<?xml','',$readData);
		$readData = str_replace('version="1.0"','',$readData);
		$readData = str_replace('encoding="utf-8"','',$readData);
		$readData = str_replace('?>','',$readData);
		$readData = str_replace('<language>','',$readData);
		$readData = str_replace('</language>','',$readData);
		$data .= $readData;
	}
	$componentQ = $db->query("SELECT * FROM cms_components_def WHERE status='N'");
	while($resultM = $db->fetch($componentQ)) {
		if(is_file(SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['componentName'].'/language/javascript/en-GB.xml')) {
			$myfile = SITE_ROOT.SITE_FOLDER.'/v2/modules/'.$resultM['componentName'].'/language/javascript/en-GB.xml';
		} else {
			continue;
		}
		$fh = fopen($myfile,"rb");
		$readData = fread($fh,filesize($myfile));
		fclose($fh);
		$readData = str_replace('<?xml','',$readData);
		$readData = str_replace('version="1.0"','',$readData);
		$readData = str_replace('encoding="utf-8"','',$readData);
		$readData = str_replace('?>','',$readData);
		$readData = str_replace('<language>','',$readData);
		$readData = str_replace('</language>','',$readData);
		$data .= $readData;
	}
	$data .= '</language>';
	echo $data;
}
?>