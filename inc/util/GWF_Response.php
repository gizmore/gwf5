<?php
class GWF_Response
{
	protected $html;
	protected $error;
	
	public static function make($html)
	{
		return new self($html);
	}
	
	public function __construct($html, $error=false)
	{
		$this->html = $html;
		$this->error = $error;
	}
	
	public function getHTML()
	{
		return $this->html;
	}
	
	public function add(GWF_Response $response)
	{
		$this->error = $response->error || $this->error;
		if (is_array($this->html))
		{
			$this->html = array('data' => $response->html);
		}
		else
		{
			$this->html .= $response->html;
		}
		return $this;
	}
	
	public static function error(string $key, array $args=null)
	{
		$html = t($key, $args);
		return new self($html, true);
	}
	
	public function __toString()
	{
		switch (GWF5::instance()->getFormat())
		{
			default:
			case 'html': return $this->html ? $this->html : '';
			case 'json': return $this->toJSON();
		}
	}
	
	public function toJSON()
	{
		return json_encode(array(
			'data' => $this->html,
			'error' => $this->error,
		));
	}
}
