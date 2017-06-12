<?php
/**
 * File utility and database storage.
 * @author gizmore
 * @version 5.0
 * @sinve 3.0
 */
class GWF_File extends GDO
{
	###########
	### GDO ###
	###########
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('file_id')->label('id'),
			GDO_String::make('file_name')->notNull(),
			GDO_Int::make('file_size')->unsigned()->notNull(),
			GDO_String::make('file_type')->ascii()->caseS()->notNull(),
		);
	}
	
	public function getID() { return $this->getVar('file_id'); }
	public function getName() { return $this->getVar('file_name'); }
	public function getSize() { return $this->getVar('file_size'); }
	public function getType() { return $this->getVar('file_type'); }
	
	public function getPath() { return self::filesDir() . $this->getID(); }
	
	###############
	### Factory ###
	###############
	public static function filesDir()
	{
		return GWF_PATH . 'dbimg/files/';
	}
	
	public static function singleFromForm(array $filesValues)
	{
		return self::fromForm($filesValues[0]);
	}
	
	public static function multipleFromForm(array $filesValues)
	{
		$files = [];
		foreach ($filesValues as $values)
		{
			if ($file = self::fromForm($values))
			{
				$files[] = $file;
			}
		}
		return $files;
	}
	
	/**
	 * @param array $values
	 * @return GWF_File
	 */
	public static function fromForm(array $values)
	{
		GWF_File::createDir(self::filesDir());
		$file = self::blank(['file_name'=>$values['name'], 'file_size' => $values['size'], 'file_type' => $values['mime']])->insert();
		copy($values['path'], $file->getPath());
		return $file;
	}

	############
	### Util ###
	############
	public static function isFile(string $filename) { return is_file($filename) && is_readable($filename); }
	public static function isDir(string $filename) { return is_dir($filename) && is_readable($filename); }
	public static function createDir(string $path) { return self::isDir($path) ? true : @mkdir($path, GWF_CHMOD, true); }
	public static function dirsize(string $path)
	{
		$bytes = 0;
		$path = realpath($path);
		if (self::isDir($path))
		{
			foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $file)
			{
				$bytes += $file->getSize();
			}
		}
		return $bytes;
		
	}
	
	public static function scandir(string $dir)
	{
		$files = array_slice(scandir($dir), 2);
		return $files;
	}
	
	public static function removeDir(string $dir)
	{
		if (is_dir($dir))
		{
			$objects = scandir($dir);
			foreach ($objects as $object)
			{
				if ($object != "." && $object != "..")
				{
					if (is_dir($dir."/".$object))
					{
						self::removeDir($dir."/".$object);
					}
					else
					{
						unlink($dir."/".$object);
					}
				}
			}
			rmdir($dir);
		}
	}
	
}
