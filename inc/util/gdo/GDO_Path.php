<?php
class GDO_Path extends GDO_String
{
	public $pattern = "#^[^\\?/:]+$#";
	
	public function htmlClass()
	{
		return GWF_File::isFile($this->getValue()) ? ' class="gwf-file-valid"' : ' class="gwf-file-invalid"';
	}
	
	public $existing = false;
	public function existingFile()
	{
		$this->existing = 'is_file';
		return $this;
	}
	
	public function existingDir(bool $existing)
	{
		$this->existing = 'is_dir';
		return $this;
	}
	
	public function validate($value)
	{
		return parent::validate($value) && $this->validatePath($value);
	}
	
	public function validatePath($filename)
	{
		if ($this->existing)
		{
			if ( (!is_readable($filename)) || (!call_user_func($this->existing, $filename)) )
			{
				return $this->error('err_path_not_exists', [$filename, $this->existing]);
			}
		}
		return true;
	}

	public function defaultLabel() { return $this->label('path'); }
}
