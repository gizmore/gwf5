<?php
/**
 * Cronjob that deletes old sessions.
 * 
 * @author gizmore
 * @version 5.0
 * @since 1.0
 * 
 * @see Login_Form
 * @see Login_Logout
 * @see Register_Activate
 * @see Register_Guest
 */
final class GWF_CleanupSessions extends GWF_MethodCronjob
{
	public function run()
	{
		$cut = time() - GWF_SESS_TIME;
		GWF_Session::table()->deleteWhere("sess_time < {$cut}")->exec();
		if (0 < ($deleted = GDODB::instance()->affectedRows()))
		{
			$this->log("Deleted $deleted sessions.");
		}
	}
}
