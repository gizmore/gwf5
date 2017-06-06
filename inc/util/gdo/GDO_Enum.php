<?php
class GDO_Enum extends GDOType
{
	public $enumValues;
	
	public function enumValues(...$enumValues)
	{
		$this->enumValues = $enumValues;
		return $this;
	}
	
	public function gdoColumnDefine()
	{
		$values = implode(',', array_map(array('GDO', 'quoteS'), $this->enumValues));
		return "{$this->identifier()} ENUM ($values){$this->gdoNullDefine()}{$this->gdoInitialDefine()}";
	}
}