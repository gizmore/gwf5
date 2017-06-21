<?php
final class GWF_Stream
{
	public static function path(string $path)
	{
		$out = false;
		if (ob_get_level()>0)
		{
			$out = ob_end_clean();
		}
		$result = self::_path($path);
		if ($out !== false)
		{
			ob_start();
			echo $out;
		}
		return $result;
	}
	
	public static function _path(string $path)
	{
		if ($fh = fopen($path, 'rb'))
		{
			while (!feof($fh))
			{
				echo fread($fh, 1024*1024);
				flush();
			}
			fclose($fh);
			return true;
		}
		return false;
	}
	
	public static function file(GWF_File $file)
	{
		self::path($file->getPath());
		
	}
	
	public static function serve(GWF_File $file)
	{
		header('Content-Type: '.$file->getType());
		header('Content-Size: '.$file->getSize());
		header('Content-Disposition: attachment; filename="'.htmlspecialchars($file->getName()).'"');
		self::file($file);
		die();
	}
}
