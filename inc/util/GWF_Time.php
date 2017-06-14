<?php
/**
 * GWF_Time helper class.
 * Note: If you want dates display with different dateformats you can try to substr() or str_repeat() your gwf_date to appropiate lengths.
 * TODO: Make ready for applications that don't use base/lang files.
 * TODO: Split to different files. GWF_Date, GWF_Validator, GWF_TimeConvert, GWF_Duration?
 * @author gizmore
 * @version 2.92
 */
final class GWF_Time
{
	private static $CACHE = array(); # Date language cache

	const ONE_SECOND = 1;
	const ONE_MINUTE = 60;
	const ONE_HOUR = 3600;
	const ONE_DAY = 86400;
	const ONE_WEEK = 604800;
	const ONE_MONTH = 2592000;
	const ONE_YEAR = 31536000;

	###############
	### Display ###
	###############
	/**
	 * Display a timestamp.
	 * TODO: Function is slow
	 * @see GWF_Time::displayDate
	 * @param $timestamp
	 * @param $langid
	 * @param $default_return
	 * @return unknown_type
	 */
	public static function displayTimestamp($timestamp=null, string $iso=null, string $default_return='err_no_timestamp')
	{
	}


	/**
	 * Get the dateformat language cache for an ISO.
	 * @param string $iso
	 * @return array
	 */
	private static function getCache($iso)
	{
		if (!isset(self::$CACHE[$iso]))
		{
			self::$CACHE[$iso] = GWF_Trans::tiso($iso, 'datecache');
		}
		return self::$CACHE[$iso];
	}


	/**
	 * Compute an age, in years, from a date compared to current date.
	 * @param $birthdate
	 * @return int -1 on error
	 */
	public static function getAge($birthdate)
	{
		if ((strlen($birthdate) !== 8) || ($birthdate === '00000000')) {
			return -1;
		}
		$now = (int)date('Ymd');
		$birthdate = (int) $birthdate;
		$age = $now - $birthdate;
		return intval($age / 10000, 10);
	}

	public static function displayAge($gwf_date)
	{
		return self::displayAgeTS(self::getTimestamp($gwf_date));
	}

	public static function displayAgeTS($timestamp)
	{
		return self::humanDuration(time()-round($timestamp));
	}
	
	public static function displayAgeISO($gwf_date, $iso)
	{
		return self::displayAgeTSISO(self::getTimestamp($gwf_date), $iso);
	}

	public static function displayAgeTSISO($timestamp, $iso)
	{
		return self::humanDurationISO($iso, time()-round($timestamp));
	}
	
	################
	### Duration ###
	################

	public static function humanDurationEN($duration, $nUnits=2)
	{
		static $units = true;
		if ($units === true) {
			$units = array('s' => 60,'m' => 60,'h' => 24,'d' => 365,'y' => 1000000);
		}
		return self::humanDurationRaw($duration, $nUnits, $units);
	}


	public static function humanDurationISO($iso, $duration, $nUnits=2)
	{
		static $cache = array();
		if (!isset($cache[$iso]))
		{
			$cache[$iso] = array(
				GWF_HTML::langISO($iso, 'unit_sec_s') => 60,
				GWF_HTML::langISO($iso, 'unit_min_s') => 60,
				GWF_HTML::langISO($iso, 'unit_hour_s') => 24,
				GWF_HTML::langISO($iso, 'unit_day_s') => 365,
				GWF_HTML::langISO($iso, 'unit_year_s') => 1000000,
			);
		}
		return self::humanDurationRaw($duration, $nUnits, $cache[$iso]);
	}


	public static function humanDurationRaw($duration, $nUnits=2, array $units)
	{
		$duration = (int)$duration;
		$calced = array();
		foreach ($units as $text => $mod)
		{
			if (0 < ($remainder = $duration % $mod)) {
				$calced[] = $remainder.$text;
			}
			$duration = intval($duration / $mod);
			if ($duration === 0) {
				break;
			}
		}

		if (count($calced) === 0) {
			return '0'.key($units);
		}

		$calced = array_reverse($calced, true);
		$i = 0;
		foreach ($calced as $key => $value)
		{
			$i++;
			if ($i > $nUnits) {
				unset($calced[$key]);
			}
		}

		return implode(' ', $calced);
	}


	/**
	 * Return a human readable duration.
	 * Example: 666 returns 11 minutes 6 seconds.
	 * @param $duration in seconds.
	 * @param $nUnits how many units to display max.
	 * @return string
	 */
	public static function humanDuration($duration, $nUnits=2)
	{
		return self::humanDurationISO(GWF_Language::getCurrentISO(), $duration, $nUnits);
	}

}

