<?php
/**
 * Hidden fields are read-only by default.
 * @author gizmore
 * @since 5.0
 */
final class GDO_Hidden extends GDOType
{
    public $writable = false;
    
	public function renderCell() { return ''; }
	public function render() { return GWF_Template::mainPHP('form/hidden.php', ['field' => $this]); }
	public function renderFilter() { return GWF_Template::mainPHP('filter/hidden.php', ['field' => $this]); }
}
