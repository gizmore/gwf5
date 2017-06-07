<?php
final class GWF_TopMenu
{
	use GWF_Fields;
	
	private static $INSTANCE;
	
	/**
	 * @return self
	 */
	public static function instance()
	{
		if (!self::$INSTANCE)
		{
			self::$INSTANCE = new self();
		}
		return self::$INSTANCE;
	}
	
	public function render()
	{
		foreach (GWF5::instance()->getActiveModules() as $module)
		{
			$module->onLoadTopMenu($this);
		}
		$tVars = array(
			'topmenu' => $this,
			'fields' => $this->getFields(),
		);
		return GWF_Template::templateMain('topmenu.php', $tVars);
	}
}
