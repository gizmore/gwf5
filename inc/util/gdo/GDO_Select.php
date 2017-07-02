<?php
class GDO_Select extends GDO_Combobox
{
	public function formValue()
	{
		if (null === ($value = parent::formValue()))
		{
			$value = $this->emptyValue;
		}
		return $value;
	}
	
	public function getGDOValue()
	{
		return json_decode($this->getValue());
	}
	
	public function addFormValue(GWF_Form $form, $value)
	{
		$value = $value === $this->emptyValue ? null : $value;
		return parent::addFormValue($form, $value);
	}
	
	public function validate($value)
	{
		return $this->multiple ? $this->validateMultiple($value) : $this->validateSingle($value);
	}
	
	private function validateMultiple($value)
	{
		$values = json_decode($value);
		foreach ($values as $value)
		{
			if (!$this->validateSingle($value))
			{
				return false;
			}
		}
		
		if ( ($this->minSelected !== null) && ($this->minSelected > count($values)) )
		{
			return $this->error('err_select_min', [$this->minSelected]);
		}
		
		if ( ($this->maxSelected !== null) && ($this->maxSelected < count($values)) )
		{
			return $this->error('err_select_max', [$this->maxSelected]);
		}
		
		return true;
	}
	
	private function validateSingle($value)
	{
		if ( ($this->null) && ($value === $this->emptyValue) )
		{
			return true;
		}
		
		if (!isset($this->choices[$value]))
		{
			return $this->error('err_invalid_choice');
		}
		return true;
	}
	
	################
	### Multiple ###
	################
	public $multiple = false;
	public function multiple()
	{
		$this->multiple = true;
		return $this;
	}
	
	public $minSelected;
	public $maxSelected;
	public function minSelected(int $minSelected)
	{
		$this->minSelected = $minSelected;
		return $this;
	}
	
	public function maxSelected(int $maxSelected)
	{
		$this->maxSelected = $maxSelected;
		return $this;
	}
	
	##############
	### Render ###
	##############
	public function render()
	{
		return GWF_Template::mainPHP('form/select.php', ['field' => $this]);
	}
	public function renderChoice()
	{
	}
}