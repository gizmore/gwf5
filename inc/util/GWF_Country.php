<?php
class GWF_Country extends GDO
{
	public function gdoTableName() { return 'gwf_country'; }
	public function gdoColumns()
	{
		return array(
			GDO_Char::make('c_iso')->label('id')->size(2)->ascii()->caseS()->primary(),
		);
	}
}