<?php
class GDO_Gender extends GDO_Enum
{
	const NONE = 'no_gender';
	const MALE = 'male';
	const FEMALE = 'female';
	
	public function __construct()
	{
		$this->enumValues(self::MALE, self::FEMALE, SELF::NONE);
	}
	
	public function defaultLabel() { return $this->label('gender'); }
}
