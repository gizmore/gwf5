<?php
/**
 * Scan the fonts dir for a select.
 * @author gizmore
 */
class GDO_Font extends GDO_Select
{
	/**
	 * TTF Font directory of php/gwf. 
	 */
	public static function directory() { return GWF_PATH . 'inc/fonts/'; }
	
	###
	public function __construct()
	{
		parent::__construct();
		$this->choices = $this->fontChoices();
	}
	
	public function fontChoices()
	{
		$fonts = GWF_File::scandir(self::directory());
		return array_combine($fonts, $fonts);
	}
}
