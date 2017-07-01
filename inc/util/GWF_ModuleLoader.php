<?php
/**
 * Module loader.
 * 
 * @author gizmore
 * @version 5.0
 * @since 1.0
 */
final class GWF_ModuleLoader
{
	/**
	 * Base modules path, the modules folder.
	 * @var string
	 */
	private $path;
	
	/**
	 * @var GWF_Module[]
	 */
	private $modules = [];
	
	/**
	 * @var GWF_Module[]
	 */
	private $activeModules = [];
	
	private $loadedFS = false;
	
	public function __construct(string $path)
	{
		$this->path = $path;
	}
	
	/**
	 * Get all currently loaded modules.
	 * @return GWF_Module[]
	 */
	public function getModules()
	{
		return $this->modules;
	}
	
		/**
	 * Get all currently loaded modules.
	 * @return GWF_Module[]
	 */
	public function getActiveModules()
	{
		return $this->activeModules;
	}
	
	/**
	 * @param string $moduleName
	 * @return GWF_Module
	 */
	public function getModule(string $moduleName)
	{
		return @$this->modules[$moduleName];
	}
	
	/**
	 * Load active modules, preferably from cache.
	 * Sorted by priority to be spinlock free.
	 * @return GWF_Module[]
	 */
	public function loadModulesCache()
	{
		if (!($cache = GDOCache::get('gwf_modules')))
		{
			$cache = $this->loadModules();
			GDOCache::set('gwf_modules', $cache);
		}
		else
		{
			$this->initFromCache($cache);
		}
		
		return $this->modules;
	}
	
	private function initFromCache(array $cache)
	{
		$this->modules = $cache;
		$this->activeModules = $cache;
// 		uasort($this->modules, function($a, $b){return $a->module_priority - $b->module_priority; });
		foreach ($this->modules as $module)
		{
			$module->initModule();
		}
// 		$this->initModuleVars();
	}
	
	public function loadModules($loadDBOnly = true)
	{
		$loaded = false;
		if (count($this->activeModules) === 0)
		{
			$this->loadModulesDB();
			$loaded = true;
		}
		if (!$loadDBOnly)
		{
			if (!$this->loadedFS)
			{
				$this->loadModulesFS();
				$this->loadedFS = $loaded = true;
			}
		}
		if ($loaded)
		{
			uasort($this->modules, function($a, $b){ return $a->module_priority - $b->module_priority; });
			GDO::sort($this->activeModules, 'module_sort');
			$this->initModuleVars();
			foreach ($this->modules as $module)
			{
				$module->initModule();
			}
		}
		return $this->modules;
	}
	
	public function loadModulesDB()
	{
		$result = GWF_Module::table()->select('*')->where('module_enabled')->order('module_priority')->exec();
		while ($moduleData = $result->fetchAssoc())
		{
			$moduleName = $moduleData['module_name'];
			if (!isset($this->modules[$moduleName]))
			{
				include(GWF_PATH . 'module/'. $moduleName .'/Module_' . $moduleName. '.php');
				if ($module = self::instanciate($moduleData))
				{
					$this->activeModules[$moduleName] = $this->modules[$moduleName] = $module->setPersisted(true);
				}
			}
		}
		return $this->modules;
	}

	public function loadModulesFS()
	{
		GWF_Filewalker::traverse($this->path, false, array($this, 'loadModuleFS'), false);
	}
	
	public function loadModuleFS($entry, $path)
	{
		if (!isset($this->modules[$entry]))
		{
			$path = $path . "/Module_$entry.php";
			if (GWF_File::isFile($path))
			{
				require $path;
				$moduleData = GWF_Module::table()->blankData();
				$moduleData['module_name'] = $entry;
				if ($module = self::instanciate($moduleData, true))
				{
					$this->modules[$entry] = $module;
				}
			}
		}
	}
	
	public static function instanciate(array $moduleData, $dirty = null)
	{
		$klass = 'Module_' . $moduleData['module_name'];
		$instance = new $klass();
		$instance instanceof GWF_Module;
		$instance->setGDOVars($moduleData, $dirty);
// 		$instance->initModule();
		return $instance;
	}

	############
	### Vars ###
	############
	public function initModuleVars()
	{
		$result = GWF_ModuleVar::table()->select('module_name, mv_name, mv_value')->join('LEFT JOIN gwf_module ON module_id=mv_module_id')->exec();
		while ($row = $result->fetchRow())
		{
			if ($module = @$this->modules[$row[0]])
			{
				if ($var = $module->getConfigColumn($row[1]))
				{
					$var->value($row[2]);
				}
			}
		}
		
	}
	
	public function sortModules(string $columnName, bool $ascending=true)
	{
		return GDO::sort($this->modules, $columnName, $ascending);
	}
	
	
}
