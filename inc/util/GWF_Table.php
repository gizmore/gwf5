<?php
class GWF_Table extends GWF_Response
{
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
		$this->title = GWF_Trans::t($key, $args);
		return $this;
	}

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
		$tVars = array(
			'table' => $this,
			'result' => $this->result,
			'headers' => $this->headers,
			'pagemenu' => $this->pagemenu,
		);
		return GWF_Template::templateMain('table.php', $tVars);
		
	}
}
