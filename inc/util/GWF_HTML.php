<?php
class GWF_HTML
{
	public static function escape(string $s=null) { return $s ? htmlspecialchars($s) : ''; }
	
}
