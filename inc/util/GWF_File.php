<?php
class GWF_File extends GDO
{
	public static function isFile($filename)
	{
		return is_file($filename) && is_readable($filename);
	}
	public static function isDir($filename)
	{
		return is_dir($filename) && is_readable($filename);
	}
	###########
	### GDO ###
	###########
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('file_id')->label('id'),
			GDO_String::make('file_name')->notNull(),
			GDO_Int::make('file_size')->unsigned()->notNull(),
			GDO_String::make()
				
		);
	}
}