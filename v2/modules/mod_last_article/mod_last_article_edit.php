<?php 
	require_once('../../initialize.php');
	$query = $db->fetch($db->query("SELECT categoryID, number, animation, ID FROM mod_last_article WHERE cms_panel_id='".$db->filter('panel')."' AND cms_layout='".$db->filter('layout')."'"));
?>
<form action="" name="mod_last_article_edit" method="post" class="form2">
    <table cellpadding="0" cellspacing="4" border="0" width="99%" >
    <input type="hidden" name="id" value="<?php echo $crypt->encrypt($query['ID']); ?>" />
     <tr>
        <td class="left_td" valign="top">
		<div class="title_form_big">Category:</div><div class="title_form_small">Please select category:</div>
        </td>
        <td class="right_td">
			 <select id="categoryID" style="width:90%; margin-left:5%; margin-right:5%;">
				<?
                    $query1=$db->query('SELECT title, ID FROM cms_article_groups WHERE status="N" ORDER BY ID');
                    while($results=$db->fetch($query1)) {
                        if($results['ID']==$query['categoryID'])
                            $select='selected="selected"';
                        else
                            $select="";
                        echo '<option value="'.$results['ID'].'" '.$select.'>'.$results['title'].'</option>';
                    }		
            ?>
        	</select>
        </td>
    </tr>
     <tr>
        <td class="left_td" valign="top">
		<div class="title_form_big">Animation:</div><div class="title_form_small">Would you like animated articles?</div>
        </td>
        <td class="right_td">
			 <select id="animation" style="width:90%; margin-left:5%; margin-right:5%;">
				<option value="0" <? if($query['animation']==0) echo 'selected="selected"';?>>Yes</option>
                <option value="1" <? if($query['animation']==1) echo 'selected="selected"';?>>No</option>
        	</select>
        </td>
    </tr>
    <tr>
        <td class="left_td" valign="top">
		<div class="title_form_big">Number:</div><div class="title_form_small">Define number of articles to be displayed:</div>
        </td>
        <td class="right_td">
			<input type="text" name="linkNum" class="input" value="<?=$query['number']?>"/>
        </td>
    </tr>
    </table>
</form>