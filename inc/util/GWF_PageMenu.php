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
		return ($this->page - 1) * $this->ipp;
	}
	
	############
	### HREF ###
	############
	public $href;
	public function href(string $href)
	{
		$search = '&' . $this->param . '=' . Common::getRequestString($this->param, '');
		$this->href = str_replace($search, '', $href) . $this->hrefAppend();
		return $this;
	}
	
	public function hrefAppend()
	{
		return sprintf('&%1$s=%%PAGE%%', $this->param);
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
		switch (GWF5::instance()->getFormat())
		{
			case 'json': return $this->renderJSON();
			case 'html': default: return $this->renderHTML();
		}
	}
	
	public function renderJSON()
	{
		return array(
			'items' => $this->items,
			'ipp' => $this->ipp,
			'page' => $this->page,
			'pages' => $this->pages,
		);
	}

	public function renderHTML()
	{
		if ($this->pages > 1)
		{
			$tVars = array(
				'pagemenu' => $this,
				'pages' => $this->pagesObject(),
			);
			return GWF_Template::mainPHP('pagemenu.php', $tVars);
		}
	}
	
	
	private function pagesObject()
	{
		$pages = [];
		$pages[] = new GWF_PageMenuItem($this->page, $this->replaceHREF($this->page), true);
		for ($i = 1; $i <= 4; $i++)
		{
			$page = $this->page - $i;
			if ($page > 0)
			{
				array_unshift($pages, new GWF_PageMenuItem($page, $this->replaceHREF($page)));
			}
			$page = $this->page + $i;
			if ($page <= $this->pages)
			{
				$pages[] = new GWF_PageMenuItem($page, $this->replaceHREF($page));
			}
		}
		return $pages;
	}
}