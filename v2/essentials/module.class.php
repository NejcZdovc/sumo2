<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Module {
    public $number = 0;
    
    public $moduleID = '';
    
    public $moduleName = '';
    
    public $moduleEdit = '';
    
    public $parentComponent = 0;
    
    public $moduleInsertID = 0;
    
    public $componentInsertID = 0;
    
    public $componentID = '';
    
    public $componentName = '';
	
	public $domains= '';
    
    private $success = true;
    
    private $insertTable = array();
    
    private $tableTable = array();
    
    private $component = false;
    
    public $error = array();
    
    public function __construct() {
        
    }
    
    public function getFilename($path) {
        $pathArray = explode('/',$path);
        return $pathArray[count($pathArray)-1];
    }
    
    public function getFilePerms($file) {
        $permission =  substr(decoct( fileperms($file) ), 2);
        $permission *= 1.0;
        if($permission >= PER_FILE) {
            return true;
        }
        return false;
    }
    
    public function checkSystem() {
        $return = array();
        if(!$this->getFilePerms(SITE_ROOT.SITE_FOLDER.DS.'modules')) {
            $return[] = DS.'modules';
        }
        if(!$this->getFilePerms(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR)) {
            $return[] = DS.ADMIN_ADDR;
        }
        if(!$this->getFilePerms(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'modules')) {
            $return[] = DS.ADMIN_ADDR.DS.'modules';
        }
        if(!is_file(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'modules'.DS.'system.xml')) {
            $return[] = DS.ADMIN_ADDR.DS.'modules'.DS.'system.xml';
        } else {
            if(!$this->getFilePerms(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'modules'.DS.'system.xml')) {
                $return[] = DS.ADMIN_ADDR.DS.'modules'.DS.'system.xml';
            }
        }
        if(!is_file(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'modules'.DS.'javascript.xml')) {
            $return[] = DS.ADMIN_ADDR.DS.'modules'.DS.'javascript.xml';
        } else {
            if(!$this->getFilePerms(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'modules'.DS.'javascript.xml')) {
                $return[] = DS.ADMIN_ADDR.DS.'modules'.DS.'javascript.xml';
            }
        }
        if(!is_file(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'modules'.DS.'css.xml')) {
            $return[] = DS.ADMIN_ADDR.DS.'modules'.DS.'css.xml';
        } else {
            if(!$this->getFilePerms(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'modules'.DS.'css.xml')) {
                $return[] = DS.ADMIN_ADDR.DS.'modules'.DS.'css.xml';
            }
        }
        if(!$this->getFilePerms(SITE_ROOT.SITE_FOLDER.DS.ADMIN_ADDR.DS.'temp')) {
            $return[] = DS.ADMIN_ADDR.DS.'temp';
        }
        if(count($return) > 0) {
            return $return;
        } else {
            return true;
        }
    }
    
    public function registerModule($name, $id, $edit = true, $componentId = null, $version=null) {
        global $db, $lang;
        if($this->success === true) {
            $fId = 'mod_'.$db->filterVar($id);
			if($db->filterVar($edit)==false) {
				$fEdit = '';
				$dataEdit="";
			} else {
            	$fEdit = 'mod_'.$db->filterVar($id).'_edit';
				$dataEdit=" OR editName='".$fEdit."'";
			}
            $fName = $db->filterVar($name);
            $fComponent = $db->filterVar($componentId);
            $this->moduleEdit = $fEdit;
            $this->moduleID = $fId;
            $this->moduleName = $fName;
            $rows = $db->rows($db->query("SELECT * FROM cms_modules_def WHERE moduleName='".$fId."'".$dataEdit.""));
            if($rows > 0) {
                $this->success = false;
				$this->error[] = $lang->MOD_141.': '.$fId;
            } else {
                if($componentId === null) {
                    $result = $db->query("INSERT INTO cms_modules_def (moduleName,editName,name, version) VALUES ('".$fId."','".$fEdit."','".$fName."', '".$version."')");
                    if(!$result) {
                        $this->success = false;
						$this->error[] = $lang->MOD_142.': '.$fId;
                    } else {
                        $lastId = $db->getLastId();
                        $this->insertTable[] = array('cms_modules_def',$lastId);
                        $this->moduleInsertID = $lastId;
                    }
                } else {
                    $comQuery = $db->query("SELECT * FROM cms_components_def WHERE componentName='".$fComponent."'");
                    $rows = $db->rows($comQuery);
                    if($rows == 0 || $rows > 1) {
                        $this->success = false;
						$this->error[] = $lang->MOD_143.': '.$fId;
                    } else {
                        $comResult = $db->get($comQuery);
                        $this->parentComponent = $comResult['ID'];
                        $this->componentID = $comResult['componentName'];
                        $this->componentName = $comResult['name'];
                        $result = $db->query("INSERT INTO cms_modules_def (moduleName,editName,name,componentID,version) VALUES ('".$fId."','".$fEdit."','".$fName."','".$comResult['ID']."', '".$version."')");
                        if(!$result) {
                            $this->success = false;
			    			$this->error[] = $lang->MOD_144.': '.$fId;
                        } else {
                            $lastId = $db->getLastId();
                            $this->insertTable[] = array('cms_modules_def',$lastId);
                            $this->moduleInsertID = $lastId;
                        }
                    }
                }
            }
			if($this->moduleInsertID != 0) {
				if($db->filterVar($edit)==false)
					$this->addEditTableWithOut($db->filterVar($id));
            }		
        }
    }
    
    public function registerComponent($id, $name, $version=null) {
        global $db, $lang;
        if($this->success === true) {
            $fId = 'com_'.$db->filterVar($id);
            $fName = $db->filterVar($name);
            $this->componentID = $fId;
            $this->componentName = $fName;
            $rows = $db->rows($db->query("SELECT * FROM cms_components_def WHERE componentName='".$fId."'"));
            if($rows > 0) {
                $this->success = false;
				$this->error[] = $lang->MOD_145.': '.$fId;
            } else {
				$result = $db->query("INSERT INTO cms_components_def (name,componentName,version) VALUES ('".$fName."','".$fId."', '".$version."')");
				if(!$result) {
					$this->success = false;
					$this->error[] = $lang->MOD_147.': '.$fId;
				} else {
					$lastId = $db->getLastId();
					$this->insertTable[] = array('cms_components_def',$lastId);
					$this->component = true;
					$this->componentInsertID = $lastId;
				}
			}
        }
    }
    
    public function addSpecialPage($title, $id, $keyword = null, $description = null) {
    	global $db, $lang;
    	if($this->success === true) {
    		$fTitle = $db->filterVar($title);
    		$fId = $db->filterVar($id);
    		if($keyword == null) {
    			$fKeyword = '';
    		} else {
    			$fKeyword = $db->filterVar($keyword);
    		}
    		if($description == null) {
    			$fDescription = '';
    		} else {
    			$fDescription = $db->filterVar($description);
    		}
    		if($this->moduleInsertID != 0) {
    			$rows = $db->rows($db->query("SELECT * FROM cms_menus_items WHERE link='".$fId."'"));
    			if($rows > 0) {
    				$this->success = false;
		   		 $this->error[] = $lang->MOD_148.': '.$fId;
    			} else {
    				$langQuery = $db->query("SELECT * FROM cms_language_front WHERE enabled='1'");
    				while($lang = $db->fetch($langQuery)) {
						foreach($this->domains as $domain) {
							$result = $db->query("INSERT INTO cms_menus_items (menuID,title,parentID,orderID,keyword,description,selection,link,target,alias,domain) VALUES ('".$this->moduleInsertID."','".$fTitle."','-1','-1','".$fKeyword."','".$fDescription."','4','".$fId."','".$lang['ID']."','".$fId."_".$lang['short']."', '".$domain."')");
							if(!$result) {
								$this->success = false;
								$this->error[] = $lang->MOD_149.': '.$fId;
							} else {
								$this->insertTable[] = array('cms_menus_items',$db->getLastId());
							}
						}
    				}
    			}
    		} else {
    			$this->success = false;
			$this->error[] = $lang->MOD_150.': '.$fId;
    		}
    	}
    }
    
	public function addSubFavorites($main, $sub, $image, $link) {
		global $db, $lang;
		if($this->success === true) {
			$fMain = $db->filterVar($main);
			$fSub = $db->filterVar($sub);
			$fImage = $db->filterVar($image);
			$fLink = $db->filterVar($link);
			if($this->componentInsertID != 0) {
				$rows = $db->rows($db->query("SELECT * FROM cms_favorites_def WHERE title='".$fMain."' AND subtitle='".$fSub."'"));
				if($rows > 0) {
					$this->success = false;
					$this->error[] = $lang->MOD_151.': '.$fSub;
				} else {
					$result = $db->query("INSERT INTO cms_favorites_def (title,subtitle,img,click,comID) VALUES ('".$fMain."','".$fSub."','".$fImage."','".$fLink."', '".$this->componentInsertID."')");
					if(!$result) {
						$this->success = false;
						$this->error[] = $lang->MOD_152.': '.$fSub;
					} else {
						$this->insertTable[] = array('cms_favorites_def',$db->getLastId());
						$admin=$db->get($db->query('SELECT access FROM cms_user_groups WHERE ID="1" AND title="Super administrator"'));
						$access = unserialize(urldecode($admin['access']));
						if(!array_key_exists($fSub,$access)) {
							$access[$fSub] = 5;
							$ser_array = urlencode(serialize($access));
							$db->query('UPDATE cms_user_groups SET access="'.$ser_array.'" WHERE ID="1" AND title="Super administrator"');
						}
					}
				}
			} else {
                $this->success = false;
				$this->error[] = $lang->MOD_154.': '.$fSub;
            }
		}
	}
    
    public function addIncludes($link,$type) {
        global $db, $lang;
        if($this->success === true) {
            if($this->moduleInsertID != 0 || $this->componentInsertID !=0) {
                $fLink = 'mod_'.$db->filterVar($link);
                $fType = $db->filterVar($type);
                $rows = $db->rows($db->query("SELECT * FROM includes WHERE link='".$fLink."' AND modulID='".$this->moduleInsertID."'"));
                if($rows > 0) {
                    $this->success = false;
		    		$this->error[] = $lang->MOD_158.': '.$fLink;
                } else {
                    $result = $db->query("INSERT INTO includes (link,type,modulID) VALUES ('".$fLink."','".$fType."','".$this->moduleInsertID."')");
                    if(!$result) {
                        $this->success = false;
			$this->error[] = $lang->MOD_159.': '.$fLink;
                    } else {
                        $this->insertTable[] = array('includes',$db->getLastId());
                    }
                }
            } else {
                $this->success = false;
				$this->error[] = $lang->MOD_150.': '.$fLink;
            }
        }
    }
    
    public function addGroupInclude($link, $type) {
	global $db, $lang;
        if($this->success === true) {
            if($this->moduleInsertID != 0 || $this->componentInsertID != 0) {
                $fLink = 'mod_'.$db->filterVar($link);
                $fType = $db->filterVar($type);
				$trueFile = str_replace($this->moduleID.'/','',$fLink);
				if(is_file('../temp/'.$this->number.'/files-f/'.$trueFile)) {
					$fMd5 = md5_file('../temp/'.$this->number.'/files-f/'.$trueFile);
					$rows = $db->rows($db->query("SELECT * FROM cms_group_includes WHERE link='".$fLink."' AND modulID='".$this->moduleInsertID."' AND md5='".$fMd5."'"));
					if($rows > 0) {
						$this->success = false;
						$this->error[] = $lang->MOD_161.': '.$fLink;
					} else {
						$result = $db->query("INSERT INTO cms_group_includes (link,type,modulID,md5) VALUES ('".$fLink."','".$fType."','".$this->moduleInsertID."','".$fMd5."')");
						if(!$result) {
							$this->success = false;
							$this->error[] = $lang->MOD_162.': '.$fLink;
						} else {
							$this->insertTable[] = array('cms_group_includes',$db->getLastId());
						}
					}
				} else {
					$this->success = false;
					$this->error[] = $lang->MOD_163.': '.$fLink;
				}
            } else {
                $this->success = false;
				if($this->moduleInsertID == 0)
					$this->error[] = $lang->MOD_150.': '.$fLink;
				else
					$this->error[] = $lang->MOD_154.': '.$fLink;
            }
        }
    }
    
    public function addTable($tableName, $tableStructure) {
        global $db,$lang;
        if($this->success === true) {
            $fName = $db->filterVar($tableName);
            $rows = $db->rows($db->query("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE table_name='".$fName."' AND table_schema='".DB_DATABASE."'"));
            if($rows > 0) {
                $this->success = false;
				$this->error[] = $lang->MOD_166.': '.$fName;
            } else {
                $queryString = "CREATE TABLE IF NOT EXISTS ".$fName." (".$tableStructure.")";
                $result = $db->query($queryString);
                if(!$result) {
                    $this->success = false;
		    		$this->error[] = $lang->MOD_167.': '.$fName;
                } else {
                    $this->tableTable[] = $fName;
                    $this->addToTables($fName);
                }
            }
        }
    }
	
	
	public function addTableInsert($tableName, $query) {
		global $db, $lang;
		if($this->success === true) {
			$fName = $db->filterVar($tableName);
			$rows = $db->rows($db->query("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE table_name='".$fName."' AND table_schema='".DB_DATABASE."'"));
			if($rows == 0) {
				$this->success = false;
				$this->error[] = $lang->MOD_231.': '.$fName;
				return;
			}		
			$db->query($query);
			if ($db->isError()) {
				$this->success = false;
				$this->error[] = $lang->MOD_167.': '.$db->error();
			}
		}
	}
    
    public function addEditTable($tableName, $tableStructure) {
        global $db, $lang;
        if($this->success === true) {
            if($this->moduleInsertID != 0) {
                $fName = 'mod_'.$db->filterVar($tableName);
                $fStructure = $db->filterVar($tableStructure);
                $rows = $db->rows($db->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME='".$fName."'"));
                if($rows > 0) {
                    $this->success = false;
		    		$this->error[] = $lang->MOD_165.': '.$fName;
                } else {
                    if(strpos('cms_group_id',$tableStructure) == false && strpos('cms_panel_id',$tableStructure) == false && strpos('cms_layout',$tableStructure) == false) {
                        $tableStructure .= ',cms_group_id INT(11) NOT NULL, cms_panel_id INT(11) NOT NULL, cms_layout VARCHAR(200) NOT NULL, cms_enabled TINYINT(1) NOT NULL DEFAULT 1';
                        $queryString = 'CREATE TABLE IF NOT EXISTS '.$fName.' ('.$tableStructure.')';
                        $result = $db->query($queryString);
                        if(!$result) {
                            $this->success = false;
			   				$this->error[] = $lang->MOD_164.': '.$fName;
                        } else {
                            $this->tableTable[] = $fName;
                            $db->query("UPDATE cms_modules_def SET editTable='".$fName."' WHERE ID='".$this->moduleInsertID."'");
                        }
                    } else {
                        $this->success = false;
						$this->error[] = $lang->MOD_160.': '.$fName;
                    }
                }
            } else {
                $this->success = false;
				$this->error[] = $lang->MOD_150.': '.$fName;
            }
        }
    }
	
	public function addEditTableWithOut($tableName) {
        global $db, $lang;
        if($this->success === true) {
            if($this->moduleInsertID != 0) {
                $fName = 'mod_'.$db->filterVar($tableName);
				$tableStructure = 'ID INT NOT NULL PRIMARY KEY AUTO_INCREMENT ,cms_group_id INT(11) NOT NULL, cms_panel_id INT(11) NOT NULL, cms_layout VARCHAR(200) NOT NULL, cms_enabled TINYINT(1) NOT NULL DEFAULT 1';
				$queryString = 'CREATE TABLE IF NOT EXISTS '.$fName.' ('.$tableStructure.')';
				$result = $db->query($queryString);
				if(!$result) {
					$this->success = false;
					$this->error[] = $lang->MOD_164.': '.$fName;
				} else {
					$this->tableTable[] = $fName;
					$db->query("UPDATE cms_modules_def SET editTable='".$fName."' WHERE ID='".$this->moduleInsertID."'");
				}
            } else {
                $this->success = false;
				$this->error[] = $lang->MOD_150.': '.$fName;
            }
        }
    }
    
    private function addToTables($tableName) {
        global $db;
        if($this->component == true) {
            $tablesResult = $db->get($db->query("SELECT tables FROM cms_components_def WHERE ID='".$this->componentInsertID."'"));
            $newTablesString = $tablesResult['tables'];
            if(strlen($tablesResult['tables']) == 0) {
                $newTablesString = $tableName;
            } else {
                $newTablesString .= '!'.$tableName;
            }
            $db->query("UPDATE cms_components_def SET tables='".$newTablesString."' WHERE ID='".$this->componentInsertID."'");
        } else {
            $tablesResult = $db->get($db->query("SELECT tables FROM cms_modules_def WHERE ID='".$this->moduleInsertID."'"));
            $newTablesString = $tablesResult['tables'];
            if(strlen($tablesResult['tables']) == 0) {
                $newTablesString = $tableName;
            } else {
                $newTablesString .= '!'.$tableName;
            }
            $db->query("UPDATE cms_modules_def SET tables='".$newTablesString."' WHERE ID='".$this->moduleInsertID."'");
        }
    }
    
    public function reverseActions() {
        global $db;
        foreach($this->tableTable as $table) {
            $db->query("DROP TABLE IF EXISTS ".$table);
        }
        foreach($this->insertTable as $set) {
            $db->query("DELETE FROM ".$set[0]." WHERE ID='".$set[1]."'");
        }
    }
    
    public function getFileString($path) {
        return md5_file($path);
    }
    
    public function getUnique() {
        if($this->component == true) {
            return $this->componentID;
        } else {
            return $this->moduleID;
        }
    }
    
    public function getID() {
        if($this->component == true) {
            return $this->componentInsertID;
        } else {
            return $this->moduleInsertID;
        }
    }
    
    public function reverseFiles() {
        $javascript = new DOMDocument();
        $javascript->load('../modules/javascript.xml');
        $jsRoot = $javascript->getElementsByTagName("javascript")->item(0);
        $items = $javascript->getElementsByTagName("item");
		$delItems = array();
        foreach($items as $item) {
            $attributes = array();
            foreach($item->attributes as $attrName => $attrNode) {
                $attributes[$attrName] = $attrNode->value;
            }
            if($attributes['deleteId'] && $attributes['deleteId'] == $this->getUnique().'_'.$this->getID()) {
				$delItems[] = $item;
            }
        }
	foreach($delItems as $item) {
	    $item->parentNode->removeChild($item); 
	}
        $javascript->save('../modules/javascript.xml');
        $css = new DOMDocument();
        $css->load('../modules/css.xml');
        $cssRoot = $css->getElementsByTagName("css")->item(0);
        $items = $css->getElementsByTagName("item");
		$delItems = array();
        foreach($items as $item) {
            $attributes = array();
            foreach($item->attributes as $attrName => $attrNode) {
                $attributes[$attrName] = $attrNode->value;
            }
            if($attributes['deleteId'] && $attributes['deleteId'] == $this->getUnique().'_'.$this->getID()) {
				$delItems[] = $item;
            }
        }
		foreach($delItems as $item) {
			$item->parentNode->removeChild($item); 
		}
        $css->save('../modules/css.xml');
        if(is_dir('../modules/'.$this->getUnique())) {
            recursive_remove_directory('../modules/'.$this->getUnique().'/');
        }
        if(is_dir('../../modules/'.$this->getUnique())) {
            recursive_remove_directory('../../modules/'.$this->getUnique().'/');
        }
    }
    
    private function deleteChildren(&$node) {
		while ($node->firstChild) {
			while ($node->firstChild->firstChild) {
			$this->deleteChildren($node->firstChild);
			}
			$node->removeChild($node->firstChild);
		}
    }
    
    public function reverseSystem() {
        $system = new DOMDocument();
        $system->load('../modules/system.xml');
        $dialogs = $system->getElementsByTagName('dialog')->item(0);
		$delItems = array();
		foreach($dialogs->getElementsByTagName('item') as $item) {
			$attributes = array();
			foreach($item->attributes as $attrName => $attrNode) {
				$attributes[$attrName] = $attrNode->value;
			}
			if($attributes['deleteId'] && $attributes['deleteId'] == $this->getUnique().'_'.$this->getID()) {
				$delItems[] = $item;
			}
		}
		foreach($delItems as $item) {
			$this->deleteChildren($item);
			$item->parentNode->removeChild($item);
		}
		$accordions = $system->getElementsByTagName('accordion')->item(0);
		$delItems = array();
		foreach($accordions->getElementsByTagName('item') as $item) {
			$attributes = array();
			foreach($item->attributes as $attrName => $attrNode) {
				$attributes[$attrName] = $attrNode->value;
			}
			if($attributes['deleteId'] && $attributes['deleteId'] == $this->getUnique().'_'.$this->getID()) {
				$delItems[] = $item;
			}
		}
		foreach($delItems as $item) {
			$this->deleteChildren($item);
			$item->parentNode->removeChild($item);
		}
        $system->save('../modules/system.xml');
    }
    
    public function isSuccess() {
		return $this->success;
    }
	
	
	public function insertDomain($string) {
		global $db;
		$domains=explode('*/*', $string);							 
		if($this->component == true) {
			foreach($domains as $domain) {
				$db->query("INSERT INTO cms_domains_ids (elementID,domainID,type) VALUES ('".$this->componentInsertID."','".$domain."','com')");			
			 }
        } else {
			foreach($domains as $domain) {
				$db->query("INSERT INTO cms_domains_ids (elementID,domainID,type) VALUES ('".$this->moduleInsertID."','".$domain."','mod')");			
			}
        }	
	}
}

$module = new Module();
?>