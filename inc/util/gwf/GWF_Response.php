<?php
/**
 * Generic response wrapper which can chain more response.
 * 
 * @author gizmore
 * @see GWF_Error
 * @see GWF_Message
 */
class GWF_Response
{
	protected $html;
	protected $error;
	
	/**
	 * @param unknown $html
	 * @return self
	 */
	public static function make($html) { return new self($html); }
	public static function message(string $key, array $args=null) { return new GWF_Message($key, $args); }
	public static function error(string $key, array $args=null) { return new GWF_Error($key, $args); }
	
	public function __construct($html, bool $error=false)
	{
		$this->html = $html;
		$this->error = $error;
	}
	
	public function isError()
	{
		return $this->error;
	}
	
	public function getHTML()
	{
		return $this->html;
	}
	
	public function pre(GWF_Response $response=null)
	{
		if (!$response)
		{
			$response = $this;
		}
		else
		{
			$response->add($this);
		}
		return $response;
	}
	
	public function add(GWF_Response $response=null)
	{
		if ($response)
		{
			$this->error = $response->error || $this->error;
			if (empty($this->html))
			{
				$this->html = $response->html;
			}
			elseif (is_array($this->html))
			{
				if (!empty($response->html))
				{
					$this->html = array_merge($this->html, $response->html);
				}
			}
			else
			{
				$this->html .= $response->html;
			}
		}
		return $this;
	}
	
	public function __toString()
	{
		switch (GWF5::instance()->getFormat())
		{
			default:
			case 'html': return $this->html ? $this->html : '';
			case 'json': return $this->toJSON();
			case 'ws': return $this->replyWS();
		}
	}
	
	public function render()
	{
		return $this->__toString();
	}
	
	public function toJSON()
	{
		return json_encode(array(
			'data' => $this->html,
			'error' => $this->error,
		));
	}
}
