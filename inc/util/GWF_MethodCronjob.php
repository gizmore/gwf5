<?php
/**
 * Baseclass method for a cronjob.
 * 
 * @author gizmore
 * @since 5.0
 *
 */
abstract class GWF_MethodCronjob extends GWF_Method
{
	public function getPermission() { return 'cronjob'; }
	public function execute()
	{
		$this->start();
		$this->run();
		$this->end();
	}
	
	public abstract function run();

	###########
	### Log ###
	###########
	public function start() { GWF_Log::logCron('[START] '.get_called_class()); }
	public function end() { GWF_Log::logCron('[DONE] '.get_called_class().PHP_EOL); }

	public function log(string $msg) { GWF_Log::logCron('[+] '.$msg); }
	public function error(string $msg) { GWF_Log::logCron('[ERROR] '.$msg); return false; }
	public function warning(string $msg) { GWF_Log::logCron('[WARNING] '.$msg); }
	public function notice(string $msg) { GWF_Log::logCron('[NOTICE] '.$msg); }
}
