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
	private static function pathError($path) { return GWF_Response::error('err_file', array(htmlspecialchars(str_replace('%DESIGN%', 'default', $path)))); }
	
	/**
	 * Get template from theme folder.
	 * @param string $file
	 * @param array $tVars
	 * @return string
	 */
	public static function templateMain($file, $tVars=NULL)
	{
		return self::templatePHP(GWF_PATH.'theme/%DESIGN%/'.$file, $tVars);
	}
	
	/**
	 * Get a PHP Template output
	 * @param $path path to template file
	 * @return string
	 */
	private static function templatePHP($path, $tVars=NULL, $moduleName=NULL)
	{
		if (!($path2 = self::getPath($path, $moduleName)))
		{
			return self::pathError($path);
		}
		if (is_array($tVars))
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
	
	public static function moduleTemplatePHP($moduleName, $file, $tVars=NULL)
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
	private static function getPath($path, $moduleName=NULL)
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

