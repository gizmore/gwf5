<?php
/**
 * @author gizmore
 * @version 5.0
 * @since 1.0
 */
final class GWF_Template
{
	protected static $MODULE_FILE = NULL;
	
	public static function getDesign() { return GWF_THEME; }
	private static function pathError(string $path) { return GWF_Response::error('err_file', array(htmlspecialchars(str_replace('%DESIGN%', 'default', $path)))); }
	
	/**
	 * Get template from theme folder.
	 * @param string $file
	 * @param array $tVars
	 * @return string
	 */
	public static function templateMain(string $file, array $tVars=null)
	{
		return self::templatePHP("theme/%DESIGN%/$file", $tVars);
	}
	
	/**
	 * Get a PHP Template output
	 * @param $path path to template file
	 * @return string
	 */
	private static function templatePHP(string $path, array $tVars=null, string $moduleName=null)
	{
		if (!($path2 = self::getPath($path, $moduleName)))
		{
			return self::pathError($path);
		}
		if ($tVars)
		{
			foreach ($tVars as $__key => $__value)
			{
				$$__key = $__value;
			}
		}
		ob_start();
		require $path2;
		$back = ob_get_contents();
		ob_end_clean();
		return new GWF_Response($back);
	}
	
	public static function moduleTemplatePHP(string $moduleName, string $file, array $tVars=null)
	{
		self::$MODULE_FILE = $file;
		$path = GWF_PATH.'themes/%DESIGN%/module/'.$moduleName.'/'.self::$MODULE_FILE;
		return self::templatePHP($path, $tVars, $moduleName);
	}
	
	/**
	 * Get the Path for the GWF Design if the file exists
	 * @param string $path templatepath
	 * @return string|false
	 */
	private static function getPath(string $path, string $moduleName=null)
	{
		// Try custom theme first.
		$path1 = str_replace('%DESIGN%', self::getDesign(), $path);
		if (file_exists($path1))
		{
			return $path1;
		}
		
		// Try module file on module templates.
		if ($moduleName)
		{
			$path1 = GWF_PATH.'modules/'.$moduleName.'/tpl/'.self::$MODULE_FILE;
		}
		else // or default theme on main templates.
		{
			$path1 = str_replace('%DESIGN%', 'default', $path);
		}
		if (file_exists($path1))
		{
			return $path1;
		}
		
		return false;
	}
}

