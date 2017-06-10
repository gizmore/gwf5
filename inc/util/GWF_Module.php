<?php
/**
 * GWF base module class.
 * 
 * @author gizmore
 * @version 5.0
 * @since 1.0
 */
class GWF_Module extends GDO
{
	################
	### Override ###
	################
	public $module_version = "5.00";
	public $module_author = "Christian Busch <gizmore@wechall.net>";
	public $module_license = "MIT";

	/**
	 * @return string[]
	 */
	public function getClasses() {}
	
	/**
	 * @return GDOType[]
	 */
	protected function getConfig() { return []; }
	
	public function getModuleConfig()
	{
		return array_merge($this->getDefaultConfig(), $this->getConfig());
	}
	
	public function getDefaultConfig()
	{
		return array(
		);
	}

	##############
	### Config ###
	##############
	public function getConfigCache()
	{
		static $config;
		if (!$config)
		{
			$config = $this->getConfig();
		}
		return $config;
	}
	
	public function getConfigColumn(string $key)
	{
		foreach ($this->getConfigCache() as $gdoType)
		{
			if ($gdoType->name === $key)
			{
				return $gdoType;
			}
		}
	}
	
	public function getConfigVar(string $key)
	{
		return $this->getConfigColumn($key)->getValue();
	}
	
	public function getConfigValue(string $key)
	{
		return $this->getConfigColumn($key)->getGDOValue();
	}
	
	##############
	### Events ###
	##############
	public function onInit() {}
	public function onLoad() {}
	public function onRenderFor(GWF_Navbar $navbar) {}
	public function onLoadLanguage() {}
	public function onIncludeScripts() {}
	
	###########
	### GDO ###
	###########
	public function gdoColumnsCache() { return GDODB::columnsS('GWF_Module'); } # Polymorph fix
	public function gdoTableName() { return "gwf_module"; }
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('module_id'),
			GDO_Name::make('module_name')->notNull()->unique(),
			GDO_Sort::make('module_priority')->notNull()->initial('50'),
			GDO_Char::make('module_version')->notNull()->initial('0.00')->size(4),
			GDO_Bool::make('module_enabled')->notNull()->initial('0'),
		);
	}
	
	###############
	### Static ###
	##############
	/**
	 * @return self
	 */
	public static function instance() { return GWF5::instance()->getModule(self::getNameS()); }
	public static function getNameS() { return GWF_String::substrFrom(get_called_class(), 'Module_'); }
	
	##############
	### Getter ###
	##############
	public function getID() { return $this->getVar('module_id'); }
	public function getName() { return $this->getVar('module_name'); }
	public function getVersion() { return $this->getVar('module_version'); }
	public function isEnabled() { return $this->getVar('module_enabled') === '1'; }
	public function isCoreModule() { return false; }
	
	###############
	### Display ###
	###############
	public function render_fs_version() { return $this->module_version; }
		
	
	############
	### Href ###
	############
	public function href_install_module() { return href('Admin', 'Install', '&module='.$this->getName()); }
	public function href_configure_module() { return href('Admin', 'Configure', '&module='.$this->getName()); }
	public function href_administrate_module() {}
	
	##############
	### Helper ###
	##############
	public function canUpdate() { return $this->module_version != $this->getVersion(); }
	public function canInstall() { return !$this->isPersisted(); }
	public function filePath(string $path='') { return GWF_PATH . 'modules/' . $this->getName() . '/' . $path; }
	public function wwwPath(string $path='') { return '/modules/' . $this->getName() . '/' . $path; }
	public function includeClass(string $class) { require $this->filePath("$class.php"); }
	
	#################
	### Templates ###
	#################
	/**
	 * @param string $file
	 * @param array $tVars
	 * @return GWF_Response
	 */
	public function templatePHP(string $file, array $tVars=null)
	{
		switch (GWF5::instance()->getFormat())
		{
			case 'json': return new GWF_Response($tVars);
			case 'html': default: return GWF_Template::modulePHP($this->getName(), $file, $tVars);
		}
	}
	public function templateFile(string $file) { return GWF_Template::moduleFile($this->getName(), $file); }
	public function error(string $key, array $args=null) { return new GWF_Error($key, $args); }
	public function message(string $key, array $args=null) { return new GWF_Message($key, $args); }
	
	############
	### Init ###
	############
	public function initModule()
	{
		if ($classes = $this->getClasses())
		{
			foreach ($classes as $class)
			{
				$this->includeClass($class);
			}
		}
		$this->onLoadLanguage();
		$this->onInit();
	}
	
	public function loadLanguage(string $path)
	{
		GWF_Trans::addPath($this->filePath($path));
		return $this;
	}
	
	##############
	### Method ###
	##############
	/**
	 * @param string $methodName
	 * @return GWF_Method
	 */
	public function getMethod(string $methodName)
	{
		
		$klass = $this->getName() . '_' . $methodName;
		
		if (!class_exists($klass, false))
		{
			include $this->filePath("method/$methodName.php");
		}
		
		return new $klass($this);
	}
	
	public function getMethodHREF(string $methodName)
	{
		return GWF_Url::relative(sprintf('/index.php?mo=%s&me=%s', $this->getName(), $methodName));
	}
	
	##################
	### Javascript ###
	##################
	public function addJavascript(string $path)
	{
		return GWF_Javascript::addJavascript($this->wwwPath($path));
	}
	
	public function addCSS(string $path)
	{
		return GWF_Website::addCSS($this->wwwPath($path));
	}
}
