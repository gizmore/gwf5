<?php
class GDO_Table extends GDO_Blank
{
	use GWF_Fields;
	use GDO_HREFTrait;
	
	private $sortable;
	private $sortableURL;
	public function sortable(string $sortableURL=null)
	{
		$this->sortable = $sortableURL !== null;
		$this->sortableURL = $sortableURL;
		return $this;
	}
	
	public $filtered;
	public function filtered(bool $filtered=true)
	{
		$this->filtered = $filtered;
		return $this;
	}
	
	public function paginateDefault()
	{
		return $this->paginate(true, Module_GWF::instance()->cfgItemsPerPage());
	}
	
	private $pagemenu;
	public function paginate(bool $paginate=true, int $ipp=10)
	{
		if ($paginate)
		{
			$this->pagemenu = GDO_PageMenu::make($this->name.'_page');
			$this->pagemenu->ipp($ipp);
		}
		return $this->ipp($ipp);
	}
	
	private $ipp = 10;
	public function ipp(int $ipp)
	{
		$this->ipp = $ipp;
		return $this;
	}
	
	public $result;
	public function result(GDOResult $result)
	{
		if (!$this->fetchAs)
		{
			$this->fetchAs = $result->table;
		}
		$this->result = $result;
		return $this;
	}
	
	/**
	 * @return GDOResult
	 */
	public function getResult()
	{
		if (!($this->result))
		{
			if (!($this->result = $this->queryResult()))
			{
				return new GDOArrayResult([]);
			}
		}
		return $this->result;
	}
		
	public $query;
	public function query(GDOQuery $query)
	{
		if (!$this->fetchAs)
		{
			$this->fetchAs = $query->table;
		}
		$this->query = $query;
		return $this;
	}
	
	public function getQuery()
	{
		return $this->query->clone();
	}
	
	public function countItems()
	{
		if ($this->query)
		{
			return $this->getQuery()->select('COUNT(*)')->exec()->fetchValue();
		}
		else
		{
			return $this->getResult()->numRows();
		}
	}
	
	public function queryResult()
	{
		if ($this->pagemenu)
		{
			$this->pagemenu->filterQuery($this->query);
		}
		if ($this->filtered)
		{
			foreach ($this->getFields() as $gdoType)
			{
				$gdoType->filterQuery($this->query);
			}
		}
		return $this->query->exec();
	}
	
	/**
	 * @return GDO_PageMenu
	 */
	public function getPageMenu()
	{
		if ($this->pagemenu)
		{
			$this->pagemenu->items($this->countItems());
			$this->pagemenu->href($this->href);
		}
		return $this->pagemenu;
	}
	
	public $fetchAs;
	public function fetchAs(GDO $fetchAs=null)
	{
		$this->fetchAs = $fetchAs;
		return $this;
	}
	
	###############
	### Actions ###
	###############
	private $actions;
	public function actions()
	{
		if (!$this->actions)
		{
			$this->actions = GDO_Bar::make();
		}
		return $this->actions;
	}
	
	##############
	### Render ###
	##############
	public function renderCell()
	{
		return $this->render()->getHTML();
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('cell/table.php', ['field'=>$this]);
	}
	
	public function initJSON()
	{
		return json_encode(array(
			'tableName' => $this->result->table->gdoClassName(),
			'pagemenu' => $this->pagemenu ? $this->getPageMenu()->initJSON() : null,
			'sortable' => $this->sortable,
			'sortableURL' => $this->sortableURL,
		));
	}
}
