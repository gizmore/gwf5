<?php
class GDO_Binary extends GDOType
{
	private $sizet = '';
	
	public function tiny() { $this->sizet = 'TINY'; return $this; }
	public function medium() { $this->sizet = 'MEDIUM'; return $this; }
	public function long() { $this->sizet = 'LONG'; return $this; }
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} {$this->sizet}BLOB {$this->gdoNullDefine()}";
	}

	public function render()
	{
		return GWF_Template::mainPHP('form/binary.php', ['field'=>$this]);
	}

}