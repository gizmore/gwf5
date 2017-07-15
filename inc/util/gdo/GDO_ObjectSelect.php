<?php
class GDO_ObjectSelect extends GDO_Select
{
	use GDO_ObjectTrait;

	public function toJSON()
	{
		if ($gdo = $this->getGDOValue())
		{
			return array($this->name => $gdo->toJSON());
		}
		return array($this->name => null);
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
	
	public function initChoices()
	{
		return $this->choices ? $this : $this->choices($this->table->all());
	}
	
	#################
	### Get Value ###
	#################
	public function getGDOValue()
	{
		return $this->multiple ? $this->getGDOValueMulti() : $this->getGDOValueSingle();
	}

	public function getGDOValueSingle()
	{
		$id = $this->gdo ? $this->gdo->getVar($this->name) : $this->formValue();
		return $this->foreignTable()->find($id, false);
	}

	public function getGDOValueMulti()
	{
		$back = [];
		$fkTable = $this->foreignTable();
		foreach (json_decode($this->getValue()) as $id)
		{
			if ($object = $fkTable->find($id, false))
			{
				$back[$id] = $object;
			}
		}
		return $back;
	}
	
}
