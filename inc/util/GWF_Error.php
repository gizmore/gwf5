<?php
class GWF_Error extends GWF_Response
{
	public function __construct(string $key, array $args=null, bool $log=true)
	{
		$this->html = t($key, $args);
		$this->error = true;
		if ($log)
		{
			GWF_Log::logError(GWF_Trans::tiso('en', $key, $args));
		}
		$this->html = GWF_Template::templateMain('error.php', ['response'=>$this]);
	}
	
}
