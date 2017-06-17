<?php
/**
 * Empty does nothing field.
 * Useful in table headers.
 * 
 * @author gizmore
 * @since 5.0
 * @see GDO_Label
 */
class GDO_Blank extends GDOType
{
	public function blankData() {}
	public function render() { return ''; }
	public function addFormValue(GWF_Form $form, $value) {}
}
