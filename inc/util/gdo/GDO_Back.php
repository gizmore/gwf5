<?php
final class GDO_Back extends GDO_Link
{
	public function defaultLabel() { return $this->label('link_back'); }
	public function __construct()
	{
		$this->name('back');
		$this->icon('undo');
		$this->href(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'javascript:window.history.back();');
	}
}
