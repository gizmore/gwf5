<?php
require 'inc/gdo5/GDO.php';
require 'inc/util/Common.php';

final class GWF5
{
	/**
	 * @var GWF5
	 */
	private static $INSTANCE;

	public static function instance() { return self::$INSTANCE; }
	
	private $response;
	
	public static function getFormat() { return 'html'; }

	/**
	 * @var GWF_ModuleLoader
	 */
	private $moduleLoader;
	
	public function __construct()
	{
		self::$INSTANCE = $this;
		
		define('GWF_PATH', dirname(__FILE__, 2) . '/');

		spl_autoload_register(function($name) {
			if (mb_strstr($name, 'GDO_') !== false)
			{
				$filename = GWF_PATH . "inc/util/gdo/$name.php";
			}
			else
			{
				$filename = GWF_PATH . "inc/util/$name.php";
			}
			if (is_file($filename))
			{
				include $filename;
			}
		});
		
		GWF_Trans::addPath(GWF_PATH . 'inc/lang/util');

		$this->moduleLoader = new GWF_ModuleLoader(GWF_PATH . 'modules/');
	}
	
	public function loadModules($loadDBOnly = true)
	{
		return $this->moduleLoader->loadModules($loadDBOnly);
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
	
	public function render(GWF_Method $method, GWF_Response $response)
	{
		$this->response($response);
		$tVars = array(
			'response' => $this->response,
			'method' => $method,
		);
		return GWF_Template::templateMain('index.php', $tVars);
	}
	
	public function renderBlank()
	{
		return $this->response ? $this->response->__toString() : '';
	}
	
	public function response(GWF_Response $response)
	{
		$this->response = $this->response ? $this->response->add($response) : $response;
		return $this;
	}
}
