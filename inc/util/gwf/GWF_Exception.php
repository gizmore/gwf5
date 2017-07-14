<?php
class GWF_Exception extends Exception
{
	public function __construct(string $key, array $args=null)
	{
		parent::__construct(t($key, $args), 500);
	}
}
