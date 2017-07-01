<?php
class GWF_Message extends GWF_Response
{
	public function __construct(string $key, array $args=null, bool $log=true)
	{
		if (GWF5::instance()->getFormat() !== 'json')
		{
			$this->html = GWF_Template::mainPHP('message.php', ['response'=>$this, 'message' => t($key, $args)]);
		}
		else
		{
			$this->html = array('message' => $key, 'args' => $args);
		}
		
		if ($log)
		{
			GWF_Log::logMessage(GWF_Trans::tiso('en', $key, $args));
		}
	}
	
	public static function make($html)
	{
		return new self($html);
	}
	
}
