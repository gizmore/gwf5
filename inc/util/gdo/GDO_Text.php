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
	public function defaultLabel() { return $this->label('message'); }
	
	public $max = 4096;
	
	public function gdoColumnDefine()
	{
		return "{$this->identifier()} TEXT({$this->max}) CHARSET {$this->gdoCharsetDefine()} {$this->gdoCollateDefine()}{$this->gdoNullDefine()}";
	}
}
