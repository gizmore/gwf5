<?php
class GDO_Path extends GDO_String
{
	public function htmlClass()
	{
		return GWF_File::isFile($this->getValue()) ? ' class="gwf-file-valid"' : ' class="gwf-file-invalid"';
	}

	public function defaultLabel() { return $this->label('path'); }
}
