<?php 
	require_once('../initialize.php');
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
?>
<form action="" name="d_article_cat" id="d_article_cat_id" method="post" class="form2">
<input type="hidden" id="edit_id_cat" value="<?=$db->filter('cat')?>" />
<input type="hidden" id="edit_id" value="<?=$db->filter('id')?>" />
<table cellpadding="0" cellspacing="4" border="0" width="99%" >
<?
	$stevilo=0;
	$query=$db->query('SELECT name, ID FROM cms_language_front');
	while($results=$db->fetch($query)) {
		if($stevilo==0)
			echo "<tr>";   
			 
    	echo '<td width="200px" class="right_td" style="vertical-align:top;"><div style="margin-bottom:10px; font-weight:bold;">'.$results['name'].'</div>';
		
		$query1 = $db->query("SELECT * FROM cms_article_categories WHERE status='N' AND lang='".$results['ID']."'");
		while($result1=$db->fetch($query1)) {
			echo '<div style="cursor:pointer; clear:both;"><input type="checkbox" style="float:left;" value="'.$result1['ID'].'" /><div style="float:left; margin-top:5px;">'.$result1['title'].'</div></div>';
			
		}
		echo "</td>";
		$stevilo++;
		if($stevilo==2) {
			$stevilo=0;
			echo "</tr>";	
		}
	}
	if($stevilo!=2)
		echo "</tr>";
?>
</table>
</form>