<?php
class GWF_Message extends GWF_Response
{
	public function __construct(string $key, array $args=null, $log=true)
	{
		$this->html = t($key, $args);
		if ($log)
		{
			GWF_Log::logMessage(GWF_Trans::tiso('en', $key, $args));
		}
		if (GWF5::instance()->getFormat() !== 'json')
		{
			$this->html = GWF_Template::mainPHP('message.php', ['response'=>$this]);
		}
	}
	
	public static function make($html)
	{
		return new self($html);
	}
	
}
