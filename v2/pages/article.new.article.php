<?php require_once('../initialize.php'); 
	if(!$session->isLogedIn() || !$security->checkURL()) {
		exit;
	}
?>
<form action="" name="a_article_new_a" method="post" class="form2">
	<div class="table_panel">
   	<table cellpadding="0" cellspacing="4" border="0" width="100%" >
   	<tr>
        <td class="left_td td_width" width="25%" valign="top">
        <div class="title_form_big"><?php echo $lang->ARTICLE_NEW_T_1?>:</div><div class="title_form_small"><?php echo $lang->ARTICLE_NEW_T_2?></div>
        </td>
        <td class="right_td td_width" width="25%">
        <input name="title" id="title" value="" type="text" class="input" />
        </td>
        <td class="left_td td_width" width="25%" valign="top">
        <div class="title_form_big"><?php echo $lang->ARTICLE_NEW_C_1?>:</div><div class="title_form_small"><?php echo $lang->MOD_46?></div>
        </td>
        <td class="right_td td_width" width="25%">
        <input id="category_new_article" value="" name="category" type="hidden" />
        <div title="<?php echo $lang->ARTICLE_15?>" onclick="sumo2.dialog.NewDialog('d_article_cat', 'type=N$!$id=')" style="cursor:pointer; font-weight:bold; font-size:15px; margin-left:40%;"><?php echo $lang->MOD_45?></div>
        </td>
   	</tr>
   	<tr>
    <td class="left_td" colspan="5">
        <div style="float:left; margin-right:5px;"><span class="title_form_big"><?php echo $lang->ARTICLE_17?></span></div>
        <div style="clear:both;"></div>
        <div id="add_options_1"> 
        	<div style="float:left; margin-right:10px; width:155px;"><span style="font-size:13px;"><?php echo $lang->ARTICLE_6?>:</span><input type="text" readonly="readonly" id="date-start-a" style="margin-left:5px; cursor:pointer;" class="input" size="16" value=""/></div>
            <div style="float:left; margin-right:10px; width:155px;"><span style="font-size:13px;"><?php echo $lang->ARTICLE_7?>:</span> <input type="text" readonly="readonly" id="date-end-a" style="margin-left:5px; cursor:pointer;" size="16" class="input" value=""/></div>
            <div style="float:left; margin-right:10px; width:155px;"><span style="font-size:13px;"><?php echo $lang->ARTICLE_5?>:</span> <input type="text" id="author" class="input" style="margin-left:5px;" size="25" value="<?php echo $user->name?>"/></div>
            <div style="float:left; width:155px;"><span style="font-size:13px;"><?php echo $lang->ARTICLE_16?>:</span> <input class="input" type="text" id="alias" style="margin-left:5px;" size="25" value=""/></div>
        <div style="float:left; margin-left:20px;"><span style="font-size:13px; display:block; margin-bottom:6px;"><?php echo $lang->ARTICLE_18?>:</span><input type="radio" name="published" checked="checked" id="yes" value="1"><label for="yes"><?php echo $lang->ARTICLE_19?></label><input type="radio" name="published" id="no" value="0"><label for="no"><?php echo $lang->ARTICLE_20?></label></div>
        	<div style="clear:both;"></div>
        </div>
    
   	</tr>
    <tr>
    	<td class="left_td" valign="top" style="width:25%;">
        	<div class="title_form_big"><?php echo $lang->ARTICLE_31?>:</div><div class="title_form_small"><?php echo $lang->ARTICLE_32?></div>
        </td>
    	<td colspan="3" class="right_td">  
              <ul id="article_newTags" name="article_newTags"></ul>
        </td>    
    </tr>
   	<tr>
        <td colspan="5" class="left_td" valign="top">
        <div>
        	<div class="title_form_big" id="articleT_summary" style="float:left; cursor:pointer; margin-right:30px;" onclick="sumo2.article.ChangeView('summary', this);"><?php echo $lang->ARTICLE_21?></div>
            <div class="title_form_big" id="articleT_keywords" style="float:left; cursor:pointer; margin-right:30px; font-weight:normal;" onclick="sumo2.article.ChangeView('keywords', this);"><?php echo $lang->SETTINGS_25?></div>
            <div class="title_form_big" id="articleT_description" style="float:left; cursor:pointer; margin-right:30px; font-weight:normal;" onclick="sumo2.article.ChangeView('description', this);"><?php echo $lang->SETTINGS_27?></div>
            
        </div>
        <div style="clear:both;">
        	<div class="title_form_small" id="articleS_summary"><?php echo $lang->ARTICLE_22?></div>
            <div class="title_form_small" id="articleS_keywords" style="display:none;"><?php echo $lang->MOD_196?></div>
            <div class="title_form_small" id="articleS_description" style="display:none;"><?php echo $lang->MOD_197?></div>
        </div>
        </td>
   	</tr>
   	<tr>
    	<td colspan="5">
    		<textarea  id="summery" style="width:85%; margin-left:5%; margin-right:5%; height:100px;" class="input-area"></textarea>
            <textarea  id="keywords" style="width:85%; margin-left:5%; margin-right:5%; height:100px; display:none;" class="input-area"></textarea>
            <textarea  id="description" style="width:85%; margin-left:5%; margin-right:5%; height:100px; display:none;" class="input-area"></textarea>
        </td>
   	</tr>
   	<tr>
        <td colspan="5" class="left_td" valign="top">
        <div class="title_form_big"><?php echo $lang->ARTICLE_23?>:</div><div class="title_form_small"><?php echo $lang->ARTICLE_24?></div>
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
			<textarea class="ckeditor" id="editor2" name="content" rows="10"></textarea>
        </td>
    
    </tr>
    </table>
    </div>
</form>
