<?php
/**
 * Apache and NGinx HTAccess utility.
 * @author gizmore
 * @since 2.0
 * @version 5.0
 */
final class HTAccess
{
	public static function make(string $dir)
	{
		return new self($dir);
	}

	private $dir;
	
	private function __construct(string $dir)
	{
		$this->dir = $dir;
	}
	
	public function isProtected()
	{
		
	}
}
