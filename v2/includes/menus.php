<?php require_once('../initialize.php');
	$security->checkMin();
	if(ob_get_length()>0) {ob_end_clean(); }
	if($db->is('type')) {
		if($db->filter('type') == 'new') {
			$name=htmlspecialchars($db->filter('menu'));
			$content=htmlspecialchars($db->filter('content'));
			$lang=$db->filter('lang');
			$default=0;
			$def=$db->query('SELECT ID FROM cms_menus WHERE status="N" AND lang="'.$lang.'"');
			if($db->rows($def)>0) {
				$def=$db->query('SELECT ID FROM cms_menus WHERE s_default="1" AND status="N" AND lang="'.$lang.'"');
				if($db->rows($def)==0)
					$default=1;
			}
			else
				$default=1;
			$db->query('INSERT INTO cms_menus (title, description, lang, s_default, domain) VALUES ("'.$name.'", "'.$content.'", "'.$lang.'", '.$default.', "'.$user->domain.'")');
			echo "ok";
			exit;
		} else if($db->filter('type') == 'edit') {
			$name=htmlspecialchars($db->filter('menu'));
			$content=htmlspecialchars($db->filter('content'));
			$id=$crypt->decrypt($db->filter('id'));
			$db->query('UPDATE cms_menus SET title="'.$name.'", description="'.$content.'" WHERE ID='.$id.'');
			echo "ok";
			exit;
		} else if($db->filter('type') == 'newitem') {
			$name=htmlspecialchars($db->filter('menu'));
			$keywords=$db->filter('keywords');
			$description=$db->filter('description');
			$template=$db->filter('template');
			$moduleID=$db->filter('moduleID');
			$id=$db->filter('id');
			$id=explode("#", $id);
			if($id[0]=="0")
				$id[0]="-1";
			$order=$db->get($db->query('SELECT orderID FROM cms_menus_items WHERE parentID='.$id[0].' order by orderID desc LIMIT 1'));
			$order=$order['orderID']+1;
			$selected=$db->filter('selected');
			$short_link=$db->filter('short_link');
			$elink=$db->filter('elink');
			$restriction=$db->filter('restriction');
			$target=$db->filter('target');
			$parentID=$db->filter('parentID');
			$prefix=getPrefixTitle($name, "cms_menus_items", "altPrefix", "", "", 'AND domain="'.$user->domain.'" AND parentID="'.$id[0].'"');
			if($selected==1)
				$db->query('INSERT INTO cms_menus_items (title, status, parentID, menuID, orderID, keyword, description, selection, template, restriction, altPrefix, domain, moduleID) VALUES ("'.$name.'", "N", '.$id[0].', '.$id[1].', '.$order.', "'.$keywords.'", "'.$description.'", "1", "'.$template.'", "'.$restriction.'", "'.$prefix.'", "'.$user->domain.'", "'.$moduleID.'")');
			else if($selected==2) {
				$db->query('INSERT INTO cms_menus_items (title, status, parentID, menuID, orderID, keyword, description, selection, template, link, restriction, altPrefix, domain, moduleID) VALUES ("'.$name.'", "N", '.$id[0].', '.$id[1].', '.$order.', "'.$keywords.'", "'.$description.'", "2", "'.$template.'", "'.$short_link.'", "'.$restriction.'", "'.$prefix.'", "'.$user->domain.'", "-1")');
			}
			else if($selected==3)
				$db->query('INSERT INTO cms_menus_items (title, status, parentID, menuID, orderID, keyword, description, selection, template, link, target, restriction, altPrefix, domain, moduleID) VALUES ("'.$name.'", "N", '.$id[0].', '.$id[1].', '.$order.', "'.$keywords.'", "'.$description.'", "3", "'.$template.'", "'.$elink.'", "'.$target.'", "'.$restriction.'", "'.$prefix.'", "'.$user->domain.'", "-1")');
			else if($selected==4) {
				if($elink=="") {
					$prefix=getPrefixTitle($name, "cms_menus_items", "altPrefix", "", "", 'AND domain="'.$user->domain.'" AND parentID="'.$id[0].'"');
				} else {
					$prefix=getPrefixTitle("", "cms_menus_items", "altPrefix", "", $elink, 'AND domain="'.$user->domain.'" AND parentID="'.$id[0].'"');
				}

				$db->query('INSERT INTO cms_menus_items (title, status, parentID, menuID, orderID, keyword, description, selection, template, target, restriction, altPrefix, domain, moduleID) VALUES ("'.$name.'", "N", '.$parentID.', '.$id[0].', -1, "'.$keywords.'", "'.$description.'", "4", "'.$template.'", "'.$id[1].'", "'.$restriction.'", "'.$prefix.'", "'.$user->domain.'", "-1")');
			}
			echo "ok";
			exit;
		}else if($db->filter('type') == 'edititem') {
			$id=explode("#", $db->filter('id'));
			$id=$id[0];
			$name=htmlspecialchars($db->filter('menu'));
			$template=$db->filter('template');
			$moduleID=$db->filter('moduleID');
			$keywords=$db->filter('keywords');
			$description=$db->filter('description');
			$selected=$db->filter('selected');
			$short_link=$db->filter('short_link');
			$elink=$db->filter('elink');
			$restriction=$db->filter('restriction');
			$target=$db->filter('target');
			$parentID=$db->filter('parentID');
			$altPrefix=$db->filter('altPrefix');
			if($altPrefix=="") {
				$prefix=getPrefixTitle($name, "cms_menus_items", "altPrefix", "", "", 'AND domain="'.$user->domain.'" AND parentID="'.$parentID.'"');
			} else {
				$prefix=getPrefixTitle("", 'cms_menus_items', 'altPrefix', $id, $altPrefix, 'AND domain="'.$user->domain.'" AND parentID="'.$parentID.'"');
			}

			if($selected==1)
				$db->query('UPDATE cms_menus_items SET title="'.$name.'", keyword="'.$keywords.'", description="'.$description.'", template="'.$template.'", restriction="'.$restriction.'", selection="1", altPrefix="'.$prefix.'", moduleID="'.$moduleID.'" WHERE ID='.$id.'');
			else if($selected==2)
				$db->query('UPDATE cms_menus_items SET title="'.$name.'", keyword="'.$keywords.'", description="'.$description.'", template="'.$template.'", restriction="'.$restriction.'", selection="2", link="'.$short_link.'", altPrefix="'.$prefix.'", moduleID="-1" WHERE ID='.$id.'');
			else if($selected==3)
				$db->query('UPDATE cms_menus_items SET title="'.$name.'", keyword="'.$keywords.'", description="'.$description.'", template="'.$template.'", restriction="'.$restriction.'", selection="3", link="'.$elink.'", target="'.$target.'", altPrefix="'.$prefix.'", moduleID="-1" WHERE ID='.$id.'');
			else if($selected==4)
				$db->query('UPDATE cms_menus_items SET title="'.$name.'", keyword="'.$keywords.'", description="'.$description.'", template="'.$template.'", restriction="'.$restriction.'", selection="4", parentID="'.$parentID.'", altPrefix="'.$prefix.'", moduleID="-1" WHERE ID='.$id.'');
			echo "ok";
			exit;
		} else if($db->filter('type') == 'edithome') {
			$id=$db->filter('id');
			$name=htmlspecialchars($db->filter('menu'));
			$altTitle=$db->filter('altTitle');
			$template=$db->filter('template');
			$keywords=$db->filter('keywords');
			$description=$db->filter('description');
			$selected=$db->filter('selected');
			$short_link=$db->filter('short_link');
			$prefix=getPrefixTitle($name, 'cms_menus_items', 'altPrefix', $id, "", 'AND domain="'.$user->domain.'"');
			$prefixHome=getPrefixTitle($name, 'cms_homepage', 'altPrefix', $id, "", 'AND domain="'.$user->domain.'"');
			$db->query('UPDATE cms_homepage SET title="'.$name.'", keyword="'.$keywords.'", description="'.$description.'", template="'.$template.'", link="'.$short_link.'", selection="'.$selected.'", altPrefix="'.$prefixHome.'", altTitle="'.$altTitle.'" WHERE ID='.$id.'');
			$db->query('UPDATE cms_menus_items SET title="'.$name.'", keyword="'.$keywords.'", description="'.$description.'", altPrefix="'.$prefix.'" WHERE link='.$id.' AND parentID="-1" AND orderID="-1"');
			echo "ok";
			exit;
		} else if($db->filter('type') == 'deleteitem') {
			$id=explode("#", $db->filter('id'));
			$id=$id[0];
			$db->query('UPDATE cms_menus_items SET status="D" WHERE ID='.$id.'');
			echo "ok";
			exit;
		} else if($db->filter('type') == 'statusitem') {
			$id = $db->filter('id');
			$result = $db->get($db->query("SELECT enabled FROM cms_menus_items WHERE ID='".$id."'"));
			if($result) {
				if($result['enabled'] == 0)
					$new = 1;
				else
					$new = 0;
			}
			$db->query("UPDATE cms_menus_items SET enabled='".$new."' WHERE ID='".$id."'");
			echo 'Finished';
			exit;
		}else if($db->filter('type') == 'status') {
			$id = $crypt->decrypt($db->filter('id'));
			$result = $db->get($db->query("SELECT enabled FROM cms_menus WHERE ID='".$id."'"));
			if($result) {
				if($result['enabled'] == 0)
					$new = 1;
				else
					$new = 0;
			}
			$db->query("UPDATE cms_menus SET enabled='".$new."' WHERE ID='".$id."'");
			echo 'Finished';
			exit;
		} else if($db->filter('type') == 'delete') {
			$id=$crypt->decrypt($db->filter('id'));
			$db->query('UPDATE cms_menus SET status="D" WHERE ID="'.$id.'"');
			$articleq = $db->query('SELECT ID FROM cms_menus_items WHERE menuID='.$id.'');
			while($articleF=$db->fetch($articleq)) {
				$db->query("UPDATE cms_menus_items SET status='D' WHERE ID='".$articleF['ID']."'");
			}
			echo "ok";
			exit;
		} else if($db->filter('type') == 'translate') {
			$menu=htmlspecialchars($db->filter('menu'));
			$content=htmlspecialchars($db->filter('content'));
			$lang=$crypt->decrypt($db->filter('lang'));
			$parent=$crypt->decrypt($db->filter('parent'));
			$default=0;
			$def=$db->query('SELECT ID FROM cms_menus WHERE status="N" AND lang="'.$lang.'"');
			if($db->rows($def)>0) {
				$def=$db->query('SELECT ID FROM cms_menus WHERE s_default="1" AND status="N" AND lang="'.$lang.'"');
				if($db->rows($def)==0)
					$default=1;
			}
			else
				$default=1;
			$db->query('INSERT INTO cms_menus (title, description, lang, parent, s_default, domain) VALUES ("'.$menu.'", "'.$content.'", "'.$lang.'", "'.$parent.'", "'.$default.'", "'.$user->domain.'")');
			echo "ok";
			exit;
		} else if($db->filter('type') == 'up') {
			$id=explode("#", $db->filter('id'));
			$id=$id[0];
			$firstorder=$db->get($db->query('SELECT orderID, parentID, menuID, ID FROM cms_menus_items WHERE ID='.$id.''));
			$secondorder=$db->get($db->query('SELECT orderID, ID FROM cms_menus_items WHERE parentID='.$firstorder['parentID'].' AND menuID='.$firstorder['menuID'].' AND status="N" AND orderID<'.$firstorder['orderID'].' ORDER BY orderID desc  LIMIT 1'));
			$db->query('UPDATE cms_menus_items SET orderID="'.$secondorder['orderID'].'" WHERE ID="'.$firstorder['ID'].'"');
			$db->query('UPDATE cms_menus_items SET orderID="'.$firstorder['orderID'].'" WHERE ID="'.$secondorder['ID'].'"');
			echo "ok";
			exit;
		} else if($db->filter('type') == 'down') {
			$id=explode("#", $db->filter('id'));
			$id=$id[0];
			$firstorder=$db->get($db->query('SELECT orderID, parentID, menuID, ID FROM cms_menus_items WHERE ID='.$id.''));
			$secondorder=$db->get($db->query('SELECT orderID, ID FROM cms_menus_items WHERE parentID='.$firstorder['parentID'].' AND menuID='.$firstorder['menuID'].' AND status="N" AND orderID>'.$firstorder['orderID'].' ORDER BY orderID asc LIMIT 1'));
			$db->query('UPDATE cms_menus_items SET orderID="'.$secondorder['orderID'].'" WHERE ID="'.$firstorder['ID'].'"');
			$db->query('UPDATE cms_menus_items SET orderID="'.$firstorder['orderID'].'" WHERE ID="'.$secondorder['ID'].'"');
			echo "ok";
			exit;
		} else if ($db->filter('type') == 'default_m') {
			$id=$crypt->decrypt($db->filter('id'));
			$lang=$db->filter('lang');
			$selected_q=$db->query('SELECT ID FROM cms_menus WHERE s_default=1 AND lang='.$lang.'');
			$selected=$db->rows($selected_q);
			if($selected>0) {
				$selected_q=$db->fetch($selected_q);
				$db->query('UPDATE cms_menus SET s_default="0" WHERE ID="'.$selected_q['ID'].'"');
				$db->query('UPDATE cms_menus SET s_default="1" WHERE ID="'.$id.'"');
			} else
				$db->query('UPDATE cms_menus SET s_default="1" WHERE ID="'.$id.'"');
			echo "ok";
			exit;
		}  else if($db->filter('type') == 'showMenu') {
			$id=explode("#", $db->filter('id'));
			$id=$id[0];
			$current=$db->get($db->query('SELECT showM FROM cms_menus_items WHERE ID="'.$id.'"'));
			if($current['showM']=="Y")
				$show="N";
			else
				$show="Y";
			$db->query('UPDATE cms_menus_items SET showM="'.$show.'" WHERE ID="'.$id.'"');
			echo "ok";
			exit;
		} else if($db->filter('type') == 'newitemspecial') {
			$name=htmlspecialchars($db->filter('menu'));
			$keywords=$db->filter('keywords');
			$description=$db->filter('description');
			$template=$db->filter('template');
			$selected=$db->filter('selected');
			$elink=$db->filter('elink');
			$restriction=$db->filter('restriction');
			$parentID=$db->filter('parentID');
			$module=$db->filter('module');
			$language=$crypt->decrypt($db->filter('lang'));
			$prefix=getPrefixTitle($name, 'cms_menus_items', 'altPrefix', "", "", 'AND domain="'.$user->domain.'"');
			$db->query('INSERT INTO cms_menus_items (title, parentID, menuID, orderID, keyword, description, selection, template, link, target, restriction, alias, altPrefix, domain) VALUES ("'.$name.'",'.$parentID.', '.$module.', -1, "'.$keywords.'", "'.$description.'", "4", "'.$template.'", "'.$elink.'", "'.$language.'", "'.$restriction.'", "'.$elink.'", "'.$prefix.'", "'.$user->domain.'")');
			echo "ok";
			exit;
		}
	}
?>