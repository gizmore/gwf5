<?php
final class GWF_PublicKey extends GDO
{
	###########
	### GDO ###
	###########
	public function gdoDependencies() { return array('GWF_User'); }
	
	public function gdoColumns()
	{
		return array(
			GDO_User::make('gpg_uid')->primary(),
			GDO_Message::make('gpg_pubkey')->caseS()->ascii()->max(16384),
		);
	}

	##############
	### Static ###
	##############
	public static function removeKey(string $userid) { return self::table()->deleteWhere('gpg_uid='.(int)$userid); }
	public static function updateKey(string $userid, string $file_content) { return self::blank(['gpg_uid'=>$userid, 'gpg_pubkey'=>$file_content])->replace(); }
	public static function getKeyForUser(GWF_User $user) { return self::getKeyForUID($user->getID()); }
	public static function getKeyForUID(string $userid=null) { return self::table()->select('gpg_pubkey')->where('gpg_uid='.(int)$userid)->exec()->fetchValue(); }
	public static function getFingerprintForUser(GWF_User $user) { return self::getFingerprintForUID($user->getID()); }
	public static function getFingerprintForUID(string $userid)
	{
		if ($key = self::getKeyForUID($userid))
		{
			return self::grabFingerprint($key);
		}
	}
	
	/**
	 * Return a public key in hex format or false.
	 * @param string $key
	 */
	public static function grabFingerprint(string $file_content)
	{
		$gpg = gnupg_init();
		if (false === ($result = gnupg_import($gpg, $file_content))) {
			GWF_Log::logCritical('gnupg_import() failed');
			GWF_Log::logCritical(GWF_HTML::lang('ERR_GENERAL', __FILE__, __LINE__));
			return false;
		}
		if ( ($result['imported']+$result['unchanged']) === 0 ) {
			return false;
		}
		return $result['fingerprint'];
	}
}