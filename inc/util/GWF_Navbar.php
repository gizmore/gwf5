<?php
final class GWF_Navbar
{
	use GWF_Fields;
	
	const LEFT = 1;
	const RIGHT = 2;
	const TOP = 3;
	const BOTTOM = 4;
	
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
	public static function create(int $position, string $direction) { return new self($position, $direction); }
	public static function left() { return self::create(self::LEFT, 'column'); }
	public static function right() { return self::create(self::RIGHT, 'column'); }
	public static function top() { return self::create(self::TOP, 'row'); }
	public static function bottom() { return self::create(self::BOTTOM, 'row'); }
	
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
		$tVars = array(
			'navbar' => $this,
			'fields' => $this->getFields(),
		);
		return GWF_Template::mainPHP('navbar.php', $tVars);
	}
}
