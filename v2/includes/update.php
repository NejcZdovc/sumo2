<?php
	$isNoUpdateFile=1;
	require_once('../initialize.php');
	$security->checkMin();
if(ob_get_length()>0) { ob_end_clean();	}
	if($db->is('type')) {
		if($db->filter('type')=='check'){
			echo $update->getVersion();
		    exit;
		} else if($db->filter('type')=='step1'){
			$versions=$update->getVersions();
			$username=$update->getFTP();
			echo $username.'////'.$versions.'////'.$update->getTxt();
			exit;
		} else if($db->filter('type')=='step2'){
			$unzip=$update->UnZip();
			if($unzip!="yes")
				echo implode("!!!!!",$unzip);
			else {
				$permision=$update->SetPermissions();
				if($permision=="no")
					echo "no";
				else if($permision!="yes")
					echo implode("!!!!!",$permision);
				else {
					echo "yes";
				}
			}
			exit;
		} else if($db->filter('type')=='step3'){
			$mysql=$update->MYSQL();
			if($mysql!="yes")
				echo $mysql;
			else
				echo "yes";
			exit;
		} else if($db->filter('type')=='step4'){
			$delete=$update->DeleteFiles();
			if($delete!="yes")
				echo implode("!!!!!",$delete);
			else {
				$copy=$update->CopyFiles();
				if($copy!="yes") {
					if(is_array($copy)) {
						echo implode("!!!!!",$copy);
					} else {
						echo $copy;
					}
				} else {
					echo "yes";
				}
			}
			exit;
		} else if($db->filter('type')=='step5'){
			$mysql=$update->Finish();
			exit;
		} else if($db->filter('type')=='close'){
			$db->query('UPDATE cms_user_settings SET updateOption="OFF" WHERE userID="'.$user->id.'"');
			echo 'ok';
			exit;
		}
	}
?>