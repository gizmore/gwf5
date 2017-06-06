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
	protected function getConfig() { return array(); }

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
	public function onLoad() {}
	public function onLoadLanguage() {}
	public function onInit() {}
	
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
			GDO_Char::make('module_version')->notNull()->initial('5.00')->label('module_version')->size(4),
			GDO_Bool::make('module_enabled')->notNull()->initial('1')->label('module_enabled'),
// 			GDO_Many::make('module_vars')->klass('GWF_ModuleVar')->manyOn('LEFT JOIN gwf_module ON module_id=mv_module_id'),
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
	
	###############
	### Display ###
	###############
	public function display_css()
	{
		return 'gwf-module-update';
	}
	public function display_fs_version() { return $this->module_version; }
	
	
	##############
	### Helper ###
	##############
	public function canUpdate() { return $this->module_version != $this->getVersion(); }
	public function canInstall() { return !$this->isPersisted(); }
	public function filePath(string $path='') { return GWF_PATH . 'modules/' . $this->getName() . '/' . $path; }
	public function includeClass(string $class) { require $this->filePath("$class.php"); }
	public function template(string $file, array $tVars=null) { return GWF_Template::moduleTemplatePHP($this->getName(), $file, $tVars); }
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
		require $this->filePath("method/$methodName.php");
		$klass = $this->getName() . '_' . $methodName;
		return new $klass($this);
	}
	
	
}
