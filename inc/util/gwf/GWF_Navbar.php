<?php
final class GWF_Navbar
{
	use GWF_Fields;
	
	const LEFT = 1;
	const RIGHT = 2;
	const TOP = 3;
	const BOTTOM = 4;
	
	const ROW = 'row';
	const COLUMN = 'column';
	
	public function isLeft() { return $this->position === self::LEFT; }
	public function isRight() { return $this->position === self::RIGHT; }
	public function isTop() { return $this->position === self::TOP; }
	public function isBottom() { return $this->position === self::BOTTOM; }
	
	###############
	### Factory ###
	###############
	/**
	 * Convinience wrapper
	 * @param string $position
	 * @return GWF_Navbar
	 */
	public static function create(int $position=10, string $direction=self::ROW) { return new self($position, $direction); }
	public static function left() { return self::create(self::LEFT, self::COLUMN); }
	public static function right() { return self::create(self::RIGHT, self::COLUMN); }
	public static function top() { return self::create(self::TOP, self::ROW); }
	public static function bottom() { return self::create(self::BOTTOM, self::ROW); }
	
	private $position;
	public function position() { return $this->position; }
	
	private $direction;
	public function direction() { return $this->direction; }
	
	
	public function __construct(int $position, string $direction)
	{
		$this->position = $position;
		$this->direction = $direction;
	}
	
	public function render()
	{
		foreach (GWF5::instance()->getActiveModules() as $module)
		{
			$module->onRenderFor($this);
		}
		return GDO_Bar::make('gwfnavbar')->addFields($this->getFields())->direction($this->direction)->renderCell();
	}
}
