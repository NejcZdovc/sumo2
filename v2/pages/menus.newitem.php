<?php require_once('../initialize.php'); 
	if(!$session->isLogedIn() || !$security->checkURL()) {
		exit;
	}
?>
<form action="" name="d_menus_new_i" method="post" class="form2">
<?php if($db->filter('mode')!='sp') { ?>
<input type="hidden" name="id" id="id" value="<?php echo  $db->filter('id') ?>" />
<?php } else {?>
	<input type="hidden" name="id" id="id" value="<?php echo  $crypt->decrypt($db->filter('id')) ?>" />
<?php } ?>
	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->MENU_20?>:</div><div class="title_form_small"><?php echo $lang->MENU_21?></div>
        </td>
        <td class="right_td">
        <input type="text" name="name" id="name" class="input" />
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
        	while($results=$db->fetch($query)) {
			if($results['ID']==$globals->template)
				echo '<option value="'.$results['ID'].'" selected="Selected" style="font-weight:bolder;">'.$results['name'].'</option>';
			else
				echo '<option value="'.$results['ID'].'">'.$results['name'].'</option>';
				
			}        
		?>
        </select>
        </td>
    </tr>
	<tr>
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->MOD_229?>:</div><div class="title_form_small"><?php echo $lang->MOD_247?></div>
        </td>
        <td class="right_td">
        <select id="moduleID" style="width:100%;">
			<option value="-1"><?php echo $lang->MOD_248 ?></option>
			<?php $query=$db->query('SELECT md.ID, md.name FROM cms_modules_def as md LEFT JOIN cms_domains_ids as di ON md.ID=di.elementID WHERE md.enabled=1 AND md.status="N" AND di.domainID="'.$user->domain.'" AND di.type="mod"');
        	while($results1=$db->fetch($query)) {
				echo '<option value="'.$results1['ID'].'">'.$results1['name'].'</option>';
			} ?>
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
			<textarea  id="keywords" class="input-area" name="content" rows="5" cols="54"></textarea>
        </td>
    
    </tr>
    <tr>
        <td colspan="2" class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->MENU_26?>:</div><div class="title_form_small"><?php echo $lang->MENU_27?></div>
        </td>
    </tr>
    <tr>
    	<td colspan="2" class="right_td" style="padding:5px;">
			<textarea  id="description" class="input-area" name="content" rows="5" cols="54"></textarea>
        </td>
    
    </tr>
    <tr>
        <td class="left_td" valign="top">
            <div class="title_form_big"><?php echo $lang->MOD_174?>:</div><div class="title_form_small"><?php echo $lang->MOD_175?></div>
        </td>
        <td class="right_td">
            <input type="radio" id="public" name="restriction" checked="checked" value="1" /><label for="public"><?php echo $lang->MOD_172?></label><br/>
            <input type="radio" id="registred" name="restriction" value="2" /><label for="registred"><?php echo $lang->MOD_173?></label>
        </td>
    </tr>
    <?php if($db->filter('mode')!='sp') { ?>
        <tr>
            <td class="left_td" valign="top">
                <div class="title_form_big"><?php echo $lang->MOD_11?>:</div><div class="title_form_small"><?php echo $lang->MOD_12?></div>
            </td>
            <td class="right_td">
                <input type="radio" onchange="sumo2.menu.View(this.id);" checked="checked" id="new" name="typ" value="1" /><label for="new"><?php echo $lang->MOD_13?></label><br/>
                <input type="radio" onchange="sumo2.menu.View(this.id);" id="short" name="typ" value="2" /><label for="short"><?php echo $lang->MOD_14?></label><br/>
                <input type="radio" onchange="sumo2.menu.View(this.id);" id="link" name="typ" value="3" /><label for="link"><?php echo $lang->MOD_15?></label>
            </td>
        </tr>
     <?php } else { ?>
    <tr>
        <td>
        	<input type="radio" checked="checked" disabled="disabled" id="new" name="typ" value="4" style="display:none;" />
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
        	<div class="title_form_big"><?php echo $lang->MOD_181?>:</div><div class="title_form_small"><?php echo $lang->MOD_182?></div>
        </td>
        <td class="right_td">
        	<input type="text" name="linkA" id="linkA" class="input" value="<?php echo  $results['alias'] ?>" />
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
				 echo '<option value="-1" selected="selected"><b>'.$lang->ARTICLE_20.'</b></option>';
				 $query=$db->query('SELECT ID, name FROM cms_language_front WHERE enabled=1');
					while($results=$db->fetch($query)) {
					echo '<optgroup label="'.$results['name'].'">';
						$query1=$db->query('SELECT ID, title FROM cms_menus WHERE lang="'.$results['ID'].'" AND enabled=1 AND domain="'.$user->domain.'"');
						while($results1=$db->fetch($query1)) {
							echo '<optgroup style="padding-left:15px; font-weight:bold !important; font-style:normal !important; text-decoration:none !important;" label="&nbsp;'.$results1['title'].'">';
							$query2=$db->query('SELECT ID, title FROM cms_menus_items WHERE menuID="'.$results1['ID'].'" AND parentID=-1 AND enabled=1 AND status="N"');
							while($results2=$db->fetch($query2)) {
								echo' <option value="'.$results2['ID'].'">&nbsp;&nbsp;'.$results2['title'].'</option>';
								$query3=$db->query('SELECT ID, title FROM cms_menus_items WHERE menuID="'.$results1['ID'].'" AND parentID="'.$results2['ID'].'" AND enabled=1 AND status="N"');
								while($results3=$db->fetch($query3)) {
									echo' <option value="'.$results3['ID'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$results3['title'].'</option>';
									$query4=$db->query('SELECT ID, title FROM cms_menus_items WHERE menuID="'.$results1['ID'].'" AND parentID="'.$results3['ID'].'" AND enabled=1 AND status="N"');
									while($results4=$db->fetch($query4)) {
										echo' <option value="'.$results4['ID'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$results4['title'].'</option>';
										$query5=$db->query('SELECT ID, title FROM cms_menus_items WHERE menuID="'.$results1['ID'].'" AND parentID="'.$results4['ID'].'" AND enabled=1 AND status="N"');
										while($results5=$db->fetch($query5)) {
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
    <div id="div_short" style="display:none;">
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
					while($results=$db->fetch($query)) {
					echo '<optgroup label="'.$results['name'].'">';
						$query1=$db->query('SELECT ID, title FROM cms_menus WHERE lang="'.$results['ID'].'" AND enabled=1 AND domain="'.$user->domain.'"');
						while($results1=$db->fetch($query1)) {
							echo '<optgroup style="padding-left:15px; font-weight:bold !important; font-style:normal !important; text-decoration:none !important;" label="&nbsp;'.$results1['title'].'">';
							$query2=$db->query('SELECT ID, title FROM cms_menus_items WHERE menuID="'.$results1['ID'].'" AND parentID=0 AND enabled=1 AND status="N"');
							while($results2=$db->fetch($query2)) {
								echo' <option value="'.$results2['ID'].'">&nbsp;&nbsp;'.$results2['title'].'</option>';
								$query3=$db->query('SELECT ID, title FROM cms_menus_items WHERE menuID="'.$results1['ID'].'" AND parentID="'.$results2['ID'].'" AND enabled=1 AND status="N"');
								while($results3=$db->fetch($query3)) {
									echo' <option value="'.$results3['ID'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$results3['title'].'</option>';
									$query4=$db->query('SELECT ID, title FROM cms_menus_items WHERE menuID="'.$results1['ID'].'" AND parentID="'.$results3['ID'].'" AND enabled=1 AND status="N"');
									while($results4=$db->fetch($query4)) {
										echo' <option value="'.$results4['ID'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$results4['title'].'</option>';
										$query5=$db->query('SELECT ID, title FROM cms_menus_items WHERE menuID="'.$results1['ID'].'" AND parentID="'.$results4['ID'].'" AND enabled=1 AND status="N"');
										while($results5=$db->fetch($query5)) {
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
    <div id="div_link" style="display:none; ">
    	<table cellpadding="0" cellspacing="4" border="0" width="99%" >
        <tr>
            <td class="left_td" valign="top">
                <div class="title_form_big"><?php echo $lang->MOD_18?>:</div><div class="title_form_small"><?php echo $lang->MOD_19?></div>
            </td>
            <td class="right_td">
                <input name="extra_link" id="extra_link" value="http://" type="text" maxlength="50" class="input" />
            </td>        
        </tr>
        <tr>
            <td class="left_td" valign="top">
                <div class="title_form_big"><?php echo $lang->MOD_20?>:</div><div class="title_form_small"><?php echo $lang->MOD_21?></div>
            </td>
            <td class="right_td">
              	<input type="radio" checked="checked" id="same" name="target" value="1" /><label for="same"><?php echo $lang->MOD_22?></label><br/>
                <input type="radio" id="blank" name="target" value="2" /><label for="blank"><?php echo $lang->MOD_23?></label><br/>
           		<input type="radio" id="new_w" name="target" value="3" /><label for="new_w"><?php echo $lang->MOD_24?></label>  
            </td>        
        </tr>
        </table>
    </div>
    </div>
</form>
