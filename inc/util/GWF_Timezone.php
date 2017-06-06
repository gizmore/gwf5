<?php
class GWF_Timezone extends GDO
{
	###########
	### GDO ###
	###########
	public function gdoTableName() { return 'gwf_timezone'; }
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('tz_id'),
			GDO_Name::make('tz_name'),
		);
	}
}