<?php
/**
 * Hooks do not render any output.
 * Hooks add messages to the IPC queue 1.
 * 
 * Hooks follow this convetions.
 * 1) The hook name is camel-case, e.g: 'UserAuthenticated'.
 * 2) The hook name shalle include the module name, e.g. LoginSuccess
 * 
 * @see Module_Websocket
 * 
 * @author gizmore
 * @since 3.0
 * @version 5.0
 */
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
			self::callIPC($ipc);
		}
	}
	
	private static function callIPC($ipc)
	{
		foreach ($args as $k => $arg)
		{
			if ($arg instanceof GDO)
			{
				$args[$k] = $arg->getID();
			}
		}
		msg_send($ipc, 1, [$event, $args]);
	}
}
