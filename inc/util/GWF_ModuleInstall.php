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
			$module->setVars(['module_enabled'=>'1', 'module_version'=>'5.00']);
			$module->insert();
			self::upgradeTo($module, '5.00');
		}
		
		while ($module->getVersion() !== $module->module_version)
		{
			self::upgrade($module);
		}
		
		self::installMethods($module);
	}
	
	public static function installModuleClasses(GWF_Module $module)
	{
		if ($classes = $module->getClasses())
		{
			foreach ($module->getClasses() as $class)
			{
				if (is_subclass_of($class, 'GDO'))
				{
					$gdo = $class::table();
					$gdo instanceof GDO;
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
	
	public static function installMethods(GWF_Module $module)
	{
		$dir = $module->filePath('method');
		if (GWF_File::isDir($dir))
		{
			GWF_Filewalker::traverse($dir, array(__CLASS__, 'installMethod'), false, false, $module);
		}
	}
	
	public static function installMethod($entry, $path, GWF_Module $module)
	{
		include $path;
		$method = $module->getMethod(GWF_String::substrTo($entry, '.'));
		if ($permission = $method->getPermission())
		{
			GWF_Permission::create($permission);
		}
	}
}
