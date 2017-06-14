<?php
/**
 * Convinience cronjob launcher.
 * 
 * @author gizmore
 * @version 5.0
 * 
 * @see GWF_MethodCronjob
 */
final class GWF_Cronjob
{
	public static function run()
	{
		$modules = GWF5::instance()->loadModules();
		foreach ($modules as $module)
		{
			GWF_ModuleInstall::loopMethods($module, [__CLASS__, 'runCronjob']);
		}
	}
	
	public static function runCronjob($entry, $path, $module)
	{
		$method = GWF_ModuleInstall::loopMethod($module, $path);
		if ($method instanceof GWF_MethodCronjob)
		{
			$method->execwrap();
		}
	}
}
