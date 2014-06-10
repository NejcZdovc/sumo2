<?php 
	require_once('../initialize.php');
	$security->checkFull();
?>
<form action="" name="d_article_cat" id="d_article_cat_id" method="post" class="form2">
<input type="hidden" id="edit_id_cat" value="<?php echo $db->filter('cat')?>" />
<input type="hidden" id="edit_id" value="<?php echo $db->filter('id')?>" />
<table cellpadding="0" cellspacing="4" border="0" width="99%" >
<?php
	$stevilo=0;
	$query=$db->query('SELECT name, ID FROM cms_language_front');
	while($results=$db->fetch($query)) {
		if($stevilo==0)
			echo "<tr>";   
			 
    	echo '<td width="200px" class="right_td" style="vertical-align:top;"><div style="margin-bottom:10px; font-weight:bold;">'.$results['name'].'</div>';
		
		$query1 = $db->query("SELECT * FROM cms_article_categories WHERE status='N' AND lang='".$results['ID']."' AND domain='".$user->domain."'");
		while($result1=$db->fetch($query1)) {
			echo '<div style="cursor:pointer; clear:both;"><input id="cat_'.$result1['ID'].'" type="checkbox" style="float:left;" value="'.$result1['ID'].'" /><label for="cat_'.$result1['ID'].'"><div style="float:left; margin-top:5px;">'.$result1['title'].'</div></label></div>';
			
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