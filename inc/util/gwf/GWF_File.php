<?php
/**
 * File utility and database storage.
 * @author gizmore
 * @version 5.0
 * @sinve 3.0
 * 
 * @see GDO_File
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
			GDO_String::make('file_type')->ascii()->caseS()->notNull()->max(96),
			GDO_Filesize::make('file_size')->notNull(),
			GDO_Int::make('file_width')->unsigned(),
			GDO_Int::make('file_height')->unsigned(),
			GDO_Int::make('file_bitrate')->unsigned(),
			GDO_Duration::make('file_duration'),
		);
	}
	
	public function getID() { return $this->getVar('file_id'); }
	public function getName() { return $this->getVar('file_name'); }
	public function getSize() { return $this->getVar('file_size'); }
	public function getType() { return $this->getVar('file_type'); }
	public function displaySize() { return self::humanFilesize($this->getSize()); }
	
	private $path;
	public function tempPath(string $path=null)
	{
		$this->path = $path;
		return $this;
	}
	
	public function getPath() { return $this->path ? $this->path : $this->getDestPath(); }
	public function getDestPath() { return self::filesDir() . $this->getID(); }
	
	public function renderCell() { return GWF_Template::mainPHP('cell/file.php', ['gdo'=>$this]); }
	
	public function delete()
	{
		@unlink($this->getDestPath());
		return parent::delete();
	}
	
	###############
	### Factory ###
	###############
	public static function filesDir()
	{
		return GWF_PATH . 'dbimg/files/';
	}
	
	/**
	 * @param array $values
	 * @return GWF_File
	 */
	public static function fromForm(array $values)
	{
		return self::blank(array(
			'file_name' => $values['name'],
			'file_size' => $values['size'],
			'file_type' => $values['mime']
		))->tempPath($values['path']);
	}
	
	public function copy()
	{
		$this->insert();
		copy($this->path, $this->getDestPath());
		$this->path = null;
		return $this;
	}
	
	/**
	 * @param string $contents
	 * @return GWF_File
	 */
	public static function fromString(string $name, string $content)
	{
		# Create temp dir
		$tempDir = GWF_PATH . 'temp/file';
		GWF_File::createDir($tempDir);
		# Copy content to temp file
		$tempPath = $tempDir . '/' . md5(md5($name).md5($content));
		file_put_contents($tempPath, $content);
		# Return fresh GWF_File.
		$values = array(
			'name' => $name,
			'size' => strlen($content),
			'mime' => mime_content_type($tempPath),
			'path' => $tempPath,
		);
		return self::fromForm($values);
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
	
	public static function humanFilesize($bytes, $factor='1024', $digits='2')
	{
		$txt = t('filesize');
		$i = 0;
		$rem = '0';
		while (bccomp($bytes, $factor) >= 0)
		{
			$rem = bcmod($bytes, $factor);
			$bytes = bcdiv($bytes, $factor);
			$i++;
		}
		return $i === 0
		? sprintf("%s%s", $bytes, $txt[$i])
		: sprintf("%.0{$digits}f%s", ($bytes+$rem/$factor), $txt[$i]);
	}
}
