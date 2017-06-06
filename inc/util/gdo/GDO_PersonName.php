<?php
/**
 * A persons real name.
 * Pattern is character or space.
 * @author gizmore
 * @since 5.0
 */
class GDO_PersonName extends GDO_String
{
	public function __construct()
	{
		$this->min = 1;
		$this->max = 128;
		$this->pattern = "/[\\w ]+/d";
		$this->encoding = self::UTF8;
		$this->caseSensitive = false;
	}
}
