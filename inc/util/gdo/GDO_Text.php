<?php
/**
 * The text gdoType exceeds the varchar.
 * It is displayed in a textarea like form field.
 * The cell rendering should be dottet.
 * 
 * @author gizmore
 *
 */
class GDO_Text extends GDO_String
{
	public $max = 4096;
	
	public function gdoColumnDefine()
	{
		$charset = $this->gdoCharsetDefine();
		$collate = $this->gdoCollateDefine();
		return "{$this->identifier()} TEXT({$this->max}) CHARSET $charset $collate{$this->gdoNullDefine()}";
	}
}
