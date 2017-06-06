<?php
class GDO_IP extends GDO_String
{
	public static $CURRENT = null;

	public static function current() { return self::$CURRENT; }
	
	public function __construct()
	{
		$this->min = 3;
		$this->max = 45;
		$this->encoding = self::ASCII;
		$this->caseSensitive = true;
		$this->pattern = "/^[.:0-9a-f]{3,45}$/i";
	}
	
}

GDO_IP::$CURRENT = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
