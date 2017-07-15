<?php
class GDO_Icon extends GDO_Button
{
	public $foreground = '#00000';
	public function foreground(string $color) { $this->foreground = $color; return $this; }
	
	public $background = '#fffff';
	public function background(string $color) { $this->background = $color; return $this; }
	
	public $width = 48;
	public function width(int $width) { $this->width = $width; return $this; }
	
	public $height = 48;
	public function height(int $height) { $this->height = $height; return $this; }

}
