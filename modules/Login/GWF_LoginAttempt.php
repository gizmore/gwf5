<?php
class GWF_LoginAttempt extends GDO
{
	public function gdoTableName() { return 'gwf_login_attempt'; }
	
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('la_id'),
			GDO_Object::make('la_user_id')->klass('GWF_User')->notNull(),
			GDO_Time::make('la_time')->notNull(),
			GDO_IP::make('la_ip')->notNull(),
		);
	}
}