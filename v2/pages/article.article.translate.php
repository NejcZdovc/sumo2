<? require_once('../initialize.php'); 
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 	exit;
	}
	$id=$crypt->decrypt($db->filter('article'));
	$language=$crypt->decrypt($db->filter('lang'));
	$short_old=lang_name_front($crypt->decrypt($db->filter('current')));
	$old=$db->fetch($db->query('SELECT title, content, stub, category, dateStart, dateEnd, author, authorAlias, published, image FROM cms_article WHERE ID="'.$id.'"'));
	$oldCat=explode('#??#', $old['category']);
	$Cats="";
	foreach($oldCat as $value) {
		if(strlen($value)>=1) {
			$temp=$db->fetch($db->query('SELECT title FROM cms_article_categories WHERE ID='.$value.''));
			$Cats.='-'.$temp['title'].'<br/>';
		}
	}
?>
<form action="" name="a_article_a_translate" method="post" class="form2">
	<input type="hidden" id="parent" value="<?=$id?>" />
    <input type="hidden" id="lang" value="<?=$language?>" />
	<div class="table_panel">
   	<table cellpadding="0" cellspacing="4" border="0" width="100%" >
   	<tr>
        <td class="left_td td_width" width="50%" valign="top">
        <div class="title_form_big"><?=$lang->ARTICLE_NEW_T_1?>:</div><div class="title_form_small" style="margin-bottom:10px; width:100%;"><?=$lang->ARTICLE_NEW_T_2?></div>
        <div style="float:left; width:45%;"><b><?=$short_old?> <?=$lang->ARTICLE_29?>:</b><br/><?=$old['title']?></div><div style="margin-left:10px; width:45%; float:left;"><b><?=lang_name_front($language)?> <?=$lang->ARTICLE_29?>:</b> <input name="title" id="title" value="" type="text" class="input" /></div>
        </td>
        <td class="left_td td_width" width="50%" valign="top">
        <div class="title_form_big"><?=$lang->ARTICLE_NEW_C_1?>:</div><div class="title_form_small" style="margin-bottom:10px;"><?=$lang->ARTICLE_14?></div>
        <div style="float:left; width:45%;"><b><?=$short_old?> <?=$lang->ARTICLE_29?>:</b><br/><?=$Cats?></div><div style="margin-left:10px; width:45%; float:left;"><b><?=lang_name_front($language)?> <?=$lang->ARTICLE_29?>:</b><input id="category_new_article_translate" value="" name="category" type="hidden" />
        <div title="<?=$lang->ARTICLE_15?>" onclick="sumo2.dialog.NewDialog('d_article_cat', 'type=T$!$id=')" style="cursor:pointer; font-weight:bold; font-size:15px; margin-left:40%;"><?=$lang->MOD_45?></div></div>
        </td>
   	</tr>
   	<tr>
    	<td class="left_td" colspan="2">
        <div style="float:left; margin-right:5px;"><span class="title_form_big"><?=$lang->ARTICLE_17?></span></div> <div style="clear:both;"></div>
        <div id="add_options_1"> 
        	<div style="float:left; margin-right:10px;"><span style="font-size:13px;"><?=$lang->ARTICLE_6?>:</span><input type="text" readonly="readonly" id="date_start_t" style="margin-left:5px; cursor:pointer;" class="input" size="16" value=""/></div>
            <div style="float:left; margin-right:10px;"><span style="font-size:13px;"><?=$lang->ARTICLE_7?>:</span> <input type="text" readonly="readonly" id="date_end_t" style="margin-left:5px; cursor:pointer;" size="16" class="input" value=""/></div>
            <div style="float:left; margin-right:10px;"><span style="font-size:13px;"><?=$lang->ARTICLE_5?>:</span> <input type="text" id="author" class="input" style="margin-left:5px;" size="25" value="<?=$user->name?>"/></div>
            <div style="float:left;"><span style="font-size:13px;"><?=$lang->ARTICLE_16?>:</span> <input class="input" type="text" id="alias" style="margin-left:5px;" size="25" value=""/></div>
        <div style="float:left; margin-right:10px; margin-top:17px;"><span style="font-size:13px;"><?=$lang->ARTICLE_18?>:</span> <input type="radio" name="published" checked="checked" id="yes" value="1"><label for="yes"><?=$lang->ARTICLE_19?></label><input type="radio" name="published" id="no" value="0"><label for="no"><?=$lang->ARTICLE_20?></label></div>
        	<div style="clear:both;"></div>
        </div>
    
   	</tr>
   	<tr>
        <td colspan="2" class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->ARTICLE_21?>:</div><div class="title_form_small"><?=$lang->ARTICLE_22?></div>
        </td>
   	</tr>
   	<tr>
    	<td colspan="2">
        <div style="width:90%; margin-left:5%; margin-bottom:10px; margin-right:5%;"><b><?=$short_old?> <?=$lang->ARTICLE_29?>:</b><br/><?=htmlspecialchars_decode($old['stub'])?></div><div style="width:90%; margin-left:5%; margin-right:5%;"><b><?=lang_name_front($language)?> <?=$lang->ARTICLE_29?>:</b><br/>
    	<textarea  id="summery" style="width:100%; height:100px;" class="input-area"></textarea></div>
        </td>
   	</tr>
   	<tr>
        <td colspan="2" class="left_td" valign="top">
        <div class="title_form_big"><?=$lang->ARTICLE_23?>:</div><div class="title_form_small"><?=$lang->ARTICLE_24?></div>
        </td>
    </tr>
    <tr>
    	<td colspan="2" class="right_td">
        	<div id="alerts">
		<noscript>
			<p>
				<strong>CKEditor requires JavaScript to run</strong>. In a browser with no JavaScript
				support, like yours, you should still see the contents (HTML data) and you should
				be able to edit it normally, without a rich editor interface.
			</p>
		</noscript>
	</div>
    <div id="old_html" style="display:none;"><?= htmlspecialchars_decode($old['content'])?></div>
    		        <div style="width:90%; margin-left:5%; margin-bottom:10px; margin-right:5%;"><span onclick="CKEDITOR.instances.editor6.insertHtml( $('#old_html').html() );" style="cursor:pointer;font-weight:bold;"><?=$lang->MOD_189?>  <?=$short_old?> <?=$lang->ARTICLE_29?></span>&nbsp;&nbsp;<br/></div><div style="width:90%; margin-left:5%; margin-right:5%;"><b><?=lang_name_front($language)?> <?=$lang->ARTICLE_29?>:</b><br/>
			<textarea class="ckeditor" id="editor6" name="content" rows="10"></textarea></div>
        </td>
    
    </tr>
    </table>
    </div>
</form>