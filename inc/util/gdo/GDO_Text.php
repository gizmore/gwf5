<?php
class GDO_Text extends GDO_String
{
	public $max = 4096;
	
	public function gdoColumnDefine()
	{
		$charset = $this->gdoCharsetDefine();
		$collate = $this->gdoCollateDefine();
		return "{$this->identifier()} TEXT({$this->max}) CHARSET $charset COLLATE $collate{$this->gdoNullDefine()}";
	}
	
}
