<?php
class GWF_PageMenu
{
	public static function pagecount(int $numItems, int $itemsPerPage)
	{
		return max(array(intval((($numItems-1) / $itemsPerPage)+1), 1));
	}
	
	public $param;
	public $items;
	public $ipp;
	public $pages;
	public $page;

	public function __construct(int $totalItems, int $itemsPerPage=50, string $paramName='page')
	{
		$this->items = $totalItems;
		$this->ipp = $itemsPerPage;
		$this->param = $paramName;
		$this->pages = self::pagecount($totalItems, $itemsPerPage);
		$this->page = GWF_Math::clamp(Common::getRequestInt($this->param, 1), 1, $this->pages);
		
		$this->href = $_SERVER['REQUEST_URI'] . $this->hrefAppend();
	}
	
	public function getFrom()
	{
		return $this->page * $this->ipp;
	}
	
	############
	### HREF ###
	############
	public $href;
	public function href(string $href)
	{
		$this->href = $href . $this->hrefAppend();
		return $this;
	}
	
	public function hrefAppend()
	{
		return sprintf('&%1$s[page]=%%PAGE%%', $this->param);
	}
	
	public function replaceHREF(int $page)
	{
		return str_replace('%PAGE%', $page, $this->href);
	}
	
	#############
	### Shown ###
	#############
	public $shown = 5;
	public function shown(int $shown)
	{
		$this->shown = $shown;
		return $this;
	}
	
	##############
	### Render ###
	##############
	public function render()
	{
		$tVars = array(
			'pagemenu' => $this,
			'pages' => $this->pagesObject(),
		);
		return GWF_Template::templateMain('pagemenu.php', $tVars);
	}
	
	private function pagesObject()
	{
		return array(
			new GWF_PageMenuItem(1, $this->replaceHref(1), $this->page == 1),
			GWF_PageMenuItem::dotted(),
			GWF_PageMenuItem::dotted(),
			new GWF_PageMenuItem(8, $this->replaceHref(8), $this->page == 8),
			new GWF_PageMenuItem(8, $this->replaceHref(8), $this->page == 8),
			new GWF_PageMenuItem(8, $this->replaceHref(8), $this->page == 8),
			GWF_PageMenuItem::dotted(),
			GWF_PageMenuItem::dotted(),
			new GWF_PageMenuItem(400, $this->replaceHref(400), $this->page == 400),
		);
	}
}