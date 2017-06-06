<?php
class GWF_PageMenuItem
{
	public $page;
	public $href;
	public $selected;
	
	public static function dotted()
	{
		return new self(0, '#', false);
	}
	
	public function htmlClass()
	{
		return $this->selected ? ' class="page-selected"' : '';
	}
}