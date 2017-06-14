<?php
class GDO_Char extends GDO_String
{
	public $encoding = self::ASCII;
	public $caseSensitive = true;
	
	public function size(int $size)
	{
		$this->min = $this->max = $size;
		return $this;
	}

	public function gdoColumnDefine()
	{
		return
		"{$this->identifier()} CHAR({$this->max}) CHARSET {$this->gdoCharsetDefine()} {$this->gdoCollateDefine()}" .
		$this->gdoNullDefine() . $this->gdoInitialDefine();
	}
}
