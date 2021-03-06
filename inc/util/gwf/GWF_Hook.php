<?php
/**
 * Hooks do not render any output.
 * Hooks add messages to the IPC queue 1, which are/can be consumed by the websocket server.
 * 
 * Hooks follow this convetions.
 * 1) The hook name is camel-case, e.g: 'UserAuthenticated'.
 * 2) The hook name shall include the module name, e.g. LoginSuccess
 * 
 * @see Module_Websocket
 * 
 * @todo Find a way to generate hook lists for senders and receivers. Maybe reflection for receiver and grep for sender
 * 
 * @author gizmore
 * @version 5.0
 * @since 3.0
 */
final class GWF_Hook
{
	/**
	 * Simply try to call a function on all active modules.
	 * As on gwf5 all modules are always loaded, there is not much logic involved.
	 * 
	 * @param string $event
	 * @param array $args
	 */
	public static function call(string $event, ...$args)
	{
		$method_name = "hook$event";
		foreach (GWF5::instance()->getActiveModules() as $module)
		{
			if (method_exists($module, $method_name))
			{
				call_user_func([$module, $method_name], ...$args);
			}
		}
		
		# Call IPC hooks
		if (GWF_IPC && (!GWF5::instance()->isInstall()))
		{
    		if ($ipc = self::ipc())
    		{
    			self::callIPC($ipc, $event, $args);
    		}
		}
	}

	###########
	### IPC ###
	###########
	private static $ipc;
	public static function ipc()
	{
		if (!isset(self::$ipc))
		{
			self::$ipc = msg_get_queue(1);
		}
		return self::$ipc;
	}
	
	private static function callIPC($ipc, string $event, array $args=null)
	{
		# Map GDO Objects to IDs.
		# The IPC Service will refetch the Objects on their end.
		if ($args)
		{
			foreach ($args as $k => $arg)
			{
				if ($arg instanceof GDO)
				{
					$args[$k] = $arg->getID();
				}
				elseif ($arg instanceof GWF_Form)
				{
					return; # SKIP GWF_Form hooks, as they enrich forms only,
					        # which is not required on websocket IPC channels.
				}
			}
		}
		
		# Send to IPC
		msg_send($ipc, 1, [$event, $args]);
	}
}
