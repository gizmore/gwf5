<?php
class GWF_LoginHistory extends GDO
{
	public function gdoCached() { return false; }
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('lh_id'),
			GDO_Object::make('lh_user_id')->klass('GWF_User')->name(),
			GDO_IP::make('lh_ip'),
			GDO_CreatedAt::make('lh_authenticated_at'),
		);
	}
}
