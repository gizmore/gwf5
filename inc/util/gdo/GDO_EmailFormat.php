<?php
/**
 * Enum that switches between text und html format.
 * @author gizmore
 * @since 5.0
 */
final class GDO_EmailFormat extends GDO_Enum
{
	const TEXT = 'text';
	const HTML = 'html';
	
	public function __construct()
	{
		$this->enumValues(self::TEXT, self::HTML)->notNull()->initial(self::HTML);
	}

	public function defaultLabel() { return $this->label('email_fmt'); }
	
}
