<?php
class GDO_PageMenu extends GDO_Blank
{
	use GDO_HREFTrait;
	
	private $numItems = 0;
	public function items(int $numItems)
	{
		$this->numItems = $numItems;
		return $this;
	}
	
	private $ipp = 10;
	public function ipp(int $ipp)
	{
		$this->ipp = $ipp;
		return $this;
	}
	
	public function getPages()
	{
	    return self::getPageCountS($this->numItems, $this->ipp);
	}
	
	public static function getPageCountS($numItems, $ipp)
	{
	    return max(array(intval((($numItems-1) / $ipp)+1), 1));
	}
	
	public $shown = 4;
	public function shown(int $shown)
	{
		$this->shown = $shown;
		return $this;
	}
	
	
	public function filterQuery(GDOQuery $query)
	{
		$query->limit($this->ipp, $this->getFrom());
	}
	
	public function getPage()
	{
		return GWF_Math::clamp($this->filterValue(), 1, $this->getPages());
	}

	public function getFrom()
	{
	    return self::getFromS($this->getPage(), $this->ipp);
	}
	
	public static function getFromS($page, $ipp)
	{
	    return ($page - 1) * $ipp;
	}

	##############
	### Render ###
	##############
	public function initJSON()
	{
		return $this->renderJSON();
	}
	
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
			'href' => $this->href,
			'items' => $this->numItems,
			'ipp' => $this->ipp,
			'page' => $this->getPage(),
			'pages' => $this->getPages(),
		);
	}
	
	public function renderHTML()
	{
		if ($this->getPages() > 1)
		{
			$tVars = array(
				'pagemenu' => $this,
				'pages' => $this->pagesObject(),
			);
			return GWF_Template::mainPHP('pagemenu.php', $tVars);
		}
	}
	
	private function replaceHREF($page)
	{
		$this->href = preg_replace("#&f\\[{$this->name}\\]=\\d+#", '', $this->href);
		return $this->href . '&f[' . $this->name . ']='. $page;
	}
	
	private function pagesObject()
	{
		$curr = $this->getPage();
		$nPages = $this->getPages();
		$pages = [];
		$pages[] = new GWF_PageMenuItem($curr, $this->replaceHREF($curr), true);
		for ($i = 1; $i <= $this->shown; $i++)
		{
			$page = $curr- $i;
			if ($page > 0)
			{
				array_unshift($pages, new GWF_PageMenuItem($page, $this->replaceHREF($page)));
			}
			$page = $curr+ $i;
			if ($page <= $nPages)
			{
				$pages[] = new GWF_PageMenuItem($page, $this->replaceHREF($page));
			}
		}
		return $pages;
	}
}
