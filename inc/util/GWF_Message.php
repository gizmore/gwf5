<?php
class GWF_Message extends GWF_Response
{
	public function __construct(string $key, array $args=null, $log=true)
	{
		$this->html = GWF_Trans::t($key, $args);
		if ($log)
		{
			GWF_Log::logMessage(GWF_Trans::tiso('en', $key, $args));
		}
		$this->html = GWF_Template::templateMain('message.php', ['response'=>$this]);
	}
	
}
