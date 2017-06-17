<?php
/**
 * Scan the fonts dir for a select.
 * @author gizmore
 */
class GDO_Font extends GDO_Select
{
	public function defaultLabel() { return $this->label('font'); }
	
	/**
	 * TTF Font directory of php/gwf. 
	 */
	public static function directory() { return GWF_PATH . 'inc/fonts/'; }
	
	public function render()
	{
		$this->choices = $this->fontChoices();
		return parent::render();
	}
	
	public function fontChoices()
	{
		$fonts = GWF_File::scandir(self::directory());
		return array_combine($fonts, $fonts);
	}
}
