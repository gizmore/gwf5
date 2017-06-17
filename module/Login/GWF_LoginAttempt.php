<?php
/**
 * Database table for login attempts.
 * 
 * @author gizmore
 * @since 2.0
 *
 */
class GWF_LoginAttempt extends GDO
{
	public function gdoCached() { return false; }
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('la_id'),
			GDO_IP::make('la_ip')->notNull(),
			GDO_Object::make('la_user_id')->klass('GWF_User'),
			GDO_CreatedAt::make('la_time'),
		);
	}
}
