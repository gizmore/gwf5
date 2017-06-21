<?php
final class GDO_Anchor extends GDO_Button
{
	public function render()
	{
		return GWF_HTML::anchor($this->href, $this->label);
	}
	
	public function renderCell()
	{
		return GWF_HTML::anchor($this->gdoHREF(), $this->gdoLabel());
	}
}
