<?php
/**
 * Create and display a captcha.
 * 
 * @author gizmore
 * @author spaceone
 * 
 * @since 2.0
 * @version 5.0
 */
class Captcha_Image extends GWF_Method
{
	public function execute() 
	{
		# Load the Captcha class
		$module = Module_Captcha::instance();
		include $module->filePath('vendor/Class_Captcha.php');
		
		# disable HTTP Caching
		GWF_HTTP::noCache();
		
		# Setup Font, Color, Size
		$aFonts = json_decode($module->cfgCaptchaFonts());
		$aFonts = array_map(function($font){ return GWF_PATH . 'inc/fonts/'.$font; }, $aFonts);
		$rgbcolor = ltrim($module->cfgCaptchaBG(), '#');
		$width = $module->cfgCaptchaWidth();
		$height = $module->cfgCaptchaHeight();
		$oVisualCaptcha = new PhpCaptcha($aFonts, $width, $height, $rgbcolor);
		
		return new GWF_Response($oVisualCaptcha->Create('', GWF_Session::get('php_lock_captcha', true)));
	}
}
