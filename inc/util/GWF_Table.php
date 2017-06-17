<?php
/**
 * Renders a GDOResult.
 * 
 * @author gizmore
 *
 */
class GWF_Table extends GWF_Response
{
	use GWF_Fields;
	
	const BOOTSTRAP = 0;
	const DATATABLE = 1;
	public static $MODE_TPL = ['table.php', 'datatable.php'];
	public $name = 'test';
	public $mode = 0;
	
	/**
	 * @var GWF_Method
	 */
	public $method;
	
	/**
	 * @var GDOResult
	 */
	public $result;
	public function __construct(string $param='table')
	{
		$this->param = $param;
// 		$this->href = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] . $this->hrefAppend() : '';
	}
	
	public function result(GDOResult $result)
	{
		$this->result = $result;
		return $this;
	}
	
	#############
	### Title ###
	#############
	private $title;
	public function title(string $key, array $args=null)
	{
		$this->title = t($key, $args);
		return $this;
	}
	
	############
	### Name ###
	############

	
	############
	### HREF ###
	############
	public $href;
	public $param = "page";
	public function href(string $href)
	{
		$this->href = $href; # . $this->hrefAppend();
		return $this;
	}
	
	public function param(string $param)
	{
		$this->param = $param;
		return $this;
	}
	
// 	public function hrefAppend()
// 	{
// // 		http_build_query($query_data)
// 		return sprintf('&%1$s[filter]=%%FILTER%%&%1$s[by]=%%BY%%&%1$s[dir]=%%DIR%%', $this->param);
// 	}
	
// 	public function getFilter()
// 	{
// 		return Common::getR
// 	}
	
// 	public function getSorting()
// 	{
		
// 	}

	################
	### PageMenu ###
	################
	/**
	 * @var GWF_PageMenu
	 */
	public $pagemenu;
	public function paginated(int $totalItems, int $ipp=50, $param='page')
	{
		$this->pagemenu = new GWF_PageMenu($totalItems, $ipp, $param);
		$this->pagemenu->href($this->href);
		return $this;
	}
		
	##############
	### Render ###
	##############
	public function render()
	{
		switch (GWF5::instance()->getFormat())
		{
			case 'json': return new GWF_Response($this->renderJSON());
			case 'html': default: return $this->renderHTML();
		}
	}
	
	public function renderJSON()
	{
		return array(
			'result' => $this->result->renderJSON(),
			'headers' => $this->renderHeadersJSON(),
			'pagemenu' => $this->pagemenu ? $this->pagemenu->renderJSON() : null,
		);
	}
	
	private function renderHeadersJSON()
	{
		$headers = [];
		foreach ($this->getFields() as $gdoType)
		{
			$headers[] = $gdoType->renderJSON();
		}
		return $headers;
	}
	
	public function renderHTML()
	{
		$tVars = array(
			'table' => $this,
			'result' => $this->result,
			'headers' => $this->getFields(),
			'pagemenu' => $this->pagemenu,
		);
		return GWF_Template::mainPHP(self::$MODE_TPL[$this->mode], $tVars);
	}
}
