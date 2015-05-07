<?php 
	require_once('../initialize.php');
	$security->checkFull();
	$id = $crypt->decrypt($db->filter('id'));
	$result = $db->get($db->query("SELECT * FROM cms_user_groups WHERE ID='".$id."'"));
if($result) {
?>
	<form action="" name="a_user_edit_group" id="a_user_edit_group" method="post" class="form2">
		<div>
			<ul class="group_tab" >
				<li id="group1tab"><a href="#group1"><?=$lang->MOD_249?></a></li>
				<li id="group2tab"><a href="#group2"><?=$lang->MOD_250?></a></li>
			</ul>
		</div>
		<div style="clear:both;"></div>
		<div id="group_container" class="group_tab_container">
			<input id="group_current_tab" type="hidden" value="#group1" />
			<div id="group1" class="group_tab_content" style="overflow:auto;">
				<?php include("user.management.edit.group.basic.php") ?>
			</div>
			<div id="group2" class="group_tab_content" style="overflow:auto;">
				<?php include("user.management.edit.group.permissions.php") ?>
			</div>
		</div>
	</form>
<?php } ?>