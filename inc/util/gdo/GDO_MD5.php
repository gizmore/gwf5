<?php
final class GDO_MD5 extends GDO_Char
{
	public $encoding = self::BINARY;
	public $caseSensitive = true;
	
	public function __construct()
	{
		$this->size(16);
	}
	
	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/md5', ['field'=>$this]);
	}
}
