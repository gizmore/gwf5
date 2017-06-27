<?php
/**
 * GWF Template Engine.
 * Very cheap / basic
 * 
 * There are 4 types of template.
 * 
 * mainPHP => php template is in /theme folder
 * mainFile => raw file template is in /theme folder
 * modulePHP => php template is in the according module/xxx/tpl folder 
 * moduleFile => raw file template is in the according module/xxx/tpl folder
 * 
 * Module templates can be overriden by theme/module/xxx/tpl/ folder
 * 
 * Themes have a parent theme, so one level of inheritance is possible.
 *  
 * @author gizmore
 * @version 5.0
 * @since 1.0
 */
final class GWF_Template
{
	protected static $MODULE_FILE = NULL; # ouch global temp var
	
	public static function getDesign() { return GWF5::instance()->getTheme(); }
	private static function pathError(string $path) { return GWF_Error::error('err_file', array(htmlspecialchars(str_replace('%DESIGN%', 'default', $path)))); }
	
	#####################
	### PHP Templates ###
	#####################
	/**
	 * PHP Template inside the main theme folder.
	 * @param string $file
	 * @param array $tVars
	 * @return GWF_Response
	 */
	public static function mainPHP(string $file, array $tVars=null)
	{
		return self::php("theme/%DESIGN%/$file", $tVars);
	}
	
	/**
	 * Template inside modules, or overriden in theme.
	 * 
	 * @param string $moduleName
	 * @param string $file
	 * @param array $tVars
	 * @return GWF_Response
	 */
	public static function modulePHP(string $moduleName, string $file, array $tVars=null)
	{
		self::$MODULE_FILE = $file;
		$path = GWF_PATH . 'themes/%DESIGN%/module/' . $moduleName . '/' . self::$MODULE_FILE;
		return self::php($path, $tVars, $moduleName);
	}
	
	/**
	 * Get a PHP Template output
	 * @param $path path to template file
	 * @return GWF_Response
	 */
	private static function php(string $path, array $tVars=null, string $moduleName=null)
	{
		if (!($path2 = self::getPath($path, $moduleName)))
		{
			return self::pathError($path);
		}
		if ($tVars)
		{
			foreach ($tVars as $__key => $__value)
			{
				$$__key = $__value; # make tVars locals for the template
			}
		}
		try
		{
			ob_start();
			include $path2;
			return GWF_Response::make(ob_get_contents());
		}
		catch (Exception $e)
		{
			return GWF_Response::make(ob_get_contents());
// 			throw $e;
		}
		finally
		{
			ob_end_clean();
		}
	}
	
	########################
	### Static Templates ###
	########################
	/**
	 * Static file inside the main theme folder.
	 * @param string $file
	 * @param array $tVars
	 * @return string
	 */
	public static function mainFile(string $file)
	{
		return self::file("theme/%DESIGN%/$file");
	}
	
	/**
	 * Static file inside modules, or overriden in theme.
	 * @param string $moduleName
	 * @param string $file
	 * @param array $tVars
	 * @return string
	 */
	public static function moduleFile(string $moduleName, string $file)
	{
		self::$MODULE_FILE = $file;
		$path = GWF_PATH . 'themes/%DESIGN%/module/' . $moduleName . '/' . self::$MODULE_FILE;
		return self::file($path, $moduleName);
	}
	
	/**
	 * Get file contents of a file inside template dir hierarchy
	 * @param string $path path to template file
	 * @return string
	 */
	private static function file(string $path, string $moduleName=null)
	{
		if (!($path2 = self::getPath($path, $moduleName)))
		{
			return self::pathError($path);
		}
		return new GWF_Response(file_get_contents($path2));
	}
	
	#########################
	### Path substitution ###
	#########################
	/**
	 * Get the Path for the GWF Design if the file exists
	 * @param string $path templatepath
	 * @return string
	 */
	private static function getPath(string $path, string $moduleName=null)
	{
		// Try custom theme first.
		$path1 = str_replace('%DESIGN%', self::getDesign(), $path);
		if (is_file($path1))
		{
			return $path1;
		}
		
		// Try module file on module templates.
		if ($moduleName)
		{
			$path1 = GWF_PATH.'module/'.$moduleName.'/tpl/'.self::$MODULE_FILE;
		}
		else // or default theme on parent template.
		{
			$path1 = str_replace('%DESIGN%', GWF_PARENT_THEME, $path);
		}
		
		return is_file($path1) ? $path1 : null;
	}
}
