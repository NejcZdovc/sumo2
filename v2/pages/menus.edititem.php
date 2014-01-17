<?php require_once('../initialize.php'); 
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$id=$db->filter('id');
	$id=explode("#", $id);
	$results=$db->fetch($db->query('SELECT title, keyword, restriction, description, template, target, selection, link, alias, parentID, altPrefix FROM cms_menus_items WHERE ID='.$id[0].''));
?>
<form action="" name="d_menus_edit_i" method="post" class="form2">
<input type="hidden" name="id" id="id" value="<?php echo  $id[0] ?>" />
	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->MENU_20?>:</div><div class="title_form_small"><?php echo $lang->MENU_21?></div>
        </td>
        <td class="right_td">
        <input type="text" name="name" id="name" class="input" value="<?php echo  $results['title'] ?>" />
        <input type="text" name="enterfix" style="display:none;" />
        </td>
    </tr>
     <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->MENU_22?>:</div><div class="title_form_small"><?php echo $lang->MENU_23?></div>
        </td>
        <td class="right_td">
        <select id="template" style="width:100%;">
        <?php $query=$db->query('SELECT ID, name FROM cms_template WHERE enabled=1 AND status="N" AND domain="'.$user->domain.'"');
        	while($results1=$db->fetch($query)) {
			if($results1['ID']==$results['template'])
				echo '<option value="'.$results1['ID'].'" selected="Selected" style="font-weight:bolder;">'.$results1['name'].'</option>';
			else
				echo '<option value="'.$results1['ID'].'">'.$results1['name'].'</option>';
				
			}        
		?>
        </select>
        </td>
    </tr>
    <tr><td height="10px" width="100%" colspan="2"></td></tr>
    <tr>
        <td colspan="2" class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->MENU_24?>:</div><div class="title_form_small"><?php echo $lang->MENU_25?></div>
        </td>
    </tr>
    <tr>
    	<td colspan="2" class="right_td" style="padding:5px;">
			<textarea  id="keywords" class="input-area" name="content" rows="5" cols="54"><?php echo  $results['keyword'] ?></textarea>
        </td>
    
    </tr>
    <tr>
        <td colspan="2" class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->MENU_26?>:</div><div class="title_form_small"><?php echo $lang->MENU_27?></div>
        </td>
    </tr>
    <tr>
    	<td colspan="2" class="right_td" style="padding:5px;">
			<textarea  id="description" class="input-area" name="content"  rows="5" cols="54"><?php echo  $results['description'] ?></textarea>
        </td>
    
    </tr>
   
    <tr>
        <td class="left_td" valign="top">
            <div class="title_form_big"><?php echo $lang->MOD_174?>:</div><div class="title_form_small"><?php echo $lang->MOD_175?></div>
        </td>
        <td class="right_td">
            <input type="radio" <?php if($results['restriction']==1) echo 'checked="checked"'; ?> id="public" name="restriction" value="1" /><label for="public"><?php echo $lang->MOD_172?></label><br/>
            <input type="radio" <?php if($results['restriction']==2) echo 'checked="checked"'; ?> id="registred" name="restriction" value="2" /><label for="registred"><?php echo $lang->MOD_173?></label>
        </td>
    </tr>
   <?php if($db->filter('mode')!='sp') { ?>
    <tr>
        <td class="left_td" valign="top">
        	<div class="title_form_big"><?php echo $lang->MOD_201?>:</div><div class="title_form_small"><?php echo $lang->MOD_202?></div>
        </td>
        <td class="right_td">
        	<input type="text" name="altPrefix" id="altPrefix" class="input" value="<?php echo  $results['altPrefix'] ?>" />
        </td>
    </tr>
 	<tr>
        <td class="left_td" valign="top">
            <div class="title_form_big"><?php echo $lang->MOD_11?>:</div><div class="title_form_small"><?php echo $lang->MOD_12?></div>
        </td>
        <td class="right_td">
            <input type="radio" <?php if($results['selection']==1) echo 'checked="checked"'; ?> onchange="sumo2.menu.View(this.id);" id="new" name="typ" value="1" /><label for="new"><?php echo $lang->MOD_13?></label><br/>
            <input type="radio" <?php if($results['selection']==2) echo 'checked="checked"'; ?> onchange="sumo2.menu.View(this.id);" id="short" name="typ" value="2" /><label for="short"><?php echo $lang->MOD_14?></label><br/>
            <input type="radio" <?php if($results['selection']==3) echo 'checked="checked"'; ?> onchange="sumo2.menu.View(this.id);" id="link" name="typ" value="3" /><label for="link"><?php echo $lang->MOD_15?></label>
        </td>
    </tr>
    <?php } else { ?>
    <tr>
        <td>
        	<input type="radio" checked="checked" id="new" disabled="disabled" name="typ" value="4" style="display:none;" />
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
        	<div class="title_form_big"><?php echo $lang->MOD_201?>:</div><div class="title_form_small"><?php echo $lang->MOD_202?></div>
        </td>
        <td class="right_td">
        	<input type="text" name="altPrefix" id="altPrefix" class="input" value="<?php echo  $results['altPrefix'] ?>" />
        </td>
    </tr>
     <tr>
        <td class="left_td" valign="top" colspan="2">
        	<div class="title_form_big"><?php echo $lang->MOD_183?>:</div><div class="title_form_small"><?php echo $lang->MOD_184?></div>
        </td>
    </tr>
    <tr>
        <td class="right_td" colspan="2">
			<select id="parent" size="3" style="width:450px; height:180px;">
                <?php
				if($results['parentID']=='-1')
					echo '<option value="-1" selected="selected"><b>'.$lang->ARTICLE_20.'</b></option>';
				else
					echo '<option value="-1"><b>'.$lang->ARTICLE_20.'</b></option>';
				
				 $query=$db->query('SELECT ID, name FROM cms_language_front WHERE enabled=1');
					while($resultsF=$db->fetch($query)) {
					echo '<optgroup label="'.$resultsF['name'].'">';
					$home_name=$db->fetch($db->query('SELECT ID,title FROM cms_homepage WHERE lang="'.$resultsF['ID'].'" AND domain="'.$user->domain.'"'));
					$home_id=$db->fetch($db->query('SELECT ID FROM cms_menus_items WHERE parentID="-1" AND orderID="-1" AND link="'.$home_name['ID'].'"'));
					if($results['parentID']==$home_id['ID'])
						echo '<option value="'.$home_id['ID'].'" selected="selected">&bull;&nbsp;'.$home_name['title'].'</option>';
					else
						echo '<option value="'.$home_id['ID'].'">&bull;&nbsp;'.$home_name['title'].'</option>';
					$query1=$db->query('SELECT ID, title FROM cms_menus WHERE lang="'.$resultsF['ID'].'" AND enabled=1 AND domain="'.$user->domain.'"');
					while($results1=$db->fetch($query1)) {
						echo '<optgroup style="padding-left:15px; font-weight:bold !important; font-style:normal !important; text-decoration:none !important;" label="&nbsp;'.$results1['title'].'">';
						$query2=$db->query('SELECT ID, title FROM cms_menus_items WHERE menuID="'.$results1['ID'].'" AND parentID="-1"  AND selection!="4" AND enabled="1" AND status="N"');
						while($results2=$db->fetch($query2)) {
							if($results['parentID']==$results2['ID'])
								echo' <option value="'.$results2['ID'].'" selected="selected">&nbsp;&nbsp;'.$results2['title'].'</option>';
							else
								echo' <option value="'.$results2['ID'].'">&nbsp;&nbsp;'.$results2['title'].'</option>';
							$query3=$db->query('SELECT ID, title FROM cms_menus_items WHERE menuID="'.$results1['ID'].'" AND parentID="'.$results2['ID'].'" AND enabled=1 AND status="N"');
							while($results3=$db->fetch($query3)) {
								if($results['parentID']==$results3['ID'])
									echo' <option value="'.$results3['ID'].'" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$results3['title'].'</option>';
								else
									echo' <option value="'.$results3['ID'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$results3['title'].'</option>';
								$query4=$db->query('SELECT ID, title FROM cms_menus_items WHERE menuID="'.$results1['ID'].'" AND parentID="'.$results3['ID'].'" AND enabled=1 AND status="N"');
								while($results4=$db->fetch($query4)) {
									if($results['parentID']==$results4['ID'])
										echo' <option value="'.$results4['ID'].'" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$results4['title'].'</option>';
									else
										echo' <option value="'.$results4['ID'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$results4['title'].'</option>';
									$query5=$db->query('SELECT ID, title FROM cms_menus_items WHERE menuID="'.$results1['ID'].'" AND parentID="'.$results4['ID'].'" AND enabled=1 AND status="N"');
									while($results5=$db->fetch($query5)) {
										if($results['parentID']==$results5['ID'])
											echo' <option value="'.$results5['ID'].'" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$results5['title'].'</option>';
										else
											echo' <option value="'.$results5['ID'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$results5['title'].'</option>';
									}
								}
							}
						}
						echo '</optgroup>';
					}
                	echo '</optgroup>';						
					}
				?>         
                </select>
        </td>
    </tr>
    <?php } ?>
    </table>
    <div id="div_new" style="display:none;"></div>
    <div id="div_short" <?php if($results['selection']==2) echo 'style="display:block;"'; else echo 'style="display:none;"'; ?>>
    	<table cellpadding="0" cellspacing="4" border="0" width="99%" >
        <tr>
            <td class="left_td" valign="top" colspan="2">
                <div class="title_form_big"><?php echo $lang->MOD_16?>:</div><div class="title_form_small"><?php echo $lang->MOD_17?></div>
            </td>        
        </tr>
        <tr>
            <td class="right_td" valign="top" colspan="2" style="padding:5px;">
            	<select id="shortcut_link" size="3" style="width:450px; height:180px;">
                <?php $query=$db->query('SELECT ID, name FROM cms_language_front WHERE enabled=1');
					while($resultsF=$db->fetch($query)) {
					echo '<optgroup label="'.$resultsF['name'].'">';
					$home_name=$db->fetch($db->query('SELECT ID,title FROM cms_homepage WHERE lang="'.$resultsF['ID'].'" AND domain="'.$user->domain.'"'));
					$home_id=$db->fetch($db->query('SELECT ID FROM cms_menus_items WHERE parentID="-1" AND orderID="-1" AND link="'.$home_name['ID'].'"'));
					if($results['link']==$home_id['ID'])
						echo '<option value="'.$home_id['ID'].'" selected="selected">&bull;&nbsp;'.$home_name['title'].'</option>';
					else
						echo '<option value="'.$home_id['ID'].'">&bull;&nbsp;'.$home_name['title'].'</option>';
					$query1=$db->query('SELECT ID, title FROM cms_menus WHERE lang="'.$resultsF['ID'].'" AND enabled=1 AND domain="'.$user->domain.'"');
					while($results1=$db->fetch($query1)) {
						echo '<optgroup style="padding-left:15px; font-weight:bold !important; font-style:normal !important; text-decoration:none !important;" label="&nbsp;'.$results1['title'].'">';
						$query2=$db->query('SELECT ID, title FROM cms_menus_items WHERE menuID="'.$results1['ID'].'" AND parentID="-1" AND selection!="4" AND enabled="1" AND status="N"');
						while($results2=$db->fetch($query2)) {
							if($results['link']==$results2['ID'])
								echo' <option value="'.$results2['ID'].'" selected="selected">&nbsp;&nbsp;'.$results2['title'].'</option>';
							else
								echo' <option value="'.$results2['ID'].'">&nbsp;&nbsp;'.$results2['title'].'</option>';
							$query3=$db->query('SELECT ID, title FROM cms_menus_items WHERE menuID="'.$results1['ID'].'" AND parentID="'.$results2['ID'].'" AND enabled=1 AND status="N"');
							while($results3=$db->fetch($query3)) {
								if($results['link']==$results3['ID'])
									echo' <option value="'.$results3['ID'].'" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$results3['title'].'</option>';
								else
									echo' <option value="'.$results3['ID'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$results3['title'].'</option>';
								$query4=$db->query('SELECT ID, title FROM cms_menus_items WHERE menuID="'.$results1['ID'].'" AND parentID="'.$results3['ID'].'" AND enabled=1 AND status="N"');
								while($results4=$db->fetch($query4)) {
									if($results['link']==$results4['ID'])
										echo' <option value="'.$results4['ID'].'" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$results4['title'].'</option>';
									else
										echo' <option value="'.$results4['ID'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$results4['title'].'</option>';
									$query5=$db->query('SELECT ID, title FROM cms_menus_items WHERE menuID="'.$results1['ID'].'" AND parentID="'.$results4['ID'].'" AND enabled=1 AND status="N"');
									while($results5=$db->fetch($query5)) {
										if($results['link']==$results5['ID'])
											echo' <option value="'.$results5['ID'].'" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$results5['title'].'</option>';
										else
											echo' <option value="'.$results5['ID'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$results5['title'].'</option>';
									}
								}
							}
						}
						echo '</optgroup>';
					}
                	echo '</optgroup>';						
					}
				?>              
                </select>
            </td>        
        </tr>
        </table>
    </div>
    <div id="div_link" <?php if($results['selection']==3) echo 'style="display:block;"'; else echo 'style="display:none;"'; ?>>
    	<table cellpadding="0" cellspacing="4" border="0" width="99%" >
        <tr>
            <td class="left_td" valign="top">
                <div class="title_form_big"><?php echo $lang->MOD_18?>:</div><div class="title_form_small"><?php echo $lang->MOD_19?></div>
            </td>
            <td class="right_td">
                <input name="extra_link" id="extra_link" type="text" maxlength="50" value="<?php echo $results['link']?>" class="input" />
            </td>        
        </tr>
        <tr>
            <td class="left_td" valign="top">
                <div class="title_form_big"><?php echo $lang->MOD_20?>:</div><div class="title_form_small"><?php echo $lang->MOD_21?></div>
            </td>
            <td class="right_td">
              	<input type="radio" <?php if($results['target']==1) echo 'checked="checked"'; ?> id="same" name="target" value="1" /><label for="same"><?php echo $lang->MOD_22?></label><br/>
                <input type="radio" <?php if($results['target']==2) echo 'checked="checked"'; ?> id="blank" name="target" value="2" /><label for="blank"><?php echo $lang->MOD_23?></label><br/>
           		<input type="radio" <?php if($results['target']==3) echo 'checked="checked"'; ?> id="new_w" name="target" value="3" /><label for="new_w"><?php echo $lang->MOD_24?></label>  
            </td>        
        </tr>
        </table>
    </div>
    </div>
</form>