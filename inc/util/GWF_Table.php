<?php
/**
 * Renders a GDOResult.
 * 
 * @author gizmore
 *
 */
class GWF_Table extends GWF_Response
{
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
	private $result;
	public function __construct(GDOResult $result, string $param='table')
	{
		$this->result = $result;
		$this->param = $param;
		$this->href = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] . $this->hrefAppend() : '';
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

	###############
	### Headers ###
	###############
	/**
	 * @var GDOType[]
	 */
	private $headers = [];
	public function headers(array $headers)
	{
		$this->headers = $headers;
		return $this;
	}
	public function addHeader(GDOType $gdoType)
	{
		$this->headers[] = $gdoType;
		return $this;
	}
	
	
	
	############
	### HREF ###
	############
	private $href;
	private $param;
	public function href(string $href)
	{
		$this->href = $href . $this->hrefAppend();
		return $this;
	}
	
	public function param(string $param)
	{
		$this->param = $param;
		return $this;
	}
	
	public function hrefAppend()
	{
		return sprintf('&%1$s[filter]=%%FILTER%%&%1$s[by]=%%BY%%&%1$s[dir]=%%DIR%%', $this->param);
	}

	################
	### PageMenu ###
	################
	/**
	 * @var GWF_PageMenu
	 */
	private $pagemenu;
	public function paginated(int $totalItems, int $ipp=50)
	{
		$this->pagemenu = new GWF_PageMenu($totalItems, $ipp, $this->param);
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
		foreach ($this->headers as $gdoType)
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
			'headers' => $this->headers,
			'pagemenu' => $this->pagemenu,
		);
		return GWF_Template::mainPHP(self::$MODE_TPL[$this->mode], $tVars);
	}
}
