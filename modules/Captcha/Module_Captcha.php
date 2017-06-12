<?php
final class Module_Captcha extends GWF_Module
{
	public function getConfig()
	{
		return array(
			GDO_Font::make('captcha_font')->initial('["arial.ttf"]')->multiple()->minSelected(1)->notNull(),
			GDO_Color::make('captcha_bg')->initial('#612612')->notNull(),
			GDO_Int::make('captcha_width')->initial('164')->min(48)->max(512)->notNull(),
			GDO_Int::make('captcha_height')->initial('48')->min(24)->max(256)->notNull(),
		);
	}
	public function cfgCaptchaFonts() { return $this->getConfigValue('captcha_font'); }
	public function cfgCaptchaBG() { return $this->getConfigValue('captcha_bg'); }
	public function cfgCaptchaWidth() { return $this->getConfigValue('captcha_width'); }
	public function cfgCaptchaHeight() { return $this->getConfigValue('captcha_height'); }
}
