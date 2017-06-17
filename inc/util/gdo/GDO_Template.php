<?php
final class GDO_Template extends GDO_Blank
{
	public $module;
	public function module(GWF_Module $module)
	{
		$this->module = $module;
		return $this;
	}
	
	public $file;
	public $tVars;
	public function template(string $file, array $tVars=null)
	{
		$this->file = $file;
		$this->tVars = $tVars;
		return $this;
	}
	
	public function render()
	{
		return $this->module ?
			GWF_Template::modulePHP($this->module->getName(), $this->file, $this->tVars) : 
			GWF_Template::mainPHP($this->file, $this->tVars);
	}

}