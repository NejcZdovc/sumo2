<?php	require_once('../initialize.php');
	if(!$session->isLogedIn() || !$security->checkURL()) {
	 exit;
	}
if(ob_get_length()>0) {ob_end_clean(); }

if($db->is('type')) {
	if($db->filter('type') == 'addgroup') {
		$name = htmlspecialchars($db->filter('name'));
		$description = $db->filter(htmlspecialchars('description'));
		$prefix=getPrefixTitle($name, 'cms_article_categories', 'altPrefix');
		$db->query("INSERT INTO cms_article_categories (title, altPrefix, description, lang, domain) VALUES ('".$name."', '".$prefix."', '".$description."', '".$user->translate_lang."', '".$user->domain."')");
		echo 'ok';
		exit;
	} else if($db->filter('type') == 'statusgroup') {
		$id = $crypt->decrypt($db->filter('id'));
		$result = $db->get($db->query("SELECT enabled FROM cms_article_categories WHERE ID='".$id."'"));
		if($result) {
			if($result['enabled'] == 0) {
				$new = 1;
			} else {
				$new = 0;
			}
			$db->query("UPDATE cms_article_categories SET enabled='".$new."' WHERE ID='".$id."'");
			echo 'Finished';
			exit;
		}
	} else if($db->filter('type') == 'editgroup') {
		$name = htmlspecialchars($db->filter('name'));
		$description = $db->filter(htmlspecialchars('description'));
		$id = $crypt->decrypt($db->filter('id'));
		$prefix=getPrefixTitle($name, 'cms_article_categories', 'altPrefix');
		$db->query("UPDATE cms_article_categories SET title='".$name."', altPrefix='".$prefix."', description='".$description."' WHERE ID='".$id."'");
		echo 'ok';
		exit;
	} else if($db->filter('type') == 'deletegroup') {
		$id = $crypt->decrypt($db->filter('id'));
		$db->query("UPDATE cms_article_categories SET status='D' WHERE ID='".$id."'");
		$articleq = $db->query('SELECT ID,category FROM cms_article WHERE category='.$id.'');
		while($articleF=$db->fetch($articleq)) {
			$db->query("UPDATE cms_article SET status='D' WHERE ID='".$articleF['ID']."'");	
		}
		echo 'Finished';
		exit;
	} else if($db->filter('type') == 'newarticle') {
		$title = htmlspecialchars($db->filter('title'));
		$category = $db->filter('category');
		$datestart = $db->filter('datestart');
		$datestart=strtotime($datestart);
		$dateend = $db->filter('dateend');
		$dateend=strtotime($dateend);
		$published = $db->filter('published');
		$author = $db->filter('author');
		$alias = $db->filter('alias');
		$content = htmlspecialchars($db->filter('content'));
		$content=str_replace('$!$','&',$content);
		$stub = htmlspecialchars($db->filter('stub'));
		$keywords = htmlspecialchars($db->filter('keywords'));
		$description = htmlspecialchars($db->filter('description'));
		$prefix=getPrefixTitle($title, 'cms_article', 'altPrefix');		
		$lang=$db->get($db->query('SELECT translate_lang FROM cms_user_settings WHERE userID='.$user->id.''));
		if(is_numeric($lang['translate_lang']) && $lang['translate_lang'] != 0)
			$lang=$lang['translate_lang'];
		else
			$lang=1;
		$db->query("INSERT INTO cms_article (title,content,category,dateStart,dateEnd,author,authorAlias,date,published,stub,lang, altPrefix,keywords,description,domain) VALUES ('".$title."','".$content."','".$category."','".$datestart."','".$dateend."','".$author."','".$alias."', '".time()."','".$published."', '".$stub."','".$lang."', '".$prefix."', '".$keywords."', '".$description."', '".$user->domain."')");
		$id=$db->getLastId();
		$tags=explode("*/*", $db->filter('tags'));
		foreach($tags as $tag) {
			$db->query("INSERT INTO cms_article_tags (value, articleID) VALUES ('".$tag."', '".$id."')");
		}
		
		echo 'Finished';
		exit;
	} else if($db->filter('type') == 'statusarticle') {
		$id = $crypt->decrypt($db->filter('id'));
		$result = $db->get($db->query("SELECT published FROM cms_article WHERE ID='".$id."'"));
		if($result) {
			if($result['published'] == 0) {
				$new = 1;
			} else {
				$new = 0;
			}
			$db->query("UPDATE cms_article SET published='".$new."' WHERE ID='".$id."'");
			echo 'Finished';
			exit;
		}
	} else if($db->filter('type') == 'deletearticle') {
		$id = $crypt->decrypt($db->filter('id'));
		$db->query("UPDATE cms_article SET status='D' WHERE ID='".$id."'");
		echo 'Finished';
		exit;
	} else if($db->filter('type') == 'editarticle') {		
		$title = htmlspecialchars($db->filter('title'));
		$category = $db->filter('category');
		$datestart=strtotime($db->filter('datestart'));
		$dateend=strtotime($db->filter('dateend'));
		$datecreate = strtotime($db->filter('datecreate'));
		$published = $db->filter('published');
		$author = htmlspecialchars($db->filter('author'));
		$alias = htmlspecialchars($db->filter('alias'));
		$content = htmlspecialchars($db->filter('content'));
		$content=str_replace('\n','',$content);
		$stub = htmlspecialchars($db->filter('stub'));		
		if($db->is('image'))
			$image="image='".$db->filter('image')."'";
		$id=$crypt->decrypt($db->filter('id'));
		$imageCat=$db->filter('imageCat');
		$keywords = htmlspecialchars($db->filter('keywords'));
		$description = htmlspecialchars($db->filter('description'));
		$prefix=$db->filter('altPrefix');
		if($prefix=="" || $prefix==" ") {
			$prefix=getPrefixTitle($title, 'cms_article', 'altPrefix', $id);
		} else {
			if(checkPrefixTitle($prefix, 'cms_article', 'altPrefix', $id)) {
				$prefix=getPrefixTitle($title, 'cms_article', 'altPrefix', $id);
			}
		}
		$db->query("UPDATE cms_article SET title='".$title."', content='".$content."', category='".$category."', dateStart='".$datestart."', dateEnd='".$dateend."', author='".$author."', authorAlias='".$alias."', ".$image.", published='".$published."', stub='".$stub."', galleryCat='".$imageCat."', changes=TIMESTAMP('".time()."'), altPrefix='".$prefix."', keywords='".$keywords."', description='".$description."', date='".$datecreate."' WHERE ID='".$id."'");
		
		$db->query("DELETE FROM cms_article_tags WHERE articleID='".$id."'");
		$tags=explode("*/*", $db->filter('tags'));
		foreach($tags as $tag) {
			$db->query("INSERT INTO cms_article_tags (value, articleID) VALUES ('".$tag."', '".$id."')");
		}
		echo 'Finished';
		exit;
	} else if ($db->filter('type')=='translate_c') {
		$lang=$db->filter('lang');
		$name=$db->filter('name');
		$description=$db->filter('description');
		$parent=$db->filter('parent');
		$db->query("INSERT INTO cms_article_categories (title,description,lang,parent) VALUES ('".$name."','".$description."','".$lang."','".$parent."')");
		echo 'Finished';
		exit;
	} else if($db->filter('type') == 'translate_a') {
		$title = htmlspecialchars($db->filter('title'));
		$category = htmlspecialchars($db->filter('category'));
		$datestart = htmlspecialchars($db->filter('datestart'));
		$datestart=strtotime($datestart);
		$dateend = htmlspecialchars($db->filter('dateend'));
		$dateend=strtotime($dateend);
		$published = htmlspecialchars($db->filter('published'));
		$author = htmlspecialchars($db->filter('author'));
		$alias = htmlspecialchars($db->filter('alias'));
		$content = htmlspecialchars($db->filter('content'));
		$stub = htmlspecialchars($db->filter('stub'));
		$lang=$db->filter('lang');
		$parent=$db->filter('parent');
		$prefix=getPrefixTitle($title, 'cms_article', 'altPrefix');
		$db->query("INSERT INTO cms_article (title,content,category,dateStart,dateEnd,author,authorAlias,date,published,stub,lang,parent,altPrefix) VALUES ('".$title."','".$content."','".$category."','".$datestart."','".$dateend."','".$author."','".$alias."', '".time()."','".$published."', '".$stub."','".$lang."', '".$parent."', '".$prefix."')");
		echo 'Finished';
		exit;
	} else if($db->filter('type') == 'deleteimage') {
		$id = $crypt->decrypt($db->filter('id'));
		$article=$db->query('SELECT ID FROM cms_article WHERE image="'.$id.'"');
		if($db->rows($article)>0) {
			$db->query("UPDATE cms_article SET image='0' WHERE image='".$id."'");
		}
		$image=$db->get($db->query("SELECT file FROM cms_article_images WHERE ID='".$id."'"));
		unlink($_SERVER['DOCUMENT_ROOT'] .'/images/'.$user->domainName.'/article/'.$image['file']);
		$db->query("DELETE FROM cms_article_images WHERE ID='".$id."'");
		echo 'ok';
		exit;
	} else if($db->filter('type') == 'renameimage') {
		$id = $crypt->decrypt($db->filter('id'));
		$name = $db->filter('name');
		$db->query("UPDATE cms_article_images SET name='".$name."' WHERE ID='".$id."'");
		echo 'ok';
		exit;
	} else if($db->filter('type')=='counter') {
		$id = $crypt->decrypt($db->filter('id'));
		$db->query("UPDATE cms_article SET views='0' WHERE ID='".$id."'");
		echo 'ok';
		exit;
	} else if($db->filter('type')=='counterall') {
		$query = $db->query("SELECT ID FROM cms_article");
		while($result=$db->fetch($query)) {
			$db->query("UPDATE cms_article SET views='0' WHERE ID='".$result['ID']."'");
		}
		echo 'ok';
		exit;
	}
}
?>