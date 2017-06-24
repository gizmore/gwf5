<?php
/**
 * HTTP Url field.
 * Features link checking.
 * 
 * @author gizmore
 * @since 5.0
 * @version 5.0
 */
class GDO_Url extends GDO_String
{
	public function defaultLabel() { return $this->label('url'); }
	
	public $reachable = false;
	public $allowLocal = false;
	
	public $min = 0;
	public $max = 255;
	public $pattern = "#(?:https?://|/).*#i";
	
	public function allowLocal(bool $allowLocal=true)
	{
		$this->allowLocal = $allowLocal;
		return $this;
	}
	
	public function reachable(bool $reachable=true)
	{
		$this->reachable = $reachable;
		return $this;
	}

	public function validate($value)
	{
		return parent::validate($value) ? $this->validateUrl($value) : false;
	}
	
	public function validateUrl($value)
	{
		if ( (!$this->allowLocal) && ($value[0] === '/') )
		{
			return $this->error('err_local_url_not_allowed', [htmlspecialchars($value)]);
		}
		if ( ($this->reachable) && (!GWF_HTTP::pageExists($value)) )
		{
			return $this->error('err_url_not_reachable', [htmlspecialchars($value)]);
		}
		return true;
	}
}
