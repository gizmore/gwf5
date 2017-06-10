<?php
class GDO_Combobox extends GDO_String
{
	public function __construct()
	{
		
	}
	
	###############
	### Choices ###
	###############
	public $choices;
	public function choices(array $choices)
	{
		$this->choices = $choices;
		return $this;
	}
	
	public $emptyValue = "0";
	public $emptyChoice;
	public function emptyChoice(string $key, array $args=null, $value="0")
	{
		$this->emptyValue = $value;
		$this->emptyChoice = t($key, $args);
		return $this;
	}
	public function emptyChoiceValue()
	{
		return $this->emptyValue;
	}
	
	public function emptyChoiceLabel()
	{
		return $this->emptyChoice ? $this->emptyChoice : t('no_selection');
	}
	
	################
	### Validate ###
	################
	public function validate($value)
	{
		if ( ($this->null) && ($value == $this->emptyChoice) )
		{
			return true;
		}
		if ($this->isValidChoice($value))
		{
			return true;
		}
		return parent::validate($value);
	}
	
	public function isValidChoice($value)
	{
		return isset($this->choices[$value]);
	}
	
	public function validateChoice($value)
	{
		if (!$this->isValidChoice($value))
		{
			return $this->error('err_invalid_choice');
		}
		return true;
	}
}