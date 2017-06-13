<?php
final class GWF_Hook
{
	public static function call(string $event, array $args=null)
	{
		$method_name = "hook$event";
		foreach (GWF5::instance()->getActiveModules() as $module)
		{
			if (method_exists($module, $method_name))
			{
				call_user_func([$module, $method_name], $args);
			}
		}
	}
}
