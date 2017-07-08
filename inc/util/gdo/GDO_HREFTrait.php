<?php
trait GDO_HREFTrait
{
	public $href; # = 'javascript:;';
	public function htmlHREF() { return sprintf(' href="%s"', GWF_HTML::escape($this->href)); }
	public function href(string $href)
	{
		$this->href = $href;
		return $this;
	}
}
