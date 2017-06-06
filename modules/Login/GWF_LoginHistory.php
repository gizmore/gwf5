<?php
class GWF_LoginHistory extends GDO
{
	public function gdoTableName() { return 'gwf_login_history'; }
	
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
