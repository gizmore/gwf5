<?php
define('GWF_CORE_VERSION', '5.00');
# Core
require 'inc/gdo5/GDO.php';
require 'inc/util/Common.php';
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
	
	public function getFormat() { return Common::getRequestString('fmt', 'html'); }
	public function isHTML() { return $this->getFormat() === 'html'; }
	public function isJSON() { return $this->getFormat() === 'json'; }
	public function isAjax() { return isset($_REQUEST['ajax']); }
	
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
			$filename = $name[1] === 'D' ? "inc/util/gdo/$name.php" : "inc/util/$name.php";
			@include $filename;
		});
		
		GWF_Trans::addPath(GWF_PATH . 'inc/lang/util');

		$this->moduleLoader = new GWF_ModuleLoader(GWF_PATH . 'modules/');
	}
	
	public function __destruct()
	{
		GWF_Session::commit();
		GWF_Log::flush();
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
	public function getModule($moduleName=true)
	{
		$moduleName = $moduleName === true ? Common::getGetString('mo', GWF_MODULE) : $moduleName;
		return $this->moduleLoader->getModule($moduleName);
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
	
	
	public function render(GWF_Method $method, GWF_Response $response)
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
					echo $response->__toString();
				}
				else
				{
					$tVars = array(
						'response' => $this->response($response),
						'method' => $method,
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


function href($moduleName, $methodName, $append='')
{
	return GWF5::instance()->getMethodHREF($moduleName, $methodName, $append);
}

function url($moduleName, $methodName, $append='')
{
	return GWF_Url::absolute(href($moduleName, $methodName, $append));
}
