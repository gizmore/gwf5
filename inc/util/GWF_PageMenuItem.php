<?php
class GWF_PageMenuItem
{
	public $page;
	public $href;
	public $selected;
	
	public function __construct($page, $href, $selected)
	{
		$this->page = $page;
		$this->href = $href;
		$this->selected = $selected;
	}
	
	public static function dotted()
	{
		return new self('…', 'javascript:;', false);
	}
	
	public function htmlClass()
	{
		return $this->selected ? ' class="page-selected"' : '';
	}
}