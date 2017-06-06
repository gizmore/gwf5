<?php
class GDO_String extends GDOType
{
	const UTF8 = 1;
	const ASCII = 2;
	const BINARY = 3;
	
	public $pattern;
	public $encoding = self::UTF8;
	public $caseSensitive = false;
	
	public $min = 0;
	public $max = 255;
	
	public function utf8() { return $this->encoding(self::UTF8); }
	public function ascii() { return $this->encoding(self::ASCII); }
	public function binary() { return $this->encoding(self::BINARY); }
	public function isBinary() { return $this->encoding === self::BINARY; }

	public function encoding(int $encoding) { $this->encoding = $encoding; return $this; }
	
	public function pattern(string $pattern) { $this->pattern = $pattern; return $this; }
	public function htmlPattern() { return $this->pattern ? " pattern=\"{$this->pattern}\"" : ''; }
	
	public function caseI(bool $caseInsensitive=true) { return $this->caseS(!$caseInsensitive); }
	public function caseS(bool $caseSensitive=true) { $this->caseSensitive = $caseSensitive; return $this; }
	
	public function min($min) { $this->min = $min; return $this; }
	public function max($max) { $this->max = $max; return $this; }
	
	public function gdoColumnDefine()
	{
		$charset = $this->gdoCharsetDefine();
		$collate = $this->gdoCollateDefine();
		return "{$this->identifier()} VARCHAR({$this->max}) CHARSET $charset COLLATE $collate{$this->gdoNullDefine()}";
	}
	
	public function gdoCharsetDefine()
	{
		switch ($this->encoding)
		{
			case self::UTF8: return 'utf8';
			case self::ASCII: return 'ascii';
			case self::BINARY: return 'binary';
		}
	}
	
	public function gdoCollateDefine()
	{
		$append = $this->isBinary() ? '' : ($this->caseSensitive ? '_bin' : '_general_ci');
		return $this->gdoCharsetDefine() . $append;
	}

	public function render()
	{
		$tVars = array(
			'field' => $this,
		);
		return GWF_Template::templateMain('form/string.php', $tVars);
	}
	
	public function formValue()
	{
		$value = parent::formValue();
		return $value ? GWF_String::utf8($value) : $value;
	}
	
	public function validate($value)
	{
		$len = mb_strlen($value);
		if ( ($this->min !== null) && ($len < $this->min) )
		{
			return $this->strlenError();
		}
		if ( ($this->max !== null) && ($len > $this->max) )
		{
			return $this->strlenError();
		}
		if ( ($this->pattern !== null) && (!preg_match($this->pattern, $value)) )
		{
			return $this->patternError();
		}
		return true;
	}

	private function patternError()
	{
		return $this->error('err_string_pattern');
	}

	private function strlenError()
	{
		if ( ($this->max !== null) && ($this->min !== null) )
		{
			return $this->error('err_strlen_between', [$this->min, $this->max]);
		}
		elseif ($this->max !== null)
		{
			return $this->error('err_strlen_too_large', [$this->max]);
		}
		elseif ($this->min !== null)
		{
			return $this->error('err_strlen_too_small', [$this->min]);
		}
	}
}