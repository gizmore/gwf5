<?php
class GWF_ModuleInstall
{
	public static function installModules(array $modules)
	{
		foreach ($modules as $module)
		{
			self::installModule($module);
		}
	}
	
	public static function installModule(GWF_Module $module)
	{
		self::installModuleClasses($module);
		
		if (!$module->isPersisted())
		{
			$module->insert();
			self::upgradeTo($module, $module->getVersion());
		}
		
		while ($module->getVersion() !== $module->module_version)
		{
			self::upgrade($module);
		}
	}
	
	public static function installModuleClasses(GWF_Module $module)
	{
		if ($classes = $module->getClasses())
		{
			foreach ($module->getClasses() as $class)
			{
	// 			$module->includeClass($class);
				if (is_subclass_of($class, 'GDO'))
				{
					$gdo = $class::table();
					$gdo instanceof GDO;
					$gdo->dropTable();
					$gdo->createTable();
				}
			}
		}
	}
	
	public static function upgrade(GWF_Module $module)
	{
		$version = self::increaseVersion($module);
		self::upgradeTo($module, $version);
	}
		
	public static function upgradeTo(GWF_Module $module, $version)
	{
		$upgradeFile = $module->filePath("upgrade/$version.php");
		if (GWF_File::isFile($upgradeFile))
		{
			include($upgradeFile);
		}
	}
	
	public static function increaseVersion(GWF_Module $module)
	{
		$v = (string) (floatval($module->getVersion()) + 0.01);
		$module->saveVar('module_version', $v);
		return $v;
	}
}