<?php
$isNoUpdateFile=1;
require_once('../initialize.php'); 
if(!$session->isLogedIn()) {
	exit;
}
if(file_exists("../system.xml")) { 
	$xml = simplexml_load_file("../system.xml","SimpleXMLElement", LIBXML_NOCDATA);
	$modXml = simplexml_load_file("../modules/system.xml","SimpleXMLElement", LIBXML_NOCDATA);
	header('Cache-Control: max-age=2592000');
	header('Expires-Active: On');
	header('Expires: Fri, 1 Jan 2500 01:01:01 GMT');
	header('Pragma:');
	header("Content-type: application/x-javascript; charset=utf-8");
	if($user->developer=="1") {
		echo "sumo2.language.GetLanguage();";
		echo "\n";
		echo "sumo2.accordion.MAX_PANELS='".AESEncryptCtr($user->accordion, "h39oyMN9cXzKT7loxCzYIUgD4uyHt9Fvccigc39GXpTjlAfkAlPegh3lnAIqJRDnAmJwc91WtwPHSs", 256)."';"; 
		echo "var editor_number=\"56065605650\";";
		echo "\n";
		echo "sumo2.settings={";
		echo "\n";
		echo "ADMIN_ROOT:\"".ADMIN_ADDR."\",";
		echo "\n";
		echo "DOC_ROOT:\"".SITE_ROOT.SITE_FOLDER."\",";
		echo "\n";
		echo "DIALOGS:Array(";
		echo "\n";
		$counter1 = 1;
		$counter2 = 1;
		foreach($xml->dialog->item as $dialog) {
			$counter2 = 1;
			echo "{";
			echo "\n";
			echo "refresh:".$dialog->refresh.",";
			echo "\n";
			echo "close:".$dialog->close.",";
			echo "\n";
			echo "uniqueId:\"".$dialog->uniqueId."\",";
			echo "\n";
			echo "title:".((strpos($dialog->title,"sumo2.language") === false)?"\"".$dialog->title."\"":$dialog->title).",";
			echo "\n";
			echo "page:\"".$dialog->page."\",";
			echo "\n";
			echo "params:\"".$dialog->params."\",";
			echo "\n";
			echo "height:".$dialog->height.",";
			echo "\n";
			echo "width:".$dialog->width.",";
			echo "\n";
			echo "end:function(data){".$dialog->end."},";
			echo "\n";
			if($dialog->uniqueId && $user->getAuth($dialog->uniqueId) < 2) {
				echo "buttons:Array()";
			} else {
				echo "buttons:Array(";
				echo "\n";
				foreach($dialog->buttons->button as $button) {
					echo "{";
					echo "\n";
					echo "title:".((strpos($button->title,"sumo2.language") === false)?"\"".$button->title."\"":$button->title).",";
					echo "\n";
					echo "icon:\"".$button->icon."\",";
					echo "\n";
					echo "func:function(){";
					echo "\n";
					echo "".$button->func."";
					echo "\n";
					echo "}";
					echo "\n";
					echo "}".(($counter2 == count($dialog->buttons->button))?"":",")."";
					echo "\n";
					$counter2++;
				}
				echo ")";
			}
			echo "\n";
			echo "}".(($counter1 == count($xml->dialog->item) && count($modXml->dialog->item) == 0)?"":",")."";
			echo "\n";
			$counter1++;	
		}
		$counter1 = 1;
		$counter2 = 1;
		foreach($modXml->dialog->item as $dialog) {
			$counter2 = 1;
			echo "{";
			echo "\n";
			echo "refresh:".$dialog->refresh.",";
			echo "\n";
			echo "close:".$dialog->close.",";
			echo "\n";			
			echo "uniqueId:\"".$dialog->uniqueId."\",";
			echo "\n";
			echo "title:".((strpos($dialog->title,"sumo2.language") === false)?"\"".$dialog->title."\"":$dialog->title).",";
			echo "\n";
			echo "page:\"".$dialog->page."\",";
			echo "\n";
			echo "params:\"".$dialog->params."\",";
			echo "\n";
			echo "height:".$dialog->height.",";
			echo "\n";
			echo "width:".$dialog->width.",";
			echo "\n";
			echo "end:function(data){".$dialog->end."},";
			echo "\n";
			if($dialog->uniqueId && $user->getAuth($dialog->uniqueId) < 2) {
				echo "buttons:Array()";
			} else {
				echo "buttons:Array(";
				echo "\n";
				foreach($dialog->buttons->button as $button) {
					echo "{";
					echo "\n";
					echo "title:".((strpos($button->title,"sumo2.language") === false)?"\"".$button->title."\"":$button->title).",";
					echo "\n";
					echo "icon:\"".$button->icon."\",";
					echo "\n";
					echo "func:function(){";
					echo "\n";
					echo "".$button->func."";
					echo "\n";
					echo "}";
					echo "\n";
					echo "}".(($counter2 == count($dialog->buttons->button))?"":",")."";
					echo "\n";
					$counter2++;
				}
				echo ")";
			}
			echo "\n";
			echo "}".(($counter1 == count($modXml->dialog->item))?"":",")."";
			echo "\n";
			$counter1++;	
		}
		echo "),";
		echo "\n";
		echo "ACCORDIONS:Array(";
		echo "\n";
		$counter1 = 1;
		$counter2 = 1;
		foreach($xml->accordion->item as $accordion) {
			$counter2 = 1;
			echo "{";
			echo "\n";
			echo "refresh:".$accordion->refresh.",";
			echo "\n";
			if($accordion->changes) {
				echo "changes:".$accordion->changes.",";
			} else {
				echo "changes:false,";
			}
			echo "\n";
			echo "uniqueId:\"".$accordion->uniqueId."\",";
			echo "\n";
			echo "title:\"".$accordion->title."\",";
			echo "\n";
			echo "page:\"".$accordion->page."\",";
			echo "\n";
			echo "params:\"".$accordion->params."\",";
			echo "\n";
			echo "minWidth:".$accordion->minWidth.",";
			echo "\n";
			echo "func:function(data){".$accordion->func."},";
			echo "\n";
			if($accordion->uniqueId && $user->getAuth($accordion->uniqueId) < 3) {
				echo "buttons:Array()";
				echo "\n";
			} else {
				echo "buttons:Array(";
				echo "\n";
					foreach($accordion->buttons->button as $button) {
						echo "{";
						echo "\n";
						echo "title:".((strpos($button->title,"sumo2.language") === false)?"\"".$button->title."\"":$button->title).",";
						echo "\n";
						echo "icon:\"".$button->icon."\",";
						echo "\n";
						echo "func:function(){";
						echo "\n";
						echo "".$button->func."";
						echo "\n";
						echo "}";
						echo "\n";
						echo "}".(($counter2 == count($accordion->buttons->button))?"":",")."";
						echo "\n";
						$counter2++;
					}
				echo ")";
				echo "\n";
			}
			echo "}".(($counter1 == count($xml->accordion->item) && count($modXml->accordion->item) == 0)?"":",")."";
			echo "\n";
			$counter1++;
		}
		$counter1 = 1;
		$counter2 = 1;
		foreach($modXml->accordion->item as $accordion) {
			$counter2 = 1;
			echo "{";
			echo "\n";
			echo "refresh:".$accordion->refresh.",";
			echo "\n";
			if($accordion->changes) {
				echo "changes:".$accordion->changes.",";
			} else {
				echo "changes:false,";
			}
			echo "\n";
			echo "uniqueId:\"".$accordion->uniqueId."\",";
			echo "\n";
			echo "title:\"".$accordion->title."\",";
			echo "\n";
			echo "page:\"".$accordion->page."\",";
			echo "\n";
			echo "params:\"".$accordion->params."\",";
			echo "\n";
			echo "minWidth:".$accordion->minWidth.",";
			echo "\n";
			echo "func:function(data){".$accordion->func."},";
			echo "\n";
			if($accordion->uniqueId && $user->getAuth($accordion->uniqueId) < 3) {
				echo "buttons:Array()";
				echo "\n";
			} else {
				echo "buttons:Array(";
				echo "\n";
					foreach($accordion->buttons->button as $button) {
						echo "{";
						echo "\n";
						echo "title:".((strpos($button->title,"sumo2.language") === false)?"\"".$button->title."\"":$button->title).",";
						echo "\n";
						echo "icon:\"".$button->icon."\",";
						echo "\n";
						echo "func:function(){";
						echo "\n";
						echo "".$button->func."";
						echo "\n";
						echo "}";
						echo "\n";
						echo "}".(($counter2 == count($accordion->buttons->button))?"":",")."";
						echo "\n";
						$counter2++;
					}
				echo ")";
				echo "\n";
			}
			echo "}".(($counter1 == count($modXml->accordion->item))?"":",")."";
			echo "\n";
			$counter1++;
		}
		echo ")";
		echo "\n";
		echo "};";
		echo "\n";
		echo "sumo2.specialArray={";
		echo "\n";
		echo "dialogs:{";
		echo "\n";
		$arrayCounter = 1;
		foreach($xml->dialog->item as $dialog) {
			echo "".$dialog->uniqueId.":".($arrayCounter-1).(($arrayCounter == count($xml->dialog->item) && count($modXml->dialog->item) == 0)?"":",")."";
			echo "\n";
			$arrayCounter++;
		}
		$tempHolder = $arrayCounter - 1;
		$arrayCounter = 1;
		foreach($modXml->dialog->item as $dialog) {
			echo "".$dialog->uniqueId.":".($arrayCounter+$tempHolder-1).(($arrayCounter == count($modXml->dialog->item))?"":",")."";
			echo "\n";
			$arrayCounter++;
		}
		echo "},";
		echo "\n";
		echo "accordions:{";
		echo "\n";
		$arrayCounter = 1;
		foreach($xml->accordion->item as $accordion) {
			echo "".$accordion->uniqueId.":".($arrayCounter-1).(($arrayCounter == count($xml->accordion->item) && count($modXml->accordion->item) == 0)?"":",")."";
			echo "\n";
			$arrayCounter++;
		}
		$tempHolder = $arrayCounter - 1;
		$arrayCounter = 1;
		foreach($modXml->accordion->item as $accordion) {
			echo "".$accordion->uniqueId.":".($arrayCounter+$tempHolder-1).(($arrayCounter == count($modXml->accordion->item))?"":",")."";
			echo "\n";
			$arrayCounter++;
		}
		echo "}";
		echo "\n";
		echo "};";
		echo "\n";
	} else {
		$output = "";
		$output .= "sumo2.language.GetLanguage();";
		$output .= "sumo2.accordion.MAX_PANELS='".AESEncryptCtr($user->accordion, "h39oyMN9cXzKT7loxCzYIUgD4uyHt9Fvccigc39GXpTjlAfkAlPegh3lnAIqJRDnAmJwc91WtwPHSs", 256)."';"; 
		$output .= "var editor_number=\"56065605650\";";
		$output .= "sumo2.settings={";
		$output .= "ADMIN_ROOT:\"".ADMIN_ADDR."\",";
		$output .= "DOC_ROOT:\"".SITE_ROOT.SITE_FOLDER."\",";
		$output .= "DIALOGS:Array(";
		$counter1 = 1;
		$counter2 = 1;
		foreach($xml->dialog->item as $dialog) {
			$counter2 = 1;
			$output .= "{";
			$output .= "refresh:".$dialog->refresh.",";
			$output .= "close:".$dialog->close.",";
			$output .= "uniqueId:\"".$dialog->uniqueId."\",";
			$output .= "title:".((strpos($dialog->title,"sumo2.language") === false)?"\"".$dialog->title."\"":$dialog->title).",";
			$output .= "page:\"".$dialog->page."\",";
			$output .= "params:\"".$dialog->params."\",";
			$output .= "height:".$dialog->height.",";
			$output .= "width:".$dialog->width.",";
			$output .= "end:function(data){".$dialog->end."},";
			if($dialog->uniqueId && $user->getAuth($dialog->uniqueId) < 2) {
				$output .= "buttons:Array()";
			} else {
				$output .= "buttons:Array(";
				foreach($dialog->buttons->button as $button) {
					$output .= "{";
					$output .= "title:".((strpos($button->title,"sumo2.language") === false)?"\"".$button->title."\"":$button->title).",";
					$output .= "icon:\"".$button->icon."\",";
					$output .= "func:function(){";
					$output .= "".$button->func."";
					$output .= "}";
					$output .= "}".(($counter2 == count($dialog->buttons->button))?"":",")."";
					$counter2++;
				}
				$output .= ")";
			}
			$output .= "}".(($counter1 == count($xml->dialog->item) && count($modXml->dialog->item) == 0)?"":",")."";
			$counter1++;	
		}
		$counter1 = 1;
		$counter2 = 1;
		foreach($modXml->dialog->item as $dialog) {
			$counter2 = 1;
			$output .= "{";
			$output .= "refresh:".$dialog->refresh.",";
			$output .= "close:".$dialog->close.",";
			$output .= "uniqueId:\"".$dialog->uniqueId."\",";
			$output .= "title:".((strpos($dialog->title,"sumo2.language") === false)?"\"".$dialog->title."\"":$dialog->title).",";
			$output .= "page:\"".$dialog->page."\",";
			$output .= "params:\"".$dialog->params."\",";
			$output .= "height:".$dialog->height.",";
			$output .= "width:".$dialog->width.",";
			$output .= "end:function(data){".$dialog->end."},";
			if($dialog->uniqueId && $user->getAuth($dialog->uniqueId) < 2) {
				$output .= "buttons:Array()";
			} else {
				$output .= "buttons:Array(";
				foreach($dialog->buttons->button as $button) {
					$output .= "{";
					$output .= "title:".((strpos($button->title,"sumo2.language") === false)?"\"".$button->title."\"":$button->title).",";
					$output .= "icon:\"".$button->icon."\",";
					$output .= "func:function(){";
					$output .= "".$button->func."";
					$output .= "}";
					$output .= "}".(($counter2 == count($dialog->buttons->button))?"":",")."";
					$counter2++;
				}
				$output .= ")";
			}
			$output .= "}".(($counter1 == count($modXml->dialog->item))?"":",")."";
			$counter1++;	
		}
		$output .= "),";
		$output .= "ACCORDIONS:Array(";
		$counter1 = 1;
		$counter2 = 1;
		foreach($xml->accordion->item as $accordion) {
			$counter2 = 1;
			$output .= "{";
			$output .= "refresh:".$accordion->refresh.",";
			if($accordion->changes) {
				$output .= "changes:".$accordion->changes.",";
			} else {
				$output .= "changes:false,";
			}
			$output .= "uniqueId:\"".$accordion->uniqueId."\",";
			$output .= "title:\"".$accordion->title."\",";
			$output .= "page:\"".$accordion->page."\",";
			$output .= "params:\"".$accordion->params."\",";
			$output .= "minWidth:".$accordion->minWidth.",";
			$output .= "func:function(data){".$accordion->func."},";
			if($accordion->uniqueId && $user->getAuth($accordion->uniqueId) < 3) {
				$output .= "buttons:Array()";
			} else {
				$output .= "buttons:Array(";
				foreach($accordion->buttons->button as $button) {
					$output .= "{";
					$output .= "title:".((strpos($button->title,"sumo2.language") === false)?"\"".$button->title."\"":$button->title).",";
					$output .= "icon:\"".$button->icon."\",";
					$output .= "func:function(){";
					$output .= "".$button->func."";
					$output .= "}";
					$output .= "}".(($counter2 == count($accordion->buttons->button))?"":",")."";
					$counter2++;
				}
				$output .= ")";
			}
			$output .= "}".(($counter1 == count($xml->accordion->item) && count($modXml->accordion->item) == 0)?"":",")."";
			$counter1++;
		}
		$counter1 = 1;
		$counter2 = 1;
		foreach($modXml->accordion->item as $accordion) {
			$counter2 = 1;
			$output .= "{";
			$output .= "refresh:".$accordion->refresh.",";
			if($accordion->changes) {
				$output .= "changes:".$accordion->changes.",";
			} else {
				$output .= "changes:false,";
			}
			$output .= "uniqueId:\"".$accordion->uniqueId."\",";
			$output .= "title:\"".$accordion->title."\",";
			$output .= "page:\"".$accordion->page."\",";
			$output .= "params:\"".$accordion->params."\",";
			$output .= "minWidth:".$accordion->minWidth.",";
			$output .= "func:function(data){".$accordion->func."},";
			if($accordion->uniqueId && $user->getAuth($accordion->uniqueId) < 3) {
				$output .= "buttons:Array()";
			} else {
				$output .= "buttons:Array(";
				foreach($accordion->buttons->button as $button) {
					$output .= "{";
					$output .= "title:".((strpos($button->title,"sumo2.language") === false)?"\"".$button->title."\"":$button->title).",";
					$output .= "icon:\"".$button->icon."\",";
					$output .= "func:function(){";
					$output .= "".$button->func."";
					$output .= "}";
					$output .= "}".(($counter2 == count($accordion->buttons->button))?"":",")."";
					$counter2++;
				}
				$output .= ")";
			}
			$output .= "}".(($counter1 == count($modXml->accordion->item))?"":",")."";
			$counter1++;
		}
		$output .= ")";
		$output .= "};";
		$output .= "sumo2.specialArray={";
		$output .= "dialogs:{";
		$arrayCounter = 1;
		foreach($xml->dialog->item as $dialog) {
			$output .= "".$dialog->uniqueId.":".($arrayCounter-1).(($arrayCounter == count($xml->dialog->item) && count($modXml->dialog->item) == 0)?"":",")."";
			$arrayCounter++;
		}
		$tempHolder = $arrayCounter - 1;
		$arrayCounter = 1;
		foreach($modXml->dialog->item as $dialog) {
			$output .= "".$dialog->uniqueId.":".($arrayCounter+$tempHolder-1).(($arrayCounter == count($modXml->dialog->item))?"":",")."";
			$arrayCounter++;
		}
		$output .= "},";
		$output .= "accordions:{";
		$arrayCounter = 1;
		foreach($xml->accordion->item as $accordion) {
			$output .= "".$accordion->uniqueId.":".($arrayCounter-1).(($arrayCounter == count($xml->accordion->item) && count($modXml->accordion->item) == 0)?"":",")."";
			$arrayCounter++;
		}
		$tempHolder = $arrayCounter - 1;
		$arrayCounter = 1;
		foreach($modXml->accordion->item as $accordion) {
			$output .= "".$accordion->uniqueId.":".($arrayCounter+$tempHolder-1).(($arrayCounter == count($modXml->accordion->item))?"":",")."";
			$arrayCounter++;
		}
		$output .= "}";
		$output .= "};";
		$output = trim($output);
		$output=preg_replace('/[\s]+/',' ',$output);
		echo $output;
	}
} else {
	error_log("ERROR: System.xml file doesn\"t exsist.");	
}
?>