<?php
class GWF_File extends GDO
{
	public static function isFile($filename)
	{
		return is_file($filename) && is_readable($filename);
	}
	
	###########
	### GDO ###
	###########
	
	public function gdoTableName() { return 'gwf_files'; }
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('file_id')->label('id'),
		);
	}
}