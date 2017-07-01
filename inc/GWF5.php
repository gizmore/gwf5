<?php
define('GWF_CORE_VERSION', '5.00');
# Core
require 'inc/gdo5/GDO.php';
require 'inc/util/Common.php';
require 'inc/util/GWF_String.php';
# Traits
require 'inc/util/trait/GWF_Fields.php';

final class GWF5
{
	/**
	 * @var GWF5
	 */
	private static $INSTANCE;

	public static function instance() { return self::$INSTANCE; }
	
	private $response;
	
	public function getTheme() { return GWF_THEME; }
	public function getSiteName() { return t('site_name'); }
	
	public function getFormat() { return Common::getGetString('fmt', 'html'); }
	public function isHTML() { return $this->getFormat() === 'html'; }
	public function isJSON() { return $this->getFormat() === 'json'; }
	public function isAjax() { return isset($_GET['ajax']); }
	public function isInstall() { return defined('GWF_INSTALL'); }
	
	public function isCLI() { return php_sapi_name() === 'cli'; }
	public function isFullPageRequest() { return (!$this->isAjax()) && $this->isHTML(); }
	/**
	 * @var GWF_ModuleLoader
	 */
	private $moduleLoader;
	
	public function __construct()
	{
		self::$INSTANCE = $this;
		
		define('GWF_PATH', dirname(__FILE__, 2) . '/');
		
		spl_autoload_register(function($name) {
			if ($name[0] === 'G')
			{
				$filename = $name[1] === 'D' ? "inc/util/gdo/$name.php" : "inc/util/$name.php";
			}
			elseif ($modulename = GWF_String::substrFrom($name, 'Module_', null))
			{
				$filename = "module/$modulename/$name.php";
			}
			else
			{
				throw new Exception('File not found: ' . htmlspecialchars($name));
			}
			
			if (!@include($filename))
			{
				throw new Exception('File not found: ' . htmlspecialchars($name));
			}
			
		});
		
		GWF_Trans::addPath(GWF_PATH . 'inc/lang/util');
		GWF_Trans::addPath(GWF_PATH . 'inc/lang/country');
		GWF_Trans::addPath(GWF_PATH . 'inc/lang/language');
			
		$this->moduleLoader = new GWF_ModuleLoader(GWF_PATH . 'module/');
	}
	
	public function __destruct()
	{
		$this->finish();
	}
	
	public function finish()
	{
		static $finished;
		if (!isset($finished))
		{
			$finished = true;
			GWF_Session::commit();
			GWF_Log::flush();
		}
	}
	
	public function loadModulesCache()
	{
		# Load by priortiy
		$modules = $this->moduleLoader->loadModulesCache();
		
		# Include JS
		if ($this->isFullPageRequest())
		{
			foreach ($modules as $module)
			{
				$module->onIncludeScripts();
			}
		}
		
		# But sort by sorting column afterwards
		return $this->moduleLoader->sortModules('module_sort');
	}
	
	/**
	 * @param string $loadDBOnly
	 * @return GWF_Module[]
	 */
	public function loadModules($loadDBOnly = true)
	{
		return $this->moduleLoader->loadModules($loadDBOnly);
	}
	
	/**
	 * @return GWF_Module[]
	 */
	public function getActiveModules()
	{
		return $this->moduleLoader->getActiveModules();
	}
	
	/**
	 * @param string $moduleName
	 * @return GWF_Module
	 */
	public function getModule(string $moduleName=null)
	{
		return $moduleName ? $this->moduleLoader->getModule($moduleName) : null;
	}
	
	public function defaultMethod()
	{
		return method(GWF_MODULE, GWF_METHOD);
	}
	
	/**
	 * @param string $moduleName
	 * @param string $methodName
	 * return GWF_Method
	 */
	public function getMethod(string $moduleName, string $methodName)
	{
		return $this->moduleLoader->getModule($moduleName)->getMethod($methodName);
	}
	
	public static function getMethodHREF(string $moduleName, string $methodName, string $append='')
	{
		return sprintf('/index.php?mo=%s&me=%s%s', $moduleName, $methodName, $append);
	}
	
	
	public function render(GWF_Response $response)
	{
		switch ($this->getFormat())
		{
			case 'json':
				header("Content-Type: application/json");
				return $response->toJSON();
			default:
			case 'html':
				if ($this->isAjax())
				{
					return $response->__toString();
				}
				else
				{
					$tVars = array(
						'response' => $this->response($response),
					);
					return GWF_Template::mainPHP('index.php', $tVars);
				}
		}
	}
	
	public function renderBlank()
	{
		return $this->response ? $this->response->__toString() : '';
	}
	
	public function response(GWF_Response $response)
	{
		$this->response = $this->response ? $this->response->add($response) : $response;
		return $this->response;
	}
}

#####################
### Global helper ###
#####################
function href(string $moduleName, string $methodName, string $append='')
{
	return GWF5::instance()->getMethodHREF($moduleName, $methodName, $append);
}

function url(string $moduleName, string $methodName, string $append='')
{
	return GWF_Url::absolute(href($moduleName, $methodName, $append));
}

function method(string $moduleName, string $methodName)
{
	return GWF5::instance()->getModule($moduleName)->getMethod($methodName);
}
