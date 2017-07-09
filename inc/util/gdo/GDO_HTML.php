<?php
class GDO_HTML extends GDO_Blank
{
	public function render() { return GWF_Response::make($this->label); }
	public function renderCell() { return $this->label; }
	public function renderChoice() { return $this->label; }
	
	public function content(string $content)
	{
		return $this->rawlabel($content);
	}
}
