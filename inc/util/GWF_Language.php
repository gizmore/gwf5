<?php
class GWF_Language extends GDO
{
	public function gdoTableName() { return 'gwf_language'; }
	public function gdoColumns()
	{
		return array(
			GDO_Char::make('lang_id')->primary()->size(2),
		);
	}
}