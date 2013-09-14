<?php 
	$isNoUpdateFile=1;
	require_once('../initialize.php');
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 exit;
	}
	if(ob_get_length()>0) {
		ob_end_clean();
	}
	if(isset($_POST['type'])) {
		if($_POST['type']=='check'){
			echo $update->getVersion();
		    exit;
		}
		else if($_POST['type']=='step1'){
			$versions=$update->getVersions();
			$username=$update->getFTP();	
			echo $username.'////'.$versions.'////'.$update->getTxt();
			exit;
		}
		else if($_POST['type']=='step2'){
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
		}		
		else if($_POST['type']=='step3'){
			$mysql=$update->MYSQL();
			if($mysql!="yes")
				echo $mysql;
			else 
				echo "yes";
			exit;
		}
		else if($_POST['type']=='step4'){
			$delete=$update->DeleteFiles();
			if($delete!="yes")
				echo implode("!!!!!",$delete);
			else {
				$copy=$update->CopyFiles();
				if($copy!="yes")
					echo implode("!!!!!",$copy);
				else {
					echo "yes";
				}
			}
			exit;
		}
		else if($_POST['type']=='step5'){
			$mysql=$update->Finish();
			exit;
		}
		else if($_POST['type']=='close'){
			$db->query('UPDATE cms_user_settings SET updateOption="OFF" WHERE userID="'.$user->id.'"');
			echo 'ok';
			exit;
		}
	}
?>