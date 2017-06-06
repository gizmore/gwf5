<?php
class GWF_Response
{
	protected $html;
	protected $error;
	
	public function __construct(string $html='', $error=false)
	{
		$this->html = $html;
		$this->error = $error;
	}
	
	public function add(GWF_Response $response)
	{
		$this->error = $response->error || $this->error;
		$this->html .= $response->html;
		return $this;
	}
	
	public static function error(string $key, array $args=null)
	{
		$html = GWF_Trans::t($key, $args);
		return new self($html, true);
	}
	
	public function __toString()
	{
		return $this->html ? $this->html : '';
	}
}