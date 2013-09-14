<? 
class LastArticle extends Modules
{	
	public $number;
	public $category;
	
	function __construct() {
		parent::__construct();
		global $db;
		$query=$db->get($db->query('SELECT categoryID, number, animation FROM mod_last_article WHERE ID='.$this->id.''));
		$this->number=$query['number'];
		$this->category=$query['categoryID'];			
		$this->smarty->assign("title", $this->title);
		$this->smarty->assign("content", $this->article());
		if($query['animation']==0)
			$tpl = 'modules/'.$this->folderName.'/html/view_'.$this->prefix.'_on.tpl';
		else
			$tpl = 'modules/'.$this->folderName.'/html/view_'.$this->prefix.'_off.tpl';

		$this->smarty->display($tpl);
	}
	
	public function article()
	{
		global $db;
		$content=array();
		$query=$db->query('SELECT title, content, views, stub, date, dateStart, changes, author, authorAlias, dateEnd, ID FROM cms_article WHERE category LIKE "%#??#'.$this->category.'#??#%" ORDER BY date asc LIMIT '.$this->number);
		while($art=$db->fetch($query)) {
			$temp = array();
			$temp['title']=$art['title'];
			$temp['date']=$art['date'];
			$temp['views']=$art['views'];
			$temp['changes']=$art['changes'];
			if($temp['image']!=0)
				$temp['image']='/images/article/'.$art['ID'].'/'.$art['image'];
			$temp['dateStart']=$art['dateStart'];
			$temp['dateEnd']=$art['dateEnd'];
			if($art['authorAlias'] == '')
				$temp['author']=$art['author'];
			else
				$temp['author']=$art['authorAlias'];
			$temp['content']=$this->cutText(htmlspecialchars_decode($art['content']), 300);
			$temp['stub']=$this->cutText(htmlspecialchars_decode($art['stub']), 300);
			$temp['more']='#';
			array_push($content, $temp);
		}
		return $content;
	}
}
?>