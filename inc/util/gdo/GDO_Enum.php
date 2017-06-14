<?php
class GDO_Enum extends GDOType
{
	use GDO_NameLabelTrait;
	
	public $enumValues;
	
	public function enumValues(string ...$enumValues)
	{
		$this->enumValues = $enumValues;
		return $this;
	}
	
	public function gdoColumnDefine()
	{
		$values = implode(',', array_map(array('GDO', 'quoteS'), $this->enumValues));
		return "{$this->identifier()} ENUM ($values){$this->gdoNullDefine()}{$this->gdoInitialDefine()}";
	}

	public function render()
	{
		return GWF_Template::mainPHP('form/enum.php', ['field' => $this]);
	}

}