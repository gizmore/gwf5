<?php
class GDO_ObjectSelect extends GDO_Select
{
	use GDO_ObjectTrait;

	public function initChoices()
	{
		return $this->choices ? $this : $this->choices($this->table->all());
	}
	
	public function render()
	{
		$this->initChoices();
		return parent::render();
	}
	
	public function validate($value)
	{
		$this->initChoices();
		return parent::validate($value);
	}
	
}
