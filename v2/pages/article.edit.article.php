<? require_once('../initialize.php');
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 	exit;
	}
	$id=$crypt->decrypt($db->filter('id'));
	$results_basic=$db->fetch($db->query('SELECT title, galleryCat, content, stub, category, dateStart, dateEnd, author, authorAlias, published, image, keywords, description, date, altPrefix FROM cms_article WHERE ID="'.$id.'"'));
?>
<form action="" name="a_article_edit_a" method="post" class="form2">
	<input id="id_a" name="id_a" type="hidden" value="<?=$db->filter('id')?>" />
    <input id="id_ad" name="id_ad" type="hidden" value="<?=$id?>" />
    <input id="domainNameEdit" name="domainNameEdit" type="hidden" value="<?=$user->domainName?>" />
	 <?php
		if($db->is('accId')) {
			echo '<input type="hidden" name="accid" value="'.$db->filter('accId').'" />';	
		} else {
			echo '<input type="hidden" name="accid" value="" />';
		}
	?>
	<div class="table_panel">
    <table cellpadding="0" cellspacing="4" border="0" width="100%" >
    <tr>
        <td class="left_td td_width" width="25%" valign="top">
        <div class="title_form_big"><?=$lang->ARTICLE_NEW_T_1?>:</div><div class="title_form_small"><?=$lang->ARTICLE_NEW_T_2?></div>
        </td>
        <td class="right_td td_width" width="25%">
        <input name="title" id="title" value="<?=$results_basic['title']?>" type="text" class="input" />
        </td>
        <td class="left_td td_width" width="25%" valign="top">
        <div class="title_form_big"><?=$lang->ARTICLE_NEW_C_1?>:</div><div class="title_form_small"><?=$lang->MOD_46?></div>
        </td>
        <td class="right_td td_width" width="25%">
        <input id="category_edit_article" value="<?=$results_basic['category']?>" name="category" type="hidden" />
        <div id="cat_edit_new" title="<?=$lang->ARTICLE_15?>" onclick="" style="cursor:pointer; font-weight:bold; font-size:15px; margin-left:40%;"><?=$lang->MOD_45?></div>
        </td>
    </tr>
    <tr>
    	<td class="left_td" colspan="5">
        <div style="float:left; margin-right:5px;"><span class="title_form_big"><?=$lang->ARTICLE_17?> </span></div><div style="clear:both;"></div>
        <div id="add_options_2"> 
        	<div style="float:left; margin-right:10px; width:135px;"><span style="font-size:13px;"><?=$lang->ARTICLE_6?> :</span><input class="input" type="text" readonly="readonly" id="date_start_e" style="margin-left:5px; cursor:pointer;" size="16" value="<? if($results_basic['dateStart'] != 0) echo  date('Y-m-d H:i', $results_basic['dateStart']); ?>"/></div>
            <div style="float:left; margin-right:10px; width:135px;"><span style="font-size:13px;"><?=$lang->ARTICLE_7?> :</span> <input class="input" type="text" readonly="readonly" id="date_end_e" style="margin-left:5px; cursor:pointer;" size="16" value="<? if($results_basic['dateEnd'] != 0) echo date('Y-m-d H:i', $results_basic['dateEnd']); ?>"/></div>
            <div style="float:left; margin-right:10px; width:135px;"><span style="font-size:13px;"><?=$lang->CREATE_DATE?> :</span><input class="input" type="text" readonly="readonly" id="date_create_e" style="margin-left:5px; cursor:pointer;" size="16" value="<? if($results_basic['date'] != 0) echo  date('Y-m-d H:i', $results_basic['date']); ?>"/></div>
            <div style="float:left; margin-right:10px; width:155px;"><span style="font-size:13px;"><?=$lang->ARTICLE_5?> :</span> <input class="input" type="text" id="author" style="margin-left:5px;" size="25" value="<?=$results_basic['author']?>"/></div>
            <div style="float:left; margin-right:10px; width:155px;"><span style="font-size:13px;"><?=$lang->ARTICLE_16?> :</span> <input class="input" type="text" id="alias" style="margin-left:5px;" size="25" value="<?=$results_basic['authorAlias']?>"/></div> 
            <div style="float:left; margin-right:10px; width:155px;"><span style="font-size:13px;"><?=$lang->SITE_TREE_4?> :</span> <input class="input" type="text" id="altPrefix" style="margin-left:5px;" size="25" value="<?=$results_basic['altPrefix']?>"/></div>            
            <div style="float:left; margin-right:10px;"><span style="font-size:13px; display:block; margin-bottom:6px;"><?=$lang->ARTICLE_18?> :</span> <input type="radio" name="published" id="yes" value="1" <? if($results_basic['published']==1) echo 'checked="checked"' ?>><label for="yes"><?=$lang->ARTICLE_19?> </label><input type="radio" name="published" id="no" <? if($results_basic['published']==0) echo 'checked="checked"' ?> value="0"><label for="no"><?=$lang->ARTICLE_20?> </label></div>
            <? if ($db->rows($db->query("show tables like 'com_gallery_cat'"))>0) {
            $query=$db->query('SELECT ID, name FROM com_gallery_cat WHERE status="N"');
			if($db->rows($query)>0) { ?>
             <div style="float:left; margin-right:10px;"><span style="font-size:13px;">Category from gallery:</span><br/>
             	<select id="imageCat" class="input" name="imageCat" style="margin-left:5px; padding-top:3px; width:150px; height:25px;">
                 <option value="0">-- Select category --</option>
                  <? 
                    while($results=$db->fetch($query)) {
                        if($results_basic['galleryCat']==$results['ID'])
                            echo '<option value="'.$results['ID'].'" selected="selected">'.$results['name'].'</option>';
                        else
                            echo '<option value="'.$results['ID'].'">'.$results['name'].'</option>';	
                    }
                ?>
                </select>
             </div>
            <? }
			}
			$query=$db->query('SELECT ID, name FROM cms_article_images WHERE statusID="N" AND articleID="'.$id.'"');
			if($db->rows($query)>0) { ?>
            <div style="float:left; margin-right:15px;"><span style="font-size:13px;"><?=$lang->ARTICLE_25?>:</span><br/>
            	<select id="image" class="input" name="image" style="margin-left:5px; max-width:120px; width:auto;">
                <option value="0">-- <?=$lang->MOD_47?> --</option>
                <? 
                    while($results=$db->fetch($query)) {
                        if($results_basic['image']==$results['ID'])
                            echo '<option value="'.$results['ID'].'" selected="selected">'.$results['name'].'</option>';
                        else
                            echo '<option value="'.$results['ID'].'">'.$results['name'].'</option>';	
                    }
                ?>
            </select>            
            </div>
            <? } ?>        
        	<div style="clear:both;"></div>
        </div>
    
    </tr>
    <tr>
    	<td class="left_td" valign="top" style="width:25%;">
        	<div class="title_form_big"><?=$lang->ARTICLE_31?>:</div><div class="title_form_small"><?=$lang->ARTICLE_32?></div>
        </td>
    	<td colspan="3" class="right_td">         
              <ul id="article_editTags" name="article_editTags">
               <?
			  		$q=$db->query('SELECT * FROM cms_article_tags WHERE articleID="'.$id.'"  ORDER BY ID ASC');
					while($r=$db->fetch($q)) {
						echo '<li data-value="'.$r['value'].'">'.$r['value'].'</li>';
					}			  
			  ?>
              </ul>
        </td>    
    </tr>
    <tr>
        <td colspan="5" class="left_td" valign="top">
            <div>
                <div class="title_form_big" id="articleT_summary" style="float:left; cursor:pointer; margin-right:30px;" onclick="sumo2.article.ChangeView('summary', this);"><?=$lang->ARTICLE_21?></div>                
                <div class="title_form_big" id="articleT_keywords" style="float:left; cursor:pointer; margin-right:30px; font-weight:normal;" onclick="sumo2.article.ChangeView('keywords', this);"><?=$lang->SETTINGS_25?></div>
                <div class="title_form_big" id="articleT_description" style="float:left; cursor:pointer; margin-right:30px; font-weight:normal;" onclick="sumo2.article.ChangeView('description', this);"><?=$lang->SETTINGS_27?></div>
                
            </div>
            <div style="clear:both;">
                <div class="title_form_small" id="articleS_summary"><?=$lang->ARTICLE_22?></div>
                <div class="title_form_small" id="articleS_keywords" style="display:none;"><?=$lang->MOD_196?></div>
                <div class="title_form_small" id="articleS_description" style="display:none;"><?=$lang->MOD_197?></div>
            </div>
        </td>
   	</tr>
   	<tr>
    	<td colspan="5">
    		<textarea  id="summery" style="width:85%; margin-left:5%; margin-right:5%; height:100px;" class="input-area"><?=htmlspecialchars_decode($results_basic['stub'])?></textarea>
            <textarea  id="keywords" style="width:85%; margin-left:5%; margin-right:5%; height:100px; display:none;" class="input-area"><?=htmlspecialchars_decode($results_basic['keywords'])?></textarea>
            <textarea  id="description" style="width:85%; margin-left:5%; margin-right:5%; height:100px; display:none;" class="input-area"><?=htmlspecialchars_decode($results_basic['description'])?></textarea>
        </td>
   	</tr>
   <tr>
   		<td colspan="5" class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->ARTICLE_23?>:</div><div class="title_form_small"><?=$lang->ARTICLE_24?></div>
        </td>
    </tr>
    <tr>
    	<td colspan="5" class="right_td">
        	<div id="alerts">
		<noscript>
			<p>
				<strong>CKEditor requires JavaScript to run</strong>. In a browser with no JavaScript
				support, like yours, you should still see the contents (HTML data) and you should
				be able to edit it normally, without a rich editor interface.
			</p>
		</noscript>
	</div>
			<textarea class="ckeditor" id="testeditor" name="content" rows="10"><?=htmlspecialchars_decode($results_basic['content'])?></textarea>
        </td>
    </tr>
    <tr>
        <td  class="left_td" colspan="2" style="vertical-align:top;">
        <span class="title_form_big"><?=$lang->ARTICLE_26?>:</span><br/><span class="title_form_small"><?=$lang->ARTICLE_27?></span>
        </td>
        <td colspan="2" style="padding-left:20px; padding-top:5px; width:50%;"> 
        	<div style="margin:0 auto; width:350px;">
                <div style="float:left;"><input type="file" name="uploadify66"  id="uploadify6"  multiple="true"  /></div>
                <div id="uploadify6_div" style="float:right; margin-right:50px; cursor:pointer; height:32px; width:111px; background: url(images/css_sprite.png) -1500px -1658px; font-family:'Myriad Pro'; line-height:32px; text-align:center; font-size:14px; color:#FFF;" ><?=$lang->ARTICLE_28?></div>
            </div>
        </td>
    </tr>
    <tr>
    	<td colspan="5" class="left_td" style="width:100%;"><div id="fileq_article"></div><div style="clear:both; margin-bottom:20px;"></div>
        <div>
        	<?
				$query_image=$db->query('SELECT * FROM cms_article_images WHERE articleID='.$id.' AND statusID="N"');
				while($image=$db->fetch($query_image)) {
					echo '<div style="float:left; margin-right:20px; margin-bottom:10px; width:100px; height:132px; display:block; overflow:hidden;" onmouseover="document.getElementById(\'article_image_control_'.$image['ID'].'\').style.display=\'block\'" onmouseout="document.getElementById(\'article_image_control_'.$image['ID'].'\').style.display=\'none\'">
					<img src="../images/'.$user->domainName.'/article/'.$image['file'].'" height="100" style="float:left; width:100px; display:block;"/>
								<div style="margin-left:5px;float:left; display:none; width: 70px; margin-left:-70px; height:20px;" id="article_image_control_'.$image['ID'].'">
                        			<div title="'.$lang->MOD_48.'" style="margin-top:4px;  background: url(images/css_sprite.png) -652px -1661px;width:16px;height:16px;" class="delete sumo2-tooltip" onclick="sumo2.article.ShowPicture(\'../images/'.$user->domainName.'/article/'.$image['file'].'\')"></div>
<div title="'.$lang->MOD_49.'" style="margin-top:4px; margin-left:5px; background: url(images/css_sprite.png) -572px -1645px;" class="delete sumo2-tooltip" onclick="sumo2.dialog.NewDialog(\'d_article_image_rename\',\'id='.$crypt->encrypt($image['ID']).'\')"></div>
                        	<div title="'.$lang->MOD_50.'" style="margin-top:4px; margin-left:5px; background: url(images/css_sprite.png) -636px -1629px;" class="delete sumo2-tooltip" onclick="sumo2.article.DeletePicture(\''.$crypt->encrypt($image['ID']).'\')"></div>
                        </div>
						<div style="clear:both"></div>
						<div style="width:100%; text-align:center; font-weight:bold; margin-top:5px;">'.$image['name'].'</div>
</div>';
				}
			?>
            <div style="clear:both;"></div>
        </div>
        
        </td>
    </tr>
    </table>
    </div>
</form>
<div id="sumo2-article-picture-frame" style="display:none;position:absolute;z-index:9000;padding:10px;background:#f3f3f3;border:1px solid #cdcdcd;"><img id="sumo2-article-picture" src="" height="" width="" /></div>