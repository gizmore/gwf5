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
			$module->setVars(['module_enabled'=>'1', 'module_version'=>'5.00', 'module_priority' => $module->module_priority]);
			$module->insert();
			self::upgradeTo($module, '5.00');
		}
		
		while ($module->getVersion() !== $module->module_version)
		{
			self::upgrade($module);
		}
		
		self::installMethods($module);

		$module->onInstall();
		
		GDOCache::unset('gwf_modules');
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
					if (!$gdo->gdoAbstract())
					{
						$gdo->createTable();
					}
				}
			}
		}
	}
	
	public static function dropModule(GWF_Module $module)
	{
		$module->onWipe();
		self::dropModuleClasses($module);
		$module->delete();
		GDOCache::unset('gwf_modules');
	}

	public static function dropModuleClasses(GWF_Module $module)
	{
		if ($classes = $module->getClasses())
		{
			foreach (array_reverse($module->getClasses()) as $class)
			{
				if (is_subclass_of($class, 'GDO'))
				{
					$gdo = $class::table();
					$gdo instanceof GDO;
					if (!$gdo->gdoAbstract())
					{
						$gdo->dropTable();
					}
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
		self::loopMethods($module, array(__CLASS__, 'installMethod'));
	}
	
	public static function loopMethods(GWF_Module $module, $callback)
	{
		$dir = $module->filePath('method');
		if (GWF_File::isDir($dir))
		{
			GWF_Filewalker::traverse($dir, $callback, false, false, $module);
		}
	}
	
	/**
	 * Helper to get the method for a method loop.
	 * @param GWF_Module $module
	 * @param string $path
	 * @return GWF_Method
	 */
	public static function loopMethod(GWF_Module $module, string $path)
	{
		$entry = GWF_String::substrTo(basename($path), '.');
		$class_name= $module->getName() . '_' . $entry;
		if (!class_exists($class_name, false))
		{
			include $path;
		}
		return $module->getMethod($entry);
	}
	
	public static function installMethod($entry, $path, GWF_Module $module)
	{
		$method = self::loopMethod($module, $path);
		if ($permission = $method->getPermission())
		{
			GWF_Permission::create($permission);
		}
	}
}
