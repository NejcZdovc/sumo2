<form action="" name="a_settings_sumo" method="post" class="form2">
	<div class="">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->SETTINGS_1?>:</div><div class="title_form_small"><?php echo $lang->SETTINGS_2?></div>
        </td>
        <td class="right_td">
        <select id="items" class="input" style="margin-left:5%; margin-right:5%; width:90%;">
        	<option value="10" <?php if($user->items == 10) echo 'selected="selected"'; ?>>10</option>
            <option value="20" <?php if($user->items == 20) echo 'selected="selected"'; ?>>20</option>
            <option value="50" <?php if($user->items == 50) echo 'selected="selected"'; ?>>50</option>
            <option value="100" <?php if($user->items == 100) echo 'selected="selected"'; ?>>100</option>
            <option value="500" <?php if($user->items == 500) echo 'selected="selected"'; ?>>500</option>
            <option value="1000" <?php if($user->items == 1000) echo 'selected="selected"'; ?>>1000</option>
        </select>
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->SETTINGS_3?>:</div><div class="title_form_small"><?php echo $lang->SETTINGS_4?></div>
        </td>
        <td class="right_td">
        <select id="language" class="input" style="margin-left:5%; margin-right:5%; width:90%;">
        	<?php 
        		$query2 = $db->query("SELECT ID,name FROM cms_language WHERE enabled=1");
        		while($result2 = $db->fetch($query2)) {
        			if($result2['ID'] == $user->lang) {
        				echo '<option value="'.$result2['ID'].'" selected="selected">'.$result2['name'].'</option>';
        			} else {
        				echo '<option value="'.$result2['ID'].'">'.$result2['name'].'</option>';
        			}
        		}
        	?>
        </select>
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->SETTINGS_5?>:</div><div class="title_form_small"><?php echo $lang->SETTINGS_6?></div>
        </td>
        <td class="right_td">
        <select class="input" id="accordion" style="margin-left:5%; margin-right:5%; width:90%;">
        	<option value="5" <?php if($user->accordion == 5) echo 'selected="selected"'; ?>>5</option>
            <option value="6" <?php if($user->accordion == 6) echo 'selected="selected"'; ?>>6</option>
            <option value="7" <?php if($user->accordion == 7) echo 'selected="selected"'; ?>>7</option>
            <option value="8" <?php if($user->accordion == 8) echo 'selected="selected"'; ?>>8</option>
            <option value="9" <?php if($user->accordion == 9) echo 'selected="selected"'; ?>>9</option>
            <option value="10" <?php if($user->accordion == 10) echo 'selected="selected"'; ?>>10</option>
        </select>
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
        <td class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->SETTINGS_7?>:</div><div class="title_form_small"><?php echo $lang->SETTINGS_8?></div>
        </td>
        <td class="right_td">
        <select class="input" id="preview" style="margin-left:5%; margin-right:5%; width:90%;">
        	<option value="1" <?php if($user->preview == 1) echo 'selected="selected"'; ?>><?php echo $lang->SETTINGS_9?></option>
            <option value="2" <?php if($user->preview == 2) echo 'selected="selected"'; ?>><?php echo $lang->SETTINGS_10?></option>
            <option value="3" <?php if($user->preview == 3) echo 'selected="selected"'; ?>><?php echo $lang->SETTINGS_11?></option>
            <option value="4" <?php if($user->preview == 4) echo 'selected="selected"'; ?>><?php echo $lang->SETTINGS_12?></option>
        </select>
        </td>
    </tr>
    <tr style="margin-bottom:20px;">
			<td class="left_td" valign="top">
			<div class="title_form_big">View:</div><div class="title_form_small">Select default view for site tree in CMS.</div>
			</td>
			<td class="right_td">
			<select class="input" id="view" style="margin-left:5%; margin-right:5%; width:90%;">
				<option value="L" <?php if($user->view == "L") echo 'selected="selected"'; ?>>Layout</option>
				<option value="T" <?php if($user->view == "T") echo 'selected="selected"'; ?>>Template</option>
			</select>
			</td>
		</tr>
    <?php 
		$query2 = $db->query("SELECT ID,name FROM cms_language_front WHERE enabled=1");
		$int = $db->rows($query2);
		if($int<2)
			$trans_int='disabled="disabled"';
		else
			$trans_int="";
		?>
		 <tr style="margin-bottom:20px;">
			<td class="left_td" valign="top">
			<div class="title_form_big"><?php echo $lang->SETTINGS_13?>:</div><div class="title_form_small"><?php echo $lang->SETTINGS_14?></div>
			</td>
			<td class="right_td">
			<select class="input" <?php echo $trans_int?> id="translate_state" style="margin-left:5%; margin-right:5%; width:90%;">
				<option value="ON" <?php if($user->translate_state == "ON") echo 'selected="selected"'; ?>><?php echo $lang->SETTINGS_15?></option>
				<option value="OFF" <?php if($user->translate_state == "OFF") echo 'selected="selected"'; ?>><?php echo $lang->SETTINGS_16?></option>
			</select>
			</td>
		</tr>
        <?php if($int>1) {?>
		<tr style="margin-bottom:20px;">
			<td class="left_td" valign="top">
			<div class="title_form_big"><?php echo $lang->SETTINGS_17?>:</div><div class="title_form_small"><?php echo $lang->SETTINGS_18?></div>
			</td>
			<td class="right_td">
			<select class="input" id="translate_lang" style="margin-left:5%; margin-right:5%; width:90%;">
				<?php 
					
					while($result2 = $db->fetch($query2)) {
						if($result2['ID'] == $user->translate_lang) {
							echo '<option value="'.$result2['ID'].'" selected="selected">'.$result2['name'].'</option>';
						} else {
							echo '<option value="'.$result2['ID'].'">'.$result2['name'].'</option>';
						}
					}
				?>
			</select>
			</td>
		</tr>
		<?php } ?>
        <?php if($user->getAuth('FAV_SITE_3')==5) {?>
            <tr style="margin-bottom:20px;">
                <td class="left_td" valign="top">
                <div class="title_form_big"><?php echo $lang->MOD_176?>:</div><div class="title_form_small"><?php echo $lang->MOD_177?></div>
                </td>
                <td class="right_td">
                    <select class="input" id="update" style="margin-left:5%; margin-right:5%; width:90%;">
                        <option value="ON" <?php if($user->updateOption == "ON") echo 'selected="selected"'; ?>><?php echo $lang->SETTINGS_15?></option>
                        <option value="OFF" <?php if($user->updateOption == "OFF") echo 'selected="selected"'; ?>><?php echo $lang->SETTINGS_16?></option>
                    </select>
                </td>
            </tr>
            <tr style="margin-bottom:20px;">
                <td class="left_td" valign="top">
                <div class="title_form_big"><?php echo $lang->MOD_194?>:</div><div class="title_form_small"><?php echo $lang->MOD_195?></div>
                </td>
                <td class="right_td">
                    <select class="input" id="beta" style="margin-left:5%; margin-right:5%; width:90%;">
                        <option value="1" <?php if($user->beta == "1") echo 'selected="selected"'; ?>><?php echo $lang->ARTICLE_19?></option>
                        <option value="0" <?php if($user->beta == "0") echo 'selected="selected"'; ?>><?php echo $lang->ARTICLE_20?></option>
                    </select>
                </td>
            </tr>
            <tr style="margin-bottom:20px;">
                <td class="left_td" valign="top">
                	<div class="title_form_big"><?php echo $lang->MOD_190?>:</div><div class="title_form_small"><?php echo $lang->MOD_191?></div>
                </td>
                <td class="right_td">
                	<div style="cursor:pointer; font-weight:bold; font-size:15px; margin-left:40%;" onclick="sumo2.update.Init(true);"><?php echo $lang->MOD_190?></div>
                </td>
           </tr>
           <tr style="margin-bottom:20px;">
                <td class="left_td" valign="top">
                <div class="title_form_big"><?php echo $lang->MOD_207?>:</div><div class="title_form_small"><?php echo $lang->MOD_208?></div>
                </td>
                <td class="right_td">
                    <select class="input" id="developer" style="margin-left:5%; margin-right:5%; width:90%;">
                        <option value="1" <?php if($user->developer == "1") echo 'selected="selected"'; ?>><?php echo $lang->ARTICLE_19?></option>
                        <option value="0" <?php if($user->developer == "0") echo 'selected="selected"'; ?>><?php echo $lang->ARTICLE_20?></option>
                    </select>
                </td>
            </tr>
            <tr style="margin-bottom:20px;">
                <td class="left_td" valign="top">
                <div class="title_form_big"><?php echo $lang->MOD_242?>:</div><div class="title_form_small"><?php echo $lang->MOD_243?></div>
                </td>
                <td class="right_td">
                    <input value="<?php echo  PER_FILE?>" disabled="disabled" class="input" style="margin-left:5%; margin-right:5%; width:90%;" />
                </td>
            </tr>
            <tr style="margin-bottom:20px;">
                <td class="left_td" valign="top">
                <div class="title_form_big"><?php echo $lang->MOD_244?>:</div><div class="title_form_small"><?php echo $lang->MOD_245?></div>
                </td>
                <td class="right_td">
                   <input value="<?php echo  PER_FOLDER?>" disabled="disabled" class="input" style="margin-left:5%; margin-right:5%; width:90%;" />
                </td>
            </tr>
        <?php } else { ?>
			<input value="OFF" id="update" type="hidden" />	
            <input value="0" id="beta" type="hidden" />	
            <input value="0" id="developer" type="hidden" />			
		<?php }?>
    </table>
    </div>
</form>
