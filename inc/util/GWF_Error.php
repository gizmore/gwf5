<?php
class GWF_Error extends GWF_Response
{
	public function __construct(string $key, array $args=null, bool $log=true)
	{
		$this->error = true;

		if (GWF5::instance()->getFormat() !== 'json')
		{
			$this->html = GWF_Template::mainPHP('error.php', ['response'=>$this, 'message' => t($key, $args)]);
		}
		else
		{
			$this->html = array('error' => $key, 'args' => $args);
		}

		if ($log)
		{
			GWF_Log::logError(GWF_Trans::tiso('en', $key, $args));
		}
		
	}
	
	public static function make($html)
	{
		return new self($html);
	}
	
}
