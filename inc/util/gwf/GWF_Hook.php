<?php
final class GWF_Hook
{
	private static $ipc;
	public static function ipc()
	{
		if (!isset(self::$ipc))
		{
			self::$ipc = msg_get_queue(1);
		}
		return self::$ipc;
	}
	
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
		
		if ($ipc = self::ipc())
		{
			msg_send($ipc, 1, [$event, $args]);
		}
	}
}
