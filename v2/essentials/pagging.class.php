<?php
if( !defined( '_JEXEC' ) && !defined( '_VALID_MOS' ) ) {
	header( 'HTTP/1.0 404 Not Found');
	header( 'Location: http://www.3zsistemi.si');
	exit;
}
class Pagging
{
    public $query="";
    public $pageSize=0;
    public $pageID=0;
    public $allRows=0;
    private $accordionID="";
    
    function __construct($accordionID) {
		global $db;		
        $this->accordionID=$accordionID;
        if($db->is('pag_id')) {
            $this->pageID=intval($db->filter('pag_id'));
        }
    }
    
    function numbers() {        
        $content='<div style="margin-top:10px;margin-left:10px;">';
        $int=ceil($this->allRows/$this->pageSize);
        if($int>1) {
            if($this->pageID>0) {
                if($int>3 && $this->pageID!=1 && ($this->pageID-3)>0)
                    $content.='<div class="pagging-button" onclick="sumo2.accordion.ReloadAccordion(\''.$this->accordionID.'\',\'pag_id=0&size='.$this->pageSize.'\')">&lt;&lt;</div>';
                for($i=3; $i>0; $i--) {
                    if(($this->pageID-$i+1)>0) 
                        $content.='<div class="pagging-button" onclick="sumo2.accordion.ReloadAccordion(\''.$this->accordionID.'\',\'pag_id='.($this->pageID-$i).'&size='.$this->pageSize.'\')">'.($this->pageID-$i+1).'</div>';
                }
            }
            else $this->pageID=0;
                    
            $content.='<div class="pagging-button sel-button" onclick="sumo2.accordion.ReloadAccordion(\''.$this->accordionID.'\',\'pag_id='.$this->pageID.'&size='.$this->pageSize.'\')"><b>'.($this->pageID+1).'</b></div>';
            for($i=1; $i<4; $i++) {
                if(($this->pageID+$i)<$int)
                    $content.= '<div class="pagging-button" onclick="sumo2.accordion.ReloadAccordion(\''.$this->accordionID.'\',\'pag_id='.($this->pageID+$i).'&size='.$this->pageSize.'\')">'.($this->pageID+$i+1)."</div>";
            }
            if($int>3 && $this->pageID!=$int)
                $content.='<div class="pagging-button" onclick="sumo2.accordion.ReloadAccordion(\''.$this->accordionID.'\',\'pag_id='.$int.'&size='.$this->pageSize.'\')">&gt;&gt;</div>';
        }
        $content.='</div>';
        return $content;
    }
    
    function init($sql) {
        global $db, $user;
		$this->query=$sql;
        if($db->is('size')) {
            $this->pageSize=intval($db->filter('size'));
        }
        else {
            $this->pageSize=intval($user->items);
        }
        $current=$this->pageID*$this->pageSize;
        $next=4*$this->pageSize;
		if (strpos($sql, '{limit}') !== false) {
			$rowsQuery=str_replace("{limit}", "LIMIT ".$current.", ".$next."", $sql);
		} else {
			$rowsQuery=$sql." LIMIT ".$current.", ".$next;
		}
    
        $rows=$db->rows($db->query($rowsQuery));
        $this->allRows=$current + $rows;
        if($this->allRows>$this->pageSize) {
			$limit=" LIMIT ".($this->pageID*$this->pageSize).", ".$this->pageSize."";
			if (strpos($sql, '{limit}') !== false) {
				$this->query=str_replace("{limit}", $limit, $sql);
			} else {
				$this->query.=$limit;
			}
        } else {
			$this->query=str_replace("{limit}", "", $sql);
		}
    }
    
    function dropDown() {        
        $array = array(10, 20, 50, 100, 500, 1000);
        $result='Display #<select name="displaySelection" class="input" onchange="sumo2.accordion.ReloadAccordion(\''.$this->accordionID.'\',\'pag_id=0$!$size=\'+this.value+\'\')">';
        foreach ($array as $i => $value) {
            $selected='';
            if($this->pageSize===666 && $array[$i]==1000) {
                $selected='selected="selected"';
            } else if($this->pageSize==$array[$i]) {
                $selected='selected="selected"';
            }
            $result.='<option value="'.$array[$i].'" '.$selected.'>'.$array[$i].'</option>';
        }	
        $result.='</select>';
        
        return $result;
    }
}