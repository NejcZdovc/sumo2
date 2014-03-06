<?php 
require_once('../initialize.php'); 
if(!$session->isLogedIn() && !$security->checkURL()) {
	exit;
}

$id = $user->id;
$result = $db->get($db->query("SELECT option1, option2, option3, option4, option5, option6, option7, option8, option9, option10 FROM cms_favorites WHERE UserID='".$id."' LIMIT 1"));
?>
<form name="favoritesEdit">
<table id="favoritesTable" cellspacing="0" summary="Favorites selection" class="table1 fav-table">
<caption class="hide"><?php echo $lang->FAV_EDIT_3?></caption>
<tr>
  <th scope="col" abbr="Icon"><?php echo $lang->FAV_EDIT_1?></th>
  <th scope="col" abbr="Title"><?php echo $lang->FAV_EDIT_2?></th>
  <th scope="col" abbr="Select"></th>
</tr>
<?php 
	$i=0;
	$query1 = $db->query("SELECT ID, title, subtitle, img FROM cms_favorites_def ORDER BY ID asc");
	while($result1 = $db->fetch($query1)) {
		if($user->isAuth($result1['subtitle'])) {?>
			<tr valign="top">
				<td class="fav-img">
					<?php echo getImageFav($result1['img'],$lang->$result1['subtitle'])?>
				</td>
				<td>
					<div class="fav-title"><?php echo $lang->$result1['title'];?></div>
					<div class="fav-subtitle"><?php echo $lang->$result1['subtitle'];?></div>
				</td>
				<?php 
					$checked = "";
					if($result) {			
						if($result['option1'] == $result1['ID']) {
							$checked = 'checked="checked"';
						}
						else if($result['option2'] == $result1['ID']) {
							$checked = 'checked="checked"';
						}
						else if($result['option3'] == $result1['ID']) {
							$checked = 'checked="checked"';
						}
						else if($result['option4'] == $result1['ID']) {
							$checked = 'checked="checked"';
						}
						else if($result['option5'] == $result1['ID']) {
							$checked = 'checked="checked"';
						}
						else if($result['option6'] == $result1['ID']) {
							$checked = 'checked="checked"';
						}
						else if($result['option7'] == $result1['ID']) {
							$checked = 'checked="checked"';
						}
						else if($result['option8'] == $result1['ID']) {
							$checked = 'checked="checked"';
						}
						else if($result['option9'] == $result1['ID']) {
							$checked = 'checked="checked"';
						}
						else if($result['option10'] == $result1['ID']) {
							$checked = 'checked="checked"';
						}
					}
				?>
				<td class="fav-sel">
					<input type="checkbox" name="favorites" value="<?php echo $result1['ID']?>" onclick="sumo2.favorites.CheckSelection(this)" <?php echo $checked;?> />
				</td>
			</tr>
		<?php }
	}
?>
</table>
</form>
